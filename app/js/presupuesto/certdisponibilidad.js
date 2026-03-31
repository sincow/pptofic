document.addEventListener("DOMContentLoaded", function() {
    var listWhere = [];
      listWhere.push({
         "id": "Estado",
         "value": '1'
      })
      // para limpiar el select de dependencia cada vez que se cargue la página
      $('#dependencia').empty().append('<option value="">Seleccionar</option>').val(null).trigger('change');
      let selectDependencia = document.getElementById('dependencia');
      getSelects('presupuesto', 'dependencia', selectDependencia, 'DependenciaId', ['DependenciaId', 'Nombre'], listWhere);
      //
    
      listWhere = [];
      listWhere.push({
         "id": "Estado",
         "value": '1'
      })
      // para limpiar el select ordenador del gasto cada vez que se cargue la página
      $('#ordenadorgasto').empty().append('<option value="">Seleccionar</option>').val(null).trigger('change');
      let selectOrdenadorGasto = document.getElementById('ordenadorgasto');
      getSelects('presupuesto', 'ordenadorgasto', selectOrdenadorGasto, 'TerceroId', ['TerceroId', 'TerNombr'], listWhere);
      //

      listWhere = [];
      listWhere.push({
         "id": "Estado",
         "value": '1'
      })
      // para limpiar el select tipo documento cada vez que se cargue la página
      $('#tipodocumento').empty().append('<option value="">Seleccionar</option>').val(null).trigger('change');
      let selectTipoDocumento = document.getElementById('tipodocumento');
      getSelects('presupuesto', 'tipodocumento', selectTipoDocumento, 'TipoDocumentoId', ['TipoDocumentoId', 'Nombre'], listWhere);
      //

      listWhere = [];
      listWhere.push({
         "id": "Estado",
         "value": '1'
      })
      // para limpiar el select RUBRO DE GASTOS cada vez que se cargue la página
      $('#detalleCodigo').empty().append('<option value="">Seleccionar</option>').val(null).trigger('change');
      let selectRubroGasto = document.getElementById('detalleCodigo');
      getSelects('presupuesto', 'rubrogasto', selectRubroGasto, 'RubroGastoId', ['RubroGastoId', 'Nombre'], listWhere);
      //
      //el select de tipo de financiacion 
     $('#detalleTipoFinanciacion').empty().append('<option value="">Seleccionar</option>').val(null).trigger('change');


});

//Validar cabecera del CDP
$('#detalleCodigo').on('select2:opening', function (e) {
   if (!validarCabeceraAntesDetalle()) {
      e.preventDefault(); // bloquea que abra el select2
   }
});


function validarCabeceraAntesDetalle() {
   const fecha = document.getElementById('fecha').value.trim();
   const expiracion = document.getElementById('expiracion').value.trim();
   const perfiscal = document.getElementById('periodofiscal').value.trim();
   const dependencia = document.getElementById('dependencia').value.trim();
   const ordenador = document.getElementById('ordenadorgasto').value.trim();
   const documento = document.getElementById('tipodocumento').value.trim();
   const numero = document.getElementById('documentonro').value.trim();
   const concepto = $('#concepto').val().trim();


   if (fecha === '') {
      alert('Debe seleccionar la fecha.');
      document.getElementById('fecha').focus();
      return false;
   }

   if (expiracion === '') {
      alert('Debe seleccionar la expiración.');
      document.getElementById('expiracion').focus();
      return false;
   }

   if (perfiscal === '') {
      alert('Debe seleccionar el periodo fiscal.');
      document.getElementById('periodofiscal').focus();
      return false;
   }

   if (dependencia === '') {
      alert('Debe seleccionar la dependencia.');
      document.getElementById('dependencia').focus();
      return false;
   }

   if (ordenador === '') {
      alert('Debe seleccionar el ordenador.');
      document.getElementById('ordenadorgasto').focus();
      return false;
   }

   if (documento === '') {
      alert('Debe seleccionar el documento soporte.');
      document.getElementById('tipodocumento').focus();
      return false;
   }

   if (numero === '') {
      alert('Debe generar o digitar el número.');
      document.getElementById('documentonro').focus();
      return false;
   }
   
   if (concepto === '') {
      Swal.fire('Falta dato', 'Debe digitar el concepto.', 'warning');
      $('#concepto').focus();
      return;
   }

   return true;
}

