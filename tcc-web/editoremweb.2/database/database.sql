DROP DATABASE if EXISTS bce_school;

CREATE DATABASE bce_school;

USE bce_school;

CREATE TABLE exercicios(
	id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
	titulo VARCHAR(255) NOT NULL,
		descricao TEXT NOT NULL,
		codigo_inicial TEXT NOT NULL,
		posicao INT NOT null
);

CREATE TABLE testes (
	id INT AUTO_INCREMENT PRIMARY KEY,
	exercicio_id INT NOT NULL,
	entrada TEXT NOT NULL,
	saida_esperada TEXT NOT NULL,
	/*on delete cascade faz q caso  exercicio pai do id seja deletado
	tambem é deleta o teste desse exercicio*/
	FOREIGN KEY(exercicio_id)REFERENCES exercicios(id) ON DELETE cascade
);

CREATE TABLE aulas(
	id INT AUTO_INCREMENT PRIMARY KEY,
	titulo VARCHAR(255) NOT NULL,
	video_url VARCHAR(255) NOT NULL,
	conteudo TEXT NOT NULL,
	exercicio_id INT NOT NULL,
	posicao INT NOT NULL,
	FOREIGN KEY (exercicio_id) REFERENCES exercicios(id)
	ON DELETE cascade
);

CREATE TABLE usuarios(
	id INT AUTO_INCREMENT PRIMARY KEY,
	nome VARCHAR(100) NOT NULL,
	email VARCHAR(255) NOT NULL,
	senha VARCHAR(255) NOT NULL,
	aula_atual INT NOT NULL DEFAULT 1
);


INSERT INTO exercicios

(titulo, descricao, codigo_inicial, posicao)

VALUES
(
    'Exercício 1 — Hello World',
    'Escreva um programa Java que mostre Hello World.',
    '
    public class Main {
    public static void main(String[] args) {

    }
}',
    1
),
(
    'Exercício 2 — Soma',
    'Leia dois números e mostre a soma deles.',
    '
    import java.util.Scanner;

public class Main {
    public static void main(String[] args) {
    Scanner leitor = new Scanner(System.in);

    }
}',
    2
),
(
    'Exercício 3 — Maior número',
    'Leia dois números e mostre qual é o maior.',
    '
    import java.util.Scanner;

public class Main {
    public static void main(String[] args) {
    Scanner leitor = new Scanner(System.in);

    }
}',
    3
);

INSERT INTO testes
(exercicio_id, entrada, saida_esperada)
VALUES
(
    1,
    '',
    'Hello World'
),
(
    2,
    '2 3',
    '5'
),
(
    2,
    '10 5',
    '15'
),
(
    2,
    '-2 3',
    '1'
);
INSERT INTO aulas
(titulo, video_url, conteudo, exercicio_id, posicao)
VALUES
(
    'Olá Mundo',
    'YOUTUBE_LINK_AQUI',
    'Aqui você aprenderá como criar seu primeiro programa Java...',
    1,
    1
),
(
    'Soma',
    'YOUTUBE_LINK_AQUI',
    'Nesta aula você aprenderá como receber valores...',
    2,
    2
),
(
    'Maior número',
    'YOUTUBE_LINK_AQUI',
    'Nesta aula você aprenderá como comparar valores...',
    3,
    3
);