<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_suc = $_SESSION['id_suc'];
$venta_pedidos = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_venta_pedidos WHERE id_suc = $id_suc;"));
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
        if (!empty($venta_pedidos)){
            foreach($venta_pedidos as $vp){?>
        <tr>
            <td><?= $vp ['id_ped']; ?></td>
            <td><?= $vp ['id_ped']; ?></td>
            <td><?= $vp ['suc_nombre']; ?></td>
            <td><?= $vp ['ped_fecha']; ?></td>
            <td><?= $vp ['estado']; ?></td>
            <td>
                <?php if($vp['estado'] == 'PENDIENTE'){?>
                <button class="btn btn-success" onclick="cabecera(<?= $vp['id_ped']; ?>);"><i class="fa fa-check-circle"></i></button>
                 <button class="btn btn-warning text-white" onclick="cabecera(<?= $vp['id_ped']; ?>);"><i class="fa fa-edit"></i></button>
                 <button class="btn btn-danger" onclick="cabecera(<?= $vp['id_ped']; ?>);"><i class="fa fa-minus-circle"></i></button>
                <?php } ?>
                 <button class="btn btn-primary" onclick="detalles(<?= $vp['id_ped']; ?>);"><i class="fa fa-list-alt"></i></button>

            </td>
                                    
        </tr>
            <?php }
        }
       ?>
    </tbody>
    
</table>

