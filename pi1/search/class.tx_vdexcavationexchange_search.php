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
class tx_vdexcavationexchange_search extends tslib_pibase {
	var $prefixId = 'tx_vdexcavationexchange_search';		// Same as class name
	var $scriptRelPath = 'pi1/search/class.tx_vdexcavationexchange_search.php';	// Path to this script relative to the extension dir.
	var $extKey = 'vd_excavationexchange';	// The extension key.
  //
  var $folderRelPath; // path to the current folder
  var $table = 'tx_vdexcavationexchange_search';
  var $fieldsItems; // the translated items for each fields
  var $recordsOpenCount; // lots open
  var $recordsArchiveCount; // lots archived
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function ini($conf)	{
		$this->conf=$conf;  
    // create cObj
    $this->cObj = t3lib_div::makeInstance('tslib_cObj');
    // config
    $this->folderRelPath = $GLOBALS['TYPO3_LOADED_EXT'][$this->extKey]['siteRelPath'].'/pi1/search';
    $this->setFieldsItems();
	}
  
  // ***************************************************
  // DATABASE
  // ***************************************************

  function select($uid, $options){
    $uid = (intval($uid));
    $qw_pid = '';
    if(!intval($options['startingPoint'])){
      return false;
    }else{
      $pid = intval($options['startingPoint']);
      $qw_pid = 'pid='. $pid;
    }
    // select specific record
    $qw_uid = $uid?$this->table.'.uid = '. $uid:'';
    // select archives
    $qw_archive = $options['archive']?$this->table.'.archive = 1':$this->table.'.archive != 1';        
    // select user records
    $qw_fe_cruser_id = $options['fe_cruser_id']?$this->table.'.fe_cruser_id = '. intval($options['fe_cruser_id']):'';
    $qw_where = $options['where']?$options['where']:'';
    // access check 
		$GLOBALS['SIM_ACCESS_TIME'] += 100;		
    $qw_access = ($options['noAccessClause'] == 1)?'':t3lib_BEfunc::deleteClause($this->table) . t3lib_BEfunc::BEenableFields($this->table);
    $q_where = $qw_pid;
    $q_where = ($q_where && $qw_uid)?$q_where.' AND '.$qw_uid:$q_where.$qw_uid;
    $q_where = ($q_where && $qw_fe_cruser_id)?$q_where.' AND '.$qw_fe_cruser_id:$q_where.$qw_fe_cruser_id;
    $q_where = ($q_where && $qw_archive)?$q_where.' AND '.$qw_archive:$q_where.$qw_archive;
    $q_where = ($q_where && $qw_where)?$q_where.' AND '.$qw_where:$q_where.$qw_where;
		
    // $qw_access has the AND already
    $q_where = ($q_where && $qw_access)?$q_where.' '.$qw_access:$q_where.$qw_access;
    $res=$GLOBALS['TYPO3_DB']->exec_SELECTquery('*',$this->table,$q_where);                    
    $record = $this->fetchQuery($res);
    // transform records for lists and date
    $list = array('catmatterrial','designiationuscsmeuble','designiationsimplemeuble','designiationsimplemeubleha','designiationsimplemeublehb','designiationroche','designiationrocheautre','district');	
    foreach($record as $k=>$v){
      foreach($list as $lk=>$lv){
        $item = $v[$lv];
        // remove the defautl values or message values
        if($item>0){
          $record[$k][$lv.'_label'] = $this->fieldsItems[$lv][$item];
        }
      }
    }
    // transform records for lists and date
    $list = array('deliverydate','startdatework','enddatework');
    foreach($record as $k=>$v){
      foreach($list as $lk=>$lv){
        $item = $v[$lv];
        // make date
        $record[$k][$lv] = date('j.m.Y',$item);
      }
    }
    // transform records for fe_cruser_id
    $list = 'fe_cruser_id';
    foreach($record as $k=>$v){
			$item = $v[$list];
			// remove the defautl values or message values
			if($item>0){
				$record[$k][$list.'_label'] = array();
				$record[$k][$list.'_label'] = t3lib_BEfunc::getRecord('fe_users',$item,'uid, username, email, company, last_name, disable, deleted');
			}
			// display the description of the material infoMat_label
			$infoCompl ='';
			if($record[$k]['catmatterrial'] == 1 ){
				$infoCompl = $record[$k][designiationsimplemeubleha_label];
			}elseif($record[$k]['catmatterrial'] == 2){
				$infoCompl = $record[$k][designiationsimplemeublehb_label];
			}elseif($record[$k]['catmatterrial'] == 3){
				$infoCompl = $record[$k][designiationsimplemeuble_label];
				if($record[$k][designiationroche]==4){
					$infoCompl = $infoCompl?$infoCompl . ', ' . $record[$k][designiationrocheautre]: $record[$k][designiationrocheautre]; 
				}else{
					$infoCompl = $infoCompl?$infoCompl . ', ' . $record[$k][designiationroche_label]: $record[$k][designiationrocheautre]; 
				}
			}
			$record[$k][infoMat_label] = $infoCompl;
			// end infoMat_label  			
    }      
    
    return $record;
  }
  /**
   *
   */
  function insert($data, $options=''){
    global $TCA;
    // check record does not exist
    if($data['uid']){
      return false;
    }
    if(!intval($options['startingPoint'])){
      return false;
    }
    if(!$data['fe_cruser_id']){
      return false;
    }
    // default fields values like time ...
    // pid 	tstamp 	crdate 	cruser_id 	deleted 	hidden 	starttime 	endtime 	fe_group 
    $recordNew['pid'] = intval($options['startingPoint']);
    $recordNew['tstamp'] = $GLOBALS['EXEC_TIME'];
    $recordNew['crdate'] = $GLOBALS['EXEC_TIME'];
    $recordNew['starttime'] = $GLOBALS['EXEC_TIME'];
    // if BE user
    $recordNew['cruser_id'] = ''; // to do
    $recordNew['fe_group'] = ''; // to do
    
    // $recordNew['fe_userid'] = '';
    // check fields exist with TCA
    t3lib_div::loadTCA($this->table);
    $columns = $TCA[$this->table]['columns'];
    foreach($data as $f=>$v){
      if(array_key_exists($f,$columns)){
        $recordNew[$f] = $v; 
      } 
    }
    
    // check validations not here
    // transform records for date
    $list = array('deliverydate','startdatework','enddatework');
    foreach($recordNew as $k=>$v){
      foreach($list as $lk=>$lv){
        if($k == $lv && !is_int($v)){
          $recordNew[$k] = strtotime($v)?strtotime($v):$v;
        }
      }
    }  
    
    // insert data
    $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery($this->table, $recordNew);
    $newID = mysql_insert_id();
    // check reset
    return $newID;
    
  }
    