$('#detalleCodigo').on('select2:select', function (e) {
   const codigo = $(this).val();

   //console.log('Obteniendo información para el código de rubro:', codigo);

   if (!codigo) {
      return;
   }

   obtenerInfoRubro(codigo);
});

$('#detalleTipoFinanciacion').on('select2:select', function (e) {
   //console.log('Tipo de financiación seleccionado:', $(this).val());
   const codigo = $('#detalleCodigo').val();
   if (!codigo) {
      return;
   }
  // console.log('Tipo de financiación seleccionado::::');

   valorRubro(codigo);
});



async function valorRubro(codigo) {
   
   //para hallar el valor 
    const PeriodoFiscal = document.getElementById('periodofiscal').value.trim();  
    const tipoFinanciacion = document.getElementById('detalleTipoFinanciacion').value.trim();  
    const dependencia = document.getElementById('dependencia').value.trim();
    var aprValor = 0;
    var aprUsado = 0;

    try {
      // 1. Valor aprobado
      let listWhere = [
         { id: "RubCodig", value: codigo },
         { id: "PeriodoFiscal", value: PeriodoFiscal },
         { id: "TipoFinanciacionId", value: tipoFinanciacion }
      ];

      const formData1= new FormData();
      formData1.append("modulo", "presupuesto");
      formData1.append("option", "presupuestoanual");
      formData1.append("action", "getSaldo");
      formData1.append("listWhere", JSON.stringify(listWhere));
   
      const response1 = await fetch('helpers/ajaxRouter.php', {
         method: 'POST',
         body: formData1
      });
      
      const data1 = await response1.json();
      if (data1.length > 0) {
         //console.log('Valor aprobado obtenido:', data1[0].AprValor);
          aprValor = parseFloat(data1[0].AprValor || 0);
      }
      

     //2. Valor usado
     listWhere = [
         { id: "c.PeriodoFiscal", value: PeriodoFiscal },
         { id: "d.RubroGastoId", value: codigo },
         { id: "c.DependenciaId", value: dependencia },
         { id: "c.Estado", value: '0' }
      ];
      
      const formData2= new FormData();
      formData2.append("modulo", "presupuesto");
      formData2.append("option", "certdisponibilidad");
      formData2.append("action", "getValorUsado");
      formData2.append("listWhere", JSON.stringify(listWhere));
      
      const response2 = await fetch('helpers/ajaxRouter.php', {
         method: 'POST',
         body: formData2
      }); 
      
      const data2 = await response2.json();
      if (data2.length > 0) {
          aprUsado = parseFloat(data2[0].AprUsado || 0);
      }
      //Calcular saldo
      const saldo = aprValor - aprUsado;
      document.getElementById('detalleSaldo').value = formatoMoneda(saldo);
     } catch (error) {
      console.error('Error calculando saldo:', error);
      document.getElementById('detalleSaldo').value = '0';
   }   
}


function obtenerInfoRubro(codigo) {
   // console.log('Obteniendo información para el código de rubro::::', codigo);
   
   var listWhere = [];
   listWhere.push({"id": "RubroGastoId","value": codigo })

   const formData = new FormData();
   formData.append("modulo", "presupuesto");
   formData.append("option", "rubrogasto");
   formData.append("action", "getWhere");
   formData.append("listWhere", JSON.stringify(listWhere));
   
   const response = fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      if (data.length > 0) {
          document.getElementById('detalleDescripcion').value = data[0].Nombre;
      }
      else
      { 
         document.getElementById('detalleDescripcion').value = '';
          Swal.fire({
            icon: 'warning',
            title: 'Aviso',
            text: 'El rubro de gasto no está creado en el sistema.',
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#3086d6c7'
         });
      }
    })

       //hallar los tipos de financiacion para ese rubro de gasto
      // para limpiar el select de financiacion cada vez que se seleccione un rubro de gasto
      $('#detalleTipoFinanciacion').empty().append('<option value="">Seleccionar</option>').val(null).trigger('change');
      //
      listWhere = [];
      listWhere.push({"id": "g.RubroGastoId","value": codigo })

      const formData1 = new FormData();
      formData1.append("modulo", "presupuesto");
      formData1.append("option", "rubrogasto");
      formData1.append("action", "getTipoFinanciacion");
      formData1.append("listWhere", JSON.stringify(listWhere));
      
      const response1 = fetch('helpers/ajaxRouter.php', {
         method: 'POST',
         body: formData1
      }).then(resp => resp.json())
      .then( data => {
         const select = $('#detalleTipoFinanciacion');
         let options = '<option value="">Seleccionar</option>';   

         if (data.length > 0) {
            data.forEach(item => {
               options += `<option value="${item.TipoFinanciacionId}">${item.TipoFinanciacionNombre}</option>`;
            });
         }
         select.html(options);
         select.val('').trigger('change');
      })
    valorRubro(codigo);
}

