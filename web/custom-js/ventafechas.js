$(function () {

  /*Evento change de ChkEstado en el cual al chequear o deschequear cambia el label*/
$("#chkEstado").change(function() {
if(this.checked) {
 $("#chkEstado").val(true);
 document.getElementById("lblchk").innerHTML = 'REPORTES DETALLADOS';
} else {
$("#chkEstado").val(false);
document.getElementById("lblchk").innerHTML = 'REPORTES TOTALIZADOS';
}
});


$(document).on('click', '#print_vigentes', function(e){

       Print_Report('Vigentes');
       e.preventDefault();
  });

  $(document).on('click', '#print_anuladas', function(e){

       Print_Report('Anuladas');
       e.preventDefault();
 });

$(document).on('click', '#print_contado', function(e){

       Print_Report('Contado');
       e.preventDefault();
 });

$(document).on('click', '#print_credito', function(e){

       Print_Report('Credito');
       e.preventDefault();
 });

 var mySwitch = new Switchery($('.switchery')[0], {
          size:"small",
          color: '#0D74E9',
          secondaryColor :'#26A69A'
      });



// Setting datatable defaults
$.extend( $.fn.dataTable.defaults, {
    autoWidth: false,
    columnDefs: [{
        orderable: false,
        width: '100px'
    }],
    dom: '<"datatable-header"fpl><"datatable-scroll"t><"datatable-footer"ip>',
    language: {
        search: '<span>Buscar:</span> _INPUT_',
        lengthMenu: '<span>Ver:</span> _MENU_',
        emptyTable: "No existen registros",
        sZeroRecords:    "No se encontraron resultados",
        sInfoEmpty:      "No existen registros que contabilizar",
        sInfoFiltered:   "(filtrado de un total de _MAX_ registros)",
        sInfo:           "Mostrando del registro _START_ al _END_ de un total de _TOTAL_ datos",
        paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }

    },
    drawCallback: function () {
        $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
    },
    preDrawCallback: function() {
        $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
    }
});


// Basic datatable
$('.datatable-basic').DataTable();

// Add placeholder to the datatable filter option
$('.dataTables_filter input[type=search]').attr('placeholder','Escriba para filtrar...');


// Enable Select2 select for the length option
$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});


  $('#txtF1').datetimepicker({
        locale: 'es',
        format: 'DD/MM/YYYY',
        useCurrent:true,
        viewDate: moment()

  });

  $('#txtF2').datetimepicker({
        locale: 'es',
        format: 'DD/MM/YYYY',
        useCurrent: false
  });

$("#txtF1").on("dp.change", function (e) {
            $('#txtF2').data("DateTimePicker").minDate(e.date);
        });
