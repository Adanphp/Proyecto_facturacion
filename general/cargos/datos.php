<?php
require '../../conexion.php';
$conexion = Conexion::conectar();
$datos = pg_query($conexion,"SELECT * FROM cargos");//retorna las matriz en tres dimsiones
$cargo = pg_fetch_all($datos);//simplifica de tres dimensiones a dos dimensiones el indice

?>
<table class="table table-bordered" id="tabla_datos">
    <thead>
        <tr>
            <th>#</th>
            <th>Cargo</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        
    </thead>
 
    <tbody>
       
       <?php if(!empty($cargo)) { foreach($cargo as $p){?> 
        <tr>
            <td><?= $p['id_car'] ?></td>
            <td><?= $p['car_descrip']?></td>
            <td><?= $p['estado']?></td>
            <td>
                <?php if($p['estado'] == 'ACTIVO'){?>
                <button class="btn btn-danger" title="INACTIVAR" onclick="inactivar(<?= $p['id_car']?>);"><i class="fa fa-minus-circle"></i></button>
                <button class="btn btn-warning text-white" title="EDITAR" onclick="editar(<?= $p['id_car']?>);"><i class="fa fa-edit"></i></button>
                <?php }else{?>
                <button class="btn btn-success" title="ACTIVAR" onclick="activar(<?= $p['id_car']?>);"><i class="fa fa-check-circle"></i></button>
        
                <?php }?>
            </td>

       
       <?php } }else{ ?>
            
        <?php }?>
    </tbody>
</table>
