<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_suc = $_SESSION['id_suc'];
$compra_pedidos = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_compra_pedidos WHERE id_suc = $id_suc;"));
?>
<div class="btn-group">
    <button class="btn btn-success" onclick="agregar();">
        <i class="fa fa-plus-circle"></i> Agregar Nuevo Pedido
    </button>
</div>
<table class="table table-bordered" id="tabla_panel_pedidos">
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
            if(!empty($compra_pedidos)){
                foreach($compra_pedidos as $cp){ ?>
                    <tr>
                        <td><?= $cp['id_ped']; ?></td>
                        <td><?= $cp['suc_nombre']; ?></td>
                        <td><?= $cp['ped_fecha']; ?></td>
                        <td><?= $cp['estado']; ?></td>
                        <td>
                            <?php if($cp['estado'] == 'PENDIENTE'){ ?>
                            <button class="btn btn-success" onclick="cabecera(<?= $cp['id_ped']; ?>);"><i class="fa fa-check-circle"></i></button>
                            <button class="btn btn-warning text-white" onclick="cabecera(<?= $cp['id_ped']; ?>);"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger" onclick="cabecera(<?= $cp['id_ped']; ?>);"><i class="fa fa-minus-circle"></i></button>
                            <?php } ?>
                            <button class="btn btn-primary" onclick="detalles(<?= $cp['id_ped']; ?>);"><i class="fa fa-list-alt"></i></button>
                        </td>
                    </tr>
                <?php }
            }
        ?>
    </tbody>
</table>