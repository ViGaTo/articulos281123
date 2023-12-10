<?php
namespace App\Utils;

use App\Db\Articulos;
use App\Db\Categorias;

class Tools{
    public static array $tiposImagen = [
        "image/gif",
        "image/png",
        "image/jpg",
        "image/jpeg",
        "image/bmp",
        "image/webp",
        "image/svg+xml",
        "image/x-icon"
    ];

    public static function validarImagen(string $tipo, int $size){
        if(!in_array($tipo, self::$tiposImagen)){
            $_SESSION['Imagen'] = "Error: El tipo no es válido";
            return false;
        }
        
        if ($size>3000000) {
            $_SESSION['Imagen'] = "Error: El tamaño no es válido";
            return false;
        }

        return true;
    }

    public static function sanearCadenas($valor){
        return htmlspecialchars(trim($valor));
    }

    public static function longitudCadena(string $nombre, string $valor, int $longitud){
        if(strlen($valor)<$longitud){
            $_SESSION[$nombre] = "Error: El campo $nombre no ha superado el mínimo de $longitud carácteres";
            return false;
        }
        return true;
    }

    public static function existeNombre(string $nombre, ?int $id=null){
        if(!Articulos::comprobarNombre($nombre, $id)){
            return false;
        }
        $_SESSION['Nombre'] = "Error: Existe el nombre que ha escrito";
        return true;
    }

    public static function precioValido(float $precio){
        if($precio < 5 || $precio>=1000){
            $_SESSION['Precio'] = "Error: El precio es demasiado bajo o grande (Entre 5 y 999.99)";
            return false;
        }
        return true;
    }

    public static function categoriaValida(int $id){
        if(!Categorias::comprobarCategorias($id)){
            $_SESSION['Categoria'] = "Error: La categoría no es válida";
            return false;
        }
        return true;
    }

    public static function mostrarErrores(string $nombre){
        if(isset($_SESSION[$nombre])){
            echo "<p class='text-red-700 italic text-sm'>{$_SESSION[$nombre]}</p>";
            unset($_SESSION[$nombre]);
        }
    }
}