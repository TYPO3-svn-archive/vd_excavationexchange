<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Jean-Luc Thirot <jean-luc.thirot@vd.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_tslib.'class.tslib_pibase.php');

/**
 * Plugin 'VD/Excavation exchange' for the 'vd_excavationexchange' extension.
 *
 * @author	Jean-Luc Thirot <jean-luc.thirot@vd.ch>
 * @package	TYPO3
 * @subpackage	tx_vdexcavationexchange
 */
class tx_vdexcavationexchange_validator extends tslib_pibase {
	var $prefixId = 'tx_vdexcavationexchange_validator';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_vdexcavationexchange_validator.php';	// Path to this script relative to the extension dir.
	var $extKey = 'vd_excavationexchange';	// The extension key.
  
  var $isValid = false; // the validation result
  var $errorMsgs = array(); // the detailed validation result message [field] = message
	var $fields = array(); // the fields to validate
  var $fieldsConf = array(); // default hard coded fields configuration
  var $table; // the table to validate
	/**
	 * Validator for the extension. Get the fields dot validate and call a function for each field like check_()
   * Use functions to select a specific function
	 *
	 * @param	string		$fields: array of fields to validate
	 * @return	The content that is displayed on the website
	 */
	function validate($table, $fieldsValue,$ffunctions='')	{
		$this->pi_USER_INT_obj=1;	// Configuring so caching is not expected. This value means that no cHash params are ever set. We do this, because it's a USER_INT object!
    $this->pi_setPiVarDefaults();
    
    unset($this->errorMsgs);
    $this->table = $table;
    $this->set_fieldsConf($ffunctions);

    if(sizeOf($fieldsValue)<=0){
      // no fields to validate
      return true;
    }
    $isValid = true;
    foreach($fieldsValue as $f=>$v){
      $func = $this->fieldsConf[$this->table][$f]['func'];
      $msg = '';
      if($func){
        switch($func){
          case 'required':
            $msg = $this->f_required($f,$v);
            break;
          case 'requiredNotNull':
            $msg = $this->f_requiredNotNull($f,$v);
            break;            
          case 'date':
            $msg = $this->f_date($f,$v);
            break;
          case 'int':
            $msg = $this->f_int($f,$v);          
            break;
          case 'intOrEmpty':
            $msg = $this->f_intOrEmpty($f,$v);          
            break;						
          case 'email':
            $msg = $this->f_email($f,$v);          
            break;            
          case 'offer_roles':
            $msg = $this->f_offer_roles($fieldsValue);
            break;    
          case 'categoryMaterial':
            $msg = $this->f_categoryMaterial($fieldsValue, $f, $v);
            break; 
          case 'dateCompare':
            $msg = $this->f_dateCompare($fieldsValue, $f, $v);
            break; 						
          default:
        }
      }
      $this->errorMsgs[$f] = $msg;
      // set main validation for the global FORM
      $isValid = (trim($msg)<>'')?false:$isValid; 
    }
    $this->isValid = $isValid;

		return $this->isValid;
	}
	/**
	 * Configure HARD CODED the fields validation ['field']['validation function'],['msg']
   * Use functions to select a specific function
	 *
	 * @param	string		$fields: array of fields to validate
	 * @return	The content that is displayed on the website
	 */
  function set_fieldsConf($ffunctions=''){
    $f = array();
    $f['offer'] = array();
    $f['offerlot'] = array();
    $f['response'] = array();
    $f['search'] = array();

    $f['offer']['roles']['func'] = 'offer_roles';
    $f['offer']['roles']['msg'] = 'Entrez un rôle';
    //
    $f['offer']['contactname']['func'] = 'required';
    $f['offer']['contactname']['msg'] = 'Le nom de contact est obligatoire';
    $f['offer']['contactfunction']['func'] = 'required';
    $f['offer']['contactfunction']['msg'] = 'La fonction est obligatoire';  
    $f['offer']['startdatework']['func'] = 'date';
    $f['offer']['startdatework']['msg'] = 'Entrez une date au format (jj.mm.aaaa)';
    $f['offer']['enddatework']['func'] = 'date';
    $f['offer']['enddatework']['msg'] = 'Entrez une date au format (jj.mm.aaaa)';
		// field for comparaison
    $f['offer']['dateCompare']['func'] = 'dateCompare';
    $f['offer']['dateCompare']['msg'] = 'La date de fin doit être plus grande que la date de début';		
		
		
    $f['offer']['district']['func'] = 'requiredNotNull';
    $f['offer']['district']['msg'] = 'Entrez une région';
    
    $f['offerlot']['uid']['func'] = 'intOrEmpty';
    $f['offerlot']['catmatterrial']['func'] = 'int';
    $f['offerlot']['catmatterrial']['msg'] = 'Entrez une catégorie';    

    $f['offerlot']['designiationuscsmeuble']['func'] = 'categoryMaterial';
    $f['offerlot']['designiationuscsmeuble']['msg'] = 'Entrez le type de matériel meuble';    
    $f['offerlot']['designiationsimplemeubleha']['func'] = 'categoryMaterial';
    $f['offerlot']['designiationsimplemeubleha']['msg'] = 'Entrez le type de matériel meuble HA';   
    
    $f['offerlot']['designiationsimplemeublehb']['func'] = 'categoryMaterial';
    $f['offerlot']['designiationsimplemeublehb']['msg'] = 'Entrez le type de matériel meuble HB';   
    
    $f['offerlot']['designiationroche']['func'] = 'categoryMaterial';
    $f['offerlot']['designiationroche']['msg'] = 'Entrez le type de matériel rocheux';   
    
    $f['offerlot']['designiationsimplemeuble']['func'] = 'categoryMaterial';
    $f['offerlot']['designiationsimplemeuble']['msg'] = 'Entrez le type de matériel meuble';    
    
    $f['offerlot']['volume']['func'] = 'int';
    $f['offerlot']['volume']['msg'] = 'Entrez un volume en m3';
    
    $f['response']['email']['func'] = 'email';
    $f['response']['email']['msg'] = 'Entrez une adresse mail';
    $f['response']['company']['func'] = 'required';
    $f['response']['company']['msg'] = 'Entrez le nom de votre société';

		$f['search']['uid']['func'] = 'intOrEmpty';
    $f['search']['material']['func'] = 'required';
    $f['search']['material']['msg'] = 'Entrez une description du matériel demandé';
    $f['search']['volume']['func'] = 'int';
    $f['search']['volume']['msg'] = 'Entrez un volume en m3';
    $f['search']['deliverydate']['func'] = 'date';
    $f['search']['deliverydate']['msg'] = 'Entrez une date au format (jj.mm.aaaa)';	
		
    $f['search']['startdatework']['func'] = 'date';
    $f['search']['startdatework']['msg'] = 'Entrez une date au format (jj.mm.aaaa)';
    $f['search']['enddatework']['func'] = 'date';
    $f['search']['enddatework']['msg'] = 'Entrez une date au format (jj.mm.aaaa)';
		// field for comparaison
    $f['search']['dateCompare']['func'] = 'dateCompare';
    $f['search']['dateCompare']['msg'] = 'La date de fin doit être plus grande que la date de début';			
		
		
    $f['search']['district']['func'] = 'requiredNotNull';
    $f['search']['district']['msg'] = 'Entrez une région';
		
    $f['search']['catmatterrial']['func'] = 'int';
    $f['search']['catmatterrial']['msg'] = 'Entrez une catégorie';    

    $f['search']['designiationuscsmeuble']['func'] = 'categoryMaterial';
    $f['search']['designiationuscsmeuble']['msg'] = 'Entrez le type de matériel meuble';    
    $f['search']['designiationsimplemeubleha']['func'] = 'categoryMaterial';
    $f['search']['designiationsimplemeubleha']['msg'] = 'Entrez le type de matériel meuble HA';   
    
    $f['search']['designiationsimplemeublehb']['func'] = 'categoryMaterial';
    $f['search']['designiationsimplemeublehb']['msg'] = 'Entrez le type de matériel meuble HB';   
    
    $f['search']['designiationroche']['func'] = 'categoryMaterial';
    $f['search']['designiationroche']['msg'] = 'Entrez le type de matériel rocheux';   
    
    $f['search']['designiationsimplemeuble']['func'] = 'categoryMaterial';
    $f['search']['designiationsimplemeuble']['msg'] = 'Entrez le type de matériel meuble';    		
    
    // add or replace fields configuration with external values
    if(sizeOf($ffunctions)>0 && is_array($ffunctions)){
      foreach($ffunctions as $f=>$v){
        // to do
      }
    }
    $this->fieldsConf = $f;
  }
  function get_errorMsgs(){
    return $this->errorMsgs;
  }

