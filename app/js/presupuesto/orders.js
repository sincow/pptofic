document.addEventListener("DOMContentLoaded", function () {
	const formulario = document.getElementById("frmOrderAdd");
	if (formulario) {
		function ajustarCollapse() {
			const anchoPantalla = window.innerWidth;
			const collapse = document.getElementById("multiCollapseExample1");
			if (anchoPantalla >= 1200) {
				collapse.classList.add("show");
			} else {
				collapse.classList.remove("show");
			}
		}
		ajustarCollapse();
		window.addEventListener("resize", ajustarCollapse);
	}
	const formularioEdit = document.getElementById("frmOrderEdit");
	if (formularioEdit) {
		function ajustarCollapse() {
			const anchoPantalla = window.innerWidth;
			const collapse = document.getElementById("multiCollapseConditions");
			if (anchoPantalla >= 1200) {
				collapse.classList.add("show");
			} else {
				collapse.classList.remove("show");
			}
		}
		ajustarCollapse();
		window.addEventListener("resize", ajustarCollapse);
	}
});


/******************************************************************************************/
$(".frmOrders").on("click", ".orderId", function (e) {
	var idOrder = $(this).attr("idOrd");
	// window.config.config.idProducto = idProduct;
	$(".idOrde").val(idOrder);
	//$(".frmOrders").submit();
	// e.preventDefault();
	// $.post('orderdetails', { idPedido: idOrder }, function(respuesta) {
	//   console.log('Respuesta del servidor:', respuesta);
	// });
	const form = document.createElement('form');
	form.method = 'POST';
	form.action = 'orderdetails';
	form.style.display = 'none';
	const input = document.createElement('input');
	input.type = 'hidden';
	input.name = 'idPedido';
	input.value = idOrder;
	form.appendChild(input);

	const params = {
		idPedido: idOrder,
	};
	// Agregar campos ocultos al form
	/*
	for (const key in params) {
   	if (params.hasOwnProperty(key)) {
			const input = document.createElement('input');
			input.type = 'hidden';
			input.name = key;
			input.value = params[key];
			form.appendChild(input);
   	}
	}
	*/
  document.body.appendChild(form);
  form.submit();
})


/**********************************************************************/
$(".frmOrderDetails").on("change", "#idOrderQuery", function () {
	var id = $(this).val();
	$(".idOrde").val(id);
	$(".frmOrderDetails").submit();
})


/******************************************************************************************/
$("#frmOrderAdd").on("change", "#orderIdCustomer", function () {
	const formato = new Intl.NumberFormat('es-MX', { minimumFractionDigits: 0, maximumFractionDiits: 0 });
	$("#orderIdAddressShipping").empty();
	$("#orderIdAddressShipping").append('<option value="">Seleccionar</option>');
	var datos = new FormData();
	datos.append("idCustomer", $("#orderIdCustomer").val());
	datos.append("idShipping", "");
	$.ajax({
		url: "ajax/customers.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function (respuesta) {
			$("#orderNitCustomer").val(respuesta[0]["nit_customer"]);
			$("#orderNameCustomer").val(respuesta[0]["name_customer"]);
			$("#orderCityCustomer").val(respuesta[0]["code_city"]);
			$("#orderPhoneCustomer").val(respuesta[0]["phone_customer"]);
			$("#orderPhonePrincipal").val(respuesta[0]["phone_customer"]);
			$("#orderAddressPrincipal").val(respuesta[0]["address_customer"]);
			$("#orderCityPrincipal").val(respuesta[0]["name_city"] + ", " + respuesta[0]["name_department"]);
			$("#orderCreditCustomer").val(respuesta[0]["credit_customer"]);
			$("#discCustomer").val(respuesta[0]["discount_customer"]);
			// $("#orderCityPrincipal").val(respuesta[0]["name_city"]+", "+respuesta[0]["name_country"]);
			if (respuesta[0]["accounttype_customer"] == '01') {
				$("#orderAccountCustomer").val("Clase Cuenta: 01 - Con IVA");
			} else {
				$("#orderAccountCustomer").val("Clase Cuenta: 02 - Excluido IVA");
				$("#taxPercent").val(0);
			}
			$("#orderCupCreCustomer").val("Cupo Crédito: $"+formato.format(respuesta[0]["credit_customer"]));
			$("#orderDescriptionList").val("Lista Precio: " + respuesta[0]["id_list_customer"]);
			$("#orderVencinCustomer").val("Dias Vencimiento: "+respuesta[0]["expiration_customer"]);
			$("#orderDiscountCustomer").val("Descuento Máximo: "+respuesta[0]["discount_customer"]+"%");
			$("#orderDateExpiration").val(respuesta[0]["expiration_customer"]);
			$("#orderAccounType").val(respuesta[0]["accounttype_customer"]);
			$("#orderEmailPrincipal").val(respuesta[0]["email_customer"]);
			$("#orderNameSeller").val("Vendedor: " + respuesta[0]["name_seller"]);
			$("#orderIdSeller").val(respuesta[0]["id_seller_customer"]);
			$("#orderCodeSeller").val(respuesta[0]["code_seller"]);
			$("#orderIdListCustomer").val(respuesta[0]["id_list_customer"]);
			// $("#orderDescriptionList").val("Lista Precio: "+respuesta[0]["id_list_customer"]+"-"+respuesta[0]["description_list"]);
			respuesta.forEach(funcionForEach);
			function funcionForEach(item, index) {
				if (item.status_shipping == "1") {
					$("#orderIdAddressShipping").append(
						// '<option value="' + item.id_shipping + '">' + item.address_shipping + ", " + item.city_shipping + '</option>'
						'<option value="' + item.id_shipping + '">' + item.name_city_shipping + ", " + item.address_shipping + '</option>'
					)
				}
			}
		}
	})
})


