<?php
/**
 * Debug class, basically for handling of exceptions and user-error messages.
 * @requires:
 *  -Utility.php
 */
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'Utility.php');
class Debug
{
  /**
   * Does nothing, intentionally.
   *
   * @access public
   * @param  void
   * @return void
   */
  public function __construct() {} // end __construct()


  	/**
	 * Given an error message and a debug level, perform the action on the message
	 * as indicated by the level.
	 *
	 * Valid debug levels (NOT DEBUG_ARG):
	 *   0 = off
	 *   1 = write to log
	 *   2 = write to log and email
	 *   3 = email only
	 *   4 = all
	 *   5 = print to screen
	 *
	 * @access public
	 * @param  $strErrorMessage  Error Message
	 * @param  $intDebugLevel       Debug Level
	 * @param  $boolBacKTrace  add backtrace calls to the debug message
	 * @param  $objShowObjectMembers  object to show member variables for || FALSE
	 * @return void
	 */
	public static function Debug_er($strErrorMessage,$intDebugLevel,$boolBacKTrace = FALSE,$objShowObjectMembers = FALSE){
    	if(DEBUG_ARG != 1)
    	  return;
        $strBackTrace = '';
        $strObjectMembers = '';
        $strErrorMessage .= self::GetMemoryUsage();
        if($boolBacKTrace)
            $strBackTrace = self::FormBackTrace(FALSE);
        if($objShowObjectMembers)
            $strObjectMembers = self::LoadObjectVariables($objShowObjectMembers,FALSE);
        if((int)$intDebugLevel > 0){
    		if(($intDebugLevel == 5 || $intDebugLevel == 4)){
    		  $strErrorReport = '<div class="row">';
    		  $strErrorReport .= '<div class="col-lg-12 bg-warning text-light">';
    		  $strErrorReport .= $strBackTrace.'<br />';
    		  $strErrorReport .= $strErrorMessage.'<br />';
    		  $strErrorReport .= $strObjectMembers.'<br />';
    		  $strErrorReport .= '<div>';
    		  $strErrorReport .= '<div>';
    		  echo $strErrorReport;
    		}
    		if($intDebugLevel != 5 && $intDebugLevel != 3){
    			Debug::Debug_log($strBackTrace.$strErrorMessage.$strObjectMembers,$intDebugLevel);
            }
    		if($intDebugLevel == 2 || $intDebugLevel == 3 || $intDebugLevel == 4){
    		/**
             * @internal
  			 * the previous if statement already handles adding to the debug log;
  			 * this should only be concerned with whether the error should be emailed.
  			 */
    		Debug::Send_Mail(ADMIN,"Execution Error ",$strBackTrace.$strErrorMessage.$strObjectMembers);
    		}
      }
	} // end Debug_er()


    /**
    * gather the memory usage for this moment and append it to the log
    * @return string
    */
    public static function GetMemoryUsage(){
     $strUsage = "\r\n";
     $intPHPMemory = memory_get_usage(TRUE);
     //add memory usage
      $strUsage .= "memory_get_usage [";
     if ($intPHPMemory < 1024)
      $strUsage .= $intPHPMemory." Bytes";
     elseif ($intPHPMemory < 1048576)
      $strUsage .= round($intPHPMemory/1024,2)." KB";
     else
      $strUsage .= round($intPHPMemory/1048576,2)." MB";
     $strUsage .= "]\r\n";
     $strUsage .= "memory_get_peak_usage [";
     //peak memory
     $intPHPPeakMemory = memory_get_peak_usage (TRUE);
     if ($intPHPPeakMemory < 1024)
       $strUsage .= $intPHPPeakMemory." Bytes";
     elseif ($intPHPPeakMemory < 1048576)
       $strUsage .= round($intPHPPeakMemory/1024,2)." KB";
     else
       $strUsage .= round($intPHPPeakMemory/1048576,2)." MB";
     $strUsage .= "]\r\n";
     return $strUsage;
    }

        //form the backtrace
  public static function FormBackTrace($boolForDisplay = FALSE){
    $arrBackTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    $strBreak = "\r\n";
    $strArguments = '';
    $strBackTrace = '';
    if($boolForDisplay)
      $strBreak = '<br />';
    foreach($arrBackTrace as $ka=>$va){
      $strBackTraceFile = 'File ['.$va['file'].']'.$strBreak;
      if(array_key_exists('args',$va) && is_array($va['args'])){
        foreach($va['args'] as $kb=>$vb){
          if(!is_object($vb))
            $strArguments .= '<pre>'.$vb.'</pre>,';
          else{
            $strObjectVariables = var_export($vb,TRUE);
            $strArguments .= '[OBJECT]'.$strBreak.'<pre>'.$strObjectVariables.'</pre>,'.$strBreak;
          }
        }
      }
      $strBackTrace .= 'Line ['.$va['line'].'] '.$va['class'].'->'.$va['function'].'('.$strArguments.')'.$strBreak;
    }
    return $strBackTraceFile.$strBackTrace;

  }

   public static function LoadObjectVariables($objShowObjectMembers,$boolForDisplay = TRUE){
      $strReturn = '';
        $strObjectVariables = var_export($objShowObjectMembers,TRUE);
      if($boolForDisplay){
        $strReturn .= '<pre>'.$strObjectVariables.'</pre>';
      }
      else{
        $strReturn = $strObjectVariables;
      }
      return $strReturn;
    }

    
  /**
   * Given an error string and a debug level, attempt to open the debug log and
   * append the error string with a timestamp. If the log cannot be written to,
   * send an email to the administrator notifying him of the problem and the
   * error message that was supposed to be written.
   *
   * @access public
   * @param  $strErrorMessage   Error Message
   * @return bool
   */
  public static function Debug_log($strErrorMessage,$intDebugLevel){
    // switched from m_d_Y to Y_m_d to match string cardinality with date progression
    $strLogAddress = (dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Logs'.DIRECTORY_SEPARATOR.'DBGLOG_'.date('Y_m_d',time()).'.txt');
    $arrLastError = error_get_last();
    $strLastError = '';
    if(sizeof($arrLastError) > 0)
      $strLastError = var_export($arrLastError,TRUE);
    //make our log now
    $strLogMessage = "\r\n----------------------[".date('r')."]:----------------------\r\n ";
    $strLogMessage .= "[" . $_SERVER['SCRIPT_NAME']."]: " .  $strErrorMessage . "\r\n";
    $strLogMessage .= '$strLastError ['.$strLastError.']'." \r\n";
      if(!Utility::Get()->AppendFile($strLogAddress,$strLogMessage)){
        //send an email of our failure
        return Utility::Get()->Send_Mail(ADMIN,"Query Error ".$intFunctionCall,($error."<br />".$strErrorMessage.'<br />$strLastError ['.$strLastError.']'." \r\n".$strLogAddress));
      }
    return TRUE;
  } // end Debug_log()


} // end class Debug

?>