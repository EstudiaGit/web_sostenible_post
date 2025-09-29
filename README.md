# API REST Básica con PHP y MySQL

Este proyecto implementa una API RESTful para gestionar "posts" (entradas de blog) usando **PHP puro** y **MySQL**. Incluye un frontend en HTML/JS para interactuar con la API y una página estática de productos.

---

## 🛠️ Herramientas Utilizadas

- **PHP 8.0+**
- **Composer** (opcional, no hay dependencias actualmente)
- **Docker y Docker Compose** (para la base de datos MySQL)
- **Postman** (o cualquier cliente HTTP)
- **TablePlus** (o cualquier cliente MySQL)
- **Visual Studio Code** (opcional)
- **PowerShell** (para el script de inicio en Windows)

---

## 🚀 Puesta en Marcha

### 1. Levantar la Base de Datos con Docker

```sh
docker-compose up -d
```

Esto inicia un contenedor MySQL accesible en:

- **Host:** `127.0.0.1`
- **Puerto:** `3307`
- **Usuario:** `root`
- **Contraseña:** `micontraseñasecreta`

### 2. Crear la Base de Datos y la Tabla

Conéctate a MySQL y ejecuta:

```sql
CREATE DATABASE post_api;
USE post_api;
CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT,
  status ENUM('draft', 'published') DEFAULT 'draft',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 3. Configuración de la Conexión

El archivo `db.php` ya está configurado para:

- Host: `localhost`
- Puerto: `3307`
- Base de datos: `post_api`
- Usuario: `root`
- Contraseña: `micontraseñasecreta`

### 4. Iniciar el Servidor PHP

Desde la raíz del proyecto:

```sh
php -S localhost:8000
```

O usa el script de PowerShell `iniciar.ps1` para automatizar todo:

```sh
powershell -ExecutionPolicy Bypass -File iniciar.ps1
```

---

## 🧪 Probando la API

Todos los endpoints requieren la cabecera:

```
Authorization: my-secret-api-key
```

### Endpoints

- **GET `api.php`**: Lista los posts (parámetros opcionales: `limit`, `page`)
- **GET `/api.php?id=ID`**: Obtiene un post por ID
- **POST `api.php`**: Crea un post (JSON: `title`, `content`, `status`)
- **PUT `api.php`**: Actualiza un post (JSON: `id`, `title`, `content`)
- **DELETE `api.php`**: Elimina un post (JSON: `id`)

### Ejemplo: Crear un Post

```json
{
  "title": "Mi primer post",
  "content": "Contenido de ejemplo",
  "status": "published"
}
```

---

## 🌐 Frontend

- `index.html`: Interfaz web para crear, ver y eliminar posts usando la API.
- `productos_leprechaun.html`: Página estática con productos de ejemplo.

---

## 📝 Notas

- El proyecto no usa frameworks PHP ni dependencias externas.
- El frontend consume la API vía fetch y requiere que el backend esté corriendo.
- El script `iniciar.ps1` automatiza el arranque de Docker, el servidor PHP y la navegación al directorio del proyecto.

---

## 📂 Estructura de Archivos

- `api.php` — Lógica principal de la API REST
- `db.php` — Conexión PDO a MySQL
- `index.html` — Frontend para posts
- `productos_leprechaun.html` — Página de productos
- `docker-compose.yaml` — Servicio MySQL
- `iniciar.ps1` — Script de inicio en Windows
- `composer.json` — (vacío, sin dependencias)
