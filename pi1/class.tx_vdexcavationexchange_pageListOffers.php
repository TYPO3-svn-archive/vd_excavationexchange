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
require_once(t3lib_extMgm::extPath("vd_excavationexchange").'pi1/class.tx_vdexcavationexchange_pagination.php');

/**
* Plugin 'VD/Excavation exchange' for the 'vd_excavationexchange' extension.
*
* @author	Jean-Luc Thirot <jean-luc.thirot@vd.ch>
* @package	TYPO3
* @subpackage	tx_vdexcavationexchange
*/
class tx_vdexcavationexchange_pageListOffers extends tslib_pibase {
  var $prefixId = 'tx_vdexcavationexchange_pageListOffers';		// Same as class name
  var $scriptRelPath = 'pi1/class.tx_vdexcavationexchange_pageListOffers.php';	// Path to this script relative to the extension dir.
  var $extKey = 'vd_excavationexchange';	// The extension key.
  var $accessObj; // the access object

  var $cmd; // the status, steps, commands, sub action in the main action
  var $cmdErrorMsg = array(); // the error msg for the sub actions
  var $startingPoint; // the page folder for the records Warning only one value
  var $offer; // the offer model
  // var $response; // the response model
  var $offerLot; // the lot model
  var $offersLots; // the lots  
  var $fieldsItems; // the lot fields items
  var $fieldsItemsOffer; // offer list of items  
  var $filters; // the filters values
  var $pagination; // the pagination object
  var $paginationView; // the pagination view
  
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

    // get offer Model
    $confDB['startingPoint'] = $this->startingPoint;
    $this->offer = t3lib_div::makeInstance('tx_vdexcavationexchange_offer');
    $this->offer->ini($confDB);
    $this->fieldsItemsOffer = $this->offer->fieldsItems;
    
