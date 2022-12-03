<?php
require '../../conexion.php';
require '../../session.php';
$conexion = Conexion::conectar();
$id_suc = $_SESSION['id_suc'];
$id_ped = $_POST['id_ped'];
if($id_ped == '-1'){ //AL PRESIONAR CANCELAR
?>
<label class="text-danger">
    <i class="fa fa-exclamation-circle"></i> Seleccione una cabecera
</label>
<?php
}else if($id_ped == '0'){ //AL PRESIONAR AGREGAR
?>
<label class="text-danger">
    <i class="fa fa-exclamation-circle"></i> Guarde los datos de la cabecera...
</label>
<?php
}else{
    if($id_ped == '-2'){ //EL ULTIMO PEDIDO REGISTRADO
        $cab = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_compra_pedidos WHERE id_ped = (select max(id_ped) from compra_pedidos);"));
    }else{ //UN PEDIDO REGISTRADO CON ANTERIORIDAD
        $cab = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_compra_pedidos WHERE id_ped = $id_ped;"));
    }
    $detalles = pg_fetch_all(pg_query($conexion,"SELECT * FROM v_compra_pedidos_detalles WHERE id_ped = $id_ped;"));
    ?>
<div class="row">
    <div class="card-success col-8">
        <div class="card-header">
            Detalles Cargados
        </div>
        <div class="card-body">
            <?php if(empty($detalles)){ ?>
            <label class="text-danger"><i class="fa fa-exclamation-circle"></i> Sin detalles cargados...</label>
            <?php }else{ ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($detalles as $d){ ?>
                    <tr>
                        <td><?= $d['item_descrip']."(".$d['mar_descrip'].")"; ?></td>
                        <td><?= $d['cantidad']; ?></td>
                        <td>
                            <?php if($cab[0]['estado'] == 'PENDIENTE'){ ?>
                                <button class="btn btn-warning text-white" onclick="editar_detalle(<?= $d['id_item'];?>);"><i class="fa fa-edit"></i></button>
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
        <div class="card-info col-4">
            <div class="card-header">
                Añadir Producto
            </div>
            <div class="card-body">
                <?php $items = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_items WHERE id_item NOT IN (SELECT id_item FROM compra_pedidos_detalles WHERE id_ped = ".$cab[0]['id_ped'].") ORDER BY item_descrip, mar_descrip;")); ?>
                <?php if(empty($items)){ ?>
                    <label class="text-danger"><i class="fa fa-exclamation-circle"></i> Ya no hay productos disponibles...</label>
                <?php }else{ ?>
                    <div class="form-group">
                        <label>Producto</label>
                        <select class="select2" id="agregar_id_item">
                            <?php foreach($items as $i){ ?>
                            <option value="<?= $i['id_item'];?>"><?= $i['item_descrip']." (".$i['mar_descrip'].")";?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Cantidad</label>
                        <input type="number" class="form-control" value="1" id="agregar_cantidad">
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-success" onclick="agregar_detalle();"><i class="fa fa-plus-circle"></i> Agregar Producto</button>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>
<?php
} ?>