-- 008: correcciones. Seguro de correr varias veces.
-- Arregla el error "Database error creating new user" y fija el search_path
-- de todas las funciones. Agrega cancelar_reserva.

create or replace function public.crear_perfil() returns trigger
language plpgsql security definer set search_path = public as $$
begin
  insert into public.perfiles (id, nombre, email)
  values (new.id, coalesce(new.raw_user_meta_data->>'nombre', split_part(new.email,'@',1)), new.email)
  on conflict (id) do nothing;
  return new;
end; $$;

drop trigger if exists on_auth_user_created on auth.users;
create trigger on_auth_user_created after insert on auth.users
for each row execute function public.crear_perfil();

create or replace function public.es_superadmin() returns boolean
language sql security definer stable set search_path = public as $$
  select exists (select 1 from public.perfiles where id = auth.uid() and rol = 'superadmin');
$$;

create or replace function public.es_admin_de(p_salon uuid) returns boolean
language sql security definer stable set search_path = public as $$
  select public.es_superadmin() or exists (
    select 1 from public.admin_salones a join public.perfiles p on p.id = a.admin_id
    where a.admin_id = auth.uid() and a.salon_id = p_salon and p.rol = 'admin' and p.activo);
$$;

create or replace function public.limitar_admins() returns trigger
language plpgsql set search_path = public as $$
begin
  if new.rol = 'admin' and (select count(*) from public.perfiles where rol = 'admin' and id <> new.id) >= 4 then
    raise exception 'Maximo 4 administradores';
  end if;
  return new;
end; $$;

create or replace function public.asignar_admin(p_email text) returns void
language plpgsql security definer set search_path = public as $$
declare uid uuid;
begin
  if not public.es_superadmin() then raise exception 'No autorizado'; end if;
  select id into uid from auth.users where email = p_email;
  if uid is null then raise exception 'El usuario no existe (debe registrarse primero)'; end if;
  update public.perfiles set rol = 'admin' where id = uid;
end; $$;

create or replace function public.quitar_admin(p_id uuid) returns void
language plpgsql security definer set search_path = public as $$
begin
  if not public.es_superadmin() then raise exception 'No autorizado'; end if;
  delete from public.admin_salones where admin_id = p_id;
  update public.perfiles set rol = 'usuario' where id = p_id and rol = 'admin';
end; $$;

create or replace function public.fechas_ocupadas(p_salon uuid)
returns table(fecha date) language sql security definer set search_path = public as $$
  select fecha from public.reservas
  where salon_id = p_salon and estado in ('pendiente','confirmada') and fecha >= current_date;
$$;

-- La reserva siempre nace pendiente, sin pago y con el precio del salon
create or replace function public.set_monto_total() returns trigger
language plpgsql set search_path = public as $$
begin
  new.monto_total := coalesce((select precio from public.salones where id = new.salon_id), 0);
  new.monto_pagado := 0;
  new.estado_pago := 'sin_pago';
  new.estado := 'pendiente';
  return new;
end; $$;

create or replace function public.recalcular_pago_reserva() returns trigger
language plpgsql security definer set search_path = public as $$
declare
  v_res uuid := coalesce(new.reserva_id, old.reserva_id);
  v_pagado numeric;
  v_total numeric;
begin
  select coalesce(sum(monto), 0) into v_pagado from public.pagos where reserva_id = v_res and estado = 'aprobado';
  select monto_total into v_total from public.reservas where id = v_res;
  update public.reservas set monto_pagado = v_pagado,
    estado_pago = case when v_pagado <= 0 then 'sin_pago' when v_pagado >= v_total then 'pagado' else 'parcial' end
  where id = v_res;
  return null;
end; $$;

create or replace function public.registrar_pago_manual(p_reserva uuid, p_monto numeric, p_metodo text)
returns void language plpgsql security definer set search_path = public as $$
declare
  r public.reservas%rowtype;
  v_saldo numeric;
begin
  select * into r from public.reservas where id = p_reserva;
  if r.id is null or not public.es_admin_de(r.salon_id) then raise exception 'No autorizado'; end if;
  if p_metodo not in ('efectivo','transferencia') then raise exception 'Metodo invalido'; end if;
  v_saldo := r.monto_total - r.monto_pagado;
  if p_monto <= 0 or p_monto > v_saldo then raise exception 'El monto debe estar entre 1 y el saldo (%)', v_saldo; end if;
  insert into public.pagos (reserva_id, user_id, monto, tipo, metodo, estado)
  values (r.id, r.user_id, p_monto, case when p_monto >= v_saldo then 'total' else 'parcial' end, p_metodo, 'aprobado');
end; $$;

-- El usuario cancela su reserva sin tener permiso de update directo
create or replace function public.cancelar_reserva(p_id uuid) returns void
language plpgsql security definer set search_path = public as $$
begin
  update public.reservas set estado = 'cancelada'
  where id = p_id and (user_id = auth.uid() or public.es_admin_de(salon_id));
  if not found then raise exception 'No autorizado o inexistente'; end if;
end; $$;
