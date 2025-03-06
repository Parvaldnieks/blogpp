<?php
class Database {
    private $config = [
            "host" => "localhost",
            "port" => 3306,
            "dbname" => "blogpp",
            "charset" => "utf8mb4",
            "user" => "root",
            "password" => ""
    ];

    private $pdo;

    // Konstruktors - pieslēdzas datu bāzei
    public function __construct() {
        $dsn = "mysql:" . http_build_query($this->config, "", ";");
        $this->pdo = new PDO($dsn);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    // Metode, kas izpilda vaicājumus
    public function query($sql, $params = []) {

        // 1. Sagatavot vaicājumu
        $statement = $this->pdo->prepare($sql);
        // 2. Izpildīt vaicājumu
        $statement->execute($params);
        return $statement;
    }
}  