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
    'HELLO WORLD'
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
