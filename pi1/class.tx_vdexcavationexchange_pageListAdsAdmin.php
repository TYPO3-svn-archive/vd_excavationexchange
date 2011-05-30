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
class tx_vdexcavationexchange_pageListAdsAdmin extends tslib_pibase {
  var $prefixId = 'tx_vdexcavationexchange_pageListAdsAdmin';		// Same as class name
  var $scriptRelPath = 'pi1/class.tx_vdexcavationexchange_pageListAdsAdmin.php';	// Path to this script relative to the extension dir.
  var $extKey = 'vd_excavationexchange';	// The extension key.
  var $accessObj; // the access object

  var $cmd; // the status, steps, commands, sub action in the main action
  var $cmdErrorMsg = array(); // the error msg for the sub actions
  var $startingPoint; // the page folder for the records Warning only one value
  var $offer; // the offer model
  var $response; // the response model
  var $offerLot; // the lot model
  var $offersLots; // the lots
  var $fieldsItemsOfferLot; // the lot fields items
  var $fieldsItemsOffer; // offer list of items  
  var $search; // the search model
  var $searchAds; // the searched ads
  var $fieldsItemsSearch; // the search fields items
  
  var $filters; // the filters values
  var $pagination; // the pagination object
  var $paginationView; // the pagination view
  var $annonceType; // offer or view type
  var $archivesCase = 0; // wors with archove or open records
  
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

    // get search Model
    $this->search = t3lib_div::makeInstance('tx_vdexcavationexchange_search');
    $this->search->ini($confDB);
    $this->fieldsItemsSearch = $this->search->fieldsItems;    
    $this->response = t3lib_div::makeInstance('tx_vdexcavationexchange_response');
    $this->response->ini($confDB);
    
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
    // fe_user access management
    $access = $this->accessObj->checkAccess('','pageListAdsAdmin','read');
    if(!(bindec($access) & bindec('0100'))){
      $this->cmd = 'readError';
      $this->cmdErrorMsg[] = 'Vous n\'êtes pas autorisé à voir cette page';
      // Get the FORM
      $content = $this->get_include_contents('view_canNotAccess.php');
      // return view
      return $content;  
    }     
    
    $this->fieldsItemsOfferLot = $this->offerLot->fieldsItems;
    
    /*********************************************************
    * filters get request and prepare query
    *********************************************************/  
    $this->filters = $this->caller->piVars[filter];
    // validation configuration title = required, date = date
    // create validator
    if($this->caller->piVars[submit][search]){
      $validateOfferData = $this->filters['excav'];
      // remove date from validation if empty
      if(trim($validateOfferData['tstampStart'])==''){
        unset($validateOfferData['tstampStart']);
      }
      if((trim($validateOfferData['tstampEnd'])=='')){
        unset($validateOfferData['tstampEnd']);
      }
      $validator = t3lib_div::makeInstance('tx_vdexcavationexchange_validator');
      $validOffer = $validator->validate('offer', $validateOfferData);
      $this->validationOffer = $validator->get_errorMsgs(); // [field][msg]

    }
    // validation end
    
