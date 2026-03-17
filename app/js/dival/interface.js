//*********************************************************************************************
document.addEventListener("DOMContentLoaded", function() {
   console.log('DOM fully loaded and parsed');
   var listWhere = [];
   listWhere.push({
      "id": "ComEstad",
      "value": '1'
   })
   let selectCompte = document.getElementById('DvComChe');
   getSelects('contabilidad', 'comprobantes', selectCompte, 'ComCodig', textOpt = ['ComCodig', 'ComDescr'], listWhere);
   selectCompte = document.getElementById('DvComApl');
   getSelects('contabilidad', 'comprobantes', selectCompte, 'ComCodig', textOpt = ['ComCodig', 'ComDescr'], listWhere);
   selectCompte = document.getElementById('DvComCon');
   getSelects('contabilidad', 'comprobantes', selectCompte, 'ComCodig', textOpt = ['ComCodig', 'ComDescr'], listWhere);
   selectCompte = document.getElementById('DvComDev');
   getSelects('contabilidad', 'comprobantes', selectCompte, 'ComCodig', textOpt = ['ComCodig', 'ComDescr'], listWhere);
   selectCompte = document.getElementById('DvComCap');
   getSelects('contabilidad', 'comprobantes', selectCompte, 'ComCodig', textOpt = ['ComCodig', 'ComDescr'], listWhere);
   selectCompte = document.getElementById('DvComInt');
   getSelects('contabilidad', 'comprobantes', selectCompte, 'ComCodig', textOpt = ['ComCodig', 'ComDescr'], listWhere);
   selectCompte = document.getElementById('DvComVal');
   getSelects('contabilidad', 'comprobantes', selectCompte, 'ComCodig', textOpt = ['ComCodig', 'ComDescr'], listWhere);
   selectCompte = document.getElementById('DvComEfe');
   getSelects('contabilidad', 'comprobantes', selectCompte, 'ComCodig', textOpt = ['ComCodig', 'ComDescr'], listWhere);

   var listWhere = [];
   listWhere.push({
      "id": "CueMovim",
      "value": '1'
   })
   listWhere.push({
      "id": "CueEstad",
      "value": '1'
   })
   selectCompte = document.getElementById('DvCueCli');
   getSelects('contabilidad', 'cuentas', selectCompte, 'CueCodig', textOpt = ['CueCodig', 'CueNombr'], listWhere);
   selectCompte = document.getElementById('DvCueCaj');
   getSelects('contabilidad', 'cuentas', selectCompte, 'CueCodig', textOpt = ['CueCodig', 'CueNombr'], listWhere);
   selectCompte = document.getElementById('DvCueIva');
   getSelects('contabilidad', 'cuentas', selectCompte, 'CueCodig', textOpt = ['CueCodig', 'CueNombr'], listWhere);
   selectCompte = document.getElementById('DvCueIba');
   getSelects('contabilidad', 'cuentas', selectCompte, 'CueCodig', textOpt = ['CueCodig', 'CueNombr'], listWhere);
   selectCompte = document.getElementById('DvCueIco');
   getSelects('contabilidad', 'cuentas', selectCompte, 'CueCodig', textOpt = ['CueCodig', 'CueNombr'], listWhere);
   selectCompte = document.getElementById('DvCueCom');
   getSelects('contabilidad', 'cuentas', selectCompte, 'CueCodig', textOpt = ['CueCodig', 'CueNombr'], listWhere);
   selectCompte = document.getElementById('DvCueInt');
   getSelects('contabilidad', 'cuentas', selectCompte, 'CueCodig', textOpt = ['CueCodig', 'CueNombr'], listWhere);
   selectCompte = document.getElementById('DvCueMen');
   getSelects('contabilidad', 'cuentas', selectCompte, 'CueCodig', textOpt = ['CueCodig', 'CueNombr'], listWhere);

   var listWhere = [];
   listWhere.push({
      "id": "ConEstad",
      "value": '1'
   })
   selectCompte = document.getElementById('DvConCon');
   getSelects('bancos', 'tiposmovimiento', selectCompte, 'ConCodig', textOpt = ['ConCodig', 'ConNombr'], listWhere);
   selectCompte = document.getElementById('DvConDev');
   getSelects('bancos', 'tiposmovimiento', selectCompte, 'ConCodig', textOpt = ['ConCodig', 'ConNombr'], listWhere);
   selectCompte = document.getElementById('DvConEgr');
   getSelects('bancos', 'tiposmovimiento', selectCompte, 'ConCodig', textOpt = ['ConCodig', 'ConNombr'], listWhere);

   const formData = new FormData();
   formData.append("modulo", "admon");
   formData.append("option", "parametros");
   formData.append("action", "getAll");
   formData.append("modcodig", "21");
   const response = fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formData
   }).then(resp => resp.json())
   .then( data => {
      if (data.length > 0) {
			data.forEach(parametros => {
            if (parametros["ParObjeto"] != "" && parametros["ParValor"] != '' && document.getElementById(parametros["ParObjeto"])) {
               document.getElementById(parametros["ParObjeto"]).value = parametros["ParValor"];
               if (document.getElementById(parametros["ParObjeto"]).classList.contains('select2') || document.getElementById(parametros["ParObjeto"]).classList.contains('select') ) {
                  document.getElementById(parametros["ParObjeto"]).dispatchEvent(new Event('change'));
               }
            }
            if (parametros['ParCodig'] == 'IVI') {
               document.getElementById(parametros["ParObjeto"] + "_" + parametros["ParValor"]).checked = true;
            }
         });
      }
   });


   //**************************************************************************************
   document.getElementById('btnSaveParams').addEventListener('click', async function(e) {
      e.preventDefault();
      e.stopPropagation();

      const nivelesRubIng = document.getElementById('DvNivRubIng');
      if (nivelesRubIng) {
         const validacion = validarNivelesRubros(nivelesRubIng.value);
         if (!validacion.success) {
            Swal.fire({
               icon: 'error',
               title: 'Error',
               text: validacion.message
            });
            nivelesRubIng.focus();
            return;
         }

         // opcional: normalizar formato
         nivelesRubIng.value = validacion.niveles.join(',');
      }

      const nivelesRubGas = document.getElementById('DvNivRubGas');
      if (nivelesRubGas) {
         const validacion = validarNivelesRubros(nivelesRubGas.value);
         if (!validacion.success) {
            Swal.fire({
               icon: 'error',
               title: 'Error',
               text: validacion.message
            });
            nivelesRubGas.focus();
            return;
         }

         // opcional: normalizar formato
         nivelesRubGas.value = validacion.niveles.join(',');
      }

      const params = await createJsonParams();
      const question = swal.fire({
         text: 'Seguro que deseas guardar los cambios?',
         icon: 'question',
         toast: false,
         showCancelButton: true,
         reverseButtons: true,
         focusCancel: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         cancelButtonText: 'No, Cancelar',
         confirmButtonText: 'Si, Guardar'
      }).then(function (result) {
         if (result.isConfirmed) {
            saveParams()
         }
      })
   }  );

});