/******************************************************************************************/
$("#frmOrderAdd").on("change", "#orderIdAddressShipping", function () {
	var datos = new FormData();
	datos.append("idCustomer", $("#orderIdCustomer").val());
	datos.append("idShipping", $("#orderIdAddressShipping").val());
	$.ajax({
		url: "ajax/customers.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function (respuesta) {
			$("#orderAddressShipping").val(respuesta[0]["address_shipping"]);
			$("#orderCityShipping").val(respuesta[0]["name_city_shipping"] + ", " + respuesta[0]["name_country_shipping"]);
			$("#orderEmailShipping").val(respuesta[0]["email_shipping"]);
		}
	})
})


/******************************************************************************************/
$(".frmOrderAdd").on("click", "#btnAddProductOrder", function () {
	if ($("#idProductOrderNew").val() == "") {
		return;
	}
	var idProductOrderNew = document.getElementById("idProductOrderNew");
	var productName = idProductOrderNew.options[idProductOrderNew.selectedIndex].text;
	// var priceService = Number($("#newid_service_booking option:selected").attr("price_service_booking"));
	// var productCode = $('#idProductOrderNew option:selected').dataset.code_product;
	// var productCode = idProductOrderNew.options[idProductOrderNew.selectedIndex].dataset.code_product;
	// var productCode = $('.idProductOrderNew').find('option:selected')
	//       .map(function(index,element){
	//       return $(element).attr("code_product");
	//   }).toArray();
	// var productCode = $("idProductOrderNew :selected").map((i, el) => $(el).attr("code_product")).toArray()
	var productCode = "";
	var productNam = "";
	var productName = $("#idProductOrderNew option:selected").text();
	var productPrice = 0;
	var orderIdListCustomer = $("#orderIdListCustomer").val();
	if ($("#orderIdCustomer").val() == "") {
		swal.fire({
			title: '¡Error!',
			text: "Debe seleccionar primero el cliente!",
			icon: 'error',
			confirmButtonText: 'Cerrar'
		});
		return;
	}
	const formato = new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDiits: 2 });
	// let idProductOrderNew = document.getElementById("idProductOrderNew");
	// let productName = idProductOrderNew.options[idProductOrderNew.selectedIndex].text;
	let partes = productName.split(" - "); // Divide en base al guion con espacio
	let codeProduct = partes[0];  // "A017410"
	let descripcion = partes[1];
	addProductOrder($("#idProductOrderNew").val(), orderIdListCustomer, codeProduct, productName, 0);
	$("#idProductOrderNew").val("");
	$("#idProductOrderNew").trigger("change");
	// $("#idProductOrderNew").select();

	/*
	var datos = new FormData();
	datos.append("getProducts", "ok");
	datos.append("idProduct", $("#idProductOrderNew").val());
	datos.append("idPriceList", orderIdListCustomer);
	datos.append("codeProduct", codeProduct);
	$.ajax({
		url: "ajax/products.ajax.php",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function (respuesta) {
			if (respuesta == false || respuesta == "") {
				swal.fire({
					title: 'Atención!',
					// text: "No se encontró el artícuo en la lista de precio " + $("#orderIdListCustomer").val(),
					text: "Este artículo está agotado",
					icon: 'info',
					confirmButtonText: 'Cerrar'
				});
				return;
			}
			productCode = respuesta["code_product"];
			productNam = respuesta["description_product"];
			productPrice = respuesta["price_price"];
			productStock = respuesta["stock_product"];
			productDscto = respuesta["discount_product"];
			if ($("#discCustomer").val() < productDscto) {
				productDscto = $("#discCustomer").val();
			}
			productId = respuesta["id_product"];
			productTax = Number($("#taxPercent").val());;
			// var power = Math.pow(10,2);
			// productPrice = Math.round(respuesta["price_price"] * power) / power;
			$("#orderaddTable-body").append(
				'<tr>' +
					'<td class="p-0 pt-1">' +
						productCode +
						// '<input class="form-control-plaintext outline-none text-start text-800 py-0 productOrderCod" type="text" value ='+productCode+' readonly/>'+
						'<input type="hidden" class="productOrderid" value=' + productId + '>' +
						'<input type="hidden" class="productOrderCod" value="' + productCode + '">' +
						'<input type="hidden" class="productOrderNam" value="' + productNam + '">' +
					'</td>' +
					'<td class="p-0 pt-1">' +
						productName +
					'</td>' +
					'<td class="p-0 pt-1 px-2 text-end">' +
						productStock +
					'</td>' +
					'<td class="p-0 pt-1 px-2 text-end">' +
						formato.format(productPrice) +
						'<input type="hidden" class="productOrderSto" value=' + productStock + '>' +
						'<input type="hidden" class="productOrderPri" value=' + productPrice + '>' +
						'<input type="hidden" class="productOrderTax" value=' + productTax + '>' +
						'<input type="hidden" class="productPorDscto" value=' + productDscto + '>' +
					'</td>' +
					'<td class="p-0 pt-1 px-2 text-end">' +
						formato.format(productDscto) +
					// '<input class="form-control py-1 text-end productOrderDes" type="number" min="0" max="100" step="0.50" value='+productDscto+'>'+
					'</td>' +
					'<td class="p-0 px-2">' +
						'<input class="form-control py-1 text-end productOrderCan" type="number" min="0" value=0 >' +
					'</td>' +
					'<td class="p-0 pt-1 text-end">' +
						'<input class="form-control-plaintext outline-none text-end text-1000 py-0 productSubtotal" type="text" value = 0.00 disabled readonly>' +
					'</td>' +
				'</tr>'
			);
			$(".productOrderCan").select();
			$(".productOrderCan").focus();
		}
	})
	*/
})


