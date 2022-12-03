<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_clie = $_POST['id_clie'];
$factura = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_factura WHERE id_clie = $id_clie;"));
?>
<div class="btn-group">
    <button class="btn btn-success" onclick="agregar();">
        <i class="fa fa-plus-circle"></i> Generar Factura
    </button>
</div>
<table class="table table-bordered" id="tabla_panel_factura" style="width: 100%;">
    <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Timbrado</th>
            <th>Fabricante</th>
            <th>Tipo Pago</th>
            <th>Vencimiento</th>
            <th>Estado</th>
            <th>Auditoria</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            if(!empty($factura)){
                foreach($factura as $o){ ?>
                    <tr>
                        <td><?= $o['id_fac']; ?></td>
                        <td><?= $o['fecha']; ?></td>
                        <td><?= $o['clie_descrip']; ?></td>
                        <td><?= $o['pro_descrip']; ?></td>
                        <td><?= $o['tp_detalles']; ?></td>
                        <td><?= $o['estado']; ?></td>
                        <td>
                            <?php if($o['estado'] == 'PENDIENTE'){ ?>
                            <button class="btn btn-success" onclick="cabecera(<?= $o['id_fac']; ?>);"><i class="fa fa-check-circle"></i></button>
                            <button class="btn btn-warning text-white" onclick="cabecera(<?= $o['id_fac']; ?>);"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger" onclick="cabecera(<?= $o['id_fac']; ?>);"><i class="fa fa-minus-circle"></i></button>
                            <?php } ?>
                            <button class="btn btn-primary" onclick="detalles(<?= $o['id_fac']; ?>);"><i class="fa fa-list-alt"></i></button>
                        </td>
                    </tr>
                <?php }
            }
        ?>
    </tbody>
</table>