  // *************************************************************
  // Validation functions
  // *************************************************************
 
  // ********************* Validation specific functions **********
  function f_offer_roles($fieldsValue){
    if(!$fieldsValue['rolemaitreouvrage'] && !$fieldsValue['rolearchitecte'] && !$fieldsValue['roleingenieur']
        && !$fieldsValue['roleentrepreneur'] && !$fieldsValue['roleautre'] ){
       $msg = $this->fieldsConf[$this->table]['roles']['msg'];
     }else{
     }
     return $msg;
  }
/** Check for each category at least one answer for the material 
  *
  */
  function f_categoryMaterial($fieldsValue, $field, $v){
    if($fieldsValue['catmatterrial'] == 1 ){
      if($field == 'designiationsimplemeubleha' && $v == 0) {
        $msg = $this->fieldsConf[$this->table][$field]['msg'];
      }
    }elseif($fieldsValue['catmatterrial'] == 2){
      if($field == 'designiationsimplemeublehb' && $v == 0) {
        $msg = $this->fieldsConf[$this->table][$field]['msg'];
      }
    }elseif($fieldsValue['catmatterrial'] == 3){
      if($field == 'designiationsimplemeuble' && $v == 0 ) {
        $msg = $this->fieldsConf[$this->table][$field]['msg'];
      }
    }elseif($fieldsValue['catmatterrial'] == 4){
			// le champ Si autre n'est pas contrôlé
      if($field == 'designiationroche' && $v == 0 ) {
        $msg = $this->fieldsConf[$this->table][$field]['msg'];
      }
		}
     return $msg;
  }  
	/**
		* Compares 2 dates
		*/
  public function f_dateCompare($fieldsValue, $field, $v){
    // Transformation date to timeStamp 22.08.2005 > timestamp
    $value = trim($value);
    $time1 = strtotime(trim($fieldsValue['startdatework']));
		$time2 = strtotime(trim($fieldsValue['enddatework']));
    if($time1 && $time2 && $time1 > $time2){
       $msg = $this->fieldsConf[$this->table][$field]['msg'];
     }else{
     }
		 return $msg;
  }		
  
