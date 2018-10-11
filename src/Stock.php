<?php

class Stock
{
    public $host = '127.0.0.1';

    public $dbname = 'stock';

    public $user = 'root';

    public $password = 'mytest';

    public $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(
            "mysql:host=$this->host;dbname=$this->dbname",
            $this->user,
            $this->password);
    }

    public function exec($sql)
    {
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result[0][0];
    }
}