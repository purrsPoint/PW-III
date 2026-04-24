<?php 

require_once __DIR__ . '/Database.php';


class Partida{
    public static function all(): array{
        $pdo = Database::connection();

        //selecão das paradas em sql
        $sql = "SELECT p.*,
                m.nome AS modalidade from partidas p
                JOIN modalidades m ON m.id = p.modalide_id 
                ORDER BY p.data_hora DESC";
                return $pdo->query($sql)->fetchAll();
    }

    public static function find(int $id): ?array{
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            "SELECT p.*, m.nome AS modalidade FROM partidas p 
            JOIN modalidades m ON m.id = p.modalidade_id 
            WHERE p.id = :id"
        );

        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();

        return $row?: null;
    }

    public static function create(int $modalidadeid, string $dataHora, string $casa, string $fora):int{

    if($casa === $fora){
        throw new InvalidArgumentException("Times não podem ser iguais.");
        }
    $pdo = Database::connection();

    $stmt = $pdo->prepare(
        "INSERT INTO partidas (modalidade_id,  data_hora, time_casa, time_fora) VALUES (:mid, :dh, :casa, :fora)"
    );
    $stmt->execute([
        'mid' => $modalidadeid,
        'dh' => $dataHora,
        'casa' => $casa,
        'fora' => $fora
    ]);
    return(int)$pdo->lastInsertId();
    }

    public static function atualizarPlacar(int $partidaId, int $casa, int $fora): void {
        if($casa < 0 || $fora < 0){
            throw new InvalidArgumentException("Placar não pode ser negativo.");
        }

        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            "UPDATE partidas
            SET placar_casa = :casa,placar_fora = :fora
            WHERE id = :id"
        );

        $stmt->execute(['casa' => $casa, 'fora' => $fora, 'id' => $partidaId]);
    }
}
?>