async function addProductOrder(idProduct, idPriceList, codeProduct, productName, productAmount) {
	const formato = new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDiits: 2 });
	var datos = new FormData();
	datos.append("getProducts", "ok");
	datos.append("idProduct", $("#idProductOrderNew").val());
	datos.append("idPriceList", idPriceList);
	datos.append("codeProduct", codeProduct);
	let ajaxUrl ="ajax/products.ajax.php";
	if ($("#companytype").val() == "2") {
		datos.append("option", "inventories");
		datos.append("action", "getInventoryProduct");
		ajaxUrl = 'ajax/ajaxRouter.php';
	}

	const respuesta = await $.ajax({
		url: ajaxUrl,
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		success: function (respuesta) {
			console.log(respuesta);
			if (respuesta == false || respuesta == "") {
				swal.fire({
					title: 'Atención!',
					text: "Este artículo " + codeProduct + " - " + productName + " está agotado",
					icon: 'info',
					confirmButtonText: 'Cerrar'
				});
				return;
			}
			productCode = respuesta["code_product"];
			productNam = respuesta["description_product"];
			productPrice = respuesta["price_price"];
			producCost   = respuesta["cost_inventory"];
			productStock = respuesta["stock_product"];
			productDscto = respuesta["discount_product"];
			if ($("#discCustomer").val() < productDscto) {
				productDscto = $("#discCustomer").val();
			}
			productId = respuesta["id_product"];
			productTax = Number($("#taxPercent").val());
			if ($("#orderAccounType").val() == "02") {
				productTax = 0;
			}
			var subto = productAmount * productPrice;
			var disco = Math.round(Number(subto) * productDscto / 100);
			subto = subto - disco;
			var productSubtotal = formato.format(subto);
			//$(gross).val(formato.format(subto));
			// var power = Math.pow(10,2);
			// productPrice = Math.round(respuesta["price_price"] * power) / power;
			$("#orderaddTable-body").append(
				'<tr>' +
					'<td class="p-0 pt-1">' +
						productCode +
						// '<input class="form-control-plaintext outline-none text-start text-800 py-0 productOrderCod" type="text" value ='+productCode+' readonly/>'+
						'<input type="hidden" class="productOrderid" value=' + idProduct + '>' +
						'<input type="hidden" class="productOrderCod" value="' + productCode + '">' +
						'<input type="hidden" class="productOrderNam" value="' + productName + '">' +
					'</td>' +
					'<td class="p-0 pt-1">' +
						productName +
					'</td>' +
					'<td class="p-0 pt-1 px-2 text-end">' +	
						productStock +
					'</td>' +
					'<td class="p-0 pt-1 px-2 text-end">' +
						formato.format(productPrice) +
						'<input type="hidden" class="productOrderSto" value=' + productStock + '>' +
						'<input type="hidden" class="productOrderPri" value=' + productPrice + '>' +
						'<input type="hidden" class="productOrderCos" value=' + producCost + '>' +
						'<input type="hidden" class="productOrderTax" value=' + productTax + '>' +
						'<input type="hidden" class="productPorDscto" value=' + productDscto + '>' +
					'</td>' +
					'<td class="p-0 pt-1 px-2 text-end">' +
						formato.format(productDscto) +
					// '<input class="form-control py-1 text-end productOrderDes" type="number" min="0" max="100" step="0.50" value='+productDscto+'>'+
					'</td>' +
					'<td class="p-0 px-2">' +
						'<input class="form-control py-1 text-end productOrderCan" type="number" min="0" value=' + productAmount + ' >' +
					'</td>' +
					'<td class="p-0 pt-1 text-end">' +
						'<input class="form-control-plaintext outline-none text-end text-1000 py-0 productSubtotal" type="text" value = ' + productSubtotal + ' disabled readonly>' +
					'</td>' +
				'</tr>'
			);
			$(".productOrderCan").select();
			$(".productOrderCan").focus();
		}
	})
	totOrderNew();
	return new Promise((resolve) => {
		resolve(respuesta);
	});
}