function formatoMoneda(valor) {
   return new Intl.NumberFormat('es-CO', {
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
   }).format(valor);
}

$('#detalleValor').on('input', function () {
   let valor = this.value.replace(/\D/g, ''); // solo números
   this.value = formatoMoneda(valor);
});

function obtenerValorNumerico(valor) {
   return parseFloat(String(valor || '').replace(/\./g, '').replace(/,/g, '').trim()) || 0;
 }

$('#btnAgregarDetalle').on('click', function () {
   const codigo = $('#detalleCodigo').val();
   const codigoTexto = $('#detalleCodigo option:selected').text().trim();

   const tipoFinanciacionId = $('#detalleTipoFinanciacion').val();
   const tipoFinanciacionTexto = $('#detalleTipoFinanciacion option:selected').text().trim();

   const detalle = $('#detalleDescripcion').val().trim();
   const saldoTexto = $('#detalleSaldo').val().trim();
   const valorTexto = $('#detalleValor').val().trim();

   const saldo = obtenerValorNumerico(saldoTexto);
   const valor = obtenerValorNumerico(valorTexto);

   if (!codigo) {
      Swal.fire('Falta dato', 'Debe seleccionar el código.', 'warning');
      $('#detalleCodigo').select2('open');
      return;
   }

   if (!tipoFinanciacionId) {
      Swal.fire('Falta dato', 'Debe seleccionar el tipo de financiación.', 'warning');
      $('#detalleTipoFinanciacion').focus();
      return;
   }

   if (valor <= 0) {
      Swal.fire('Valor inválido', 'El valor debe ser mayor a 0.', 'warning');
      $('#detalleValor').focus();
      return;
   }

   if (valor > saldo) {
      Swal.fire('Valor inválido', 'El valor no puede ser mayor al saldo.', 'warning');
      $('#detalleValor').focus();
      return;
   }

   if (existeDetalle(codigo, tipoFinanciacionId)) {
   Swal.fire('Registro duplicado', 'Ese código con ese tipo de financiación ya fue agregado.', 'warning');
   return;
}

   agregarFilaDetalle({
      codigo,
      codigoTexto,
      tipoFinanciacionId,
      tipoFinanciacionTexto,
      detalle,
      saldo,
      valor
   });

   limpiarCamposDetalle();
   recalcularTotalCertificado();
});

function agregarFilaDetalle(item) {
   const tbody = $('#tbodyDetalle');

   const fila = `
      <tr>
         <td>
            ${item.codigo}
            <input type="hidden" name="detalleCodigo[]" value="${item.codigo}">
         </td>
         <td>
            ${item.tipoFinanciacionTexto}
            <input type="hidden" name="detalleTipoFinanciacion[]" value="${item.tipoFinanciacionId}">
         </td>
         <td>
            ${item.detalle}
            <input type="hidden" name="detalleDescripcion[]" value="${item.detalle}">
         </td>
         <td class="text-end">
            ${formatoMoneda(item.saldo)}
            <input type="hidden" name="detalleSaldo[]" value="${item.saldo}">
         </td>
         <td class="text-end">
            ${formatoMoneda(item.valor)}
            <input type="hidden" name="detalleValor[]" value="${item.valor}">
         </td>
         <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger btnEliminarDetalle" title="Eliminar">
               <i class="fa-solid fa-trash"></i>
            </button>
         </td>
      </tr>
   `;

   tbody.append(fila);
}

