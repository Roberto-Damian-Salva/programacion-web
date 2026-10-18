import { createClient } from 'npm:@supabase/supabase-js@2'

Deno.serve(async (req) => {
  const u = new URL(req.url)
  let id = u.searchParams.get('data.id') || u.searchParams.get('id')
  const topic = u.searchParams.get('type') || u.searchParams.get('topic') || ''
  if (!id) { try { const b = await req.json(); id = b?.data?.id; } catch (_) { /* sin cuerpo */ } }
  if (!id || topic.includes('merchant_order')) return new Response('ok')

  const mp = await fetch('https://api.mercadopago.com/v1/payments/' + id, {
    headers: { Authorization: 'Bearer ' + Deno.env.get('MP_ACCESS_TOKEN') },
  }).then(r => r.json())
  if (!mp?.id || !mp.external_reference) return new Response('ok')

  const mapa: Record<string, string> = { approved: 'aprobado', rejected: 'rechazado', cancelled: 'rechazado', refunded: 'reembolsado', charged_back: 'reembolsado' }
  const estado = mapa[mp.status] ?? 'pendiente'

  const db = createClient(Deno.env.get('SUPABASE_URL')!, Deno.env.get('SUPABASE_SERVICE_ROLE_KEY')!)
  await db.from('pagos').update({ estado, mp_payment_id: String(mp.id), raw: mp }).eq('id', mp.external_reference)
  return new Response('ok')
})
