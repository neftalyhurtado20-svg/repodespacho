# Aplicativo de Gestion de Envios (PHP + MySQL)

Este proyecto implementa un CRUD para envios de mensajeria.

Cada envio contiene:
- `destinatario`
- `direccion`
- `descripcion`

Funcionalidades:
- listar envios
- crear un envio
- editar un envio
- eliminar un envio

## Requisitos

- PHP 8.0+ con extension `pdo_mysql`
- MySQL 5.7+ o MariaDB compatible

## Configuracion

1. Crea la base de datos y la tabla:

```bash
mysql -u root -p < database/schema.sql
```

2. Define variables de entorno (PowerShell):

```powershell
$env:DB_HOST="127.0.0.1"
$env:DB_PORT="3306"
$env:DB_NAME="mensajeria"
$env:DB_USER="root"
$env:DB_PASS=""
```

Si no defines variables, el sistema usa esos mismos valores por defecto.

3. Inicia el servidor de desarrollo PHP:

```bash
php -S localhost:8000
```

4. Abre en tu navegador:

```text
http://localhost:8000
```

## Estructura principal

- `index.php`: listado de envios
- `create.php`: creacion de envio
- `edit.php`: actualizacion de envio
- `delete.php`: eliminacion de envio
- `includes/db.php`: conexion PDO a MySQL
- `includes/helpers.php`: utilidades (CSRF, validacion, flash messages)
- `database/schema.sql`: script de creacion de base y tabla