/******************************************************************************************/
$("#frmOrderAdd").on("change", ".productOrderDes", function () {
	var discount = $(this).parent().parent().children().children(".productPorDscto").val();
	if ($(this).val() > discount) {
		swal.fire({
			title: '¡Error!',
			text: "El descuento no puede ser mayor al permitido!",
			icon: 'error',
			confirmButtonText: 'Cerrar'
		});
		this.value = discount;
	}
})


/******************************************************************************************/
$(".frmOrderAdd").on("change", ".productOrderCan", function () {
	const formato = new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDiits: 2 });
	if ($(this).val() < 0) {
		$(this).val(0);
	}
	var idPriceList = $("#orderIdListCustomer").val();

	datos.append("getProducts", "ok");
	datos.append("idProduct", $("#idProductOrderNew").val());
	datos.append("idPriceList", idPriceList);
	datos.append("codeProduct", codeProduct);
	let ajaxUrl ="ajax/products.ajax.php";
	if ($("#companytype").val() == "2") {
		datos.append("option", "inventories");
		datos.append("action", "getInventoryProduct");
		ajaxUrl = 'ajax/ajaxRouter.php';
	}

	var stock = $(this).parent().parent().children().children(".productOrderSto");
	var price = $(this).parent().parent().children().children(".productOrderPri");
	var pordi = $(this).parent().parent().children().children(".productPorDscto");
	var gross = $(this).parent().parent().children().children(".productSubtotal");
	if (Number($(this).val()) > Number($(stock).val())) {
		swal.fire({
			title: '¡Error!',
			text: "La cantidad pedida no puede ser mayor que la existencia!",
			icon: 'error',
			confirmButtonText: 'Cerrar'
		});
		$(this).val(Number($(stock).val()));
	}
	var subto = Number($(this).val()) * Number($(price).val());
	var disco = Math.round(Number(subto) * Number($(pordi).val()) / 100);
	subto = subto - disco;
	$(gross).val(formato.format(subto));
	totOrderNew();
})


/******************************************************************************************/
$("#frmOrderAdd").on("change", ".productOrderDes", function () {
	const formato = new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDiits: 2 });
	if ($(this).val() < 0 || $(this).val() > 100) {
		$(this).val(0);
	}
	var price = $(this).parent().parent().children().children(".productOrderPri");
	var amoun = $(this).parent().parent().children().children(".productOrderCan");
	var gross = $(this).parent().parent().children().children(".productSubtotal");
	var subto = Number($(amoun).val()) * Number($(price).val());
	var disco = Math.round(Number(subto) * Number($(this).val()) / 100);
	subto = subto - disco;
	$(gross).val(formato.format(subto));
	totOrderNew();
})


