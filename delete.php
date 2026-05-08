<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

if (!validateCsrfToken($_POST['csrf_token'] ?? null)) {
    setFlash('error', 'No se pudo validar la solicitud. Intenta nuevamente.');
    redirect('index.php');
}

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($id === false) {
    setFlash('error', 'El envio a eliminar no es valido.');
    redirect('index.php');
}

$statement = $pdo->prepare('DELETE FROM envios WHERE id = :id');
$statement->execute([':id' => $id]);

if ($statement->rowCount() > 0) {
    setFlash('success', 'Envio eliminado correctamente.');
} else {
    setFlash('warning', 'No se encontro el envio para eliminar.');
}

redirect('index.php');
