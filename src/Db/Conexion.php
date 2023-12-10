<?php
namespace App\Db;

use \PDO;
use \PDOException;

class Conexion{
    protected static $conexion;

    public function __construct()
    {
        self::setConexion();
    }

    public static function setConexion(){
        if(self::$conexion!=null) return;

        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__."/../../");
        $dotenv->load();

        $user = $_ENV['USER'];
        $pass = $_ENV['PASS'];
        $db = $_ENV['DB'];
        $host = $_ENV['HOST'];

        $dsn = "mysql:host=$host;dbname=$db";
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        try {
            self::$conexion = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $ex) {
            die("Error al conectar: ".$ex->getMessage());
        }
    }
}