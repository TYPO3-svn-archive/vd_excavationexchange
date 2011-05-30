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
class tx_vdexcavationexchange_pageListMyAds extends tslib_pibase {
	var $prefixId = 'tx_vdexcavationexchange_pageListMyAds';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_vdexcavationexchange_pageListMyAds.php';	// Path to this script relative to the extension dir.
	var $extKey = 'vd_excavationexchange';	// The extension key.
  
  var $accessObj; // the access object
  var $records_id; // the records ID

  var $cmd; // the status, steps, commands, sub action in the main action
  var $cmdErrorMsg = array(); // the error msg for the sub actions
  var $startingPoint; // the page folder for the records Warning only one value

  var $offer; // the offer model
  var $response; // the response model
  var $offers; // the offers ad
  var $lotObj; // the lot model
  var $lots = array(); // the lots
  var $searchads; // the search ad
  var $searchadsResponses; // the responses
  var $archiveCase; // manage the archives view case
  
	function ini($content, $caller, $action)	{
		$this->conf = $caller->conf;
		$this->pi_setPiVarDefaults();
		$this->pi_USER_INT_obj=1;	// Configuring so caching is not expected. This value means that no cHash params are ever set. We do this, because it's a USER_INT model!
    // create cObj
    $this->cObj = t3lib_div::makeInstance('tslib_cObj');
    // get caller pluginmodel ref. Used with piVars    
    $this->caller = $caller;
    $this->startingPoint = $this->caller->conf['starting_points'];
    // check environment
    // check FE user authentication access management 
    $this->accessObj = t3lib_div::makeInstance('tx_vdexcavationexchange_access');
    $this->accessObj->ini($this->conf);
   
    // **************************************************************************
    // commun FORM preparation for all actions 
    // **************************************************************************    
    // FORM variables
    $this->formAction = $this->pi_getPageLink($GLOBALS['TSFE']->id);    
    // get record id if any

    // get the offer search respons Models
    $confDB['startingPoint'] = $this->startingPoint;
    $this->offer = t3lib_div::makeInstance('tx_vdexcavationexchange_offer');
    $this->offer->ini($confDB);
    $this->lotObj = t3lib_div::makeInstance('tx_vdexcavationexchange_offerlot');
    $this->lotObj->ini($confDB);    
    $this->searchads = t3lib_div::makeInstance('tx_vdexcavationexchange_search');
    $this->searchads->ini($confDB);
    $this->response = t3lib_div::makeInstance('tx_vdexcavationexchange_response');
    $this->response->ini($confDB);
    
    $this->action = $this->caller->piVars['action']?$this->caller->piVars['action']:$action;

    // **************************************************************************
    // MAIN ACTION
    // **************************************************************************
    // switch action read edit update delete ... 
    switch($this->action) {
      case 'read':
        $this->archivesCase = 0;
        $content = $this->readAdsListView();
        break;
      case 'readArchives':
        $this->archivesCase = 1;
        $content = $this->readAdsListView();
        break;
      case 'readOffers':
        $this->archivesCase = 0;
        $content = $this->readOffersListView();
        break;        
      case 'readOffersArchives':
        $this->archivesCase = 1;
        $content = $this->readOffersListView();
        break;
      case 'readSearchs':
        $this->archivesCase = 0;
        $content = $this->readSearchsListView();
        break;
      case 'readSearchsArchives':
        $this->archivesCase = 1;
        $content = $this->readSearchsListView();
        break;        
      default:
    }      
    $this->content = $content;
	}
  
  function getContent(){
    return $this->content;
  }
  
