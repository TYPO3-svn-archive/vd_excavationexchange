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
class tx_vdexcavationexchange_email extends tslib_pibase {
	var $prefixId = 'tx_vdexcavationexchange_email';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_vdexcavationexchange_email.php';	// Path to this script relative to the extension dir.
	var $extKey = 'vd_excavationexchange';	// The extension key.

	var $caller; // the caller object

	function ini($caller)	{
		$this->conf = $caller->conf;
		$this->pi_setPiVarDefaults();
		$this->pi_USER_INT_obj=1;	// Configuring so caching is not expected. This value means that no cHash params are ever set. We do this, because it's a USER_INT model!
		// create cObj
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
		// get caller pluginmodel ref. Used with piVars    
		$this->caller = $caller;
		$this->action = $this->caller->piVars['action']?$this->caller->piVars['action']:$action;
	}
	/**
	* Code inspired from the typo3 extension ke_smalladds 2009
	*
	*/
	function sendResponse($lot, $response, $link){
		// format TS of the mail
		//  $emaildata['body'] = 'Bonjour vous avez répondu à l'annonce suivante.|Lot No: %s |||Voir les annonces: %s';
		if(!$this->conf){
				return false;
		}
		// send an email to the administrator, if configured so
		if ($this->conf['notifyEmailAdminOnCreated']) {
			$emaildata = $this->conf['notifyEmailAdminOnCreated.'];    
		} 
		$res = true;

		$infos = $lot['uid'].', '.$lot['catmatterrial_label'] .', '.$lot['infoMat_label'].', volume:'.$lot['volume'];
		
		if ($emaildata) {
			$emaildata['body']=str_replace("|","\n",$emaildata['body']);
			$res = t3lib_div::plainMailEncoded(
					$emaildata['toEmail'],
					sprintf($emaildata['subject'], $lot['uid']),
					sprintf($emaildata['body'],
									html_entity_decode($infos),
									html_entity_decode($response['firstname']) . ' '.  html_entity_decode($response['lastname']),
									html_entity_decode($response['phone']),
									html_entity_decode($response['email']),
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
									html_entity_decode($infos),
									html_entity_decode($response['firstname']) . ' '.  html_entity_decode($response['lastname']),
									html_entity_decode($response['phone']),
									html_entity_decode($response['email']),
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
			$emaildata['body']=str_replace("|","\n",$emaildata['body']);
			$res2 = t3lib_div::plainMailEncoded(
					$emaildata['toEmail'],
					sprintf($emaildata['subject'], $lot['uid']),
					sprintf($emaildata['body'],
									html_entity_decode($infos),
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
	* Code inspired from the typo3 extension ke_smalladds 2009
	*
	*/
	function sendDetails($lot, $response, $content, $link){
		
		$signature = '
La bourse aux matériaux
	
Voir l\'annonce : '.$link;
		
		
		if(!$this->conf){
				return false;
		}
		$owner = $lot[fe_cruser_id_label];
		// send an email to the administrator, if configured so
		if ($this->conf['notifyEmailAdminOnCreated']) {
			$emaildata = $this->conf['notifyEmailAdminOnCreated.'];
			$emaildata['body'] = 'Le contributeur '.$owner['email'].' à envoyé les détails d\'une annonce à '. $response['email'] .'.'. strip_tags($content).$signature;
		} 

		$res = true;
		if ($emaildata) {
			$res = t3lib_div::plainMailEncoded(
					$emaildata['toEmail'],
					sprintf($emaildata['subject'], $lot['uid']),
					$emaildata['body'],
					'From: '.$emaildata['fromName'].' <'.$emaildata['fromEmail'].'>'				
					);
			unset($emaildata);
		}

		// send an email to the editor, if configured so
		if ($this->conf['notifyEmailEditorOnResponse']) {
			$emaildata = $this->conf['notifyEmailEditorOnResponse.'];
			$emaildata['toEmail'] = $owner[email];
			$emaildata['body'] = 'Bonjour,

vous avez envoyé les détails d\'une annonce à '. $response['email'] .'.'.	strip_tags($content).$signature;
		}
		$res1 = true;
		if ($emaildata) {
			$res1 = t3lib_div::plainMailEncoded(
					$emaildata['toEmail'],
					sprintf($emaildata['subject'], $lot['uid']),
					$emaildata['body'],					
					'From: '.$emaildata['fromName'].' <'.$emaildata['fromEmail'].'>'				
					);
			unset($emaildata);
		}
		// send an email to the user
		if ($response['email']) {
			$emaildata = $this->conf['notifyEmailUserOnResponse.']; 
			$emaildata['toEmail'] = html_entity_decode($response['email']);
			$emaildata['replyToName'] = html_entity_decode($owner['company']);
			$emaildata['replyTo'] = html_entity_decode($owner['email']);
			$emaildata['body'] = 'Bonjour,
Suite à votre intérêt, l\'annonceur '.$owner['company'].' ('.$owner['email'].') vous a envoyé les détails d\'une annonce.' .	strip_tags($content).$signature;
		}
		$res2 = true;
		if ($emaildata) {
			$res2 = t3lib_div::plainMailEncoded(
					$emaildata['toEmail'],
					sprintf($emaildata['subject'], $lot['uid']),
					$emaildata['body'],
					'From: '.$emaildata['fromName'].' <'.$emaildata['fromEmail'].'>
					Reply-To: '.$emaildata['replyToName'].' <'.$emaildata['replyTo'].'>'
					);
		}
		if($res && $res1 && $res2){
			// plainMailEncoded return void so no check possible
			return true;
		}else{
			return true;      
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_email.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_email.php']);
}

?>