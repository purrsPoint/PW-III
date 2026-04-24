<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da partida</title>
</head>
<body>

    <p><a href="/partidas"><- Voltar</a></p>
    

    <h1><?=  htmlspecialchars($partida['modalidade'])?>
    --
    <?= htmlspecialchars($partida['time_casa'])?> x
    <?= htmlspecialchars($partida['time_fora'])?>
    </h1>

    <p><strong>Data:</strong><?= htmlspecialchars($partida['data_hora'])?></p>

    <h2>Placar</h2>

    <form method="post" action="/partidas/placar">

    <input type="hidden" name="id" value="<?= (int)$partida['id']?>">
    Casa: <input type="number" name="placar_casa" value="<?= (int)$partida['placar_casa']?>" min="0">

    Fora: <input type="number" name="placar_fora" value="<?= (int)$partida['placar_fora']?>"  min="0">

    <button type="submit">Atualizar</button>
    
</form>

<h2>Cartões</h2>

<ul>
    <?php foreach($cartoes as $c)?>
    <li>

    <?= htmlspecialchars($c['tipo'])?> — <?=  htmlspecialchars($c['jogador'])?>

    (<?= htmlspecialchars($c['turma'])?>)
    <?php if(!empty($c['minuto']))?>
    —<?= htmlspecialchars($c['minuto'])?>

    <?php endif; ?>

    <?php if(!empty($c['observacao']))?>
    —<?= htmlspecialchars($c['observacao'])?>

    <?php endif; ?>
    </li>

    <?php endforeach; ?>
</ul>
</body>
</html>