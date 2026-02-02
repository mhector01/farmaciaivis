<?php

	$objVenta =  new Venta();
	$count_ventas = $objVenta->Count_Ventas('FECHAS','','');

	foreach ($count_ventas as $row => $column) {

		$ventas_anuladas = $column["ventas_anuladas"];
		$ventas_vigentes = $column["ventas_vigentes"];
		$ventas_contado = $column["ventas_contado"];
		$ventas_credito = $column["ventas_credito"];

	}

 ?>

			<!-- Labels -->
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-flat">
						<div class="breadcrumb-line">
							<ul class="breadcrumb">
								<li><a href="?View=Inicio"><i class="icon-home2 position-left"></i> Inicio</a></li>
								<li><a href="javascript:;">Ventas</a></li>
								<li class="active">Ventas por Fechas</li>
							</ul>
						</div>
						<div class="panel-heading">
							<h6 class="panel-title">Ventas por Fechas</h6>

							<div class="heading-elements">
									<form class="heading-form" action="#">
										<div class="form-group">
											<div class="checkbox checkbox-switchery switchery-sm">
												<label>
												<input type="checkbox" id="chkEstado" name="chkEstado"
												 class="switchery" checked="checked" >
												 <span id="lblchk">REPORTES DETALLADOS</span>
												 </label>
											</div>
										</div>
									</form>
								</div>



							 <div class="row">
								 <div class="col-sm-6 col-md-5">
								 	<form role="form" autocomplete="off" class="form-validate-jquery" id="frmSearch">
										<div class="form-group">
											<div class="row">
												<div class="col-sm-5">
													<div class="input-group">
													<span class="input-group-addon"><i class="icon-calendar3"></i></span>
													<input type="text" id="txtF1" name="txtF1" placeholder=""
													 class="form-control input-sm" style="text-transform:uppercase;"
							                		onkeyup="javascript:this.value=this.value.toUpperCase();">
							                		</div>
												</div>
												<div class="col-sm-5">
													<div class="input-group">
													<span class="input-group-addon"><i class="icon-calendar3"></i></span>
													<input type="text" id="txtF2" name="txtF2" placeholder=""
													 class="form-control input-sm" style="text-transform:uppercase;"
							                		onkeyup="javascript:this.value=this.value.toUpperCase();">
							                		</div>
												</div>
												<div class="col-sm-2">
													<button style="margin-top: 0px;" id="btnGuardar"
													type="submit" class="btn btn-primary btn-sm">
													<i class="icon-search4"></i> Consultar</button>
												</div>
											</div>
										</div>
									  </form>
							   	  </div>
							  </div>

						</div>

					<div id="reload-div">
						<div class="panel-body">
							<div class="tabbable">
								<ul class="nav nav-tabs nav-tabs-highlight">
									<li class="active"><a href="#label-tab1" data-toggle="tab">VIGENTES <span id="span-ing" class="label
									label-success position-right"><?php echo $ventas_vigentes ?></span></a></li>
									<li><a href="#label-tab2" data-toggle="tab">ANULADAS <span id="span-dev" class="label bg-danger
									position-right"><?php echo $ventas_anuladas ?></span></a></li>
									<li><a href="#label-tab3" data-toggle="tab">VENTAS AL CONTADO <span id="span-pre" class="label bg-warning
									position-right"><?php echo $ventas_contado ?></span></a></li>
									<li><a href="#label-tab4" data-toggle="tab">VENTAS AL CREDITO <span id="span-gas" class="label bg-info
									position-right"><?php echo $ventas_credito ?></span></a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane active" id="label-tab1">
										<!-- Basic initialization -->
										<div class="panel panel-flat">
											<div class="panel-heading">
												<h5 class="panel-title">Ventas Vigentes</h5>
												<div class="heading-elements">
													<button type="button" id="print_vigentes"
													class="btn bg-danger-400 heading-btn" id="btnPrint" value="vigentes">
													<i class="icon-printer2"></i> Imprimir Reporte</button>
												</div>
											</div>
												<div class="panel-body">
													<table class="table datatable-basic table-xs table-hover">
														<thead>
															<tr>
																<th>No. Venta</th>
																<th>Comprobante</th>
																<th>No.Comprobante</th>
																<th>Fecha y Hora Venta</th>
																<th>Tipo Pago</th>
																<th>Total</th>
																<th>Estado</th>
																<th>Opciones</th>
															</tr>
														</thead>

														<tbody>

														  <?php
																$filas = $objVenta->Listar_Ventas('FECHAS','','',1);
																if (is_array($filas) || is_object($filas))
																{
																foreach ($filas as $row => $column)
																{

																$fecha_venta = $column["fecha_venta"];
																if(is_null($fecha_venta))
																{
																	$c_fecha_venta = '';

																} else {

																	$c_fecha_venta = DateTime::createFromFormat('Y-m-d H:i:s',$fecha_venta)->format('d/m/Y H:i:s');
																}

																$tipo_comprobante = $column["tipo_comprobante"];
																if($tipo_comprobante == '1')
																{
																	$tipo_comprobante = 'TICKET';

																} else if ($tipo_comprobante == '2'){

																	$tipo_comprobante = 'FACTURA';

																} else if ($tipo_comprobante == '3'){

																	$tipo_comprobante = 'BOLETA';
																}


																$tipo_pago = $column["tipo_pago"];
																if($tipo_pago == '1')
																{
																	$tipo_pago = 'CONTADO';

																} else if ($tipo_pago == '2'){

																	$tipo_pago = 'CREDITO';

																}

																?>
																	<tr>
																		<td><?php print($column['numero_venta']); ?></td>
																		<td><?php print($tipo_comprobante); ?></td>
													                	<td><?php print($column['numero_comprobante']); ?></td>
													                	<td><?php print($c_fecha_venta); ?></td>
													                	<td><?php print($tipo_pago); ?></td>
													                	<td><?php print($column['total']); ?></td>
													                	<td><?php if($column['estado_venta'] == '1')
													                		echo '<span class="label label-success label-rounded"><span
													                		class="text-bold">VIGENTE</span></span>';
													                		else
													                		echo '<span class="label label-default label-rounded">
													                	<span
													                	    class="text-bold">ANULADA</span></span>'
														                ?></td>
																		<td class="text-center">
																			<ul class="icons-list">
																				<li class="dropdown">
																					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
																						<i class="icon-menu9"></i>
																					</a>
																					<ul class="dropdown-menu dropdown-menu-right">
																					    <li>
                                        <a href="javascript:void(0)" onclick="editarVenta('<?php print($column['idventa']); ?>')">
                                            <i class="icon-pencil7"></i> Editar Venta
                                        </a>
                                    </li>
																					   <li><a id="delete_product"
																					   data-id="<?php print($column['idventa']); ?>"
																						href="javascript:void(0)">
																					   <i class="icon-cancel-circle2">
																				       </i> Anular</a></li>

																					   <li><a id="detail_pay"  data-toggle="modal" data-target="#modal_detalle"
																					   data-id="<?php print($column['idventa']); ?>"
																						href="javascript:void(0)">
																					   <i class="icon-file-spreadsheet">
																				       </i> Ver Detalle</a></li>

																				       <li><a id="print_receip"
																					   data-id="<?php print($column['idventa']); ?>"
																						href="javascript:void(0)">
																					   <i class="icon-typewriter">
																				       </i> Comprobante</a></li>

																					</ul>
																				</li>
																			</ul>
																		</td>
													                </tr>
																<?php
																		}
																	}
																?>
															</tbody>
													</table>
												</div>
											</div>
									</div>

									<div class="tab-pane" id="label-tab2">
										<!-- Basic initialization -->
										<div class="panel panel-flat">
											<div class="panel-heading">
												<h5 class="panel-title">Ventas Anuladas</h5>
												<div class="heading-elements">
													<button type="button" id="print_anuladas"
													class="btn bg-danger-400 heading-btn" id="btnPrint" value="anuladas">
													<i class="icon-printer2"></i> Imprimir Reporte</button>
												</div>
											</div>
												<div class="panel-body">
													<table class="table datatable-basic table-xs table-hover">
														<thead>
															<tr>
																<th>No.Venta</th>
																<th>Comprobante</th>
																<th>No.Comprobante</th>
																<th>Fecha y Hora Venta</th>
																<th>Tipo Pago</th>
																<th>Total</th>
																<th>Estado</th>
																<th>Opciones</th>
															</tr>
														</thead>

														<tbody>

														  <?php
																$filas = $objVenta->Listar_Ventas('FECHAS','','',0);
																if (is_array($filas) || is_object($filas))
																{
																foreach ($filas as $row => $column)
																{

																$fecha_venta = $column["fecha_venta"];
																if(is_null($fecha_venta))
																{
																	$c_fecha_venta = '';

																} else {

																	$c_fecha_venta = DateTime::createFromFormat('Y-m-d H:i:s',$fecha_venta)->format('d/m/Y H:i:s');
																}

																$tipo_comprobante = $column["tipo_comprobante"];
																if($tipo_comprobante == '1')
																{
																	$tipo_comprobante = 'TICKET';

																} else if ($tipo_comprobante == '2'){

																	$tipo_comprobante = 'FACTURA';

																} else if ($tipo_comprobante == '3'){

																	$tipo_comprobante = 'BOLETA';
																}


																$tipo_pago = $column["tipo_pago"];
																if($tipo_pago == '1')
																{
																	$tipo_pago = 'CONTADO';

																} else if ($tipo_pago == '2'){

																	$tipo_pago = 'CREDITO';

																}

																?>
																	<tr>
																		<td><?php print($column['numero_venta']); ?></td>
																		<td><?php print($tipo_comprobante); ?></td>
													                	<td><?php print($column['numero_comprobante']); ?></td>
													                	<td><?php print($c_fecha_venta); ?></td>
													                	<td><?php print($tipo_pago); ?></td>
													                	<td><?php print($column['total']); ?></td>
													                	<td><?php if($column['estado_venta'] == '1')
													                		echo '<span class="label label-success label-rounded"><span
													                		class="text-bold">VIGENTE</span></span>';
													                		else
													                		echo '<span class="label label-default label-rounded">
													                	<span
													                	    class="text-bold">ANULADA</span></span>'
														                ?></td>
														                <td class="text-center">
																			<ul class="icons-list">
																				<li class="dropdown">
																					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
																						<i class="icon-menu9"></i>
																					</a>
																					<ul class="dropdown-menu dropdown-menu-right">
																					    

																					   <li><a id="detail_pay"  data-toggle="modal" data-target="#modal_detalle"
																					   data-id="<?php print($column['idventa']); ?>"
																						href="javascript:void(0)">
																					   <i class="icon-file-spreadsheet">
																				       </i> Ver Detalle</a></li>

																					</ul>
																				</li>
																			</ul>
																		</td>
													                </tr>
																<?php
																		}
																	}
																?>
															</tbody>
													</table>
												</div>
											</div>
									</div>


									<div class="tab-pane" id="label-tab3">
										<!-- Basic initialization -->
										<div class="panel panel-flat">
											<div class="panel-heading">
												<h5 class="panel-title">Ventas al Contado</h5>
												<div class="heading-elements">
													<button type="button" id="print_contado"
													class="btn bg-danger-400 heading-btn" id="btnPrint" value="contado">
													<i class="icon-printer2"></i> Imprimir Reporte</button>
												</div>
											</div>
												<div class="panel-body">
													<table class="table datatable-basic table-xs table-hover">
														<thead>
															<tr>
																<th>No. Venta</th>
																<th>Comprobante</th>
																<th>No.Comprobante</th>
																<th>Fecha y Hora Venta</th>
																<th>Tipo Pago</th>
																<th>Total</th>
																<th>Estado</th>
																<th>Opciones</th>
															</tr>
														</thead>

														<tbody>

														  <?php
																$filas = $objVenta->Listar_Ventas('FECHAS','','',1);
																if (is_array($filas) || is_object($filas))
																{
																foreach ($filas as $row => $column)
																{

																$fecha_venta = $column["fecha_venta"];
																if(is_null($fecha_venta))
																{
																	$c_fecha_venta = '';

																} else {

																	$c_fecha_venta = DateTime::createFromFormat('Y-m-d H:i:s',$fecha_venta)->format('d/m/Y H:i:s');
																}

																$tipo_comprobante = $column["tipo_comprobante"];
																if($tipo_comprobante == '1')
																{
																	$tipo_comprobante = 'TICKET';

																} else if ($tipo_comprobante == '2'){

																	$tipo_comprobante = 'FACTURA';

																} else if ($tipo_comprobante == '3'){

																	$tipo_comprobante = 'BOLETA';
																}


																$tipo_pago = $column["tipo_pago"];
																if($tipo_pago == '1')
																{
																	$tipo_pago = 'CONTADO';

																} else if ($tipo_pago == '2'){

																	$tipo_pago = 'CREDITO';

																}

																?>
																	<tr>
																		<td><?php print($column['numero_venta']); ?></td>
																		<td><?php print($tipo_comprobante); ?></td>
													                	<td><?php print($column['numero_comprobante']); ?></td>
													                	<td><?php print($c_fecha_venta); ?></td>
													                	<td><?php print($tipo_pago); ?></td>
													                	<td><?php print($column['total']); ?></td>
													                	<td><?php if($column['estado_venta'] == '1')
													                		echo '<span class="label label-success label-rounded"><span
													                		class="text-bold">VIGENTE</span></span>';
													                		else
													                		echo '<span class="label label-default label-rounded">
													                	<span
													                	    class="text-bold">ANULADA</span></span>'
														                ?></td>
														                <td class="text-center">
																			<ul class="icons-list">
																				<li class="dropdown">
																					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
																						<i class="icon-menu9"></i>
																					</a>
																					<ul class="dropdown-menu dropdown-menu-right">
																					    
																					    

																					   <li><a id="detail_pay"  data-toggle="modal" data-target="#modal_detalle"
																					   data-id="<?php print($column['idventa']); ?>"
																						href="javascript:void(0)">
																					   <i class="icon-file-spreadsheet">
																				       </i> Ver Detalle</a></li>

																				       <li><a id="print_receip"
																					   data-id="<?php print($column['idventa']); ?>"
																						href="javascript:void(0)">
																					   <i class="icon-typewriter">
																				       </i> Comprobante</a></li>

																					</ul>
																				</li>
																			</ul>
																		</td>
													                </tr>
																<?php
																		}
																	}
																?>
															</tbody>
													</table>
												</div>
											</div>
									</div>

									<div class="tab-pane" id="label-tab4">
										<!-- Basic initialization -->
										<div class="panel panel-flat">
											<div class="panel-heading">
												<h5 class="panel-title">Ventas al Credito</h5>
												<div class="heading-elements">
													<button type="button"  id="print_credito"
													class="btn bg-danger-400 heading-btn" id="btnPrint" value="credito">
													<i class="icon-printer2"></i> Imprimir Reporte</button>
												</div>
											</div>
												<div class="panel-body">
													<table class="table datatable-basic table-xs table-hover">
														<thead>
															<tr>
																<th>No. Venta</th>
																<th>Comprobante</th>
																<th>No.Comprobante</th>
																<th>Fecha y Hora Venta</th>
																<th>Tipo Pago</th>
																<th>Total</th>
																<th>Opciones</th>
															</tr>
														</thead>

														<tbody>

														  <?php
																$filas = $objVenta->Listar_Ventas('FECHAS','','',2);
																if (is_array($filas) || is_object($filas))
																{
																foreach ($filas as $row => $column)
																{

																$fecha_venta = $column["fecha_venta"];
																if(is_null($fecha_venta))
																{
																	$c_fecha_venta = '';

																} else {

																	$c_fecha_venta = DateTime::createFromFormat('Y-m-d H:i:s',$fecha_venta)->format('d/m/Y H:i:s');
																}

																$tipo_comprobante = $column["tipo_comprobante"];
																if($tipo_comprobante == '1')
																{
																	$tipo_comprobante = 'TICKET';

																} else if ($tipo_comprobante == '2'){

																	$tipo_comprobante = 'FACTURA';

																} else if ($tipo_comprobante == '3'){

																	$tipo_comprobante = 'BOLETA';
																}


																$tipo_pago = $column["tipo_pago"];
																if($tipo_pago == '1')
																{
																	$tipo_pago = 'CONTADO';

																} else if ($tipo_pago == '2'){

																	$tipo_pago = 'CREDITO';

																}

																?>
																	<tr>
																		<td><?php print($column['numero_venta']); ?></td>
																		<td><?php print($tipo_comprobante); ?></td>
													                	<td><?php print($column['numero_comprobante']); ?></td>
													                	<td><?php print($c_fecha_venta); ?></td>
													                	<td><?php print($tipo_pago); ?></td>
													                	<td><?php print($column['total']); ?></td>
														                <td class="text-center">
																			<ul class="icons-list">
																				<li class="dropdown">
																					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
																						<i class="icon-menu9"></i>
																					</a>
																					<ul class="dropdown-menu dropdown-menu-right">

																				       <li><a id="delete_product"
																					   data-id="<?php print($column['idventa']); ?>"
																						href="javascript:void(0)">
																					   <i class="icon-cancel-circle2">
																				       </i> Anular</a></li>


																					   <li><a id="detail_pay"  data-toggle="modal"
																					   data-target="#modal_detalle"
																					   data-id="<?php print($column['idventa']); ?>"
																						href="javascript:void(0)">
																					   <i class="icon-file-spreadsheet">
																				       </i> Ver Detalle</a></li>

																				       <li><a id="print_receip"
																					   data-id="<?php print($column['idventa']); ?>"
																						href="javascript:void(0)">
																					   <i class="icon-typewriter">
																				       </i> Comprobante</a></li>

																					</ul>
																				</li>
																			</ul>
																		</td>
													                </tr>
																<?php
																		}
																	}
																?>
															</tbody>
													</table>
												</div>
											</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					</div>
				</div>
			</div>
			<!-- /labels -->