	/**
	 * get offer and search ads and display as a list
	 *
	 * @return	The content listing view 
	 */
  function readAdsListView(){
    $this->cmd = 'read';
    unset($this->cmdErrorMsg);
    
    // fe_user access management
    $access = $this->accessObj->checkAccess('','pageListMyAds','read');
    if(!(bindec($access) & bindec('0100'))){
      $this->cmd = 'readError';
      $this->cmdErrorMsg[] = 'Vous n\'êtes pas autorisé à voir cette page';
      // Get the FORM
      $content = $this->get_include_contents('view_canNotAccess.php');
      // return view
      return $content;  
    }  
    // get the offer records
    $queryConf['startingPoint'] = $this->startingPoint;
    $queryConf['fe_cruser_id'] = $GLOBALS['TSFE']->fe_user->user['ses_userid'];

    $offers = $this->offer->select('', $queryConf);
    if(!$offers){
      $this->cmd = 'noContent';
      $this->cmdErrorMsg[] = 'Les enregistrements sont introuvables';
    }else{
      $this->offers = $offers;
    }
    // get user lots
    if($this->archivesCase==1){
      $queryConf['archive'] = 1;
    }
		$queryConf['orderBy'] = 'offer_id,uid';
    $this->lots = $this->lotObj->select('', $queryConf);
			
		if(sizeof($this->lots)>0){
			// get the responses
			foreach($this->lots as $k=>$v){
				$lots_id[] = intval($k);
			}
			$queryConf['where'] = 'tx_vdexcavationexchange_response.lot_id IN (' . implode(',',$lots_id) .')';
			$res = $this->response->select('',$queryConf);
			// put the lot key ID
			foreach($res as $k=>$v){
				$this->lotsResponses[$v['lot_id']][] = $v;
			}
		}else{
				$this->cmd = 'noRecords';
		}

    // call the template view
    $content = $this->get_include_contents('view_listMyAds.php');
    // prepare final offer view
    // $content = $content;
               
    // return offer view
    return $content;
  } 
  function readOffersListView(){
    $this->cmd = 'read';
    unset($this->cmdErrorMsg);
    
    // fe_user access management
    $access = $this->accessObj->checkAccess('','pageListMyAds','read');
    if(!(bindec($access) & bindec('0100'))){
      $this->cmd = 'readError';
      $this->cmdErrorMsg[] = 'Vous n\'êtes pas autorisé à voir cette page';
      return false; // load the error page to do
    }  
    // get the offer records
    $queryConf['startingPoint'] = $this->startingPoint;
    $queryConf['fe_cruser_id'] = $GLOBALS['TSFE']->fe_user->user['ses_userid'];

    $offers = $this->offer->select('', $queryConf);
    if(!$offers){
      $this->cmd = 'noContent';
      $this->cmdErrorMsg[] = 'Les enregistrements sont introuvables';
    }else{
			
      $this->offers = $offers;
    }
    // get user lots
    if($this->archivesCase==1){
      $queryConf['archive'] = 1;
    }
		$queryConf['orderBy'] = 'offer_id,uid';
    $this->lots = $this->lotObj->select('', $queryConf);
		
		if(!$this->lots){
			$this->cmd = 'noRecords';
		}else{
			// get the responses
			foreach($this->lots as $k=>$v){
				$lots_id[] = intval($k);
			}
			$queryConf['where'] = 'tx_vdexcavationexchange_response.lot_id IN (' . implode(',',$lots_id) .')';
			$res = $this->response->select('',$queryConf);
			// put the lot key ID
			foreach($res as $k=>$v){
				$this->lotsResponses[$v['lot_id']][] = $v;
			}			
		}
		

 
    // call the template view
    $content = $this->get_include_contents('view_listMyAds.php');
    // prepare final offer view
    // $content = $content;
               
    // return offer view
    return $content;
  }
  function readSearchsListView(){
		
    $this->cmd = 'read';
    unset($this->cmdErrorMsg);
    // fe_user access management
    $access = $this->accessObj->checkAccess('','pageListMyAds','read');
    if(!(bindec($access) & bindec('0100'))){
      $this->cmd = 'canNotRead';
      // Get the FORM
      $content = $this->get_include_contents('view_canNotAccess.php');
      // return view
      return $content;
    }  
    // get the search records
    $queryConf['startingPoint'] = $this->startingPoint;
    $queryConf['fe_cruser_id'] = $GLOBALS['TSFE']->fe_user->user['ses_userid'];
    $queryConf['archive'] = ($this->archivesCase==1)?1:'';
    $searchads = $this->searchads->select('', $queryConf);
		if(!$searchads){
      $this->cmd = 'noRecords';
    }else{
      $this->searchads = $searchads;
      // get the responses
      foreach($this->searchads as $k=>$v){
        $lots_id[] = intval($k);
      }
      $queryConf['where'] = 'tx_vdexcavationexchange_response.search_id IN (' . implode(',',$lots_id) .')';
      $res = $this->response->select('',$queryConf);
      // put the lot key ID
      foreach($res as $k=>$v){
        $this->searchadsResponses[$v['search_id']][] = $v;
      }
    }

    // call the template view
    $content = $this->get_include_contents('view_listMyAdsSearch.php');
    // prepare final offer view
    // $content = $content;
               
    // return offer view
    return $content;
  }   
  
  // ****************************************************
  function get_include_contents($filename) {
    ob_start();
    include $filename;
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;
  }  
  
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_pageListMyAds.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_pageListMyAds.php']);
}

?>