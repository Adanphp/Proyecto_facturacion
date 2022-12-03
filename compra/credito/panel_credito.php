<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_suc = $_SESSION['id_suc'];
$notas = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_credito WHERE id_suc = $id_suc;"));
?>
<div class="btn-group">
    <button class="btn btn-success" onclick="agregar();">
        <i class="fa fa-plus-circle"></i> Generar Nueva Nota
    </button>
</div>
<table class="table table-bordered" id="tabla_panel_credito" style="width: 100%;">
    <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Factura</th>
            <th>Timbrado</th>
            <th>Proveedor</th>
            <th>Tipo de Documento</th>
            <th>Forma de Pago</th>
            <th>Intervalo_cuota</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            if(!empty($credito)){
                foreach($credito as $c){ ?>
                    <tr>
                        <td><?= $c['id_cre']; ?></td>
                        <td><?= $c['fecha']; ?></td>
                        <td><?= $c['com']; ?></td>
                        <td><?= $c['tim_descrip']; ?></td>
                        <td><?= $c['pro_descrip']; ?></td>
                        <td><?= $c['doc_descri']; ?></td>
                        <td><?= $c['tip_detalles']; ?></td>
                        <td><?= $c['intervalo_cuota']; ?></td>
                        <td><?= $c['estado']; ?></td>

                        <td>
                            <?php if($n['estado'] == 'PENDIENTE'){ ?>
                            <button class="btn btn-success" onclick="cabecera(<?= $c['id_cre']; ?>);"><i class="fa fa-check-circle"></i></button>
                            <button class="btn btn-warning text-white" onclick="cabecera(<?= $c['id_cre']; ?>);"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger" onclick="cabecera(<?= $c['id_cre']; ?>);"><i class="fa fa-minus-circle"></i></button>
                            <?php } ?>
                            <button class="btn btn-primary" onclick="detalles(<?= $c['id_cre']; ?>);"><i class="fa fa-list-alt"></i></button>
                        </td>
                    </tr>
                <?php }
            }
        ?>
    </tbody>
</table>