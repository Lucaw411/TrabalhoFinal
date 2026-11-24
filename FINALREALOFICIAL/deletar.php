<?php
require_once 'verifica.php';
require_once 'conectar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $meta = R::load('metas', $_POST['id']);

    if ($meta->id && $meta->usuario_id == $_SESSION['usuario_id']) {
        R::trash($meta);
    }
}

header('Location: metas.php');
exit;