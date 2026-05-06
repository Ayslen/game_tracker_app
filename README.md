# Seguimiento de Juegos

App base en PHP + MySQL para registrar juegos por usuario.

## Uso con XAMPP

1. Copia la carpeta del proyecto a `htdocs` o ejecútala desde tu entorno PHP.
2. Inicia Apache y MySQL en XAMPP.
3. Revisa `config.php`:
   - El proyecto prueba automáticamente los puertos `3306` y `3307` mediante `DB_PORTS`.
   - No es necesario cambiar manualmente el puerto si MySQL usa alguno de esos dos.
4. Abre la app en el navegador.

La base de datos y las tablas se crean automáticamente al abrir la app o registrar un usuario. No necesitas importar un archivo `.sql`.

## Alumno 2: Base de Datos

El Alumno 2 se encargó de complementar la parte de base de datos del proyecto, agregando archivos SQL para documentar, probar y respaldar la estructura utilizada por la aplicación.

Aunque la aplicación ya crea automáticamente la base de datos y las tablas desde `inc/db.php`, se agregaron archivos específicos dentro de la carpeta `database` para representar formalmente el diseño de la base de datos.

### Archivos agregados

- `database/schema.sql`: contiene la creación de la base de datos `game_tracker` y las tablas principales `users` y `games`.
- `database/queries.sql`: contiene consultas SQL de prueba para consultar, insertar, actualizar, buscar, ordenar y eliminar registros.
- `database/seed.sql`: contiene datos de prueba, incluyendo un usuario demo y videojuegos de ejemplo.
- `uploads/demo/`: contiene imágenes de prueba utilizadas por algunos registros insertados desde `seed.sql`.

### Base de datos utilizada

La base de datos del proyecto se llama:

```sql
game_tracker