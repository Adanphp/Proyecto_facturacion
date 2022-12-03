$(function(){
    datos();
    $('body').on("keydown", function(e){
        if (e.ctrlKey && e.which === 81){
            agregar();
        }
        //$("#tecla_presionada").val(e.keyCode);
    });// presionar la combinacion de teclas ctrl + q para el evento de agregar
}); //todo lo que contenga este comando va a ser ejecutado de inmediato
function datos(){
    $.ajax({ //se hace un llamado
    type:"POST",
    url:"datos.php"   
    }).done(function(resultado){  //retorna los datos concatenando a la variable "resultado"
        $("#div_datos").html(resultado); //le asignamos el resultado a la division que corresponda
        formato_tabla("#tabla_datos", 10 );
    });
}

function formato_tabla(tabla, cantidad){
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
function agregar(){
     $.ajax({
        type:"POST",
        url:"agregar.php"   
    }).done(function(resultado){ 
        $("#modal-agregar").html(resultado); 
        $("#btn-modal-agregar").click();
    });
    
}
function agregar_grabar (){
    $("#operacion").val(1);
    grabar();
}
function editar (id_car){
    $.ajax({
        type:"POST",
        url: "editar.php",
        data:{
                id_car:id_car
        }
    }).done(function(resultado){
        $("#modal-editar").html(resultado);
        $("#btn-modal-editar").click();
        $('#editar_car_descrip').keypress(function(e){
            var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
            if (key===13){
                editar_grabar();
            }
        })
    });
}

function editar_grabar(){
    $("#operacion").val(2);
    grabar();
}
function activar (id_car){
    $("#id_car").val(id_car);
    $("#operacion").val(3);
    grabar();
}
function inactivar (id_car){
    $("#id_car").val(id_car);
    $("#operacion").val(4);
    grabar();
}

function grabar(){
    var operacion = $("#operacion").val();
    if(operacion == '1'){
        var id_car = 0;
        var car_descrip = $("#agregar_car_descrip").val();
        
        
    }
    if(operacion == '2'){
        var id_car = $("#editar_id_car").val();
        var car_descrip = $("#editar_car_descrip").val();
        
    }
    if (operacion == '3' || operacion == '4'){
        var id_car = $("#id_car").val();
        var car_descrip = '';
    }
    $.ajax({
        type: "POST",
        url: "grabar.php",
        data: {
            id_car: id_car,
            car_descrip: car_descrip,
            operacion: operacion
        }
    }).done(function(resultado){
        if(verificar_mensaje(resultado)){
            postgrabar();
        }
    });
    
}

function postgrabar(){
    var operacion = $("#operacion").val();
    datos();
    if (operacion == '1'){
        $("#btn-modal-agregar-cerrar").click();
    }
    if(operacion == '2')
        $("#btn-modal-editar-cerrar").click();
}