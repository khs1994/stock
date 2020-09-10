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
        $host = $this->host;

        if (file_exists('/.dockerenv')) {
            $host = 'mysql';
        }

        $this->pdo = new PDO(
            "mysql:host=$host;dbname=$this->dbname",
            $this->user,
            $this->password
        );
    }

    public function exec($sql, $single = true)
    {
        // var_dump($sql);
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();

        if(!$single){
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
        }

        $result = $stmt->fetchAll();

        if ($single) {
            return $result[0][0] ?? null;
        }

        return $result;
    }
}
