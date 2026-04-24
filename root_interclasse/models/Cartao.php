<?php

    require_once __DIR__ . '/Database.php';
    
    class Cartao{
        public static function allByPartida(int $partidaId): array{
            
            $pdo = Database::connection();

            $stmt = $pdo->prepare(
                "SELECT c.*, j.nome AS jogador, j.turma
                FROM cartoes c
                JOIN jogadores j ON j.id = c.jogador_id
                WHERE c.partida_id = :pid
                ORDER BY c.id DESC"
            );

            $stmt->execute(['pid' => $partidaId]);

            return $stmt->fetchAll();
        }
        public static function registrar(int $partidaId, int $jogadorId, string $tipo, ?int $minuto, ?string $obs): int{
            if(!in_array($tipo,['amarelo','vermelho'], true)){
                throw new InvalidArgumentException('Tipo de cartão inválido.');
            }

            $pdo = Database::connection();

            $stmt = $pdo->prepare(
                "INSERT INTO cartoes(partida_id, jogador_id, tipo, minuto, observacao)
                VALUES  (:pid, :jid, :tipo, :minuto, :obds)"
            );

            $stmt->execute([
                'pid' => $partidaId,
                'jid' => $jogadorId,
                'tipo' => $tipo,
                'minuto' => $minuto,
                'obs' => $obs
            ]);

            return (int)$pdo->lastInsertId();
        }
    }
?>