//**************************************************************************************
async function createJsonParams() {
   let paramsList = [];
   document.querySelectorAll('.param').forEach( param => {
      if (param.getAttribute("ParCodig") == 'IVI') {
         if (param.checked) {
            paramsList.push({
               "ParCodig": param.getAttribute("ParCodig"),
               "ParNombr": param.getAttribute("ParNombr"),
               "ParValor": param.value,
               "ParObjeto": param.getAttribute("name")
            })
         }
      } else {
         paramsList.push({
            "ParCodig": param.getAttribute("ParCodig"),
            "ParNombr": param.getAttribute("ParNombr"),
            "ParValor": param.value,
            "ParObjeto": param.getAttribute("name")
         })
      }
   });
   document.getElementById('parametersList').value = JSON.stringify(paramsList);
}

//**************************************************************************************
function saveParams() {
   const formData = new FormData();
   formData.append("modulo", "admon");
   formData.append("option", "parametros");
   formData.append("action", "save");
   formData.append("modcodig", "21");
   formData.append("paramsList", document.getElementById('parametersList').value);
   if (execQueryUpd(formData, 'paramsForm', null)) {
   }
}

function validarNivelesRubros(valor) {
   if (!valor || valor.trim() === '') {
      return {
         success: false,
         message: 'Debe definir la estructura de niveles del rubro.'
      };
   }

   const partes = valor.split(',')
      .map(x => x.trim())
      .filter(x => x !== '');

   if (partes.length === 0) {
      return {
         success: false,
         message: 'La estructura de niveles no es válida.'
      };
   }

   const niveles = partes.map(x => Number(x));

   if (niveles.some(x => !Number.isInteger(x) || x <= 0)) {
      return {
         success: false,
         message: 'Los niveles deben ser números enteros positivos.'
      };
   }

   for (let i = 1; i < niveles.length; i++) {
      if (niveles[i] <= niveles[i - 1]) {
         return {
            success: false,
            message: 'Los niveles deben estar en orden ascendente y no repetidos.'
         };
      }
   }

   if (niveles[niveles.length - 1] > 20) {
      return {
         success: false,
         message: 'El nivel máximo no puede ser mayor a 20.'
      };
   }

   return {
      success: true,
      niveles: niveles
   };
}