<?php
session_name('app_caotico');
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
	phpinfo();
	session_destroy();
?>