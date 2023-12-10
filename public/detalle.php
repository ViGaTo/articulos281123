<?php
use App\Db\Articulos;

if(!isset($_GET['id'])){
    header("Location:index.php");
    die();
}
require_once __DIR__."/../vendor/autoload.php";

$id = $_GET['id'];
$articulo = Articulos::recogerArticuloId($id);
$nombreCategoria = Articulos::nombreCategoria($id);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- CDN TAILWIND CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!--CDN FONTAWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--CDN SWEETALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body{
            background-color: orangered;
        }
    </style>
</head>
<body>
<div class="mt-3 mx-auto max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <img class="rounded-t-lg" src="<?php echo $articulo->imagen ?>" alt="<?php echo $articulo->nombre ?>" />
    <div class="p-5">
        <a href="#">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><?php echo $articulo->nombre ?></h5>
        </a>
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Precio: <?php echo $articulo->precio ?>€</p>
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Disponible: <?php echo $articulo->disponible ?></p>
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Categoría: <?php echo $nombreCategoria->nombre ?></p>
        <a href="index.php" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <i class="fas fa-home mr-2"></i> INICIO
        </a>
    </div>
</div>

</body>
</html>