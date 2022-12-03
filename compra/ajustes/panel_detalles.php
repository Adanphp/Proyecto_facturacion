<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_suc = $_SESSION['id_suc'];
$id_ajus = $_POST['id_ajus'];

if($id_ajus== '-1'){ //AL PRESIONAR CANCELAR
?>
<label class="text-danger">
    <i class="fa fa-exclamation-circle"></i> Seleccione una cabecera
</label>
<?php
}else if($id_ajus == '0'){ //AL PRESIONAR AGREGAR
?>
<label class="text-danger">
    <i class="fa fa-exclamation-circle"></i> Datos grabados...
</label>
<?php
}else{
    if($id_ajus == '-2'){ //EL ULTIMO PEDIDO REGISTRADO
        $cab = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_ajustes WHERE id_ajus = (select max(id_ajus) from ajustes);"));
    }else{ //UN PEDIDO REGISTRADO CON ANTERIORIDAD
        $cab = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_ajustes WHERE id_ajus = $id_ajus;"));
    }
    $detalles = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_ajustes_detalles WHERE id_ajus = $id_ajus;"));
    ?>

        <div class="card-body">
            <?php if(empty($detalles)){ ?>
            <?php }else{ ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        
                        <th>Producto</th>
                        <th>Anterior</th>
                        <th>Actual</th>
                        <th>Observacion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($detalles as $d){ ?>
                    <tr>
                        <td><?= $d['item_descrip']; ?></td>
                        <td><?= $d['anterior']; ?></td>
                        <td><?= $d['actual']; ?></td>
                        <td><?= $d['mot_descrip']; ?></td>
                    

                            <td>
                            <?php if($cab[0]['estado'] == 'PENDIENTE'){ ?>
                                <button class="btn btn-warning text-white" onclick="modificar_detalle(<?= $d['id_item'];?>);"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger" onclick="quitar_detalle(<?= $d['id_item'];?>);"><i class="fa fa-minus-circle"></i></button>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } ?>
        </div>
    </div>
    <?php if($cab[0]['estado'] == 'PENDIENTE'){ ?>
        <div class="card-primary col-12">
            <div class="card-header">
                Detalles
            </div>
            <div class="card-body">
                <?php $items = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_items WHERE id_item NOT IN (SELECT id_item FROM ajustes_detalles WHERE id_ajus = ".$cab[0]['id_ajus'].") ORDER BY item_descrip;")); ?>
                <?php if(empty($items)){ ?>
                <?php }else{ ?>
               
                    <?php $motivo_ajustes = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_motivo_ajustes WHERE id_mot NOT IN (SELECT id_mot FROM ajustes_detalles WHERE id_ajus = ".$cab[0]['id_ajus'].") ORDER BY mot_descrip;")); ?>
                <?php if(empty($mot_ajustes)){ ?>
                    <?php } ?>
               
                    <div class="form-group">
                        <label>Producto</label>
                        <select class="select2" id="agregar_id_item">
                            <?php foreach($items as $i){ ?>
                            <option value="<?= $i['id_item'];?>"><?= $i['item_descrip']."";?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Cantidad Anterior</label>
                        <input type="number" class="form-control" value="" id="agregar_anterior">
                    </div>
                    <div class="form-group">
                        <label>Cantidad Actual</label>
                        <input type="number" class="form-control" value="" id="agregar_actual">
                    </div>
                    
                    <div class="form-group">
                        <label>Motivo</label>
                        <select class="select2" id="agregar_id_mot">
                            <?php foreach($motivo_ajustes as $m){ ?>
                            <option value="<?= $m['id_mot'];?>"><?= $m['mot_descrip']."";?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-success" onclick="agregar_detalle();"><i class="fa fa-plus-circle"></i> Guardar</button>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>
<?php
} ?>