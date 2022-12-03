<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_suc =$_SESSION['id_suc'];
$reserva = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_reserva WHERE id_suc = $id_suc;"));
?>
<div class="btn-group">
<button class="btn btn-success" onclick="agregar();">
        <i class="fa fa-plus-circle"></i> Agregar Nueva Reserva
    </button>
</div>


<table class="table table-bordered" id="tabla_panel_reserva">
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
        <?php if(!empty($reserva)){
                foreach ($reserva as $r){ ?>
                    <tr>
                        <td><?= $r['id_re'];?></td>
                        <td><?= $r['suc_nombre'];?></td>
                        <td><?= $r['ped_fecha'];?></td>
                        <td><?= $r['estado'];?></td>
                        <td>
                            <?php if($r['estado'] == 'PENDIENTE'){ ?>
                            <button class="btn btn-success" onclick="cabecera(<?=$r['id_re']; ?>)"><i class="fa fa-check-circle"></i></button>
                            <button class="btn btn-warning text-white" onclick="cabecera(<?=$r['id_re']; ?>)"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-danger" onclick="cabecera(<?=$r['id_re']; ?>)"><i class="fa fa-minus-circle"></i></button>
                            <?php } ?>
                            <button class="btn btn-primary" onclick="detalles(<?= $r['id_re'];?>)" ><i class="fa fa-list-alt"></i></button>
                        </td>
                    </tr>
                 <?php 
            }
        }
        ?>
    </tbody>
</table>