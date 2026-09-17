<?php
session_start();

function userlogado(): bool{
    return isset($_SESSION['usuario_id']);
}

function precisalogar(): void{
    if(!userlogado()){
        header('Location: login.php');
        exit;
    }
}

function pegarusuarioid(): int{
    return $_SESSION['usuario_id'] ?? null;
}

function pegarnomeusuario(): string{
    return $_SESSION['usuario_nome'] ?? '';
}