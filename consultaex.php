<?php
session_name('app_caotico');
if(!isset($_SESSION)){ session_start(); } 
require_once 'data/conexion.php';
require_once 'modelos.php';
$json = array();
$json['msj']     = 'Finalizada';
$json['success'] = false;
$libs = new db(); //carga de funciones del modelo
$x=0;
$xcontador = 0;
if (isset($_POST['codigobarra'])) { $_SESSION['codigobarra'] = $_POST['codigobarra'];}
$resultado = $libs->getexistencias($_SESSION['estado'], $_SESSION['codigobarra']);

if ($resultado ) {
    $json['success'] = true;
    $_SESSION["vistaactual"] = 15;
    if (!sqlsrv_has_rows($resultado)) {
        $encabezado              = '<h3 class= "padre">&ldquo;ESCANEAR CODIGO&rdquo; </h3>';
        $xmensajebtn='POSICION VACIA';
        $_SESSION["vistaactual"] = 15;
    } 
    else {
        $json['success'] = true;

       // $xmensajebtn='del Codigo: ';
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
        
        
        while ($obj = sqlsrv_fetch_object($resultado)) {
            $json['success'] = true;
            $_SESSION["vistaactual"] = 15;
            $x= $x+1;
            $CODIGOSALE  	  = trim($obj->CODIPRESEN);
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
//finaliza
    
    
}
else {
$json['detalle']     = "INGRESE UNA POSICION";
$json['success'] = false;
}
//$_SESSION["vistaactual"] = 15;
//}
$json['detalle']= $encabezado . $detalle. $xmensajebtn .''. initws();
?>