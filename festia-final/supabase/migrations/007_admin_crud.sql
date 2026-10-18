-- 007: email, activo y edicion de perfiles (Marcos)
alter table perfiles add column email text, add column activo boolean not null default true;
update perfiles p set email = u.email from auth.users u where u.id = p.id;

-- el trigger de alta ahora guarda también el email
create or replace function crear_perfil() returns trigger language plpgsql security definer as $$
begin
  insert into perfiles (id, nombre, email)
  values (new.id, coalesce(new.raw_user_meta_data->>'nombre', split_part(new.email,'@',1)), new.email);
  return new;
end; $$;

-- un admin desactivado pierde todos sus permisos
create or replace function es_admin_de(p_salon uuid) returns boolean language sql security definer stable as $$
  select es_superadmin() or exists (
    select 1 from admin_salones a join perfiles p on p.id = a.admin_id
    where a.admin_id = auth.uid() and a.salon_id = p_salon and p.rol = 'admin' and p.activo);
$$;

-- el superadmin puede editar perfiles
create policy perfiles_editar on perfiles for update to authenticated
using (es_superadmin()) with check (es_superadmin());
