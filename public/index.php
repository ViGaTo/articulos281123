<?php
session_start();

use App\Db\Articulos;
use App\Db\Categorias;

require_once __DIR__ . "/../vendor/autoload.php";

Categorias::generarCategorias(6);
Articulos::generarArticulos(36);

$articulos = Articulos::read();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CDN TAILWIND CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!--CDN FONTAWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--CDN SWEETALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>articulos</title>
    <style>
        body {
            background-color: orangered;
        }
    </style>
</head>

<body>
    <h1 class="my-3 text-xl text-center">Listado de artículos</h1>
    <div class="mx-auto mb-1 flex flex-row-reverse w-3/4">
        <a href="nuevo.php" class="mb-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"><i class="fas fa-add mr-2"></i> NUEVO</a>
    </div>

    <div class="mx-auto w-3/4 relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        INFO
                    </th>
                    <th scope="col" class="px-6 py-3">
                        NOMBRE
                    </th>
                    <th scope="col" class="px-6 py-3">
                        CATEGORIA
                    </th>
                    <th scope="col" class="px-6 py-3">
                        PRECIO
                    </th>
                    <th scope="col" class="px-6 py-3">
                        DISPONIBLE
                    </th>
                    <th scope="col" class="px-6 py-3">
                        ACTIONS
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($articulos as $item) {
                    echo <<<TXT
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <td class="px-6 py-4">
                <a href="detalle.php?id={$item->id}"><i class="text-lg fas text-yellow-600 fa-info"></i></a>
                </td>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {$item->nombre}
                </th>
                <td class="px-6 py-4">
                    {$item->nomCat}
                </td>
                <td class="px-6 py-4">
                    {$item->precio} €
                </td>
                <td class="px-6 py-4">
                    {$item->disponible}
                </td>
                <td class="px-6 py-4">
                <form action="delete.php" method="POST">
                <input type='hidden' name='id' value='{$item->id}'>
                <a href="update.php?id={$item->id}"<i class="ml-2 text-lg fas fa-edit text-green-500"></i>
                <button type='submit'>
                <i class="text-lg fas fa-trash text-red-700"></i>
                </button>
                </form>
                </td>
            </tr>
            TXT;
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
    if (isset($_SESSION['mensaje'])) {
        echo <<<TXT
        <script>
        Swal.fire({
            position: "center",
            icon: "success",
            title: "{$_SESSION['mensaje']}",
            showConfirmButton: false,
            timer: 1500
        });
        </script>
        TXT;
        unset($_SESSION['mensaje']);
    }
    ?>
</body>

</html>