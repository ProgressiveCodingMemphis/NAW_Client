<?php
//define our version. This hopefully helps make upgrading and downgrading easier
//we will look in the includes[NAWVERSION] folder for our core operating code
define('NAWVERSION','1.0.0');
//define local token
define('TXTOKEN','RzRvPxzuCy21YXG05CxwFQ3cXvaNFWop1ZFCI1tv1OMHzM5gFJhOZ1TqLtSGIsHI');
//define local key, set to test key for demo purposes
define('LOCALKEY','aQSqFImyv97Jnsx3vvG4DEXTXJn0c5nVzwWMOrwP8E8GewaomSFBd55m2VkiuOWt');
//set our (bit)CoinMinor endpoint address to (bit)CoinMajor API access address
define('APIPORTAL','http://bitminor.ddns.net/CoinMiner/');
//define administrative handler
define('ADMIN','treymelton@gmail.com');
//give this site a name for ease of recognition in error handling
define('SITENAME','Bit Miner client');
//turn debugging on and off
define('DEBUG_ARG',1);
   /*
   * Valid debug levels (NOT DEBUG_ARG):
   *   0 = off
   *   1 = write to log
   *   2 = write to log and email
   *   3 = email only
   *   4 = all
   *   5 = print to screen (default)
  */
define('DEBUG_LEVEL',5);
//execute load program
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'includes'.NAWVERSION.DIRECTORY_SEPARATOR.'nawLoad.php');   
?>