<?php

class Database{
    private static ?PDO $conn = null;

    public static function connection(): PDO{
        if(self::$conn === null){
            $dsn = "mysql:host=localhost;dbname=interclasse;charset=utf8mb4";
            self::$conn = new PDO($dsn, "root", "", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
        }
        return self::$conn;
    }
}
?>