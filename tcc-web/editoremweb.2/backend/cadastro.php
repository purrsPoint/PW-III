<?php

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/session.php';

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if (empty($nome) || empty($email) || empty($senha)) {
    header('Location: ../pages/cadastro.php?erro=preenchimento');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../pages/cadastro.php?erro=email_invalido');
    exit;
}

if(strlen($senha)<8) {
    header('Location: ../pages/cadastro.php?erro=senha_curta');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
$stmt->execute([$email]);

if($stmt->fetch()) {
    header('Location: ../pages/cadastro.php?erro=email_existente');
    exit;
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);
$stmt = $pdo->prepare(
 "INSERT INTO usuarios
 (nome, email, senha)
 VALUES (?, ?, ?)"
);

$stmt->execute([$nome, $email, $senhaHash]);

$usuarioId = $pdo->lastInsertId();

$_SESSION['usuario_id'] = (int) $usuarioId;
$_SESSION['nome'] = $nome;

header('Location: ../pages/aulas.php');