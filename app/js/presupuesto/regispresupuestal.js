document.addEventListener("DOMContentLoaded", function() {
    var listWhere = [];
      listWhere.push({
         "id": "Estado",
         "value": '1'
      })
      // para limpiar el select de tipo de contrato cada vez que se cargue la página
      $('#tipocontrato').empty().append('<option value="">Seleccionar</option>').val(null).trigger('change');
      let selecttipocontrato = document.getElementById('tipocontrato');
      getSelects('presupuesto', 'tipocontrato', selecttipocontrato, 'TipoContratoId', ['TipoContratoId', 'Nombre'], listWhere);
      //
   
      var listWhere = [];
      listWhere.push({
         "id": "TerEstad",
         "value": '1'
      })
      let selectQuery = 'tercerocrp';
      let paramsQuery = [];
      paramsQuery.push({
         "modulo": "contabilidad",
         "option": "terceros",
         "action": "getByQuery",
         "listWhere": JSON.stringify(listWhere)
       })

      initialSelect(selectQuery, paramsQuery);


      // fechas 
      const hoy = new Date();

      document.getElementById('fecha').value = formatearFecha(hoy);
      document.getElementById('fechaplazo').value = formatearFecha(hoy);

      const plazoInput = document.getElementById('plazo');
      plazoInput.addEventListener('blur', calcularFechaPlazo);



});

document.getElementById('nrocdp').addEventListener('blur', async function () {
   let nrocdp = this.value.trim();
   const periodoFiscal = document.getElementById('periodofiscal').value.trim();

   if (nrocdp === '') {
      limpiarDatosCertificado();
      return;
   }

   if (periodoFiscal === '') {
      Swal.fire({
         icon: 'warning',
         title: 'Validación',
         text: 'Debe digitar primero el período fiscal.'
      });
      return;
   }

   
   nrocdp = nrocdp.replace(/\D/g, ''); // Solo números
   nrocdp = nrocdp.padStart(8, '0'); // Rellenar con ceros a la izquierda a 8 posiciones
   this.value = nrocdp;

   try {
      await consultarCDP(nrocdp,periodoFiscal);
   } catch (error) {
      console.error(error);
      Swal.fire({
         icon: 'error',
         title: 'Error',
         text: 'Ocurrió un error al consultar el certificado.'
      });
   }
});

function formatearFecha(fecha) {
   const dia = String(fecha.getDate()).padStart(2, '0');
   const mes = String(fecha.getMonth() + 1).padStart(2, '0');
   const anio = fecha.getFullYear();
   return `${dia}/${mes}/${anio}`;
}

function calcularFechaPlazo() {
   const plazo = parseInt(document.getElementById('plazo').value, 10) || 0;
   const fechaBase = new Date();

   fechaBase.setDate(fechaBase.getDate() + plazo);

   document.getElementById('fechaplazo').value = formatearFecha(fechaBase);
}

function limpiarDatosCertificado() {
   document.getElementById('dependencia').value = '';
   document.getElementById('tipodocumento').value = '';
   document.getElementById('documentonro').value = '';
   document.getElementById('concepto').value = '';
   document.getElementById('ordenadorgasto').value = '';

   $('#tercerocrp').val(null).trigger('change');

   document.getElementById('tbodyDetalle').innerHTML = '';
   document.getElementById('totalReserva').textContent = '0';
}

async function consultarCDP(certDispId, periodoFiscal) {
  
   const formData = new FormData();
   formData.append('modulo', 'presupuesto');
   formData.append('option', 'certdisponibilidad'); 
   formData.append('action', 'getCertDisponibilidad'); 
   formData.append('certDispId', certDispId);
   formData.append('periodoFiscal', periodoFiscal);

   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   });

   const result = await response.json();
   
   if (!result.success) {
      limpiarDatosCertificado();
      Swal.fire({
         icon: 'warning',
         title: 'Validación',
         text: result.message || 'CDP no existe o está anulado.'
      });
      return;
   }
   console.log('Llenando datos del CDP en el formulario...');

   llenarCabeceraCertificado(result.header);
   llenarDetalleCertificado(result.detail || []);
}

