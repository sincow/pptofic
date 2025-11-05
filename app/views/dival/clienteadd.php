<style>
   .modal-dialog {
      max-height: 98vh !important;
   }

   .modal-content {
      max-height: 95vh;
      display: flex;
      flex-direction: column;
   }

   .modal-body {
      overflow-y: auto;
      max-height: calc(90vh - 120px); /* Restar altura del header y footer */
      flex: 1;
   }
</style>
<div id="modalClienteAdd" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fw-bolder" id="modalClienteAddTit">Adicionar Cliente</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" ara-label="Close"></button>
			</div>
			<form class="needs-validation" role="form" id="formClienteAdd" method="post" novalidate>
            <input type="hidden" id="modulo" name="modulo" value="dival"/>
            <input type="hidden" id="option" name="option" value="clientes"/>
            <input type="hidden" id="action" name="action" value="create"/>
				<div class="modal-body h-100 p-0">
               <div class="card mb-0"> 
                  <!--min-height:452px; max-height: 560px; -->
                  <!-- <div class="table-responsive scrollbar card-client" style="overflow-y: auto;"> -->

                  <div class="card-body p-4">
                     <div class="row g-3">
                        <div class="col-sm-8 col-lg-6">
                           <div class="form-group">
                              <label for="newTerTiDoc" class="text-label fs-0 ps-1">Tipo Documento *</label>
                              <select class="form-control" style="width: 100%;" id="newTerTiDoc" name="TerTiDoc" required>
                                 <option value="">Seleccionar</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-sm-4 col-lg-3">
                           <div class="form-group">
                              <label for="newTerDocId" class="text-label fs-0 ps-1">Doc Identidad *</label>
                              <input type="text" class="form-control" id="newTerDocId" name="TerDocId" required>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="newTerRaSoc" class="text-label fs-0 ps-1">Razón Social *</label>
                              <input type="text" class="form-control" id="newTerRaSoc" name="TerRaSoc" maxlength="200" value="" required>
                           </div>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                           <div class="form-group">
                              <label for="newTerNomb1" class="text-label fs-0 ps-1">Primer Nombre *</label>
                              <input type="text" class="form-control" id="newTerNomb1" name="TerNomb1" maxlength="80" value="" required>
                           </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                           <div class="form-group">
                              <label for="newTerNomb2" class="text-label fs-0 ps-1">Otros Nombres </label>
                              <input type="text" class="form-control" id="newTerNomb2" name="TerNomb2" maxlength="80" value="" required>
                           </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                           <div class="form-group">
                              <label for="newTerApel1" class="text-label fs-0 ps-1">Primer Apellido *</label>
                              <input type="text" class="form-control" id="newTerApel1" name="TerApel1" maxlength="80" value="" required>
                           </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                           <div class="form-group">
                              <label for="newTerApel2" class="text-label fs-0 ps-1">Segundo Apellido </label>
                              <input type="text" class="form-control" id="newTerApel2" name="TerApel2" maxlength="80" value="" required>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="newCiuCodig" class="text-label fs-0 ps-1">Ciudad *</label>
                              <select class="form-control" style="width: 100%;" id="newCiuCodig" name="CiuCodig">
                                 <option value="">Seleccionar</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                           <div class="form-group">
                              <label for="newfecha_nacimiento" class="text-label fs-0 ps-1">Fecha Nacimiento *</label>
                              <input type="date" class="form-control" id="newfecha_nacimiento" name="fecha_nacimiento" required>
                           </div>
                        </div>
                        <div class="col-sm-6 col-lg-3 mt-sm-7">
                           <div class="form-check form-switch">
                              <!-- <label for="newBanCodig" class="text-label fs-0 ps-1">Código Interno *</label> -->
                              <input class="form-check-input" type="checkbox" id="newpep" name="pep" />
                              <label class="form-check-label" for="newpep">Persona PEP</label>
                           </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                           <div class="form-group">
                              <label for="newTerDirec" class="text-label fs-0 ps-1">Dirección Oficina *</label>
                              <input type="text" class="form-control" id="newTerDirec" name="TerDirec" maxlength="150" required>
                           </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                           <div class="form-group">
                              <label for="newdireccion_residencia" class="text-label fs-0 ps-1">Dirección Residencia *</label>
                              <input type="text" class="form-control" id="newdireccion_residencia" name="direccion_residencia" maxlength="100" required>
                           </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                           <div class="form-group">
                              <label for="neworigen_recursos" class="text-label fs-0 ps-1">Origen Recursos *</label>
                              <input type="text" class="form-control" id="neworigen_recursos" name="origen_recursos" maxlength="50" required>
                           </div>
                        </div>


                        <div class="col-sm-6 col-lg-2">
                           <div class="form-group">
                              <label for="newTerTele1" class="text-label fs-0 ps-1">Teléfono 1 *</label>
                              <input type="number" class="form-control" id="newTerTele1" name="TerTele1" maxlength="15" required>
                           </div>
                        </div>
                        <div class="col-sm-6 col-lg-2">
                           <div class="form-group">
                              <label for="newTerTele2" class="text-label fs-0 ps-1">Teleféfono 2 *</label>
                              <input type="number" class="form-control" id="newTerTele2" name="TerTele2" maxlength="15">
                           </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                           <label for="newtipo_telefono1" class="text-label fs-0 ps-1">Tipo Teléfono 1</label>
                           <div class="form-check">
                              <label class="form-check-label me-5" for="newtipo_telefono1">
                                 <input class="form-check-input" id="newtipo_telefono1" type="radio" name="tipo_telefono" value=1 checked="">
                                 Celular
                              </label>
                              <label class="form-check-label" for="newtipo_telefono2">
                                 <input class="form-check-input" id="newtipo_telefono2" type="radio" name="tipo_telefono" value=2>
                                 Fijo
                              </label>
                           </div>
                        </div>
                        <div class="col-sm-6 col-lg-5">
                           <div class="form-group">
                              <label for="newpersona_responde" class="text-label fs-0 ps-1">Quien Contesta Teléfono</label>
                              <input type="text" class="form-control" id="newpersona_responde" name="persona_responde" maxlength="100" required>
                           </div>
                        </div>
                        <div class="col-sm-12 col-lg-9">
                           <div class="row">
                              <div class="col-8 mb-2">
                                 <div class="form-group">
                                    <label for="newTerEmail" class="text-label fs-0 ps-1">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="newTerEmail" name="TerEmail" maxlength="150" required>
                                 </div>
                              </div>
                              <div class="col-sm-6 col-lg-4">
                                 <div class="form-group">
                                    <label for="newTerResAu" class="text-label fs-0 ps-1">Res AutoRetenedor</label>
                                    <input type="text" class="form-control" id="newTerResAu" name="TerResAu" maxlength="15" >
                                 </div>
                              </div>

                              <div class="col-sm-6 col-5">
                                 <div class="form-group">
                                    <label for="newTerFreAu" class="text-label fs-0 ps-1">Fecha Resolución</label>
                                    <input type="date" class="form-control" id="newTerFreAu" name="TerFreAu" >
                                 </div>
                              </div>

                              <div class="col-sm-6 col-lg-6">
                                 <label for="newTerRegim1" class="text-label fs-0 ps-1">Régimen</label>
                                 <div class="form-check">
                                    <label class="form-check-label me-5" for="newTerRegim1">
                                       <input class="form-check-input" id="newTerRegim1" type="radio" name="TerRegim" value=0>
                                       Común
                                    </label>
                                    <label class="form-check-label" for="newTerRegim2">
                                       <input class="form-check-input" id="newTerRegim2" type="radio" name="TerRegim" value=1 checked="" >
                                       Persona Natural
                                    </label>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                           <label for="newTerEmail" class="text-label fs-0 ps-1">Opciones</label>
                           <div class="form-check">
                              <input class="form-check-input" id="newTerGrCon" name="TerGrCon" type="checkbox" value="" >
                              <label class="form-check-label" for="newTerGrCon">Gran Contribuyente</label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" id="newTerAuRet" name="TerAuRet" type="checkbox" value="" >
                              <label class="form-check-label" for="newTerAuRet">Auto Retenedor</label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" id="newTerRetie" name="TerRetie" type="checkbox" value="" >
                              <label class="form-check-label" for="newTerRetie">Practica Retefuente</label>
                           </div>
                        </div>

                        <div class="col-sm-7 col-lg-4">
                           <div class="form-group">
                              <label for="newreferencia_comercial" class="text-label fs-0 ps-1">Referencia Comercial</label>
                              <input type="text" class="form-control" id="newreferencia_comercial" name="referencia_comercial" maxlength="100" >
                           </div>
                        </div>
                        <div class="col-sm-5 col-lg-2">
                           <div class="form-group">
                              <label for="newtelefono_refcomercial" class="text-label fs-0 ps-1">Teléfono</label>
                              <input type="text" class="form-control" id="newtelefono_refcomercial" name="telefono_refcomercial" maxlength="20" >
                           </div>
                        </div>

                        <div class="col-sm-7 col-lg-4">
                           <div class="form-group">
                              <label for="newreferencia_personal" class="text-label fs-0 ps-1">Referencia Personal</label>
                              <input type="text" class="form-control" id="newreferencia_personal" name="referencia_personal" maxlength="100" >
                           </div>
                        </div>
                        <div class="col-sm-5 col-lg-2">
                           <div class="form-group">
                              <label for="newtelefono_refpersonal" class="text-label fs-0 ps-1">Teléfono</label>
                              <input type="text" class="form-control" id="newtelefono_refpersonal" name="telefono_refpersonal" maxlength="20" >
                           </div>
                        </div>

                        <div class="col-sm-9 col-lg-6">
                           <div class="form-group">
                              <label for="newTerAcEco" class="text-label fs-0 ps-1">Actividad Económica *</label>
                              <select class="form-control" style="width: 100%;" id="newTerAcEco" name="TerAcEco" required>
                                 <option value="">Seleccionar</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-sm-4 col-lg-2">
                           <div class="form-group">
                              <label for="newvalor_cupo" class="text-label fs-0 ps-1">Cupo</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="newvalor_cupo" name="valor_cupo" maxlength="11" value="0">
                           </div>
                        </div>
                        <div class="col-sm-4 col-lg-2">
                           <div class="form-group">
                              <label for="newvalor_cupotemporal" class="text-label fs-0 ps-1">Cupo TMP</label>
                              <input type="text" class="form-control p-1" style="text-align: right;" id="newvalor_cupotemporal" name="valor_cupotemporal" maxlength="11" value="0">
                           </div>
                        </div>
                        <div class="col-sm-4 col-lg-2">
                           <div class="form-group">
                              <label for="newnivel_riezgo" class="text-label fs-0 ps-1">N.R.</label>
                              <input type="number" class="form-control" id="newnivel_riezgo" name="nivel_riezgo" max="9" maxlength="1">
                           </div>
                        </div>

                     </div>
                  </div>

               <!-- </div> -->
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-phoenix-primary" id="btnClienteAdd">Guardar</button>
               <button type="button" class="btn btn-phoenix-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
         </form>
      </div>
   </div>
</div>
<script>
   $(document).ready(function() {
      $('#newCiuCodig').select2({
         dropdownParent: $('#modalClienteAdd'),
         width: '100%'
      });
   });
</script>