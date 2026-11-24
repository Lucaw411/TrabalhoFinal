<?php
require_once 'verifica.php';
require_once 'conectar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['descricao'])) {
    $meta = R::dispense('metas');
    $meta->descricao  = $_POST['descricao'];
    $meta->concluida  = 0;
    $meta->usuario_id = $_SESSION['usuario_id'];
    R::store($meta);
}

header('Location: metas.php');
exit;