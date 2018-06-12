<?php
/*******************************************************
* @ brief Utility class for all Basic reused functions
*  @param: takes no parameters
*  @Requires:
*    -none, presently
*
****************************************************/
class Utility{
  public static function Get(){
		//==== instantiate or retrieve singleton ====
		static $inst = NULL;
		if( $inst == NULL )
			$inst = new Utility();
		return( $inst );
   }

   public function __construct(){
    // construct here
   }


   /**
   * Returns an encrypted & utf8-encoded string
   */
    function encrypt($strSafeWord) {
        $strSecureKey = hash('sha256',LOCALKEY,TRUE);
        $strIV = mcrypt_create_iv(32);
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $strSecureKey, $strSafeWord, MCRYPT_MODE_ECB, $strIV ));
    }

    /**
     * Returns decrypted original string
     */
    function decrypt($strSafeWord) {
        $strSecureKey = hash('sha256',LOCALKEY,TRUE);
        $strIV = mcrypt_create_iv(32);
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $strSecureKey, base64_decode($strSafeWord), MCRYPT_MODE_ECB, $strIV ));
    }


  /**
  * encode a PHP array into json
  * @param array $arrValues values to be encoded
  * @param $objEncodeType
  * -JSON_HEX_QUOT
  * -JSON_HEX_TAG
  * -JSON_HEX_AMP
  * -JSON_HEX_APOS
  * -JSON_NUMERIC_CHECK
  * -JSON_PRETTY_PRINT
  * -JSON_UNESCAPED_SLASHES
  * -JSON_FORCE_OBJECT
  * -JSON_PRESERVE_ZERO_FRACTION
  * -JSON_UNESCAPED_UNICODE
  * -JSON_PARTIAL_OUTPUT_ON_ERROR
  * @return string
  */
  public static function JSONEncode($arrValues,$objEncodeType = JSON_FORCE_OBJECT){//JSON_FORCE_OBJECT
     //try to encode it now
     if($strJsonData = json_encode($arrValues,$objEncodeType))
        return $strJsonData;
     switch (json_last_error()) {
        case JSON_ERROR_NONE:
            $_SESSION['jsonerror'] = ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            $_SESSION['jsonerror'] = ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            $_SESSION['jsonerror'] = ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            $_SESSION['jsonerror'] = ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            $_SESSION['jsonerror'] = ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            $_SESSION['jsonerror'] =' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            $_SESSION['jsonerror'] = ' - Unknown error';
        break;
    }                                                                            
    //cannot decode this
    return FALSE;
  }

  /**
  * decode a json array string into a php array
  * @param string $strValues values to be decoded
  * @param bool $boolAssociatve return an associative array or numerically index array
  * @return array
  */
  public static function JSONDecode($strValues, $boolAssociatve=TRUE){
     return json_decode($strValues, $boolAssociatve);
  }

  /**
  * given a status code, return the human readable description
  * @param $intCode
  * @return string ( description )
  */
  function GetHTTPResponse($intCode){
    $arrDescription = array();
    if ($intCode !== NULL) {
      switch ($intCode) {
          case 100: $arrDescription[0] = 'Continue';
            $arrDescription[1] = '#E6E312';
            break;
          case 101: $arrDescription[0] = 'Switching Protocols';
            $arrDescription[1] = '#E6E312';
            break;
          case 200: $arrDescription[0] = 'OK';
            $arrDescription[1] = '#089131';
            break;
          case 201: $arrDescription[0] = 'Created';
            $arrDescription[1] = '#E6E312';
            break;
          case 202: $arrDescription[0] = 'Accepted';
            $arrDescription[1] = '#089131';
            break;
          case 203: $arrDescription[0] = 'Non-Authoritative Information';
            $arrDescription[1] = '';
            break;
          case 204: $arrDescription[0] = 'No Content';
            $arrDescription[1] = '#CC8F0B';
            break;
          case 205: $arrDescription[0] = 'Reset Content';
            $arrDescription[1] = '#CC0000';
            break;
          case 206: $arrDescription[0] = 'Partial Content';
            $arrDescription[1] = '#CC0000';
            break;
          case 300: $arrDescription[0] = 'Multiple Choices';
            $arrDescription[1] = '#CC8F0B';
            break;
          case 301: $arrDescription[0] = 'Moved Permanently';
            $arrDescription[1] = '#CC8F0B';
            break;
          case 302: $arrDescription[0] = 'Moved Temporarily';
            $arrDescription[1] = '#CC8F0B';
            break;
          case 303: $arrDescription[0] = 'See Other';
            $arrDescription[1] = '#CC8F0B';
            break;
          case 304: $arrDescription[0] = 'Not Modified';
            $arrDescription[1] = '#CC8F0B';
            break;
          case 305: $arrDescription[0] = 'Use Proxy';
            $arrDescription[1] = '#CC8F0B';
            break;
          case 400: $arrDescription[0] = 'Bad Request';
            $arrDescription[1] = '#CC0000';
            break;
          case 401: $arrDescription[0] = 'Unauthorized';
            $arrDescription[1] = '#CC0000';
            break;
          case 402: $arrDescription[0] = 'Payment Required';
            $arrDescription[1] = '#CC0000';
            break;
          case 403: $arrDescription[0] = 'Forbidden';
            $arrDescription[1] = '#CC0000';
            break;
          case 404: $arrDescription[0] = 'Not Found';
            $arrDescription[1] = '#CC0000';
            break;
          case 405: $arrDescription[0] = 'Method Not Allowed';
            $arrDescription[1] = '#CC0000';
            break;
          case 406: $arrDescription[0] = 'Not Acceptable';
            $arrDescription[1] = '#CC0000';
            break;
          case 407: $arrDescription[0] = 'Proxy Authentication Required';
            $arrDescription[1] = '#CC0000';
            break;
          case 408: $arrDescription[0] = 'Request Time-out';
            $arrDescription[1] = '#CC0000';
            break;
          case 409: $arrDescription[0] = 'Conflict';
            $arrDescription[1] = '#CC0000';
            break;
          case 410: $arrDescription[0] = 'Gone';
            $arrDescription[1] = '#CC0000';
            break;
          case 411: $arrDescription[0] = 'Length Required';
            $arrDescription[1] = '#CC0000';
            break;
          case 412: $arrDescription[0] = 'Precondition Failed';
            $arrDescription[1] = '#CC0000';
            break;
          case 413: $arrDescription[0] = 'Request Entity Too Large';
            $arrDescription[1] = '#CC0000';
            break;
          case 414: $arrDescription[0] = 'Request-URI Too Large';
            $arrDescription[1] = '#CC0000';
            break;
          case 415: $arrDescription[0] = 'Unsupported Media Type';
            $arrDescription[1] = '#CC0000';
            break;
          case 500: $arrDescription[0] = 'Internal Server Error';
            $arrDescription[1] = '#CC0000';
            break;
          case 501: $arrDescription[0] = 'Not Implemented';
            $arrDescription[1] = '#CC0000';
            break;
          case 502: $arrDescription[0] = 'Bad Gateway';
            $arrDescription[1] = '#CC0000';
            break;
          case 503: $arrDescription[0] = 'Service Unavailable';
            $arrDescription[1] = '#CC0000';
            break;
          case 504: $arrDescription[0] = 'Gateway Time-out';
            $arrDescription[1] = '#CC0000';
            break;
          case 505: $arrDescription[0] = 'HTTP Version not supported';
            $arrDescription[1] = '#CC0000';
            break;
          default:
            $arrDescription[0] = 'Unknown http status code "' . htmlentities($intCode) . '"';
            $arrDescription[1] = '#CC0000';
          break;
      }
    }
    $arrDescription[2] = $intCode;
    return $arrDescription;
  }

  /**
  * given  URL and filename get the header HTTP code
  * @param $strURL
  * @return int ( response code ) || bool
  */
  function GetURLHeaderHTTP($strURL,$boolDescribe=FALSE){
    $strHeaders = get_headers($strURL);
    $strResult =  substr($strHeaders[0], 9, 3);
    if($boolDescribe)
        return $this->GetHTTPResponse($strResult);
    //give back our boolean truth
    if((int)$strResult !== 200){
      return FALSE;
    }
    return $strResult;
  }

  /**
  * given a url, send a CURL request
  * @param $strURL
  * @param $strPostString
  * @return array ( $varResponse, $arrHeaders)
  */
  function MakeQuickCURL($strURL,$strPostString=''){
    $arrResponse = array();
    $objCURL = curl_init($strURL);
    curl_setopt($objCURL, CURLOPT_URL, $strURL);
    curl_setopt($objCURL, CURLOPT_TIMEOUT, 60);
    curl_setopt($objCURL, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($objCURL, CURLOPT_POST, TRUE);
    curl_setopt($objCURL, CURLOPT_POSTFIELDS, $strPostString);
    curl_setopt($objCURL, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($objCURL, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($objCURL, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Length: " . strlen($strPostString)));


    $arrResponse = curl_exec ($objCURL);
    //$arrResponse['result'] = curl_exec ($objCURL);
    //$arrResponse['headers'] = curl_getinfo($objCURL);
    curl_close ($objCURL);
    return $arrResponse;
  }

  /**
  * given a url, send a CURL request
  * @param $strURL
  * @return array ( $varResponse, $arrHeaders)
  */
  function MakeOutBoundCURL($strURL){
    $arrResponse = array();
    $objCURL = curl_init();
    curl_setopt($objCURL, CURLOPT_URL, $strURL);
    curl_setopt($objCURL, CURLOPT_TIMEOUT, 30);
    curl_setopt($objCURL, CURLOPT_RETURNTRANSFER,1);
    $arrResponse['result'] = curl_exec ($objCURL);
    $arrResponse['headers'] = curl_getinfo($objCURL);
    curl_close ($objCURL);
    return $arrResponse;
  }

  /**
  * we remake this function for public use as it is needed in other places
  * @param $strAddress - wallet address
  * @return string ( wallet address )
  */
  function StripHexPreface($strAddress){
		if(substr($strAddress, 0, 2) == '0x')
			$strAddress = substr($strAddress, 2);
		return $strAddress;
  }



  /**
  * given a value, determine its type and return a proper human readable number value
  * if the value is not a number of any kind, return FALSE
  * @param $varValue - value to convert
  * @param $intDecimalPlaces - default 9, how many to return
  * @return number | FALSE
  */
  function ConvertToNumber($varValue, $intDecimalPlaces=12){
    //strip any hex artifacts
    $varValue = $this->StripHexPreface($varValue);
    if (ctype_xdigit($varValue)) {
        return number_format(hexdec ($varValue ),$intDecimalPlaces);
    }
    //if it's a string return FALSE since we cannot convert a string to a number
    else if(is_string($varValue)){
        return FALSE;
    }
    else if(is_float($varValue)) {
        return number_format($varValue,$intDecimalPlaces);
     }
    else{
        return $varValue;
    }
  }

  /**
  * given a  file location and a message create and or append a file
  * @param $strFileLocation location of the file to write
  * @param $strContent content to write to the file
  * @return bool
  */
  function AppendFile($strFileLocation, $strContent) {
    $objFileHandle = fopen($strFileLocation, "a+");
    $boolSuccess = fwrite($objFileHandle, $strContent);
    fclose($objFileHandle);
    return (bool)$boolSuccess;
  }


  /**
   * Trivial e-mail function.
   *
   * @access public
   * @param  $strTo     E-mail address of recipient
   * @param  $strSubject    Subject of the e-mail
   * @param  $strMessage   Message body
   * @return bool
   */
  public static function Send_Mail($strTo,$strSubject,$strMessage){
    $strHeaders  = 'MIME-Version: 1.0' . "\n";
    $strHeaders .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
    $strHeaders .= 'To: '.$strTo.' <'.$strTo.'>' . "\n";
    $strHeaders .= 'From: '.SITENAME.'< '.ADMIN.' >' . "\n";
    if(mail($strTo,$strSubject,$strMessage,$strHeaders))
      return true;
    else
      return false;
  } // end Send_Mail()

}//end class
?>