/******************************************************************************************/
function totOrderNew() {
	const formato = new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDiits: 2 });
	var productOrderPri = $(".productOrderPri");
	var productOrderCos = $(".productOrderCos");
	var productPorDscto = $(".productPorDscto");
	var productOrderCan = $(".productOrderCan");
	var productSubtotal = $(".productSubtotal");
	var taxPercet = Number($("#taxPercent").val());
	var shippPercent = Number($("#shippPercent").val());
	var totOrderGross = 0;
	var totOrderCosts = 0;
	var totOrderDisco = 0;
	var totOrderTaxes = 0;
	var totOrderShipp = 0;
	if ($("#orderAccounType").val() == "02") {
		var taxPercet = 0;
	}
	for (var i = 0; i < productOrderCan.length; i++) {
		var subto = Number($(productOrderCan[i]).val()) * Number($(productOrderPri[i]).val());
		var disco = Math.round(Number(subto) * Number($(productPorDscto[i]).val()) / 100);
		subto = subto - disco;
		$(productSubtotal[i]).val(formato.format(subto));
		totOrderGross = totOrderGross + $(productOrderCan[i]).val() * $(productOrderPri[i]).val();
		valORderGross = ($(productOrderCan[i]).val() * $(productOrderPri[i]).val()).toFixed(2);;
		totOrderDisco = totOrderDisco + Math.round(valORderGross * $(productPorDscto[i]).val() / 100);
		valOrderDisco = Math.round(valORderGross * $(productPorDscto[i]).val() / 100);
		totOrderTaxes = totOrderTaxes + (valORderGross - valOrderDisco) * taxPercet / 100;
		totOrderCosts = totOrderCosts + $(productOrderCan[i]).val() * $(productOrderCos[i]).val();
	}
	totOrderShipp = Math.round((totOrderGross - totOrderDisco) * shippPercent / 100);
	$(".totOrderGross").html("$" + formato.format(totOrderGross));
	$("#totOrderGross").val(totOrderGross);
	$("#totOrderCosts").val(totOrderCosts);
	$(".totOrderDiscount").html("-$" + formato.format(totOrderDisco));
	$("#totOrderDiscount").val(totOrderDisco);
	$(".totOrderTax").html("$" + formato.format(0));
	$("#totOrderTax").val(0);
	if ($("#orderAccounType").val() == "01") {
		$(".totOrderTax").html("$" + formato.format(totOrderTaxes));
		$("#totOrderTax").val(totOrderTaxes);
	}
	$(".totOrderShipping").html("$" + formato.format(totOrderShipp));
	$("#totOrderShipping").val(totOrderShipp);
	totOrderGross = totOrderGross - totOrderDisco + totOrderTaxes;
	$(".totOrderSubtotal").html("$" + formato.format(totOrderGross));
	totOrderGross = totOrderGross + totOrderShipp;
	$(".totOrderTotal").html("$" + formato.format(totOrderGross));
	$("#totOrderTotal").val(totOrderGross);
}


/******************************************************************************************/
function listProductosPed() {
	var orderProductsList = [];
	var productOrderid = $(".productOrderid");
	var productOrderCod = $(".productOrderCod");
	var productOrderNam = $(".productOrderNam");
	var productOrderPri = $(".productOrderPri");
	var productOrderCos = $(".productOrderCos");
	var productPorDscto = $(".productPorDscto");
	var productOrderTax = $(".productOrderTax");
	var productOrderCan = $(".productOrderCan");
	for (var i = 0; i < productOrderid.length; i++) {
		if ($(productOrderCan[i]).val() > 0) {
			orderProductsList.push({
				"productOrderid": $(productOrderid[i]).val(),
				"productOrderCod": $(productOrderCod[i]).val(),
				"productOrderNam": $(productOrderNam[i]).val(),
				"productOrderPri": $(productOrderPri[i]).val(),
				"productOrderCos": $(productOrderCos[i]).val(),
				"productOrderDes": $(productPorDscto[i]).val(),
				"productOrderTax": $(productOrderTax[i]).val(),
				"productOrderCan": $(productOrderCan[i]).val()
			})
		}
	}
	$("#orderProductsList").val(JSON.stringify(orderProductsList));
}


