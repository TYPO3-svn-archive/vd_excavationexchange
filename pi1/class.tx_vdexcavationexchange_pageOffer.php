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
class tx_vdexcavationexchange_pageOffer extends tslib_pibase {
	var $prefixId = 'tx_vdexcavationexchange_pageOffer';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_vdexcavationexchange_pageOffer.php';	// Path to this script relative to the extension dir.
	var $extKey = 'vd_excavationexchange';	// The extension key.
	
	var $accessObj; // the access object
	var $rec_id; // the record ID if any
	var $formAction; // the action link in the FORM
	var $submit; // the submit command from the user
	var $cmd; // the status, steps, commands, sub action in the main action
	var $cmdErrorMsg = array(); // the error msg for the sub actions
	var $startingPoint; // the page folder for the records Warning only one value
	var $pagesRedirect; // list of redirections see $this->conf['pagesRedirect.']
	var $model; // the offer object
	var $lotObj; // the lot object
	var $lotNew; // the new lot
	var $lots = array(); // the existing lots array
	var $emailObj; // the email object
	var $fieldsItems; // lot list of items
	var $fieldsItemsOffer; // offer list of items
	var $annonceType = 'offer';

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
		$confDB['startingPoint'] = intval($this->startingPoint);
		// get record id if any
		$this->rec_id = intval($this->caller->piVars['offer']['rec_id']);
		// get offer Model
		$this->model = t3lib_div::makeInstance('tx_vdexcavationexchange_offer');
		$this->model->ini($confDB);
		$this->fieldsItemsOffer = $this->model->fieldsItems;
		$this->lotObj = t3lib_div::makeInstance('tx_vdexcavationexchange_offerlot');
		$this->lotObj->ini($confDB);
		$this->fieldsItems = $this->lotObj->fieldsItems;

		// FORM variables
		$this->formAction = $this->pi_getPageLink($GLOBALS['TSFE']->id);
		// request Data (data for the offer id,....)
		// and request [submit][save] (save, cancel)
		$this->submit = $this->caller->piVars['offer']['submit'];
		$this->action = $this->caller->piVars['action']?$this->caller->piVars['action']:$action;
		// email notification
		$this->emailObj = t3lib_div::makeInstance('tx_vdexcavationexchange_email');
		$this->emailObj->ini($this->caller);
		// **************************************************************************
		// MAIN ACTION
		// **************************************************************************
		// ARCHIVER LE LOT
		if($this->caller->piVars['offer']['submit_archiver']){
			$this->action = 'archiver';
		}elseif($this->caller->piVars['offer']['submit_delete']){
			$this->action = 'delete';
		}
		// Activer LE LOT
		if($this->caller->piVars['offer']['submit_activer']){
			$this->action = 'activer';
		}
		// Send email		
		if($this->caller->piVars['offer']['send_email']){
			$this->action = 'sendEmail';
		}		
		// redirects
		$conf = array('parameter' =>  $this->conf['pagesRedirect.']['myAds'],'useCashHash' => true,'returnLast' => 'url',);
		$this->redirectToMyAds = $this->cObj->typoLink('', $conf);
		
