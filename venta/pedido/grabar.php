<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_ped = $_POST['id_ped'];
$ped_fecha = $_POST['ped_fecha'];
$id_suc = $_SESSION['id_suc'];
$id_item = $_POST['id_item'];
$cantidad = $_POST['cantidad'];
$usuario = $_SESSION['usu_nombre'];
$operacion = $_POST['operacion'];
$grabar = pg_query($conexion, "SELECT sp_venta_pedidos($id_ped, $id_suc, '$ped_fecha', $id_item, $cantidad, '$usuario', $operacion);");
if($grabar){
    echo pg_last_notice($conexion)."_/_success";
}else{
    echo pg_last_error()."_/_error";
}