/******************************************************************************************/
$("#frmOrderAdd").on("click", "#btnOrderAddDraft", function () {
	var valid = "";
	$("#orderDraft").val(0);
	$('.gesAlert').html("");
	$("#frmOrderAdd").removeClass("was-validated")
	// $("#orderIdCustomer").addClass("is-invalid");
	if ($("#orderProductsList").val() == "[]") {
		$('.gesAlert').append('<div class="alert alert-soft-danger d-flex p-2 mb-0" role="alert">' +
			'<span class="fas fa-times-circle text-danger fs-3 me-3"></span>' +
			'<p class="mb-0 flex-1">El pedido no se graba si no hay artículos!</p>' +
			'<button class="btn-close float-end" type="button" data-bs-dismiss="alert" aria-label="Close" style="width: 0.07rem;"></button>' +
			'</div>');
		$("#frmOrderAdd").addClass("was-validated")
		valid = "1";
		// return;
	}
	if (valid == "") {
		listProductosPed();
		swal.fire({
			title: '¿Está seguro de Grabar borrador el Documento?',
			text: "¡Si no lo está puede cancelar la accíón!",
			icon: 'question',
			showCancelButton: true,
			reverseButtons: true,
			focusCancel: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			cancelButtonText: 'No, Cancelar',
			confirmButtonText: 'Si, Grabar!'
		}).then(function (result) {
			if (result.value) {
				$("#orderDraft").val(1);
				$("#frmOrderAdd").submit();
			}
		})
	}
})


/******************************************************************************************/
$("#frmOrderAdd").on("click", "#btnOrderAddExcel", function () {
	if ($("#fileOrderExcel").val() == "") {
		swal.fire({
			title: '¡Error!',
			text: "Debe seleccionar primero el archivo Excel!",
			icon: 'error',
			confirmButtonText: 'Cerrar'
		});
		return;
	}
	var archivoSeleccionado = document.getElementById("fileOrderExcel");
	var formulario = document.getElementById("frmOrderAdd");
	var file = archivoSeleccionado.files[0];
	var datos = new FormData();
	datos.append("fileOrderExcel", file);
	const formData = new FormData(formulario);
	$.ajax({
		url: "ajax/subirexcel.ajax.php",
      type: 'POST',
      data: datos,
      contentType: false,
      processData: false,
      beforeSend: function () {
      	$('#orderaddTable-body').empty();
      	$('#orderaddTable-body').append('<tr><td></td><td style="padding:0px 1px;"><div class="alert alert-outline-info">Importando archivo...</div></td></tr>');
      },
      success: async function (response) {
			$('#orderaddTable-body').empty();
			var orderIdListCustomer = $("#orderIdListCustomer").val();
			if (response.success) {
        		const registros = response.registros;
				const promesas = registros.forEach(reg => {
					idProduct = reg.id;
					codeProduct = reg.codigo;
					productName = reg.descripcion;
					productAmount = reg.cantidad;
					return addProductOrder(idProduct, orderIdListCustomer, codeProduct, productName, productAmount);
				});
				await Promise.all(promesas);
				totOrderNew();
			} else {
				swal.fire({
					title: '¡Error!',
					// text: "Debe seleccionar primero el cliente!",
					html: response.errores,
					icon: 'error',
					confirmButtonText: 'Cerrar'
				});
				$('#orderaddTable-body').empty();
				$('#orderaddTable-body').append('<tr><td></td><td style="padding:0px 1px;"><div class="alert alert-outline-danger">Ocurrió un error al importar el archivo.</div></td></tr>');
			}
      },
      error: function () {
			swal.fire({
				title: '¡Error!',
				// text: "Debe seleccionar primero el cliente!",
				html: "Ocurrio un error al importar el archivo.",
				icon: 'error',
				confirmButtonText: 'Cerrar'
			});
      	$('#orderaddTable-body').empty();
      	$('#orderaddTable-body').append('<tr><td></td><td style="padding:0px 1px;"><div class="alert alert-outline-danger">Ocurrió un error al importar el archivo.</div></td></tr>');
      }
   });
	//totOrderNew();
})