$("#txtF2").on("dp.change", function (e) {
    $('#txtF1').data("DateTimePicker").maxDate(e.date);
});


     var validator = $("#frmSearch").validate({

      ignore: '.select2-search__field', // ignore hidden fields
      errorClass: 'validation-error-label',
      successClass: 'validation-valid-label',

      highlight: function(element, errorClass) {
          $(element).removeClass(errorClass);
      },
      unhighlight: function(element, errorClass) {
          $(element).removeClass(errorClass);
      },
      // Different components require proper error label placement
      errorPlacement: function(error, element) {

        // Input with icons and Select2
         if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
              error.appendTo( element.parent() );
          }

         // Input group, styled file input
          else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
              error.appendTo( element.parent().parent() );
          }

        else {
            error.insertAfter(element);
        }

      },

      rules: {
        txtF1:{
          required: true
        },
        txtF2:{
          required:true
        }
      },
    validClass: "validation-valid-label",
     success: function(label) {
          label.addClass("validation-valid-label").text("Correcto.")
      },

       submitHandler: function (form) {
           buscar_datos();
        }
     });

  $(document).on('click', '#delete_product', function(e){

       var productId = $(this).data('id');
       SwalDelete(productId);
       e.preventDefault();
  });

  $(document).on('click', '#pay_money', function(e){

       var productId = $(this).data('id');
       Finalizar(productId);
       e.preventDefault();
  });

  $(document).on('click', '#detail_pay', function(e){

       var productId = $(this).data('id');
       detalle_venta(productId);
       e.preventDefault();
  });

  $(document).on('click', '#print_receip', function(e){

       var productId = $(this).data('id');
       Imprimir_Ticket(productId);
       e.preventDefault();
  });
  
  // ====================================================================
  //  EVENTO CLICK PARA EL BOTÓN "ACTUALIZAR VENTA"
  //  (Pegar esto antes del cierre "});" de la función principal)
  // ====================================================================
  $(document).on('click', '#btnRegistrar', function(e){
        e.preventDefault(); // Evita que se recargue la pagina

        // Verificamos si estamos en modo edición
        var modo = $("#action_mode").val();

        if (modo === 'editar') {
            
            var btn = $(this);
            var originalText = btn.html();
            
            // 1. Efecto visual de carga
            btn.html('<i class="icon-spinner4 spinner"></i> Guardando...');
            btn.prop('disabled', true);

            // 2. Enviar datos por AJAX
            $.ajax({
                url: 'web/ajax/ajxventa.php?action=editar',
                type: 'POST',
                // Serializamos el formulario de pago Y los inputs de la tabla de productos
                data: $("#frmPago").serialize(),
                success: function(data) {
                    try {
                        // Limpieza de respuesta por si el servidor devuelve comillas extra
                        var response = JSON.parse(data.replace(/^"|"$/g, ''));
                        
                        if(response == "Validado") {
                            swal({
                                title: "¡Actualizado!",
                                text: "La venta ha sido modificada correctamente.",
                                confirmButtonColor: "#66BB6A",
                                type: "success"
                            }, function() {
                                // Al cerrar la alerta, recargamos la pagina
                                window.location.reload();
                            });
                            $("#modal_iconified_cash").modal('hide');
                        } else {
                            swal({
                                title: "Error",
                                text: "No se pudo actualizar la venta. Intente de nuevo.",
                                confirmButtonColor: "#EF5350",
                                type: "error"
                            });
                            // Restaurar botón
                            btn.html(originalText);
                            btn.prop('disabled', false);
                        }
                    } catch(e) {
                        console.log("Error parseando respuesta:", data);
                        // Si la respuesta no es JSON pero contiene "Validado"
                        if(data.indexOf("Validado") !== -1){
                             window.location.reload();
                        } else {
                            alert("Error del sistema: " + data);
                            btn.html(originalText);
                            btn.prop('disabled', false);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    alert("Error de conexión: " + error);
                    btn.html(originalText);
                    btn.prop('disabled', false);
                }
            });
        } 
        // Si NO es modo editar (es nueva venta), deja que tu lógica original maneje el submit (si aplica)
        // o agrega lógica aquí si es necesario.
  });
  
  // ====================================================================
    //  AUTOCOMPLETE PARA AGREGAR PRODUCTOS
    // ====================================================================
    if($("#txtBuscarProducto").length > 0) {
        $("#txtBuscarProducto").autocomplete({
            source: "web/ajax/ajxventa.php?action=autocomplete",
            minLength: 2, // Empieza a buscar al escribir 2 letras
            select: function(event, ui) {
                // Al seleccionar, agregamos el producto a la tabla
                agregarFila(ui.item);
                
                // Limpiamos el buscador
                $(this).val(""); 
                return false;
            }
        }).data("ui-autocomplete")._renderItem = function(ul, item) {
            // Diseño personalizado del resultado de búsqueda
            return $("<li>")
                .append("<a><span class='text-bold'>" + item.label + "</span><br>" + 
                        "<span class='text-muted'>Precio: " + item.precio_venta + " | Stock: " + item.stock + "</span></a>")
                .appendTo(ul);
        };
    }

});

function buscar_datos()
{
  var fecha1 = $("#txtF1").val();
  var fecha2 = $("#txtF2").val();

    if(fecha1!="" && fecha2!="")
    {
        $.ajax({

           type:"GET",
           url:"web/ajax/reload-ventas_fecha.php?fecha1="+fecha1+"&fecha2="+fecha2,
           success: function(data){
              $('#reload-div').html(data);
           }

       });
    } else {

      $.ajax({

           type:"GET",
           url:"web/ajax/reload-ventas_fecha.php?fecha1=empty&fecha2=empty",
           success: function(data){
              $('#reload-div').html(data);
           }

       });

    }

}

