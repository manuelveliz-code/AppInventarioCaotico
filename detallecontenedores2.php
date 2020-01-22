
<?php
session_name('app_caotico');
if(!isset($_SESSION)){  	session_start();  }

require_once 'data/conexion.php';
require_once 'modelos.php';


$libs            = new db();
//echo 'cunico: '.$_POST['cunico'].'<br>';
//echo 'pedido: '.$_POST['pedido'].'<br>';
//$idDetAgrupa=0;
$xcunico=$_POST['cunico'];
$xpedido=$_POST['pedido'];


$idAgrupa= $_POST['idAgrupa']; // es el detalle de el grupo asignado
// si iddetagrupa viene vacio, se reconsulta con G5
echo $idAgrupa;

/*

if ($idAgrupa==0){
	$resultadoxx = $libs-> getGrupoPicking('G2',$idAgrupa,$_SESSION['usuario'],$xcunico,$xpedido,'','');  // consulta de iddetagrupa
	if ($resultadoxx) {
		while ($obj = sqlsrv_fetch_object($resultadoxx)) {
			$idAgrupa = ($obj->idAgrupa);
			$idDetAgrupa = ($obj->idDetAgrupa);
		}
	}
}


echo $idAgrupa;
echo $idDetAgrupa;
echo $_SESSION['usuario'];
*/
$idDetAgrupa =0;
$xdetallecontenedor='';
$resultado       = $libs->getGrupoPicking('G4', '',$_SESSION["usuario"],'',$xpedido,'','');  // listado de contenedores disponibles
if ($resultado==true)
{
	// detalle de grupo
	$resultadox       = $libs->getGrupoPicking('G6', '','','','','',$idDetAgrupa); // carretillas asignadas a iddetalleagrupa
	if ($resultadox==true)
	{
		if (sqlsrv_has_rows($resultadox)) {
			while( $objX = sqlsrv_fetch_object($resultadox)) {
    			$xdetallecontenedor=$xdetallecontenedor. 
	    		'<li class="list-group-item d-flex justify-content-between align-items-center" id= "Contenedor'. $objX->idCarreta.'">'.$objX->Descripcion.': '.$objX->idCarreta.' 
	    		<button class="btn btn-outline-info " type="button"  idlst= "Contenedor'.$objX->idCarreta.'"><span class= "icon '.$objX->Descripcion.'"></span></button>
	    		<button class="btn btn-outline-danger fas fa-minus cmdeliminarlst" type="button"  idlst= "Contenedor'.$objX->idCarreta.'"></button>
	    		</li>';
			}
		}
		else{
			$xdetallecontenedor='';
		}
	}
	// fin detalle grupo
	$xdetalle="";
	$xpie= "";
	$json['msj']     = 'Datos generados';
	$xdetalle=$xdetalle. '
	<div class="well">
        <div class="row">
			<div class="input-group mb-3 mx-auto">
				<div class="input-group-prepend">
					<div class="dropdown">
			  			<button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 68px;"><i class="fas fa-list-ul"></i> </button>
    					<div class="dropdown-menu">';
    if (sqlsrv_has_rows($resultado)) {
    	while( $obj = sqlsrv_fetch_object($resultado)) {
    		$xdetalle=$xdetalle.  '<a class="dropdown-item selecontenedor '.$obj->descripcion.'" href="#" idCarreta="'.$obj->idCarreta.'" descripcion="'.$obj->descripcion.'">'.$obj->idCarreta.'</a><div class="dropdown-divider"></div>';
    	
    	}
    }	
    else{
    		$xdetalle=$xdetalle.  '<div class="dropdown-divider"></div><a class="dropdown-item selecontenedor sincontenedor" href="#" idCarreta="" descripcion="">Contenedores no disponibles</a><div class="dropdown-divider"></div>';
    	}
    
    
}
    $xdetalle=$xdetalle. '
    					</div>
    				</div>
    			</div>
    			<input type="number" class="form-control" placeholder="Seleccione Contenedor de la lista" aria-label="Número de control" aria-describedby="número de control" id="controlcontenedor"  descripcion="" readonly>
    			<div class="input-group-append"><button class="btn btn-primary fas fa-plus" id="cmdaddcontenedor" type="button"  cunico= "'.$xcunico.'"  pedido= "'.$xpedido.'" idAgrupa="'.$idAgrupa.'"  idDetAgrupa= "'.$idDetAgrupa.'" style="width: 68px;"></button></div>
    		</div>
	  	</div>
	</div>
	<ul class="list-group" id="lista_contenedores_enc">
	    <li href="#" class="list-group-item list-group-item">
	      Contenedores asignados al factura:
	    </li>
	    '.$xdetallecontenedor.'
	  </ul>';


    $xpie=$xpie.  '
	<script type="text/javascript">
		$(document).ready(function() {
			$(".cmdeliminarlst").click(function(evento) {
				var idlisteliminar= $(this).attr("idlst");
				$("#"+idlisteliminar).remove();
			});

			$(".selecontenedor").off("click");
		    $(".selecontenedor").on("click", function(e) {
		        var $contenedor = $(this).attr("idCarreta");
		        var $descripcion = $(this).attr("descripcion");
		        //console.log($contenedor);
		        $("#controlcontenedor").val($contenedor);
		        $("#controlcontenedor").attr("descripcion",$descripcion);

		        /*$.ajax({
		            url: "controlador.php?action=16",
		            type: "post",
		            data: {
		                "rack": $rack
		            },
		            dataType: "json",
		            success: function(data) {
		                $("#contenidopg").html(data.detalle);
		                 alertify.success(data.msj);
		            },
		            error: function(jqXHR, textStatus, error) {
						console.log("error en agregar carretill";
		                alertify.error(error);
		            }
		        });*/    
		    });
		}); 
	</script>
	';
	echo $xdetalle. ' ' . $xpie;
