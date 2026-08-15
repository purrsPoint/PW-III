<?php

$host = "localhost";
$banco = "bce_school";
$usuario = "root";
$senha = "";

$pdo = new PDO(
"mysql:host=$host;dbname=$banco",
$usuario,
$senha
);

$pdo->setAttribute(
PDO::ATTR_ERRMODE,
PDO::ERRMODE_EXCEPTION
);
