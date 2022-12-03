<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_cla = $_POST['id_cla'];
$cla_descrip = $_POST['cla_descrip'];
$usuario = $_SESSION['usu_nombre'];
$operacion = $_POST['operacion'];
$grabar = pg_query($conexion,"select sp_clasificaciones($id_cla, '$cla_descrip', '$usuario', $operacion);");
if($grabar){
    echo pg_last_notice($conexion)."_/_success";
}else{
    echo pg_last_error()."_/_error";
}
