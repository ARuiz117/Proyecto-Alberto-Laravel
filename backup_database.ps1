# Script para hacer backup automático de la base de datos
# Uso: .\backup_database.ps1

$timestamp = Get-Date -Format 'yyyy-MM-dd_HH-mm-ss'
$backupPath = "c:\xampp\htdocs\ProyectoAlberto-Steam-Laravel\BACKUPS\backup_$timestamp.sql"
$dbName = "misteamdb_laravel"

Write-Host "Iniciando backup de la base de datos: $dbName"
Write-Host "Guardando en: $backupPath"

& "C:\xampp\mysql\bin\mysqldump.exe" -u root $dbName | Out-File -Encoding UTF8 $backupPath

if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ Backup completado exitosamente" -ForegroundColor Green
    Write-Host "Archivo: $backupPath"
} else {
    Write-Host "✗ Error al hacer el backup" -ForegroundColor Red
}