function detalle_venta(VentaNo)
{
    $.ajax({

       type:"GET",
       url:"web/ajax/reload-detalle-venta.php?numero_transaccion="+VentaNo,
       success: function(data){
          $('#reload-detalle').html(data);
       }

   });

}

function Imprimir_Ticket(VentaNo)
{
   window.open('reportes/TicketV.php?venta='+btoa(VentaNo),
  'win2','status=yes,toolbar=yes,scrollbars=yes,titlebar=yes,menubar=yes,'+
  'resizable=yes,width=600,height=600,directories=no,location=no'+
  'fullscreen=yes');

}


function SwalDelete(productId){
              swal({
                title: "¿Está seguro que desea anular la transacción?",
                text: "Este proceso es irreversible!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#EF5350",
                confirmButtonText: "Si, Anular",
                cancelButtonText: "No, volver atras",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm){
                if (isConfirm) {
                     return new Promise(function(resolve) {
                        $.ajax({
                        url: 'web/ajax/ajxanulacion.php',
                        type: 'POST',
                        data: 'proceso=Anular_Venta&numero_transaccion='+productId,
                        dataType: 'json'
                        })
                        .done(function(response){
                         swal('Anulada!', response.message, response.status);
                          buscar_datos();
                        })
                        .fail(function(){
                         swal('Oops...', 'Algo salio mal al procesar tu peticion!', 'error');
                        });
                     });
                }
                else {
                    swal({
                        title: "Esta bien",
                        text: "Puedes seguir donde te quedaste",
                        confirmButtonColor: "#2196F3",
                        type: "info"
                    });
                }
            });

 }

   function Finalizar(productId){
              swal({
                title: "¿Está seguro que desea finalizar la venta?",
                text: "Este proceso es irreversible!",
                showCancelButton: true,
                confirmButtonColor: "#4caf50",
                confirmButtonText: "Si, Finalizar",
                cancelButtonText: "No, volver atras",
                closeOnConfirm: false,
                closeOnCancel: false,
                imageUrl: "web/assets/images/change.png",
                allowOutsideClick: false
            },
            function(isConfirm){
                if (isConfirm) {
                     return new Promise(function(resolve) {
                        $.ajax({
                        url: 'web/ajax/ajxanulacion.php',
                        type: 'POST',
                        data: 'proceso=Finalizar_Venta&numero_transaccion='+productId,
                        dataType: 'json'
                        })
                        .done(function(response){
                         swal('Finalizada!', response.message, response.status);
                         buscar_datos();
                        })
                        .fail(function(){
                         swal('Oops...', 'Algo salio mal al procesar tu peticion!', 'error');
                        });
                     });
                }
                else {
                    swal({
                        title: "Esta bien",
                        text: "Puedes seguir donde te quedaste",
                        confirmButtonColor: "#2196F3",
                        type: "info"
                    });
                }
            });

 }

  function Print_Report(Criterio)
{


  var fecha1 = $("#txtF1").val();
  var fecha2 = $("#txtF2").val();

  var estado = $('#chkEstado').is(':checked') ? 1 : 0;

  if(estado == 0){

    if(fecha1!="" && fecha2!="")
    {
        if(Criterio == 'Vigentes')
        {
             window.open('reportes/Ventas_Vigentes_Fechas.php?fecha1='+fecha1+'&fecha2='+fecha2,
            'win2',
            'status=yes,toolbar=yes,scrollbars=yes,titlebar=yes,menubar=yes,'+
            'resizable=yes,width=800,height=800,directories=no,location=no'+
            'fullscreen=yes');

        } else if (Criterio == 'Anuladas') {

             window.open('reportes/Ventas_Anuladas_Fechas.php?fecha1='+fecha1+'&fecha2='+fecha2,
            'win2',
            'status=yes,toolbar=yes,scrollbars=yes,titlebar=yes,menubar=yes,'+
            'resizable=yes,width=600,height=600,directories=no,location=no'+
            'fullscreen=yes');

        } else if (Criterio == 'Contado') {

             window.open('reportes/Ventas_Contado_Fechas.php?fecha1='+fecha1+'&fecha2='+fecha2,
            'win2',
            'status=yes,toolbar=yes,scrollbars=yes,titlebar=yes,menubar=yes,'+
            'resizable=yes,width=600,height=600,directories=no,location=no'+
            'fullscreen=yes');

        } else if (Criterio == 'Credito'){

             window.open('reportes/Ventas_Credito_Fechas.php?fecha1='+fecha1+'&fecha2='+fecha2,
            'win2',
            'status=yes,toolbar=yes,scrollbars=yes,titlebar=yes,menubar=yes,'+
            'resizable=yes,width=600,height=600,directories=no,location=no'+
            'fullscreen=yes');
        }

    } else {


        swal({
                title: "Ops!",
                imageUrl: "web/assets/images/calendar.png",
                text: "Debes seleccionar 2 fechas",
                confirmButtonColor: "#EF5350"
         });



    }

 } else {


   if(fecha1!="" && fecha2!="")
   {
       if(Criterio == 'Vigentes')
       {
            window.open('reportes/VentasD_Vigentes_Fechas.php?fecha1='+fecha1+'&fecha2='+fecha2,
           'win2',
           'status=yes,toolbar=yes,scrollbars=yes,titlebar=yes,menubar=yes,'+
           'resizable=yes,width=800,height=800,directories=no,location=no'+
           'fullscreen=yes');

       } else if (Criterio == 'Anuladas') {

            window.open('reportes/VentasD_Anuladas_Fechas.php?fecha1='+fecha1+'&fecha2='+fecha2,
           'win2',
           'status=yes,toolbar=yes,scrollbars=yes,titlebar=yes,menubar=yes,'+
           'resizable=yes,width=600,height=600,directories=no,location=no'+
           'fullscreen=yes');

       } else if (Criterio == 'Contado') {

            window.open('reportes/VentasD_Contado_Fechas.php?fecha1='+fecha1+'&fecha2='+fecha2,
           'win2',
           'status=yes,toolbar=yes,scrollbars=yes,titlebar=yes,menubar=yes,'+
           'resizable=yes,width=600,height=600,directories=no,location=no'+
           'fullscreen=yes');

       } else if (Criterio == 'Credito'){

            window.open('reportes/VentasD_Credito_Fechas.php?fecha1='+fecha1+'&fecha2='+fecha2,
           'win2',
           'status=yes,toolbar=yes,scrollbars=yes,titlebar=yes,menubar=yes,'+
           'resizable=yes,width=600,height=600,directories=no,location=no'+
           'fullscreen=yes');
       }

   } else {


       swal({
               title: "Ops!",
               imageUrl: "web/assets/images/calendar.png",
               text: "Debes seleccionar 2 fechas",
               confirmButtonColor: "#EF5350"
        });

   }



 }


}

