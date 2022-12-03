<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_pais = $_POST['id_pais'];
$pais_descrip = $_POST['pais_descrip'];
$gentilicio = $_POST['gentilicio'];
$pais_abreviatura = $_POST['pais_abreviatura'];
$usuario = $_SESSION['usu_nombre'];
$operacion = $_POST['operacion'];
$grabar = pg_query($conexion,"select sp_paises($id_pais, '$pais_descrip', '$gentilicio', '$pais_abreviatura', '$usuario', $operacion);");
if($grabar){
    echo pg_last_notice($conexion)."_/_success";
}else{
    echo pg_last_error()."_/_error";
}