# Seguimiento de Juegos

App base en PHP + MySQL para registrar juegos por usuario.

## Uso con XAMPP

1. Copia la carpeta del proyecto a `htdocs` o ejecútala desde tu entorno PHP.
2. Inicia Apache y MySQL en XAMPP.
3. Revisa `config.php`:
   - Si MySQL usa puerto 3306, deja `DB_PORT` en `3306`.
   - Si MySQL usa puerto 3307, cambia `DB_PORT` a `3307`.
4. Abre la app en el navegador.

La base de datos y las tablas se crean automáticamente al abrir la app o registrar un usuario. No necesitas importar un archivo `.sql`.
