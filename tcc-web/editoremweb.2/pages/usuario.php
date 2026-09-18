<?php

require_once "../backend/sessao.php";

precisalogar();

$nome = pegarnomeusuario();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Java School | Usuário</title>

    <link rel="stylesheet" href="css/conta.css">

</head>

<body>

    <main class="conta-container">

        <div class="conta-box">

            <h1>Usuário</h1>

            <p>
                Nome:
                <?= htmlspecialchars($nome) ?>
            </p>

            <a
                class="botao"
                href="aulas.php"
            >
                Voltar para as Aulas
            </a>

            <a
                class="sair"
                href="../backend/logout.php"
            >
                Sair da conta
            </a>

        </div>

    </main>

</body>

</html>