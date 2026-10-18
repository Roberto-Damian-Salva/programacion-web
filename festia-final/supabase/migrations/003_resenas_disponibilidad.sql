-- 003: resenas y calendario de disponibilidad (Juan)
create view resenas_publicas as
select r.id, r.salon_id, r.puntaje, r.comentario, r.created_at, p.nombre
from resenas r join perfiles p on p.id = r.user_id;

create or replace function fechas_ocupadas(p_salon uuid)
returns table(fecha date) language sql security definer as $$
  select fecha from reservas
  where salon_id = p_salon and estado in ('pendiente','confirmada') and fecha >= current_date;
$$;
