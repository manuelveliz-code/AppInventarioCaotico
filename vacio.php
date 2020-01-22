<?php 


$pietab          = '
				<script type="text/javascript">
					$(document).ready(function(){ $("#menug").load("menu.php"); });
				</script>	
				
				 ';
		//$json['detalle'] = $enc_superior.' <div id="detallegrupo">'. $xdetalle . '</div>' . $pietab .'' . $conteoculto. ' ' . initws();
		$json['detalle'] = minificado($enc_superior.' <div id="detallegrupo">'. $xdetalle . '</div>' . $pietab .'' . $conteoculto. ' ' . initws());
				 
		echo json_encode($json);
		break;


?>


	$json            = array();
		$detalle         = '';
		$libs            = new db();
		$json['msj']     = 'Ordenes Pendientes';
		$json['success'] = true;
		$x=0;
		if (isset($_POST['tipoop'])) { 	$_SESSION['estado'] = $_POST['tipoop']; } 
		if (isset($_POST['descripcion'])) { $_SESSION['proceso'] = $_POST['descripcion'];}
		if (isset($_POST['tipomov'])) { $_SESSION['tipomov'] = $_POST['tipomov'];}
		$xcontador = 0;
		$resultado = $libs->getEncabezadosreingresobod($_SESSION['estado'],'','','');
		$detalle='';
		if ($resultado) {
			if (!sqlsrv_has_rows($resultado)) {
				$encabezado              = '<h3 class= "padre">&ldquo;Sin Reingresos con leyenda pendientes&rdquo; </h3>';
				$xmensajebtn='Ir a Traslados entre bodegas';
				$_SESSION["vistaactual"] = 13;
			} 
			else {
				$xmensajebtn='Generar Tareas de traslado';
				$json['msj']             = 'Datos generados';
				$_SESSION["vistaactual"] = 13;
				$encabezado              = '<h3 class= "padre">' . $_SESSION['proceso'] . '</h3>
				<table class="table">
					<thead class="thead-dark">
					    <tr>
					      <th scope="col" >#</th>
					      <th scope="col">Código</th>
					      <th scope="col" class="txtizq">Nombre</th>
					      <th scope="col" class="txtizq">Cantidad</th>
					    </tr>
				  	</thead>
					<tbody>
				';
				
				
				while ($obj = sqlsrv_fetch_object($resultado)) {
					$x= $x+1;
					$CODIGOSALE  	  = trim($obj->CODIGOSALE);
					$NOMBRE  	  = $obj->NOMBRE;
					$CantidadSale 	  = $obj->CantidadSale;
					$xdetalle = '
						<tr>
					      <th scope="row">'.$x.'</th>
					      <td>'.$CODIGOSALE.'</td>
					      <td>'.$NOMBRE.'</td>
					      <td>'.$CantidadSale.'</td>
					    </tr>';
					$detalle= $detalle.$xdetalle;
				} 
			}
			$pietab             = '
					</tbody>
				</table>
				</br>
				<button class="btn btn-primary btn-lg" id="btngenerartareasetiquetado">'.$xmensajebtn.'<i class="fas  fa-edit pl-1"></i></button>
				</br>
				 <script type="text/javascript">
				 	$(document).ready(function() {
				 		$("#menug").load("menu.php");
					});
				 </script>';
			$json['detalle']    = $encabezado . $detalle . $pietab . ' ' . initws();
			$json['encabezado'] = $detalle;
		} 
		else {
			$json['msj']     = dbGetErrorMsg();
			$json['success'] = false;
		}
		echo json_encode($json);
		break;


		document.getElementById("resultado").innerHTML=" \
            Por elementos: "+porElementos+" \
            <br>Por ID: "+porId+" \
            <br>Por Nombre: "+porNombre+" \
            <br>Por TagName: "+porTagName+" \
            <br>Por ClassName: "+porClassName;
    }
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>focusout demo</title>
  <style>
  .inputs {
    float: left;
    margin-right: 1em;
  }
  .inputs p {
    margin-top: 0;
  }
  </style>
  <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
</head>
<body>
 
<div class="inputs">
  <p>
    <input type="text"><br>
    <input type="text">
  </p>
  <p>
    <input type="password">
  </p>
</div>
<div id="focus-count">focusout fire</div>
<div id="blur-count">blur fire</div>
 
<script>
var focus = 0,
  blur = 0;
$( "p" )
  .focusout(function() {
    focus++;
    $( "#focus-count" ).text( "focusout fired: " + focus + "x" );
  })
  .blur(function() {
    blur++;
    $( "#blur-count" ).text( "blur fired: " + blur + "x" );
  });
</script>
 
</body>
</html>


$.ajax({
        type:'post',
        url:'"index.php"',
        data:  '"datos"'=dato,
        succes: functiion(data){
            console.log(data);
        }


    });









      $(document).off("click","#btnexistencia");
    $(document).on("click", "#btnexistencia", function(e){
        var codigoBarra = 0;
        
        console.log(codigoBarra);
        var xtipoop = $(this).attr("tipoop");
        var xdescripcion = $(this).attr("descripcion");
        var xtipomov = $(this).attr("tipomov");
        $.ajax({ async:true,
            url: 'controlador.php?action=40',
            type: 'post',
            data: {
                'codigobarra':codigoBarra,
                'tipoop': xtipoop,
                'tipomov': xtipomov,
                'descripcion': xdescripcion
            },
            dataType: 'json',
            success: function(data) {
                if (data.success == true) {
                    $("#contenidopg_oculto").html(data.encabezado);
                    $("#contenidopg").html(data.detalle);
                   
                } else {
                    alertify.error(data.msj);
                }
            },
            error: function(jqXHR, textStatus, error) {
                alertify.error(error);
            }
        });

      function obtenercodigoexistencia(){

        $("#txtBuscapedido").focusout(function() {
						
						
        });
      }
    });















































