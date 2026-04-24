    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Partidas - Interclasse</title>
    </head>
    <body>
        
        <h1>Partidas</h1>

        <p><a href="/partidas/create">Cadastrar nova partida</a></p>

        <table border="1" cellpadding="6">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Modalidade</th>
                    <th>Confronto</th>
                    <th>Placar</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($partidas as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['data_hora'])?></td>
                    <td><?= htmlspecialchars($p['modalidade'])?></td>

                    <td><?= htmlspecialchars($p['time_casa'])?> x <?= htmlspecialchars($p['time_fora'])?></td>
                    <td><?= (int)$p['placar_casa']?> : <?= (int)$p['placar_fora']?></td>
                    <td>
                        <a href="/partidas/show?id=<?= urlencode((string)$p['id']) ?>">Detalhes</a>
                    
                    </td>
                </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    </body>
    </html>