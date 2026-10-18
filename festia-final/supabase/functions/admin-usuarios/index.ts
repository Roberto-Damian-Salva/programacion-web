import { createClient } from 'npm:@supabase/supabase-js@2'

const cors = { 'Access-Control-Allow-Origin': '*', 'Access-Control-Allow-Headers': 'authorization, x-client-info, apikey, content-type' }
const json = (b: unknown, s = 200) => new Response(JSON.stringify(b), { status: s, headers: { ...cors, 'Content-Type': 'application/json' } })

Deno.serve(async (req) => {
  if (req.method === 'OPTIONS') return new Response('ok', { headers: cors })
  const url = Deno.env.get('SUPABASE_URL')!
  const userClient = createClient(url, Deno.env.get('SUPABASE_ANON_KEY')!, { global: { headers: { Authorization: req.headers.get('Authorization') ?? '' } } })
  const { data: { user } } = await userClient.auth.getUser()
  if (!user) return json({ error: 'No autenticado' }, 401)

  const db = createClient(url, Deno.env.get('SUPABASE_SERVICE_ROLE_KEY')!)
  const { data: yo } = await db.from('perfiles').select('rol').eq('id', user.id).single()
  if (yo?.rol !== 'superadmin') return json({ error: 'No autorizado' }, 403)

  const { accion, email, password, nombre, salones, id } = await req.json()

  if (accion === 'crear') {
    const { count } = await db.from('perfiles').select('id', { count: 'exact', head: true }).eq('rol', 'admin')
    if ((count ?? 0) >= 4) return json({ error: 'Máximo 4 administradores' }, 400)
    const { data, error } = await db.auth.admin.createUser({ email, password, email_confirm: true, user_metadata: { nombre } })
    if (error) return json({ error: error.message }, 400)
    const { error: e2 } = await db.from('perfiles').update({ rol: 'admin', nombre }).eq('id', data.user.id)
    if (e2) { await db.auth.admin.deleteUser(data.user.id); return json({ error: e2.message }, 400) }
    if (salones?.length) await db.from('admin_salones').insert(salones.map((s: string) => ({ admin_id: data.user.id, salon_id: s })))
    return json({ ok: true, id: data.user.id })
  }

  if (accion === 'eliminar') {
    if (id === user.id) return json({ error: 'No podés eliminarte a vos mismo' }, 400)
    const { data: objetivo } = await db.from('perfiles').select('rol').eq('id', id).single()
    if (objetivo?.rol === 'superadmin') return json({ error: 'No se puede eliminar un superadmin' }, 400)
    const { error } = await db.auth.admin.deleteUser(id)
    return error ? json({ error: error.message }, 400) : json({ ok: true })
  }

  return json({ error: 'Acción inválida' }, 400)
})
