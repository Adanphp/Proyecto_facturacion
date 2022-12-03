$(function () {
    panel_ajustes();
});

function refrescar_select(){
    $(".select2").select2();
    $(".select2").attr("style", "width: 100%;");
}

function formato_tabla(tabla,cantidad ){
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

//FUNCION PANEL_AJUSTES
function panel_ajustes(){
    $.ajax({
        url:"panel_ajustes.php"
    }).done(function(resultado){
        $("#panel-ajustes").html(resultado);
        formato_tabla("#tabla_panel_ajustes", 5);
    });
}

//FUNCION PANEL_CABECERA
function panel_cabecera(id_ajus){
    $.ajax({
        url:"panel_cabecera.php",
        method:"POST",
        data:{
            id_ajus:id_ajus
        }
    }).done(function(resultado){
        $("#panel-cabecera").html(resultado);
    });
}

//FUNCION PANEL_DETALLES
function panel_detalles(id_ajus){
    $.ajax({
        url:"panel_detalles.php",
        method:"POST",
        data:{
            id_ajus:id_ajus
        }
    }).done(function(resultado){
        $("#panel-detalles").html(resultado);
        refrescar_select();
    });
}

//FUNCION_AGREGAR
function agregar(){
    panel_cabecera(0);
    panel_detalles(0);
    $("#btn-panel-cabecera").click();
}

//FUNCION CANCELAR
function cancelar(){
    panel_cabecera(-1);
    panel_detalles(-1);
    panel_ajustes();
    $("#btn-panel-ajustes").click();
    mensaje('CANCELADO','error');
}

//FUNCION CABECERA
function cabecera(id_ajus){
    panel_cabecera(id_ajus);
    panel_detalles(id_ajus);
    $("#btn-panel-cabecera").click();
}

//FUNCION DETALLES
function detalles(id_ajus){
    panel_cabecera(id_ajus);
    panel_detalles(id_ajus);
    $("#btn-panel-detalles").click();
}

//FUNCION AGREGAR_GRABAR
function agregar_grabar(){
    $("#operacion").val(1);
    grabar();
}

//FUNCION MODIFICAR
function modificar(){
    $("#operacion").val(2);
    grabar();
}

//FUNCION CONFIRMAR
function confirmar(){
    $("#operacion").val(3);
    grabar();
}

//FUNCION ANULAR
function anular(){
    $("#operacion").val(4);
    grabar();
}

//FUNCION AGREGAR_DETALLE
function agregar_detalle(){
    $("#operacion").val(5);
    grabar();
}

//FUNCION QUITAR_DETALLE
function quitar_detalle(id_item){
    $("#eliminar_id_item").val(id_item);
    $("#operacion").val(7);
    grabar();
}

//FUNCION MODIFICAR_DETALLE
function modificar_detalle(id_item){
    var id_ajus= $("#id_ajus").val();
    $.ajax({
        type:"POST",
        url:"panel_modificar.php",
        data:{
            id_ajus: id_ajus,
            id_item: id_item
        }   
    }).done(function(resultado){
        $("#panel-modificar").html(resultado);
        $("#btn-panel-modificar").click();
    });
}

//FUNCION MODIFICAR_DETALLE_GRABAR
function modificar_detalle_grabar(){
    $("#operacion").val(6);
    grabar();
}

//FUNCION GRABAR
function grabar(){
    var id_ajus = '0';
    var id_suc = '0';
    var fecha = '2021-01-01';
    var id_item = '0';
    var anterior = '0';
    var actual = '0';
    var id_mot = '0';
    var operacion = $("#operacion").val();
    if(operacion == '1'){
        fecha = $("fecha").val();
        id_suc = $("#agregar_id_suc").val();
    }
    if(operacion == '2' || operacion == '3' || operacion == '4'){
        id_ajus = $("#id_ajus").val();
        fecha = $("#fecha").val();
    }
    if(operacion == '5'){
        id_ajus = $("#id_ajus").val();
        id_item = $("#agregar_id_item").val();
        anterior = $("#agregar_anterior").val();
        actual = $("#agregar_actual").val();
        id_mot = $("#agregar_id_mot").val();

    }
    if(operacion == '6'){
        id_ajus = $("#id_ajus").val();
        id_item = $("#modificar_id_item").val();
        cantidad = $("#modificar_cantidad").val();
    }
    if(operacion == '7'){
        id_ajus = $("#id_ajus").val();
        id_item = $("#eliminar_id_item").val();
    }
 //ENVIAMOS LOS DATOS POR EL METODO POST 
    $.ajax({
        type:"POST",
        url:"grabar.php",
        data:{
            id_ajus:id_ajus,
            id_suc:id_suc,
            fecha:fecha,
            id_item:id_item,
            anterior:anterior,
            actual:actual,
            id_mot:id_mot,
            operacion:operacion
        }
    }).done(function(resultado){
        if(verificar_mensaje(resultado)){
            postgrabar();
        }
    });
}

//FUNCION POSTGRABAR
function postgrabar(){
    var operacion = $("#operacion").val();
    if(operacion == '1'){
        panel_cabecera(-2);
        panel_detalles(-2);
        panel_ajustes();
        $("#btn-panel-detalles").click();
    }
    if(operacion == '2'){
        panel_cabecera($("#id_ajus").val());
        panel_detalles($("#id_ajus").val());
        panel_ajustes();
        $("#btn-panel-detalles").click();
    }
    if(operacion == '3' || operacion == '4'){
        panel_cabecera(-1);
        panel_detalles(-1);
        panel_ajustes();
        $("#btn-panel-ajustes").click();
    }
    if(operacion == '5' || operacion == '6' || operacion == '7'){
        panel_cabecera($("#id_ajus").val());
        panel_detalles($("#id_ajus").val());
    }
    if(operacion == '6'){
        $("#btn-modal-modificar-cerrar").click();
    }
}