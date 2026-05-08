<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

$statement = $pdo->query(
    'SELECT id, destinatario, direccion, descripcion, created_at 
     FROM envios 
     ORDER BY created_at DESC'
);
$envios = $statement->fetchAll();

$flash = getFlash();
$pageTitle = 'Envios';

require __DIR__ . '/includes/header.php';
?>
<main class="container main-content">
    <section class="page-header">
        <h1>Gestion de envios</h1>
        <a class="button" href="create.php">Crear envio</a>
    </section>

    <?php if ($flash !== null): ?>
        <div class="alert alert-<?= e((string) $flash['type']) ?>">
            <?= e((string) $flash['message']) ?>
        </div>
    <?php endif; ?>

    <?php if (count($envios) === 0): ?>
        <div class="empty-state">
            No hay envios registrados.
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Destinatario</th>
                        <th>Direccion</th>
                        <th>Descripcion</th>
                        <th>Fecha de creacion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($envios as $envio): ?>
                        <tr>
                            <td><?= (int) $envio['id'] ?></td>
                            <td><?= e((string) $envio['destinatario']) ?></td>
                            <td><?= e((string) $envio['direccion']) ?></td>
                            <td><?= e((string) $envio['descripcion']) ?></td>
                            <td><?= e((string) $envio['created_at']) ?></td>
                            <td class="actions">
                                <a class="link-button" href="edit.php?id=<?= (int) $envio['id'] ?>">
                                    Editar
                                </a>
                                <form method="post" action="delete.php" onsubmit="return confirm('Seguro que deseas eliminar este envio?');">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                                    <input type="hidden" name="id" value="<?= (int) $envio['id'] ?>">
                                    <button class="link-button danger" type="submit">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
