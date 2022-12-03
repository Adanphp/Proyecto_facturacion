<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_suc = $_SESSION['id_suc'];
$orden = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_orden WHERE id_suc = $id_suc;"));
?>
<div class="btn-group">
    <button class="btn btn-success" onclick="agregar();">
        <i class="fa fa-plus-circle"></i> Generar Nueva Orden
    </button>
</div>
<table class="table table-bordered" id="tabla_panel_orden" style="width: 100%;">
    <thead>
        <tr>
            <th>#</th>
            <th>Sucursal</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            if(!empty($orden)){
                foreach($orden as $o){ ?>
                    <tr>
                        <td><?= $o['id_or']; ?></td>
                        <td><?= $o['suc_nombre']; ?></td>
                        <td><?= $o['or_fecha']; ?></td>
                        <td><?= $o['estado']; ?></td>
                        <td>
                            <?php if($o['estado'] == 'PENDIENTE'){ ?>
                            <button class="btn btn-success" onclick="cabecera(<?= $o['id_or']; ?>);"><i class="fa fa-check-circle"></i></button>
                            <button class="btn btn-warning text-white" onclick="cabecera(<?= $o['id_or']; ?>);"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger" onclick="cabecera(<?= $o['id_or']; ?>);"><i class="fa fa-minus-circle"></i></button>
                            <?php } ?>
                            <button class="btn btn-primary" onclick="detalles(<?= $o['id_or']; ?>);"><i class="fa fa-list-alt"></i></button>
                        </td>
                    </tr>
                <?php }
            }
        ?>
    </tbody>
</table>