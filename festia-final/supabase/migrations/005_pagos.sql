-- 005: pagos, triggers y estado de pago (Juan)
alter table salones add column sena_porcentaje int not null default 30 check (sena_porcentaje between 1 and 100);

alter table reservas
  add column monto_total numeric not null default 0,
  add column monto_pagado numeric not null default 0,
  add column estado_pago text not null default 'sin_pago' check (estado_pago in ('sin_pago','parcial','pagado'));

create table pagos (
  id uuid primary key default gen_random_uuid(),
  reserva_id uuid not null references reservas on delete cascade,
  user_id uuid references auth.users,
  monto numeric not null check (monto > 0),
  tipo text not null check (tipo in ('sena','parcial','total')),
  metodo text not null default 'mercadopago' check (metodo in ('mercadopago','efectivo','transferencia')),
  estado text not null default 'pendiente' check (estado in ('pendiente','aprobado','rechazado','reembolsado')),
  mp_preference_id text,
  mp_payment_id text unique,
  raw jsonb,
  created_at timestamptz default now()
);
create index pagos_reserva_idx on pagos (reserva_id);

-- El monto total se toma siempre del precio del salón (no del navegador)
create or replace function set_monto_total() returns trigger language plpgsql as $$
begin
  new.monto_total := coalesce((select precio from salones where id = new.salon_id), 0);
  new.monto_pagado := 0;
  new.estado_pago := 'sin_pago';
  return new;
end; $$;
create trigger trg_monto_total before insert on reservas for each row execute function set_monto_total();

-- Recalcula el resumen de la reserva cada vez que cambia un pago
create or replace function recalcular_pago_reserva() returns trigger language plpgsql security definer as $$
declare
  v_res uuid := coalesce(new.reserva_id, old.reserva_id);
  v_pagado numeric;
  v_total numeric;
begin
  select coalesce(sum(monto), 0) into v_pagado from pagos where reserva_id = v_res and estado = 'aprobado';
  select monto_total into v_total from reservas where id = v_res;
  update reservas set monto_pagado = v_pagado,
    estado_pago = case when v_pagado <= 0 then 'sin_pago' when v_pagado >= v_total then 'pagado' else 'parcial' end
  where id = v_res;
  return null;
end; $$;
create trigger trg_pagos_recalc after insert or update or delete on pagos for each row execute function recalcular_pago_reserva();
