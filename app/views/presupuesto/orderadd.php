<?php
  date_default_timezone_set('America/Bogota');
  $fecha = date('Y-m-d');
  $dateDelivery = date('Y-m-d', strtotime($fecha.' + 7days'));
?>
<div class="content pt-11">
  <div class="row mb-0">
    <div class="col-lg-3">
      <h3 class="mb-0 ps-lg-2">Grabar Pedido</h3>
    </div>
    <div class="col-lg-6">
      <div class="gesAlert">
      </div>
    </div>
    <div class="col-lg-3 pe-4">
      <nav class="mb-2 pe-2" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 float-sm-end">
          <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
          <li class="breadcrumb-item active">Grabar Pedido</li>
        </ol>
      </nav>
    </div>
  </div>
  <form class="needs-validation frmOrderAdd" role="form" id="frmOrderAdd" enctype="multipart/form-data" name="frmOrderAdd" method="post" novalidate>
    <input type="hidden" class="companytype" id="companytype" value=<?php echo $_SESSION["companytype"];?>>
    <div class="mb-2">
      <div class="row g-2">
        <div class="row g-2">
          <div class="col-12 col-md-6 col-xl-5 mb-lg-0">
            <div class="card">
              <div class="card-body d-flex flex-column justify-content-between py-2">
                <div class="row align-items-center g-1 text-sm-start">
                <!-- <div class="row align-items-center g-1 text-center text-sm-start"> -->
                  <div class="col-12 col-sm-auto flex-1">
                    <div class="form-control mb-3 p-0 border-0">
                      <label for="orderIdCustomer">Nombre del Cliente</label>
                      <!-- <select class="form-select" id="orderIdCustomer" name="orderIdCustomer" data-choices="data-choices" data-options='{"removeItemButton":true,"placeholder":true}' required> -->
                      <select class="form-control select2" style="width: 100%;" name="orderIdCustomer" id="orderIdCustomer">
                        <option selected="selected" value="">Seleccionar</option>
                        <?php
                          $table = "customers";
                          $order = "name_customer";
                          $where = "status_customer = '1'";
                          if ($_SESSION["profile"] == 'V' ) {
                            $where = "id_seller_customer = ".$_SESSION["idSeller"]." AND status_customer = '1'";
                          }
                          $customers = GeneralModel::getAll($table, $order, $where);
                          foreach ($customers as $key => $value) {
                            echo '
                            <option value='.$value["id_customer"].'>'.$value["nit_customer"].' - '.$value["name_customer"].'</option>
                            ';
                          }
                        ?>
                      </select>
                    </div>
                    <div class="mb-0">
                      <div class="row">
                        <div class="col-12 col-md-6">
                          <div class="form-control mb-0 p-0 border-0">
                            <!-- <label for="orderAddressPrincipal">Dirección</label> -->
                            <div class="row">
                              <div class="col-auto col-md-2 col-xl-1 me-xl-3 pt-1 pe-2">
                                <span class="fas fa-map-marker-alt"></span>
                              </div>
                              <div class="col-auto col-md-10 col-xl-10 px-0">
                                <input class="form-control-plaintext py-0 text-700 fw-bold" id="orderAddressPrincipal" name="orderAddressPrincipal" type="text" placeholder='Dirección Principal' readonly/>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-auto col-md-2 col-xl-1 me-xl-3 pt-1 pe-2">
                                <span class="fas fa-city"></span>
                              </div>
                              <div class="col-auto col-md-10 col-xl-10 px-0">
                                <input class="form-control-plaintext outline-none py-0 text-700 fw-bold" id="orderCityPrincipal" type="text" placeholder="Ciudad, Departamento" readonly/>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-auto col-md-2 col-xl-1 me-xl-3 pt-1 pe-2">
                                <span class="fas fa-user"></span>
                              </div>
                              <div class="col-auto col-md-10 col-xl-10 px-0">
                                <input class="form-control-plaintext outline-none py-0 text-700 fw-bold" id="orderNameSeller" type="text" placeholder="Vendedor" readonly/>
                              </div>
                            </div>
                            <!-- <input class="form-control-plaintext outline-none py-0 text-700 fw-bold" id="orderAccountCustomer" type="text" placeholder="Clase Cuenta" readonly/> -->
                          </div>
                        </div>
                        <div class="col-12 col-md-6">
                          <div class="form-control mb-0 p-0 border-0">
                            <!-- <label for="orderEmailPrincipal">Correo electrónico</label> -->
                            <div class="row">
                              <div class="col-auto col-md-2 col-xl-1 me-xl-3 pt-1 pe-2">
                                <span class="fas fa-envelope"></span>
                              </div>
                              <div class="col-auto col-md-10 col-xl-10 px-0">
                                <input class="form-control-plaintext outline-none py-0 text-700 fw-bold" id="orderEmailPrincipal" name="orderEmailPrincipal" type="email" placeholder="nombre@correo.com" readonly/>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-auto col-md-2 col-xl-1 me-xl-3 pt-1 pe-2">
                                <span class="fas fa-phone"></span>
                              </div>
                              <div class="col-auto col-md-10 col-xl-10 px-0">
                                <input class="form-control-plaintext outline-none py-0 text-700 fw-bold" id="orderPhonePrincipal" name="orderPhonePrincipal" type="text" placeholder="Teléfono" readonly/>
                              </div>
                            </div>
                            <!-- <input class="form-control-plaintext outline-none py-0 text-700 fw-bold" id="orderDescriptionList" type="text" placeholder="Lista Precio, Vencimiento" readonly/> -->
                            <input type="hidden" type="text" id="orderIdSeller" name="orderIdSeller" value=""/>
                            <input type="hidden" id="orderCodeSeller" name="orderCodeSeller" value=""/>
                            <input type="hidden" id="orderNitCustomer" name="orderNitCustomer" value=""/>
                            <input type="hidden" id="orderNameCustomer" name="orderNameCustomer" value=""/>
                            <input type="hidden" id="orderCityCustomer" name="orderCityCustomer" value=""/>
                            <input type="hidden" id="orderPhoneCustomer" name="orderPhoneCustomer" value=""/>
                            <input type="hidden" id="orderIdListCustomer" name="orderIdListCustomer" value=""/>
                            <input type="hidden" id="orderAccounType" name="orderAccounType" value=""/>
                            <input type="hidden" id="orderDateExpiration" name="orderDateExpiration" value=""/>
                            <input type="hidden" id="orderCreditCustomer" name="orderCreditCustomer" value=0/>
                            <input type="hidden" id="orderDraft" name="orderDraft" value=0>
                            <input type="hidden" id="discCustomer" name="discCustomer" value=0>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-xl-5 mb-lg-0">
            <div class="card">
              <div class="card-body d-flex flex-column justify-content-between py-2">
                <div class="row align-items-center g-5 text-sm-start">
                  <div class="col-12 col-sm-auto flex-1">
                    <div class="form-control mb-3 p-0 border-0">
                      <label for="orderIdAddressShipping">Dirección de Despacho</label>
                      <select class="form-control select2" name="orderIdAddressShipping" id="orderIdAddressShipping">
                        <option selected="selected" value="">Seleccionar</option>
                      </select>
                    </div>
                    <div class="mb-0">
                      <div class="row">
                        <div class="col-12 col-md-6">
                          <div class="form-control mb-0 p-0 border-0">
                            <!-- <label for="orderAddressShipping">Dirección</label> -->
                            <div class="row">
                              <div class="col-auto col-md-2 col-xl-1 me-xl-3 pt-1 pe-2">
                                <span class="fas fa-map-marker-alt"></span>
                              </div>
                              <div class="col-auto col-md-10 col-xl-10 px-0">
                                <input class="form-control-plaintext py-0 text-700 fw-bold" id="orderAddressShipping" name="orderAddressShipping" type="text" placeholder="Dirección" readonly/>
                              </div>
                            </div>
                            <!-- <input class="form-control-plaintext outline-none py-0 text-700 fw-bold" id="orderAddressShipping" name="orderAddressShipping" type="text" placeholder="" readonly/> -->
                            <div class="row">
                              <div class="col-auto col-md-2 col-xl-1 me-xl-3 pt-1 pe-2">
                                <span class="fas fa-city"></span>
                              </div>
                              <div class="col-auto col-md-10 col-xl-10 px-0">
                                <input class="form-control-plaintext outline-none py-0 text-700 fw-bold" id="orderCityShipping" type="text" placeholder="Ciudad" readonly/>
                              </div>
                            </div>
                            <input class="form-control-plaintext outline-none py-0 text-700 fw-bold" id="space" type="text" placeholder="" readonly/>
                          </div>
                        </div>
                        <div class="col-12 col-md-6">
                          <div class="form-control mb-0 p-0 border-0">
                            <!-- <label for="orderEmailShipping">Correo electrónico</label> -->
                            <div class="row">
                              <div class="col-auto col-md-2 col-xl-1 me-xl-3 pt-1 pe-2">
                                <span class="fas fa-envelope"></span>
                              </div>
                              <div class="col-auto col-md-10 col-xl-10 ps-0">
                                <input class="form-control-plaintext py-0 text-700 fw-bold" id="orderEmailShipping" name="orderEmailShipping" type="email" placeholder="Correo electrónico" readonly/>
                              </div>
                            </div>
                            <!-- <input class="form-control-plaintext outline-none py-0 text-700 fw-bold" id="orderEmailShipping" type="email" placeholder="Correo electrónico" readonly/> -->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-4 co00l-xl-2 mb-lg-0">
            <div class="card">
              <div class="card-body d-flex flex-column justify-content-between py-2">
                <p class="mb-0">
                  <a class="btn btn-phoenix-secondary py-1 px-2 mb-1 mt-0 me-1 " data-bs-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="true" aria-controls="multiCollapseExample1">Condiciones Comerciales</a>
                </p>
                <div class="row">
                  <div class="col-auto flex-1 px-2">
                    <div class="collapse multi-collapse mb-3 mb-sm-0 show" id="multiCollapseExample1">
                      <div class="form-control mb-0 p-0 border-0">
                        <!-- <label for="orderAccountCustomer">Condiciones Comerciales</label> -->
                        <input class="form-control-plaintext outline-none py-0 text-700 fw-bold" id="orderAccountCustomer" type="text" placeholder="Clase Cuenta" readonly/>
                        <input class="form-control-plaintext outline-none py-0 text-700 fw-bold" id="orderCupCreCustomer" type="text" placeholder="Cupo Crédito" readonly/>
                        <input class="form-control-plaintext outline-none py-0 text-700 fw-bold" id="orderDescriptionList" type="text" placeholder="Lista Precio" readonly/>
                        <input class="form-control-plaintext outline-none py-0 text-700 fw-bold" id="orderVencinCustomer" type="text" placeholder="Vencimiento" readonly/>
                        <input class="form-control-plaintext outline-none py-0 text-700 fw-bold" id="orderDiscountCustomer" type="text" placeholder="Descuento Máximo" readonly/>
                        <!-- <input class="form-control datetimepicker py-1" type="text" id="orderDateDelivery" name="orderDateDelivery" placeholder="yyyy-mm-dd" data-options='{"disableMobile":true}' value=<?php //echo $dateDelivery;?>/> -->
                        <input type="hidden" id="orderDateDelivery" name="orderDateDelivery" value=<?php echo $dateDelivery;?>/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row g-2 mt-0">
          <div class="col-12">

            <div class="card mb-1">
              <div class="card-body p-3 py-2">
                <!-- <div id="orderTable" data-list='{"valueNames":["products","price","quantity","total"],"page":10}'> -->
                <div id="orderTable">
                  <div class="table-responsive scrollbar" style="min-height:160px; max-height: 278px; overflow-y: auto;">
                    <table class="table table-sm table-hover fs--1 mb-0 border-top border-200" style="position: relative; border-collapse: collapse; width: 99%;">
                      <thead>
                        <tr>
                          <th class="white-space-nowrap align-middle ps-0" scope="col" style="width:10%; white-space: nowrap; position: sticky; top:-1px; background: #f5f5f5;" data-sort="prodcode">CÓDIGO</th>
                          <th class="align-middle ps-0" scope="col" style="width: 40%; min-width:250px; white-space: nowrap; position: sticky; top:-1px; background: #f5f5f5;" data-sort="products">DESCRIPCIÓN</th>
                          <th class="align-middle text-end ps-4" scope="col" data-sort="stock" style="width: 9%; white-space: nowrap; position: sticky; top:-1px; background: #f5f5f5;">EXISTENCIA</th>
                          <th class="align-middle text-end ps-4" scope="col" data-sort="price" style="width: 12%; white-space: nowrap; position: sticky; top:-1px; background: #f5f5f5;">VALOR</th>
                          <th class="align-middle text-end ps-4" scope="col" data-sort="discount" style="width:8%; white-space: nowrap; position: sticky; top:-1px; background: #f5f5f5;">%DESCTO</th>
                          <th class="align-middle text-end ps-4" scope="col" data-sort="quantity" style="width: 11%; white-space: nowrap; position: sticky; top:-1px; background: #f5f5f5;">CANTIDAD</th>
                          <th class="align-middle text-end ps-4" scope="col" data-sort="total" style="width:13%; white-space: nowrap; position: sticky; top:-1px; background: #f5f5f5;">TOTAL</th>
                        </tr>
                      </thead>
                      <!-- <tbody class="list" id="orderaddTable-body"> -->
                      <tbody id="orderaddTable-body">
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="d-flex flex-between-center py-2 border-top mb-0">
                  <p class="text-1100 fw-semi-bold lh-sm mb-0">Subtotal:</p>
                  <p class="text-1100 fw-bold lh-sm mb-0 pe-3 totOrderSubtotal">0.00</p>
                </div>
              </div>
            </div>

          </div>
        </div>
        <div class="row g-2 mt-0">
          <div class="col-12 col-lg-9">
            <!--
            <div class="card mb-2">
              <div class="card-body p-3 py-2">
                <div id="orderTable">
                  <div class="table-responsive scrollbar" style="min-height:160px; max-height: 278px; overflow-y: auto;">
                    <table class="table table-sm table-hover fs--1 mb-0 border-top border-200" style="position: relative; border-collapse: collapse; width:auto;">
                      <thead>
                        <tr>
                          <th class="white-space-nowrap align-middle ps-0" scope="col" style="width:10%; white-space: nowrap; position: sticky; top:-1px; background: #f5f5f5;" data-sort="prodcode">CÓDIGO</th>
                          <th class="align-middle ps-0" scope="col" style="width: 40%; min-width:200px; white-space: nowrap; position: sticky; top:-1px; background: #f5f5f5;" data-sort="products">DESCRIPCIÓN</th>
                          <th class="align-middle text-end ps-4" scope="col" data-sort="stock" style="width: 9%; white-space: nowrap; position: sticky; top:-1px; background: #f5f5f5;">EXISTENCIA</th>
                          <th class="align-middle text-end ps-4" scope="col" data-sort="price" style="width: 12%; white-space: nowrap; position: sticky; top:-1px; background: #f5f5f5;">VALOR</th>
                          <th class="align-middle text-end ps-4" scope="col" data-sort="discount" style="width:8%; white-space: nowrap; position: sticky; top:-1px; background: #f5f5f5;">%DESCTO</th>
                          <th class="align-middle text-end ps-4" scope="col" data-sort="quantity" style="width: 12%; white-space: nowrap; position: sticky; top:-1px; background: #f5f5f5;">CANTIDAD</th>
                          <th class="align-middle text-end ps-4" scope="col" data-sort="total" style="width:12%; white-space: nowrap; position: sticky; top:-1px; background: #f5f5f5;">TOTAL</th>
                        </tr>
                      </thead>
                      <tbody id="orderaddTable-body">
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="d-flex flex-between-center py-2 border-top mb-0">
                  <p class="text-1100 fw-semi-bold lh-sm mb-0">Subtotal:</p>
                  <p class="text-1100 fw-bold lh-sm mb-0 totOrderSubtotal">0.00</p>
                </div>
              </div>
            </div>
            -->
            <div class="card mb-2">
              <div class="card-body px-3 py-2">
                <div class="row col-12 m-0">
                  <div class="col-12 col-md-9 p-2 p-md-0">
                    <div class="form-control-sm mb-0 p-0 pe-2">
                      <label for="idProductOrderNew">Artículo a pedir</label>
                      <select class="form-control select2" name="idProductOrderNew" id="idProductOrderNew">
                      <!-- <select class="form-select idProductOrderNew" id="idProductOrderNew" data-choices="data-choices" data-options='{"removeItemButton":true,"placeholder":true}'> -->
                        <option selected="selected" value="">Seleccionar</option>
                        <?php
                          $item = null;
                          $value = null;
                          $order = "description_product";
                          $where = "";
                          $limit = "";
                          // $product= ProductsController::getProducts($item, $value, $order, $where, $limit);
                          // foreach ($product as $key => $value) {
                          //   echo '<option value='.$value["id_product"].' code_product='.$value["code_product"].' >'.$value["code_product"]." - ".$value["description_product"].'</option>';
                          // }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-12 col-md-3 pt-1 pt-md-3">
                    <button class="btn btn-phoenix-primary" type="button" id="btnAddProductOrder"><span class="fa-solid fa-check me-1 fs--2"></span>Confirmar</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-body px-3 py-2">
                <label for="orderAddComments">Observaciones</label>
                <textarea class="form-control" id="orderAddComments" name="orderAddComments" rows="2"  ></textarea>
              </div>
            </div>
            <div class="d-grid gap-2">
              <button class="btn btn-primary" type="button" id="btnOrderAdd">GRABAR PEDIDO</button>
              <div class="row g-2">
                <div class="col-6">
                  <button class="btn btn-phoenix-success w-100" type="button" id="btnOrderAddDraft">GRABAR BORRADOR</button>
                </div>
                <div class="col-6">
                  <button class="btn btn-phoenix-primary w-100" type="button" id="btnOrderAddExcel">IMPORTAR DE EXCEL</button>
                  <input class="form-control" type="file" name="fileOrderExcel" id="fileOrderExcel" accept=".xls,.xlsx" required>
                </div>
              </div>
            </div>

          </div>
          <div class="col-12 col-lg-3">
            <!-- <div class="row"> -->
            <div class="row fs-lg--1 fs-xl-0">
              <div class="col-12">
                <div class="card mb-3">
                  <div class="card-body px-3 py-2">
                    <h4 class="card-title mb-2">Resumen</h4>
                    <div>
                      <div class="d-flex justify-content-between">
                        <p class="text-900 fw-semi-bold mb-0">Valor Bruto:</p>
                        <p class="text-1100 fw-semi-bold totOrderGross mb-0">0.00</p>
                        <input type="hidden" id="totOrderGross" name="totOrderGross"/>
                        <input type="hidden" id="totOrderCosts" name="totOrderCosts"/>
                      </div>
                      <div class="d-flex justify-content-between">
                        <p class="text-900 fw-semi-bold mb-0">Descuento:</p>
                        <p class="text-danger fw-semi-bold totOrderDiscount mb-0">0.00</p>
                        <input type="hidden" id="totOrderDiscount" name="totOrderDiscount"/>
                      </div>
                      <div class="d-flex justify-content-between">
                        <p class="text-900 fw-semi-bold mb-0">IVA:</p>
                        <p class="text-1100 fw-semi-bold totOrderTax mb-0">$0.00</p>
                        <input type="hidden" id="totOrderTax" name="totOrderTax"/>
                      </div>
                      <div class="d-flex justify-content-between">
                        <p class="text-900 fw-semi-bold mb-0">Subtotal:</p>
                        <p class="text-1100 fw-semi-bold totOrderSubtotal mb-0">$0.00</p>
                      </div>
                      <div class="d-flex justify-content-between">
                        <p class="text-900 fw-semi-bold mb-0">Costo de envío:</p>
                        <p class="text-1100 fw-semi-bold totOrderShipping mb-0">$0.00</p>
                        <input type="hidden" id="totOrderShipping" name="totOrderShipping"/>
                      </div>
                    </div>
                    <div class="d-flex justify-content-between border-top border-dashed pt-1 fs-md-0 fs-xl-1">
                      <p class="mb-0 fw-semi-bold">Total :</p>
                      <p class="mb-0 fw-semi-bold totOrderTotal">$0.00</p>
                      <input type="hidden" id="totOrderTotal" name="totOrderTotal"/>
                    </div>
                    <!-- <div class="d-grid gap-2">
                      <button class="btn btn-success" type="button" id="btnOrderAddDraft">GRABAR BORRADOR</button>
                    </div> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <input type="hidden" id="orderProductsList" name="orderProductsList">
    <input type="hidden" id="taxPercent" value="">
    <input type="hidden" id="shippPercent" value="">
  </form>
  <?php
    // $crearCliente = new OrdersController();
    $crearCliente->orderAdd();
  ?>
  <?php
    include "footer.php";
  ?>
</div>
<!-- <script src="views/js/orders.js?v=1.0.4"></script> -->