import { createClient } from 'npm:@supabase/supabase-js@2'

const cors = { 'Access-Control-Allow-Origin': '*', 'Access-Control-Allow-Headers': 'authorization, x-client-info, apikey, content-type' }
const json = (b: unknown, s = 200) => new Response(JSON.stringify(b), { status: s, headers: { ...cors, 'Content-Type': 'application/json' } })

Deno.serve(async (req) => {
  if (req.method === 'OPTIONS') return new Response('ok', { headers: cors })
  const url = Deno.env.get('SUPABASE_URL')!

  // 1. Quién es el usuario (por su token)
  const userClient = createClient(url, Deno.env.get('SUPABASE_ANON_KEY')!, { global: { headers: { Authorization: req.headers.get('Authorization') ?? '' } } })
  const { data: { user } } = await userClient.auth.getUser()
  if (!user) return json({ error: 'No autenticado' }, 401)

  const { reserva_id, modo, monto } = await req.json()
  const db = createClient(url, Deno.env.get('SUPABASE_SERVICE_ROLE_KEY')!)

  // 2. Reserva propia
  const { data: r } = await db.from('reservas')
    .select('id, monto_total, monto_pagado, estado, salones(nombre, sena_porcentaje)')
    .eq('id', reserva_id).eq('user_id', user.id).single()
  if (!r) return json({ error: 'Reserva no encontrada' }, 404)
  if (r.estado === 'cancelada') return json({ error: 'La reserva está cancelada' }, 400)

  // 3. El monto se calcula acá, nunca se confía en el navegador
  const saldo = Number(r.monto_total) - Number(r.monto_pagado)
  if (saldo <= 0) return json({ error: 'La reserva ya está paga' }, 400)
  const sena = Math.min(Math.round(Number(r.monto_total) * r.salones.sena_porcentaje / 100), saldo)
  let importe = saldo
  if (modo === 'sena') importe = sena
  if (modo === 'monto') {
    importe = Number(monto)
    if (!(importe >= sena && importe <= saldo)) return json({ error: 'Monto fuera de rango' }, 400)
  }
  const tipo = importe >= saldo ? 'total' : modo === 'sena' ? 'sena' : 'parcial'

  // 4. Registrar el pago como pendiente
  const { data: pago, error: e1 } = await db.from('pagos')
    .insert({ reserva_id: r.id, user_id: user.id, monto: importe, tipo, metodo: 'mercadopago', estado: 'pendiente' })
    .select().single()
  if (e1) return json({ error: e1.message }, 400)

  // 5. Preferencia de Mercado Pago
  const front = Deno.env.get('FRONTEND_URL')!
  const pref = await fetch('https://api.mercadopago.com/checkout/preferences', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', Authorization: 'Bearer ' + Deno.env.get('MP_ACCESS_TOKEN') },
    body: JSON.stringify({
      items: [{ title: 'Reserva ' + r.salones.nombre + ' (' + tipo + ')', quantity: 1, unit_price: importe, currency_id: 'ARS' }],
      external_reference: pago.id,
      notification_url: url + '/functions/v1/mp-webhook',
      back_urls: { success: front + '/pago/resultado', failure: front + '/pago/resultado', pending: front + '/pago/resultado' },
      auto_return: 'approved',
    }),
  }).then(x => x.json())

  if (!pref.init_point) return json({ error: 'Mercado Pago no devolvió el link', detalle: pref }, 502)
  await db.from('pagos').update({ mp_preference_id: pref.id }).eq('id', pago.id)
  return json({ init_point: pref.init_point, pago_id: pago.id })
})
