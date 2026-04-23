
CREATE DATABASE interclasse CHARACTER SET UTF8MB4 COLLATE UTF8MB4_UNICODE_CI;

USE interclasse;

CREATE TABLE modalidades(
	id INT AUTO_INCREMENT PRIMARY KEY,
	nome VARCHAR(80) NOT NULL UNIQUE 
);

CREATE TABLE jogadores (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nome VARCHAR(80) NOT NULL,
	turma VARCHAR(20) NOT NULL,
	numero INT NULL 
);

CREATE TABLE partidas(
	id INT AUTO_INCREMENT PRIMARY KEY,
	modalidade_id INT NOT NULL,
	data_hora DATETIME NOT NULL,
	time_casa VARCHAR(20) NOT NULL,
	time_fora VARCHAR(20) NOT NULL,
	placar_casa INT NOT NULL DEFAULT 0,
	placar_fora INT NOT NULL DEFAULT 0,
	FOREIGN KEY (modalidade_id) REFERENCES modalidades(id)
);

CREATE TABLE cartoes(
	id INT AUTO_INCREMENT PRIMARY KEY,
	partida_id INT NOT NULL,
	jogador_id INT NOT NULL,
	tipo ENUM('amarelo','vermelho') NOT NULL,
	minuto INT NULL,
	observacap VARCHAR(255) NULL,
	FOREIGN KEY (partida_id) REFERENCES partidas(id),
	FOREIGN KEY (jogador_id) REFERENCES jogadores(id)
);