    if($this->caller->piVars[submit][cancel]){
      unset($this->filters);
    }    
  //   debug($this->filters);
    // default value
    $this->annonceType = 'offer';
    // excavation general filter > typpe annonce and user
    if(is_array($this->filters[excav])){
      unset($query);
      unset($queryLot);
      if($v = $this->filters[excav]['typeAnnonce']){
        $v = intVal($v);
        if($v==0){
          // offer ads
          $this->annonceType = 'offer';
        }elseif($v==1){
          // search ads
          $this->annonceType = 'search';
        }
      }
      $table = ($this->annonceType === 'offer')?'tx_vdexcavationexchange_offer':'tx_vdexcavationexchange_search';
      
      if($v = $this->filters[excav]['statusAnnonce']){
        $v = intVal($v);
        if($v==0){
          // offer ads
          $annonceStatus = 'ouvert';
         
        }elseif($v==1){
          // search ads
          $annonceStatus = 'archive';
        }
      }      
      // user
      if($v = trim($this->filters[excav]['user'])){
        // get users ids
        if($feusers = $this->searchFeUsers($v)){
          foreach($feusers as $fek=>$fev){
            $q = $table.'.fe_cruser_id ='.$fev[uid];
           $query = $query? $query.' OR '. $q: $q;
          }
        }
      }
      if($v = $this->filters[excav]['district']){
        $q= $v?$table.'.district ='. intVal($v):'';
        $query = $query? $query.' AND '. $q: $q;
      }
      // LOTS query -------------------------------------------------------------------------------------
      $table = ($this->annonceType === 'offer')?'tx_vdexcavationexchange_offerlot':'tx_vdexcavationexchange_search';
      // lotNo > use table offerlot or search
      if($v = trim($this->filters[excav]['lotNo'])){
        $q = $table.'.uid ='.intVal($v);
        $queryLot = $queryLot? $queryLot.' AND '. $q: $q;
      }
      if($this->validationOffer[tstampStart]==''){
        if($v = $this->filters[excav]['tstampStart']){
          // date to timestamp
          $v = trim($v);
          if($v<>''){
            $v = strtotime($v);
            if($v){
              $q = $table.'.tstamp >='. $v;
              $queryLot = $queryLot? $queryLot.' AND '. $q: $q;
            }
          }
        }
        if($v = $this->filters[excav]['tstampEnd']){
          if($v<>''){
            $v = strtotime($v.'+1 day');
            $q = $v? $table.'.tstamp <='. $v:'';
            $queryLot = $queryLot? $queryLot.' AND '. $q: $q;
          }
        }
      }      
    }
    // $table = ($this->annonceType === 'offer')?'tx_vdexcavationexchange_offer':'tx_vdexcavationexchange_search';
    
