-- Extensión requerida para el constraint de exclusión (EXCLUDE USING gist)
-- que impide traslapes de horario para conductor, aprendiz y vehículo en la tabla `clases`.
CREATE EXTENSION IF NOT EXISTS btree_gist;
