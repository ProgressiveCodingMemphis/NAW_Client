<?php
session_start();
error_reporting (E_ALL); // ^ E_WARNING ^ E_PARSE ^ E_COMPILE_ERROR ^ E_NOTICE
ini_set('log_errors', 1);
ini_set('display_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/Logs/error.log');
header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );   
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="robots" content="all,deny">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo SITENAME; ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>    
  </head>
  <body>