<?php
session_name('app_caotico');
if(!isset($_SESSION)){ session_start(); } 
 echo '
      <nav class="navbar fixed-top navbar-dark bg-dark">
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span> Hola
            <span id="nickenc" > </span> '. $_SESSION['nombreusuario'] .'
          </button>
        <button class=" btn btn-warning" type="button" id="btnseleop"><span class="fas fa-exchange-alt"></span></button>
        <button class=" btn btn-success btnrefresh"  type="button" id="btnrefresh" ><span class="fas fa-sync-alt"></span></button>
          <button class=" btn btn-danger btnsalir"  type="button" id="btnsalir" ><span class="fas fa-times-circle"></span></button>
         
        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0 nav navbar-nav nav-collapse collapse">
              <li class="nav-item active">
                <a class="nav-link links_menu" id="mnseleop"  data-toggle="collapse" ><span class="fas fa-exchange-alt"> </span> Cambiar Operacion <span class="sr-only"></span></a>
              </li>
              <li class="nav-item ">
                <a class="nav-link links_menu btnrefresh" id="mnactualizar" data-toggle="collapse"><span class="fas fa-sync-alt"> </span> Actualizar <span class="sr-only"></span></a>
              </li>
              <li class="nav-item ">
                <a class="nav-link links_menu " id="mnfullscreen" data-toggle="collapse"><span class="fas fa-expand"> </span> Pantalla completa <span class="sr-only"></span></a>
              </li>
              
              <li class="nav-item">
                <a class="nav-link links_menu"  id="mnsalir" ><span class="fas fa-times-circle"> </span> Salir</a>
              </li>
            </ul>
        </div>
      </nav>
';
?>