    if($this->annonceType == 'offer'){
      $queryConfOfferLot['where'] = $queryLot;
      $queryConfOffer['where'] = $query;
    }elseif($this->annonceType == 'search'){
      if($query && $queryLot){
        $queryConfSearch['where'] = $query. ' AND ' .$queryLot;
      }elseif($query){
        $queryConfSearch['where'] = $query;  
      }elseif($queryLot){
        $queryConfSearch['where'] = $queryLot;
      }
    }

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
    $this->archivesCase = $annonceStatus=='archive' ?1:0; 
    if($this->annonceType == 'offer'){
      // 1 get the offers
      
      $queryConfOffer['startingPoint'] = $this->startingPoint;
      $this->offers = $this->offer->select('',$queryConfOffer);
      // 2 filter the lot compared to the offer filter
      
      foreach($this->offers as $k=>$v){
        $recs_id[] = $k;
      }
      if($recs_id){
        $queryConfOfferLot['where_in'] = 'tx_vdexcavationexchange_offerlot.offer_id IN (' . implode(',',$recs_id) .')';
        // 3 get the records LOTS
        $queryConfOfferLot['startingPoint'] = $this->startingPoint;
        $queryConfOfferLot['archive'] = $this->archivesCase;
        $lots = $this->offerLot->select('', $queryConfOfferLot);
        if(!$lots){
          $this->cmdErrorMsg[] = 'Les enregistrements sont introuvables';
        }else{
          $this->data = $lots;
        }
      }
      // --------------------------------------------------
    }elseif($this->annonceType == 'search'){
      $queryConfSearch['startingPoint'] = $this->startingPoint;
      $queryConfSearch['archive'] = $this->archivesCase;
      $this->searchs = $this->search->select('',$queryConfSearch);
      // 2 filter the lot compared to the search filter
      $this->data = $this->searchs ;
    }

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
        // $k == uid WARNING
        if($counter>=$this->pagination->getRecordFrom() && $counter < $end){
        }else{
          unset($this->data[$k]);
        }
        $counter++;
      }
    }else{
      $this->cmdErrorMsg[] = 'Les enregistrements sont introuvables';
    }
    // call the template view
    $content = $this->get_include_contents('view_listAdsAdmin.php');
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
  /*
   * FE USERS FUNCTIONS
   */
  function searchFeUsers($search){
    // $record = t3lib_BEfunc::getRecord($this->table,$uid,'*',($q_where ? ' AND '.$q_where : ''));
    $v = strtoupper ($search);
    $v = $GLOBALS['TYPO3_DB']->fullQuoteStr('%'.$v.'%','fe_users');
    // build where clause with upper case search
    if($v<>''){
      $fields = array('name','username','company','email');
      foreach($fields as $fk=>$fv){
        $w = 'upper('.$fv .') LIKE ('. $v.')';
        $wf = $wf ? $wf . ' OR '. $w : $w;
      }
      $wf = '('.$wf.')';
    }

    // select in sys_folder pid starting point in groups
    // no get ALL FE users
    // $where_clause = $wf? $wf.' AND pid = '.$this->startingPoint:' pid = '.$this->startingPoint;    
    // No access check
    $where_clause = '('.$wf.')';
    $records = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid,username,pid,email,deleted,disable,usergroup','fe_users',$where_clause,$groupBy = '',$orderBy = '',$limit = '',$uidIndexField = '');
    // keep only records in groups
    $groups = array(trim($this->conf[FE_groupAdmin]),trim($this->conf[FE_groupActiv]),trim($this->conf[FE_groupPending])); 
    foreach($records as $k=>$v){
      $recGroups  = explode(',',$v['usergroup']);
      foreach($recGroups as $rgk=>$rgv){
        if(in_array($rgv,$groups)){
        // keep
          $recordsEnd[] = $v;;
        }else{
        // reject
        }
      }
    }
    return $recordsEnd;
  }
  
  
      /*
      FE_groupAdmin = 39
  FE_groupActiv = 38
  FE_groupPending =  37
  */
  function getFeUsersStats(){
    // No access check
    $where_clause = 'deleted=0';
    $records = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid,deleted,disable,usergroup','fe_users',$where_clause);
    // keep only records in groups
    $groups = array(trim($this->conf[FE_groupAdmin]),trim($this->conf[FE_groupActiv]),trim($this->conf[FE_groupPending])); 
    
    $feStat[FE_groupAdmin] = 0;
    $feStat[FE_groupActiv] = 0;
    $feStat[FE_groupActivDisabled] = 0;
    $feStat[FE_groupPending] = 0;
    $feStat[FE_groupPendingDisabled] = 0;
    
    foreach($records as $k=>$v){
      $recGroups  = explode(',',$v['usergroup']);
      foreach($recGroups as $rgk=>$rgv){
        // make stats
        if($rgv == $this->conf[FE_groupAdmin]){
          $feStat[FE_groupAdmin]++;
        }elseif($rgv == $this->conf[FE_groupActiv]){
          if($v[disable]==1){
            $feStat[FE_groupActivDisabled]++;
          }else{
            $feStat[FE_groupActiv]++;
          }
        }elseif($rgv == $this->conf[FE_groupPending]){
          if($v[disable]==1){
            $feStat[FE_groupPendingDisabled]++;
          }else{
            $feStat[FE_groupPending]++;
          }
        }
      }
    }
    $feStat[total] = $feStat[FE_groupAdmin]+$feStat[FE_groupActivDisabled]+$feStat[FE_groupActiv]+$feStat[FE_groupPendingDisabled]+$feStat[FE_groupPending];
    return $feStat;
  }
  
  
  function get_offres(){
    
  }
  
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_pageListAdsAdmin.php'])	{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdexcavationexchange_pageListAdsAdmin.php']);
}

?>