/******************************************************************************************/
$("#frmOrderEdit").on("click", "#btnOrderAddDraft", function () {
	var valid = "";
	$("#orderDraft").val(0);
	$('.gesAlert').html("");
	$("#frmOrderEdit").removeClass("was-validated")
	// $("#orderIdCustomer").addClass("is-invalid");
	if ($("#orderProductsList").val() == "[]") {
		$('.gesAlert').append('<div class="alert alert-soft-danger d-flex p-2 mb-0" role="alert">' +
			'<span class="fas fa-times-circle text-danger fs-3 me-3"></span>' +
			'<p class="mb-0 flex-1">El pedido no se graba si no hay artículos!</p>' +
			'<button class="btn-close float-end" type="button" data-bs-dismiss="alert" aria-label="Close" style="width: 0.07rem;"></button>' +
			'</div>');
		$("#frmOrderEdit").addClass("was-validated")
		valid = "1";
		// return;
	}
	if (valid == "") {
		listProductosPed();
		swal.fire({
			title: '¿Está seguro de Grabar borrador el Documento?',
			text: "¡Si no lo está puede cancelar la accíón!",
			icon: 'question',
			showCancelButton: true,
			reverseButtons: true,
			focusCancel: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			cancelButtonText: 'No, Cancelar',
			confirmButtonText: 'Si, Grabar!'
		}).then(function (result) {
			if (result.value) {
				$("#orderDraft").val(1);
				$("#frmOrderEdit").submit();
			}
		})
	}
})


/******************************************************************************************/
$("#frmOrderAdd").on("mouseover", "#btnOrderAdd", function () {
	listProductosPed();
})


/******************************************************************************************/
$("#frmOrderAdd").on("click", "#btnOrderAdd", function () {
	var valid = "";
	$('.gesAlert').html("");
	$("#frmOrderAdd").removeClass("was-validated")
	// $("#orderIdCustomer").addClass("is-invalid");
	if ($("#orderProductsList").val() == "[]") {
		$('.gesAlert').append('<div class="alert alert-soft-danger d-flex p-2 mb-0" role="alert">' +
			'<span class="fas fa-times-circle text-danger fs-3 me-3"></span>' +
			'<p class="mb-0 flex-1">El pedido no se graba si no hay artículos!</p>' +
			'<button class="btn-close float-end" type="button" data-bs-dismiss="alert" aria-label="Close" style="width: 0.07rem;"></button>' +
			'</div>');
		$("#frmOrderAdd").addClass("was-validated")
		valid = "1";
		// return;
	}
	// if ($("#totOrderTotal").val() > $("#orderCreditCustomer").val()) {
	// 	$('.gesAlert').append('<div class="alert alert-soft-danger d-flex p-2 mb-0" role="alert">' +
	// 		'<span class="fas fa-times-circle text-danger fs-3 me-3"></span>' +
	// 		'<p class="mb-0 flex-1">El total del pedido no puede ser mayor al cupo de crédito del cliente!</p>' +
	// 		'<button class="btn-close float-end" type="button" data-bs-dismiss="alert" aria-label="Close" style="width: 0.07rem;"></button>' +
	// 		'</div>');
	// 	$("#frmOrderAdd").addClass("was-validated")
	// 	valid = "1";
	// }
	if (valid == "") {
		swal.fire({
			title: '¿Está seguro de Grabar el Documento?',
			text: "¡Si no lo está puede cancelar la accíón!",
			icon: 'question',
			showCancelButton: true,
			reverseButtons: true,
			focusCancel: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			cancelButtonText: 'No, Cancelar',
			confirmButtonText: 'Si, Grabar!'
		}).then(function (result) {
			if (result.value) {
				$("#frmOrderAdd").submit();
			}
		})
	}
})


/******************************************************************************************/
$(document).on("click", ".btnOrderUpdtPayment", function () {
	$("#idOrderPayment").val($(this).attr("idOrder"));
})


/******************************************************************************************/
$("#frmorderupdtPayment").on("click", "#btnOrderupdtPayment", function () {
})


/******************************************************************************************/
$(document).on("click", ".btnOrderUpdtDelivery", function () {
	$("#idOrderDelivery").val($(this).attr("idOrder"));
})


/******************************************************************************************/
$(document).on("click", ".btnOrderUpdtEnlistment", function () {
	$("#idOrderEnlistment").val($(this).attr("idOrder"));
})


/******************************************************************************************/
$(document).on("click", ".btnOrderUpdtReception", function () {
	$("#idOrderReception").val($(this).attr("idOrder"));
})

$(document).on("click", "#btnOrderProc", function () {
	// $(".frmOrderProcess").on("click", "#btnOrderProc", function () {
	/*
	swal.fire({
		title: '¿Está seguro de Procesar el Pedido?',
		text: "¡Si no lo está puede cancelar la accíón!",
		icon: 'question',
		showCancelButton: true,
		reverseButtons: true,
		focusCancel: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'No, Cancelar',
		confirmButtonText: 'Si, Grabar!'
	}).then(function (result) {
		if (result.value) {
			alert(result.value);
			// $("#frmOrderAdd").submit();
		}
	})
	*/
})


