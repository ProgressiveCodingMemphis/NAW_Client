<?php
/**************************************************
* Class :SimpleTX
 * @brief Well-formed object declaration for simple transactional data
*
***************************************************/
/**
 * @class SimpleTX
 */
class SimpleTX
{
    public $intTXId;// int (11)
    public $fltTXAmount;// float (50)
    public $strTXCoin;// string (15)
    public $strTXStatus;// string (15)
    public $intStatusCode;//int (5)
    public $strTXDescription;// string (255)
    public $intTXDate;// int (11)

  function __construct(){
  //construct
  }

  public static function Get(){
    //==== instantiate or retrieve singleton ====
    static $inst = NULL;
    if( $inst == NULL )
      $inst = new SimpleTX();
    return( $inst );
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

}//end class