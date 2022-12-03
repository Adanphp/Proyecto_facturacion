<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
//RECIBIMOS LOS DATOS ENVIADOS POR EL METODO POST Y SESSION
$id_ajus = $_POST['id_ajus'];
$id_suc = $_SESSION['id_suc'];
$ajus_fecha = $_POST['ajus_fecha'];
$id_item = $_POST['id_item'];
$anterior = $_POST['anterior'];
$actual = $_POST['actual'];
$id_mot = $_POST['id_mot'];
$usuario = $_SESSION['usu_nombre']; 
$operacion = $_POST['operacion'];
$grabar = pg_query($conexion, "SELECT sp_ajustes($id_ajus , $id_suc, '$ajus_fecha',  $id_item, $anterior, $actual, $id_mot, '$usuario', $operacion);");
if($grabar){
    echo pg_last_notice($conexion)."_/_success";
}else{
    echo pg_last_error()."_/_error";
}