$("#frmOrderProcess").on("click", "#btnOrderProcess", function () {
	listProductosPed();
	swal.fire({
		title: '¿Está seguro de Procesar el Pedido?',
		text: "¡Si no lo está puede cancelar la accíón!",
		icon: 'question',
		showCancelButton: true,
		reverseButtons: true,
		focusCancel: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'No, Cancelar',
		confirmButtonText: 'Si, Grabar!'
	}).then(function (result) {
		if (result.value) {
			$("#frmOrderProcess").submit();
		}
	})
})


$("#frmOrderProcess").on("click", "#btnOrderUpdate", function () {
	$("#newOrderIdDiv").toggle();
	// $("#newOrderDiv").show();
	$("#newOrderId").select();
	$("#newOrderId").focus();
	// listProductosPed();
	// swal.fire({
	// 	title: '¿Está seguro de Sincronizar el Pedido?',
	// 	text: "¡Si no lo está puede cancelar la accíón!",
	// 	icon: 'question',
	// 	showCancelButton: true,
	// 	reverseButtons: true,
	// 	focusCancel: true,
	// 	confirmButtonColor: '#3085d6',
	// 	cancelButtonColor: '#d33',
	// 	cancelButtonText: 'No, Cancelar',
	// 	confirmButtonText: 'Si, Grabar!'
	// }).then(function (result) {
	// 	if (result.value) {
	// 		$("#frmOrderProcess").submit();
	// 	}
	// })
})


$("#frmOrderProcess").on("click", "#btnNewOrderId", function () {
	if ($("#newOrderId").val() == "") {
		swal.fire({
			title: '¡Error!',
			text: "Debe digitar el número de pedido!",
			icon: 'error',
			confirmButtonText: 'Cerrar'
		});
		$("#newOrderId").select();
		$("#newOrderId").focus();
		return;
	}
	swal.fire({
		title: '¿Está seguro de Sincronizar el Número del Pedido?',
		text: "¡Si no lo está puede cancelar la accíón!",
		icon: 'question',
		showCancelButton: true,
		reverseButtons: true,
		focusCancel: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'No, Cancelar',
		confirmButtonText: 'Si, Grabar!'
	}).then(function (result) {
		if (result.value) {
			$("#orderProcessAction").val("2");
			// $("#frmOrderProcess").removeClass("was-validated");
			// $("#newOrderId").removeClass("is-invalid");
			$("#frmOrderProcess").submit();
			// $("#frmNesOrderId").submit();
		}
	})
})


function viewOrderDel(idOrder) {
	$("#idOrderDel").val(idOrder);
	$("#numberOrderDel").text("Pedido Número: "+idOrder);
	$("#commentOrderDel").select();
	$("#commentOrderDel").focus();

	// swal.fire({
   //    title: '¿Está seguro de Desplegar el Detalle del Pedido?',
   //    text: "��Si no lo está puede cancelar la acción!",
   //    icon: 'question',
   //    showCancelButton: true,
   //    reverseButtons: true,
   //    focusCancel: true,
   //    confirmButtonColor: '#3085d6',
   //    cancelButtonColor: '#d33',
   //    cancelButtonText: 'No, Cancelar',
   //    confirmButtonText: 'Si, Desplegar!'
   // }).then(function (result) {
   //    if (result.value) {
   //       $("#viewOrderDel").submit();
   //    }
   // })
}

$("#frmOrderDel").on("click", "#btnOrderDel", function () {
	if ($("#commentOrderDel").val() == "") {
		swal.fire({
			title: '¡Error!',
			text: "Debe especificar motivo de la anulación!",
			icon: 'error',
			confirmButtonText: 'Cerrar'
		});
		$("#commentOrderDel").select();
		$("#commentOrderDel").focus();
		return;
	}
	swal.fire({
		title: '¿Está seguro de Anular el Pedido?',
		text: "¡Si no lo está puede cancelar la accíón!",
		icon: 'question',
		showCancelButton: true,
		reverseButtons: true,
		focusCancel: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'No, Cancelar',
		confirmButtonText: 'Si, Grabar!'
	}).then(function (result) {
		if (result.value) {
			$("#frmOrderDel").submit();
		}
	})
})