  function update($data, $options=''){
    global $TCA;
    if(!$data['uid']){
      return false;
    }
    // check fields exist with TCA
    t3lib_div::loadTCA($this->table);
    $columns = $TCA[$this->table]['columns'];
    foreach($data as $f=>$v){
      if(array_key_exists($f,$columns)){
        $fields_values[$f] = $v; 
      } 
    }
    // add deleted
    if($data[deleted]){
      $fields_values[deleted] = 1; 
    }
    // tstamp
    $fields_values['tstamp'] = $GLOBALS['EXEC_TIME'];
    // check validations not here
    // transform records for date
    $list = array('deliverydate','startdatework','enddatework');
    foreach($fields_values as $k=>$v){
      foreach($list as $lk=>$lv){
        if($k == $lv && !is_int($v)){
          $fields_values[$k] = strtotime($v)?strtotime($v):$v;
        }
      }
    }    
    // select specific record
    $qw_uid = $this->table.'.uid = '. $data['uid'];
    // access check
    $qw_access = ($options['noAccessClause'] == 1)?'':t3lib_BEfunc::deleteClause($this->table) . t3lib_BEfunc::BEenableFields($this->table);

    // $qw_access has the AND already
    $q_where = $qw_uid . $qw_access;
    $res=$GLOBALS['TYPO3_DB']->exec_UPDATEquery($this->table,$q_where,$fields_values);                    
    return $res;
  }
  
