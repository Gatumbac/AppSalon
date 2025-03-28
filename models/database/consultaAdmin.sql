USE APPSALON;
SELECT c.id AS id, c.hora, CONCAT(u.nombre, ' ',  u.apellido) AS cliente, u.telefono, u.email, s.nombre AS servicio, s.precio
FROM citas c 
LEFT JOIN usuarios u
ON c.usuario_id = u.id
LEFT JOIN citas_servicios cs
ON c.id = cs.cita_id
LEFT JOIN servicios s
ON s.id = cs.servicio_id
WHERE c.fecha = '2025-04-02';