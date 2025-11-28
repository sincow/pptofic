/* DATA TABLE*/
/*************************************************************/
$('.tablas').DataTable({
	"language": {
		"decimal": ".",
		"thousands": ",",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
		"infoPostFix": "",
		"infoFiltered": "(filtrado de un total de _MAX_ registros)",
		"loadingRecords": "Cargando...",
		"lengthMenu": "Mostrar _MENU_ registros",
		"paginate": {
			"first": "Primero",
			"last": "Último",
			"next": "Siguiente",
			"previous": "Anterior"
		},
		"processing": "Procesando...",
		"search": "Buscar:",
		"searchPlaceholder": "Término de búsqueda",
		"zeroRecords": "No se encontraron resultados",
		"emptyTable": "Ningún dato disponible en esta tabla",
		"aria": {
			"sortAscending": ": Activar para ordenar la columna de manera ascendente",
			"sortDescending": ": Activar para ordenar la columna de manera descendente"
		},
		//only works for built-in buttons, not for custom buttons
		"buttons": {
			"create": "Nuevo",
			"edit": "Cambiar",
			"remove": "Borrar",
			"copy": "Copiar",
			"csv": "fichero CSV",
			"excel": "tabla Excel",
			"pdf": "documento PDF",
			"print": "Imprimir",
			"colvis": "Visibilidad columnas",
			"collection": "Colección",
			"upload": "Seleccione fichero...."
		},
		"select": {
			"rows": {
				_: '%d filas seleccionadas',
				0: 'clic fila para seleccionar',
				1: 'una fila seleccionada'
			}
		}
	}
});


/*************************************************************/
function execQueryUpd(formData, funcCharge, form) {
	let result = null;
	fetch('helpers/ajaxRouter.php', {
		method: 'POST',
		body: formData
	}).then(resp => resp.json())
		.then(async data => {
			if (data["success"]) {
				result = true;
				if (typeof funcCharge === 'string' && typeof window[funcCharge] === 'function') {
					window[funcCharge]();
				} else if (typeof funcCharge === 'function') {
					//funcCharge();
				}
				if (form !== null) {
					$(form).modal("hide");
				}
				const notify = await swal.fire({
					title: $(".success").val(),
					text: data["message"],
					icon: 'success',
					confirmButtonText: 'Aceptar'
				});
			} else {
				result = false;
				const notify = await swal.fire({
					title: $(".error").val(),
					text: data["message"],
					icon: 'error',
					confirmButtonText: 'Aceptar'
				});
			}
		});
	return result;
}


/*************************************************************************************/
$("#lang").change(function (e) {
// $('#formLang').on('submit', function(e) {
	e.preventDefault();
	const formData = new FormData();
	formData.append("option", "languages");
	formData.append("action", "switch");
	formData.append("lang", $("#lang").val());
	// alert($("#lang").val());

	fetch('helpers/ajaxRouter.php', {
		method: 'POST',
		body: formData
	}).then(resp => resp.json())
	.then(async data => {
		if (data["success"]) {
			const notify = await swal.fire({
				title: 'Éxito',
				text: data["message"],
				icon: 'success',
				confirmButtonText: 'Aceptar'
			});
			// location.reload();
		} else {
			const notify = await swal.fire({
				title: 'Error',
				text: data["message"],
				icon: 'error',
				confirmButtonText: 'Aceptar'
			});
		}
	}).catch(error => {
		alert('Error al cambiar el idioma. Por favor, inténtelo de nuevo.'+error);
		console.error('Error:', error);
	});
	// formData.append("action", "changeLanguage");
	// formData.append("lang", $(this).val());
});


