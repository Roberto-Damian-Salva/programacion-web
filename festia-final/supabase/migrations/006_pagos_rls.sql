-- 006: seguridad de pagos y pago manual (Marcos)
alter table pagos enable row level security;

-- El usuario ve sus pagos; el admin ve los de sus salones
create policy pagos_ver on pagos for select to authenticated
using (user_id = auth.uid() or es_admin_de((select salon_id from reservas where id = reserva_id)));

-- Sin policies de insert/update/delete: solo escriben las Edge Functions (service role)
-- y las funciones security definer de abajo.

create or replace function registrar_pago_manual(p_reserva uuid, p_monto numeric, p_metodo text)
returns void language plpgsql security definer as $$
declare r reservas%rowtype; v_saldo numeric;
begin
  select * into r from reservas where id = p_reserva;
  if r.id is null or not es_admin_de(r.salon_id) then raise exception 'No autorizado'; end if;
  if p_metodo not in ('efectivo','transferencia') then raise exception 'Método inválido'; end if;
  v_saldo := r.monto_total - r.monto_pagado;
  if p_monto <= 0 or p_monto > v_saldo then raise exception 'El monto debe estar entre 1 y el saldo (%)', v_saldo; end if;
  insert into pagos (reserva_id, user_id, monto, tipo, metodo, estado)
  values (r.id, r.user_id, p_monto, case when p_monto >= v_saldo then 'total' else 'parcial' end, p_metodo, 'aprobado');
end; $$;