		// switch action read edit update delete ... 
		switch($this->action) {
		case 'create':
			$content = $this->createOfferView();
			break;
		case 'read':
			// not used $content = $this->readLotView($id);
			break;
		case 'update':
			$content = $this->updateOfferView();
			break;         
		case 'delete':
			$id = key($this->caller->piVars['offer']['submit_delete']);
			$content = $this->deleteLot($id);
			break; 
		case 'archiver':
			$id = key($this->caller->piVars['offer']['submit_archiver']);
			$content = $this->archiverLot($id);
			break; 
		case 'activer':
			$id = key($this->caller->piVars['offer']['submit_activer']);
			$content = $this->activerLot($id);
			break;
		case 'sendEmail':
			// parameter from link of from FORM is different !
			if(is_array($this->caller->piVars['offer']['send_email'])){
				$id = key($this->caller->piVars['offer']['send_email']);
			}else{
				$id = ($this->caller->piVars['offer']['send_email']);
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
	* send an email of the lot
	*
	* @return	void
	*/
	function sendEmailLot(){
		// fe_user access management can update 
		$rec = $this->lotObj->get_fecruserid($this->lotID); 
		$access = $this->accessObj->checkAccess($rec,'pageOffer','update');
		if(!(bindec($access) & bindec('0010'))){
			$this->cmd = 'canNotUpdate88';
			// Get the FORM
			$content = $this->get_include_contents('view_canNotAccess.php');
			return $content;      
		}		
		// get lot
		$lotQueryConf['startingPoint'] = $this->startingPoint;
		$lot = $this->lotObj->select(intVal($this->lotID),$lotQueryConf);
		// get response
		$respID = $this->caller->piVars[resp];

		$respQueryConf['startingPoint'] = $this->startingPoint;		
		$responseObj = t3lib_div::makeInstance('tx_vdexcavationexchange_response');
		$response = $responseObj->select(intVal($respID),$respQueryConf);
		// get contents
		$content = $this->readLotView($this->lotID);
		if($this->submit['sendEmail']){
			// send the email after confirmation
			$conf = array(
				'parameter' =>  $this->conf['pagesRedirect.']['offresListPage'],
				'additionalParams' => '&tx_vdexcavationexchange_pi1[id]=' . $this->lotID,
				'useCashHash' => true,
				'returnLast' => 'url',
			);
			$url = $this->cObj->typoLink('', $conf);
			$link = t3lib_div::getIndpEnv('TYPO3_SITE_URL').$url;	
			$resmail = $this->emailObj->sendDetails($lot[$this->lotID],$response[$respID], $content, $link);

			// send the email after confirmation

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
	* @return	The content view for an offer that is displayed on the website
	*/
	function readLotView($lotID){
		$lotID = intVal($lotID);
		// fe_user access management can read 
		$rec = $this->lotObj->get_fecruserid($lotID); 
		$access = $this->accessObj->checkAccess($rec,'pageOffer','read');
		if(!(bindec($access) & bindec('0100'))){
			$this->cmd = 'canNotUpdate';
			// Get the FORM
			$content = $this->get_include_contents('view_canNotAccess.php');
			return $content;      
		}
		
    
		
		$lotQueryConf['startingPoint'] = $this->startingPoint;
		$queryConf['startingPoint'] = $this->startingPoint;	
		
		// template for the view
		$offer = $this->model->select($this->rec_id,$queryConf);
		if($offer){ 
			// fe_user access management
			$access = $this->accessObj->checkAccess($offer[$this->rec_id],'pageOffer','read');
			if(!(bindec($access) & bindec('0100'))){
				$this->cmd = 'readForbidden';
				$this->data = '';
				$this->lot = '';
			}else{
				$this->cmd = 'read';
				$this->data = $offer[$this->rec_id];
				$this->lot = $this->lotObj->select($lotID,$lotQueryConf);
				$this->lot = $this->lot[$lotID];
			}
		}
		$content = $this->get_include_contents('view_offerDetails.php');
		
		return $content;
	}
	/**
	* make the offer view for the create action depending of the submit command [save, cancel]
	* 
	* 
	* @param	string		$content: The PlugIn content
	* @param	array		$conf: The PlugIn configuration
	* @return	The content view for an offer that is displayed on the website
	*/
	function createOfferView(){
		// fe_user access management
		$access = $this->accessObj->checkAccess('','pageOffer','create');
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
    // Check if the form for double click and multiple submit
		$this->generateSessionKey();
    if(!$keyExist && ($this->submit['save'] || $this->submit['update'] || $this->submit['cancel'])){
      $this->cmd = 'nsubmitError';
			
		}elseif($this->submit['save'] && !$this->rec_id){
			$this->cmd = 'save';    
			$this->caller->piVars['offer']['roles'] = 1; // validate roles
			// validation configuration title = required, date = date
			// create validator
			$validator = t3lib_div::makeInstance('tx_vdexcavationexchange_validator');
			$valid = $validator->validate('offer',$this->caller->piVars['offer']);
			$this->validation = $validator->get_errorMsgs(); // [field][msg]

			// use the piVars value for the data
			$this->data['uid'] = '';
			$this->data['fe_cruser_id'] = $GLOBALS['TSFE']->fe_user->user['ses_userid'];
			$this->data = array_merge($this->data, $this->caller->piVars['offer']);

			// LOT NEW
			// contol/validation here as piVars[lotNew][catmatterrial] is empty for radio buttons
			if(!$this->caller->piVars['lotNew']['catmatterrial']){
				$this->caller->piVars['lotNew']['catmatterrial'] = '';
			}          
			$validLot = $validator->validate('offerlot', $this->caller->piVars['lotNew']);
			
			
			$this->validationLotNew = $validator->get_errorMsgs(); // [field][msg]
			$this->lotNew['uid'] = '';
			$this->lotNew['offer_id'] = $this->rec_id;
			$this->lotNew['fe_cruser_id'] = $GLOBALS['TSFE']->fe_user->user['ses_userid'];
			$this->lotNew = array_merge($this->lotNew, $this->caller->piVars['lotNew']);
			
			// validation end
			if($valid && $validLot){ 
				// SAVE data
				$queryConf['startingPoint'] = $this->startingPoint;
				$recID = $this->model->insert($this->data,$queryConf);
				if($recID){
					// SAVE LOT
					$this->lotNew['offer_id'] = $recID;
					$lotID = $this->lotObj->insert($this->lotNew,$queryConf);
					$this->cmd = $lotID?'saveSuccess':'saveLotFailed';
					// end SAVE LOT
					$this->rec_id = $recID;
					if($lotID){
						// EMAIL SEND
						if($this->conf[sendEmail]){
							$emailres = $this->emailObj->send();
							$this->cmd = $emailres?'emailSuccess':'emailFailed';
						}
					}
					
					if(1){
						// Redirect to the page "Mon dossier"
						Header('Location: '.t3lib_div::locationHeaderUrl($this->redirectToMyAds));
						exit;
					}else{
						// get record back
						
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
			// manage lots
		}
		
		if($this->cmd=='new' || $this->cmd=='cancel' || $this->cmd=='update'){
			// session genereate key
			$this->generateSessionKey();
		}
		
		// Get the FORM
		$content = $this->get_include_contents('view_offerForm.php');
		// return offer view
		return $content;
	}
	
	function updateOfferView(){
		
		// rec_id exist		
		if($this->rec_id<1){
			// Redirect to the page "Mon dossier"
			Header('Location: '.t3lib_div::locationHeaderUrl($this->redirectToMyAds));
			exit;
		}    
		
		// fe_user access management can update 
		$rec = $this->model->get_fecruserid($this->rec_id); 
		$access = $this->accessObj->checkAccess($rec,'pageOffer','update');
		
		if(!(bindec($access) & bindec('0010'))){
			$this->cmd = 'canNotUpdate';
			// Get the FORM
			$content = $this->get_include_contents('view_canNotAccess.php');
			return $content;      
		}
		$lotQueryConf['where'] = 'offer_id = '.intVal($this->rec_id);
		$lotQueryConf['startingPoint'] = $this->startingPoint;
		$queryConf['startingPoint'] = $this->startingPoint;
		
		if($this->submit['save'] || $this->submit['saveAddLot']){
			$this->cmd = 'save';    
			// validation configuration title = required, date = date
			$this->caller->piVars['offer']['roles'] = 1; // validate roles
			// create validator
			$validator = t3lib_div::makeInstance('tx_vdexcavationexchange_validator');

			$valid = $validator->validate('offer',$this->caller->piVars['offer']);
			$this->validation = $validator->get_errorMsgs(); // [field][msg]
			// validation end
			// use the piVars value for the data
			$this->data['uid'] = $this->rec_id;
			// $this->data['fe_cruser_id'] = $GLOBALS['TSFE']->fe_user->user['ses_userid'];
			$this->data = array_merge($this->data, $this->caller->piVars['offer']);
			// lots
			// get the lots for the offer id
			$this->lots = $this->lotObj->select('',$lotQueryConf);
			$validLotsFinal = true;
			foreach($this->lots as $k0=>$lot){
				// contol/validation here as piVars[lotNew][catmatterrial] is empty for radio buttons
				if(!$this->caller->piVars['lot_'.$lot['uid']]['catmatterrial']){
					$this->caller->piVars['lot_'.$lot['uid']]['catmatterrial'] = '';
				}
				
				$validLots = $validator->validate('offerlot',$this->caller->piVars['lot_'.$lot['uid']]);
				$this->validationLot[$lot['uid']] = $validator->get_errorMsgs(); // [field][msg]
				// use the piVars lot_x value for the data to merge
				if(is_array($this->caller->piVars['lot_'.$lot['uid']])){
					$this->lots[$lot['uid']] = array_merge($lot, $this->caller->piVars['lot_'.$lot['uid']]);
				}
				$validLotsFinal = (!$validLots)?false:$validLotsFinal;
			}
			// add new lot
			if($this->submit['saveAddLot']){
				$validLotNew = true;
				// contol/validation here as piVars[lotNew][catmatterrial] is empty for radio buttons
				if(!$this->caller->piVars['lotNew']['catmatterrial']){
					$this->caller->piVars['lotNew']['catmatterrial'] = '';
				}
				
				$saveNewLot = true;
				$validLotNew = $validator->validate('offerlot',$this->caller->piVars['lotNew']);
				$this->validationLotNew = $validator->get_errorMsgs(); // [field][msg]
				$this->lotNew['uid'] = '';
				$this->lotNew['offer_id'] = $this->rec_id;    
				$this->lotNew['fe_cruser_id'] = $GLOBALS['TSFE']->fe_user->user['ses_userid'];
				$this->lotNew = array_merge($this->lotNew, $this->caller->piVars['lotNew']);
				
			}else{
				$validLotNew = true; 
				$saveNewLot = false;
			}

			if($valid && $validLotNew && $validLotsFinal){ 
				// SAVE THE DATA IN DB
				
				$res = $this->model->update($this->data,$queryConf);
				if($res ){          
					// save lots
					$resFinal = true;
					foreach($this->lots as $k0=>$lot){
						$resLot = $this->lotObj->update($lot,$queryConf);
						$resFinal = (!$resLot)?false:$resFinal;
					}
					// SAVE NEW LOT
					if($saveNewLot){
						$resLotNew = $this->lotObj->insert($this->lotNew,$queryConf);
						$resFinal = (!$resLotNew)?false:$resFinal;
						// reset new lot
						unset($this->lotNew);
					}
        
          // get lots back
          /*
          $this->cmd = 'updateSuccess';
           $queryConf['startingPoint'] = $this->startingPoint;
          $record = $this->model->select($this->rec_id,$queryConf);
          $this->data = $record[$this->rec_id];
          */
          if(!$resFinal){
            $this->cmd = 'updateLotsFailed';
          }else{
            
            $this->cmd = 'updateSuccess';
            // get lots back
            $this->lots = $this->lotObj->select('',$lotQueryConf);
          }
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
      $this->lots = $this->lotObj->select('',$lotQueryConf);
      
      
    }else{
      // *******************
      // default case "edit form"
      // *******************
      // template for the view
      $record = $this->model->select($this->rec_id,$queryConf);
      if($record){ 
        // fe_user access management
        $access = $this->accessObj->checkAccess($record[$this->rec_id],'pageOffer','update');
        if(!(bindec($access) & bindec('0010'))){
          $this->cmd = 'updateForbidden';
          $this->data = '';
          $this->lots = '';
        }else{
          $this->cmd = 'update';
          $this->data = $record[$this->rec_id];
          $this->lots = $this->lotObj->select('',$lotQueryConf);
        }    
      }else{
        $this->cmd = 'updateEmpty';
      }
    
    }
    // Get the FORM
    $content = $this->get_include_contents('view_offerForm.php');
    // prepare final offer view
    $content = $content;
    // return offer view
    return $content;
  }
  function archiverLot($id){
    // fe_user access management
    $rec = $this->lotObj->get_fecruserid($id);
    $access = $this->accessObj->checkAccess($rec,'pageOffer','update');
    if(!(bindec($access) & bindec('0010'))){
      $this->cmd = 'canNotUpdate';
      // Get the FORM
      $content = $this->get_include_contents('view_canNotAccess.php');
      return $content;    
    }    
    $id=intVal($id);
    $res = $this->lotObj->archiver($id);
    $this->cmd = $res?'archiveSuccess':'archiveFailed';
    if($red = $this->caller->piVars['redirect']){
			// $conf = array('parameter' =>  $this->conf['pagesRedirect.'][$red],'useCashHash' => true,'returnLast' => 'url',);
			// $redirectTo = $this->cObj->typoLink('', $conf);	
      // Header('Location: '.t3lib_div::locationHeaderUrl($redirectTo));
      // exit;
    }
    // Get the FORM
    $content = $this->get_include_contents('view_offerFormArchiver.php');
    // return offer view
    return $content;
  }
  function activerLot($id){
    // fe_user access management
    $rec = $this->lotObj->get_fecruserid($id); 
    $access = $this->accessObj->checkAccess($rec,'pageOffer','update');
    if(!(bindec($access) & bindec('0010'))){
      $this->cmd = 'canNotUpdate';
      return false;  
    }    
    $id=intVal($id);
    $res = $this->lotObj->activer($id);
    $this->cmd = $res?'activationSuccess':'activationFailed';
    // Get the FORM
    $content = $this->get_include_contents('view_offerFormArchiver.php');
    // prepare final offer view
    $content = $content;
    // return offer view
    return $content;            
  }
  function deleteLot($id){
    // fe_user access management
    $rec = $this->lotObj->get_fecruserid($id);  
    $access = $this->accessObj->checkAccess($rec,'pageOffer','delete');
    if(!(bindec($access) & bindec('0011'))){
      $this->cmd = 'canNotDelete';
      // Get the FORM
      $content = $this->get_include_contents('view_canNotAccess.php');
      return $content;    
    }    
    $id=intVal($id);
    $res = $this->lotObj->delete($id);
    $this->cmd = $res?'deleteSuccess':'deleteFailed';
    if($red = $this->caller->piVars['redirect']){
			// $conf = array('parameter' =>  $this->conf['pagesRedirect.'][$red],'useCashHash' => true,'returnLast' => 'url',);
			// $redirectTo = $this->cObj->typoLink('', $conf);						
      // Header('Location: '.t3lib_div::locationHeaderUrl($redirectTo));
      // exit;
    }
    // Get the FORM
    $content = $this->get_include_contents('view_offerFormArchiver.php');
    // return offer view
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
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_pageOffer.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_pageOffer.php']);
}
?>