// Función para actualizar la información del paginador
/*************************************************************/
function updatePaginationInfo_ant(ListInstance) {
   if (!ListInstance) return;
   const list = ListInstance;
   const visibleItems = list.visibleItems.length;
   const currentPage = list.i;  // Página actual (comienza en 1)
   const itemsPerPage = list.page;  // Items por página (20)
   let start = 0;
   let end = 0;
   if (visibleItems > 0) {
      start = (currentPage - 1) * itemsPerPage + 1;
      end = Math.min(currentPage * itemsPerPage, visibleItems);
      start = Math.min(start, end); // Asegurar que start <= end
   }
   const infoText = `${start} to ${end} of ${visibleItems}`;
   $('[data-list-info]').text(infoText);
}


/*************************************************************************************/
$(document).ready(function() {
    // Inicializar cuando el elemento se agregue al DOM
    $(document).on('DOMNodeInserted', function(e) {
		if ($(e.target).is('.findOwner') || $(e.target).find('.findOwner').length) {
			// initializeOwnerSelect();
		}
    });
    
    // También intentar inicializar si ya existe
   //  setTimeout(initializeOwnerSelect, 100);
});


// $(".findCustomer").ready(function() {
// $(document).ready(function() {
/*************************************************************************************/
function initializeOwnerSelect() {
	const $select = $(".findOwner");
	if ($select.length === 0) {
		console.log('Elemento .findOwner no encontrado, se intentará más tarde...');
		return;
	}
	// Verificar si ya está inicializado con Select2
	if ($select.hasClass('select2-hidden-accessible')) {
		return;
	}
	let listWhere = [];
	listWhere.push({
		"id": "status",
		"value": '1'
	})
	listWhere = JSON.stringify(listWhere);
	parameters = { "option": "owners", "action": "getWhere", "listWhere": listWhere };
	// const $select = $(".findOwner");
	initialSelect(".findOwner", parameters);
};


/*************************************************************************************/
function initialSelect(selectQuery, parameters){
	const ajaxUrl = 'helpers/ajaxRouter.php';
	setupSelect = "#terceroVale";
	setupSelect = "#"+selectQuery;
   var moduleParam = parameters[0]["modulo"];
	var optionParam = parameters[0]["option"];
	let actionParam = parameters[0]["action"];
	let listWhere = parameters[0]["listWhere"] || null;
	listWhere = JSON.stringify(listWhere);
	const formDataSearch = new FormData();
	formDataSearch.append("modulo", moduleParam);
	formDataSearch.append("option", optionParam);
	formDataSearch.append("action", actionParam);
	formDataSearch.append("listWhere", listWhere);
	let source = parameters["source"] || "";
   $(setupSelect).select2({
		language: "es",
		theme: "default",
		// theme: "classic",
		// theme: "bootstrap",
		placeholder: 'Escribe al menos 3 caracteres para buscar...',
		minimumInputLength: 3,
		ajax: {
			url: ajaxUrl,
			type: 'POST',
			//body: formDataSearch,
			// data: formDataSearch,
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					modulo: moduleParam,
					option: optionParam,
					action: actionParam,
					listWhere: listWhere,
					source: source || "*",
					query: params.term,
					page: params.page || 1
				};
			},
			processResults: function (data, params) {
				// var sourceT = "*";
				// if (setupSelect == ".findCustomerPre") {
				// 	sourceT = "precliente";
				// }
				// params.source = params.sourceT || "*";
			
				params.page = params.page || 1;
				if (data[0]["items"].length < 1) {
					data[0]["items"].push({ id: '0', text: 'Sin Resultados'});
				}
				return {
					results: data[0]["items"],
					pagination: {
						more: data[0]["pagination"]["more"]
					}
				};
			},
			cache: true
		},
		templateResult: formatResult,
		templateSelection: formatSelection
	});
	
	function formatResult(item) {
		if (!item.id) {
			return item.id;
		}
		var $item = $('<div><b>' + item.id + '</b> - ' + item.text + '</div>');
		return $item;
  	}

	function formatSelection(item) {
		// if (ajaxUrl == 'ajax/co_planctas.ajax.php') {
		if (optionParam == 'terceros' || optionParam == 'clientes' || optionParam == 'cuentas') {
			return item.id + " - " + item.text;
		} else {
			return item.text;
		}
  	}

	$(setupSelect).on('select2:open', function () {
		// var firstOption = $(setupSelect + ' option:first');
		var firstOption = $(setupSelect + ' option').first();
		if (firstOption.length) {
			$(setupSelect).val(firstOption.val()).trigger('change');
		}
	});
}


