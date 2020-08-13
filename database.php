<?php


class Database
{
    private $host = 'localhost';
    private $db = 'toko';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db, $this->username, $this->password
            );
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Koneksi gagal: " . $exception->getMessage();
        }

        return $this->conn;
    }
}