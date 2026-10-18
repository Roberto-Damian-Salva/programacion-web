-- 001: tablas base (Juan)
create table perfiles (
  id uuid primary key references auth.users on delete cascade,
  nombre text,
  rol text not null default 'usuario' check (rol in ('usuario','admin','superadmin'))
);

create table salones (
  id uuid primary key default gen_random_uuid(),
  nombre text not null,
  descripcion text,
  direccion text,
  capacidad int,
  precio numeric,
  imagen_principal text,
  activo boolean default true,
  created_at timestamptz default now()
);

create table salon_imagenes (
  id uuid primary key default gen_random_uuid(),
  salon_id uuid references salones on delete cascade,
  url text not null,
  orden int default 0
);

create table resenas (
  id uuid primary key default gen_random_uuid(),
  salon_id uuid references salones on delete cascade,
  user_id uuid references auth.users on delete cascade,
  puntaje int not null check (puntaje between 1 and 5),
  comentario text,
  created_at timestamptz default now()
);

create table reservas (
  id uuid primary key default gen_random_uuid(),
  salon_id uuid references salones on delete cascade,
  user_id uuid references auth.users on delete cascade,
  fecha date not null,
  estado text not null default 'pendiente' check (estado in ('pendiente','confirmada','cancelada')),
  created_at timestamptz default now()
);

-- un salón no puede tener 2 reservas activas el mismo día
create unique index reservas_unica on reservas (salon_id, fecha) where estado <> 'cancelada';

create table admin_salones (
  admin_id uuid references perfiles on delete cascade,
  salon_id uuid references salones on delete cascade,
  primary key (admin_id, salon_id)
);

-- crea el perfil automáticamente al registrarse
create or replace function crear_perfil() returns trigger language plpgsql security definer as $$
begin
  insert into perfiles (id, nombre) values (new.id, coalesce(new.raw_user_meta_data->>'nombre', split_part(new.email,'@',1)));
  return new;
end; $$;
create trigger on_auth_user_created after insert on auth.users for each row execute function crear_perfil();
