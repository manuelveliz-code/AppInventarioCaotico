<?php
session_name('app_caotico');
if (!isset($_SESSION)) {
	session_start();
}
if (isset($_GET["action"])) {
	$_SESSION['page'] = $_GET["action"];
} 
if (!isset($_SESSION["cunicolist"])) {
	$_SESSION['cunicolist'] = "[]";
} 
if (isset($_GET["cunicolist"])) {
	$_SESSION['cunicolist'] = $_GET["cunicolist"];	
}

$_SESSION['APP']        = 'INVENTARIO_CAOTICO';
$_SESSION['empresa']    = '0001';
$_SESSION['navegador']  = $_SERVER['HTTP_USER_AGENT'];
$_SESSION['dircliente'] = $_SERVER['REMOTE_ADDR'];
if (!isset($_SESSION["usuario"])) {
	$_SESSION["vistaactual"] = 0;
}
// este bloque de condición indica a la actualización en que página se quedo la última vez.
if ($_GET["action"] == 0) {
	cargar_pagina($_SESSION["vistaactual"]);
} 

function cargar_pagina($opt)
{
	switch ($opt) {
		case 0:
			// selecciona usuario
			$_SESSION['page'] = 4;
			break;
		case 1:
			//selecciona tipo de operacion
			$_SESSION['page'] = 6;
			break;
		case 2:
			//selecciona lote a preparar
			$_SESSION['page'] = 3;
			break;
		case 3:
			//en proceso de despacho de lote
			$_SESSION['page'] = 1;
			break;
		case 4:
			$_SESSION['page'] = 14; // encabezados de egresos
			break;
		case 5:
			$_SESSION['page'] = 15; // detalle de egreso
			break;
		case 6:
			$_SESSION['page'] = 17; // encabezados de traslados
			break;
		case 7:
			$_SESSION['page'] = 18; // encabezados de traslados
			break;
		case 8:
			$_SESSION['page'] = 99; // encabezados de grupos picking
			//$_SESSION['page'] = 99; // encabezados de grupos picking
			break;	
		case 9:
			$_SESSION['page'] = 25; // detalle de seleccion facturas
			break;
		case 10:
			$_SESSION['page'] = 21; // detalle de productos a pickear  de grupos 
			break;				
		case 11:
			$_SESSION['page'] = 32; // encabezado de ordenes pendientes de packing
			break;				
		case 12:
			$_SESSION['page'] = 33; // DETALLE de ordenes pendientes  PACKING
			break;
		case 15:
			$_SESSION['page'] = 40; // DETALLE de ordenes pendientes  PACKING
			break;
		default:
			//reinicializa todos los datos 
			break;
	} 
}
require_once 'data/conexion.php';
require_once 'modelos.php';
switch ($_SESSION['page']) {
	case 1: // detalle de orden a realizar proceso
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'Sin detalle';
		$json['success'] = true;
		echo json_encode($json);
		break;
	case 2: // actualiza el ingreso a posiciones de Rack
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'No es posible Actualizar';
		$json['success'] = false;
		$xcontador       = 0;
		if (isset($_SESSION['estado']) && isset($_SESSION['usuario'])  && isset($_POST['codprod']) ) {
			$resultado = $libs->setcolocar('I', $_POST['ordentarea'], $_SESSION['usuario'], $_POST['codprod']);
			if ($resultado == true) {
				$json['success'] = true;
				$json['msj']     = 'Ubicación Actualizada';
			} 
			else {
				$json['msj']     = dbGetErrorMsg();
				$json['success'] = false;
			}
			echo json_encode($json);
		}
		else {
			$json['msj']     = 'Vuelva a cargar tipo de operacion y Usuario';
			$json['success'] = false;
			echo json_encode($json);
		}
		break;
	case 3: // encabezado de ingresos a bodega
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'Ordenes Pendientes';
		$json['success'] = true;
		if (isset($_POST['tipoop'])) {$_SESSION['estado'] = $_POST['tipoop']; } 
		if (isset($_POST['descripcion'])) { $_SESSION['proceso'] = $_POST['descripcion'];} 
		if (isset($_POST['tipomov'])) {$_SESSION['tipomov'] = $_POST['tipomov'];} 
		$xcontador = 0;
		$resultado = $libs->getEncabezados($_SESSION['estado']);
		if ($resultado == true) {
			$json['msj']             = 'Datos generados';
			$_SESSION["vistaactual"] = 2;
			if (!sqlsrv_has_rows($resultado)) {
				$encabezado = "<h3>&ldquo;Sin Ingresos pendientes&rdquo; </h3>";
			} 
			else {
				$encabezado = "<h3>" . $_SESSION['proceso'] . "</h3>";
				while ($obj = sqlsrv_fetch_object($resultado)) {
					$refingreso     = $obj->refingreso;
					$codigo         = $obj->codigo;
					$lote           = $obj->lote;
					$fecha          = $obj->fecha;
					$OrdenTarea     = ($obj->OrdenTarea);
					$cantidad       = $obj->cantidad;
					$habilitado     = $obj->habilitado;
					$idposestante   = trim($obj->idposestante);
					$cantcajas      = $obj->cantcajas;
					$VENCE          = $obj->VENCE;
					$nombre         = trim($obj->nombre);
					$Posicion       = trim($obj->Posicion);
					$xcontador      = $xcontador + 1;
					$claseprioridad = '';
					$xdetalle2      = '';
					$xdetalle       = '
						<div  class="col-md-12   btncargartarea"  idposestante="' . $idposestante . '"refingreso="' . $refingreso . '" ordentarea= "' . $OrdenTarea . '" id= "' . $OrdenTarea . '" codprod= "' . $codigo . '">
					      <table class="appfull tareaTI">
					         <tbody>
					            <tr >
					              <td class="txtizq"><b>Lote:</b> ' . $lote . ' </td>
					              <td class="txtizq"><b>Vence:</b> ' . $VENCE . '</td>
					            </tr>
					            <tr  >
					              <td class="txtizq">Cajas: <b class="txtcantidad">' . $cantcajas . '</b></td>
					              <td class="txtizq">Cantidad: <b class="txtcantidad">' . $cantidad . '</b></td>
					            </tr>
					            <tr >
					               <td colspan="2"  class="txtizq">[ ' . $codigo . ' ]. ' . $nombre . '.</td>
					            </tr>
					             <tr >
					               <td  class="txtizq"><h10><b>Desde:</b> </h10> <h6>' . $fecha . '</h6></td>
					               <td class="txtder"><b>Posición: </b><h3> ' . $Posicion . ' </h3></td>
					            </tr>
					         </tbody>
					      </table>
					      </br>
					   </div>';
					$detalle        = $detalle . ' ' . $xdetalle . $xdetalle2;
				}
			}
			$pietab          = '
				<!-- <script type="text/javascript" src="js/ajax6.js"></script> -->
				<script type="text/javascript">
					$(document).ready(function(){ $("#menug").load("menu.php"); 
					formatearnumeros();
					});
				</script>	
				 <script type="text/javascript">
				 	$(document).ready(function() {
					 	$(".botonF1").hover(function(){
						  $(".btn2").addClass("animacionVer");
						})
					});
				 </script>';
			$json['detalle'] = $encabezado . ' ' . $detalle . ' ' . $pietab . ' ' . initws();
		} 
		else {
			$json['msj']     = dbGetErrorMsg();
			$json['success'] = false;
		}
		echo json_encode($json);
		break;
	case 4: //formulario de logueo 
		$json            = array();
		$json['success'] = true;
		$json['msj']     = 'Ingrese/Escanee su código de usuario';
		$json['detalle'] = '
		<div class="card col-12" style="align-items: center;" >
		  <img class="card-img-top" src="img\0001.png" alt="Card image cap" style="MAX-WIDTH: 45%;">
		  <div class="card-body">
		    <h5 class="card-title">Ingrese/Escanee su código de Usuario</h5>
		    <p class="card-text">
		     <div class="form-group">
				<label for="idusuarioinicia">Inicio de Sesión</label>
				<input type="number" class="form-control" id="idusuarioinicia" aria-describedby="codigoHelp" placeholder="Código" style="font-size: x-large";>
				<br>
				<small id="codigoHelp" class="form-text text-muted"> </small>
			</div>
		    <a href="#" class="btn btn-primary btningresar" id="btnidusuarioinicia">Ingresar</a>
		  </div>
		</div>
		<!-- <script type="text/javascript" src="js/ajax6.js"></script> -->
		<script type="text/javascript">
			$(document).ready(function() {
				$( "#idusuarioinicia" ).focus();
			});
		</script>';
		echo json_encode($json);
		break;
	case 5: // verificacin de login
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'No existe el usuario';
		$json['success'] = false;
		$resultado       = $libs->loguear($_POST['xusuario']);
		if ($resultado == true) {
			if (sqlsrv_has_rows($resultado)) {
				$_SESSION["vistaactual"] = 1;
				$_SESSION["usuario"]     = $_POST['xusuario'];
				while ($obj = sqlsrv_fetch_object($resultado)) {
					$nombrerec                 = $obj->nombrerec;
					$_SESSION['nombreusuario'] = $nombrerec;
					$fotoplanil                = $obj->fotoplanil;
					$mail                      = $obj->mailinfasa;
					$sexo                      = $obj->sexo;
					$json['msj']               = 'Bienvenido ' . $nombrerec;
					$json['detalle']           = cargaropciones();
				} 
			} 
		} 
		else {
			$json['success'] = false;
			$json['msj']     = 'No existe el usuario';
		}
		echo json_encode($json);
		break;
	case 6: // carga menu principal, verificar si aún esta en uso.
		$json            = array();
		$json['success'] = true;
		$json['msj']     = 'Seleccione el tipo de operación ';
		$json['detalle'] = cargaropciones();
		echo json_encode($json);
		break;
	case 7: // cerrar sesión
		$json['msj']     = 'Finalizada';
		$json['success'] = true;
		session_destroy();
		echo json_encode($json);
		break;
	case 8: // opción controladora de ir hacia atras
		$json['msj']     = 'Finalizada';
		$json['success'] = true;
		if ($_POST['optmenu'] == 'mnseleop') {
			// SI TIPO DE VISTA = 9 // DETALLE DE GRUPO. ENTONCES REGRESA A VISTA DE GRUPOS
			switch ($_SESSION["vistaactual"]) {
				case 9:
					$_SESSION['estado']='E0';
					$_SESSION["vistaactual"] = 8;
					break;
				case 10:
					$_SESSION["proceso"]='Egresos de bodega';
					$_SESSION["vistaactual"] = 9;
					//$_SESSION['estado']='E1';
					break;
				case 12:
					$_SESSION["proceso"]='Packing';
					$_SESSION["vistaactual"] = 11;
					$_SESSION['estado']='E1';
					break;
				default:
					$_SESSION["vistaactual"] = 1;
					break;
			}
			$_SESSION['cunicolist'] ='[]';
		} 
		if ($_POST['optmenu'] == 'mnselelote') {
			$_SESSION["vistaactual"] = 1;
		}
		if ($_POST['optmenu'] == 'mnfullscreen') {
			$json['msj'] = 'openFullscreen();';
		}
		if ($_POST['optmenu'] == 'mnsalir') {
			session_destroy();
		}
		echo json_encode($json);
		break;
	case 9: // opcion obsoleta
		$json['msj']     = 'opción obsoleta';
		$json['success'] = false;
		echo json_encode($json);
		break;
	case 10: // funcion obsoleta
		$json['msj']     = 'opción obsoleta';
		$json['success'] = false;
		echo json_encode($json);
		break;
	case 11: // salir a menú principal
		$json['msj']     = 'Finalizada';
		$json['success'] = true;
		Cargar_pagina(6);
		$_SESSION["vistaactual"]   = 1;
		echo json_encode($json);
		break;
	case 12: //opción de # de documentos pendients
		$libs            = new db();
		$json['msj']     = 'Finalizada';
		$json['success'] = false;
		$resultado       = $libs->gettotaldoc();
		if ($resultado == true) {
			while ($obj = sqlsrv_fetch_object($resultado)) {
				$cantingresos          = $obj->INGRESOS;
				$cantdespachos         = $obj->EGRESOS;
				$canttraslados         = $obj->TRASLADOS;
				$json['cantingresos']  = $cantingresos;
				$json['cantdespachos'] = $cantdespachos;
				$json['canttraslados'] = $canttraslados;
				if ($cantingresos + $cantdespachos + $canttraslados >= 1) {
					$json['success'] = true;
					$json['msj']     = 'Existen ordenes pendientes';
				}
				else {
					$json['success'] = false;
				}
			}
		}
		echo json_encode($json);
		break;
	case 13:// función 
		$libs      = new db() ;
		$resultado = $libs->consulta_alarma();
		if ($resultado == true) {
			while ($obj = sqlsrv_fetch_object($resultado)) {
				$alarma = $obj->alarma;
				echo $alarma;
			}
		}
		session_destroy();
		break;
	/*case 14: // encabezado de ordenes pendientes de proceso E1 EGRESOS DE BODEGA
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'Ordenes Pendientes';
		$json['success'] = true;
		if (isset($_POST['tipoop'])) {$_SESSION['estado'] = $_POST['tipoop']; }
		if (isset($_POST['descripcion'])) { $_SESSION['proceso'] = $_POST['descripcion'];}
		if (isset($_POST['tipomov'])) {$_SESSION['tipomov'] = $_POST['tipomov'];}
		$xcontador = 0;
		$resultado = $libs->getEncabezados($_SESSION['estado']);
		if ($resultado) {
			if (!sqlsrv_has_rows($resultado)) {
				$encabezado              = "<h3>&ldquo;Sin Salidas pendientes&rdquo; </h3>";
				$_SESSION["vistaactual"] = 4;
			}
			else {
				$json['msj']             = 'Datos generados';
				$_SESSION["vistaactual"] = 4;
				$encabezado              = "<h3>" . $_SESSION['proceso'] . "</h3>";
				while ($obj = sqlsrv_fetch_object($resultado)) {
					$nombre     = trim($obj->nombre);
					$cunico     = trim($obj->cunico);
					$idx        = ($obj->idx);
					$prioridad  = $obj->prioridad;
					$empresa    = $obj->empresa;
					$nomempresa = trim($obj->nomempresa);
					$opera      = $obj->opera;
					$pedido     = trim($obj->pedido);
					$empresacli = $obj->empresacli;
					$codcliente = $obj->codcliente;
					$corre      = $obj->corre;
					$correl     = $obj->correl;
					$vendedor   = trim($obj->vendedor);
					$factura    = trim($obj->factura);
					$esfact     = $obj->esfact;
					$xcontador  = $xcontador + 1;
					$xdetalle2  = '';
					$xdetalle   = '
					  	<div class="btncargartarea_egreso_marcar container card mb-3 ' . $nomempresa . ' margen15 " empresa= "' . $nomempresa . '" cunico="' . $cunico . '" id="' . $cunico . '" pedido= "'.$pedido.'" >
					      <div class="row " >
					        <div class="col">
					          <div class=" txtizq"><h3 class="text-info">' . $corre . '</h3></div>
					        </div>
					        <div class="col">
					          <div class=" txtder "><h5 class="text-primary">Ped: ' . $pedido . '</h5></div>
					        </div>
					      </div>
					     <div class="row">
					        <div class="col txtizq txt' . $nomempresa . '">
					          <h6>Cliente :</h6>
					        </div>
					        <div class="col txtder  txt' . $nomempresa . '">
					          <h6> ' . $factura . '</h6>
					        </div>
					      </div>
					      <div class="row">
					        <div class="col txtizq txt' . $nomempresa . '">
					          <h4> ' . $nombre . '</h4>
					        </div>
					      </div>
					      <div class="row">
					        <div class="col txtizq txt' . $nomempresa . '">
					          <h6>Vendedor :</h6><h6>' . $vendedor . ' </h6>
					        </div>
					        <div class="col txtder  txt' . $nomempresa . '">
					          <span class="text-muted">Empresa:</span><h6>' . $nomempresa . '</h6>
					        </div>
					      </div>
					    </div>';
					$detalle    = $detalle . ' ' . $xdetalle . $xdetalle2;
				} 
			}
			$pietab          = '
				<!-- <script type="text/javascript" src="js/ajax6.js"></script> -->
				<script type="text/javascript">
					$(document).ready(function(){ $("#menug").load("menu.php"); });
				</script>	
				 <script type="text/javascript">
				 	$(document).ready(function() {
					 	$(".botonF1").hover(function(){
						  $(".btn2").addClass("animacionVer");
						});
						$(window).scrollTop(0);
					});
				 </script>
				<div class="contenedor">
					<button class=" botonF1 btncargartareas_egreso">
					   <span class="fas fa-shopping-cart"></span> 
					</button>
				</div>
				<style type="text/css">
					.botonF1{
						width:60px;
						height:60px;
						border-radius:100%;
						background:#007bff;
						right:0;
						bottom:0;
						position:fixed;
						margin-right:16px;
						margin-bottom:16px;
						border:none;
						outline:none;
						color:#FFF;
						font-size:2em;
						box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
						transition:.3s;  
						/*background-image: url(uni.png)!important;
						background-position: center;
						/*
					}
					.animacionVer{
						transform:scale(1);
					}
				 </style>
				 <script type="text/javascript">
				 	$(document).ready(function() {
					 	$(".botonF1").hover(function(){
						  $(".btn2").addClass("animacionVer");
						})
						for(var i in cunicolist){
						   $("#"+cunicolist[i]).addClass("seleccionado");
						}
					});
				 </script> ';
			$json['detalle'] = $encabezado . ' ' . $detalle . '' . $pietab . ' ' . initws();
		}
		else {
			$json['msj']     = dbGetErrorMsg();
			$json['success'] = false;
		}
		echo json_encode($json);
		break;
	*/
	case 15: // DETALLE de ordenes pendientes  E2 EGRESOS DE BODEGA
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'Detalle de Salida';
		$json['success'] = true;
		if (isset($_POST['descripcion'])) { $_SESSION['proceso'] = $_POST['descripcion'];} 
		if (isset($_POST['clasempresa'])) { $_SESSION['clasempresa'] = $_POST['clasempresa'];} 
		if (isset($_POST['cunico'])) { 		$_SESSION['cunico'] = $_POST['cunico'];}
		$xcontador = 0;
		$resultado = $libs->getDetalleEgreso($_SESSION['cunico']);
		if ($resultado == true) {
			$json['msj']             = 'Datos generados';
			$_SESSION["vistaactual"] = 5;
			$encabezado              = "<h3 CLASS= txt" . $_SESSION['clasempresa'] . ">" . $_SESSION['proceso'] . "</h3>";
			$tipoproductoA           = '';
			while ($obj = sqlsrv_fetch_object($resultado)) {
				$refingreso     = trim($obj->refingreso);
				$codigo         = trim($obj->codigo);
				$lote           = ($obj->lote);
				$fecha          = $obj->fecha;
				$OrdenTarea     = $obj->OrdenTarea;
				$cantidad       = $obj->cantidad;
				$habilitado     = $obj->habilitado;
				$idposestante   = $obj->idposestante;
				$vence          = $obj->VENCE;
				$nombre         = $obj->nombre;
				$posicion       = $obj->Posicion;
				$preciopub      = $obj->preciopub;
				$xcontador      = $xcontador + 1;
				$PICKER			= $obj->PICKER;
				/************* DESABILITANDO LINEAS DE PEDIDO POR PICKER************/
				$classpicker     = '';
				if ($PICKER == 1) { $classpicker     = 'pickeado';	} 
				else{ $classpicker     = ''; }
				/************* separador************/
				$separador      = '';
				$claseprioridad = '';
				$tipoproducto   = "Liquido";
				// si tipo producto cambia su valor, se genera una división <hr>
				if ($tipoproducto != $tipoproductoA) {
					$tipoproductoA = $tipoproducto;
					$separador     = '<hr class= "separador hr' . $_SESSION['clasempresa'] . '" tipoproducto=' . $tipoproducto . '>';
				} 
				$claseprioridad = '';
				$xdetalle2      = '';
				$xdetalle       = $separador . '
				  	<div class="btncargartarea_detalle container card mb-3  margen15 semitransparente '.$classpicker.'" ordentarea= "' . $OrdenTarea . '" cunico="' . $refingreso . '" id="' . $OrdenTarea . '" codprod= "' . $codigo . '" PICKER= "' . $PICKER . '" >
				      <div class="row " >
				        <div class="col">
				          <div class=" txtizq"><h5 class="text-info">L: ' . $lote . '</h5></div>
				        </div>
				        <div class="col">
				          <div class=" txtder"><h5 class="text-primary">V: ' . $vence . '</h5></div>
				        </div>
				      </div>
				      <div class="row">
				        <div class="col txtizq">
				          <h6><span class="text-mutedx">Producto :</span></h6>
				        </div>
				        <div class="col txtder">
				          <h6> [' . $codigo . ']</h6>
				        </div>
				      </div>
				      <div class="row">
				        <div class="col txtizq">
				          <h4> ' . $nombre . '</h4>
				        </div>
				      </div>
				      <div class="row">
				        <div class="col txtizq">
				          <h5><span class="text-mutedx">Cantidad: </span>' . $cantidad . ' </h5>
				        </div>
				        <div class="col txtder">
				          <h5><span class="text-mutedx">Q. </span>' . $preciopub . '</h5>
				        </div>
				      </div>
				    </div>';
				$detalle        = $detalle . ' ' . $xdetalle . $xdetalle2;
			} 
			$pietab          = '
				<button type="button" class="btn btn-primary btn-lg btn-block optgrande  " id="btn_enviar_detalle" descripcion="Enviar detalle">
					<span class="fas fa-paper-plane"></span> Grabar pedido 
				</button>
				<!-- <script type="text/javascript" src="js/ajax6.js"></script> -->
				<script type="text/javascript">
					$(document).ready(function(){ $("#menug").load("menu.php"); });
				</script>	
				 <script type="text/javascript">
				 	$(document).ready(function() {
					 	$("body").removeClass("bgINFASA");
	                    $("body").removeClass("bgDIGELASA");
	                    $("body").addClass("bg' . $_SESSION['clasempresa'] . '");
					 	$(".botonF1").hover(function(){
						  $(".btn2").addClass("animacionVer");
						});
						$(window).scrollTop(0);
					});
				 </script>';
			$json['detalle'] = $encabezado . ' ' . $detalle . '' . $pietab . ' ' . initws();
		} 
		else {
			$json['msj']     = dbGetErrorMsg();
			$json['success'] = false;
		}
		echo json_encode($json);
		break;
	case 16: // envio de detalle de tarea
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'No es posible enviar la tarea';
		$json['success'] = false;
		if (isset( $_POST['ordentarea']) && isset($_SESSION['usuario'])  && isset($_POST['codprod']) ) {
			$resultado       = $libs->setdetalletarea('E4', $_POST['ordentarea'], $_SESSION['usuario'], $_POST['codprod']);
			if ($resultado) {
				$json['success'] = true;
				$json['msj']     = 'Tarea completada correctamente2 ';
			} 
			else {
				$json['msj']     = dbGetErrorMsg();
				$json['success'] = false;
			}
		} 
		else {
			$json['msj']     = 'No es posible actualizar,Intente de nuevo o actualice esta página ';
			$json['success'] = false;
			echo json_encode($json);
		}
		echo json_encode($json);
		break;
	case 17: // encabezado de ordenes pendientes de proceso E1 EGRESOS DE BODEGA
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'Ordenes Pendientes';
		$json['success'] = true;
		if (isset($_POST['tipoop'])) { 	$_SESSION['estado'] = $_POST['tipoop']; } 
		if (isset($_POST['descripcion'])) { $_SESSION['proceso'] = $_POST['descripcion'];}
		if (isset($_POST['tipomov'])) { $_SESSION['tipomov'] = $_POST['tipomov'];}
		$xcontador = 0;
		$resultado = $libs->getEncabezados($_SESSION['estado']);
		if ($resultado) {
			if (!sqlsrv_has_rows($resultado)) {
				$encabezado              = '<h3 class= "padre">&ldquo;Sin traslados pendientes&rdquo; </h3>';
				$_SESSION["vistaactual"] = 6;
			} 
			else {
				$json['msj']             = 'Datos generados';
				$_SESSION["vistaactual"] = 6;
				$encabezado              = '<h3 class= "padre">' . $_SESSION['proceso'] . '</h3>';
				$tareaingreso            = 0;
				$tareaegreso             = 0;
				$tareaingreso_pos        = '';
				$tareaegreso_pos         = '';
				$idposestante            = '';
				$idposestante2           = '';
				$paso                    = 0;
				$existencia 			 = 0;
				while ($obj = sqlsrv_fetch_object($resultado)) {
					$NOMOBJETO  	  = trim($obj->NOMOBJETO);
					$refsalida  	  = $obj->refsalida;
					$EJECUTAIND 	  = $obj->EJECUTAIND;
					$existencia		  = $obj->Existencia;
					$cantidad         = $obj->cantidad;
					if ($EJECUTAIND != 0) {
						$desabilitado = "desabilitado";
						$paso         = $paso + 1;
					} 
					else { 	$desabilitado = "";	}
					// fin agregar clase desabilitado
					if ($refsalida != 0) {
						$tareaegreso     = $refsalida;
						$tareaegreso_pos = $NOMOBJETO;
						$tipomov         = "E";
						$idposestante    = $obj->idposestante;
						$OrdenTarea      = $obj->OrdenTarea;
						$lineaexis='<h6><span class="text-mutedx">Existencia: </span>' . $existencia . ' </h6>';
						if ($existencia==$cantidad ){	$resaltar="clprio";	}
						else { 	$resaltar="";}
					} 
					$refingreso = $obj->refingreso;
					if ($refingreso != 0) {
						$tareaingreso     = $refingreso;
						$tareaingreso_pos = $NOMOBJETO;
						$idposestante2    = $obj->idposestante;
						$OrdenTarea2      = $obj->OrdenTarea;
						$tipomov          = "I";
						$resaltar="";
						$lineaexis="";
					} //$refingreso != 0

					
					
					$codigo           = $obj->codigo;
					$lote             = $obj->lote;
					$fecha            = $obj->fecha;
					$ordentareacomodin = $obj->OrdenTarea;
					
					$habilitado       = $obj->habilitado;
					$IDTRASLADO       = $obj->IDTRASLADO;
					$TARSALI          = $obj->TARSALI;
					$TARINGRE         = $obj->TARINGRE;
					$VENCE            = $obj->VENCE;
					$nombre           = trim($obj->nombre);
					$xdetalle2        = '';
					// construir los dos detalles (salida y entrada)
					$xdetalle         = '
				  	<div class="btndetalle_traslado container card mb-3  margen15 semitransparente hijo hijo' . $IDTRASLADO . ' hijo' . $IDTRASLADO . $tipomov . ' ' . $desabilitado . ' '.$resaltar.'" IDTRASLADO="' . $IDTRASLADO . '" ordentarea= "' . $ordentareacomodin . '" refsalida="' . $refsalida . '" refingreso="' . $refingreso . '" id="' . $ordentareacomodin . '" paso=' . $paso . ' tipomov= ' . $tipomov . ' codprod="' . $codigo . '" >
				      <div class="row " >
				        <div class="col">
				          <div class=" txtizq"><h5 class="text-info">L: ' . $lote . '</h5></div>
				        </div>
				        <div class="col">
				          <div class=" txtder"><h5 class="text-primary">V: ' . $VENCE . '</h5></div>
				        </div>
				      </div>
				      <div class="row">
				        <div class="col txtizq">
				          <h6><span class="text-mutedx">Producto :</span></h6>
				        </div>
				        
				        <div class="col txtder">
				          <h6> [' . $codigo . ']</h6>
				        </div>
				      </div>
				      <div class="row">
				        <div class="col txtizq">
				          <h4> ' . $nombre . '</h4>
				        </div>
				      </div>
				      <div class="row">
				        <div class="col txtizq">
				          <h5><span class="text-mutedx">Cantidad: </span>' . $cantidad . ' </h5>
				          '.$lineaexis.'
				        </div>
				        <div class="col txtder">';
					if ($refsalida != 0)
						$xdetalle = $xdetalle . '<h5><span class="text-mutedx">Extraer de: </span> ' . $NOMOBJETO . '</h5>';
					if ($refingreso != 0)
						$xdetalle = $xdetalle . '<h5><span class="text-mutedx">Almacenar en: </span> ' . $NOMOBJETO . ' </h5>';
					$xdetalle = $xdetalle . '
				        </div>
				      </div>
				    </div>';
					// cuando ingreso y egreso esten formandos, incluir  el nuevo registro
					if ($tareaingreso != 0 && $tareaegreso != 0) {
						$encabezado       = $encabezado . '
					  	<div class="btncargartareatraslado_detalle container card mb-3  margen15 semitransparente padre padre' . $IDTRASLADO . '" IDTRASLADO="' . $IDTRASLADO . '" ordentarea= "' . $OrdenTarea . '" refsalida="' . $tareaegreso . '" refingreso="' . $tareaingreso . '" id="' . $OrdenTarea . '" idposestante= "' . $idposestante . '" idposestante2="' . $idposestante2 . '" paso=' . $paso . ' ordentarea="' . $OrdenTarea . '" ordentarea2="' . $OrdenTarea2 . '" >
					      <div class="row " >
					        <div class="col">
					          <div class=" txtizq"><h5 class="text-info">L: ' . $lote . '</h5></div>
					        </div>
					        <div class="col">
					          <div class=" txtder"><h5 class="text-primary">V: ' . $VENCE . '</h5></div>
					        </div>
					      </div>
					      <div class="row">
					        <div class="col txtizq">
					          <h6><span class="text-mutedx">Producto :</span></h6>
					        </div>
					        
					        <div class="col txtder">
					          <h6> [' . $codigo . ']</h6>
					        </div>
					      </div>
					      <div class="row">
					        <div class="col txtizq">
					          <h4> ' . $nombre . '</h4>
					        </div>
					      </div>
					      <div class="row">
					        <div class="col txtizq">
					          <h5><span class="text-mutedx">Cantidad: </span>' . $cantidad . ' </h5>
					        </div>
					        <div class="col txtder">
					        <h5><span class="text-mutedx">De: </span> ' . $tareaegreso_pos . ' <span class="text-mutedx">&rArr; </span>' . $tareaingreso_pos . '</h5>
					        </div>
					      </div>
					    </div>';
						$tareaingreso     = 0;
						$tareaegreso      = 0;
						$tareaingreso_pos = '';
						$tareaegreso_pos  = '';
						$paso             = 0;
					} //$tareaingreso != 0 && $tareaegreso != 0
					//
					$detalle = $detalle . ' ' . $xdetalle . $xdetalle2;
				} //$obj = sqlsrv_fetch_object($resultado)
			}
			$pietab             = '
				<!-- <script type="text/javascript" src="js/ajax6.js"></script> -->
				 <script type="text/javascript">
				 	$(document).ready(function() {
				 		$("#menug").load("menu.php");
				 		tareashijo= $(".hijo");
				 		$("#contenidopg_oculto").html(tareashijo);
                        tareaspadre= $(".padre");
                        $("#contenidopg").html(tareaspadre);
					 	$(".botonF1").hover(function(){
						  $(".btn2").addClass("animacionVer");
						});
						$(window).scrollTop(0);
					});
				 </script>';
			$json['detalle']    = $encabezado . ' ' . $pietab . ' ' . initws();
			$json['encabezado'] = $detalle;
		} //$resultado
		else {
			//$json['msj']     = ucfirst(strtolower(utf8_encode(mssql_get_last_message())));
			$json['msj']     = dbGetErrorMsg();
			$json['success'] = false;
		}
		echo json_encode($json);
		break;
	case 18: // encabezado de TARIMAS PENDIENTES DE ARMAR
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'Tarimas pendientes de armar';
		$json['success'] = true;
		if (isset($_POST['tipoop'])) {$_SESSION['estado'] = $_POST['tipoop']; }
		if (isset($_POST['descripcion'])) {$_SESSION['proceso'] = $_POST['descripcion'];}
		if (isset($_POST['tipomov'])) {$_SESSION['tipomov'] = $_POST['tipomov'];}
		$xcontador = 0;
		$resultado = $libs->getEncabezados($_SESSION['estado']);
		if ($resultado == true) {
			$json['msj']             = 'Datos generados';
			$_SESSION["vistaactual"] = 7;
			if (!sqlsrv_has_rows($resultado)) {
				$encabezado = "<h3>&ldquo;Sin Tarimas pendientes&rdquo; </h3>";
			}
			else {
				$encabezado = "<h3>" . $_SESSION['proceso'] . "</h3>";
				while ($obj = sqlsrv_fetch_object($resultado)) {
					$refingreso     = $obj->refingreso;
					$codigo         = $obj->codigo;
					$lote           = $obj->lote;
					$fecha          = $obj->fecha;
					$OrdenTarea     = ($obj->OrdenTarea);
					$cantidad       = $obj->cantidad;
					$habilitado     = $obj->habilitado;
					$idposestante   = trim($obj->idposestante);
					$cantcajas      = $obj->cantcajas;
					$VENCE          = $obj->VENCE;
					$nombre         = trim($obj->nombre);
					$Posicion       = trim($obj->Posicion);
					$xcontador      = $xcontador + 1;
					$claseprioridad = '';
					$xdetalle2      = '';
					$xdetalle       = '
						<div  class="col-md-12   btncargartarea_tarima"  idposestante="' . $idposestante . '"refingreso="' . $refingreso . '" ordentarea= "' . $OrdenTarea . '" id= "' . $OrdenTarea . '" codprod= "' . $codigo . '">
					      <table class="appfull tareaTA">
					         <tbody>
					            <tr >
					              <td class="txtizq"><b>Lote:</b> ' . $lote . ' </td>
					              <td class="txtizq"><b>Vence:</b> ' . $VENCE . '</td>
					            </tr>
					            <tr  >
					              <td class="txtizq">Cajas: <b class="txtcantidad">' . $cantcajas . '</b></td>
					              <td class="txtizq">Cantidad: <b class="txtcantidad">' . $cantidad . '</b></td>
					            </tr>
					            <tr >
					               <td colspan="2"  class="txtizq">[ ' . $codigo . ' ]. ' . $nombre . '.</td>
					            </tr>
					             <tr >
					               <td  class="txtizq"><h10><b>Desde:</b> </h10> <h6>' . $fecha . '</h6></td>
					               <td class="txtder"><b>Etiquetar Posición: </b><h3><i class="fas fa-tag"></i> ' . $Posicion . ' </h3></td>
					            </tr>
					         </tbody>
					      </table>
					      </br>
					   </div>';
					$detalle        = $detalle . ' ' . $xdetalle . $xdetalle2;
				}
			}
			$pietab          = '
				<!-- <script type="text/javascript" src="js/ajax6.js"></script> -->
				<script type="text/javascript">
					$(document).ready(function(){ $("#menug").load("menu.php"); 
					formatearnumeros();
					});
				</script>	
				 <script type="text/javascript">
				 	$(document).ready(function() {
					 	$(".botonF1").hover(function(){
						  $(".btn2").addClass("animacionVer");
						})
					});
				 </script>';
			$json['detalle'] = $encabezado . ' ' . $detalle . ' ' . $pietab . ' ' . initws();
		} 
		else {
			$json['msj']     = dbGetErrorMsg();
			$json['success'] = false;
		}
		echo json_encode($json);
		break;
	case 19: // envio de detalle de tarea
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'No es posible enviar la tarea';
		$json['success'] = false;
		$resultado       = $libs->setdetalletarea('TL', $_POST['ordentarea'], $_SESSION['usuario'], $_POST['codprod']);
		if ($resultado) {
			$json['success'] = true;
			$json['msj']     = 'Tarima completada';
		} 
		else {
			$json['msj']     = dbGetErrorMsg();
			$json['success'] = false;
		}
		echo json_encode($json);
		break;
	case 20: // envio de detalle de tarea
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'No es posible enviar la tarea';
		$json['success'] = false;
		if (isset( $_POST['ordentarea']) && isset($_SESSION['usuario']) && isset($_POST['codprod']) ) {
			if ($_POST['codprod'] !=0 ) {
				$resultado       = $libs->GetdetalletareaHD('E3', $_POST['ordentarea'], $_SESSION['usuario'], $_POST['codprod']);
				$json['deshabilitar'] = true;
			}
			else {
			 	$resultado       = $libs->GetdetalletareaHD('E3', $_POST['ordentarea'], $_SESSION['usuario'], 0);
			 	$json['deshabilitar'] = false;
			 }
			if ($resultado) {
				$json['success'] = true;
				$json['msj']     = 'Tarea completada correctamente2 ';
			}
			else {
				$json['msj']     = dbGetErrorMsg();
				$json['msj2']     = 'Error en la consulta';
				$json['success'] = false;
			}
		}
		else {
			$json['msj']     = 'No es posible actualizar,Intente de nuevo o actualice esta página ';
			$json['success'] = false;
			echo json_encode($json);
		}
		echo json_encode($json);
		break;
	case 21: // DETALLE de ordenes pendientes  E2 EGRESOS DE BODEGA
		$json            = array();
		$detalle         = ''; 
		$detallepadre	 = '';
		$cantidadtotal	 = 0;
		//$xdetallebono	= '';
		$xdetallepadre      = '';
		$libs            = new db();
		$json['msj']     = 'Detalle de Salida';
		$json['success'] = true;	
		$_SESSION['clasempresa']='INFASA';
		$xcontador = 0;
		$resultado = $libs->getGrupoPicking('E1', $_SESSION['grupopicking'],'','','','','');
		if ($resultado == true) {
			$json['msj']             = 'Datos generados';
			$_SESSION["vistaactual"]=10;
			$encabezado              = "<h3 CLASS= txt" . $_SESSION['clasempresa'] . ">Grupo #  ". $_SESSION['grupopicking']." </h3>";
			$tipoproductoA           = '';
			$loteA					 = '';
			$codigoA				 = '';
			while ($obj = sqlsrv_fetch_object($resultado)) {
				$cunico     = trim($obj->cunico);
				$pedido     = trim($obj->pedido);
				$codigo         = trim($obj->codigo);
				$lote           = ($obj->lote);
				
				if ($lote != $loteA) {
					$cantidadtotal= 0; 
				} 
				$fecha          = $obj->fecha;
				$OrdenTarea     = $obj->OrdenTarea;
				$cantidad       = $obj->cantidad;
				$cantidadtotal	= $cantidadtotal + $cantidad;
				$habilitado     = $obj->habilitado;
				$idposestante   = $obj->idposestante;
				$vence          = $obj->Vence;
				$nombre         = trim($obj->nombre);
				$Posicion       = trim($obj->Posicion);
				$preciopub      = $obj->preciopub;
				$taked			= $obj->taked;
				$idCarreta		= $obj->idCarreta;
				$idAgrupa		= $obj->idAgrupa;
				$acceso			= $obj->acceso;
				$Area			= $obj->Area;
				$tipoproducto	= $obj->formulaf;
				$ordenCarreta	= $obj->ordenCarreta;
				$Contenedor		= $obj->Contenedor;
				$bono			= $obj->Bono;
				$Posicioncomp =trim($obj->Area).' '. trim($obj->Posicion);
				$xcontador      = $xcontador + 1;
				///************* DESABILITANDO LINEAS DE PEDIDO POR PICKER************
				$classpicker     = '';
				$descripcionbono = ' ';

				if($bono===0){
					$descripcionbono = 'Cantidad:';
				}else{
					$descripcionbono = 'Bonificacion: <img src="/img/bonificacion.jpg" height="50px" width="50px">';

				}
				if ($taked == 1) {
					$classpicker     = 'pickeado';	
				} 
				else
				{
					$classpicker     = '';
				}
				//************* separador************
				$separador      = '';
				$claseprioridad = '';
				// si tipo producto cambia su valor, se genera una división <hr>
				if ($tipoproducto != $tipoproductoA) {
					$tipoproductoA = $tipoproducto;
					$separador     = '<hr class= "separador  hr' . $_SESSION['clasempresa'] . ' contenedorsep"  tipoproducto=' . $tipoproducto . '>';
					$xdetallepadre = $xdetallepadre . $separador;
				} 
				$claseprioridad = '';
				/*------------------------------------*/
				// si se produce cambio, se agrega el lote anterior 
				if ($lote != $loteA or $codigo!= $codigoA) {
					$loteA = $lote;
					$codigoA=$codigo;
					$detallepadre        = $detallepadre . ' ' . $xdetallepadre;
				} 
				$xdetallepadre       =  '
				  	<div class="btncargar_grupos container card mb-3  margen15 semitransparente '.$classpicker.' padre'.$lote.$codigo.'" ordentarea= "' . $OrdenTarea . '" cunico="' . $cunico . '" id="' . $OrdenTarea . '" codprod= "' . $codigo . '" taked= "' . $taked . '" lote= "'.$lote.'" idposestante="'.$idposestante.'">
				      <div class="row " >
				        <div class="col">
				          <div class=" txtizq"><h5 class="text-info"> <span class="fas fa-search"></span>' . $Posicioncomp . '</h5></div>
				        </div>
				        <div class="col">
				          <div class=" txtder"><h5 class="text-primary">V: ' . $vence . '</h5></div>
				        </div>
				      </div>
				      <div class="row">
				        <div class="col txtizq">
				          <h6><span class="text-mutedx">Producto :</span></h6>
				        </div>
				        <div class="col txtder">
				          <h6> [' . $codigo . ']</h6>
				        </div>
				      </div>
				      <div class="row">
				        <div class="col txtizq">
				          <h4> ' . $nombre . '</h4>
				        </div>
				      </div>
				      <div class="row">
				        <div class="col txtizq">
				          <h5><span class="text-mutedx">Cantidad: </span>' . $cantidadtotal . ' </h5>
				        </div>
				        <div class="col txtder">
				          <h5><span class="text-mutedx">Q. </span>' . $preciopub . '</h5>
				        </div>
				        <div class="col txtder">
				          <h5><span class="text-mutedx "> </span>L:' . $lote . '</h5>
				        </div>
				      </div>
					</div>';	
				
				$xdetalle       = '
					  <div class="container card mb-3  margen15 semitransparente '.$classpicker.' hijo'.$lote.$codigo.'" ordentarea= "' . $OrdenTarea . '" cunico="' . $cunico . '" id="' . $OrdenTarea . '" codprod= "' . $codigo . '" taked= "' . $taked . '" carreta= "'. $ordenCarreta.'" posicion= "'.$idposestante.'"" >
					  
						<div class="row">
				        
				        <div class="col txtizq">
				          <h6><span class="text-mutedx">'. $descripcionbono .'</span></h6>
				        </div>
				        <div class="col txtizq">
				          <h6><span class="text-mutedx">Guardar en: </span></h6>
				        </div>
				      </div>
					  <div class="row">
				        <div class="col txtizq">
				          <h4>' . $cantidad . ' </h4>
						</div>
						
				        <div class="col txtizq">
				          <h4> ' . $Contenedor .' '.  $idCarreta.'</h4> 
				        </div>
				      </div>
				      <div class="row">
				      	
				      </div>
					</div>';
				

				

				       // $xdetalle 		= $xdetalle . ' ' . $xdetallesinbono . ' ' . $xdetallebono;	
						$detalle        = $detalle . ' ' . $xdetalle ;
			//	$detalle		= $detalle . ' ' . $xdetallebono ;
				//$detalle =$detalle. ' '.$detalle2;
			} //$obj = sqlsrv_fetch_object($resultado)
			$pietab          = '
				<button type="button" class="btn btn-primary btn-lg btn-block optgrande  " id="btn_finalizargrupo" descripcion="Enviar detalle">
					<span class="fas fa-paper-plane"></span> Finalizar Grupo 
				</button>
				<!-- <script type="text/javascript" src="js/ajax6.js"></script> -->
				<script type="text/javascript">
					$(document).ready(function(){ $("#menug").load("menu.php"); });
				</script>	
				 <script type="text/javascript">
				 	$(document).ready(function() {
					 	$("body").removeClass("bgINFASA");
	                    $("body").removeClass("bgDIGELASA");
	                    $("body").addClass("bg' . $_SESSION['clasempresa'] . '");
					 	$(".botonF1").hover(function(){
						  $(".btn2").addClass("animacionVer");
						});
						$(window).scrollTop(0);
					});
				 </script>
				 ';
			$detallepadre        = $detallepadre . ' ' . $xdetallepadre;
			//$json['detalle'] = $encabezado . ' ' . $detalle . '' . $pietab . ' ' . initws();
			$oculto= '<div class= "oculto">';
			$json['detalle'] = $encabezado .' ' .$detallepadre. ' '.$oculto . $detalle . '</div>'. ' ' . $pietab . ' ' . initws();
		} 
		else {
			$json['msj']     = dbGetErrorMsg();
			$json['success'] = false;
		}
		echo json_encode($json);
		break;
	case 22: // encabezado grupos de
		$json            = array();
		$detalle         = '';
		$detallefac         = '';
		$cadenabusq		= '';
		$libs            = new db();
		$json['msj']     = 'Detalle de Grupos generados';
		$json['success'] = true;
		if (isset($_POST['tipoop'])) {$_SESSION['estado'] = $_POST['tipoop']; } 
		if (isset($_POST['descripcion'])) { $_SESSION['proceso'] = $_POST['descripcion'];} 
		if (isset($_POST['tipomov'])) {$_SESSION['tipomov'] = $_POST['tipomov'];} 
		$xcontador = 0;
		$resultado = $libs->getEncabezadosgrupo($_SESSION['estado'],$_SESSION['usuario']);
		if ($resultado) {
			$enc_superior='
		<div class=" container card mb-3  margen15 " style="padding-top: 15px;" >
          <div class="row ">
            <div class="col">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                </div>
                <input id= "txtBuscapedido" type="text" class="form-control" placeholder="Ubicar pedido" aria-label="Ubicar Pedido" aria-describedby="basic-addon1">
              </div>
              
            </div>
            <div class="col">
                 <button type="button" class="btn btn-block  btn-success" id= "btnnuevogrupo">Nuevo.Grupo</button>
            </div>
          </div>
        </div>';
        	// es el modelo de el nuevo grupo. 
			$conteoculto= '
			<div id="modelonuevogrupo" class="oculto">
				<div class="btncargartarea_nuevogrupo container card mb-3 DIGELASA margen15 " empresa="DIGELASA" grupo = "0" id= "modelogruponuevo" >
		          <div class="row ">
		            <div class="col">
		              <div class=" txtizq"><h3 class="text-info">Grupo ID: #1454</h3></div>
		            </div>
		            <div class="col">
		              <div class=" txtder "><h5 class="text-primary">Inicio: 08/08/2019</h5></div>
		            </div>
		          </div>
		          <div class="row">
		            <div class="col txtizq txtDIGELASA">
		              <h6>Facturas :</h6>
		            </div>
		          </div>
		          <!-- linea 1-->
		          <div class="row">
		            <div class="col txtizq txtDIGELASA">
		              <h6></h6><h6> </h6>
		            </div>
		            <div class="col txtder  txtDIGELASA">
		              <span class="text-muted">Estatus:</span><h6>En proceso</h6>
		            </div>
		          </div>
		        </div>
		    </div>'; // modelo oculto de nuevo grupo.

			if (!sqlsrv_has_rows($resultado)) {
				$encabezado              = "
				<div id= 'detallegrupo'>
				<h3>&ldquo;Sin grupos pendientes&rdquo; </h3>
				</div>";
				$_SESSION["vistaactual"] = 8; // punto de anclaje, si se actualiza explorador
			}
			else {
				$anteriorgrupo= 0;
				$anteriorcunico='';
				$grupoagregar='';
				$json['msj']             = 'Datos generados';
				$_SESSION["vistaactual"] = 8;
				$encabezado              = "<h3>" . $_SESSION['proceso'] . "</h3>";
				$detcarreta='';
				while ($obj = sqlsrv_fetch_object($resultado)) {
					$idagrupa   = ($obj->idAgrupa);
					$cunico   	= ($obj->cunico);
					if ($anteriorgrupo==0 ){ $anteriorgrupo= $idagrupa; }
					if ($anteriorcunico==0 ){ $anteriorcunico= $cunico; }
					$iduser		= ($obj->idUser);
					$inicio   	= ($obj->INICIO);
					$iniciogt   	= ($obj->INICIO);
					$iddetagrupa= ($obj->idDetAgrupa);
					
					$pedido   	= ($obj->pedido);
					$xcontador  = $xcontador + 1;
					$idCarreta= ($obj->idCarreta);
					// DETALLE ACUMULATIVO.
					if ($anteriorgrupo== $idagrupa){
						// verificar si Cunico no cambia
						if ($anteriorcunico== $cunico){
							$detcarreta=$detcarreta.'
									<div class="col txtizq txtDIGELASA">
						                <h4> '.$idCarreta.'</h4>
						            </div>';
						} 
						else{
							$detcarreta='';
							$detcarreta=$detcarreta.'
									<div class="col txtizq txtDIGELASA">
						                <h4> '.$idCarreta.'</h4>
						            </div>';

							}

							$cadenabusq= $cadenabusq . $cunico.$pedido;
							$detallefac=$detallefac.'
								<div class="row">
						            <div class="col txtizq txtDIGELASA">
						                <h4> '.$cunico.'</h4>
						            </div>
						            <div class="col txtizq txtDIGELASA">
						                <h4> '.$pedido.'</h4>
						            </div>
						            '.$detcarreta.'
						        </div>';
							$xdetalle='
								<div id="detallegrupo">
								    <div class="btncargartarea_seleccionagrupo container card mb-3 DIGELASA margen15 " empresa="DIGELASA" idAgrupa="'.$idagrupa.'" dadatabus="'.$cadenabusq.'">
								        <div class="row ">
								            <div class="col">
								                <div class=" txtizq">
								                    <h3 class="text-info">Grupo ID: #'.$idagrupa.'</h3></div>
								            </div>
								            <div class="col">
								                <div class=" txtder ">
								                    <h5 class="text-primary">Inicio: '.$iniciogt.'</h5></div>
								            </div>
								        </div>
								        <div class="row">
								            <div class="col txtizq txtDIGELASA">
								                <h6>Facturas :</h6>
								            </div>
								            <div class="col txtizq txtDIGELASA">
								                <h6>Pedido :</h6>
								            </div>
								        </div>
								        <!-- detalle de facturas agregadas -->
								       '.$detallefac.'
								        <!-- fin detalle de facturas agregadas-->						       
								        <div class="row">
								            <div class="col txtizq txtDIGELASA">
								                <h6></h6>
								                <h6></h6>
								            </div>
								            <div class="col txtder  txtDIGELASA">
								                <span class="text-muted">Estatus:</span>
								                <h6>Sin finalizar</h6>
								            </div>
								        </div>
								    </div>
								</div>';
							$grupoagregar= $xdetalle;
						//}

					}
					else{
						$detcarreta='';
						$anteriorgrupo= $idagrupa;
						$anteriorcunico= $cunico;
						$detalle    = $detalle . $grupoagregar ;
						$detallefac='';
						$cadenabusq='';
						
						$cadenabusq= $cadenabusq . $cunico.$pedido;
						$detcarreta=$detcarreta.'
									<div class="col txtizq txtDIGELASA">
						                <h4> '.$idCarreta.'</h4>
						            </div>';
						$detallefac=$detallefac.'
							<div class="row">
					            <div class="col txtizq txtDIGELASA">
					                <h4> '.$cunico.'</h4>
					            </div>
					            <div class="col txtizq txtDIGELASA">
					                <h4> '.$pedido.'</h4>
					            </div>
					            '.$detcarreta.'
					        </div>';
						$xdetalle='
							<div id="detallegrupo">
							    <div class="btncargartarea_seleccionagrupo container card mb-3 DIGELASA margen15 " empresa="DIGELASA" idAgrupa="'.$idagrupa.'" dadatabus="'.$cadenabusq.'">
							        <div class="row ">
							            <div class="col">
							                <div class=" txtizq"><h3 class="text-info">Grupo ID: #'.$idagrupa.'</h3></div>
							            </div>
							            <div class="col">
							                <div class=" txtder ">
							                    <h5 class="text-primary">Inicio: '.$iniciogt.'</h5></div>
							            </div>
							        </div>
							        <div class="row">
							            <div class="col txtizq txtDIGELASA">
							                <h6>Facturas :</h6>
							            </div>
							            <div class="col txtizq txtDIGELASA">
							                <h6>Pedido :</h6>
							            </div>
							        </div>
							        <!-- detalle de facturas agregadas -->
							       '.$detallefac.'
							        <!-- fin detalle de facturas agregadas-->
							        <div class="row">
							            <div class="col txtizq txtDIGELASA">
							                <h6></h6>
							                <h6></h6>
							            </div>
							            <div class="col txtder  txtDIGELASA">
							                <span class="text-muted">Estatus:</span>
							                <h6>Sin finalizar</h6>
							            </div>
							        </div>
							    </div>
							</div>';
						$grupoagregar= $xdetalle;
					}
				} 
				
				$detalle    = $detalle . $grupoagregar ;
			}
			$pietab          = '
				<!-- <script type="text/javascript" src="js/ajax6.js"></script> -->
				<script type="text/javascript">
					$(document).ready(function(){ $("#menug").load("menu.php"); });
				</script>	
				 <script type="text/javascript">
				 	$(document).ready(function() {
						for(var i in cunicolist){
						   $("#"+cunicolist[i]).addClass("seleccionado");
						}
					});
				 </script>
				 ';
			$json['detalle'] = $enc_superior.' '. $encabezado . ' ' . $detalle . '' . $pietab .'' . $conteoculto. ' ' . initws();
		} //$resultado
		else {
			//$json['msj']     = ucfirst(strtolower(utf8_encode(mssql_get_last_message())));
			$json['msj']     = dbGetErrorMsg();
			$json['success'] = false;
		}
		echo json_encode($json);
		break;
	case 23: // Generar núevo ID de código para agrupar
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'No es posible generar grupo.';
		$json['success'] = true;
		$resultado = $libs-> getGrupoPicking('G1', '',$_SESSION['usuario'],'','','',''); // sustituir por funcion de generar G1
		if ($resultado) {
			if (!sqlsrv_has_rows($resultado)) {
				$json['msj']     = 'No es posible generar grupo.';
				$json['grupo']     = 0;
				$json['success'] = TRUE;
				$_SESSION['grupopicking']= 0;//inicializo la variable sesion con 0
			} 
			else {
				$json['success'] = true;
				$json['msj']             = 'Grupo generado';
				while ($obj = sqlsrv_fetch_object($resultado)) {
					$json['grupo'] = $obj->idAgrupa;
					$_SESSION['grupopicking']= $obj->idAgrupa;
				} 
			}
		}
		else { 		
			$json['msj']     = dbGetErrorMsg();		
			$json['success'] = false;
		}
		echo json_encode($json);
		break;
	case 24: // generar listado de Cunicos marcados en sesión de el grupo 
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'Listado posiciones Guardado';
		$json['success'] = true;
		$cunicolistaux2='';
		if (isset($_POST["cunicolist"])) {
			$cunicolistaux= explode(",",$_POST["cunicolist"]);
	        for($i = 0; $i < count($cunicolistaux); $i++){
	      		if (rtrim(ltrim($cunicolistaux[$i]))!= ''){
	      			$cunicolistaux2= $cunicolistaux2. '"'.$cunicolistaux[$i].'",';
	          	}
			}
			$json['msj']     = 'Listado posiciones Guardado|[' . $cunicolistaux2. ']';
			$_SESSION['cunicolist'] ='['. $cunicolistaux2.']';
		} 
		echo json_encode($json);
		break;
	case 25: // encabezado de ordenes pendientes de proceso E1 EGRESOS DE BODEGA nueva funcion sustituye case 14 

		//este case es para la pantalla despues del boton Nuevo.grupo
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'Ordenes Pendientes vien desde el false';
		$json['success'] = true;
		$json['dato'] = false;
		if (isset($_POST['grupopicking'])) {$_SESSION['grupopicking'] = $_POST['grupopicking']; } 
		if (isset($_POST['tipoop'])) {$_SESSION['estado'] = $_POST['tipoop']; } 
		if (isset($_POST['descripcion'])) { $_SESSION['proceso'] = $_POST['descripcion'];} 
		if (isset($_POST['tipomov'])) {$_SESSION['tipomov'] = $_POST['tipomov'];} 
		if (isset($_POST['esnuevogrupo'])) {$_SESSION['esnuevogrupo'] = $_POST['esnuevogrupo'];} 
		
		$xcontador = 0;
		if ($_SESSION['esnuevogrupo']==0){
			
			//$resultado = $libs->getGrupoPicking($_SESSION['estado'],$_SESSION['grupopicking']);
			$resultado = $libs->getGrupoPicking($_SESSION['estado'], $_SESSION['grupopicking'],'','','','','');
			
		}
		else{
			$resultado = $libs->getEncabezados($_SESSION['estado']);
		}
		
		if ($resultado) {
			if (!sqlsrv_has_rows($resultado)) {
				
				$json['dato'] = true;
				$nohay='darclick();';
				$encabezado              = '<h3>Sin Salidas pendientes </h3>';
				$_SESSION["vistaactual"] = 9;
				
			} 
			else {
				$json['msj']             = 'Datos generados';
				$_SESSION["vistaactual"] = 9;
				$encabezado              = "<h3>" . $_SESSION['proceso'] . "</h3>";
				while ($obj = sqlsrv_fetch_object($resultado)) {
					$enuso='valor';
					$nombre     = trim($obj->nombre);
					$cunico     = trim($obj->cunico);
					$idx        = ($obj->idx);
					$prioridad  = $obj->prioridad;
					$empresa    = $obj->empresa;
					$nomempresa = trim($obj->nomempresa);
					$opera      = $obj->opera;
					$pedido     = trim($obj->pedido);
					$empresacli = $obj->empresacli;
					$codcliente = $obj->codcliente;
					$corre      = $obj->corre;
					$usarioactivo      = $obj->iduser;
					$enuso      = $obj->Enuso;
					$liquido	=$obj->SOLIDO;
					$solido		=$obj->LIQUIDO;
					$idcarreta      = $obj->idCarreta;
					$correl     = $obj->correl;
					$vendedor   = trim($obj->vendedor);
					$factura    = trim($obj->factura);
					$CANTMAX     = $obj->CANTMAX;
					$PRESENTACIONES     = $obj->PRESENTACIONES;
					$LINEAS     = $obj->LINEAS;
					$idDetAgrupa     = 0;
					$clasefacturas ='';
					$clasesopaca = 'btncargartarea_egreso_marcar';
					$xusuarios =$_SESSION['usuario'];
					if($idcarreta!=0){
						$clasefacturas ='clasefacturas';
					}else{$clasefacturas ='';}
					if($usarioactivo==$xusuarios){
						$clasesopaca = 'desabilitado';
						$clasefacturas ='clasefacturasmio container';
					}
					if ($idDetAgrupa== 0){ $claseseleccionado='';}
					else{ $claseseleccionado='seleccionado'; }
					$esfact     = $obj->esfact;
					$xcontador  = $xcontador + 1;
					$xdetalle2  = '';
					if($enuso===''){
						$xdetalle   = '
						<div class="'.$clasesopaca.' container card mb-3 ' . $nomempresa . ' '. $clasefacturas . ' margen15  '.$claseseleccionado.'" empresa= "' . $nomempresa . '" cunico="' . $cunico . '" id="' . $cunico . '" pedido= "'.$pedido.'" idDetAgrupa="'.$idDetAgrupa.'">
						<div class="row " >
						
						  <div class="col">
							<div class=" txtizq"><h5 class="text-info">' . $corre . '</h5></div>
						  </div>
						  <div class="col">
							<div class=" txtizq"><h5 class="text-info">Lineas:' . $LINEAS . '</h5></div>
						  </div>
						  <div class="col">
							<div class=" txtizq"><h5 class="text-info">Prod:' . $PRESENTACIONES . '</h5></div>
						  </div>
						  <div class="col">
							<div class=" txtder "><h5 class="text-info">Solido: ' . $solido . '</h5></div>
						  </div>
						  <div class="col">
							<div class=" txtder "><h5 class="text-info">Liquido: ' . $liquido . '</h5></div>
						  </div>
						  
						  <div class="col">
							<div class=" txtder "><h5 class="text-info">Ped: ' . $pedido . '</h5></div>
						  </div>
						</div>
					   <div class="row">
						  <div class="col txtizq txt' . $nomempresa . '">
							<h6>Cliente :</h6>
						  </div>
						  <div class="col txtder  txt' . $nomempresa . '">
							<h6> ' . $factura . '</h6>
						  </div>
						</div>
						<div class="row">
						  <div class="col txtizq txt' . $nomempresa . '">
							<h4> ' . $nombre . '</h4>
						  </div>
						</div>
						<div class="row">
						  <div class="col txtizq txt' . $nomempresa . '">
							<h6>Vendedor :</h6><h6>' . $vendedor . ' </h6>
						  </div>
						 
						  <div class="col txtder  txt' . $nomempresa . '">
							<span class="text-muted">Empresa:</span><h6>' . $nomempresa . '</h6>
						  </div>
						</div>
					  </div>';
					}else{
						$xdetalle   = '
					  	<div class="btncargartarea_egreso_marcar container card mb-3 ' . $nomempresa . ' '. $clasefacturas . ' margen15  '.$claseseleccionado.'" empresa= "' . $nomempresa . '" cunico="' . $cunico . '" id="' . $cunico . '" pedido= "'.$pedido.'" idDetAgrupa="'.$idDetAgrupa.'">
						  <div class="row " >
						  
					        <div class="col">
					          <div class=" txtizq"><h5 class="text-info">' . $corre . '</h5></div>
							</div>
							<div class="col">
					          <div class=" txtizq"><h5 class="text-info">Lineas:' . $LINEAS . '</h5></div>
					        </div>
					        <div class="col">
					          <div class=" txtizq"><h5 class="text-info">Prod:' . $PRESENTACIONES . '</h5></div>
							</div>
							<div class="col">
					          <div class=" txtder "><h5 class="text-info">Solido: ' . $solido . '</h5></div>
							</div>
							<div class="col">
					          <div class=" txtder "><h5 class="text-info">Liquido: ' . $liquido . '</h5></div>
					        </div>
					        
					        <div class="col">
					          <div class=" txtder "><h5 class="text-info">Ped: ' . $pedido . '</h5></div>
					        </div>
					      </div>
					     <div class="row">
					        <div class="col txtizq txt' . $nomempresa . '">
					          <h6>Cliente :</h6>
					        </div>
					        <div class="col txtder  txt' . $nomempresa . '">
					          <h6> ' . $factura . '</h6>
					        </div>
					      </div>
					      <div class="row">
					        <div class="col txtizq txt' . $nomempresa . '">
					          <h4> ' . $nombre . '</h4>
					        </div>
					      </div>
					      <div class="row">
					        <div class="col txtizq txt' . $nomempresa . '">
					          <h6>Vendedor :</h6><h6>' . $vendedor . ' </h6>
					        </div>
					        <div class="col txtizq txt' . $nomempresa . '  	 	">
					           <h6>Estado :</h6><h6> <i class="fas fa-shopping-cart">'.$enuso.'</i> </h6> 
					          
					        </div>
					        <div class="col txtder  txt' . $nomempresa . '">
					          <span class="text-muted">Empresa:</span><h6>' . $nomempresa . '</h6>
					        </div>
					      </div>
					    </div>';
					}
					
					$detalle    = $detalle . ' ' . $xdetalle . $xdetalle2;
				} 
			}
			$pietab          = '
				<!-- <script type="text/javascript" src="js/ajax6.js"></script> -->
				<script type="text/javascript">
				
					$(document).ready(function(){ $("#menug").load("menu.php"); });
				</script>	
				 <script type="text/javascript">
				 	$(document).ready(function() {
						$("#btncargartareas_egreso").click();
					 	$(".botonF1").hover(function(){
						  $(".btn2").addClass("animacionVer");
						});
				
					});
				 </script>
				<div class="contenedor">
					<button class=" botonF1 btncargartareas_egreso">
					   <span class="fas fa-shopping-cart"></span> 
					</button>
				</div>
				<style type="text/css">
					.botonF1{
						width:60px;
						height:60px;
						border-radius:100%;
						background:#007bff;
						right:0;
						bottom:0;
						position:fixed;
						margin-right:16px;
						margin-bottom:16px;
						border:none;
						outline:none;
						color:#FFF;
						font-size:2em;
						box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
						transition:.3s;  
						/*background-image: url(uni.png)!important;
						background-position: center;*/
					}
					.animacionVer{
						transform:scale(1);
					}
				 </style>
				 <script type="text/javascript">
				 
				 	$(document).ready(function() {
						function darclick(){

							var x = document.getElementsByClassName("#btncargartareas_egreso");
							x.click();
							
							}
					 	$(".botonF1").hover(function(){
						  $(".btn2").addClass("animacionVer");
						})
						cunicolist=  '.$_SESSION['cunicolist'].';
						for(var i in cunicolist){
						   $("#"+cunicolist[i]).addClass("seleccionado");
						}
					});
				 </script>
				 ';
			$json['detalle'] = $encabezado . ' ' . $detalle . '' . $pietab . ' ' . initws();
		}
		else {
			$json['msj']     = dbGetErrorMsg();
			$json['success'] = false;
		}
		echo json_encode($json);
		break;
	case 26: // agregar factura a grupo 
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'Listado posiciones Guardado';
		$json['success'] = true;
		$cunico= $_POST['cunico'];
		$pedido= $_POST['pedido'];
		$resultado = $libs-> getGrupoPicking('G2', $_SESSION['grupopicking'], $_SESSION['usuario'],$cunico,$pedido,'',''); 
		if ($resultado) {
			
				while ($obj = sqlsrv_fetch_object($resultado)) {
					$_SESSION['grupopicking']=($obj->idAgrupa);
					$idDetAgrupa = ($obj->idDetAgrupa);
				}
				$json['idDetAgrupa'] = $idDetAgrupa;
				$json['msj']     = 'Agregado al grupo. No: .::' . $_SESSION['grupopicking'] . '::. ';
				$json['success'] = true;
		}
		else { 		
			$json['msj']     = dbGetErrorMsg();		
			$json['success'] = false;
		}
		echo json_encode($json);
		break;
	case 27: // agregar contenedor a factura/pedido de grupo
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'Listado posiciones Guardado';
		$json['success'] = true;
		$contenedor= $_POST['contenedor'];
		$idDetAgrupa= $_POST['idDetAgrupa'];
		$resultado = $libs-> getGrupoPicking('G3', '','','','',$contenedor,$idDetAgrupa); // Agrega un contenedor a factura /pedido 
		if ($resultado) {
				$json['msj']     = 'Agregado a la factura';
				$json['success'] = true;
		}
		else { 		
			$json['msj']     = dbGetErrorMsg();		
			$json['success'] = false;
		}
		echo json_encode($json);
		break;
	case 28: // asignar el # de grupo a sesión
		$json            = array();
		$detalle         = '';
		$json['msj']     = 'Grupo seleccionado';
		$json['success'] = true;
		$_SESSION['cunicolist'] ='[]';
		$_SESSION['grupopicking']= $_POST['grupopicking'];
		echo json_encode($json);
		break;
	case 30: // envio de detalle de tarea
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['success'] = false;
		if (isset( $_POST['ordentarea']) && isset($_SESSION['usuario'])  && isset($_POST['codprod'])  && isset($_POST['posicion']) ) {
			$resultado       = $libs->GetdetalletareaHD('E3', $_POST['ordentarea'], $_SESSION['usuario'],$_POST['cunico'], $_POST['codprod'], $_POST['carreta'], $_POST['taked'], $_POST['posicion']);
			if ($resultado) {
				$json['success'] = true;
				$json['msj']     = 'Tarea completada correctamente de grupo ';
			} //$resultado
			else {
				$json['msj']     = 'error en consulta'.dbGetErrorMsg();
				$json['success'] = false;
			}
		} 
		else {
			$json['msj']     = 'Actualice esta vista e intente de nuevo ';
			$json['success'] = false;
			echo json_encode($json);
		}
		echo json_encode($json);
		break;
	case 31: // Finalizar grupo de picking
		$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'No es posible Finalizar el grupo.';
		$json['success'] = true;
		$resultado = $libs-> getGrupoPicking('FG', $_SESSION['grupopicking'],'','','','',''); // finaliza grupo
		if ($resultado) {
				$json['msj']     = 'Grupo finalizado';
				$_SESSION['estado'] = 'E0'; 
				$_SESSION["vistaactual"] = 8;
				$_SESSION['cunicolist'] ='[]';
				$json['success'] = true;
		} 
		else {
			$json['msj']     = dbGetErrorMsg();		
			$json['success'] = false;
		}
		echo json_encode($json);
		break;
	case 32: // encabezado de ordenes pendientes de packing
		$json            = array();
		$detalle         = '';
		$detallefac         = '';
		$detcontenedor         = '';
		$cadenabusq		= '';
		$cunicoant		= 0;
		$libs            = new db();
		$json['msj']     = 'Detalle de Grupos generados';
		$json['success'] = true;
		$xdetalle='';
		$scriptbusqueda='';
		if (isset($_POST['tipoop'])) {$_SESSION['estado'] = $_POST['tipoop']; } 
		if (isset($_POST['descripcion'])) { $_SESSION['proceso'] = $_POST['descripcion'];} 
		if (isset($_POST['tipomov'])) {$_SESSION['tipomov'] = $_POST['tipomov'];} 
		$xcontador = 0;
		$resultado = $libs->getEncabezados('S1'); // FUNCION PROVISIONAL HASTA QUE SE CREE LA OPCION DE PK EN SPINVENTARIOCAOTICO
		if ($resultado) {
			$enc_superior='
			<div class=" container card mb-3  margen15 " style="padding-top: 15px;" >
	          <div class="row ">
	            <div class="col">
	              <div class="input-group mb-3">
	                
	                <input id= "txtBuscapedido2" type="text" class="form-control" placeholder="Ubicar pedido" aria-label="Ubicar Pedido" aria-describedby="basic-addon1">
	              </div>
	              
	            </div>
	            
	          </div>
	        </div>';
			$conteoculto= '';
		    if (!sqlsrv_has_rows($resultado)) {
				$encabezado              = "
				<div id= 'detallegrupo'>
				<h3>&ldquo;Sin grupos pendientes&rdquo; </h3>
				</div>";
				$_SESSION["vistaactual"] = 8; // punto de anclaje, si se actualiza explorador
			}
			else {
				$anteriorgrupo= 0;
				$grupoagregar='';
				$cunicoant=0;
				$json['msj']             = 'Datos generados';
				$_SESSION["vistaactual"] = 11;
				$encabezado              = "<h3>" . $_SESSION['proceso'] . "</h3>";
				$detcompleto='';
				
				while ($obj = sqlsrv_fetch_object($resultado)) {
					$idagrupa   = ($obj->pedido);		
					$cunico   	= trim($obj->cunico);		
					$factura   	= trim($obj->factura);
					//$iduser		= ($obj->idUser);	
					$inicio   	= '';	
					$iniciogt   	= ' ';
					$iddetagrupa= ($obj->pedido);
					$pedido   	= ($obj->pedido);
					$porcenpedi = ($obj->porcenpedi);
					$PorceFact	= ($obj->PorceFact);
					$idCarreta= ($obj->idCarreta);
					$prioridad= ($obj->prioridad);
					if ($prioridad== 0){
						$alerta= "";	
					}
					else{
						//$alerta= "progress-bar-striped";
						$alerta= "prioridad";
					}

					$xcontador  = $xcontador + 1; 
					
					if ($anteriorgrupo!= $idagrupa){
						/**********************************************/
						if ($anteriorgrupo== 0){
							// no agrega el pie al principio
						}
						else{
							// agregar el pie
							$xdetalle= $xdetalle .'
							
							<!-- fin detalle de facturas agregadas-->						       
							        <div class="row">
							            <div class="col txtizq txtDIGELASA">
							                <h6></h6>
							                <h6></h6>
							            </div>
							            <div class="col txtder  txtDIGELASA">
							                <span class="text-muted">Estatus:</span>
							                <h6>Sin finalizar</h6>
							            </div>
							        </div>
							    </div>
							</div>';
						}
						$xdetalle=$xdetalle.'
							    <div id= "'.$idagrupa.'" class="btncargartarea_seleccionapedido container card mb-3 DIGELASA margen15 '.$alerta.' " empresa="DIGELASA" idpedido="'.$idagrupa.'" >
							        <div class="row ">
							            <div class="col">
							                <div class=" txtizq">
							                    <h3 class="text-info">Pedido: #'.$idagrupa.'</h3></div>
							            </div>
							            <div class="col">
							                <div class=" txtder ">
							                    <h5 class="text-primary">Inicio: '.$iniciogt.'</h5></div>
							            </div>
							        </div>
							        <div class="row">
							        	<div class="col txtizq txtDIGELASA">
								        	<div class=" progress">
											  <div class="progress-bar progress-bar-striped" role="progressbar" style="width: '.$porcenpedi.'%;" aria-valuenow="'.$porcenpedi.'" aria-valuemin="0" aria-valuemax="100">'.$porcenpedi.'%</div>
											</div>
											<hr class= "hrgeneral">
										</div>
							        </div>

							        <div class="row">
							            <div class="col txtizq txtDIGELASA">
							                <h6>Facturas :</h6>
							            </div>
							            <div class="col txtder txtDIGELASA">
							                <h6>Avance:</h6>
							            </div>
							            
							        </div>
							        <!-- detalle de facturas agregadas -->
							        ';
						$scriptbusqueda=$scriptbusqueda. '$("#'.$anteriorgrupo.'").attr("databus","'.$cadenabusq.'");';
						$anteriorgrupo=$idagrupa;
						$detcompleto='';
						$cadenabusq='';
					}
					if ($cunicoant!= $cunico){
						$cadenabusq= $cadenabusq . $cunico.$pedido;
						$xdetalle= $xdetalle .'
							<div class="row">
					            <div class="col txtizq txtDIGELASA">
					                <h4> '.$cunico.'</h4>
					            </div>
					            <!--
					            <div class="col txtder txtDIGELASA">

					                <h4> '.$factura.'</h4>
					            </div> -->
					            <div class="col txtder txtDIGELASA">
						            <div class="progress">
									  <div class="progress-bar  bg-success" role="progressbar" style="width: '.$PorceFact.'%;" aria-valuenow="'.$PorceFact.'" aria-valuemin="0" aria-valuemax="100">'.$PorceFact.'%</div>
									</div>
								</div>
					        </div>';

						$cunicoant= $cunico;
					}
					$xdetalle= $xdetalle .'';
				}
				$xdetalle= $xdetalle .'
							<!-- fin detalle de facturas agregadas-->						       
							        <div class="row">
							            <div class="col txtizq txtDIGELASA">
							                <h6></h6>
							                <h6></h6>
							            </div>
							            <div class="col txtder  txtDIGELASA">
							                <span class="text-muted">Estatus:</span>
							                <h6>Sin finalizar</h6>
							            </div>
							        </div>
							    </div>
							</div>';
							$scriptbusqueda=$scriptbusqueda. '$("#'.$anteriorgrupo.'").attr("databus","'.$cadenabusq.'");';
			} 

			
			$json['detalle']=$xdetalle;
		} 
		else {

			$json['msj']     = dbGetErrorMsg();
			$json['success'] = false;
		}
		$pietab          = '
				<script type="text/javascript">
					$(document).ready(function(){ $("#menug").load("menu.php"); });
				</script>	
				 <script type="text/javascript">
				 	$(document).ready(function() {
				 		'.$scriptbusqueda.'
						for(var i in cunicolist){
						   $("#"+cunicolist[i]).addClass("seleccionado");
						}
					});
				 </script>
				 ';
			$json['detalle'] = $enc_superior.' <div id="detallegrupo">'. $xdetalle . '</div>' . $pietab .'' . $conteoculto. ' ' . initws();
		echo json_encode($json);
		break;
	case 33: // DETALLE de ordenes pendientes  PACKING
		$json            = array();
		$detalle         = ''; 
		$detallepadre	 = '';
		$cantidadtotal	 = 0;
		$xdetallepadre   = '';
		$libs            = new db();
		$json['msj']     = 'Detalle de Salida';
		$json['success'] = true;	
		$_SESSION['clasempresa']='INFASA';
		$xcontador = 0;
		if (isset($_POST['pedidopacking'])) { 
			$_SESSION['pedido'] = $_POST['pedidopacking'];
		} 
		//$_SESSION['grupopicking']=324; // provisional
		//privisional
		//$_SESSION['pedido'] ='';
							
		$resultado = $libs->GetdetalletareaHD('S2','','',$_SESSION['pedido'],0,0,0,0);
		//$resultado = $libs->getGrupoPicking('E1', $_SESSION['grupopicking'],'','','','',''); // sustituir por función real
		if ($resultado == true) {
			$json['msj']             = 'Datos generados';
			$_SESSION["vistaactual"]=12;
			$encabezado              = "<h3 CLASS= txt" . $_SESSION['clasempresa'] . ">Orden #  ". $_SESSION['pedido']." </h3>";
			$idCarretaA           = '';
			$loteA					 = '';
			$codigoA				 = '';
			$cunicoA 		 = '';
			$xdetalle 				='';
			while ($obj = sqlsrv_fetch_object($resultado)) {
				$cunico     = trim($obj->cunico);
				//$pedido     = trim($obj->pedido);
				$codigo         = trim($obj->codigo);
				$lote           = ($obj->lote);
				if ($lote != $loteA) {
					$cantidadtotal= 0; 
				} 
				$fecha          = $obj->fecha;
				$OrdenTarea     = $obj->OrdenTarea;
				$cantidad       = $obj->cantidad;
				$cantidadtotal	= $cantidadtotal + $cantidad;
				$habilitado     = $obj->habilitado;
				$idposestante   = $obj->idposestante;
				$vence          = $obj->Vence;
				$nombre         = trim($obj->nombre);
				//$Posicion       = trim($obj->Posicion);
				$Posicion       = '';
				$preciopub      = $obj->preciopub;
				$taked			= $obj->taked;	// si ya fue piqueado
				$taked2			= $obj->taked2;	// si ya fue empacado.
				$idCarreta		= $obj->idCarreta;
				$idAgrupa		= $obj->idAgrupa;
				//$acceso			= $obj->acceso;
				$acceso			= '';
				//$Area			= $obj->Area;
				$Area			= '';
				//$tipoproducto	= $obj->formulaf;
				$tipoproducto	= '';

				$ordenCarreta	= $obj->ordenCarreta;
				$Contenedor		= $obj->Contenedor;
				//$Posicioncomp =trim($obj->Area).' '. trim($obj->Posicion);
				$Posicioncomp ='';
				$xcontador      = $xcontador + 1;
				///************* DESABILITANDO LINEAS DE PEDIDO POR PICKER************
				$classpicker     = '';
				if ($taked == 1) { 	$classpicker     = 'pickeado';	} 
				else { $classpicker     = ''; }
				//************* separador************
				$separador      = '';
				$claseprioridad = '';
				/*if ($cunico != $cunicoA) {
					$cunicoA = $cunico;
					$separador     = '<hr class= "separador  contenedorsep hr' . $_SESSION['clasempresa'] . ' facturasep text-left"  tipoproducto="' . $cunico . '"></br>';
					$detalle = $detalle . $separador;
				} */
				// si tipo producto cambia su valor, se genera una división <hr>
				if ($idCarreta != $idCarretaA) {
					$idCarretaA = $idCarreta;
					$separador     = '<hr class= "separador hr' . $_SESSION['clasempresa'] . '  '.$Contenedor.'sep" tipoproducto="' . $idCarreta . '" idCarreta= "'.$idCarreta.'">';
					$detalle = $detalle . $separador;
				} 
				$claseprioridad = '';
				$xdetalle       =  '
				  	<div class="btncargar_grupos container card mb-3  margen15 semitransparente '.$classpicker.' padre'.$lote.$codigo.'" ordentarea= "' . $OrdenTarea . '" cunico="' . $cunico . '" id="' . $OrdenTarea . '" codprod= "' . $codigo . '" taked= "' . $taked . '" lote= "'.$lote.'" >
				      <div class="row " >
				        <div class="col">
				          <div class=" txtizq"><h5 class="text-info"> <span class="fas fa-search"></span>' . $Posicioncomp . '</h5></div>
				        </div>
				        <div class="col">
				          <div class=" txtder"><h5 class="text-primary">V: ' . $vence . '</h5></div>
				        </div>
				      </div>
				      <div class="row">
				        <div class="col txtizq">
				          <h6><span class="text-mutedx">Producto :</span></h6>
				        </div>
				        <div class="col txtder">
				          <h6> [' . $codigo . ']</h6>
				        </div>
				      </div>
				      <div class="row">
				        <div class="col txtizq">
				          <h4> ' . $nombre . '</h4>
				        </div>
				      </div>
				      <div class="row">
				        <div class="col txtizq">
				          <h5><span class="text-mutedx">Cantidad: </span>' . $cantidad . ' </h5>
				        </div>
				        <div class="col txtder">
				          <h5><span class="text-mutedx">Q. </span>' . $preciopub . '</h5>
				        </div>
				        <div class="col txtder">
				          <h5><span class="text-mutedx "> </span>L:' . $lote . '</h5>
				        </div>
				      </div>
				    </div>';							
				$detalle        = $detalle . ' ' . $xdetalle ;
			} 
			$pietab          = '
				<button type="button" class="btn btn-primary btn-lg btn-block optgrande  " id="btn_finalizarpedido" descripcion="Finalizar pedido">
					<span class="fas fa-paper-plane"></span> Finalizar Pedido 
				</button>
				<!-- <script type="text/javascript" src="js/ajax6.js"></script> -->
				<script type="text/javascript">
					$(document).ready(function(){ $("#menug").load("menu.php"); });
				</script>	
				 <script type="text/javascript">
				 	$(document).ready(function() {
					 	$("body").removeClass("bgINFASA");
	                    $("body").removeClass("bgDIGELASA");
	                    $("body").addClass("bg' . $_SESSION['clasempresa'] . '");
					 	$(".botonF1").hover(function(){
						  $(".btn2").addClass("animacionVer");
						});
						$(window).scrollTop(0);
					});
				 </script>
				 ';
			$json['detalle'] = $encabezado . $detalle . $pietab . ' ' . initws();
		} 
		else {
			$json['msj']     = dbGetErrorMsg();
			$json['success'] = false;
		}
		echo json_encode($json);
		break;

	case 40: // existencias	
			$json = array();
			$json['msj']     = 'Finalizada';
			$json['success'] = TRUE;
			$detalle = '';
			$codigo='0';
			$libs = new db(); //carga de funciones del modelo
			$x=0;
			$xcontador = 0;
			
					if (isset($_POST['tipoop'])) {$_SESSION['estado'] = $_POST['tipoop']; }
					if (isset($_POST['codigobarra'])) { $_SESSION['codigobarra'] = $_POST['codigobarra'];}
					if (isset($_POST['descripcion'])) { $_SESSION['proceso'] = $_POST['descripcion'];}
					if (isset($_POST['tipomov'])) { $_SESSION['tipomov'] = $_POST['tipomov'];}


						$enc_superior = '
						<div id= "btnCodigoexistencia" class=" container-fluid container card mb-3  margen15 " style="padding-top: 15px;" >
						<div class="row ">
							<div class="col">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
								</div>
								<input autofocus id= "txtBuscapedido"  value="" type="number" class="form-control" placeholder="Posicion" aria-label="Codigo de producto" aria-describedby="basic-addon1">
							</div>	
							</div>							
						</div>
						</div>';
					$resultado = $libs->getexistencias($_SESSION['estado'], $_SESSION['codigobarra']);
					if ($resultado==true) 

					{

												$_SESSION["vistaactual"] = 15;
											
												$conteoculto = '
												<div id= "btnCodigoexistencia" class=" container-fluid container card mb-3  margen15 " style="padding-top: 15px;" >
												<div class="row ">
													<div class="col">
													<div class="input-group mb-3">
														<div class="input-group-prepend">
														<span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
														</div>
														<input autofocus id= "txtBuscapedido"  value="" type="text" class="form-control" placeholder="Posicion" aria-label="Codigo de producto" aria-describedby="basic-addon1">
													</div>
													
													</div>
													
												
												</div>
												</div>';
												
										if (!sqlsrv_has_rows($resultado)) {
											$json['msj']             = 'POSICION VACIA';

																$encabezado              = '<h3 class= "padre">&ldquo;ESCANEAR CODIGO&rdquo; </h3>';
																$xmensajebtn='POSICION VACIA';
									
											} 
											else 	{

														
														$json['msj']             = 'Datos generados';
														$_SESSION["vistaactual"] =15;
														$encabezado              = '<h3 class= "padre"></h3>
														<div class="container " ><table class="table">
															<thead class="thead-dark">
																<tr>
																<th scope="col" >#</th>
																<th scope="col">Código</th>
																<th scope="col" class="txtizq">Nombre</th>
																<th scope="col" class="txtizq">Existencia</th>
																<th scope="col" class="txtizq">Lote</th>
																</tr>
															</thead>
															<tbody> </div>
														';
										
										
														while ($obj = sqlsrv_fetch_object($resultado)) 
														{
															
															$x= $x+1;
															$POSICION 	  = $obj->POSICION;
															$xmensajebtn='<h4>'.$POSICION.' </h4>';
															$CODIGOSALE  	  = trim($obj->CODIPRESEN);
															if(empty($CODIGOSALE)){
																$encabezado              = '<h3 class= "padre">&ldquo;ESCANEAR CODIGO&rdquo; </h3> <br> <h4>POSICION VACIA</H4>';
																//$xmensajebtn='POSICION VACIA';
																

															}else{
																$NOMBRE  	  = $obj->NOMBRE;
															$EXISTEN 	  = $obj->EXISTEN;
															$LOTE 	  = $obj->LOTE;
															$xdetalle = '
																<tr>
																<th scope="row">'.$x.'</th>
																<td>'.$CODIGOSALE.'</td>
																<td>'.$NOMBRE.'</td>
																<td>'.$EXISTEN.'</td>
																<td>'.$LOTE.'</td>
																</tr>';
															$detalle= $detalle.$xdetalle;

															}
															
														} 
													}
			
														$pietab          = '
															<script type="text/javascript">
															$(document).ready(function(){
																$( "#txtBuscapedido" ).focus();
															//	limpiar();

																$("#menug").load("menu.php"); 
																$( "#txtBuscapedido" ).focusout(function() {
																	var numero=0;
																	numero = $( "#txtBuscapedido" ).val();
																	 var codigo = parseInt(numero);
																		if(isNaN(codigo)){
																			limpiar();
																			alertify.error("Ingrese Una Posicion Valida");

																		}
																		else{	
																	console.log("focus out bien");
																		$.ajax({ async:true,
																		url:"controlador.php?action=40",
																		type: "post",
																		data: {
																		"codigobarra":codigo                                       
																			   },
																		dataType: "json",
																		success: function(data) {
																			if (data.success == true) {
																				$("#contenidopg_oculto").html(data.encabezado);
																				   $("#contenidopg").html(data.detalle);
																				   limpiar();
																				
																							if(data.msj=="POSICION VACIA"){
																																	//	alertify.error(data.msj);
																								}else{
																									console.log(data.msj);
																																	//	alertify.success(data.msj); 
																										}
																																		
																						} else {
																								alertify.error("aqui en el ajax del 40");
																								}
																		},
																		error: function(jqXHR, textStatus, error) {
																					//	alertify.error("INGRESE UNA POSICION VALIDA");
																					
																		}
																												
																		});
																	}
															});
													
																											
																		$("#txtBuscapedido").keypress(function(e) {
																		var code = (e.keyCode ? e.keyCode : e.which);
																			if(code==13){
																				var numero=0;
																				numero = $( "#txtBuscapedido" ).val();
																					var codigo = parseInt(numero);
																					if(isNaN(codigo)){
																						limpiar();
																						alertify.error("Ingrese Una Posicion Valida");
																						codigo=0;
																					}
																					
																				console.log("enter  bien");
																			
																			$.ajax({ async:true,
																					url:"controlador.php?action=40",
																					type: "post",
																					data: {
																							"codigobarra":codigo                                       
																							},
																					dataType: "json",
																					success: function(data) {
																						if (data.success == true) {
																						
																							$("#contenidopg_oculto").html(data.encabezado);
																							   $("#contenidopg").html(data.detalle);
																							
																								if(data.msj=="POSICION VACIA"){
																										//alertify.error(data.msj);
																								}else{
																										console.log(data.msj);
																										//alertify.success(data.msj); 
																								}								
																							   
																								} else {
																										alertify.error("aqui en el ajax del 40");
																								}
																								limpiar();
																					},
																					error: function(jqXHR, textStatus, error) {
																								alertify.error("INGRESE UNA POSICION VALIDA");
																							
																					}
																					});
																												
																				}
																			
																			}).trigger(jQuery.Event("keypress", { keycode: 8 }));
																			
																		
																			function limpiar(){
																						
																							//$("#txtBuscapedido").val(0);
																							$( "#txtBuscapedido" ).focus();
																													  }
														});	
															</script>	
															
															';
									
					}
					else {
						$json['detalle']     = "INGRESE";
						$json['success'] = true;
						}

		$json['detalle']=$enc_superior . $encabezado . $detalle .$pietab.$xmensajebtn ;

		echo json_encode($json);
		
			
			break;
	case 99: // encabezado grupos de picking
		$json            = array();
		$detalle         = '';
		$detallefac         = '';
		$detcontenedor         = '';
		$cadenabusq		= '';
		$cunicoant		= 0;
		$libs            = new db();
		$json['msj']     = 'Detalle de Grupos generados';
		$json['success'] = true;
		$xdetalle='';
		$scriptbusqueda='';
		if (isset($_POST['tipoop'])) {$_SESSION['estado'] = $_POST['tipoop']; } 
		if (isset($_POST['descripcion'])) { $_SESSION['proceso'] = $_POST['descripcion'];} 
		if (isset($_POST['tipomov'])) {$_SESSION['tipomov'] = $_POST['tipomov'];} 
		$xcontador = 0;
		$resultado = $libs->getEncabezadosgrupo($_SESSION['estado'],$_SESSION['usuario']);
		//$resultado = $libs->getEncabezadosgrupo('E0',20059);
		if ($resultado) {

			$enc_superior='
			<div class=" container card mb-3  margen15 " style="padding-top: 15px;" >
	          <div class="row ">
	            <div class="col">
	              <div class="input-group mb-3">
	                <div class="input-group-prepend">
	                  <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
	                </div>
	                <input id= "txtBuscapedido" type="text" class="form-control" placeholder="Ubicar pedido" aria-label="Ubicar Pedido" aria-describedby="basic-addon1">
	              </div>
	              
	            </div>
	            <div class="col">
	                 <button type="button" class="btn btn-block  btn-success" id= "btnnuevogrupo">Nuevo.Grupo</button>
	            </div>
	          </div>
	        </div>';
			$conteoculto= '
			<div id="modelonuevogrupo" class="oculto">
				<div class="btncargartarea_nuevogrupo container card mb-3 DIGELASA margen15 " empresa="DIGELASA" grupo = "0" id= "modelogruponuevo" >
		          <div class="row ">
		            <div class="col">
		              <div class=" txtizq"><h3 class="text-info">Grupo ID: #1454</h3></div>
		            </div>
		            <div class="col">
		              <div class=" txtder "><h5 class="text-primary">Inicio: 08/08/2019</h5></div>
		            </div>
		          </div>
		          <div class="row">
		            <div class="col txtizq txtDIGELASA">
		              <h6>Facturas :</h6>
		            </div>
		          </div>
		          <!-- linea 1-->
		          <div class="row">
		            <div class="col txtizq txtDIGELASA">
		              <h6></h6><h6> </h6>
		            </div>
		            <div class="col txtder  txtDIGELASA">
		              <span class="text-muted">Estatus:</span><h6>En proceso</h6>
		            </div>
		          </div>
		        </div>
		    </div>'; // modelo oculto de nuevo grupo.
		    if (!sqlsrv_has_rows($resultado)) {
				$encabezado              = "
				<div id= 'detallegrupo'>
				<h3>&ldquo;Sin grupos pendientes&rdquo; </h3>
				</div>";
				$_SESSION["vistaactual"] = 8; // punto de anclaje, si se actualiza explorador
			}
			else {
				$anteriorgrupo= 0;
				$grupoagregar='';
				$cunicoant=0;
				$json['msj']             = 'Datos generados';
				$_SESSION["vistaactual"] = 8;
				$encabezado              = "<h3>" . $_SESSION['proceso'] . "</h3>";
				$detcompleto='';
				
				while ($obj = sqlsrv_fetch_object($resultado)) {
					$idagrupa   = ($obj->idAgrupa);		
					$cunico   	= ($obj->cunico);		
					$iduser		= ($obj->idUser);	
					$inicio   	= ($obj->INICIO);	
					$iniciogt   	= ($obj->INICIO);
					$iddetagrupa= ($obj->idDetAgrupa);
					$pedido   	= ($obj->pedido); 
					$idCarreta= ($obj->idCarreta);
					$xcontador  = $xcontador + 1; 
					
					if ($anteriorgrupo!= $idagrupa){
						/**********************************************/
						if ($anteriorgrupo== 0){
							// no agrega el pie al principio
						}
						else{
							// agregar el pie
							$xdetalle= $xdetalle .'
							</div>
							<!-- fin detalle de facturas agregadas-->						       
							        <div class="row">
							            <div class="col txtizq txtDIGELASA">
							                <h6></h6>
							                <h6></h6>
							            </div>
							            <div class="col txtder  txtDIGELASA">
							                <span class="text-muted">Estatus:</span>
							                <h6>Sin finalizar</h6>
							            </div>
							        </div>
							    </div>
							</div>
							';
						}
						$xdetalle=$xdetalle.'
							
							    <div id= "'.$idagrupa.'" class="btncargartarea_seleccionagrupo container card mb-3 DIGELASA margen15 " empresa="DIGELASA" idAgrupa="'.$idagrupa.'" >
							        <div class="row ">
							            <div class="col">
							                <div class=" txtizq">
							                    <h3 class="text-info">Grupo ID: #'.$idagrupa.'</h3></div>
							            </div>
							            <div class="col">
							                <div class=" txtder ">
							                    <h5 class="text-primary">Inicio: '.$iniciogt.'</h5></div>
							            </div>
							        </div>
							        <div class="row">
							            <div class="col txtizq txtDIGELASA">
							                <h6>Facturas :</h6>
							            </div>
							            <div class="col txtder txtDIGELASA">
							                <h6>Pedido :</h6>
							            </div>
							            <div class="col txtder txtDIGELASA">
							                <h6>Contenedores :</h6>
							            </div>
							        </div>
							        <!-- detalle de facturas agregadas -->
							        <div class="row">';
						$scriptbusqueda=$scriptbusqueda. '$("#'.$anteriorgrupo.'").attr("databus","'.$cadenabusq.'");';
						$anteriorgrupo=$idagrupa;
						$detcompleto='';
						$cadenabusq='';
					}
					if ($cunicoant!= $cunico){
						$cadenabusq= $cadenabusq . $cunico.$pedido;
						$xdetalle= $xdetalle .'
					            <div class="col txtizq txtDIGELASA">
					                <h4> '.$cunico.'</h4>
					            </div>
					            <div class="col txtizq txtDIGELASA">
					                <h4> '.$pedido.'</h4>
					            </div>';

						$cunicoant= $cunico;
						//echo $detcontenedor.'</br>';
						//$detcontenedor='';
					}

					//echo $idCarreta.'<br>';
					$xdetalle= $xdetalle .'
					<row>
						<div class="col txtizq txtDIGELASA">
					         <h4> '.$idCarreta.'</h4>
					    </div>
					</row>';
				}
				$xdetalle= $xdetalle .'
							</div>
							<!-- fin detalle de facturas agregadas-->						       
							        <div class="row">
							            <div class="col txtizq txtDIGELASA">
							                <h6></h6>
							                <h6></h6>
							            </div>
							            <div class="col txtder  txtDIGELASA">
							                <span class="text-muted">Estatus:</span>
							                <h6>Sin finalizar</h6>
							            </div>
							        </div>
							    </div>
							</div>
							';
							$scriptbusqueda=$scriptbusqueda. '$("#'.$anteriorgrupo.'").attr("databus","'.$cadenabusq.'");';


			} 

			
			$json['detalle']=$xdetalle;
		} 
		else {

			$json['msj']     = dbGetErrorMsg();
			$json['success'] = false;
		}
		$pietab          = '
				<script type="text/javascript">
					$(document).ready(function(){ $("#menug").load("menu.php"); });
				</script>	
				 <script type="text/javascript">
				 	$(document).ready(function() {
				 		'.$scriptbusqueda.'
						for(var i in cunicolist){
						   $("#"+cunicolist[i]).addClass("seleccionado");
						}
					});
				 </script>
				 ';
			$json['detalle'] = $enc_superior.' <div id="detallegrupo">'. $xdetalle . '</div>' . $pietab .'' . $conteoculto. ' ' . initws();
		echo json_encode($json);
		break;
} 

