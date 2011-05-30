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
class tx_vdexcavationexchange_pageSearch extends tslib_pibase {
	var $prefixId = 'tx_vdexcavationexchange_pageSearch';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_vdexcavationexchange_pageSearch.php';	// Path to this script relative to the extension dir.
	var $extKey = 'vd_excavationexchange';	// The extension key.
	
	var $accessObj; // the access object
	var $rec_id; // the record ID if any
	var $formAction; // the action link in the FORM
	var $submit; // the submit command from the user
	var $cmd; // the status, steps, commands, sub action in the main action
	var $cmdErrorMsg = array(); // the error msg for the sub actions
	var $startingPoint; // the page folder for the records Warning only one value
	var $pagesRedirect; // list of redirections see $this->conf['pagesRedirect.']
	var $emailObj; // the email object
	var $fieldsItems; // the lists for the object
	var $model;
	var $annonceType = 'search';
	
	var $formSession; // the session	
	/**
	* The main method of the PlugIn
	*
	* @param	string		$content: The PlugIn content
	* @param	array		$conf: The PlugIn configuration
	* @return	The content that is displayed on the website
	*/
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
		// get record id if any
		$confDB['startingPoint'] = $this->startingPoint;
		$this->rec_id = intval($this->caller->piVars['search']['rec_id']);
		// get search Model
		$this->model = t3lib_div::makeInstance('tx_vdexcavationexchange_search');
		$this->model->ini( $confDB);
		$this->fieldsItems = $this->model->fieldsItems;
		
		// FORM variables
		$this->formAction = $this->pi_getPageLink($GLOBALS['TSFE']->id);
		// request Data (data for the search id,....)
		// and request [submit][save] (save, cancel)
		$this->submit = $this->caller->piVars['search']['submit'];
		$this->action = $this->caller->piVars['action']?$this->caller->piVars['action']:$action;
		// email notification
		$this->emailObj = t3lib_div::makeInstance('tx_vdexcavationexchange_email');
		$this->emailObj->ini($this);
		// **************************************************************************
		// MAIN ACTION
		// **************************************************************************
		// ARCHIVER LE LOT
		if($this->caller->piVars['search']['submit_archiver']){
			$this->action = 'archiver';
		}elseif($this->caller->piVars['search']['submit_delete']){
			$this->action = 'delete';
		}
		// Activer LE LOT
		if($this->caller->piVars['search']['submit_activer']){
			$this->action = 'activer';
		}
		// Send email		
		if($this->caller->piVars['search']['send_email']){
			$this->action = 'sendEmail';
		}
		// redirects
		$conf = array('parameter' =>  $this->conf['pagesRedirect.']['myAdsSearch'],'useCashHash' => true,'returnLast' => 'url',);
		$this->redirectToMyAds = $this->cObj->typoLink('', $conf);			
		// switch action read edit update delete ... 
		switch($this->action) {
			case 'create':
				$content = $this->createSearchView();
				break;
			case 'read':
				// not used
				// $content = $this->readSearchView();
				break;
			case 'update':
				$content = $this->updateSearchView();
				break;         
			case 'delete':
				$id = key($this->caller->piVars['search']['submit_delete']);
				$content = $this->deleteLot($id);
				break; 
			case 'archiver':
				$id = key($this->caller->piVars['search']['submit_archiver']);
				$content = $this->archiverLot($id);
				break; 
			case 'activer':
				$id = key($this->caller->piVars['search']['submit_activer']);
				$content = $this->activerLot($id);
				break;
		case 'sendEmail':
			// parameter from link of from FORM is different !
			if(is_array($this->caller->piVars['search']['send_email'])){
				$id = key($this->caller->piVars['search']['send_email']);
			}else{
				$id = ($this->caller->piVars['search']['send_email']);
			}
			$this->lotID = $id;
			$content = $this->sendEmailLot();
			break; 					
			default:
		}      
		$this->content = $content;
	}
	
	function getContent(){
		return $this->content;
	}
	/**
	* get the search record, prepare and return the view for the search
	*
	* @param	string		$content: The PlugIn content
	* @param	array		$conf: The PlugIn configuration
	* @return	The content view for an search that is displayed on the website
	*/
	
