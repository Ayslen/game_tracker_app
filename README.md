# Seguimiento de Juegos

Aplicación web desarrollada en PHP + MySQL para registrar videojuegos por usuario.  
Permite crear una cuenta, iniciar sesión, agregar juegos a una lista personal, editar su información, subir una portada, asignar estado, progreso, calificación y consultar los juegos guardados.

---

## Uso con XAMPP

1. Copia la carpeta del proyecto a `htdocs` o ejecútala desde tu entorno PHP.
2. Inicia Apache y MySQL en XAMPP.
3. Revisa `config.php`:
   - El proyecto prueba automáticamente los puertos `3306` y `3307` mediante `DB_PORTS`.
   - No es necesario cambiar manualmente el puerto si MySQL usa alguno de esos dos.
4. Abre la app en el navegador:

```text
http://localhost/game_tracker_app/
```

La base de datos y las tablas se crean automáticamente al abrir la app o registrar un usuario. No necesitas importar un archivo `.sql` para que la app funcione.

---

## Descripción general del proyecto

La aplicación permite que cada usuario tenga una lista privada de videojuegos.  
Cada juego puede tener:

- Título.
- Año de lanzamiento.
- Descripción o sinopsis.
- Notas personales.
- Estado: Sin Iniciar, En progreso, Completado o Abandonado.
- Porcentaje de progreso.
- Calificación de 1 a 5 estrellas.
- Imagen o portada del juego.

Los datos se guardan en una base de datos MySQL local usando XAMPP.

---

## Aportaciones por alumno

### Alumno 1: Backend, login y estructura principal

El Alumno 1 trabajó en la base funcional del sistema, incluyendo la estructura inicial del backend y el sistema de usuarios.

Aportaciones principales:

- Creación de archivos principales del sistema en PHP.
- Configuración inicial del proyecto.
- Implementación del registro de usuarios.
- Implementación del inicio de sesión.
- Manejo de sesiones de usuario.
- Protección de páginas mediante `require_login()`.
- Creación de rutas principales para agregar, editar y eliminar videojuegos.
- Integración inicial entre PHP y MySQL.
- Uso de contraseñas protegidas mediante `password_hash`.

Archivos relacionados:

- `config.php`
- `login.php`
- `register.php`
- `logout.php`
- `add_game.php`
- `edit_game.php`
- `delete_game.php`
- `inc/functions.php`
- `inc/db.php`

---

### Alumno 2: Base de Datos

El Alumno 2 se encargó de complementar y documentar la parte de base de datos del proyecto.  
Aunque la aplicación ya crea automáticamente la base de datos y las tablas desde `inc/db.php`, se agregaron archivos SQL para representar formalmente la estructura, consultas y datos de prueba.

Aportaciones principales:

- Diseño y documentación de la base de datos.
- Creación del archivo de estructura `schema.sql`.
- Creación de consultas de prueba en `queries.sql`.
- Creación de datos de prueba en `seed.sql`.
- Agregado de usuario demo.
- Agregado de videojuegos de ejemplo.
- Agregado de rutas de imágenes de prueba para los juegos demo.
- Documentación de la relación entre usuarios y videojuegos.
- Mejora de conexión a MySQL para probar automáticamente los puertos `3306` y `3307`.

Archivos agregados o modificados:

- `database/schema.sql`
- `database/queries.sql`
- `database/seed.sql`
- `uploads/demo/`
- `config.php`
- `inc/db.php`
- `README.md`

#### Base de datos utilizada

La base de datos del proyecto se llama:

```sql
game_tracker
```

#### Tablas principales

##### users

Guarda la información de los usuarios registrados.

Campos principales:

- `id`: identificador único del usuario.
- `username`: nombre de usuario, no se puede repetir.
- `password_hash`: contraseña protegida mediante hash.
- `created_at`: fecha de creación del usuario.

##### games

Guarda los videojuegos registrados por cada usuario.

Campos principales:

- `id`: identificador único del juego.
- `user_id`: identifica al usuario dueño del juego.
- `title`: título del videojuego.
- `description`: descripción del juego.
- `notes`: notas personales del usuario.
- `image_path`: ruta de la imagen del juego.
- `status`: estado del juego.
- `progress`: porcentaje de avance.
- `rating`: calificación del 1 al 5.
- `release_year`: año de lanzamiento.
- `created_at`: fecha de creación del registro.
- `updated_at`: fecha de última modificación.