  function archiver($recid){
    if(!intVal($recid)){
      return false;
    }
    $data['uid']=$recid;
    $data['archive']=1;
    $res = $this->update($data);
    return $res;
  }
  function activer($recid){
    
    if(!intVal($recid)){
      return false;
    }
    $data['uid']=$recid;
    $data['archive']=0;
    
    $res = $this->update($data);
    return $res;
  }
  // the fetch query function
  protected function fetchQuery($ress){
    $result = Array();
  	while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($ress)) {	
      $result[$row['uid']] = $row;
    }
    return $result;
  }
  
  function delete($recid){
    if(!intVal($recid)){
      return false;
    }
    $data['uid']=$recid;
    $data['deleted']=1;
    $options['noAccessClause']=1;
    $res = $this->update($data, $options);
    return $res;	
  } 
  function get_fecruserid($id){
    return t3lib_BEfunc::getRecord($this->table,$id,'uid, fe_cruser_id');
  }
  function get_recordsOpenCount(){
    if($this->recordsOpenCount){
    }else{
      // select archives
      $qw_archive = $this->table.'.archive != 1';    
      $qw_pid = 'pid='. intval($this->conf['startingPoint']);
      // access check
      $qw_access = t3lib_BEfunc::deleteClause($this->table) . t3lib_BEfunc::BEenableFields($this->table);
      // $res = t3lib_BEfunc::getRecordsByField($this->table,'archive',0, $qw_access);
      $res=$GLOBALS['TYPO3_DB']->exec_SELECTquery("count(uid)",$this->table,$qw_pid.' AND '.$qw_archive .' '. $qw_access);  
      $record = $this->fetchQuery($res);
      $this->recordsOpenCount = $record['']['count(uid)'];
    }
    return $this->recordsOpenCount;
  }
  function get_recordsArchiveCount(){
    if($this->recordsArchiveCount){
    }else{
      // select archives
      $qw_archive = $this->table.'.archive = 1';    
      $qw_pid = 'pid='. intval($this->conf['startingPoint']);
      // access check
      $qw_access = t3lib_BEfunc::deleteClause($this->table) . t3lib_BEfunc::BEenableFields($this->table);
      // $res = t3lib_BEfunc::getRecordsByField($this->table,'archive',0, $qw_access);
      $res=$GLOBALS['TYPO3_DB']->exec_SELECTquery("count(uid)",$this->table,$qw_pid.' AND '.$qw_archive .' '. $qw_access);  
      $record = $this->fetchQuery($res);
      $this->recordsArchiveCount = $record['']['count(uid)'];
    }
    return $this->recordsArchiveCount;
  }
  
/**
 * Translate the items of the fields
 *
 */
  function setFieldsItems(){
    global $TCA, $TCA_DESCR, $GLOBALS;
    unset($this->fieldsItems);
    $extPath = t3lib_extMgm::extPath('vd_excavationexchange');
    
    // $LL = $LANG->readLLfile($extPath.'locallang_db.xml'); // BE only
    t3lib_div::loadTCA('tx_vdexcavationexchange_search');
    $tableconf = $TCA['tx_vdexcavationexchange_search'];
    foreach($tableconf['columns'] as $field=>$items){
      if(sizeof($items['config']['items'])>0){
        foreach($items['config']['items'] as $k=>$v){
          // $l = str_replace("LLL:EXT:vd_excavationexchange/locallang_db.xml:",'',$v[0]);
          $l =  $GLOBALS['TSFE']->sL($v[0]);
          // $this->fieldsItems[$field][$k] = $LANG->getLLL($l,$LL); // BE only
          $this->fieldsItems[$field][$k] = $l;
        }
      // sort
      // keep the TCA order;  
      }
    }
  }
  
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/search/class.tx_vdexcavationexchange_search.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/search/class.tx_vdexcavationexchange_search.php']);
}

?>