<?php
require_once 'verifica.php';
require_once 'conectar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $meta = R::load('metas', $_POST['id']);

    // só mexe se a meta for do usuário logado
    if ($meta->id && $meta->usuario_id == $_SESSION['usuario_id']) {
        $meta->concluida = 1;
        R::store($meta);
    }
}

header('Location: metas.php');
exit;