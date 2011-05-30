<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Jean-Luc Thirot <jean-luc.thirot@vd.ch>
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
* Class '_pagination_exchange_pagination' for the 'vd_excavationexchange' extension.
* Manage the pagination for the page
* @author	Jean-Luc Thirot <jean-luc.thirot@vd.ch>
* @package	TYPO3
* @subpackage	tx_vdtableview
*/
class tx_vdtableview_pagination extends tslib_pibase {
  var $prefixId = 'tx_vdtableview_pagination';		// Same as class name
  var $scriptRelPath = 'pi1/class.tx_vdtableview_pagination.php';	// Path to this script relative to the extension dir.
  var $extKey = 'vd_excavationexchange';	// The extension key.
  var $cObj;
  // your configuration
  var $maxRecPage; // maximum records per page
  var $maxPageLinks; // maximum page links to display
  var $templateCode; // the html template code for substitution
  var $formPrefixId; // the prfix of the html FORM like 'tx_vdtableview_filters'
  var $displayTop; // display the pagination on top
  var $displayBottom; // display the pagination on bottom
  var $displayStats; // display the statistics 
    
  // build links with POST and GET parameters like search='marc' or lang=DE
  var $formParameters = array(); // parameters from POST or GET to add in the links
  //
  var $recordsTotal; // total records
  //  my var
  var $pageCurrentNo; // the current page
  var $pagesTotalNb; // the total pages calculated
  var $recordFrom; // the first record to display
  var $recordTo; // the last record to display

  // my views
  var $pagePrevView; // the html code for previous page link
  var $pageNextView; // the html code for next page link
  var $pagesLinksView = array();  // the html code for the pages link
  var $paginationView; // the final html code
  
