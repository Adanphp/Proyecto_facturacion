<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_re = $_POST['id_re'];
$ped_fecha = $_POST['ped_fecha'];
$id_suc = $_SESSION['id_suc'];
$id_tipo_servicio = $_POST['id_tipo_servicio'];
$id_item = $_POST['id_item'];
$cantidad = $_POST['cantidad'];
$precio = $_POST['precio'];
$usuario = $_SESSION['usu_nombre'];
$operacion = $_POST['operacion'];
$grabar = pg_query($conexion, "SELECT sp_reserva($id_re, $id_suc, '$ped_fecha', $id_tipo_servicio, $id_item, $cantidad, $precio, '$usuario', $operacion);");
if($grabar){
    echo pg_last_notice($conexion)."_/_success";
}else{
    echo pg_last_error()."_/_error";
}