// ============================================================================
//  FUNCION PARA CARGAR LA EDICIÓN (CORREGIDA Y ROBUSTA)
// ============================================================================
function editarVenta(idVenta) {
    var block = $("#reload-div");
    // Bloqueo de UI (si usas blockUI, si no, comenta estas lineas)
    if($.isFunction($.fn.block)) {
        $(block).block({ message: 'Cargando...', css: { border: 'none', padding: '15px', backgroundColor: '#000', '-webkit-border-radius': '10px', '-moz-border-radius': '10px', opacity: .5, color: '#fff' } });
    }

    $.ajax({
        url: 'web/ajax/ajxventa.php?action=buscar_para_editar',
        type: 'POST',
        data: { idventa: idVenta },
        dataType: 'json',
        success: function(data) {
            if($.isFunction($.fn.unblock)) { $(block).unblock(); }

            if(data.status == 'success'){
                console.log("Datos recibidos:", data); // PARA DEPURAR

                var cabecera = data.cabecera;
                var detalles = data.detalles;

                // ---------------------------------------------------
                // 1. LLENAR CAMPOS DE CABECERA
                // ---------------------------------------------------
                $("#txtIdVentaEditar").val(idVenta);
                $("#action_mode").val("editar");

                // Fecha
                $("#txtFechaVenta").val(cabecera.fecha_venta);

                // Cliente (Intenta por ID, si no, busca por texto)
                if(cabecera.idcliente){
                     $("#cbCliente").val(cabecera.idcliente).trigger('change');
                } else {
                    // Fallback si no viene ID: busca el texto en el select
                    $("#cbCliente option").filter(function() {
                        return $(this).text().includes(cabecera.cliente); 
                    }).prop('selected', true).trigger('change');
                }

                // Comprobante
                $("#cbCompro").val(cabecera.tipo_comprobante).trigger('change');

                // Método de Pago
                var pago = cabecera.tipo_pago ? cabecera.tipo_pago.toUpperCase() : ''; 
                var valPago = '1'; 
                if(pago === 'TARJETA') valPago = '2';
                if(pago.indexOf('AMBOS') !== -1 || pago.indexOf('Y') !== -1) valPago = '3';
                if(pago === 'DEPOSITO') valPago = '4';
                $("#cbMPago").val(valPago).trigger('change');

                // Datos Monetarios (Usamos parseFloat para asegurar numeros)
                $("#txtMonto").val(cabecera.pago_efectivo);
                $("#txtCambio").val(cabecera.cambio);
                $("#txtNotas").val(cabecera.notas || '');

                // ---------------------------------------------------
                // 2. LLENAR TABLA DE PRODUCTOS
                // ---------------------------------------------------
                $("#tbldetalle tbody").empty();

                $.each(detalles, function(i, item) {
                    // Preparamos el objeto tal cual lo espera la función agregarFila
                    // Nota: Tu BD devuelve 'precio_unitario', el autocomplete devuelve 'precio_venta'
                    // Normalizamos aqui:
                    var productoObj = {
                        idproducto: item.idproducto,
                        codigo_barra: item.codigo_barra || item.codigo_interno,
                        nombre_producto: item.nombre_producto,
                        cantidad: item.cantidad,
                        // Usamos el precio que viene de la venta guardada, no el actual del sistema
                        precio_venta: item.precio_unitario, 
                        exento: item.exento, // Valor monetario exento
                        descuento: item.descuento,
                        importe: item.importe,
                        fecha_vence: item.fecha_vence,
                        stock: null // No validamos stock estricto al cargar histórico
                    };
                    agregarFila(productoObj);
                    
                    var fila = `
                    <tr>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-xs" onclick="$(this).closest('tr').remove(); calcularTotalesEdicion();">
                                <i class="icon-trash"></i>
                            </button>
                        </td>
                        <td>
                            <input type="hidden" name="idproducto[]" value="${item.idproducto}">
                            <input type="hidden" name="fecha_vence[]" value="${item.fecha_vence}">
                            <small class="text-muted">${item.codigo_barra}</small><br>
                            <strong>${item.nombre_producto}</strong>
                        </td>
                        <td class="text-center">
                            <input type="number" class="form-control input-xs text-center" name="cantidad[]" 
                            value="${item.cantidad}" style="width:70px" min="1" step="0.01" 
                            onkeyup="recalcularFila(this)" onchange="recalcularFila(this)">
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control input-xs text-center" name="precio[]" 
                            value="${item.precio_unitario}" style="width:80px" readonly>
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control input-xs text-center input-exento" name="exento[]" 
                            value="${item.exento}" style="width:80px" readonly>
                        </td>
                        <td class="text-center">
                            <input type="number" class="form-control input-xs text-center" name="descuento[]" 
                            value="${item.descuento}" style="width:70px" 
                            onkeyup="recalcularFila(this)" onchange="recalcularFila(this)">
                        </td>
                        <td class="text-center">
                            <input type="text" class="form-control input-xs text-center input-importe" name="importe[]" 
                            value="${item.importe}" style="width:90px" readonly>
                        </td>
                        <td class="text-center">
                            <small>${item.fecha_vence ? item.fecha_vence : '-'}</small>
                        </td>
                    </tr>`;
                    
                    $("#tbldetalle tbody").append(fila);
                });

                // 3. FORZAR CÁLCULO DE TOTALES (Con un pequeño delay para asegurar DOM listo)
                setTimeout(function(){
                    calcularTotalesEdicion();
                }, 100);

                // 4. MOSTRAR MODAL
                $("#modal_iconified_cash").modal('show');

            } else {
                alert("Error al cargar datos: " + data);
            }
        },
        error: function(e){
            console.log(e);
            alert("Error de comunicación con el servidor");
        }
    });
}

