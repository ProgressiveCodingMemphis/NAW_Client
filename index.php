<?php
/*
  @brief: this is simply a hook script execution file with a few examples of
   usage.To turn off access to this file simply un-comment the exit before the
   require_once. There is no security here so it is advised to turn it off when
   not testing.
  @accepts: POST/GET ( interchangeable below ), Ajax and cURL
  @return: Produces Payload object as JSON text
  @requires: config\Config.php
  @version: 1.0.0
*/
//this is required wherever nawClient is used
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'Config.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'WebHeader.php');

//get our request data.
$arrPOST = filter_var_array($_GET,FILTER_SANITIZE_STRING);
//add hooks here

$objNAWClient = new nawClient();


if($objNAWClient->GetWallets()){
  echo '<div class="row">';
  echo '<div class="col-lg-6">';
  echo '<table class="table">
      <thead>
        <tr>
          <th>Account</th>
          <th>Eth Balance</th>
        </tr>
      </thead>
      <tbody>';
  foreach($objNAWClient->objPayload->arrSimpleTXData as $arrSimpleTX){
    $objSimpleTX = new SimpleTX();
    $objSimpleTX->LoadObjectWithArrayObject($arrSimpleTX);
    $arrResults = Utility::Get()->MakeOutBoundCURL('https://api.nanopool.org/v1/eth/balance/'.Utility::Get()->StripHexPreface($objSimpleTX->strTXDescription));
    if(!$arrBalanceData = Utility::Get()->JSONDecode($arrResults['result']))
        continue 1;//cannot process non-active accounts
    $strRowClass = ($arrBalanceData['status'] == 'true')? 'success':'danger' ;
    echo '<tr class="'.$strRowClass.'">';
    echo '<td>';
    echo $objSimpleTX->strTXDescription;
    echo '</td>';
    echo '<td class="">';
    $fltPresentEth = 0;
    if(array_key_exists('data',$arrBalanceData))
        $fltPresentEth = Utility::Get()->ConvertToNumber((float)$arrBalanceData['data'],12);
    echo $fltPresentEth;
    echo '</td>';
    echo '</tr>';
  }
    echo ' </tbody>
    </table>';
    echo '</div>';
    echo '</div>';
}
else{
  echo 'No valid transactions';
  Debug::Debug_er('Transaction Results',DEBUG_LEVEL,FALSE,TRUE);
}

//reset our payload
$objNAWClient->InitiatePayload();
//get a single balance
if($objNAWClient->GetPrimaryBalance() && is_array($objNAWClient->objPayload->arrSimpleTXData)){
  echo '<h2>Primary Balance</h2>';
  foreach($objNAWClient->objPayload->arrSimpleTXData as $arrSimpleTX){
    echo '<pre>';
    print_r($arrSimpleTX);
    echo '</pre>';
  }
}
else{
  Debug::Debug_er('Transaction Results',DEBUG_LEVEL,FALSE,TRUE);
}

//reset our payload
$objNAWClient->InitiatePayload();
//get the gas price
if($objNAWClient->GetGasPrice() && is_array($objNAWClient->objPayload->arrSimpleTXData)){
  echo '<h2>Gas Price</h2>';
  foreach($objNAWClient->objPayload->arrSimpleTXData as $arrSimpleTX){
    echo '<pre>';
    print_r($arrSimpleTX);
    echo '</pre>';
  }
}
else{
  Debug::Debug_er('Transaction Results',DEBUG_LEVEL,FALSE,TRUE);
}


require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'WebFooter.php');
?>