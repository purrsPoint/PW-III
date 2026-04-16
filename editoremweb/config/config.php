<?php 
//faz a raiz do projeto(um nivel acima da pasta atual config)
define('BASE_DIR', dirname(__DIR__));
//onde os arquivos vão ser armazenados para compilar e rodar
define('JAVA_TEMP_DIR', BASE_DIR. '/temp/');

if(!file_exists(JAVA_TEMP_DIR)){
    //0777 deixa o php ler/escrever e criar
    mkdir(JAVA_TEMP_DIR, 0777, true);
}