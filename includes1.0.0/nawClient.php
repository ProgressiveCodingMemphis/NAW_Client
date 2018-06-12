<?php
 /**************************************************************************
 * @CLASS nawClient
 * @brief our nawClient will handle all transaction requests.
 * @REQUIRES:
 *  -Utility.php
 *  -SimpleTransaction.php
 *  -Payload.php
 *
 **************************************************************************/
class nawClient{
   public $objPayload;//response to and from server

   public static function Get(){
		//==== instantiate or retrieve singleton ====
		static $inst = NULL;
		if( $inst == NULL )
			$inst = new nawClient();
		return( $inst );
  }

  function __construct(){
    //verify we have valid credentials
    if(strlen(TXTOKEN) !== 64 || strlen(LOCALKEY) !== 64){
     throw new exception ('No valid keys found. Cannot continue. ['.strlen(TXTOKEN).'] ['.strlen(LOCALKEY).'] ['.__LINE__.']');
     die;
    }
    else{
        $this->InitiatePayload();
        /*
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

          //public token
          private $strToken;

          //private key
          private $strLocalKey;
        */
    }
  }

  /**
  * initiate our payload object
  * @param return bool
  */
  function InitiatePayload(){
    //make our payload object now
    $this->objPayload = new Payload();
    $this->objPayload->SetPrivateKey();
    $this->objPayload->SetTXToken();
    return TRUE;
  }

  /**
  * make a request for the last X number of transactions
  * @param $intTXMax - maximum number to return. This could get messy if it becomes too large
  * @return - array of SimpleTransaction Objects
  */
  function GetLastXTX($intTXMax=25){
    $this->objPayload->strTXType = 'txi';
    return $this->ExecutePayloadLaunch();
  }

  /**
  * get the existing wallets
  * @return bool
  */
  function GetWallets(){
    $this->objPayload->strTXType = 'gew';
    return $this->ExecutePayloadLaunch();
  }

  /**
  * get the primary balance data
  * @return bool
  */
  function GetPrimaryBalance(){
    $this->objPayload->strTXType = 'gwb';
    return $this->ExecutePayloadLaunch();
  }

  /**
  * get the primary balance data
  * @return bool
  */
  function GetGasPrice(){
    $this->objPayload->strTXType = 'ggp';
    return $this->ExecutePayloadLaunch();
  }

  /**
  * given two wallet addresses, an amount, and the passkey for the 'from'
  * account, make a TX
  * @param $strFromWallet
  * @param $strToWallet
  * @param $fltAmount
  * @param $strPassKey
  * @return object ( SimpleTransaction )
  */
  function MakeTransaction($strFromWallet,$strToWallet,$fltAmount,$strPassKey){

  }

  /**
  * validate our payload
  * @return bool
          public $intTXAmount;//float - optional number of currency type involved
          public $strFromCoinType;// string - optional type of coin involved as from
          public $strToCoinType;// string - optional type of coin involved as to
          public $strReceipt;// string - TX id to give back when processing is complete
          private $strFromPassKey;//encrypted passkey for "FROM" account
          private $strPrimaryWalletId;// string - wallet ID for bitcoin or ether
          private $strSecondaryWalletId;// string - optional secondary wallet ID for bitcoin or ether
  */
  function ValidatePayload(){
    if(is_object($this->objPayload) && trim($this->objPayload->strTXType) != ''){
      switch($this->objPayload->strTXType){
        case 'txi'://nothing left to validate. Transaction inquiry
        return TRUE;
        break;
        case 'gew'://nothing left to validate. Get all the wallets
        return TRUE;
        break;
        case 'gwb'://nothing left to validate. Wallet Balance
        return TRUE;
        break;
        case 'ggp'://nothing left to validate. Gas Price check
        return TRUE;
        break;
        case 'dep'://deposit
            if(!$this->objPayload->ValidateTXAmount())
                return FALSE;                                        ;
            if(!$this->objPayload->ValidateToCoinType())
                return FALSE;
            if(!$this->objPayload->ValidatePrimaryWallet())
                return FALSE;
            return TRUE;
        break;
        case 'tfr'://transfer
            if(!$this->objPayload->ValidateTXAmount())
                return FALSE;
            if(!$this->objPayload->ValidateToCoinType())
                return FALSE;
            if(!$this->objPayload->ValidateFromCoinType())
                return FALSE;
            if(!$this->objPayload->ValidatePrimaryWallet())
                return FALSE;
            if(!$this->objPayload->ValidateSecondaryWallet())
                return FALSE;
            if(!$this->objPayload->ValidateFromPassKey())
                return FALSE;
            return TRUE;
        break;
        case 'wid'://withdraw
            if(!$this->objPayload->ValidateTXAmount())
                return FALSE;
            if(!$this->objPayload->ValidateToCoinType())
                return FALSE;
            if(!$this->objPayload->ValidateFromCoinType())
                return FALSE;
            if(!$this->objPayload->ValidatePrimaryWallet())
                return FALSE;
            if(!$this->objPayload->ValidateSecondaryWallet())
                return FALSE;
            if(!$this->objPayload->ValidateFromPassKey())
                return FALSE;
            return TRUE;
        break;
        case 'buy'://buy coins
            if(!$this->objPayload->ValidateTXAmount())
                return FALSE;
            if(!$this->objPayload->ValidateToCoinType())
                return FALSE;
            if(!$this->objPayload->ValidatePrimaryWallet())
                return FALSE;
            return TRUE;
        break;
        case 'bin'://balance inquiry
            if(!$this->objPayload->ValidatePrimaryWallet())
                return FALSE;
            return TRUE;
        break;
        default:
            $this->objPayload->strPayloadNotes .= 'Payload failed  ['.__LINE__.']';
            return FALSE;
      }
    }
    $this->objPayload->strPayloadNotes .= 'Payload failed  ['.__LINE__.']';
    //give it back
    return FALSE;
  }


  /**
  * pack our payload for delivery
  * @return bool
  */
  function PackAndSendPayload(){
    $strPayload = json_encode($this->objPayload->jsonSerialize(),JSON_FORCE_OBJECT);
    if(($strResponse = Utility::Get()->MakeQuickCURL(APIPORTAL,$strPayload))){
        if(is_array(($arrResponseData = Utility::Get()->JSONDecode($strResponse)))){
          $this->objPayload->LoadObjectWithArrayObject($arrResponseData);
          if(($this->objPayload) instanceof Payload)
              return TRUE;
          else{                                 
            return FALSE;
          }
        }
        else{
          $this->objPayload = new Payload();
          $this->objPayload->strPayloadNotes .= 'Payload failed ['.var_export($strResponse,TRUE).'] ['.__LINE__.']';
          return FALSE;
        }
    }
    $this->objPayload->strPayloadNotes .= 'Payload failed ['.var_export($strResponse,TRUE).'] ['.__LINE__.']';
    return FALSE;
  }

  /**
  * execute the payload construction and payload execution
  * @return bool
  */
  function ExecutePayloadLaunch(){
    //validate our payload
    if($this->ValidatePayload()){
        if($this->PackAndSendPayload())
            return TRUE;
        return FALSE;
    }
    else{
      $this->objPayload->strPayloadNotes .= 'Could not validate payload ['.__LINE__.']';
      return FALSE;
    }
  }


}//end class