<!-- Iconified modal -->
	<div id="modal_detalle" class="modal fade">
		<div class="modal-dialog modal-full">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title"></i> &nbsp; <span class="title-form text-uppercase">Detalle de Venta</span></h5>
				</div>

		        <form role="form" autocomplete="off" class="form-validate-jquery" id="frmModal">
					<div class="modal-body" id="modal-container">

					<div id="reload-detalle">
							<!-- Collapsible with right control button -->
							<div class="panel-group panel-group-control panel-group-control-right content-group-lg">
								<div class="panel">
									<div class="panel-heading bg-info">
										<h6 class="panel-title">
											<a class="collapsed" data-toggle="collapse" href="#collapsible-control-right-group2">Clic para ver Informaci√≥n de la Venta</a>
										</h6>
									</div>
									<div id="collapsible-control-right-group2" class="panel-collapse collapse">
										<div class="panel-body">
											<div class="table-responsive">
												<table class="table table-xxs table-bordered">
												 <tbody class="border-solid">
												 <tr>
												 	<td width="5%" class="text-bold text-left">NO. VENTA</td>
													<td width="35%"></td>
													<td width="2%" class="text-bold text-left">FORMA PAGO</td>
													<td width="30%"></td>
												 </tr>
												<tr>
													<td width="5%" class="text-bold text-left">CLIENTE</td>
													<td width="30%"></td>
													<td width="2%" class="text-bold text-left">FECHA VENTA</td>
													<td width="30%"></td>
												</tr>
												<tr>
													<td width="20%" class="text-bold text-left">NO. COMPROBANTE</td>
													<td width="5%"></td>
													<td width="10%" class="text-bold text-left"></td>
													<td width="5%"></td>
												</tr>
												</tbody>
											</table>
										 </div>
										</div>
									</div>
								</div>
							</div>
							<!-- /collapsible with right control button -->

							<div class="panel-group panel-group-control panel-group-control-right content-group-lg">
								<div class="table-responsive">
									<table id="tbldetalle" class="table table-borderless table-striped table-xxs">
										<thead>
											<tr class="bg-blue">
												<th>Producto</th>
												<th>Cant.</th>
												<th>Precio</th>
												<th>Tot. SIN IGV</th>
												<th>Descuento</th>
												<th>Importe</th>
												<th>Vence</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
										<tfoot>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td width="10%">SUMAS</td>
												<td id="sumas"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td width="10%">IGV %</td>
												<td id="iva"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td width="10%">SUBTOTAL</td>
												<td id="subtotal"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td width="10%">RET. (-)</td>
												<td id="ivaretenido"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td width="10%">TOT. SIN IGV</td>
												<td id="exentas"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td width="10%">DESCUENTO</td>
												<td id="descuentos"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td width="10%">TOTAL</td>
												<td id="total"></td>
												<td></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>

						</div>

					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- /iconified modal -->
	
	
	<div id="modal_iconified_cash" class="modal fade">
    <div class="modal-dialog modal-lg"> <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="icon-pencil7"></i> &nbsp; <span class="title-form">Editar Venta Existente</span></h5>
            </div>

            <form role="form" autocomplete="off" class="form-validate-jquery" id="frmPago">
                <div class="modal-body">
                    
                    <input type="hidden" id="txtIdVentaEditar" name="txtIdVentaEditar">
                    <input type="hidden" id="action_mode" name="action_mode" value="editar">
                    
                    <input type="hidden" id="txtSumas" name="sumas">
                    <input type="hidden" id="txtIva" name="iva">
                    <input type="hidden" id="txtExento" name="exento">
                    <input type="hidden" id="txtRetenido" name="retenido">
                    <input type="hidden" id="txtDescuento" name="descuento">
                    <input type="hidden" id="txtTotal" name="total">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Cliente:</label>
                                <select id="cbCliente" name="cbCliente" class="form-control select-size-xs" style="width: 100%;">
                                    <?php
                                        $filas = VentaModel::Listar_Clientes();
                                        if (is_array($filas)) {
                                            foreach ($filas as $row) {
                                                echo '<option value="'.$row["idcliente"].'">'.$row["nombre_cliente"].'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Comprobante:</label>
                                <select id="cbCompro" name="cbCompro" class="form-control select-size-xs">
                                    <option value="1">FACTURA</option>
                                    <option value="2">TICKET</option>
                                    <option value="3">BOLETA</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Fecha Venta (Original):</label>
                                <input type="text" id="txtFechaVenta" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <hr>
                    
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-search4"></i></span>
                            <input type="text" id="txtBuscarProducto" class="form-control input-lg" 
                                   placeholder="Escriba aqu®™ para buscar y agregar un producto..." 
                                   style="text-transform:uppercase; font-weight:bold; background-color:#fcfcfc;">
                        </div>
                    </div>

                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table id="tbldetalle" class="table table-xxs table-bordered table-hover">
                            <thead class="bg-teal">
                                <tr>
                                    <th class="text-center" width="5%"><i class="icon-trash"></i></th>
                                    <th class="text-center" width="35%">Producto</th>
                                    <th class="text-center" width="10%">Cant.</th>
                                    <th class="text-center" width="10%">Precio</th>
                                    <th class="text-center" width="10%">Exento</th>
                                    <th class="text-center" width="10%">Desc.</th>
                                    <th class="text-center" width="10%">Importe</th>
                                    <th class="text-center" width="10%">Vence</th>
                                </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>M®¶todo de Pago:</label>
                                <select id="cbMPago" name="cbMPago" class="form-control">
                                    <option value="1">EFECTIVO</option>
                                    <option value="2">TARJETA</option>
                                    <option value="3">AMBOS</option>
                                    <option value="4">DEPOSITO</option>
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-xs-6">
                                    <label>Efectivo:</label>
                                    <input type="text" id="txtMonto" name="txtMonto" class="form-control" value="0.00">
                                </div>
                                <div class="col-xs-6">
                                    <label>Cambio:</label>
                                    <input type="text" id="txtCambio" name="txtCambio" class="form-control" readonly value="0.00">
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <label>Notas:</label>
                                <input type="text" id="txtNotas" name="txtNotas" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6 text-right">
                            <div class="panel panel-body bg-grey-300">
                                <h6 class="no-margin">SUMAS: <span id="lbl_sumas">0.00</span></h6>
                                <h6 class="no-margin">EXENTO: <span id="lbl_exento">0.00</span></h6>
                                <h6 class="no-margin">DESCUENTO: <span id="lbl_descuento">0.00</span></h6>
                                <h2 class="no-margin text-bold text-success">TOTAL: <span id="lbl_total">0.00</span></h2>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnRegistrar" class="btn bg-primary-800 btn-labeled"><b><i class="icon-floppy-disk"></i></b> Actualizar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>








<script type="text/javascript" src="web/custom-js/ventafechas.js"></script>
