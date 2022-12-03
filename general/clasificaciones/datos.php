<?php
require "../../conexion.php";
$conexion = Conexion::conectar();
$datos = pg_query($conexion,"SELECT * FROM clasificaciones;");
$clasificaciones = pg_fetch_all($datos);
?>
<table class="table table-bordered" id="tabla_datos">
    <thead>
        <tr>
            <th>#</th>
            <th>Clasificacion</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($clasificaciones)){ foreach($clasificaciones as $c){ ?>
        <tr>
            <td><?= $c['id_cla']; ?></td>
            <td><?= $c['cla_descrip']; ?></td>
            <td><?= $c['estado']; ?></td>
            <td>
                <?php if($c['estado'] == 'ACTIVO'){ ?>
                <button class="btn btn-danger" title="Inactivar" onclick="inactivar(<?= $c['id_cla'];?>);"><i class="fa fa-minus-circle"></i></button>
                <button class="btn btn-warning text-white" title="Editar" onclick="editar(<?= $c['id_cla'];?>);"><i class="fa fa-edit"></i></button>
                <?php }else{ ?>
                <button class="btn btn-success" title="Activar" onclick="activar(<?= $c['id_cla'];?>);"><i class="fa fa-check-circle"></i></button>
                <?php } ?>
            </td>
        </tr>
        <?php } }else{ ?>
        
        <?php } ?>
    </tbody>
</table>

