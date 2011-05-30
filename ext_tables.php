<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
$TCA['tx_vdexcavationexchange_offer'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer',		
		'label'     => 'uid',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',	
			'starttime' => 'starttime',	
			'endtime' => 'endtime',	
			'fe_group' => 'fe_group',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_vdexcavationexchange_offer.gif',
	),
);

$TCA['tx_vdexcavationexchange_search'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search',		
		'label'     => 'uid',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',	
			'starttime' => 'starttime',	
			'endtime' => 'endtime',	
			'fe_group' => 'fe_group',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_vdexcavationexchange_search.gif',
	),
);

$TCA['tx_vdexcavationexchange_response'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_response',		
		'label'     => 'uid',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',	
			'fe_group' => 'fe_group',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_vdexcavationexchange_response.gif',
	),
);

$TCA['tx_vdexcavationexchange_offerlot'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot',		
		'label'     => 'label2',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_vdexcavationexchange_offerlot.gif',
	),
);


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key';


t3lib_extMgm::addPlugin(array(
	'LLL:EXT:vd_excavationexchange/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1'
),'list_type');
?>