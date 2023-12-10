<?php
namespace App\Db;

use \PDO;
use \PDOException;

class Articulos extends Conexion{
    private int $id;
    private string $nombre;
    private string $disponible;
    private float $precio;
    private string $imagen;
    private int $category_id;

    public function __construct()
    {
        parent::__construct();
    }

    //-------------------------------------- CRUD
    public function create(){
        $q = "insert into articulos(nombre, disponible, precio, imagen, category_id) values(:n, :d, :p, :i, :cI)";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ":n" => $this->nombre,
                ":d" => $this->disponible,
                ":p" => $this->precio,
                ":i" => $this->imagen,
                ":cI" => $this->category_id
            ]);
        } catch (PDOException $ex) {
            die("Error al crear articulos: ".$ex->getMessage());
        }
        parent::$conexion=null;
    }

    public static function read(){
        parent::setConexion();
        $q = "select articulos.*, categorias.nombre as nomCat from categorias, articulos where articulos.category_id=categorias.id order by nombre desc";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error al leer articulos: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function update(int $id){
        $q = "update articulos set nombre=:n, disponible=:d, precio=:p, imagen=:i, category_id=:cI where id=:id";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ":n" => $this->nombre,
                ":d" => $this->disponible,
                ":p" => $this->precio,
                ":i" => $this->imagen,
                ":cI" => $this->category_id,
                ":id" => $id
            ]);
        } catch (PDOException $ex) {
            die("Error al actualizar articulos: ".$ex->getMessage());
        }
        parent::$conexion=null;
    }

    public static function delete(int $id){
        parent::setConexion();
        $q = "delete from articulos where id=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([":i" => $id]);
        } catch (PDOException $ex) {
            die("Error al borrar articulos: ".$ex->getMessage());
        }
        parent::$conexion=null;
    }
    //-------------------------------------- OTROS
    public static function recogerArticuloId(int $id){
        parent::setConexion();
        $q = "select * from articulos where id=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([":i" => $id]);
        } catch (PDOException $ex) {
            die("Error al leer articulos: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function comprobarNombre(string $nombre, ?int $id=null){
        parent::setConexion();
        $q = ($id==null) ? "select nombre from articulos where :n=nombre" : "select nombre from articulos where :n=nombre AND id!=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            ($id==null) ? $stmt->execute([":n" => $nombre]) : $stmt->execute([":n" => $nombre, ":i" => $id]);
        } catch (PDOException $ex) {
            die("Error al comprobarNombre: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function nombreCategoria(int $id){
        parent::setConexion();
        $q = "select categorias.* from categorias, articulos where category_id=categorias.id AND articulos.id=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([":i" => $id]);
        } catch (PDOException $ex) {
            die("Error al leer articulos: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    //-------------------------------------- FAKER
    private static function existeArticulos(){
        parent::setConexion();
        $q = "select id from articulos";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error en existeArticulos: ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }

    public static function generarArticulos(int $cantidad){
        if(self::existeArticulos()) return;

        $faker = \Faker\Factory::create("es_ES");
        $faker->addProvider(new \Mmo\Faker\PicsumProvider($faker));
        for ($i=0; $i < $cantidad; $i++) { 
            $nombre = ucfirst($faker->unique()->words(random_int(1,4), true));
            $disponible = random_int(1,2);
            $precio = $faker->randomFloat(2, 5, 999);
            $imagen = "img/articulos/".$faker->picsum("./img/articulos/", 400, 400, false);
            $category_id = $faker->randomElement(Categorias::read())->id;

            (new Articulos)->setNombre($nombre)
            ->setDisponible($disponible)
            ->setPrecio($precio)
            ->setImagen($imagen)
            ->setCategoryId($category_id)
            ->create();
        }
    }

    //-------------------------------------- SETTERS

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
     * Set the value of disponible
     */
    public function setDisponible(string $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * Set the value of precio
     */
    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Set the value of imagen
     */
    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Set the value of category_id
     */
    public function setCategoryId(int $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }
}