<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $location): never
{
    header("Location: {$location}");
    exit;
}

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function getFlash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function validateCsrfToken(?string $token): bool
{
    if (!isset($_SESSION['csrf_token']) || $token === null) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

function textLength(string $value): int
{
    if (function_exists('mb_strlen')) {
        return mb_strlen($value);
    }

    return strlen($value);
}

function validateShipment(array $input): array
{
    $values = [
        'destinatario' => trim((string) ($input['destinatario'] ?? '')),
        'direccion' => trim((string) ($input['direccion'] ?? '')),
        'descripcion' => trim((string) ($input['descripcion'] ?? '')),
    ];

    $errors = [];

    if ($values['destinatario'] === '') {
        $errors['destinatario'] = 'El destinatario es obligatorio.';
    } elseif (textLength($values['destinatario']) > 120) {
        $errors['destinatario'] = 'El destinatario no puede superar 120 caracteres.';
    }

    if ($values['direccion'] === '') {
        $errors['direccion'] = 'La direccion es obligatoria.';
    } elseif (textLength($values['direccion']) > 255) {
        $errors['direccion'] = 'La direccion no puede superar 255 caracteres.';
    }

    if ($values['descripcion'] === '') {
        $errors['descripcion'] = 'La descripcion es obligatoria.';
    } elseif (textLength($values['descripcion']) > 500) {
        $errors['descripcion'] = 'La descripcion no puede superar 500 caracteres.';
    }

    return [$values, $errors];
}
