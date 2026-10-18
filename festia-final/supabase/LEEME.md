# Backend de Festia en Supabase

## Migraciones (supabase/migrations)
Se corren en el SQL Editor, en este orden, una por una (Run and enable RLS si pregunta):
001, 002, 003, 004, 005, 006, 007, 008.

NOTA: el proyecto de Sheila ya tiene 001 a 007 corridas. Para un proyecto nuevo, correr las 8 en orden.
El 008 se puede correr varias veces sin problema.

## Funciones (supabase/functions)
- crear-pago: crea el pago y la preferencia de Mercado Pago
- mp-webhook: recibe el aviso de Mercado Pago (se despliega con --no-verify-jwt)
- admin-usuarios: crear y eliminar administradores (solo superadmin)

Solo Sheila despliega y carga los secretos:
  npx supabase secrets set MP_ACCESS_TOKEN=TEST-xxxx FRONTEND_URL=http://localhost:5173
  npx supabase functions deploy crear-pago
  npx supabase functions deploy admin-usuarios
  npx supabase functions deploy mp-webhook --no-verify-jwt
