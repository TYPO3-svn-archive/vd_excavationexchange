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
class tx_vdexcavationexchange_access extends tslib_pibase {
	var $prefixId = 'tx_vdexcavationexchange_access';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_vdexcavationexchange_access.php';	// Path to this script relative to the extension dir.
	var $extKey = 'vd_excavationexchange';	// The extension key.
	var $access = array(); // user access[page][content][role=(admin owner loggedIn public)] = 'CRUD'
  var $fe_user; // the FE user
	/**
	 * The main method of the class
   * Set the access for the user
	 *
	 * @param	array		$conf: the caller conf
	 * @return boolean
	 */
   
   
	function ini($conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
    //
    $return = true;
    // fe user informations
    $this->fe_user = $GLOBALS['TSFE']->fe_user->user;
    
    // ********************************************
    // Configuration of the access
    // ********************************************
    $this->access['pageOffer'] = Array(
      'create'=>Array(
        'admin' => '1111',
        'owner' => '1111',
        'loggedIn'=> '1000',
        'public'=> '0000'
      ),
      'read'=>Array(
        'admin' => '1111',
        'owner' => '1111',
        'loggedIn'=> '0100',
        'public'=> '0000'
      ),
      'update'=>Array(
        'admin' => '1111',
        'owner' => '1111',
        'loggedIn'=> '0000',
        'public'=> '0000'
      ),
      'delete'=>Array(
        'admin' => '1111',
        'owner' => '1111',
        'loggedIn'=> '0000',
        'public'=> '0000'
      )       
    );
    $this->access['pageListMyAds'] = Array(
      'read'=>Array(
        'admin' => '1111',
        'owner' => '1111',
        'loggedIn'=> '0100',
        'public'=> '0000'
      )      
    );
    $this->access['pageListOffers'] = Array(
      'read'=>Array(
        'admin' => '1111',
        'owner' => '1111',
        'loggedIn'=> '0100',
        'public'=> '0100'
      )      
    );
    $this->access['pageListSearchAds'] = Array(
      'read'=>Array(
        'admin' => '1111',
        'owner' => '1111',
        'loggedIn'=> '0100',
        'public'=> '0100'
      )      
    );  
    $this->access['pageResponse'] = Array(
      'create'=>Array(
        'admin' => '1111',
        'owner' => '1111',
        'loggedIn'=> '1000',
        'public'=> '1000'
      )      
    );    
    $this->access['pageLot'] = Array(
      'create'=>Array(
        'admin' => '1111',
        'owner' => '1111',
        'loggedIn'=> '1000',
        'public'=> '0100'
      ),
      'read'=>Array(
        'admin' => '1111',
        'owner' => '1111',
        'loggedIn'=> '0100',
        'public'=> '0000'
      ),
      'update'=>Array(
        'admin' => '1111',
        'owner' => '1111',
        'loggedIn'=> '0000',
        'public'=> '0000'
      ),
      'delete'=>Array(
        'admin' => '1111',
        'owner' => '0000',
        'loggedIn'=> '0000',
        'public'=> '0000'
      ) 
    );   
    $this->access['pageSearch'] = Array(
      'create'=>Array(
        'admin' => '1111',
        'owner' => '1111',
        'loggedIn'=> '1000',
        'public'=> '0100'
      ),
      'read'=>Array(
        'admin' => '1111',
        'owner' => '1111',
        'loggedIn'=> '0100',
        'public'=> '0000'
      ),
      'update'=>Array(
        'admin' => '1111',
        'owner' => '1111',
        'loggedIn'=> '0010',
        'public'=> '0000'
      ),
      'delete'=>Array(
        'admin' => '1111',
        'owner' => '0000',
        'loggedIn'=> '0000',
        'public'=> '0000'
      ) 
    );
    
    $this->access['pageListAdsAdmin'] = Array(
      'read'=>Array(
        'admin' => '1111',
        'owner' => '0000',
        'loggedIn'=> '0000',
        'public'=> '0000'
      ),
    ); 
		return $return;
	}
  public function checkAccess($record,$page,$action=''){
    $this->fe_user['ses_userid'];
    // roles
    $roles['public'] = 1;
    $roles['loggedIn'] = $this->fe_user['ses_userid']?1:0;
    if($record['fe_cruser_id']>0){
      $roles['owner'] = ($record['fe_cruser_id'] === $this->fe_user['ses_userid'])?1:0;
    }

    // admin manged by typo3 FE groups and TS
    $groupAdmin = trim($this->conf[FE_groupAdmin]);
    $groups = explode(',',$this->fe_user['usergroup']);
    $roles['admin'] =  in_array($groupAdmin, $groups)?1:0; // to do
    // content access
    if($page && $action){
      $access = $this->access[$page][$action];
    }elseif($page){
      $access = $this->access[$page];
    }else{
      // problem
      $access = false;
      return '0000';
    }
    // and cumullate rights
    $right = '0000';
    if(is_array($roles) && $access){
      foreach($roles as $r=>$v){
          $right = $v?bindec($right) | bindec($access[$r]):$right;
      }
    }
    return decbin($right);
  }

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_access.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_access.php']);
}

?>