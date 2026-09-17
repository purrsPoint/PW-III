<?php

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/sessao.php';

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if (empty($email) || empty($senha)) {
    header('Location: ../pages/login.php?erro=preenchimento');
    exit;
}

$stmt = $pdo->prepare(
'SELECCT id, nome, $senha
FROM usuarios
WHERE email = :email'
);

$stmt->execute(['email' => $email]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if(!usuario || !password_verify($senha, $usuario['senha'])) {
    header('Location: ../pages/login.php?erro=credenciais');
    exit;
}

$_SESSION['usuario_nome'] = $usuario['nome'];
$_SESSION['usuario_id'] = (int)$usuario['id'];

header('Location: ../pages/aulas.php');
exit;