?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#cmdaddcontenedor").click(function(evento) {
			var datonuevoelementodesc= $("#controlcontenedor").attr('descripcion');
			console.log(datonuevoelementodesc);
			var datonuevoelemento= $("#controlcontenedor").val();
			console.log(datonuevoelemento);
			$idDetAgrupa = $(this).attr("iddetagrupa");
			var $idAgrupa = $(this).attr("idAgrupa");
			var $xxcunico = $(this).attr("cunico");
			var $xxpedido = $(this).attr("pedido");
			if (datonuevoelemento>0){
				if ($idDetAgrupa==0){
					$idDetAgrupa= grupoegreso(cunico, pedido, idAgrupa);
					if ($idDetAgrupa>=1){
						// sustituir el IDdetgrupo en el boton de agregar
						$(this).attr("idDetAgrupa",$idDetAgrupa);
						// sustituir el iddetgrupo en el detalle de factura
						$("#"+$xxcunico).attr("idDetAgrupa",$idDetAgrupa)
						// colorear la nueva factura y agregar al listado php
						 $("#"+$xxcunico).addClass("seleccionado");
						// enviar a sesion el nuevo objeto seleccionado
						XPOS= $.inArray($xxcunico, cunicolist);
						//console.log(XPOS);
						
			            if (XPOS<0){
			                cunicolist.push($xxcunico);
			                $("#"+$xxcunico).addClass("seleccionado");
			                clasempresa =  $("#"+$xxcunico).attr("empresa");
			                // enviar g2  grupo, null, cunico, pedido, null...
			                grupoegreso($xxcunico, $xxpedido);
			            }
			            /*else
			            {
			                cunicolist.splice(XPOS,1)
			                $("#"+$xxcunico).removeClass("seleccionado");
			            }
			            */
			            enviarcunicolist(cunicolist);
			            wsenviarcambio(1);
			            console.log(cunicolist);
					}
					else{
						// mensaje de error, no es posible agregar factura
						// no guardar
						// return 0;
					}
				}
				$.ajax({
		            url: "controlador.php?action=27", // opción de agregar contenedores
		            type: "post",
		            data: {
		                "contenedor": datonuevoelemento,
		                "idDetAgrupa": $idDetAgrupa
		            },
		            dataType: "json",
		            success: function(data) {
	            	  	if (data.success == true) {
		                    $("#controlcontenedor").val("");
							var nuevoelemento= '<li class="list-group-item d-flex justify-content-between align-items-center" id= "Contenedor'+ datonuevoelemento+'">'+datonuevoelementodesc+": "+parseInt(datonuevoelemento)+'<button class="btn btn-outline-info " type="button" ><span class= "icon '+datonuevoelementodesc+'"></span></button> <button class="btn btn-outline-danger fas fa-minus cmdeliminarlst" type="button"  idlst= "Contenedor'+ datonuevoelemento+'"></button></li>';
							$("#lista_contenedores_enc").append(nuevoelemento);
							var funcelim= '<script type="text/javascript">$(document).ready(function() {$(".cmdeliminarlst").click(function(evento) {var idlisteliminar= $(this).attr("idlst");$("#"+idlisteliminar).remove();});}); </'+'script>'
							$("#jquery_lista").html(funcelim);
		                } else {
		                    alertify.error(data.msj);
		                }
		            },
		            error: function(jqXHR, textStatus, error) {
		                alertify.error(error);
		            }
		        });
			}		
		});
		$(".cmdeliminarlst").click(function(evento) {
			var idlisteliminar= $(this).attr("idlst");
			$("#"+idlisteliminar).remove();
		}); 
	});

	function crearpicking($idAgrupa,$tipoop){
		
	}
</script>