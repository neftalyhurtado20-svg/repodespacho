<?php
declare(strict_types=1);
?>
<form class="form-card" method="post" action="<?= e($action) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
    <?php if (!empty($isEdit)): ?>
        <input type="hidden" name="id" value="<?= (int) $id ?>">
    <?php endif; ?>

    <div class="field">
        <label for="destinatario">Destinatario</label>
        <input
            id="destinatario"
            name="destinatario"
            type="text"
            maxlength="120"
            value="<?= e($values['destinatario'] ?? '') ?>"
            required
        >
        <?php if (isset($errors['destinatario'])): ?>
            <p class="error"><?= e($errors['destinatario']) ?></p>
        <?php endif; ?>
    </div>

    <div class="field">
        <label for="direccion">Direccion</label>
        <input
            id="direccion"
            name="direccion"
            type="text"
            maxlength="255"
            value="<?= e($values['direccion'] ?? '') ?>"
            required
        >
        <?php if (isset($errors['direccion'])): ?>
            <p class="error"><?= e($errors['direccion']) ?></p>
        <?php endif; ?>
    </div>

    <div class="field">
        <label for="descripcion">Descripcion</label>
        <textarea
            id="descripcion"
            name="descripcion"
            maxlength="500"
            rows="4"
            required
        ><?= e($values['descripcion'] ?? '') ?></textarea>
        <?php if (isset($errors['descripcion'])): ?>
            <p class="error"><?= e($errors['descripcion']) ?></p>
        <?php endif; ?>
    </div>

    <div class="form-actions">
        <button class="button" type="submit"><?= e($submitLabel) ?></button>
        <a class="button button-muted" href="index.php">Cancelar</a>
    </div>
</form>
