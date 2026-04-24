<?php

    require_once __DIR__ . '/../models/Partida.php'
    require_once __DIR__ . '/../models/Cartao.php' 
    require_once __DIR__ . '/../models/Database.php'

    class PartidaController {
        public function index(): void{
            $partidas = Partida::all();

            require __DIR__ . '/../views/partidas/index.php';
        }

        public function show(): void{
            $id = (int)($_GET['id'] ?? 0);

            $partida = Partida::find($id);

            if(!$partida){
                http_response_code(404);

                echo "Partida não encontrada";

                return;
            }

            $cartoes = Cartao::allByPartida($id);

            require __DIR__ . '/../views/partidas/show.php';
        }

        public function createForm(): void{

        //carrega modalidades pro <select>(combobox)
            $pdo = Database::connection();

            $modalidades = $pdo->query("SELECT id, nome FROM modalidades ORDER BY nome")->fetchAll();

            require __DIR__ . '/../views/partidas/create.php';
        }

        public function store(): void{

            $modalidadeId = (int)($_POST['modalidade_id'] ?? 0);
            $dataHora = trim($_POST['data_hora'] ??);
            $casa = trim($_POST['time_casa'] ??);
            $fora = trim($_POST['time_fora'] ??);

             if ($modalidadeId <= 0 || $dataHora === '' || $casa === '' || $fora === '') {  
                http_response_code(422);  
                echo "Dados inválidos para criar partida.";  
                return;  
            }

            try{
                Partida::create($modalidadeId, $dataHora, $casa, $fora);

                header("Location: /partidas");

                exit;
            }catch(Throwable $e){
                http_response_code(400);

                echo $e->getMessage();

            }
        }

        public function updateScore(): void{
            $id = (int)($_POST['id'] ?? 0);
            $casa = (int)($_POST['placar_casa'] ?? -1);
            $fora = (int)($_POST['placar_fora'] ?? -1);

            try{
                Partida::atualizarPlacar($id, $casa, $fora);

                header("Location: /partidas/show?id=" . urlencode((string)$id));
                exit;
            }catch(Throwable $e){
                http_response_code(400);

                echo $e->getMessage();
            }
        }
    }
?>