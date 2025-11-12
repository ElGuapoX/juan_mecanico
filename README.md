# Juan_Mecanico — Auditoría y mejoras

He realizado una auditoría rápida del proyecto y algunas mejoras iniciales. Este README resume lo hecho, pasos para probar y siguientes recomendaciones.

## Cambios aplicados (resumen)
- `src/bd/bd.php`: refactorizado para leer credenciales desde variables de entorno (DB_HOST, DB_USER, DB_PASS, DB_NAME), habilitar excepciones mysqli y usar charset `utf8mb4`.
- `src/ver_datos.php`: corregido include de conexión y reforzada la validación del parámetro `tabla` (solo permite caracteres seguros y usa `SHOW TABLES LIKE ?`).
- Corregidos includes rotos que referenciaban `db.php` inexistente (ahora apuntan a `bd/bd.php`) en varios archivos como `src/aud_errores.php` y `src/php/cliente/facturacliente.php`.
- Mitigación básica de XSS: escapado de salida en `src/php/admin/ver_mecanicos.php`, `lista_clientes.php` y `facturacion.php`.

## Cómo configurar la conexión a la BD
Opciones:
1. Usar variables de entorno (recomendado): exportar/definir las siguientes variables antes de arrancar PHP/Servidor web:

- DB_HOST (por defecto `localhost`)
- DB_USER (por defecto `root`)
- DB_PASS (por defecto ``)
- DB_NAME (por defecto `juan_mecanico`)

Si usas Docker y el `docker-compose.yml` que añadí, define también:

- MYSQL_ROOT_PASSWORD (contraseña para el usuario root de MySQL; por defecto `root` en `.env.example`)

Ejemplo en PowerShell:

```powershell
$env:DB_HOST = 'localhost'; $env:DB_USER = 'root'; $env:DB_PASS = ''; $env:DB_NAME = 'juan_mecanico'
```

2. O dejar las variables por defecto (local development) — `bd/bd.php` usará `root` y contraseña vacía si no se encuentran las variables.

Se añadió `.env.example` (sólo ejemplo) en la raíz del repo.

### Levantar con Docker (pasos rápidos)

1. Crea un archivo `.env` en la raíz (puedes usar `.env.example` como referencia). Ejemplo rápido en PowerShell:

```powershell
Set-Content -Path .env -Value "MYSQL_ROOT_PASSWORD=TuPassSegura123!`nDB_HOST=db`nDB_USER=root`nDB_PASS=TuPassSegura123!`nDB_NAME=juan_mecanico"
```

2. Levanta los contenedores:

```powershell
docker-compose up -d --build
```

3. Comprueba la importación automática del SQL (logs):

```powershell
docker-compose logs -f db
```

4. Crear un usuario de prueba (opcional) dentro del contenedor web:

```powershell
# Usa el script helper (desde la raíz del repo)
powershell -File .\scripts\create_user.ps1 -email "test@ejemplo.local" -password "MiPass123!" -role "usuario"
```

5. Accesos:
	- Web: http://localhost:8080
	- phpMyAdmin: http://localhost:8081  (usuario: root, contraseña: la que definiste en `.env`)

## Crear un usuario de prueba
Opción A (rápida, desde tu máquina con PHP instalado):

1. Genera un hash de contraseña con PHP:

```powershell
php -r "echo password_hash('MiPass123!', PASSWORD_DEFAULT).PHP_EOL;"
```

2. Inserta el usuario en la BD (reemplaza HASH_AQUI por el resultado anterior):

```sql
USE juan_mecanico;
INSERT INTO CLIENTE (NOMBRE, APELLIDO, EMAIL, PASSWORD, ROL) VALUES ('Test','Usuario','test@ejemplo.local','HASH_AQUI','usuario');
```

Opción B: script PHP de ayuda incluido `src/setup_create_user.php` (ejecutar con `php src/setup_create_user.php email contraseña rol`). Bórralo después de usarlo.

## Siguientes pasos recomendados (priorizados)
1. Reemplazar `mysqli` por `PDO` para un manejo más robusto y consistente.
2. Revisar todas las salidas HTML y aplicar `htmlspecialchars()` sistemáticamente (hay más plantillas que necesitan hardening).
3. Revisar rutas e includes y unificarlas (por ejemplo, crear un bootstrap `src/bootstrap.php` que cargue autoload/config). 
4. Añadir CSRF tokens en formularios que cambian estado (registro, crear factura, registrar auto).
5. Habilitar un mecanismo de logging con niveles (p.ej. Monolog) y no usar `die()` en producción.
6. Añadir pruebas básicas (scripts que validen endpoints y flujos de registro/login).

## Notas
- No eliminé archivos automáticamente. Antes de borrar código/archivos, puedo detectar archivos no referenciados y proponerte una lista segura para eliminar.
- Puedo continuar aplicando las recomendaciones (p. ej. escapar más plantillas, convertir a PDO, añadir CSRF). Dime qué prefieres que haga a continuación.

----