$(document).on('click', '.btnEliminarDetalle', function () {
   $(this).closest('tr').remove();
   recalcularTotalCertificado();
});

function limpiarCamposDetalle() {
   $('#detalleCodigo').val('').trigger('change');
   $('#detalleTipoFinanciacion').empty().append('<option value="">Seleccionar</option>').val('').trigger('change');
   $('#detalleDescripcion').val('');
   $('#detalleSaldo').val('0');
   $('#detalleValor').val('');
}

function recalcularTotalCertificado() {
   let total = 0;

   $('#tbodyDetalle tr').each(function () {
      const valor = obtenerValorNumerico(
         $(this).find('input[name="detalleValor[]"]').val()
      );
      total += valor;
   });

   $('#totalCertificado').text(formatoMoneda(total));
}

function existeDetalle(codigo, tipoFinanciacionId) {
   let existe = false;

   $('#tbodyDetalle tr').each(function () {
      const cod = $(this).find('input[name="detalleCodigo[]"]').val();
      const tipo = $(this).find('input[name="detalleTipoFinanciacion[]"]').val();

      if (cod === codigo && tipo === tipoFinanciacionId) {
         existe = true;
         return false;
      }
   });

   return existe;
}

$('#btnLimpiar').on('click', function (e) {
   e.preventDefault(); // evita comportamiento default del reset

   // 🔹 1. Limpiar inputs de cabecera
   $('#fecha').val('');
   $('#expiracion').val('');
   $('#periodofiscal').val('');
   $('#documentonro').val('');
   
   $('#concepto').val('');

   // 🔹 2. Resetear selects principales
   $('#dependencia').val('').trigger('change');
   $('#ordenadorgasto').val('').trigger('change');
   $('#tipodocumento').val('').trigger('change');

   // 🔹 3. Limpiar bloque detalle
   $('#detalleCodigo').val('').trigger('change');
   $('#detalleTipoFinanciacion')
      .empty()
      .append('<option value="">Seleccionar</option>')
      .val('')
      .trigger('change');

   $('#detalleDescripcion').val('');
   $('#detalleSaldo').val('0');
   $('#detalleValor').val('');

   // 🔹 4. Limpiar tabla
   $('#tbodyDetalle').empty();

   // 🔹 5. Resetear total
   $('#totalCertificado').text('0');

});

$('#btnSalir').on('click', function () {

   Swal.fire({
      title: '¿Desea salir?',
      text: 'Se perderán los datos no guardados',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, salir',
      cancelButtonText: 'Cancelar'
   }).then((result) => {
      if (result.isConfirmed) {
         window.location.href = './cdp'; // ajusta ruta
      }
   });

});

$('#crearCDPForm').on('submit', function (e) {
   if (!validarCabeceraAntesDetalle()) {
     return;
   }
   
   e.preventDefault();
   
   if ($('#tbodyDetalle tr').length === 0) {
      Swal.fire('Error', 'Debe agregar al menos un detalle', 'warning');
      return;
   }

   Swal.fire({
      title: '¿Confirmar?',
      text: '¿Desea generar este documento?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sí, guardar'
   }).then((result) => {
      if (!result.isConfirmed) return;

      const formData = new FormData(document.getElementById('crearCDPForm'));
      formData.append("modulo", "presupuesto");
      formData.append("option", "certdisponibilidad");
      formData.append("action", "create");

      fetch('helpers/ajaxRouter.php', {
         method: 'POST',
         body: formData
      })
      .then(resp => resp.json())
      .then(data => {
         console.log('Respuesta del servidor:', data);
         
         if (data.success) {
            Swal.fire('OK', data.message, 'success');
            if (data.reportUrl) {
               window.open(data.reportUrl, '_blank');
            }
                        
            $('#btnLimpiar').click();
         } else {
            Swal.fire('Error', data.message, 'error');
         }
      })
      .catch(err => {
         console.error(err);
         Swal.fire('Error', 'Error en la petición', 'error');
      });
   });
});