/*************************************************************************************/
async function getSelects(modulo, select, selectElement, value, text, listWhere) {
   // const selectElement = document.getElementById(element);
   listWhere = JSON.stringify(listWhere);
   const formDataTipDoc = new FormData();
   formDataTipDoc.append("modulo", modulo);
   formDataTipDoc.append("option", select);
   formDataTipDoc.append("action", "getWhere");
   formDataTipDoc.append("listWhere", listWhere);
   const response = await fetch('helpers/ajaxRouter.php', {
      method: 'POST',
      body: formDataTipDoc
   }).then(response => response.json())
   .then(options => {
      if (options.length === 0) {
         selectElement.innerHTML = '<option value="" disabled></option>';
      } else {
         options.forEach(option => {
            let optText = "";
            text.forEach(elemnt => {
               optText += option[elemnt]+' ' || '';
            });
            const optionResponse = document.createElement('option');
            optionResponse.value = option[value];
            optionResponse.textContent = optText;
            selectElement.appendChild(optionResponse);
         });
         selectElement.disabled = false;
      }
   })
   .catch(error => {
      console.error('Error:', error);
   });
}

const formato = new Intl.NumberFormat('es-MX', { minimumFractionDigits: 0, maximumFractionDiits: 0 });


// Funciones de autocomplete para búsqueda de clientes (similares a las de mascotas)
//***********************************************************************************************
function initAutocomplete(inputElement, left = null, top = null) {
   if (!inputElement) return;
   let timeoutId;
	inputElement.addEventListener('input', function (e) {
      clearTimeout(timeoutId);
      const searchTerm = e.target.value.trim();
      if (searchTerm.length < 2) {
         hideSuggestions();
			if (inputElement.id == 'idCliente') {
				resetOwnerSelection();
			}
         return;
      }
      timeoutId = setTimeout(() => {
         searchClients(searchTerm, inputElement, left, top);
      }, 300);
   });

   // Manejar selección con teclado
   inputElement.addEventListener('keydown', function (e) {
      const suggestions = document.getElementById('clientSuggestions');
      if (!suggestions || suggestions.style.display === 'none') return;
      const items = suggestions.querySelectorAll('.list-group-item');
      let activeItem = suggestions.querySelector('.active');
      switch (e.key) {
         case 'ArrowDown':
            e.preventDefault();
            if (!activeItem) {
               items[0]?.classList.add('active');
            } else {
               activeItem.classList.remove('active');
               const next = activeItem.nextElementSibling || items[0];
               next.classList.add('active');
            }
            break;
         case 'ArrowUp':
            e.preventDefault();
            if (!activeItem) {
               items[items.length - 1]?.classList.add('active');
            } else {
               activeItem.classList.remove('active');
               const prev = activeItem.previousElementSibling || items[items.length - 1];
               prev.classList.add('active');
            }
            break;
         case 'Enter':
            e.preventDefault();
            if (activeItem) {
               selectClient(activeItem, inputElement);
            }
            break;
         case 'Escape':
            hideSuggestions();
            break;
      }
   });
	// Cerrar sugerencias al hacer clic fuera
	document.addEventListener('click', function(e) {
		if (!inputElement.contains(e.target) &&
			!document.getElementById('clientSuggestions')?.contains(e.target)) {
			hideSuggestions();
		}
	});
}


