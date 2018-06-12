<?php
/**************************************************
* Class :Payload
 * @brief Well-formed object declaration of the 'Payload' object from a communication request
*
***************************************************/


class Payload implements JsonSerializable
{
    public $boolValidPayload;// bool - is this Payload valid
    public $strTXType;// string - we need to know the TX Type to determine what to do
    public $intTXAmount;//float - optional number of currency type involved
    public $strFromCoinType;// string - optional type of coin involved as from
    public $strToCoinType;// string - optional type of coin involved as to
    public $strReceipt;// string - TX id to give back when processing is complete
    private $strFromPassKey;//encrypted passkey for "FROM" account
    private $strPrimaryWalletId;// string - wallet ID for bitcoin or ether
    private $strSecondaryWalletId;// string - optional secondary wallet ID for bitcoin or ether

    //data for a single or many transactions
    public $arrSimpleTXData;

    //hold payload notes or other data
    public $strPayloadNotes='';

    //public token
    private $strTXToken;

    //private key
    private $strLocalKey;

  function __construct(){
  //construct
  }


  public static function Get(){
    //==== instantiate or retrieve singleton ====
    static $inst = NULL;
    if( $inst == NULL )
      $inst = new Payload();
    return( $inst );
  }

  /**
  * set our TX token
  * @return bool
  */
  function SetTXToken(){
    $this->strTXToken = TXTOKEN;
    return TRUE;
  }

  /**
  * get our TX token
  * @return string - TX Token
  */
  function GetTXToken(){
    return $this->strTXToken;
  }

  /**
  * set our private key
  * @return bool
  */
  function SetPrivateKey(){
    $this->strLocalKey = LOCALKEY;
    return TRUE;
  }

  /**
  * get our Private Key
  * @return string - Private Key
  */
  function GetPrivateKey(){
    return $this->strLocalKey;
  }

  /**
  * set our primary wallet ID
  * @param $strPrimaryWalletId
  * @return bool
  */
  function SetPrimaryWallet($strPrimaryWalletId){
    $this->strPrimaryWalletId = $strPrimaryWalletId;
    return TRUE;
  }

  /**
  * get our Primary Wallet ID
  * @return string - Primary wallet ID
  */
  function GetPrimaryWallet(){
    return $this->strPrimaryWalletId;
  }

  /**
  * set our secondary wallet ID
  * @param $strSecondaryWalletId
  * @return bool
  */
  function SetSecondaryWallet($strSecondaryWalletId){
    $this->strSecondaryWalletId = $strSecondaryWalletId;
    return TRUE;
  }

  /**
  * get our Secondary Wallet ID
  * @return string - Secondary wallet ID
  */
  function GetSecondaryWallet(){
    return $this->strSecondaryWalletId;
  }

  /**
  * validate payload TX amount
  * @param return bool
  */
  function ValidateTXAmount(){
    if(strlen(substr(strrchr($this->intTXAmount, "."), 1)) <= 15)
      return TRUE;
    return FALSE;
  }

  /**
  * validate payload To Coin type
  * @param return bool
  */
  function ValidateToCoinType(){
    if(strlen($this->strToCoinType) <= 15)
      return TRUE;
    return FALSE;
  }

  /**
  * validate payload From Coin type
  * @param return bool
  */
  function ValidateFromCoinType(){
    if(strlen($this->strFromCoinType) <= 15)
      return TRUE;
    return FALSE;
  }

  /**
  * validate payload Primary Wallet
  * @param return bool
  */
  function ValidatePrimaryWallet(){
    if(strlen($this->strPrimaryWalletId) > 31 && strlen($this->strPrimaryWalletId) < 41)
      return TRUE;
    return FALSE;

  }

  /**
  * validate payload Secondary Wallet
  * @param return bool
  */
  function ValidateSecondaryWallet(){
    if(strlen($this->strSecondaryWalletId) > 31 && strlen($this->strSecondaryWalletId) < 41)
      return TRUE;
    return FALSE;
  }

  /**
  * validate payload From Pass Key
  * @param return bool
  */
  function ValidateFromPassKey(){
    if(strlen($this->strFromPassKey) >= 0)
      return TRUE;
    return FALSE;
  }

  /**
  * given an array sent in JSON format, rehydrate the object
  * @param $arrObject
  * @return $this
  */
  public function LoadObjectWithArrayObject($arrArray){
    foreach($arrArray as $varKey=>$varValue){
      if(property_exists($this,$varKey))
          $this->{$varKey} = $varValue;
    }
    return $this;
  }

  /**
  * update an object with an object
  * @param $objUpdatingObject
  * @return bool
  */
  function UpdateObjectWithObject($objUpdatingObject){
   $arrObjectVars = get_object_vars($objUpdatingObject);
   foreach($arrObjectVars as $strName => $varValue)
      $this->$strName = $varValue;
   return TRUE;
  }

  /**
  * get the object for transport
  * @return $this
  */
  public function GetMemberVariables(){
    return get_object_vars($this);
  }

  public function jsonSerialize(){
    return get_object_vars($this);
  }

}//end class Payload
?>