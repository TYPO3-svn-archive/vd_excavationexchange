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
class tx_vdexcavationexchange_response extends tslib_pibase {
	var $prefixId = 'tx_vdexcavationexchange_response';		// Same as class name
	var $scriptRelPath = 'pi1/response/class.tx_vdexcavationexchange_response.php';	// Path to this script relative to the extension dir.
	var $extKey = 'vd_excavationexchange';	// The extension key.
	//
	var $folderRelPath; // path to the current folder
	var $table = 'tx_vdexcavationexchange_response';
	
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
		$this->folderRelPath = $GLOBALS['TYPO3_LOADED_EXT'][$this->extKey]['siteRelPath'].'/pi1/response';	

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
		// select user records
		// $qw_fe_cruser_id = $options['fe_cruser_id']?$this->table.'.fe_cruser_id = '. intval($options['fe_cruser_id']):'';
		// select where
		$qw_where = $options['where']?$options['where']:'';
    // access check 
		$GLOBALS['SIM_ACCESS_TIME'] += 100;		
		$qw_access = ($options['noAccessClause'] == 1)?'':t3lib_BEfunc::deleteClause($this->table) . t3lib_BEfunc::BEenableFields($this->table);
		
		$q_where = $qw_pid;
		$q_where = ($q_where && $qw_uid)?$q_where.' AND '.$qw_uid:$q_where.$qw_uid;
		$q_where = ($q_where && $qw_fe_cruser_id)?$q_where.' AND '.$qw_fe_cruser_id:$q_where.$qw_fe_cruser_id;
		$q_where = ($q_where && $qw_where)?$q_where.' AND '.$qw_where:$q_where.$qw_where;
		// $qw_access has the AND already
		$q_where = ($q_where && $qw_access)?$q_where.' '.$qw_access:$q_where.$qw_access;
		$res=$GLOBALS['TYPO3_DB']->exec_SELECTquery('*',$this->table,$q_where); 
		$record = $this->fetchQuery($res);
		// transform records for lists and date

		$list = array('crdate');
		foreach($record as $k=>$v){
			foreach($list as $lk=>$lv){
				$item = $v[$lv];
				// make date
				$record[$k][$lv.'_label'] = date('j.m.Y',$item);
			}
			
			// display the roles of the roles_label
			$roles_label ='';
			if($record[$k]['rolemaitreouvrage']){
				$roles_label = 'Maître d\'ouvrage';
			}
			if($record[$k]['rolearchitecte']){
				$roles_label = $roles_label? $roles_label. ', Architecte':'Architecte';
			}
			if($record[$k]['roleingenieur']){
				$roles_label = $roles_label? $roles_label. ', Ingénieur':'Ingénieur';
			}
			if($record[$k]['roleentrepreneur']){
				$roles_label = $roles_label? $roles_label. ', Ingénieur':'Ingénieur';
			}			
			if($record[$k]['roleautre']){
				$roles_label = $roles_label? $roles_label. ', Autre':'Autre';
			}			
			$record[$k][roles_label] = $roles_label;
			// end roles_label			
		}      
		
		return $record;
	}
	/**
	*
	*/
	function insert($data, $options=''){
		global $TCA;

		if(!intval($options['startingPoint'])){
			return false;
		}

		// default fields values like time ...
		// pid 	tstamp 	crdate 	cruser_id 	deleted 	hidden 	starttime 	endtime 	fe_group 
		$recordNew['pid'] = intval($options['startingPoint']);
		$recordNew['tstamp'] = $GLOBALS['EXEC_TIME'];
		$recordNew['crdate'] = $GLOBALS['EXEC_TIME'];
		// if BE user
		$recordNew['cruser_id'] = ''; // to do if time
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
		// select specific record
		$qw_uid = $this->table.'.uid = '. intval($data['uid']);
		// access check
		$qw_access = ($options['noAccessClause'] == 1)?'':t3lib_BEfunc::deleteClause($this->table) . t3lib_BEfunc::BEenableFields($this->table);
		// $qw_access has the AND already
		$q_where = $qw_uid . $qw_access;
		$res=$GLOBALS['TYPO3_DB']->exec_UPDATEquery($this->table,$q_where,$fields_values);                    
		return $res;
	}
	
	function delete(){
		
	}
	
	function readMulti(){
		
	}
	function get_recordsCount(){
		if($this->recordsCount){
		}else{
			$qw_pid = 'pid='. intval($this->conf['startingPoint']);
			// access check
			$qw_access = t3lib_BEfunc::deleteClause($this->table) . t3lib_BEfunc::BEenableFields($this->table);
			$res=$GLOBALS['TYPO3_DB']->exec_SELECTquery("count(uid)",$this->table,$qw_pid.$qw_access);  
			$record = $this->fetchQuery($res);
			$this->recordsCount = $record['']['count(uid)'];
		}
		return $this->recordsCount;
	}
	// the fetch query function
	protected function fetchQuery($ress){
		$result = Array();
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($ress)) {	
			$result[$row['uid']] = $row;
		}
		return $result;
	}


}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/response/class.tx_vdexcavationexchange_response.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/vd_excavationexchange/pi1/response/class.tx_vdexcavationexchange_response.php']);
}

?>