// ============================================================================
//  FUNCIONES DE CÁLCULO (Reemplazar también)
// ============================================================================
// ============================================================================
//  1. RECALCULAR FILA INDIVIDUAL (Actualiza Importe y revisa si es Exento)
// ============================================================================
function recalcularFila(element) {
    var row = $(element).closest('tr');
    
    // Obtener valores (protección contra vacíos)
    var cant = parseFloat(row.find('input[name="cantidad[]"]').val()) || 0;
    var prec = parseFloat(row.find('input[name="precio[]"]').val()) || 0;
    var desc = parseFloat(row.find('input[name="descuento[]"]').val()) || 0;
    
    // Calcular Importe Neto: (Cantidad * Precio) - Descuento
    var nuevoImporte = (cant * prec) - desc;
    if(nuevoImporte < 0) nuevoImporte = 0;

    // Actualizar visualmente el Importe
    row.find('input[name="importe[]"]').val(nuevoImporte.toFixed(2));
    
    // --- LÓGICA EXENTO ---
    // Verificamos si el campo EXENTO ya tenía valor (venía de BD o Autocomplete)
    // Si tiene valor > 0, asumimos que el producto es exento.
    var inputExento = row.find('input[name="exento[]"]');
    var eraExento = (parseFloat(inputExento.val()) || 0) > 0;

    if(eraExento){
        // Si es exento, la columna Exento debe ser igual al Importe total
        inputExento.val(nuevoImporte.toFixed(2));
    } else {
        // Si NO es exento (es gravado), la columna Exento debe ser 0.00
        inputExento.val("0.00");
    }

    // Recalcular Totales Generales
    calcularTotalesEdicion();
}

