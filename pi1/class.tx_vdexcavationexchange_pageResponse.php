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
class tx_vdexcavationexchange_pageResponse extends tslib_pibase {
	var $prefixId = 'tx_vdexcavationexchange_pageResponse';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_vdexcavationexchange_pageResponse.php';	// Path to this script relative to the extension dir.
	var $extKey = 'vd_excavationexchange';	// The extension key.
  var $accessObj; // the access object

  var $cmd; // the status, steps, commands, sub action in the main action

  var $caller; // the caller object
  var $startingPoint; // the page folder for the records Warning only one value
  var $model; // the object model
  var $responseType; // lot or search
  var $rec_id; // the record to answer to
  var $foreignObj; // the lot or search object 
  var $offerObj; // the offer object if lot case
  var $offerData; // the offer data
  var $type_id; // the id of the lot or the search. Required for create action
  var $foreignData; // the foreign data lot or search
  
  var $formSession; // the session

  
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
    $this->rec_id = intval($this->caller->piVars['resp']['rec_id']);
    $this->responseType = ($this->caller->piVars['resp']['type']);
    $this->type_id = intval($this->caller->piVars['resp']['type_id']); 
    
    // required parameters
    // if read the rec_id is required
    if(!$this->rec_id){
      // if new the type and type_id are required
      if(!$this->responseType || !$this->type_id){
        $this->cmd = 'missingparameters';
        return $content;
      }
    }
    
    // get lot or search Model
    $confDB['startingPoint'] = $this->startingPoint;
    if($this->responseType == 'lot'){
      $this->foreignObj = t3lib_div::makeInstance('tx_vdexcavationexchange_offerlot');
      $this->foreignObj->ini($confDB);
      $this->offerObj = t3lib_div::makeInstance('tx_vdexcavationexchange_offer');
      $this->offerObj->ini($confDB);
    }elseif($this->responseType == 'search'){
      $this->foreignObj = t3lib_div::makeInstance('tx_vdexcavationexchange_search');
      $this->foreignObj->ini($confDB);
    }else{
      // return false;
      // can be read case but not update for the responses
    }
    
    $this->action = $this->caller->piVars['action']?$this->caller->piVars['action']:$action;
    $this->model = t3lib_div::makeInstance('tx_vdexcavationexchange_response');

    // FORM variables
    $this->formAction = $this->pi_getPageLink($GLOBALS['TSFE']->id);
    // request Data (data for the lot id,....)
    // and request [submit][save] (save, cancel)
    $this->submit = $this->caller->piVars['resp']['submit'];
    $this->action = $this->caller->piVars['action']?$this->caller->piVars['action']:$action;
    
    // create captcha
    if (t3lib_extMgm::isLoaded('sr_freecap') ) {
      require_once(t3lib_extMgm::extPath('sr_freecap').'pi2/class.tx_srfreecap_pi2.php');
      $this->freeCap = t3lib_div::makeInstance('tx_srfreecap_pi2');
    }

    if (is_object($this->freeCap)) {
      $this->captchArray = $this->freeCap->makeCaptcha();
    }    
    