    $this->offerLot = t3lib_div::makeInstance('tx_vdexcavationexchange_offerLot');
    $this->offerLot->ini($confDB);    
    $this->action = $this->caller->piVars['action']?$this->caller->piVars['action']:$action;

    
    // **************************************************************************
    // MAIN ACTION
    // **************************************************************************
    // switch action read edit update delete ... 
    switch($this->action) {
    case 'read':
      $content = $this->readAdsListView();
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
		$searchLotID = -1;
    // fe_user access management
    $access = $this->accessObj->checkAccess('','pageListOffers','read');
    if(!(bindec($access) & bindec('0100'))){
      $this->cmd = 'readError';
      $this->cmdErrorMsg[] = 'Vous n\'êtes pas autorisé à voir cette page';
    }
    
    $this->fieldsItems = $this->offerLot->fieldsItems;
    
    /*********************************************************
    * filters get request and prepare query
    *********************************************************/  
    $this->filters = $this->caller->piVars[filter];
    // validation configuration title = required, date = date

		// filter from POST or GET
		$page = intVal(t3lib_div::_GP('pagination_page'));
    if($this->caller->piVars[submit][search] || $page>0){
      $validateOfferData = $this->filters['offer'];
      // remove date from validation if empty
      if(trim($validateOfferData['startdatework'])==''){
        unset($validateOfferData['startdatework']);
      }
      if((trim($validateOfferData['enddatework'])=='')){
        unset($validateOfferData['enddatework']);
      }
			// do not validate district
			if($validateOfferData['district'] == 0 ){
				unset($validateOfferData['district']);
			}			
      $validator = t3lib_div::makeInstance('tx_vdexcavationexchange_validator');
      $validOffer = $validator->validate('offer', $validateOfferData);
      $this->validationOffer = $validator->get_errorMsgs(); // [field][msg]
			// do not validate catmatterrial
			$validateLotData = $this->filters['lot'];
			if($validateLotData['catmatterrial'] == 0 ){
				unset($validateLotData['catmatterrial']);
			}
      $validLot = $validator->validate('offerlot', $validateLotData);
      $this->validationLot = $validator->get_errorMsgs(); // [field][msg]  
		}elseif($this->caller->piVars[submit][cancel]){
			unset($this->filters);
			$cancel = true;
		} 
    // validation end

		// validation OK
   
		// lot [id] in url
		if(intVal($this->caller->piVars[id])>0){
			$this->filters[lot][uid]=$this->caller->piVars[id];
		}
		// first try, diplay something like the cance case
		if(!$this->filters ){
			$cancel = true;;
		}
		if( (!$validLot  || !$validOffer )&& $cancel != true){
			// validation failed
		}else{
			// lot
			if(is_array($this->filters)){
				foreach($this->filters[lot] as $field=>$v){
					unset($query);
					// no errors
					if($this->validationLot[$field]==''){
						if($field == 'uid'){
							$v = intVal($v);
							$query = $v?'tx_vdexcavationexchange_offerlot.'.$field .'='. $v:'';
							$searchLotID = intVal($v);
						}elseif($field == 'catmatterrial'){
							// integer > 0
							$query = intVal($v)>0?'tx_vdexcavationexchange_offerlot.'.$field .'='. intVal($v):'';
						}
						if($query){
							$queryFinal = $queryFinal? $queryFinal.' AND '.$query :$query;
						}
					}
				}
			}
			// offer
			if(is_array($this->filters[offer])){
				foreach($this->filters[offer] as $field=>$v){
					unset($query);
					if($this->validationOffer[$field]==''){
						if($field == 'startdatework'){
							// date to timestamp
							$v = trim($v);
							if($v<>''){
								$v = strtotime($v);
								$query = $v?'tx_vdexcavationexchange_offer.'.$field .'>='. $v:'';
							}
						}elseif($field == 'enddatework'){
							$v = trim($v);
							if($v<>''){
								$v = strtotime($v);
								$query = $v?'tx_vdexcavationexchange_offer.'.$field .'<='. $v:'';
							}
						}elseif($field == 'commune'){
							// not used commune 2009.07.30
							$v = trim($v);
							if($v<>''){
								$v = $GLOBALS['TYPO3_DB']->fullQuoteStr('%'.$v.'%','tx_vdexcavationexchange_offer');
								$query = $v?'upper(tx_vdexcavationexchange_offer.'.$field .') LIKE upper('. $v.')':'';
							}
						}elseif($field == 'district'){
							$query = $v?'tx_vdexcavationexchange_offer.'.$field .' = '. intVal($v):'';
						}
						if($query){
							$queryFinalOffer = $queryFinalOffer? $queryFinalOffer.' AND '.$query :$query;
						}
					}
				}
			}

			$queryConf['where'] = $queryFinal;
			$queryConfOffer['where'] = $queryFinalOffer;
			
		} // end validation ok
			/*********************************************************
			* filters ends
			*********************************************************/  

		/*********************************************************
		* pagination step 1 settings
		* the goal is to calculate the LIMIT for the query used by the record object
		*********************************************************/      
		// set pagination settings
		$this->pagination = t3lib_div::makeInstance('tx_vdtableview_pagination');
		
		// Load template code
		$templatePagination = t3lib_div::getFileAbsFileName(t3lib_extMgm::extPath("vd_excavationexchange").'pi1/pagination.html');
		$templatePagination = str_replace(PATH_site,'',$templatePagination);
		$templatePaginationCode = $this->caller->cObj->fileResource($templatePagination); 
		$this->pagination->init($this->conf['pagi_recordsPage'], $this->conf['pagi_pagesRange'],
		$this->conf['pagi_displayTop'], $this->conf['pagi_displayBottom'],
		$this->conf['pagi_displayStats']); 
		// $this->pagination->init(10,10,1,1,1);                  
		$this->pagination->setTemplateCode($templatePaginationCode);
		
		// retrieve the LIMIT from request
		if($this->caller->piVars[submit][cancel]){
			// reset pagination
			$this->pagination->pageCurrentNo = 1;
			$this->pagination->readRequestPOSTGET('');
		}elseif($this->caller->piVars[submit][search]){
			$this->pagination->readRequestPOSTGET('tx_vdexcavationexchange_pi1','filter',1);
		}else{
			$this->pagination->readRequestPOSTGET('tx_vdexcavationexchange_pi1','filter');
		}
			
		/*********************************************************
		* DATA
		*********************************************************/ 
		// do not search data if validation failed and 
		if( (!$validLot  || !$validOffer )&& $cancel != true){
			//
		}else{
			// 1 get the offers
			$queryConfOffer['startingPoint'] = $this->startingPoint;
			$this->offers = $this->offer->select('',$queryConfOffer);
			// 2 filter the lot compared to the offer filter
			
			foreach($this->offers as $k=>$v){
				$recs_id[] = intval($k);
			}
			if($recs_id){
				if($searchLotID>0){
					unset($queryConf['where_in']);
				}else{
					$queryConf['where_in'] = 'tx_vdexcavationexchange_offerlot.offer_id IN (' . implode(',',$recs_id) .')';
				}
				// 3 get the records LOTS
				$queryConf['startingPoint'] = $this->startingPoint;
				$lots = $this->offerLot->select('', $queryConf);
				if(!$lots){
				}else{
					$this->data = $lots;
				}
			}
		} // end validation
		/*********************************************************
		* pagination step 2 create and display
		*********************************************************/  

		$this->pagination->create(sizeOf($this->data));
		$this->paginationView = $this->pagination->display();

		//LIMIT 10,6
		$end = $this->pagination->getRecordFrom()+$this->pagination->getRecordsLimitNb();
		$counter = 0;
		if(sizeOf($this->data)>0){
			foreach($this->data as $k=>$v){
				// display annonce for activ users // not perfect missing from and to access condition
				if($this->data[$k][fe_cruser_id_label][disable]!=1 && $this->data[$k][fe_cruser_id_label][deleted]!=1){
					// $k == uid WARNING
					if($counter>=$this->pagination->getRecordFrom() && $counter < $end){ 
					}else{
						unset($this->data[$k]);
					}
					$counter++;
				}else{
					unset($this->data[$k]);
				}
			}
		}else{
			$this->cmdErrorMsg[] = 'Les enregistrements sont introuvables';
		}


				
    // call the template view
    $content = $this->get_include_contents('view_listOffers.php');
    // prepare final offer view
    $content = $content;
    
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


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_pageListOffers.php'])	{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_pageListOffers.php']);
}

?>