<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

$values = [
    'destinatario' => '',
    'direccion' => '',
    'descripcion' => '',
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? null)) {
        setFlash('error', 'No se pudo validar la solicitud. Intenta nuevamente.');
        redirect('index.php');
    }

    [$values, $errors] = validateShipment($_POST);

    if (count($errors) === 0) {
        $statement = $pdo->prepare(
            'INSERT INTO envios (destinatario, direccion, descripcion) VALUES (:destinatario, :direccion, :descripcion)'
        );
        $statement->execute([
            ':destinatario' => $values['destinatario'],
            ':direccion' => $values['direccion'],
            ':descripcion' => $values['descripcion'],
        ]);

        setFlash('success', 'Envio creado correctamente.');
        redirect('index.php');
    }
}

$pageTitle = 'Crear envio';
$action = 'create.php';
$submitLabel = 'Guardar envio';
$isEdit = false;

require __DIR__ . '/includes/header.php';
?>
<main class="container main-content">
    <section class="page-header">
        <h1>Crear envio</h1>
        <a class="button button-muted" href="index.php">Volver al listado</a>
    </section>

    <?php require __DIR__ . '/includes/shipment-form.php'; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
