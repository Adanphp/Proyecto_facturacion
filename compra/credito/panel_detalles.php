<?php
$id_cre = $_POST['id_cre'];
var_dump($id_cre);
include '../../conexion.php';
include '../../session.php';
$id_suc = $_SESSION['id_suc']; //Trae bien
$conexion = Conexion::conectar();
if($id_cre == '-1'){ //CUANDO SE RESETEA
    ?>
<label class="text-danger"><i class="fa fa-exclamation-circle"></i> Seleccione un pedido</label>
<?php
}else if($id_cre == '0'){ //CUANDO SE PRESIONA EL BOTON AGREGAR
?>
<div class="card card-primary">
    <div class="card-header text-center elevation-3">
    <input class="form'control" id="id_cre" type="hidden" value= <?= $id_cre ?>>

        Datos del pedido
    </div>
    <div class="card-body">
        

<?php
}else{ //O SE TRATA DE UN PEDIDO DEFINIDO O SE TRATA DEL ULTIMO PEDIDO
    if($id_cre == true){ //SE TRATA DEL ULTIMO PEDIDO
        $cab = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_credito WHERE id_cre = (select max(id_cre) from credito where id_suc = $id_suc);"));
        var_dump($cab);
    }else{ //SE TRATA DE UN PEDIDO DEFINIDO
        $cab = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_credito WHERE id_cre = $id_cre;"));
    }
    $detalles = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_credito_detalles WHERE id_cre = $id_cre;"));
    $disabled = 'disabled';
    if($cab[0]['estado'] == 'CONFIRMADO'){
        $disabled = '';
    }
?>
        <div class="card-body">
            <?php if($detalles){ ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Productos</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Iva</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $total = 0; foreach($detalles as $d){ $total = $total + ($d['precio'] * $d['cantidad']); ?>
                            <tr>
                                <td><?php echo $d['item_descrip']."(".$d['mar_descrip'].")"; ?></td>
                                <td><?php echo $d['cantidad']; ?></td>
                                <td><?php echo $d['precio']; ?></td>
                                <td><?php echo $d['iva']; ?></td>
                                <td>
                                
                                   
                                    <?php if($cab[0]['estado'] == 'PENDIENTE'){ ?>
                                        <button class="btn btn-warning text-white" onclick="modificar_detalle(<?php echo $d['id_item']; ?>);" id="btn-panel-modificar-cerrar"><i class="fa fa-edit"></i></button>
                                        <button class="btn btn-danger" onclick="quitar_detalle(<?php echo $d['id_item']; ?>);"><i class="fa fa-minus-circle"></i></button>
                                    <?php } ?>
                                </td>
                            </tr>
                            <div class="card-body">
                <input type="hidden" id="eliminar_id_item" value="<?php echo $datos[0]['id_item']; ?>">
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Total</th>
                            <th colspan="2"><?php echo $total; ?></th>
                        </tr>
                    </tfoot>
                  
                </table>   
            <?php } ?>
        </div>
    </div>
   
    <?php if($cab){ ?>
        <div class="card-primary col-12">
            <div class="card-header">
                Añadir Producto
            </div>
               
                <div>
    <input class="form'control" id="id_cre" type="hidden" value= <?= $id_cre ?>>
                </div>
                <?php $item = pg_fetch_all(pg_query($conexion, "SELECT * FROM v_item WHERE id_item NOT IN (SELECT id_item FROM credito_detalles WHERE id_cre = ".$cab[0]['id_cre'].") ORDER BY item_descrip, mar_descrip;")); ?>
                <?php if(empty($item)){ ?>
                <label class="text-danger"><i class="fa fa-exclamation-circle"></i> Ya no hay productos disponibles...</label>
                       <?php }else{ ?>                         
                        <div>
                   
                    <div class="form-group">
                        <label>Producto</label>
                        <select class="select2" id="agregar_id_item">
                            <?php foreach($item as $i){ ?>
                            <option value="<?= $i['id_item'];?>"><?= $i['item_descrip']." (".$i['mar_descrip'].")";?></option>
                            <?php } ?>
                        </select>
                    </div>
                        <div class="form-group">
                            <label>Cantidad</label>
                            <input type="number" value="1" class="form-control" id="agregar_cantidad">
                        </div>
                        <div class="form-group">
                            <label>Precio</label>
                            <input type="number" value="1" class="form-control" id="agregar_precio">
                        </div>
                        <div class="form-group">
                            <label>Iva</label>
                            <input type="number" value="1" class="form-control" id="agregar_iva">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success" onclick="agregar_detalles();"><i class="fa fa-plus-circle"></i> Agregar</button>
                        </div>
                 
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
<?php
}