  /**
   * initialization of the object
   * get/set external configuration
   */ 
  public function init($maxRecPage=20, $maxPageLinks=10, $displayTop=1,$displayBottom=1,$displayStats=1){
    $this->cObj = t3lib_div::makeInstance('tslib_cObj');
    // lang
    $this->pi_loadLL();
    $this->maxRecPage = intVal($maxRecPage);
    $this->maxPageLinks = intVal($maxPageLinks);     
    $this->displayTop = $displayTop;
    $this->displayBottom = $displayBottom;
    $this->displayStats = $displayStats;
  }
  /**
   * read POST GET in order to set the request configuration
   * manage one depth for parameters with arr1
   * param string formPrefixId: the ID of the html FORM to read
   */ 
  public function readRequestPOSTGET($formPrefixId,$arr1='',$page=''){
    if($formPrefixId){
      $this->formPrefixId = $formPrefixId;
      $this->formPrefixId_arr1 = $arr1;
      // set request infos
      $this->formParameters = t3lib_div::_GP($this->formPrefixId);
      $this->formParameters = $arr1?$this->formParameters[$arr1]:$this->formParameters[$arr1];
      $this->pageCurrentNo = intVal(t3lib_div::_GP('pagination_page'));
    }
    // force page
    $this->pageCurrentNo = $page?$page:$this->pageCurrentNo;
    $this->pageCurrentNo = $this->pageCurrentNo > 0?$this->pageCurrentNo:1; 
    // set the query LIMITS
    if($this->maxRecPage > 0){
      $this->recordFrom = intVal($this->pageCurrentNo * $this->maxRecPage - $this->maxRecPage);
      $this->recordFrom = $this->recordFrom < 0 ? 0:$this->recordFrom;
      $this->recordTo = $this->pageCurrentNo * $this->maxRecPage;
    }else{
      $this->recordFrom = 0;
    }
  }
  /**
   * set the field and value for sorting the table
   */ 
  public function set_sortField($sort_parameterLabel, $sort){
    $this->sort_parameterLabel = $sort_parameterLabel;
    $this->fiedlSort = $sort;
  }
  
  
  /**
   * generate links for the navigation but not the statistics
   * 
   */ 
  public function create($recordsTotal) {

    $this->recordsTotal = intVal($recordsTotal);
    if($this->maxRecPage <= 0){
      $this->recordTo = $this->recordsTotal;
      return;
    }
    $this->pagesTotalNb = ceil($this->recordsTotal / $this->maxRecPage); 
    $current = $this->pageCurrentNo;
    // set pages intervals
    $intervalLeft = floor(($this->maxPageLinks-1)/2);
    $intervalRight = floor($this->maxPageLinks/2);

    $bonusL = ($current <= $intervalLeft)? ($intervalLeft - $current)+1:0;
    $bonusR = ($current > ($this->pagesTotalNb-$intervalRight))? $intervalRight - ($current-$this->pagesTotalNb):0;

    // create range of pages
    if($bonusL > $bonusR){      
       $pLeft  = 1;
        $pRight = $this->maxPageLinks;
    }elseif($bonusL < $bonusR){      
       $pLeft  = $this->pagesTotalNb - $this->maxPageLinks+1;
        $pRight = $this->pagesTotalNb;
    }elseif($bonusL == $bonusR){
        $pLeft  = $current - $intervalLeft;
        $pRight = $current + $intervalRight;
    }

    $pageRangeLeft = max (1, $pLeft);
    $pageRangeRight = min ($pRight, $this->pagesTotalNb);

    if($this->pagesTotalNb>0){
      $pagesRange = range($pageRangeLeft, $pageRangeRight);			    
    }else{
      $pagesRange[0] = 1;
    }
    // clean POST parameters
    if($this->formParameters){
      $arr1 = $this->formPrefixId_arr1?'['. $this->formPrefixId_arr1 .']':'';
      foreach($this->formParameters as $crit => $val) {
        // store the value in the array $params that will be used to build the link URL
        // $linkParams[$this->formPrefixId.'['.htmlspecialchars($crit).']'] = htmlspecialchars($val);
        if(is_array($val)){
          foreach($val as $k2=>$v2){
            $linkParams[$this->formPrefixId.$arr1.'['. $crit .']['.htmlspecialchars($k2).']'] = htmlspecialchars($v2);
          }
        }else{
          $linkParams[$this->formPrefixId. $arr1 .'['.htmlspecialchars($crit).']'] = htmlspecialchars($val);
        }
      } 
    }
    // add sorting value
    $linkParams[$this->sort_parameterLabel] = htmlspecialchars($this->fiedlSort);
    
    // builds pages links
    foreach ($pagesRange as $pageNbr){
      // the current page case
      if ($this->pageCurrentNo == $pageNbr) {
        $link = '<span class="currentpage">'.$pageNbr.'</span>';
        } else {
        // add to the GET parameters the aimed page number
        $linkParams['pagination_page'] = $pageNbr;
        $linkTitle = $this->pi_getLL('paginationGotoPage','Goto page') . ' '. $pageNbr;
        $link = $this->pi_linkTP($pageNbr, $linkTitle, $linkParams,'gotopage');   
      }
      $this->pagesLinksView[] = $link;
    }
    // prev and next page

    if($this->pageCurrentNo < $this->pagesTotalNb){
      $linkParams['pagination_page'] = $this->pageCurrentNo + 1;
      $linkTitle = $this->pi_getLL('paginationNextPage','Next page');
      $this->pageNextView = $this->pi_linkTP('&gt;&gt;', $linkTitle, $linkParams,'nextpage');
    }else{
      $this->pageNextView = ' &gt;&gt;';
    }

    if($this->pageCurrentNo > 1){
      $linkParams['pagination_page'] = $this->pageCurrentNo - 1;
      $linkTitle = $this->pi_getLL('paginationPreviousPage','Previous Page');
      $this->pagePrevView = $this->pi_linkTP('&lt;&lt;', $linkTitle, $linkParams,'previouspage');
    }else{
      $this->pagePrevView = '&lt;&lt; ';
    }    
    
  }   
  /**
   * set/get methods
   * 
   */ 
  public function setTemplateCode($tpl=''){
    $this->templateCode = $tpl;  
  } 
  public function getRecordFrom(){
    return $this->recordFrom;
  }
  public function getMaxRecPage(){
    return $this->maxRecPage;
  }
  public function getRecordsLimitNb(){
    if($this->maxRecPage > 0){
      return $this->maxRecPage;
    }else{
      // return all records but max 10000
      return 10000;
    }
  }
  /**
   * return the html code for the pagination after substitution in the template
   * 
   */  
  public function display($marker='###PAGINATION###')
  {
    
    $markers=array();
    if(($this->displayTop && $marker=='###PAGINATION###') || 
       ($this->displayBottom && $marker=='###PAGINATION_BOTTOM###')){
      foreach($this->pagesLinksView as $k=>$v){
        $pagesRangeHtml .= $v;
      }      
      $markers['###PAGES_RANGE###']    = $pagesRangeHtml;
      $markers['###PAGE_PREV###']    = $this->pagePrevView;		
      $markers['###PAGE_NEXT###']    = $this->pageNextView;		
      if($this->maxRecPage <= 0){
        $markers['###PAGES_RANGE###']    = '&nbsp;';
      }
    }else{   
      $markers['###PAGES_RANGE###']    = '&nbsp;';
      $markers['###PAGE_PREV###']    = '';		
      $markers['###PAGE_NEXT###']    = '';		
    }
    // make statistics
    if($this->displayStats && ($this->displayTop && $marker=='###PAGINATION###') || 
       $this->displayStats && ($this->displayBottom && $marker=='###PAGINATION_BOTTOM###') 
      ){
      $to = $this->recordTo > $this->recordsTotal?$this->recordsTotal:$this->recordTo;
      $markers['###PAGE_FROM###']    = $this->recordsTotal==0?0:$this->recordFrom+1;		
      $markers['###PAGE_TO###']    = $to;		
      $markers['###PAGE_TOTAL###']    = $this->recordsTotal;
      $tplStats = $this->cObj->getSubpart($this->templateCode,'###PAGINATION_STATS###');
      $templateSubParts['###PAGINATION_STATS###'] = $this->cObj->substituteMarkerArrayCached($tplStats,$markers);      
    }else{
      $tplStats = $this->cObj->getSubpart($this->templateCode,'###PAGINATION_STATS###');
      $templateSubParts['###PAGINATION_STATS###'] = '&nbsp;';   
    }

    $template = $this->cObj->getSubpart($this->templateCode,$marker);

    // final
    $content  = $this->cObj->substituteMarkerArrayCached($template,$markers, $templateSubParts);


    return $content;      
  } 
 
    
  /**
  * Create simple typolink
  * 
  */      
  function pi_linkTP($label, $title, $urlParameters=array(),$class)
  {
    $conf = array();
    $conf['parameter'] = $GLOBALS['TSFE']->id;
    $conf['ATagParams'] = 'class = "'.$class.'"';
    $conf['additionalParams'] = $this->conf['parent.']['addParams'].t3lib_div::implodeArrayForUrl('',$urlParameters,'',1).$this->pi_moreParams;
    // title for the link
    $conf['title'] = htmlspecialchars($title);
    return $this->cObj->typoLink($label, $conf);
  }  
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdtableview_pagination.php'])	{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/class.tx_vdtableview_pagination.php']);
}

?>