/**
	* send an email of the lot
	*
	* @return	void
	*/
	function sendEmailLot(){
		
		// fe_user access management can update 
		$rec = $this->model->get_fecruserid($this->rec_id); 
		$access = $this->accessObj->checkAccess($rec,'pageSearch','update');
		if(!(bindec($access) & bindec('0010'))){
			$this->cmd = 'canNotUpdate';
			// Get the FORM
			$content = $this->get_include_contents('view_canNotAccess.php');
			return $content;      
		}		
		// get lot
		$lotQueryConf['startingPoint'] = $this->startingPoint;
		$lot = $this->model->select(intVal($this->rec_id),$lotQueryConf);
		// get response
		$respID = $this->caller->piVars[resp];
		$respQueryConf['startingPoint'] = $this->startingPoint;		
		$responseObj = t3lib_div::makeInstance('tx_vdexcavationexchange_response');
		$response = $responseObj->select(intVal($respID),$respQueryConf);
		// get contents
		$content = $this->readSearchView();
		if($this->submit['sendEmail']){
			// send the email after confirmation
			$conf = array(
				'parameter' =>  $this->conf['pagesRedirect.']['searchListPage'],
				'additionalParams' => '&tx_vdexcavationexchange_pi1[id]=' . $this->lotID,
				'useCashHash' => true,
				'returnLast' => 'url',
			);
			$url = $this->cObj->typoLink('', $conf);
			$link = t3lib_div::getIndpEnv('TYPO3_SITE_URL').$url;
			$resmail = $this->emailObj->sendDetails($lot[$this->lotID],$response[$respID], $content, $link);
			if($resmail){
				// Redirect to the page "Mon dossier"
				Header('Location: '.t3lib_div::locationHeaderUrl($this->redirectToMyAds));
				exit;
			}else{
				$this->cmd = 'sendEmailFailed';
				$content = $this->get_include_contents('view_sendMailConfirmation.php');
			}
		}elseif($this->submit['cancel']){
			// Redirect to the page "Mon dossier"
			Header('Location: '.t3lib_div::locationHeaderUrl($this->redirectToMyAds));
			exit;
		}else{
			// display the confirmation page
			$this->recordDetails = $content;
			$content = $this->get_include_contents('view_sendMailConfirmation.php', $response[$respID]);
		}
		return $content;
	}	
	
	/**
	* get the lot record, prepare and return the view for one lot
	*
	* @param	string		$content: The PlugIn content
	* @param	array		$conf: The PlugIn configuration
	* @return	The content view for an ad that is displayed on the website
	*/
	function readSearchView(){
		// fe_user access management can read 
		$rec = $this->model->get_fecruserid($this->rec_id); 
		$access = $this->accessObj->checkAccess($rec,'pageSearch','read');
		if(!(bindec($access) & bindec('0100'))){
			$this->cmd = 'canNotRead';
			// Get the FORM
			$content = $this->get_include_contents('view_canNotAccess.php');
			return $content;      
		}
		$queryConf['startingPoint'] = $this->startingPoint;	
		// template for the view
		$lot = $this->model->select($this->rec_id,$queryConf);
		if($lot){ 
			// fe_user access management
			$access = $this->accessObj->checkAccess($lot[$this->rec_id],'pageSearch','read');
			if(!(bindec($access) & bindec('0100'))){
				$this->cmd = 'readForbidden';
				$this->data = '';			
			}else{
				$this->cmd = 'read';
				$this->data = $lot[$this->rec_id];
			}
		}
		$content = $this->get_include_contents('view_searchDetails.php');
		
		return $content;
	}
	/**
	* make the search view for the create action depending of the submit command [save, cancel]
	* 
	* 
	* @param	string		$content: The PlugIn content
	* @param	array		$conf: The PlugIn configuration
	* @return	The content view for an search that is displayed on the website
	*/
	function createSearchView(){
		// fe_user access management
		$access = $this->accessObj->checkAccess('','pageSearch','create');
		if(!(bindec($access) & bindec('1000'))){
			$this->cmd = 'canNotCreate';
			// Get the FORM
			$content = $this->get_include_contents('view_canNotAccess.php');
			// return view
			return $content;  
		}
		// debug($GLOBALS['TSFE']->fe_user->user['ses_userid']);
		
		// session
    $submittedKey = $this->caller->piVars['formsession']['key'];
    $formSession = $GLOBALS["TSFE"]->fe_user->getKey('ses',$this->extKey);
    $keyExist = ($submittedKey == $formSession['key'])?true:false;		
		$this->generateSessionKey();
    // Check if the form for double click and multiple submit
    if(!$keyExist && ($this->submit['save'] || $this->submit['update'] || $this->submit['cancel'])){
      $this->cmd = 'nsubmitError';
		}elseif($this->submit['save'] && !$this->rec_id){
			$this->cmd = 'save';    
			$this->caller->piVars['search']['roles'] = 1; // validate roles
			// validation configuration title = required, date = date
			// contol/validation here as piVars[lotNew][catmatterrial] is empty for radio buttons
			if(!$this->caller->piVars['search']['catmatterrial']){
				$this->caller->piVars['search']['catmatterrial'] = '';
			}				
			// create validator
			$validator = t3lib_div::makeInstance('tx_vdexcavationexchange_validator');
			$valid = $validator->validate('search',$this->caller->piVars['search']);
			$this->validation = $validator->get_errorMsgs(); // [field][msg]

			// use the piVars value for the data
			$this->data['uid'] = '';
			$this->data['fe_cruser_id'] = $GLOBALS['TSFE']->fe_user->user['ses_userid'];
			$this->data = array_merge($this->data, $this->caller->piVars['search']);
			
			// validation end
			if($valid ){ 
				// SAVE data
				$queryConf['startingPoint'] = $this->startingPoint;
				$recID = $this->model->insert($this->data,$queryConf);
				if($recID){
					$this->rec_id = $recID;
					// EMAIL SEND
					if($this->conf[sendEmail]){
						$emailres = $this->emailObj->send();
						$this->cmd = $emailres?'emailSuccess':'emailFailed';
					}	
					if(1){
						$this->clearFormKey();
						// Redirect to the page "Mon dossier"
						Header('Location: '.t3lib_div::locationHeaderUrl($this->redirectToMyAds));
						exit;
					}else{
						// get record back
						$this->cmd = 'update';
						$this->data['uid'] = $recID;
						$queryConf['noAccessClause'] = 1;
						$rec = $this->model->select($recID, $queryConf);
						$this->data = $rec[$this->rec_id];
						
					}
				}else{

					$this->cmd = 'saveFailed';
				}          
			}else{
				$this->cmd = 'saveNotValid';
				// Can not save -> display the FORM with errors
			}
		}elseif($this->submit['update']){
			// 1 display the form back fir update
			$this->cmd = 'update';	
			$this->clearFormKey();
			// Redirect to the page "Mon dossier"
			Header('Location: '.t3lib_div::locationHeaderUrl($this->redirectToMyAds));
			exit;
			
		}elseif($this->submit['cancel']){
			$this->cmd = 'cancel';
			// reset values for the form
			unset($this->caller->piVars);
		}else{
			// *****************************************
			// default case "edit empty form"
			// *****************************************
			$this->cmd = 'new';
			unset($this->rec_id);
			unset($this->data);
			unset($this->caller->piVars);
		}
		
		// Get the FORM
		$content = $this->get_include_contents('view_searchForm.php');
		// return search view
		return $content;
	}
	function updateSearchView(){
		
		// rec_id exist		
		if($this->rec_id<1){
			// Redirect to the page "Mon dossier"
			Header('Location: '.t3lib_div::locationHeaderUrl($this->redirectToMyAds));
			exit;
		}    		
		
		// fe_user access management can update 
		$rec = $this->model->get_fecruserid($this->rec_id);
		$access = $this->accessObj->checkAccess($rec,'pageSearch','update');
		if(!(bindec($access) & bindec('0010'))){
			$this->cmd = 'canNotUpdate';
			// Get the FORM
			$content = $this->get_include_contents('view_canNotAccess.php');
			// return search view
			return $content;      
		}
		$queryConf['startingPoint'] = $this->startingPoint;
		
		if($this->submit['save'] || $this->submit['saveAddLot']){
			$this->cmd = 'save';    
			// validation configuration title = required, date = date
			$this->caller->piVars['search']['roles'] = 1; // validate roles
			// contol/validation here as piVars[lotNew][catmatterrial] is empty for radio buttons
			if(!$this->caller->piVars['search']['catmatterrial']){
				$this->caller->piVars['search']['catmatterrial'] = '';
			}								
			// create validator
			$validator = t3lib_div::makeInstance('tx_vdexcavationexchange_validator');
			$valid = $validator->validate('search',$this->caller->piVars['search']);
			$this->validation = $validator->get_errorMsgs(); // [field][msg]
			// validation end
			// use the piVars value for the data
			$this->data['uid'] = $this->rec_id;
			// $this->data['fe_cruser_id'] = $GLOBALS['TSFE']->fe_user->user['ses_userid'];
			$this->data = array_merge($this->data, $this->caller->piVars['search']);

			if($valid){ 
				// SAVE THE DATA IN DB
				$res = $this->model->update($this->data,$queryConf);
				if($res ){          
					// save lots
					$resFinal = true;
					$this->cmd = 'updateSuccess';
					/*
					$queryConf['startingPoint'] = $this->startingPoint;
					$record = $this->model->select($this->rec_id,$queryConf);
					$this->data = $record[$this->rec_id];
					*/
				}else{
					$this->cmd = 'updateFailed';
				}          
			}else{
				$this->cmd = 'updateNotValid';
				// Can not save -> display the FORM with errors
			}
		}elseif($this->submit['cancel']){
			$this->cmd = 'cancel';
			// reste values for the form
			unset($this->caller->piVars);
			// get lots back
			$record = $this->model->select($this->rec_id,$queryConf);
			$this->data = $record[$this->rec_id];
		}else{
			// *******************
			// default case "edit form"
			// *******************
			// template for the view
			$record = $this->model->select($this->rec_id,$queryConf);
			if($record){ 
				// fe_user access management
				$access = $this->accessObj->checkAccess($record[$this->rec_id],'pageSearch','update');
				if(!(bindec($access) & bindec('0010'))){
					$this->cmd = 'updateForbidden';
					$this->data = '';
				}else{
					$this->cmd = 'update';
					$this->data = $record[$this->rec_id];
				}    
			}else{
				$this->cmd = 'updateEmpty';
			}
		
		}
		// Get the FORM
		$content = $this->get_include_contents('view_searchForm.php');
		// prepare final search view
		$content = $content;
		// return search view
		return $content;
	}

	function archiverLot($id){
		// fe_user access management
		$rec = $this->model->get_fecruserid($id);
		$access = $this->accessObj->checkAccess($rec,'pageSearch','update');
		if(!(bindec($access) & bindec('0010'))){
			$this->cmd = 'canNotUpdate';
			return false;  
		}    
		$id=intVal($id);
		$res = $this->model->archiver($id);
		$this->cmd = $res?'archiveSuccess':'archiveFailed';

		if(0){
			if($red = $this->caller->piVars['redirect']){
			// Redirect to the page "Mon dossier"
			$conf = array('parameter' =>  $this->conf['pagesRedirect.'][$red],'useCashHash' => true,'returnLast' => 'url',);
			$redirectTo = $this->cObj->typoLink('', $conf);					
					
			$redirectTo = t3lib_div::getIndpEnv('TYPO3_SITE_URL').$this->conf['pagesRedirect.'][$red];
			Header('Location: '.t3lib_div::locationHeaderUrl($redirectTo));
			exit;
			}
		}else{
			// Get the FORM
				$content = $this->get_include_contents('view_offerFormArchiver.php'); // yes we use the same view view_offerFormArchiver
			// prepare final lot view
			$content = $content;
			// return  lot view
			return $content;
		}
	}  

	function activerLot($id){
		// fe_user access management
		$rec = $this->model->get_fecruserid($id);
		$access = $this->accessObj->checkAccess($rec,'pageSearch','update');
		if(!(bindec($access) & bindec('0010'))){
			$this->cmd = 'canNotUpdate';
			return false;  
		}    
		$id=intVal($id);
		$res = $this->model->activer($id);
		$this->cmd = $res?'activationSuccess':'activationFailed';
		// Get the FORM
		$content = $this->get_include_contents('view_offerFormArchiver.php');
		// prepare final  lot view
		$content = $content;
		// return  lot view
		return $content;            
	}  
	
	function deleteLot($id){
		// fe_user access management
		$rec = $this->model->get_fecruserid($id);  
		$access = $this->accessObj->checkAccess($rec,'pageSearch','delete');
		if(!(bindec($access) & bindec('0011'))){
			$this->cmd = 'canNotDelete';
			// Get the FORM
			$content = $this->get_include_contents('view_canNotAccess.php');
			return $content;    
		}    
		$id=intVal($id);
		$res = $this->model->delete($id);
		$this->cmd = $res?'deleteSuccess':'deleteFailed';
		if($red = $this->caller->piVars['redirect']){
			// $conf = array('parameter' =>  $this->conf['pagesRedirect.'][$red],'useCashHash' => true,'returnLast' => 'url',);
			// $redirectTo = $this->cObj->typoLink('', $conf);	
			// Header('Location: '.t3lib_div::locationHeaderUrl($redirectTo));
			// exit;
		}
		// Get the FORM
		$content = $this->get_include_contents('view_offerFormArchiver.php'); // yes we use the same view view_offerFormArchiver
		// return the confirmation
		return $content;
	}  
	// ****************************************************
	function get_include_contents($filename, $params='') {
		ob_start();
		include $filename;
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	
	/**
		* genereate session and form key
		*/
	function generateSessionKey(){
		 // genereate session and form key
		$this->formSession['key'] = rand();
		$GLOBALS["TSFE"]->fe_user->setKey('ses',$this->extKey, $this->formSession);
		$GLOBALS["TSFE"]->storeSessionData();
	}		
	/**
		* clear the form key
		*/
	function clearFormKey(){
		 // session genereate key
		$this->formSession['key'] = null;
	}	
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_pageSearch.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_pageSearch.php']);
}

?>