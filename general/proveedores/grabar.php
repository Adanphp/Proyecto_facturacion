<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_proveedor = $_POST['id_proveedor'];
$pro_descrip = $_POST['pro_descrip'];
$usuario = $_SESSION['usu_nombre'];
$operacion = $_POST['operacion'];
$grabar = pg_query($conexion,"select sp_proveedores($id_proveedor, '$pro_descrip', '$usuario', $operacion);");
if($grabar){
    echo pg_last_notice($conexion)."_/_success";
}else{
    echo pg_last_error()."_/_error";
}


