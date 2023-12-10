<?php
session_start();
use App\Db\Articulos;

require_once __DIR__."/../vendor/autoload.php";
 if(!isset($_POST['id'])){
    header("Location:index.php");
    die();
 }

 $id = $_POST['id'];

 if(!Articulos::recogerArticuloId($id)){
    header("Location:index.php");
    die();
 }

 $articulo = Articulos::recogerArticuloId($id);

 if(basename($articulo->imagen)!="default.jpg"){
    unlink("./".$articulo->imagen);
 }

 Articulos::delete($id);
 $_SESSION['mensaje'] = "Exito: El articulo se ha borrado";
 header("Location:index.php");