    // **************************************************************************
    // MAIN ACTION
    // **************************************************************************
    // switch action read edit update delete ... 
    switch($this->action) {
      case 'create':
        $content = $this->createView();
        break;
      default:
      case 'read':
        $content = $this->readView();
        break;
      default:
        // no update case
    }      
    $this->content = $content;
	}
  
  function getContent(){
    return $this->content;
  }
  
	/**
	 * get lot and search ads and display as a list
	 *
	 * @return	The content listing view 
	 */
  function readView(){
    return 'RESPONSE READ VIEW';
  }
  
	/**
	 * get lot and search ads and display as a list
	 *
	 * @return	The content listing view 
	 */
  function createView(){
    $this->cmd = 'create';
    // fe_user access management
    $access = $this->accessObj->checkAccess('','pageResponse','create');
    if(!(bindec($access) & bindec('1000'))){
      $this->cmd = 'canNotCreate';
      // Get the FORM
      $content = $this->get_include_contents('view_responseForm.php');
      // return view
      return $content;  
    }
    // get foreign data
    $queryConf['startingPoint'] = $this->startingPoint;
    $this->foreignData = $this->foreignObj->select($this->type_id, $queryConf);
    if($this->responseType == 'lot'){
       $offerID = $this->foreignData[$this->type_id]['offer_id'];
      $this->offerData = $this->offerObj->select($offerID, $queryConf);
    }
		// session
    $submittedKey = $this->caller->piVars['formsession']['key'];
    $formSession = $GLOBALS["TSFE"]->fe_user->getKey('ses',$this->extKey);
    $keyExist = ($submittedKey == $formSession['key'])?true:false;		
		$this->generateSessionKey();		
		
    // Check if the form for double click and multiple submit
    if(!$keyExist && ($this->submit['save'] || $this->submit['update'] || $this->submit['cancel'])){
      $this->cmd = 'nsubmitError';
    
    // ----------- FORM submit ------------
    }elseif($this->submit['save'] && !$this->rec_id) {
      $this->cmd = 'save';    
      // validation configuration title = required, date = date
      // create validator
      $validator = t3lib_div::makeInstance('tx_vdexcavationexchange_validator');
      $valid = $validator->validate('response',$this->caller->piVars['resp']);
      $this->validation = $validator->get_errorMsgs(); // [field][msg]
      // use the piVars value for the data
      $this->data['uid'] = '';

      // the user can be empty for the response ?
      $this->data['fe_cruser_id'] = $GLOBALS['TSFE']->fe_user->user['ses_userid'];
      if($this->responseType =='lot'){
        $this->data['lot_id'] = $this->type_id;
      }elseif($this->responseType =='search'){
        $this->data['search_id'] = $this->type_id;
      }
      $this->data = array_merge($this->data, $this->caller->piVars['resp']);
      
      // captcha
      if (is_object($this->freeCap) && !$this->freeCap->checkWord($this->caller->piVars['captcha_response'])) {
          $valid = false;
          $this->cmd = 'captchaFailed';
          $this->captchaError = true;
      }    
      // validation end
      if($valid){ 
        // insert data (startingpoint type, type_id, [fe_cruser_id])
        $recID = $this->model->insert($this->data,$queryConf);
        if($recID){
          $this->cmd = 'saveSuccess';
          $this->rec_id = $recID;
          // send mail 
					$emailObj = t3lib_div::makeInstance('tx_vdexcavationexchange_email');
					$emailObj->ini($this->caller);
					// link
					if($this->responseType == 'lot'){
						$conf = array('parameter' =>  $this->conf['pagesRedirect.']['offresListPage'],'useCashHash' => true,'returnLast' => 'url',);
						$url = $this->cObj->typoLink('', $conf);
						$link = t3lib_div::getIndpEnv('TYPO3_SITE_URL').$url;
					}elseif($this->responseType == 'search'){
						$conf = array('parameter' =>  $this->conf['pagesRedirect.']['searchListPage'],'useCashHash' => true,'returnLast' => 'url',);
						$url = $this->cObj->typoLink('', $conf);						
						$link = t3lib_div::getIndpEnv('TYPO3_SITE_URL').$url;
					}					
					$resmail = $emailObj->sendResponse($this->foreignData[$this->type_id],$this->caller->piVars['resp'],$link.'?tx_vdexcavationexchange_pi1[id]='.$this->type_id);
          if($resmail != null){
            // $this->cmd = 'emailSuccess';
            if($this->responseType =='lot'){
              // $nextStep = t3lib_div::getIndpEnv('TYPO3_SITE_URL').$this->conf['pagesRedirect.']['responseLotSaved'];
            }else{
              // $nextStep = t3lib_div::getIndpEnv('TYPO3_SITE_URL').$this->conf['pagesRedirect.']['responseSearchSaved'];
            }
          }else{
            // $this->cmd = 'emailError';
            if($this->responseType =='lot'){
              // $nextStep = t3lib_div::getIndpEnv('TYPO3_SITE_URL').$this->conf['pagesRedirect.']['responseLotSavedEmailError'];
            }else{
              // $nextStep = t3lib_div::getIndpEnv('TYPO3_SITE_URL').$this->conf['pagesRedirect.']['responseSearchSavedEmailError'];
            }
          }
          if($nextStep){
            // Redirect to the page x to do use backurl technique
						$this->clearFormKey();
            Header('Location: '.t3lib_div::locationHeaderUrl($nextStep));
            exit;
          }else{
            // get record back
            $this->data['uid'] = $recID;
            $queryConf['noAccessClause'] = 1; // so as some timing problem to get the record back
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
    }elseif($this->submit['update'] ){
      // 1 display the form back for update
      $this->cmd = 'update';
    }elseif($this->submit['cancel'] ){
      $this->cmd = 'cancel';
      // reset values for the form
      unset($this->caller->piVars);
    }else{
      // ***********************************************
      // default case "NEW empty form"
      // ***********************************************
      // the lot id is required
      if($this->type_id <= 0){
        $this->cmd = 'new_noTypeId';
      }else{      
        $this->cmd = 'new';
      }
    }
		if($this->cmd=='new' || $this->cmd=='cancel'){
			// session genereate key
			$this->generateSessionKey();
		}


    // Get the FORM
    $content = $this->get_include_contents('view_responseForm.php');
    // prepare final lot view
    $content = $content;
    // return lot view
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
  /**
   * Code inspired from the typo3 extension ke_smalladds 2009
   *
   */
  function sendEmail_out_($lot, $response){
    // Compile Userinfo for notify emails
		if ($GLOBALS['TSFE']->fe_user->user) {
			$fe_userinfo="\nUser:\n";
			if (!empty($GLOBALS['TSFE']->fe_user->user['username'])) $fe_userinfo .= $GLOBALS['TSFE']->fe_user->user['username']."\n";
			if (!empty($GLOBALS['TSFE']->fe_user->user['company'])) $fe_userinfo .= $GLOBALS['TSFE']->fe_user->user['company']."\n";
			if (!empty($GLOBALS['TSFE']->fe_user->user['name'])) $fe_userinfo .= $GLOBALS['TSFE']->fe_user->user['name']."\n";
			if (!empty($GLOBALS['TSFE']->fe_user->user['address'])) $fe_userinfo .= $GLOBALS['TSFE']->fe_user->user['address']."\n";
			if (!empty($GLOBALS['TSFE']->fe_user->user['zip'])) $fe_userinfo .= $GLOBALS['TSFE']->fe_user->user['zip']." ";
			if (!empty($GLOBALS['TSFE']->fe_user->user['city'])) $fe_userinfo .= $GLOBALS['TSFE']->fe_user->user['city']."\n";
			if (!empty($GLOBALS['TSFE']->fe_user->user['country'])) $fe_userinfo .= $GLOBALS['TSFE']->fe_user->user['country']."\n";
			if (!empty($GLOBALS['TSFE']->fe_user->user['telephone'])) $fe_userinfo .= 'Tel: '.$GLOBALS['TSFE']->fe_user->user['telephone']."\n";
			if (!empty($GLOBALS['TSFE']->fe_user->user['telephone'])) $fe_userinfo .= 'Fax: '.$GLOBALS['TSFE']->fe_user->user['fax']."\n";
			if (!empty($GLOBALS['TSFE']->fe_user->user['email'])) $fe_userinfo .= $GLOBALS['TSFE']->fe_user->user['email']."\n";
			if (!empty($GLOBALS['TSFE']->fe_user->user['www'])) $fe_userinfo .= $GLOBALS['TSFE']->fe_user->user['www']."\n";
		} else {
			$fe_userinfo='';
		}
  
    // link
    if($this->responseType == 'lot'){
			$conf = array('parameter' =>  $this->conf['pagesRedirect.']['myAds'],'useCashHash' => true,'returnLast' => 'url',);
			$link = $this->cObj->typoLink('', $conf);			
    }elseif($this->responseType == 'search'){
			$conf = array('parameter' =>  $this->conf['pagesRedirect.']['myAdsSearch'],'useCashHash' => true,'returnLast' => 'url',);
			$link = $this->cObj->typoLink('', $conf);				
    }
		// Send emails
    // send an email to the administrator, if configured so
    if ($this->conf['notifyEmailAdminOnCreated']) {
      $emaildata = $this->conf['notifyEmailAdminOnCreated.'];    
    } 

    $res = true;
    if ($emaildata) {
      $emaildata['body']=str_replace("|","\n",$emaildata['body']);
      $res = t3lib_div::plainMailEncoded(
          $emaildata['toEmail'],
          sprintf($emaildata['subject'], $lot['uid']),
          sprintf($emaildata['body'],
                  $lot['uid']."\n\n",
                  html_entity_decode($response['firstname']) . ' '.  html_entity_decode($response['lastname']) . "\n",
                  html_entity_decode($response['phone']) . "\n",
                  html_entity_decode($response['email']) . "\n",
                  $link
                  ),
          'From: '.$emaildata['fromName'].' <'.$emaildata['fromEmail'].'>'											
          );
      unset($emaildata);
    }

    // send an email to the editor, if configured so
    if ($this->conf['notifyEmailEditorOnResponse']) {
      $emaildata = $this->conf['notifyEmailEditorOnResponse.'];
      $owner = t3lib_BEfunc::getRecord('fe_users',$lot['fe_cruser_id'],'*');
      $emaildata['toEmail'] = $owner[email];     
    }
    $res1 = true;
    if ($emaildata) {
      $emaildata['body']=str_replace("|","\n",$emaildata['body']);
      $res1 = t3lib_div::plainMailEncoded(
          $emaildata['toEmail'],
          sprintf($emaildata['subject'], $lot['uid']),
          sprintf($emaildata['body'],
                  $lot['uid']."\n\n",
                  html_entity_decode($response['firstname']) . ' '.  html_entity_decode($response['lastname']) . "\n",
                  html_entity_decode($response['phone']) . "\n",
                  html_entity_decode($response['email']) . "\n",
                  $link
                  ),
          'From: '.$emaildata['fromName'].' <'.$emaildata['fromEmail'].'>'											
          );
      unset($emaildata);
    }
    // send an email to the user, if configured so
    if ($this->conf['notifyEmailUserOnResponse'] &&  $response['email']) {
      $emaildata = $this->conf['notifyEmailUserOnResponse.']; 
      $emaildata['toEmail'] = $response['email'];
    }
    $res2 = true;
    if ($emaildata) {
      //  $emaildata['body'] = 'Bonjour vous avez répondu à l\'annonce suivante.|Lot No: %s |||Voir les annonces: %s';
      $emaildata['body']=str_replace("|","\n",$emaildata['body']);
      $res2 = t3lib_div::plainMailEncoded(
          $emaildata['toEmail'],
          sprintf($emaildata['subject'], $lot['uid']),
          sprintf($emaildata['body'],
                  $lot['uid']."\n\n",
                  $link
                  ),
          'From: '.$emaildata['fromName'].' <'.$emaildata['fromEmail'].'>'											
          );
    }
    if($res && $res1 && $res2){
      // plainMailEncoded return void so no check possible
      return true;
    }else{
      return true;      
    }
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

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_pageResponse.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_pageResponse.php']);
}

?>