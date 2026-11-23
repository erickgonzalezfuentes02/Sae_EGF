-- Ejecutar una sola vez en phpMyAdmin, base de datos sae_egf.
-- Vincula cada cuenta de acceso con su ficha de alumno o profesor.
ALTER TABLE usuarios
    ADD COLUMN id_alumno INT NULL AFTER id_puesto,
    ADD COLUMN id_profesor INT NULL AFTER id_alumno;

CREATE INDEX idx_usuarios_id_alumno ON usuarios (id_alumno);
CREATE INDEX idx_usuarios_id_profesor ON usuarios (id_profesor);
