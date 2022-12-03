<?php
require "../../conexion.php";
$conexion = Conexion::conectar();
$datos = pg_query($conexion,"SELECT * FROM proveedores;");
$proveedor = pg_fetch_all($datos);
?>
<table class="table table-bordered" id="tabla_datos">
    <thead>
        <tr>
            <th>#</th>
            <th>Proveedor</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($proveedor)){ foreach($proveedor as $p){ ?>
        <tr>
            <td><?= $p['id_proveedor']; ?></td>
            <td><?= $p['pro_descrip']; ?></td>
            <td><?= $p['estado']; ?></td>
            <td>
                <?php if($p['estado'] == 'ACTIVO'){ ?>
                <button class="btn btn-danger" title="Inactivar" onclick="inactivar(<?= $p['id_proveedor'];?>);"><i class="fa fa-minus-circle"></i></button>
                <button class="btn btn-warning text-white" title="Editar" onclick="editar(<?= $p['id_proveedor'];?>);"><i class="fa fa-edit"></i></button>
                <?php }else{ ?>
                <button class="btn btn-success" title="Activar" onclick="activar(<?= $p['id_proveedor'];?>);"><i class="fa fa-check-circle"></i></button>
                <?php } ?>
            </td>
        </tr>
        <?php } }else{ ?>
        
        <?php } ?>
    </tbody>
</table>


