# Proceso de Normalización - Proyecto Integrador (TP6)

## 1. Entidad sin Normalizar (Ejemplo inicial con redundancia)
* **Registro hipotético:** Un producto/publicación guardaba la categoría y el email del autor repetidos en la misma tabla de forma redundante, generando anomalías de actualización.

## 2. Aplicación de las Formas Normales
* **1FN (Primera Forma Normal):** Se eliminaron los grupos repetitivos y listas dentro de un mismo campo, asegurando que cada columna contenga valores atómicos.
* **2FN (Segunda Forma Normal):** Se eliminaron las dependencias parciales asegurando que los atributos dependan por completo de la clave primaria.
* **3FN (Tercera Forma Normal):** Se eliminaron las dependencias transitivas extrayendo entidades independientes como `categorias` y `usuarios` hacia sus propias tablas.

## 3. Listado de Tablas Resultantes y Relaciones (Esquema Relacional)
1. **`usuarios`** (PK: `id`) -> Almacena datos de acceso y roles.
2. **`categorias`** (PK: `id`) -> Tabla maestra de clasificación.
3. **`productos`** (PK: `id`, FK: `categoria_id` -> `categorias(id)`) -> Tabla principal de negocio del sistema.