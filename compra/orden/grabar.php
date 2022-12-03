<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_or = $_POST['id_or'];
$id_suc = $_POST['id_suc'];
$or_fecha = $_POST['or_fecha'];
$id_suc = $_SESSION['id_suc'];
$id_pro = $_POST['id_pro'];
$id_item = $_POST['id_item'];
$cantidad = $_POST['cantidad'];
$precio = $_POST['precio'];
$usuario = $_SESSION['usu_nombre'];
$operacion = $_POST['operacion'];

$grabar = pg_query($conexion, "SELECT sp_orden($id_or, $id_suc, '$or_fecha', $id_pro, $id_item, $cantidad, $precio, '$usuario', $operacion);");


if($grabar){
    echo pg_last_notice($conexion)."_/_success";
}else{
    echo pg_last_error()."_/_error";
}
