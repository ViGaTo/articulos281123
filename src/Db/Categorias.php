<?php
namespace App\Db;

use \PDO;
use \PDOException;

class Categorias extends Conexion{
    private int $id;
    private string $nombre;
    private string $descripcion;

    public function __construct()
    {
        parent::__construct();
    }

    //----------------------------- CRUD
    public function create(){
        $q = "insert into categorias(nombre, descripcion) values(:n, :d)";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ":n" => $this->nombre,
                ":d" => $this->descripcion
            ]);
        } catch (PDOException $ex) {
            die("Error al crear categorias: ".$ex->getMessage());
        }
        parent::$conexion=null;
    }

    public static function read(){
        parent::setConexion();
        $q = "select * from categorias";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error al leer categorias: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    //----------------------------- OTROS
    public static function comprobarCategorias(int $id){
        parent::setConexion();
        $q = "select id from categorias where id=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([":i" => $id]);
        } catch (PDOException $ex) {
            die("Error en comprobarCategorias: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }
    //----------------------------- FAKER
    private static function existeCategorias(): bool{
        parent::setConexion();
        $q = "select id from categorias";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error en existeCategorias: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }

    public static function generarCategorias(int $cantidad){
        if(self::existeCategorias()) return;

        $faker = \Faker\Factory::create("es_ES");
        for ($i=0; $i < $cantidad; $i++) { 
            $nombre = ucfirst($faker->unique()->words(random_int(1,2), true));
            $descripcion = $faker->text();

            (new Categorias)->setNombre($nombre)
            ->setDescripcion($descripcion)
            ->create();
        }
    }

    //----------------------------- SETTERS

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of nombre
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Set the value of descripcion
     */
    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }
}