<?php
	session_name('app_caotico');
	if(!isset($_SESSION)){ session_start(); } 
	if (!isset($_SESSION["cunicolist"])) {
		$_SESSION['cunicolist'] = '[]';
	}
	//require_once ("/phpwee2/phpwee.php");
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script data-pace-options='{ "ajax": false }' src="js/pace.min.js"></script>
		<link rel="stylesheet" href="css/pace-theme-corner-indicator.css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="icon.ico">
		<title>Inventario Caótico.</title>
		
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/fontawesome-all.min.css" rel="stylesheet">
		<!--<link href="css/infasa.css" rel="stylesheet">-->
		<style type="text/css">
			html,body{height:100%}body{display:-ms-flexbox;display:-webkit-box;display:flex;-ms-flex-align:center;-ms-flex-pack:center;-webkit-box-align:center;-webkit-box-pack:center;justify-content:center;padding-top:40px;padding-bottom:40px;background-color:#ffffff}.dimensiones_app{padding-top:30px;max-width:1024PX;width:100%; "
			}
			.botoncontenedor{
				background-color: black;
			    color: white;
			    margin-bottom: 5px;
			    box-shadow: 2px 2px 7px rgba(255, 255, 255, 1);
			    border-radius: .5em;
			}
			.hrdelgado{
				width: 75%
				margin: 0em !important;
				background-color: rgb(255, 255, 0);
			}
			.optgrande{
				height: 6rem;
				}
			.table {
				width: 100%; 
				padding-left: 10%;
				padding-right: 10%;
				}
			.fondopelota {
				background-image: url(img/pelo.png)
				}
			.form-signin {
			width: 100%;
			max-width: 330px;
			padding: 15px;
			margin: 0 auto;
			}
			.form-signin .checkbox {
			font-weight: 400;
			}
			.form-signin .form-control {
			position: relative;
			box-sizing: border-box;
			height: auto;
			padding: 10px;
			font-size: 16px;
			}
			.form-signin .form-control:focus {
			z-index: 2;
			}
			.form-signin input[type="email"] {
			margin-bottom: -1px;
			border-bottom-right-radius: 0;
			border-bottom-left-radius: 0;
			}
			.form-signin input[type="password"]{margin-bottom:10px;border-top-left-radius:0;border-top-right-radius:0}@media (max-width:800px){
				
				body{display:-ms-flexbox;font-size: 9px;display:-webkit-box;align-items:baseline} .form-group{
					font-size:9px;
				} .tablaresultados{padding-left:6%;padding-right:6%}.tablaresultados3{padding-left:6%;padding-right:6%}.tablaresultados2{padding-left:6%;padding-right:6%}.tabla_completa{width:100%}.table{width:100%}.navbar-toggler{font-size:.9rem}}// Medium devices (tablets,768px and up) @media (min-width:801){body{display:-ms-flexbox;display:-webkit-box;display:flex;-ms-flex-align:center;-ms-flex-pack:center;-webkit-box-align:center;align-items:center;-webkit-box-pack:center;justify-content:center;padding-top:40px;padding-bottom:40px;background-color:#d23e3e}.navbar-toggler{font-size:1.25rem}.tablaresultados{padding-left:1%;padding-right:1%;width:100%}.tablaresultados2{padding-left:1%;padding-right:1%;width:100%}.tabla_completa{width:100%}.table{width:100%}}
			.custom-checkbox {
			min-height: 1rem;
			padding-left: 0;
			margin-right: 0;
			cursor: pointer;
			margin-top: 10px;
			}
			.custom-checkbox .custom-control-indicator {
			content: "";
			display: inline-block;
			position: relative;
			width: 52px;
			height: 57px;
			background-color: #818181;
			border-radius: 15px;
			margin-right: 0px;
			-webkit-transition: background .3s ease;
			transition: background .3s ease;
			vertical-align: middle;
			box-shadow: none;
			}
			.custom-checkbox .custom-control-indicator:after {
			content: "";
			position: absolute;
			display: inline-block;
			width: 22px;
			height: 57px;
			background-color: #f1f1f1;
			border-radius: 22px;
			box-shadow: 0 1px 3px 1px rgba(0, 0, 0, 0.4);
			left: -2px;
			-webkit-transition: left .3s ease, background .3s ease, box-shadow .1s ease;
			transition: left .3s ease, background .3s ease, box-shadow .1s ease;
			}
			.custom-checkbox .custom-control-input:checked ~ .custom-control-indicator {
			background-color: #3592f7;
			background-image: none;
			box-shadow: none !important;
			}
			.custom-checkbox .custom-control-input:checked ~ .custom-control-indicator:after {
			background-color: #ffffff;
			left: 32px;
			}
			.custom-checkbox .custom-control-input:focus ~ .custom-control-indicator {
			box-shadow: none !important; 
			}
			.editabletabla{
			display: block;
			width: 100%;
			}
			.xlistamat{
			height: 100px;
			}
			.xlistnota{
			height: 100%;
			}
			.cln1{
			border-style: dashed;
			border-width: 3px;
			border-bottom:none;
			}
			.cln2{
			border-style: dashed;
			border-width: 3px;
			border-top: none;
			}
			.clprio{
			border-style: dashed;
			border-width: 3px;
			background: repeating-linear-gradient( 
			135deg, 
			rgba(255, 255, 255, 0.3) 20px, 
			rgba(255, 255, 255, 0.3) 50px, 
			rgb(211, 235, 255) 40px, 
			rgb(211, 235, 255) 60px );
			}
			.oculto{
			display: none;
			}
			.fuentepeque{
			font-size: 0.9em;
			}
			.btn-warning {
			color: #212529;
			background-color: #ffd760;
			border-color: #ffc107;
			}
			.appfull{
			
			}
			.txtizq{
			text-align: left;
			padding-left: 15px;
			}
			.txtder{
			text-align: right;
			padding-right: 15px;
			}
			.tareaTI{
			border-radius: 10px;background: rgb(214, 221, 232);
			}
			.tareaTA{
			border-radius: 10px;background: rgb(232, 223, 214);
			}
			.desabilitado{
			pointer-events: none;
			opacity: 0.4;
			}
			.vertical {
			writing-mode: vertical-lr;
			transform: rotate(90deg);
			text-align: center;
			}
			.bg-card {
			background-size: contain;
			background-position: right;
			}
			.bg-holder {
			position: absolute;
			width: 100%;
			min-height: 100%;
			top: 0;
			left: 0;
			background-size: cover;
			background-position: center;
			overflow: hidden;
			will-change: transform,opacity,filter;
			-webkit-backface-visibility: hidden;
			backface-visibility: hidden;
			background-repeat: no-repeat;
			z-index: 0;
			}
			.fondo1{
			background-image:url(img/corner-2.png);
			top: 0;
			left: 0;
			background-size: cover;
			background-position: center;
			overflow: hidden;
			will-change: transform,opacity,filter;
			-webkit-backface-visibility: hidden;
			backface-visibility: hidden;
			background-repeat: no-repeat;
			z-index: 0;
			}
			.INFASA{
			top: 0;
			left: 0;
			background-size: cover;
			background-position: center;
			overflow: hidden;
			will-change: transform,opacity,filter;
			-webkit-backface-visibility: hidden;
			backface-visibility: hidden;
			background-repeat: no-repeat;
			z-index: 0;
			background-color: #ffd760;
			border-color: #ffc107;
			border-radius: .5rem;
			}
			.INFASADET{
			background-color: rgb(244, 241, 241);	
			}
			.DIGELASA{
			top: 0;
			left: 0;
			background-size: cover;
			background-position: center;
			will-change: transform,opacity,filter;
			-webkit-backface-visibility: hidden;
			backface-visibility: hidden;
			background-repeat: no-repeat;
			z-index: 0;
			background-color: rgb(52, 58, 64);
			border-radius: .5rem;
			}
			.bgDIGELASA{
			background-color: rgb(52, 58, 64);
			}
			.bgINFASA{
			background-color: #ffd760;
			}

			#clasefacturas{
			background-color: #ff9b60;
			}

			.txtDIGELASA{
			color:white;
			}
			.txtINFASA{
			}
			.DIGELASADET{
			background-color: rgb(218, 243, 237);
			}
			.margen15{
			width: 95%;
			}
			.semitransparente{
			background-color: rgba(255, 255, 255, 0.75);
			}
			.card{
			border-radius: .5rem;	
			}
			.linea {
			margin:0px 20px;
			width:90px;    
			border-top:1px solid #999;
			position: relative;
			top:10px;
			float:left;
			}
			.leyenda {
			font-weight:bold;
			float:left;
			}
			hr.separador {
			border: 0;
			height: 1px;
			width: 73%;
			}
			hr.separador:after {
			display: inline-block;
			position: relative;
			top: -0.9em;
			padding: 0 0.25em;
			background: #343a40;
			}
			.hrINFASA{
			background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
			}
			.hrINFASA:after {
			background: #ffd75f!important;
			}
			hr.hrDIGELASA{
			background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(255, 255, 255, 0.75), rgba(0, 0, 0, 0))!important;
			color: white;
			}
			hr.hrDIGELASA:after {
			background: #343a40;
			color: white!important;
			}
			.alertify .ajs-footer {
			background: #484d51!important;
			border-top: #646464 1px solid!important;
			border-radius: 0 0 2px 2px!important;
			}

			

			.alertify .ajs-header {
		    color: #fff!important;
		    font-weight: 700!important;
		    background: #484d51!important;
		    border-bottom: #646464 1px solid!important;
		    border-radius: 2px 2px 0 0!important;
			}
			.alertify .ajs-footer .ajs-buttons .ajs-button {
		    background-color: transparent!important;
		    color: #ffd75f!important;
		    border: 0!important;
		    font-size: 14px!important;
		    font-weight: 700!important;
		    text-transform: uppercase!important;
			}
			.alertify .ajs-footer .ajs-buttons .ajs-button.ajs-ok {
			    color: #3593d2!important;
			}
			.alertify .ajs-dialog {
			    background-color: #484d51!important;
			    -webkit-box-shadow: 0 15px 20px 0 rgba(0,0,0,.25)!important;
			    box-shadow: 0 15px 20px 0 rgba(0,0,0,.25)!important;
			    border-radius: 2px!important;
			}
			.alertify .ajs-body {
			    color: #fff!important;
			}
			.enMoney::before {
			    content:"";
			}
			.negMoney {
			    color:red;
			}
			.pickeado{
				color: rgba(0, 0, 0, 0.3803921568627451)!important;
    			background-color: rgba(218, 218, 218, 0.058823529411764705)!important;
			}
			.pickeado h5{
				color: rgba(0, 0, 0, 0.3803921568627451)!important;
    			background-color: rgba(218, 218, 218, 0.058823529411764705)!important;
			}

			.seleccionado{
				border-style: solid;
			    border-color: black;
			    border-width: 1px;
			    filter: invert(100%);
			    -webkit-filter: invert(100%);
			}
			.clasefacturas{
				border-style: solid;
			    border-color: black;
				background-color:#ff9b60;
			}
			.clasefacturasmio{
				border-style: solid;
			    border-color: black;
				background-color:#95f150;
				pointer-events: none;
    opacity: 0.4;
			}
			.icon::before {
			  display: inline-block;
			  font-style: normal;
			  font-variant: normal;
			  text-rendering: auto;
			  -webkit-font-smoothing: antialiased;
			}
			.Canasta::before {
			  font-family: "Font Awesome 5 Free";
			  font-weight: 900;
			  content: "\f291";
			}
			.Carreta::before {
			  font-family: "Font Awesome 5 Free";
			  font-weight: 900;
			  content: "\f07a";
			}
			.Caja::before {
			  font-family: "Font Awesome 5 Free";
			  font-weight: 900;
			  content: "\f059";
			}
			.sincontenedor::before {
			  font-family: "Font Awesome 5 Free";
			  font-weight: 900;
			  content: "\f057 ";
			}
			.Canastasep:after {
			font-family: "Font Awesome 5 Free";
			font-weight: 900;
			content: "\f291" " (" attr(tipoproducto) ")"!important;
			display: inline-block;
			position: relative;
			top: -0.9em;
			padding: 0 0.25em;
			background: #343a40;
			}
			.Carretasep:after {
			font-family: "Font Awesome 5 Free";
			font-weight: 900;
			content: "\f07a" " (" attr(tipoproducto) ")";
			display: inline-block;
			position: relative;
			top: -0.9em;
			padding: 0 0.25em;
			background: #343a40;
			}
			.Cajasep:after {
			font-family: "Font Awesome 5 Free";
			font-weight: 900;
			content: "\f49e" " (" attr(tipoproducto) ")";
			display: inline-block;
			position: relative;
			top: -0.9em;
			padding: 0 0.25em;
			background: #343a40;
			}
			.facturasep:after {	
			content:  "Factura: [" attr(tipoproducto) "]";
			display: inline-block;
			position: relative;
			top: -0.9em;
			padding: 0 0.25em;
			background: #343a40;
			}

			.contenedorsep:after {
			
			content: " (" attr(tipoproducto) ")";
			display: inline-block;
			position: relative;
			top: -0.9em;
			/* font-size: 1.5em; */
			padding: 0 0.25em;
			background: #343a40;
			}
			hr.hrgeneral{
		      background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0))!important;
		      color: white;
		      border-top: 1px solid rgba(255, 255, 255, 0.88)!important;
		      }
		    hr.hrgeneral:after {
		       background: #ffd75f!important;
		      color: white!important;
		      border-top: 1px solid rgba(255, 255, 255, 0.88)!important;
		      }

		    .prioridad {
				    background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);
				    border-style: dashed;
				    border: #ff5a5a;
    				
				}

				 /*INICIA*/
				 #hiddenField {
						height:33px; 
						width:1px;
						position:absolute;
						margin-left:43px;
						margin-top:2px;
						border:none;
						border-width:0px 0px 0px 1px;
						}

						#cursorMeasuringDiv {
						position:absolute;
						visibility:hidden;
						margin:0px;
						padding:0px;
						}

						#hiddenField:focus {
						border:1px solid gray;  
						border-width:0px 0px 0px 1px;
						outline:none;
						animation-name: cursor;
						animation-duration: 1s;
						animation-iteration-count: infinite;
						}

						@keyframes cursor {
							from {opacity:0;}
							to {opacity:1;}
						}

				 /*FINALIZA*/


		</style>
		
	</head>
	<body class="text-center ">
		<div id= menug></div>
		<div class= "dimensiones_app" >
			<div id= "contenidopg" >
			</div>
			<!-- Conteniddo oculto -->
			
			<!-- fin Contenido oculto -->
			<div id= "contenidopg_deshabilitado" class= "desabilitado" >
			</div>
			<div id= "infasapie"><img src="img/001.png" alt="INFASA" width="150"></div>
			</br>
		</div>
		
		<!--------------------------------CUADRO MODAL DETALLE TARIMAS ------------------------------->
		<div id= "cuadromodal_detalle4">
			<div class="modal fade" id="modal_detalletarea4" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<div id="detalle_tarea4" class="modal-body">
						</div>
						
						<div class=" mb-3 " style="width: 90%; align-items: center; align-items: center; align-self: center;">
							<div class="row">
								<div class="col txtizq">
									<button type="button" class="btn btn-secondary btn-block btn-lg" data-dismiss="modal">Cancelar</button>
								</div>
								<div class="col txtder">
									<button type="button" class="btn btn-primary   btn-block btn-lg" id = "btn_guardar_detalle_tarima">Confirmar</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--------------------------------CUADRO MODAL AGREGA # CONTROL-------------------------------->
		<div id= "cuadromodal_detalle">
			<div class="modal fade" id="modal_detalletarea" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<button type="button" class="danger close bg-danger" data-dismiss="modal">&times;</button>
						<div id="detalle_tarea" class="modal-body">
						</div>
						<div class="input-group mb-3" style="width: 90%; align-items: center; align-items: center; align-self: center;">
							<input type="text" class="form-control btn-lg" placeholder="Ubicación" aria-label="txtingresobarra" aria-describedby="basic-addon2" id ="txtingresobarra">
							<div class="input-group-append">
								<button class="btn btn-lg btn-primary" type="button" id = "btn_guardar_posicion">Guardar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--------------------------------CUADRO MODAL detalle traslado-------------------------------->
		<div id= "cuadromodal_detalle3">
			<div class="modal fade" id="modal_detalletarea3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<div id="detalle_tarea3" class="modal-body">
						</div>
						<div class=" mb-3 " style="width: 90%; align-items: center; align-items: center; align-self: center;">
							<div class="row">
								<div class="col txtizq">
									<button type="button" class="btn btn-secondary btn-block btn-lg" data-dismiss="modal">Cancelar</button>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--------------------------------CUADRO MODAL detalle etiquetado-------------------------------->
		<div id= "cuadromodal_detalleetiquetado">
			<div class="modal fade" id="modal_detalletareaetiquetado" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<div id="detalle_tareaetiquetado" class="modal-body">
						</div>
						<div class=" mb-3 " style="width: 90%; align-items: center; align-items: center; align-self: center;">
							<div class="row">
								<div class="col txtizq">
									<button type="button" class="btn btn-secondary btn-block btn-lg" data-dismiss="modal">Cancelar</button>
								</div>
								<div class="col txtizq">
									<button type="button" class="btn btn-primary btn-block btn-lg" id="cmdconfirmaetiqueta" correl codigosale lote>Confirmar</button>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--------------------------------CUADRO TRASLADOS-------------------------------->
		<div id= "cuadromodal_detalle2">
			<div class="modal fade" id="modal_detalletarea2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<div id="detalle_tarea2" class="modal-body">
						</div>
						<div class=" mb-3 " style="width: 90%; align-items: center; align-items: center; align-self: center;">
							<div class="row">
								<div class="col txtizq">
									<button type="button" class="btn btn-secondary btn-block btn-lg" data-dismiss="modal">Cancelar</button>
								</div>
								<div class="col txtder">
									<button type="button" class="btn btn-primary   btn-block btn-lg" id = "btn_guardar_detalle">Confirmar</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--------------------------------CUADRO EGRESOS DE GRUPO------------------------->
		<div id= "cuadromodal_detalleg">
			<div class="modal fade" id="modal_detallegrupo" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<div id="detallegrupo" class="modal-body">
						</div>
						<div class=" mb-3 " style="width: 90%; align-items: center; align-items: center; align-self: center;">
							<div class="row">
								<div class="col txtizq">
									<button type="button" class="btn btn-secondary btn-block btn-lg" data-dismiss="modal">Cerrar</button>
								</div>
								<div class="col txtder">
									<button type="button" class="btn btn-primary   btn-block btn-lg" id = "btn_guardar_detallegrupo" lote="">Confirmar</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--------------------------------CUADRO MODAL AGREGA # de contenedor (canasta, carreta etc)-------------------------------->
	    <div id="cuadromodal2">
	      <div class="modal fade" id="modalcontenedor" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	        <div class="modal-dialog" role="document">
	          <div class="modal-content">
	            <div class="modal-body" id= "lista_contenedores">
	            </div>  
	            <div class="modal-footer">
	              <button type="button" class="btn btn-warning" data-dismiss="modal">
	                Cerrar
	              </button>
	            </div>
	          </div>
	        </div>
	      </div>
	    </div>
		<div id= "jquery_lista"></div>
		<script>
			var tarea_consulta= null;
			var tarea_consulta2= null;
			function closeFullscreen() {
			if (document.exitFullscreen) {
			  document.exitFullscreen();
			} else if (document.mozCancelFullScreen) {
			  document.mozCancelFullScreen();
			} else if (document.webkitExitFullscreen) {
			  document.webkitExitFullscreen();
			} else if (document.msExitFullscreen) {
			  document.msExitFullscreen();
			}
			}
			function openFullscreen() {
			var elem = document.documentElement;        
			if (elem.requestFullscreen) {
			 elem.requestFullscreen();
			} else if (elem.mozRequestFullScreen) { /* Firefox */
			 elem.mozRequestFullScreen();
			} else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
			 elem.webkitRequestFullscreen();
			} else if (elem.msRequestFullscreen) { /* IE/Edge */
			 elem.msRequestFullscreen();
			}
			}
		</script>
		
		<script type="text/javascript" src="js/popper.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/ajax6.js"></script>

		
		<!-- include a theme -->
		<link rel="stylesheet" href="js/alertify/css/themes/default.min.css" />
		<link rel="stylesheet" href="js/alertify/css/alertify.min.css" />
		<script src="js/alertify/alertify.min.js"></script>	
			
		<script type="text/javascript">
			$(document).ready(function(){
				window.clearInterval(tarea_consulta);//nos funciona para poder consultar y cambiar la pagina
				cargapagina();

				tarea_consulta=setInterval(cargapagina, 50000);
				ultimocargado="";
				cunicolist=[];
				cunicolist= <?php  echo $_SESSION['cunicolist']; ?>;
				clasempresa="";
				console.log(cunicolist);
			});
			
		</script>
	</body>
</html>