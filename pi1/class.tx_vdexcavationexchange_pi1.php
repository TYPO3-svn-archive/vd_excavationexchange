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
require_once(t3lib_extMgm::extPath("vd_excavationexchange").'pi1/class.tx_vdexcavationexchange_pageOffer.php');
require_once(t3lib_extMgm::extPath("vd_excavationexchange").'pi1/class.tx_vdexcavationexchange_pageSearch.php');
require_once(t3lib_extMgm::extPath("vd_excavationexchange").'pi1/class.tx_vdexcavationexchange_pageResponse.php');
require_once(t3lib_extMgm::extPath("vd_excavationexchange").'pi1/class.tx_vdexcavationexchange_pageListMyAds.php');
require_once(t3lib_extMgm::extPath("vd_excavationexchange").'pi1/class.tx_vdexcavationexchange_pageListOffers.php');
require_once(t3lib_extMgm::extPath("vd_excavationexchange").'pi1/class.tx_vdexcavationexchange_pageListSearchAds.php');
require_once(t3lib_extMgm::extPath("vd_excavationexchange").'pi1/class.tx_vdexcavationexchange_pageListAdsAdmin.php');

require_once(t3lib_extMgm::extPath("vd_excavationexchange").'pi1/class.tx_vdexcavationexchange_validator.php');
require_once(t3lib_extMgm::extPath("vd_excavationexchange").'pi1/class.tx_vdexcavationexchange_access.php');
require_once(t3lib_extMgm::extPath("vd_excavationexchange").'pi1/class.tx_vdexcavationexchange_email.php');

require_once(t3lib_extMgm::extPath("vd_excavationexchange").'pi1/offer/class.tx_vdexcavationexchange_offer.php');
require_once(t3lib_extMgm::extPath("vd_excavationexchange").'pi1/offerlot/class.tx_vdexcavationexchange_offerlot.php');
require_once(t3lib_extMgm::extPath("vd_excavationexchange").'pi1/search/class.tx_vdexcavationexchange_search.php');
require_once(t3lib_extMgm::extPath("vd_excavationexchange").'pi1/response/class.tx_vdexcavationexchange_response.php');

require_once(t3lib_extMgm::extPath('rlmp_dateselectlib').'class.tx_rlmpdateselectlib.php');

/**
 * Plugin 'VD/Excavation exchange' for the 'vd_excavationexchange' extension.
 *
 * @author	Jean-Luc Thirot <jean-luc.thirot@vd.ch>
 * @package	TYPO3
 * @subpackage	tx_vdexcavationexchange
 */
class tx_vdexcavationexchange_pi1 extends tslib_pibase {
	var $prefixId = 'tx_vdexcavationexchange_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_vdexcavationexchange_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey = 'vd_excavationexchange';	// The extension key.

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_USER_INT_obj=1;	// Configuring so caching is not expected. This value means that no cHash params are ever set. We do this, because it's a USER_INT object!

		// set configuration for the extension  TS FF
		$this->pi_initPIflexForm(); // Init FlexForm configuration for plugin
		$this->conf['view_type'] = $this->fetchConfigurationTSFF('view_type');
		$this->conf['action_type'] = $this->fetchConfigurationTSFF('action_type');
		$this->conf['starting_points'] = $this->cObj->data["pages"];//Retreiving startingpoints list
		$this->conf['resursive'] = $this->cObj->data["recursive"];//Retreiving recursivity setting
		// via TS config

		// check environment

		// check FE user authentication

		// add css addd javascript
		$this->conf['CSSFile'] = $this->fetchConfigurationTSFF('CSSFile');
		$this->setCSSFile();
		$this->addHeadersParts();

		// switch view + action
		// offer + offer->create(), offers + list(offer->view())
			
		$page = $this->conf['view_type']?$this->conf['view_type']:'offer';
		$action  = $this->conf['action_type']?$this->conf['action_type']:'read';

		// make view
		$view = t3lib_div::makeInstance('tx_vdexcavationexchange_page'.$page);
		$view->ini($content, $this, $action);

		$content = $view->getContent();
		// return content (view)

		return $this->pi_wrapInBaseClass($content);
	}

	/**
	 * Fetches configuration value given its name. Merges flexform and TS configuration values.
	 *
	 * @param	string	$param	Configuration value name
	 * @return	string	Parameter value
	 */
	function fetchConfigurationTSFF($param, $sheet='sDEF') {
		$value = trim($this->pi_getFFvalue($this->cObj->data['pi_flexform'], $param, $sheet),"'");
		return $value ? $value : $this->conf[$param];
	}

	/**
	 * set the css file
	 *
	 */
	public function setCSSFile(){

		$this->conf['CSSFile'] = t3lib_div::getFileAbsFileName($this->conf['CSSFile']);
		if(!is_file($this->conf['CSSFile'])){
			$this->errorMsg .= '<br/>The CSS file is not a valid file:'.$this->conf['CSSFile'];
		}else{
			$this->conf['CSSFile'] = str_replace(PATH_site,'',$this->conf['CSSFile']);
		}
		if ($this->conf['CSSFile']=='') {
			$this->errorMsg .= '<br/>The CSS File is missing did you add the static file in a template?';
		}else{
		}
	}

	/**
	 * add header parts like javascript and css int the head of the html page

	 * return boolean
	 */
	function addHeadersParts(){
		//css
		$key = 'EXT:' . $this->extKey . md5('46asasdasd3fvdfgfgd');
		$headerParts = '<link rel="stylesheet" type="text/css" href="'.$this->conf['CSSFile'].'"/>';
		$GLOBALS['TSFE']->additionalHeaderData[$key] = $headerParts;
		// js
		$key = 'EXT:' . $this->extKey . md5('saf654fasd8f4s6df16sd5f4');
		$headerParts = '<script type="text/javascript" src="'.t3lib_extMgm::siteRelPath($this->extKey).'pi1/vd_excavationexchange.js" language="JavaScript"></script>';
		$GLOBALS['TSFE']->additionalHeaderData[$key] = $headerParts;
	}

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_pi1.php']);
}

?>