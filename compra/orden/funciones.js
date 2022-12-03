$(function () {
    panel_orden();
});

function refrescar_select(){
    $(".select2").select2();
    $(".select2").attr("style", "width: 100%;");
}

function formato_tabla(tabla,cantidad){
    $(tabla).DataTable({
        "lengthChange": false,
        responsive: "true",
        "iDisplayLength": cantidad,
        language: {
            "sSearch":"Buscar: ",
            "sInfo":"Mostrando resultados del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoFiltered": "(filtrado de entre _MAX_ registros)",
            "sZeroRecords":"No hay resultados",
            "sInfoEmpty":"No hay resultados",
            "oPaginate":{
                "sNext":"Siguiente",
                "sPrevious":"Anterior"
            }
        }
    });
}

function panel_orden(){
    $.ajax({
        url:"panel_orden.php"
    }).done(function(resultado){
        $("#panel-orden").html(resultado);
        formato_tabla("#tabla_panel_orden", 5);
    });
}

function panel_cabecera(id_or){
    $.ajax({
        url:"panel_cabecera.php",
        method:"POST",
        data:{
            id_or:id_or
        }
    }).done(function(resultado){
        $("#panel-cabecera").html(resultado);
    });
}

function panel_detalles(id_or){
    $.ajax({
        url:"panel_detalles.php",
        method:"POST",
        data:{
            id_or:id_or
        }
    }).done(function(resultado){
        $("#panel-detalles").html(resultado);
        refrescar_select();
    });
}

function agregar(){
    panel_cabecera(0);
    panel_detalles(0);
    $("#btn-panel-cabecera").click();
}

function cancelar(){
    panel_cabecera(-1);
    panel_detalles(-1);
    panel_orden();
    $("#btn-panel-orden").click();
    mensaje('CANCELADO','error');
}

function cabecera(id_or){
    panel_cabecera(id_or);
    panel_detalles(id_or);
    $("#btn-panel-cabecera").click();
}

function detalles(id_or){
    panel_cabecera(id_or);
    panel_detalles(id_or);
    $("#btn-panel-detalles").click();
}

function agregar_grabar(){
    $("#operacion").val(1);
    grabar();
}

function modificar(){
    $("#operacion").val(2);
    grabar();
}

function confirmar(){
    $("#operacion").val(3);
    grabar();
}

function anular(){
    $("#operacion").val(4);
    grabar();
}

function agregar_detalle(){
    $("#operacion").val(5);
    grabar();
}

function quitar_detalle(id_item){
    $("#eliminar_id_item").val(id_item);
    $("#operacion").val(7);
    grabar();
}

function modificar_detalle(id_item){
    var id_or = $("#id_or").val();
    $.ajax({
        type:"POST",
        url:"panel_modificar.php",
        data:{
            id_or: id_or,
            id_item: id_item
        }
    }).done(function(resultado){
        $("#panel-modificar").html(resultado);
        $("#btn-panel-modificar").click();
    });
}

function modificar_detalle_grabar(){
    $("#operacion").val(6);
    grabar();
}

function grabar(){
    var id_or = '0';
    var or_fecha = '2021-01-01';
    var id_pro = '0';
    var id_item = '0';
    var cantidad = '0';
    var precio = '0';
    var operacion = $("#operacion").val();
    if(operacion == '1'){
        or_fecha = $("#or_fecha").val();
    }
    if(operacion == '2' || operacion == '3' || operacion == '4'){
        id_or = $("#id_or").val();
        or_fecha = $("#or_fecha").val();
    }
    if(operacion == '5'){
        id_or = $("#id_or").val();
        id_pro = $("#agregar_id_pro").val();
        id_item = $("#agregar_id_item").val();
        cantidad = $("#agregar_cantidad").val();
        precio = $("#agregar_precio").val();
    }
    if(operacion == '6'){
        id_or = $("#id_or").val();
        id_pro =$("#modificar_id_pro").val();
        id_item = $("#modificar_id_item").val();
        cantidad = $("#modificar_cantidad").val();
        precio = $("#modificar_precio").val();
      
    }
    if(operacion == '7'){
        id_or = $("#id_or").val();
        id_item = $("#eliminar_id_item").val();
    }
    $.ajax({
        type:"POST",
        url:"grabar.php",
        data:{
            id_or:id_or,
            or_fecha:or_fecha,
            id_pro:id_pro,
            id_item:id_item,
            cantidad:cantidad,
            precio:precio,
            operacion:operacion
        }
    }).done(function(resultado){
        if(verificar_mensaje(resultado)){
            postgrabar();
        }
    });
}

function postgrabar(){
    var operacion = $("#operacion").val();
    if(operacion == '1'){
        panel_cabecera(-2);
        panel_detalles(-2);
        panel_orden();
        $("#btn-panel-detalles").click();
    }
    if(operacion == '2'){
        panel_cabecera($("#id_or").val());
        panel_detalles($("#id_or").val());
        panel_orden();
        $("#btn-panel-orden").click();
    }
    if(operacion == '3' || operacion == '4'){
        panel_cabecera(-1);
        panel_detalles(-1);
        panel_orden();
        $("#btn-panel-orden").click();
    }
    if(operacion == '5' || operacion == '6' || operacion == '7'){
        panel_cabecera($("#id_or").val());
        panel_detalles($("#id_or").val());
    }
    if(operacion == '6'){
        $("#btn-modal-modificar-cerrar").click();
    }
}