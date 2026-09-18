<?php

$erro = $_GET["erro"] ?? "";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Java School | Login</title>

    <link rel="stylesheet" href="css/conta.css">

</head>

<body>

    <main class="conta-container">

        <div class="conta-box">

            <h1>Entrar</h1>

            <?php if ($erro === "preenchimento"): ?>

                <p class="erro">
                    Preencha todos os campos.
                </p>

            <?php elseif ($erro === "credenciais"): ?>

                <p class="erro">
                    E-mail ou senha incorretos.
                </p>

            <?php endif; ?>

            <form
                action="../backend/login.php"
                method="POST"
            >

                <label for="email">
                    E-mail
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                >

                <label for="senha">
                    Senha
                </label>

                <input
                    type="password"
                    id="senha"
                    name="senha"
                    required
                >

                <button type="submit">
                    Entrar
                </button>

            </form>

            <p class="conta-link">
                Não possui uma conta?
                <a href="cadastro.php">
                    Criar conta
                </a>
            </p>

        </div>

    </main>

</body>

</html>