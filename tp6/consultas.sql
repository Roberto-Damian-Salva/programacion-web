USE mi_proyecto_web;

-- 1. CREATE: Inserción de datos iniciales (Pruebas DML)
INSERT INTO categorias (nombre) VALUES ('Tecnología'), ('Hogar y Estilo');

INSERT INTO usuarios (nombre, email, password, rol) 
VALUES ('Roberto Salta', 'roberto@email.com', '123456', 'admin');

INSERT INTO productos (titulo, descripcion, precio, categoria_id) 
VALUES ('Auriculares Inalámbricos', 'Bluetooth de alta fidelidad', 45000.00, 1),
       ('Lámpara LED Escritorio', 'Luz ajustable con USB', 18500.00, 2);

-- 2. READ: Consultas con filtros, ordenamientos y uniones JOIN
SELECT * FROM productos WHERE precio > 10000 ORDER BY precio DESC;

SELECT p.id, p.titulo, p.precio, c.nombre AS categoria_nombre 
FROM productos p
INNER JOIN categorias c ON p.categoria_id = c.id;

-- 3. UPDATE: Actualización de un registro específico usando WHERE
UPDATE productos 
SET precio = 48000.00 
WHERE id = 1;

-- 4. DELETE: Eliminación de un registro de prueba usando WHERE
DELETE FROM productos 
WHERE id = 999;