function llenarCabeceraCertificado(header) {
  
   document.getElementById('dependencia').value   = header.dependenciaNombre || '';
   document.getElementById('tipodocumento').value = header.tipoDocumentoNombre || '';
   document.getElementById('documentonro').value  = header.tipoDocumentoNr || '';
   document.getElementById('concepto').value      = header.concepto || '';

   // Si también lo manejas desde la consulta
    document.getElementById('ordenadorgasto').value =
   `${header.ordenadorGastoId || ''}${header.ordenadorGastoId && header.ordenadorGastoNombre ? ' - ' : ''}${header.ordenadorGastoNombre || ''}`;

   document.getElementById('dependenciaid').value = header.dependenciaId || '';
   document.getElementById('tipodocumentoid').value = header.tipoDocumentoId || '';
   document.getElementById('ordenadorgastoid').value = header.ordenadorGastoId || '';
}

function llenarDetalleCertificado(detalle) {

   const tbody = document.getElementById('tbodyDetalle');
   tbody.innerHTML = '';

   let total = 0;

   detalle.forEach(item => {
      const saldo = parseFloat(item.SaldoDisponible || 0);

      const tr = document.createElement('tr');
      tr.innerHTML = `
         <td class="ps-2">${item.RubroGastoId || ''}</td>
         <td class="ps-2">${item.TipoFinanciacionNombre || ''}</td>
         <td class="ps-2">${item.RubroGastoNombre || ''}</td>
         <td class="ps-2 text-end">${formatearMoneda(saldo)}</td>
         <td class="ps-2 text-end">
            <input type="text"
                   class="form-control form-control-sm text-end valor-detalle"
                   value="${formatearMoneda(saldo)}"
                   data-saldo="${saldo}">
         </td>
      `;

      tbody.appendChild(tr);
   });
   inicializarEventosDetalle();
   recalcularTotalReserva();
   // document.getElementById('totalReserva').textContent = formatearMoneda(total);
}

function inicializarEventosDetalle() {
   const inputs = document.querySelectorAll('.valor-detalle');

   inputs.forEach(input => {
      input.addEventListener('focus', function () {
         this.value = limpiarNumero(this.value);
      });

      input.addEventListener('input', function () {
         this.value = limpiarNumero(this.value);
         recalcularTotalReserva();
      });

      input.addEventListener('blur', function () {
         let valor = parseFloat(limpiarNumero(this.value)) || 0;
         const saldo = parseFloat(this.dataset.saldo) || 0;

         if (valor > saldo) {
            valor = saldo;

            Swal.fire({
               icon: 'warning',
               title: 'Validación',
               text: 'El valor digitado no puede ser mayor que el saldo disponible.'
            });
         }

         if (valor < 0) {
            valor = 0;
         }

         this.value = formatearMoneda(valor);
         recalcularTotalReserva();
      });
   });
}

function recalcularTotalReserva() {
   const inputs = document.querySelectorAll('.valor-detalle');
   let total = 0;

   inputs.forEach(input => {
      const valor = parseFloat(limpiarNumero(input.value)) || 0;
      total += valor;
   });

   document.getElementById('totalReserva').textContent = formatearMoneda(total);
}

function limpiarNumero(valor) {
   return String(valor || '')
      .replace(/\./g, '')
      .replace(/,/g, '')
      .trim();
}

function formatearMoneda(valor) {
   return new Intl.NumberFormat('es-CO', {
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
   }).format(valor || 0);
}

function limpiarFormulario() {
   
   document.getElementById('plazo').value = '';
   document.getElementById('periodofiscal').value = '';
   document.getElementById('nrocdp').value = '';
   document.getElementById('contratonro').value = '';
   document.getElementById('concepto').value = '';

   limpiarDatosCertificado();

   $('#tipocontrato').val(null).trigger('change');
   $('#tercerocrp').val(null).trigger('change');

   document.getElementById('tbodyDetalle').innerHTML = '';
   document.getElementById('totalReserva').textContent = formatearMoneda(0);

   const hoy = new Date();
   document.getElementById('fecha').value = formatearFecha(hoy);
   document.getElementById('fechaplazo').value = formatearFecha(hoy);
}

document.getElementById('btnLimpiar').addEventListener('click', function (e) {
   e.preventDefault(); 
   limpiarFormulario();
});