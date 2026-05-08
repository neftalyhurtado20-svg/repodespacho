<?php
declare(strict_types=1);

/**
 * Devuelve [valor, fuente] donde fuente es "env" o "file".
 */
function configValueWithSource(string $key, mixed $fallback): array
{
    $value = getenv($key);
    if ($value === false) {
        return [$fallback, 'file'];
    }

    return [$value, 'env'];
}

function isTruthy(mixed $value): bool
{
    if (is_bool($value)) {
        return $value;
    }

    $normalized = strtolower(trim((string) $value));
    return in_array($normalized, ['1', 'true', 'yes', 'on'], true);
}

$configFile = __DIR__ . '/../config/database.php';
$fileConfig = [];
if (is_file($configFile)) {
    $loaded = require $configFile;
    if (is_array($loaded)) {
        $fileConfig = $loaded;
    }
}

[$hostRaw, $hostSource] = configValueWithSource('DB_HOST', $fileConfig['host'] ?? '127.0.0.1');
[$portRaw, $portSource] = configValueWithSource('DB_PORT', $fileConfig['port'] ?? '3306');
[$dbNameRaw, $dbNameSource] = configValueWithSource('DB_NAME', $fileConfig['name'] ?? 'mensajeria');
[$userRaw, $userSource] = configValueWithSource('DB_USER', $fileConfig['user'] ?? 'root');
[$passwordRaw, $passwordSource] = configValueWithSource('DB_PASS', $fileConfig['pass'] ?? '');
[$debugRaw] = configValueWithSource('APP_DEBUG', $fileConfig['debug'] ?? false);

$host = (string) $hostRaw;
$port = (string) $portRaw;
$dbName = (string) $dbNameRaw;
$user = (string) $userRaw;
$password = (string) $passwordRaw;
$debug = isTruthy($debugRaw);

$dsn = sprintf(
    'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
    $host,
    $port,
    $dbName
);

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $exception) {
    http_response_code(500);
    error_log('DB connection failed: ' . $exception->getMessage());

    $friendlyMessage = 'No se pudo conectar con la base de datos.';
    $normalizedError = strtolower($exception->getMessage());

    if (str_contains($normalizedError, 'unknown database')) {
        $friendlyMessage .= ' La base "' . $dbName . '" no existe.';
        $friendlyMessage .= ' Ejecuta el script database/schema.sql.';
    } elseif (str_contains($normalizedError, 'access denied')) {
        $friendlyMessage .= ' Usuario o clave invalidos.';
        $friendlyMessage .= ' Revisa host, usuario y password.';
        $friendlyMessage .= ' En alwaysdata usa el usuario MySQL del panel (no el FTP).';
    } elseif (
        str_contains($normalizedError, 'connection refused')
        || str_contains($normalizedError, 'php_network_getaddresses')
        || str_contains($normalizedError, 'no such file or directory')
    ) {
        $friendlyMessage .= ' El servidor MySQL no responde.';
        $friendlyMessage .= ' Verifica host, puerto y que MySQL este encendido.';
        $friendlyMessage .= ' Conexion intentada: ' . $host . ':' . $port . '.';
        if ($host === '127.0.0.1' || $host === 'localhost') {
            $friendlyMessage .= ' Si usas alwaysdata, prueba con mysql-[cuenta].alwaysdata.net.';
        }
    } else {
        $friendlyMessage .= ' Revisa tu configuracion.';
    }

    if ($debug) {
        $friendlyMessage .= ' Detalle tecnico: ' . $exception->getMessage();
        $friendlyMessage .= ' Config usada: ';
        $friendlyMessage .= 'host=' . $host . ' (' . $hostSource . '), ';
        $friendlyMessage .= 'port=' . $port . ' (' . $portSource . '), ';
        $friendlyMessage .= 'db=' . $dbName . ' (' . $dbNameSource . '), ';
        $friendlyMessage .= 'user=' . $user . ' (' . $userSource . '), ';
        $friendlyMessage .= 'pass=' . ($password === '' ? 'vacia' : 'definida') . ' (' . $passwordSource . ').';
    }

    exit($friendlyMessage);
}
