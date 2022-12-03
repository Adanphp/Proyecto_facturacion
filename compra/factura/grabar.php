<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_fac = $_POST['id_fac'];
$fecha = $_POST['fecha'];
$id_clie = $_post['id_clie'];
$id_pro = $_POST['id_pro'];
$id_item = $_POST['id_item'];
$id_tim = $_POR['id_tim'];
$cantidad = $_POST['cantidad'];
$precio = $_POST['precio'];
$fac_vencimiento = $_post['fac_vencimiento'];
$usuario = $_SESSION['usu_nombre'];
$operacion = $_POST['operacion'];
try{ 
$grabar = pg_query($conexion, "SELECT sp_factura($id_fac, $id_clie, '$fecha', $id_pro, $id_item,$id_tim, $cantidad, $precio, $fac_vencimiento, '$usuario', $operacion);");
 } catch(\throwable $th) {
    var_dump($th);
 }


if($grabar){
}else{
    echo pg_last_error()."_/_error";
}