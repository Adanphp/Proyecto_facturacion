<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_suc = $_SESSION['id_suc'];
$ajustes = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_ajustes WHERE id_suc = $id_suc;"));
?>
<div class="btn-group">
    <button class="btn btn-success" onclick="agregar();">
        <i class="fa fa-plus-circle"></i> Nuevo Ajuste
    </button>
</div>
<table class="table table-bordered" id="tabla_panel_ajustes" style="width: 100%;">
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
            if(!empty($ajustes)){
                foreach($ajustes as $aj){ ?>
                    <tr>
                        <td><?= $aj['id_ajus']; ?></td>
                        <td><?= $aj['suc_nombre']; ?></td>
                        <td><?= $aj['fecha']; ?></td>
                        <td><?= $aj['estado']; ?></td>
                        <td>
                            <?php if($aj['estado'] == 'PENDIENTE'){ ?>
                            <button class="btn btn-success" onclick="cabecera(<?= $aj['id_ajus']; ?>);"><i class="fa fa-check-circle"></i></button>
                            <button class="btn btn-warning text-white" onclick="cabecera(<?= $aj['id_ajus']; ?>);"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger" onclick="cabecera(<?= $aj['id_ajus']; ?>);"><i class="fa fa-minus-circle"></i></button>
                            <?php } ?>
                            <button class="btn btn-primary" onclick="detalles(<?= $aj['id_ajus']; ?>);"><i class="fa fa-list-alt"></i></button>
                        </td>
                    </tr>
                <?php }
            }
        ?>
    </tbody>
</table>