//***********************************************************************************************
function searchClients(searchTerm, inputElement, left, top) {
   let listWhere = [];
   listWhere.push({
      "id": "status",
      "value": '1',
      "like": false
   })
   listWhere.push({
      "id": "name",
      "value": searchTerm,
      "like": true
   })
   listWhere = JSON.stringify(listWhere);
   const formData = new FormData();
   formData.append("modulo", "dival");
   formData.append("option", "clientes");
   formData.append("action", "searchClient");
   formData.append("searchTerm", searchTerm);
   fetch('helpers/ajaxRouter.php', {
         method: 'POST',
         body: formData
   }).then(response => response.json())
   .then(clients => {
      showClientSuggestions(clients, inputElement, left, top);
   })
   .catch(error => {
      console.error('Error:', error);
   });
}


//***********************************************************************************************
function showClientSuggestions(clients, inputElement, left, top) {
   hideSuggestions();
   if (clients.length === 0) return;
   const suggestionsDiv = document.createElement('div');
   suggestionsDiv.id = 'clientSuggestions';
   suggestionsDiv.className = 'list-group position-absolute';
   suggestionsDiv.style.zIndex = '1000';
   suggestionsDiv.style.width = inputElement.offsetWidth + 'px';
   suggestionsDiv.style.maxHeight = '200px';
   suggestionsDiv.style.overflowY = 'auto';
   clients.forEach(client => {
      const item = document.createElement('button');
      item.type = 'button';
      item.className = 'list-group-item list-group-item-action';
      item.innerHTML = `
			<div class="fw-bold">${client.TerDocId} ${client.TerNombr}</div>
			<small class="text-label">${client.TerEmail} | Teléfono: ${client.TerTele1}</small>
			${client.nivel_riezgo ? `<br><small class="text-label">N.R.: ${client.nivel_riezgo}</small>` : ''}
      `;
      item.dataset.clientId = client.id_dvcliente;
      item.dataset.clientData = JSON.stringify(client);
      item.addEventListener('click', function () {
         selectClient(this, inputElement);
      });
      item.addEventListener('mouseenter', function () {
         suggestionsDiv.querySelectorAll('.list-group-item').forEach(i => i.classList.remove('active'));
         this.classList.add('active');
      });
      suggestionsDiv.appendChild(item);
   });

   // Posicionar debajo del input
   const rect = inputElement.getBoundingClientRect();
	// suggestionsDiv.style.top = (rect.bottom + window.scrollY) + 'px';
   // suggestionsDiv.style.left = (rect.left + window.scrollX) + 'px';
	if (left == null) {
		left = rect.left;
	}
	if (top == null) {
		top = rect.bottom;
	}
   suggestionsDiv.style.top = top + 'px';
   suggestionsDiv.style.left = left + 'px';
   //document.getElementById('formCtaclienAdd').appendChild(suggestionsDiv);
   document.getElementsByClassName('form-search')[0].appendChild(suggestionsDiv);
   // document.body.appendChild(suggestionsDiv);
}

//***********************************************************************************************
/*
function selectClient(selectedItem, inputElement) {
   const clientData = JSON.parse(selectedItem.dataset.clientData);
   document.getElementById('idCliente').value = clientData.id_dvcliente;
   inputElement.value = `${clientData.TerDocId} ${clientData.TerNombr} (${clientData.TerEmail})`;
   //loadCtasByClient(clientData.id_dvcliente);
   hideSuggestions();
}
*/

//***********************************************************************************************
function resetOwnerSelection() {
   document.getElementById('idCliente').value = '';
   // const petSelect = document.getElementById('id_pet');
   // petSelect.innerHTML = '<option value="">Seleccionar Mascota</option>';
   // petSelect.disabled = true;
}


//***********************************************************************************************
function hideSuggestions() {
   const existing = document.getElementById('clientSuggestions');
   if (existing) {
      existing.remove();
   }
}


