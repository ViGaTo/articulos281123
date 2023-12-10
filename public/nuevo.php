<?php

use App\Db\Articulos;
use App\Db\Categorias;
use App\Utils\Tools;

session_start();

require_once __DIR__ . "/../vendor/autoload.php";

$categorias = Categorias::read();

if(isset($_POST['btn'])){
    $nombre = ucfirst(Tools::sanearCadenas($_POST['nombre']));
    $precio = (float)Tools::sanearCadenas($_POST['precio']);
    $categoria = (int)$_POST['category_id'];
    $disponible = "NO";
    if(isset($_POST['disponible'])){
        $disponible = "SI";
    }

    $errores = false;

    if(!Tools::longitudCadena("Nombre", $nombre, 3)){
        $errores = true;
    }

    if(Tools::existeNombre($nombre)){
        $errores = true;
    }

    if(!Tools::precioValido($precio)){
        $errores = true;
    }

    if(!Tools::categoriaValida($categoria)){
        $errores = true;
    }

    $imagen = "img/articulos/default.jpg";
    if(is_uploaded_file($_FILES['imagen']['tmp_name'])){
        if(!Tools::validarImagen($_FILES['imagen']['type'], $_FILES['imagen']['size'])){
            $errores = true;
        }else{
            $imagen = "img/articulos/".uniqid()."_".$_FILES['imagen']['name'];
            if(!move_uploaded_file($_FILES['imagen']['tmp_name'], "./".$imagen)){
                $_SESSION['Imagen'] = "Error: La imagen no pudo ser movida";
                $errores=true;
            }
        }
    }

    if($errores){
        header("Location:nuevo.php");
        die();
    }

    (new Articulos)->setNombre($nombre)
    ->setPrecio($precio)
    ->setCategoryId($categoria)
    ->setDisponible($disponible)
    ->setImagen($imagen)
    ->create();

    $_SESSION['mensaje'] = "Exito: El articulo se ha creado";
    header("Location:index.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>articulos</title>
    <style>
        body{
            background-color: orangered;
        }
    </style>
</head>

<body>
    <h3 class="my-2 text-xl text-center">CREAR ARTICULO</h3>
    <div class="w-1/2 mx-auto p-4 rounded-xl shadow-xl bg-white border border-gray-200">
        <form action="nuevo.php" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">
                    NOMBRE
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombre" type="text" placeholder="Nombre del articulo..." name="nombre">
            <?php
            Tools::mostrarErrores("Nombre");
            ?>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="precio">
                    PRECIO
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="precio" type="text" placeholder="Precio..." name="precio">
                <?php
                Tools::mostrarErrores("Precio")
                ?>
                <div class="mb-4"></div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="autor">
                    CATEGORIA
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="category_id" name="category_id">
                    <option>Selecciona una categoria</option>
                    <?php
                    foreach ($categorias as $item) {
                        echo <<<TXT
                        <option value='{$item->id}'>{$item->nombre}</option>
                        </div>
                        TXT;
                    }
                    ?>
                </select>
                <?php
                Tools::mostrarErrores("Categoria");
                ?>
                <div class="mb-3 flex justify-between">
                    <div class="w-full">
                        <label class="mt-4 block text-gray-700 text-sm font-bold mb-2" for="imagen">
                            IMAGEN
                        </label>
                        <input type="file" name="imagen" id="imagen" accept="image/*" oninput="img.src=window.URL.createObjectURL(this.files[0])" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <?php
                Tools::mostrarErrores("Imagen");
                ?>
                    </div>
                    <div class="ml-4">
                        <img src="./img/articulos/default.jpg" class="w-60" id="img">
                    </div>
                </div>
                <div class="mb-4">
                        <label class="relative inline-flex items-center mb-4 cursor-pointer">
                            <input type="checkbox" value="Disponible" class="sr-only peer" name="disponible">
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Disponible</span>
                        </label>
                <div class="flex flex-row-reverse">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" name="btn"><i class="fas fa-save mr-2"></i>GUARDAR</button>
                    <button type="reset" class="mx-2 bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded" name="btn"><i class="fas fa-paintbrush mr-2"></i>LIMPIAR</button>
                    <a href="index.php" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-home mr-2"></i>VOLVER
                    </a>
                </div>
        </form>
    </div>
</body>

</html>