  // ******************** Validation general functions  **********   
	
  function f_required($field,$val){
    $val = trim($val);
    if($val<>''){
      return '';
    }else{
      $msg = ($this->fieldsConf[$this->table][$field]['msg']<>'')?$this->fieldsConf[$this->table][$field]['msg']:'Le champ est obligatoire';
      return $msg;
    }
  }
  function f_requiredNotNull($field,$val){
    $val = trim($val);
    if($val<>'' && $val>0){
      return '';
    }else{
      $msg = ($this->fieldsConf[$this->table][$field]['msg']<>'')?$this->fieldsConf[$this->table][$field]['msg']:'Le champ est obligatoire';
      return $msg;
    }
  }  
  public function f_date($field, $value){
    // Transformation date to timeStamp 22.08.2005 > timestamp
    $value = trim($value);
    $timeStampValue = strtotime($value);
    if($timeStampValue > 0){
      $value = date('d.m.Y', $timeStampValue);
    }else{
      $msg = ($this->fieldsConf[$this->table][$field]['msg']<>'')?$this->fieldsConf[$this->table][$field]['msg']:'Entrez une date au format (jj.mm.aaaa)';
      return $msg;
    }
  }
	
  public function f_int($field, $value){
    $value = trim($value);
    $value = intVal($value);
    if($value > 0){
    }else{
      $msg = ($this->fieldsConf[$this->table][$field]['msg']<>'')?$this->fieldsConf[$this->table][$field]['msg']:'Entrez un nombre';
      return $msg;
    }
  }
  public function f_intOrEmpty($field, $value){
    $value = trim($value);
    if(  $value == '' || intVal($value) > 0){
    }else{
      $msg = ($this->fieldsConf[$this->table][$field]['msg']<>'')?$this->fieldsConf[$this->table][$field]['msg']:'Entrez un nombre';
      return $msg;
    }
  }
  
  public function f_email($field, $value){
    $value = trim($value);
    if ($value && t3lib_div::validEmail($value)){
    }else{
      $msg = ($this->fieldsConf[$this->table][$field]['msg']<>'')?$this->fieldsConf[$this->table][$field]['msg']:'Entrez une adresse mail valide';
      return $msg;
    }
  }     
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_validator.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_validator.php']);
}

?>