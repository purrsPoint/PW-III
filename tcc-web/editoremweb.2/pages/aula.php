<?php 

require_once "../backend/database.php";
require_once "../backend/sessao.php";

precisalogar();

$usuarioid = pegarusuarioid();

$aulaid = (int) ($_GET['id'] ?? 0);

if ($aulaid <= 0){
    header("Location: aulas.php");
    exit;
}

$stmt = $pdo->prepare("
SELECT aula_atual FROM usuarios WHERE id = ?");
$stmt->execute([$usuarioid]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$usuario){
    header("Location: ../backend/logout.php");
    exit;
}

$aula_atual = (int) $usuario['aula_atual'];

$stmt = $pdo->prepare(
    "SELECT
        aulas.id,
        aulas.titulo,
        aulas.video_url,
        aulas.conteudo,
        aulas.exercicio_id,
        aulas.posicao,
        exercicios.titulo AS exercicio_titulo
     FROM aulas
     INNER JOIN exercicios
        ON exercicios.id = aulas.exercicio_id
     WHERE aulas.id = ?"
);

$stmt->execute([$aulaid]);
$aula = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$aula){
    header("Location: aulas.php");
    exit;
}

if ((int)$aula["posicao"] > $aula_atual) {
    header("Location: aulas.php");
    exit;
}

function obterurlembedyt(string $url): string
{
    $url = trim($url);

    if (preg_match(
        '~(?:youtube\.com/watch\?v=|youtu\.be/)([^&?/]+)~',
        $url,
        $correspondencia
    )) {
        return "https://www.youtube.com/embed/" . $correspondencia[1];
    }

    if (str_contains($url, "youtube.com/embed/")) {
        return $url;
    }

    return "";
}

$urlVideo = obterurlembedyt($aula["video_url"]);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title> <?= htmlspecialchars($aula["titulo"]) ?></title>

    <link rel="stylesheet" href="css/aula.css">

</head>

<body>

    <header>

        <a href="aulas.php">
            Aulas
        </a>

        <a href="usuario.php">
            <?= htmlspecialchars(pegarnomeusuario()) ?>
        </a>

    </header>


    <main class="aula-container">

        <h1>
            <?= htmlspecialchars($aula["titulo"]) ?>
        </h1>


        <?php if ($urlVideo !== ""): ?>

            <section class="video-container">

                <iframe
                    src="<?= htmlspecialchars($urlVideo) ?>"
                    title="<?= htmlspecialchars($aula["titulo"]) ?>"
                    allowfullscreen
                ></iframe>

            </section>

        <?php endif; ?>


        <section class="conteudo">

            <?= nl2br(htmlspecialchars($aula["conteudo"])) ?>

        </section>


        <section class="exercicio">

            <h2>
                Exercício
            </h2>

            <p>
                <?= htmlspecialchars($aula["exercicio_titulo"]) ?>
            </p>

            <a
                class="botao-exercicio"
                href="tarefa.php?p=<?=(int) $aula["exercicio_id"] ?>"
            >
                Ir para o exercício
            </a>

        </section>

    </main>

</body>

</html>