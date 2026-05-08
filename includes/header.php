<?php
declare(strict_types=1);

$pageTitle = $pageTitle ?? 'Gestion de Envios';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header class="topbar">
        <div class="container topbar-content">
            <a class="brand" href="index.php">Mensajeria</a>
            <nav class="topbar-nav">
                <a href="index.php">Envios</a>
                <a href="create.php">Nuevo envio</a>
            </nav>
        </div>
    </header>
