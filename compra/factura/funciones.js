$(function () {
    panel_factura();
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

function panel_factura(){
    $.ajax({
        url:"panel_factura.php"
    }).done(function(resultado){
        $("#panel-factura").html(resultado);
        formato_tabla("#tabla_panel_factura", 5);
    });
}

function panel_cabecera(id_fac){
    $.ajax({
        url:"panel_cabecera.php",
        method:"POST",
        data:{
            id_fac:id_fac
        }
    }).done(function(resultado){
        $("#panel-cabecera").html(resultado);
    });
}

function panel_detalles(id_fac){
    $.ajax({
        url:"panel_detalles.php",
        method:"POST",
        data:{
            id_fac:id_fac
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
    panel_factura();
    $("#btn-panel-factura").click();
    mensaje('CANCELADO','error');
}

function cabecera(id_fac){
    panel_cabecera(id_fac);
    panel_detalles(id_fac);
    $("#btn-panel-cabecera").click();
}

function detalles(id_fac){
    panel_cabecera(id_fac);
    panel_detalles(id_fac);
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
    var id_fac = $("#id_fac").val();
    $.ajax({
        type:"POST",
        url:"panel_modificar.php",
        data:{
            id_fac: id_fac,
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
    var id_fac = '0';
    var fecha = '2021-01-01';
    var id_pro = '0';
    var id_item = '0';
    var cantidad = '0';
    var precio = '0';
    var operacion = $("#operacion").val();
    if(operacion == '1'){
        fecha = $("#fecha").val();
    }
    if(operacion == '2' || operacion == '3' || operacion == '4'){
        id_fac = $("#id_fac").val();
        fecha = $("#fecha").val();
    }
    if(operacion == '5'){
        id_fac = $("#id_fac").val();
        id_pro = $("#agregar_id_pro").val();
        id_item = $("#agregar_id_item").val();
        cantidad = $("#agregar_cantidad").val();
        precio = $("#agregar_precio").val();
    }
    if(operacion == '6'){
        id_fac = $("#id_fac").val();
        id_pro =$("#modificar_id_pro").val();
        id_item = $("#modificar_id_item").val();
        cantidad = $("#modificar_cantidad").val();
        precio = $("#modificar_precio").val();
      
    }
    if(operacion == '7'){
        id_fac = $("#id_fac").val();
        id_item = $("#eliminar_id_item").val();
    }
    $.ajax({
        type:"POST",
        url:"grabar.php",
        data:{
            id_fac:id_fac,
            fecha:fecha,
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
        panel_factura();
        $("#btn-panel-detalles").click();
    }
    if(operacion == '2'){
        panel_cabecera($("#id_fac").val());
        panel_detalles($("#id_fac").val());
        panel_factura();
        $("#btn-panel-factura").click();
    }
    if(operacion == '3' || operacion == '4'){
        panel_cabecera(-1);
        panel_detalles(-1);
        panel_factura();
        $("#btn-panel-factura").click();
    }
    if(operacion == '5' || operacion == '6' || operacion == '7'){
        panel_cabecera($("#id_fac").val());
        panel_detalles($("#id_fac").val());
    }
    if(operacion == '6'){
        $("#btn-modal-modificar-cerrar").click();
    }
}