function dbGetErrorMsg() // obtiene el mensaje devuelto por el controlador de DB cliente
	{
	$retVal = sqlsrv_errors();
	$retVal = $retVal[0]["message"];
	$retVal = ucfirst(strtolower(utf8_encode(preg_replace('/\\[Microsoft]\\[SQL Server Native Client [0-9]+.[0-9]+](\\[SQL Server\\])?/', '', $retVal))));
	return $retVal;
	}
function destruir($sessionVariableName)
	{
	unset($_SESSION[$sessionVariableName]);
	}
function cargaropciones()
	{
		$libs      = new db();
		$resultado = $libs->gettotaldoc();
		if ($resultado == true) {
			while ($obj = sqlsrv_fetch_object($resultado)) {
				$cantingresos  = $obj->INGRESOS;
				$cantdespachos = $obj->EGRESOS;
				$canttraslados = $obj->TRASLADOS;
				$cantarmados   = $obj->TARIMAS;
			} 
		} 
		else {
			$cantingresos  = '?';
			$cantdespachos = '?';
			$canttraslados = '?';
			$cantarmados   = '?';
		}
		return ' 
		<div class="card col-12" style="align-items: center;" >
		  <img class="card-img-top" src="img\0001.png" alt="Card image cap" style="MAX-WIDTH: 45%;">
		  <div class="card-body appfull">
		    <h5 class="card-title">Seleccione el Tipo de Tarea</h5>
		    <p class="card-text">
		     <div class="form-group">
		     	<button type="button" class="btn btn-secondary btn-lg btn-block optgrande btncargararmados pastel1" id="btncargararmados"  tipoop="TA"descripcion="Aramado de tarimas" tipomov="EGRESO">
					<span class= "fas fa-boxes"></span> Armado de tarimas <span class="badge badge-light" id = "cantarmados">' . $cantarmados . '</span>
				</button>

				<button type="button" class="btn btn-secondary btn-lg btn-block optgrande btncargaringresos pastel2" id="btnrevisar" tipoop="TI" descripcion="Ingresos a Bodega" tipomov="EGRESO">
					<span class= "fas fa-pallet"></span> Ingresos a Bodega <span class="badge badge-light" id ="cantingresos">' . $cantingresos . '</span>
				</button>		
				<!--
				<button type="button" class="btn btn-primary btn-lg btn-block optgrande btncargaregresos pastel3" id="btnentregar"  tipoop="E1"descripcion="Egresos de bodega" tipomov="EGRESO">
					<span class= "fas fa-dolly-flatbed"></span> Despachos de bodega <span class="badge badge-light" id = "cantdespachos">' . $cantdespachos . '</span>
				</button> -->

				<button type="button" class="btn btn-primary btn-lg btn-block optgrande btncargaregresos pastel3" id="btnentregar"  tipoop="E0"descripcion="Egresos de bodega2" tipomov="EGRESO">
					<span class= "fas fa-dolly-flatbed"></span> Despachos de bodega # 2 <span class="badge badge-light" id = "cantdespachos">' . $cantdespachos . '</span>
				</button>

				<button type="button" class="btn btn-primary btn-lg btn-block optgrande btncargaregresos pastel3" id="btnentregar"  tipoop="PK" descripcion="Packing" tipomov="EGRESO">
					<span class= "fas fa-dolly-flatbed"></span> Packing <span class="badge badge-light" id = "cantdespachos">' . $cantdespachos . '</span>
				</button>

				<button type="button" class="btn btn-primary btn-lg btn-block optgrande btncargartraslado pastel4" id="btntraslado"  tipoop="TR"descripcion="Traslados entre bodegas" tipomov="EGRESO">
					<span class= "fas fa-exchange-alt"></span> Traslados entre bodegas <span class="badge badge-light" id = "canttraslados">' . $canttraslados . '</span>
				</button>
				<button type="button" class="btn btn-primary btn-lg btn-block optgrande btnexistencias pastel4" id="btnexistencia"  tipoop="SA"descripcion="Ver existencias de Inventario" tipomov="CONSULTA">
					<span class= "fas fa-exchange-alt"></span> Existencias de Producto <span class="badge badge-light" id = "btnexistencias"></span>
				</button>
				<hr/>
				<button type="button" class="btn btn-danger btn-lg btn-block optgrande btnsalir" id= "btnsalir"><span class= "fas fa-times-circle"></span> Cerrar sesión
				</button>
			</div>
		  </div>
		</div>
		<!-- <script type="text/javascript" src="js/ajax6.js"></script> -->

		<div class="contenedor">
			<button class="btnrefresh botonF1">
			   <span class="fas fa-sync-alt"></span> 
			</button>
		</div>
		<style type="text/css">
			.botonF1{
				width:60px;
				height:60px;
				border-radius:100%;
				background:#007bff;
				right:0;
				bottom:0;
				position:fixed;
				margin-right:16px;
				margin-bottom:16px;
				border:none;
				outline:none;
				color:#FFF;
				font-size:2em;
				box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
				transition:.3s;  
				/*background-image: url(uni.png)!important;
				background-position: center;*/
			}
			.animacionVer{
				transform:scale(1);
			}
		 </style>
		 <script type="text/javascript">
		 	$(document).ready(function() {
		 		
			 	$(".botonF1").hover(function(){
				  $(".btn2").addClass("animacionVer");
				})
			});
		 </script>
		';
	}
function initws() // suscribe a websocket
	{
		$dato = '';
		if (isset($_SESSION['estado'])) {
			$dato = '
				<script type="text/javascript">	
					$(document).ready(function() { 
						if (typeof(socket)=="undefined"){
							console.log("WS creado");
					        websocket("' . $_SESSION['estado'] . '");
					    }
					    else{
					    	websocket("' . $_SESSION['estado'] . '");
					    }
					});
				</script>';
		}
		return $dato;
	}
function clonadox($variable){
	//private $xvar;
	$xvar = $variable;
	//echo $xvar;
	return $xvar;
	}
?>