//***********************************************************************************************
function getRiskBadgeClass($nivelRiesgo) {
	switch($nivelRiesgo) {
		case 1: return 'success';
		case 2: return 'info';
		case 3: return 'warning';
		case 4: return 'danger';
		default: return 'secondary';
	}
}


//*********************************************************************************************
function formatCurrency(amount, simbol, decimals = 0) {
   return (simbol == 1)? '$':'' + amount.toLocaleString('es-MX', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
   //return (simbol == 1)? '$':'' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}


//*********************************************************************************************
function setupPaginationEvents(dataPaginacion, itemsPage = 20) {
   const prevBtn = document.querySelector('[data-list-pagination="prev"]');
   const nextBtn = document.querySelector('[data-list-pagination="next"]');
   const viewAllLink = document.querySelector('[data-list-view="*"]');
   const viewLessLink = document.querySelector('[data-list-view="less"]');
   if (prevBtn) {
      prevBtn.addEventListener('click', (e) => {
         e.preventDefault();
         if (dataPaginacion) {
            let currentPage = 1;
            const activePage = document.querySelector('.pagination .active');
            if (activePage) {
               currentPage = parseInt(activePage.textContent) || 1;
            }
            if (currentPage > 1) {
               const pageSize = dataPaginacion.page;
               const startIndex = (currentPage - 2) * pageSize + 1;
               dataPaginacion.show(startIndex, pageSize);
            }
         }
      });
   }

   if (nextBtn) {
      nextBtn.addEventListener('click', (e) => {
         e.preventDefault();
         if (dataPaginacion) {
            let currentPage = 1;
            const activePage = document.querySelector('.pagination .active');
            if (activePage) {
               currentPage = parseInt(activePage.textContent) || 1;
            }
            const totalPages = Math.ceil(dataPaginacion.items.length / dataPaginacion.page);
            if (currentPage < totalPages) {
               const pageSize = dataPaginacion.page;
               const startIndex = currentPage * pageSize + 1;
               dataPaginacion.show(startIndex, pageSize);
            }

         }
      });
   }

   if (viewAllLink) {
      viewAllLink.addEventListener('click', (e) => {
         e.preventDefault();
         if (dataPaginacion) {
            dataPaginacion.page = dataPaginacion.items.length;
            dataPaginacion.update();
            viewAllLink.classList.add('d-none');
            if (viewLessLink) viewLessLink.classList.remove('d-none');
            // viewLessLink.classList.remove('d-none');
         }
      });
   }
   if (viewLessLink) {
      viewLessLink.addEventListener('click', (e) => {
         e.preventDefault();
         if (dataPaginacion) {
            dataPaginacion.page = itemsPage;
            dataPaginacion.update();
            viewLessLink.classList.add('d-none');
            // viewAllLink.classList.remove('d-none');
            if (viewAllLink) viewAllLink.classList.remove('d-none');
         }
      });
   }
}


//*********************************************************************************************
// Función para actualizar la información del paginador
function updatePaginationInfo(dataPaginacion) {
   if (!dataPaginacion) return;
   const listInfo = document.querySelector('[data-list-info]');
   const list = dataPaginacion;
   const visibleItems = list.visibleItems.length;
   const totalItems = list.items.length;
   // const visibleItems = list.items.length;
   const currentPage = list.i;  // Página actual (comienza en 1)
   const itemsPerPage = list.page;  // Items por página (20)
   let start = 0;
   let end = 0;
   if (visibleItems > 0) {
      start = (currentPage - 1) * itemsPerPage + 1;
      start = (currentPage);
      if (currentPage == 1) {
         end = Math.min(currentPage * itemsPerPage, totalItems);
      } else {
         end = Number(currentPage) + Number(itemsPerPage) - 1;
         end = Math.min(end, totalItems);
      }
      start = Math.min(start, end);
   }
   const infoText = `${start} hasta ${end} de ${totalItems}`;
   listInfo.textContent = `${infoText}`;
   //$('[data-list-info]').text(infoText);
}