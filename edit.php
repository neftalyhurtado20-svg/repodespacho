<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

$rawId = $_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['id'] ?? null) : ($_GET['id'] ?? null);
$id = filter_var($rawId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if ($id === false) {
    setFlash('error', 'El envio solicitado no es valido.');
    redirect('index.php');
}

$findStatement = $pdo->prepare(
    'SELECT id, destinatario, direccion, descripcion 
     FROM envios 
     WHERE id = :id'
);
$findStatement->execute([':id' => $id]);
$envio = $findStatement->fetch();

if ($envio === false) {
    setFlash('error', 'El envio no existe.');
    redirect('index.php');
}

$values = [
    'destinatario' => (string) $envio['destinatario'],
    'direccion' => (string) $envio['direccion'],
    'descripcion' => (string) $envio['descripcion'],
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? null)) {
        setFlash('error', 'No se pudo validar la solicitud. Intenta nuevamente.');
        redirect('index.php');
    }

    [$values, $errors] = validateShipment($_POST);

    if (count($errors) === 0) {
        $updateStatement = $pdo->prepare(
            'UPDATE envios
             SET destinatario = :destinatario, direccion = :direccion, descripcion = :descripcion
             WHERE id = :id'
        );
        $updateStatement->execute([
            ':destinatario' => $values['destinatario'],
            ':direccion' => $values['direccion'],
            ':descripcion' => $values['descripcion'],
            ':id' => $id,
        ]);

        setFlash('success', 'Envio actualizado correctamente.');
        redirect('index.php');
    }
}

$pageTitle = 'Editar envio';
$action = 'edit.php';
$submitLabel = 'Actualizar envio';
$isEdit = true;

require __DIR__ . '/includes/header.php';
?>
<main class="container main-content">
    <section class="page-header">
        <h1>Editar envio #<?= (int) $id ?></h1>
        <a class="button button-muted" href="index.php">Volver al listado</a>
    </section>

    <?php require __DIR__ . '/includes/shipment-form.php'; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
