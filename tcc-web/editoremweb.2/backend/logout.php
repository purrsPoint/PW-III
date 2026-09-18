<?php

require_once __DIR__ . "/sessao.php";

$_SESSION = [];

session_destroy();

header("Location: ../pages/login.php");
exit;
