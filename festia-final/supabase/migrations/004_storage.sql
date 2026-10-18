-- 004: bucket de fotos (Marcos)
insert into storage.buckets (id, name, public)
values ('salones', 'salones', true)
on conflict (id) do nothing;

-- Cualquiera puede ver las fotos (bucket público). Solo admins suben/editan/borran.
create policy "admins suben fotos" on storage.objects for insert to authenticated
with check (bucket_id = 'salones' and exists (select 1 from perfiles where id = auth.uid() and rol in ('admin','superadmin')));

create policy "admins editan fotos" on storage.objects for update to authenticated
using (bucket_id = 'salones' and exists (select 1 from perfiles where id = auth.uid() and rol in ('admin','superadmin')));

create policy "admins borran fotos" on storage.objects for delete to authenticated
using (bucket_id = 'salones' and exists (select 1 from perfiles where id = auth.uid() and rol in ('admin','superadmin')));