// ============================================================================
//  2. CALCULAR TOTALES (Lógica Honduras 15% + Selector Específico)
// ============================================================================
function calcularTotalesEdicion() {
    var total_venta = 0;      
    var total_exento = 0;     
    var total_gravado = 0;    
    var total_descuento = 0;
    
    var TASA_ISV = 0.15; // 15% Honduras

    // IMPORTANTE: Usamos "#modal_iconified_cash #tbldetalle" para evitar conflictos
    // con otras tablas ocultas que tengan el mismo ID
    $("#modal_iconified_cash #tbldetalle tbody tr").each(function() {
        
        var imp  = parseFloat($(this).find('input[name="importe[]"]').val()) || 0;
        var exe  = parseFloat($(this).find('input[name="exento[]"]').val()) || 0;
        var desc = parseFloat($(this).find('input[name="descuento[]"]').val()) || 0;

        total_descuento += desc;
        total_venta += imp; // Suma bruta total (lo que paga el cliente)

        // --- CLASIFICACIÓN FISCAL ---
        if(exe > 0) {
            // Si la columna exento tiene valor, todo el importe se va a EXENTO
            total_exento += imp;
        } else {
            // Si exento es 0, el importe incluye impuesto (GRAVADO)
            total_gravado += imp; 
        }
    });

    // --- MATEMÁTICA INVERSA ---
    // Base Imponible = Monto Gravado / 1.15
    var base_gravada = total_gravado / (1 + TASA_ISV);
    
    // Impuesto = Monto Gravado - Base Imponible
    var isv_calculado = total_gravado - base_gravada;

    // --- REDONDEO ---
    total_venta     = parseFloat(total_venta.toFixed(2));
    total_exento    = parseFloat(total_exento.toFixed(2));
    base_gravada    = parseFloat(base_gravada.toFixed(2));
    isv_calculado   = parseFloat(isv_calculado.toFixed(2));
    total_descuento = parseFloat(total_descuento.toFixed(2));

    // --- ACTUALIZAR ETIQUETAS VISUALES (Cuadro Gris) ---
    $("#lbl_sumas").text(base_gravada.toFixed(2));      // Sumas (Base Neta)
    $("#lbl_exento").text(total_exento.toFixed(2));     // Exento
    $("#lbl_descuento").text(total_descuento.toFixed(2));
    $("#lbl_total").text(total_venta.toFixed(2));       // Total Final
    
    // Encabezado Verde y Campo "A Pagar"
    $("#big_total").text(total_venta.toFixed(2));
    $("#txtDeuda").val(total_venta.toFixed(2)); 

    // --- ACTUALIZAR INPUTS OCULTOS (Para Guardar en BD) ---
    $("#txtSumas").val(base_gravada.toFixed(2));
    $("#txtIva").val(isv_calculado.toFixed(2));
    $("#txtExento").val(total_exento.toFixed(2));
    $("#txtDescuento").val(total_descuento.toFixed(2));
    $("#txtTotal").val(total_venta.toFixed(2));
    
    $("#txtRetenido").val("0.00");
}

