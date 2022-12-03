<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_per = $_POST['id_per'];
$per_ci = $_POST['per_ci'];
$per_ci = pg_query($conexion, "SELECT * FROM personas WHERE per_ci = '$per_ci' ");
 
if (pg_num_rows($per_ci) > 0) {
    echo '
        <script>
            alert("El Numero de CI ya fue registrado");
        </script> 
    ';
    exit();
}
$per_ruc = $_POST['per_ruc'];
$per_nombre = $_POST['per_nombre'];
$per_apellido = $_POST['per_apellido'];
$per_fenaci = $_POST['per_fenaci'];
$per_celular = $_POST['per_celular'];
$per_email = $_POST['per_email'];
$per_direccion = $_POST['per_direccion'];
$id_ciu = $_POST['id_ciu'];
$id_gen = $_POST['id_gen'];
$id_ec = $_POST['id_ec'];
$es_persona = $_POST['es_persona'];
$operacion = $_POST['operacion'];
$usuario = $_SESSION['usu_nombre'];
$grabar = pg_query($conexion, "SELECT sp_personas($id_per, '$per_ci', '$per_ruc', '$per_nombre', '$per_apellido', '$per_fenaci', '$per_celular', '$per_email', '$per_direccion', $id_ciu, $id_gen, $id_ec, '$es_persona', '$usuario', $operacion);");

if($grabar){
    echo pg_last_notice($conexion)."_/_success";
}else{
    echo pg_last_error()."_/_error";
}