#### Relación entre tablas

La tabla `games` se relaciona con la tabla `users` mediante el campo `user_id`.

```text
users.id  →  games.user_id
```

Esto permite que cada usuario tenga su propia lista privada de videojuegos.

#### Datos de prueba

El archivo `seed.sql` agrega un usuario de prueba:

```text
Usuario: demo
Contraseña: demo1234
```

También agrega videojuegos de ejemplo como:

- Minecraft.
- Grand Theft Auto V.
- Elden Ring.
- Resident Evil 9: Requiem.

Para ver estos datos en la app, cada integrante debe importar manualmente el archivo:

```text
database/seed.sql
```

desde phpMyAdmin.

---

### Alumno 3: API externa e IA

El Alumno 3 trabajó en la integración de una API externa para apoyar el llenado automático de información de los videojuegos.

Aportaciones principales:

- Creación de una API interna para comunicarse con Gemini.
- Generación automática de descripción o sinopsis mediante IA.
- Búsqueda automática del año de lanzamiento del juego.
- Botones de IA dentro del formulario de juegos.
- Manejo básico de respuestas de la API.
- Integración de solicitudes desde JavaScript usando `fetch`.

Archivos relacionados:

- `api/ai.php`
- `inc/ai_placeholders.php`
- `game_form.php`
- `Fronted/app.js`

Nota: la función de IA depende de la disponibilidad de la API externa. Si se supera la cuota gratuita o la clave de API falla, los botones de IA pueden mostrar un error.

---

### Alumno 4: Frontend, interfaz y dashboard

El Alumno 4 trabajó en la parte visual del proyecto, mejorando la interfaz de usuario y el diseño general de la aplicación.

Aportaciones principales:

- Diseño visual con estilo gamer/neón.
- Creación de estilos CSS para login, registro, dashboard y formularios.
- Diseño de tarjetas para mostrar los videojuegos.
- Diseño de botones de editar, borrar y agregar juego.
- Mejora visual de formularios.
- Implementación de vista previa de imagen al seleccionar portada.
- Estructura visual del dashboard principal.
- Mejoras en la experiencia de usuario.

Archivos relacionados:

- `Fronted/style.css`
- `Fronted/app.js`
- `Fronted/index.html`
- `index.php`
- `game_form.php`
- `login.php`
- `register.php`
- `add_game.php`
- `edit_game.php`

---

## Archivos principales del proyecto

```text
game_tracker_app/
│
├─ api/
│  ├─ ai.php
│  ├─ game_form.php
│  └─ games.php
│
├─ database/
│  ├─ schema.sql
│  ├─ queries.sql
│  └─ seed.sql
│
├─ Fronted/
│  ├─ app.js
│  ├─ index.html
│  └─ style.css
│
├─ inc/
│  ├─ ai_placeholders.php
│  ├─ db.php
│  ├─ footer.php
│  ├─ functions.php
│  ├─ header.php
│  └─ lang.php
│
├─ uploads/
│  └─ demo/
│
├─ add_game.php
├─ config.php
├─ delete_game.php
├─ edit_game.php
├─ game_form.php
├─ index.php
├─ login.php
├─ logout.php
├─ register.php
└─ README.md
```

---

## Imágenes de juegos

Las imágenes subidas por el usuario se guardan dentro de la carpeta:

```text
uploads/
```

La base de datos no guarda la imagen directamente, sino solamente la ruta del archivo en el campo:

```text
image_path
```

Ejemplo:

```text
uploads/demo/minecraft.jpg
```

---

## Importar datos de prueba

Para cargar los datos demo:

1. Abre phpMyAdmin:

```text
http://localhost/phpmyadmin
```

2. Selecciona la base de datos:

```text
game_tracker
```

3. Entra a la pestaña **Importar**.
4. Selecciona el archivo:

```text
database/seed.sql
```

5. Presiona **Continuar**.

Después podrás iniciar sesión con:

```text
Usuario: demo
Contraseña: demo1234
```

---

## Notas finales

- La aplicación crea automáticamente la base de datos y las tablas desde `inc/db.php`.
- Los archivos `.sql` sirven como respaldo, documentación y datos de prueba.
- Los datos reales de cada usuario se guardan localmente en MySQL/XAMPP.
- GitHub guarda el código, pero no guarda automáticamente la base de datos local de cada integrante.