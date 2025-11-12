param(
    [string]$email = "test@ejemplo.local",
    [string]$password = "MiPass123!",
    [string]$role = "usuario"
)

Write-Host "Creando usuario: $email (role: $role) dentro del contenedor web..."
# Ejecuta el script PHP dentro del contenedor web. Requiere docker-compose up -d ejecutado.
docker-compose exec -T web php src/setup_create_user.php $email $password $role