// ============================================================================
//  3. AGREGAR FILA (Normalizado para Edición y Búsqueda)
// ============================================================================
function agregarFila(item) {
    // Valores por defecto
    var cantidad = item.cantidad || 1;
    var precio = parseFloat(item.precio_venta).toFixed(2);
    var descuento = item.descuento || "0.00";
    var fecha_vence = item.fecha_vence || "";
    
    var valorExentoInput = "0.00";
    
    // Calcular importe inicial
    var importe = (cantidad * precio) - descuento;
    if(importe < 0) importe = 0;

    // --- Lógica Exento Inicial ---
    // Caso A: Viene del Buscador (item.exento es 1 o 0)
    if (item.exento == 1 && !item.importe) {
       valorExentoInput = importe.toFixed(2); 
    } 
    // Caso B: Viene de Editar BD (item.exento es el monto monetario)
    else if (parseFloat(item.exento) > 0) {
       valorExentoInput = parseFloat(item.exento).toFixed(2);
    }

    var fila = `
    <tr>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-xs" onclick="$(this).closest('tr').remove(); calcularTotalesEdicion();">
                <i class="icon-trash"></i>
            </button>
        </td>
        <td>
            <input type="hidden" name="idproducto[]" value="${item.idproducto || item.value}"> 
            <input type="hidden" name="fecha_vence[]" value="${fecha_vence}">
            
            <small class="text-muted">${item.codigo_barra || item.label}</small><br>
            <strong>${item.nombre_producto || item.producto}</strong>
        </td>
        <td class="text-center">
            <input type="number" class="form-control input-xs text-center" name="cantidad[]" 
            value="${cantidad}" style="width:70px" min="1" step="0.01" 
            onkeyup="recalcularFila(this)" onchange="recalcularFila(this)">
        </td>
        <td class="text-center">
            <input type="text" class="form-control input-xs text-center" name="precio[]" 
            value="${precio}" style="width:80px" readonly>
        </td>
        <td class="text-center">
            <input type="text" class="form-control input-xs text-center input-exento" name="exento[]" 
            value="${valorExentoInput}" style="width:80px" readonly>
        </td>
        <td class="text-center">
            <input type="number" class="form-control input-xs text-center" name="descuento[]" 
            value="${descuento}" style="width:70px" 
            onkeyup="recalcularFila(this)" onchange="recalcularFila(this)">
        </td>
        <td class="text-center">
            <input type="text" class="form-control input-xs text-center input-importe" name="importe[]" 
            value="${importe.toFixed(2)}" style="width:90px" readonly>
        </td>
        <td class="text-center">
            <small>${fecha_vence ? fecha_vence : '-'}</small>
        </td>
    </tr>`;

    // Insertar en la tabla ESPECÍFICA del modal
    $("#modal_iconified_cash #tbldetalle tbody").append(fila);
    
    // Recalcular inmediatamente
    calcularTotalesEdicion();
}