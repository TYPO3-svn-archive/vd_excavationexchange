<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_vdexcavationexchange_offer'] = array (
	'ctrl' => $TCA['tx_vdexcavationexchange_offer']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,starttime,endtime,fe_group,rolemaitreouvrage,rolearchitecte,roleingenieur,roleentrepreneur,roleautre,fe_cruser_id,contactname,contactfunction,commune,district,address,ouvragetype,permisconstdepose,permisconstobtenu,startdatework,enddatework,destcomblementsiteextraction,destdecharge,destautre,destautretxt,destkm,siteindicepollution,sitepollue,siteindustrielle,sitedechetsdemolition,sitehydrocarbure,sitedechets,siteaccident'
	),
	'feInterface' => $TCA['tx_vdexcavationexchange_offer']['feInterface'],
	'columns' => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array (
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'fe_group' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'  => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
					array('LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.any_login', -2),
					array('LLL:EXT:lang/locallang_general.xml:LGL.usergroups', '--div--')
				),
				'foreign_table' => 'fe_groups'
			)
		),
		'rolemaitreouvrage' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.rolemaitreouvrage',		
			'config' => array (
				'type' => 'check',
			)
		),
		'rolearchitecte' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.rolearchitecte',		
			'config' => array (
				'type' => 'check',
			)
		),
		'roleingenieur' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.roleingenieur',		
			'config' => array (
				'type' => 'check',
			)
		),
		'roleentrepreneur' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.roleentrepreneur',		
			'config' => array (
				'type' => 'check',
			)
		),
		'roleautre' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.roleautre',		
			'config' => array (
				'type' => 'check',
			)
		),
		'fe_cruser_id' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.fe_cruser_id',		
			'config' => array (
				'type' => 'group',	
				'internal_type' => 'db',	
				'allowed' => 'fe_users',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'contactname' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.contactname',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'contactfunction' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.contactfunction',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'commune' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.commune',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'district' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.district',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.district.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.district.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.district.I.2', '2'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.district.I.3', '3'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.district.I.4', '4'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.district.I.5', '5'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.district.I.6', '6'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.district.I.7', '7'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.district.I.8', '8'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.district.I.9', '9'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.district.I.10', '10'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.district.I.11', '11'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.district.I.12', '12'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.district.I.13', '13'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.district.I.14', '14'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.district.I.15', '15'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'address' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.address',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'ouvragetype' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.ouvragetype',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'permisconstdepose' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.permisconstdepose',		
			'config' => array (
				'type' => 'radio',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.permisconstdepose.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.permisconstdepose.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.permisconstdepose.I.2', '2'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.permisconstdepose.I.3', '3'),
				),
			)
		),
		'permisconstobtenu' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.permisconstobtenu',		
			'config' => array (
				'type' => 'radio',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.permisconstobtenu.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.permisconstobtenu.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.permisconstobtenu.I.2', '2'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.permisconstobtenu.I.3', '3'),
				),
			)
		),
		'startdatework' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.startdatework',		
			'config' => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0'
			)
		),
		'enddatework' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.enddatework',		
			'config' => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0'
			)
		),
		'destcomblementsiteextraction' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.destcomblementsiteextraction',		
			'config' => array (
				'type' => 'check',
			)
		),
		'destdecharge' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.destdecharge',		
			'config' => array (
				'type' => 'check',
			)
		),
		'destautre' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.destautre',		
			'config' => array (
				'type' => 'check',
			)
		),
		'destautretxt' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.destautretxt',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'destkm' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.destkm',		
			'config' => array (
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array (
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
		'siteindicepollution' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.siteindicepollution',		
			'config' => array (
				'type' => 'radio',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.siteindicepollution.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.siteindicepollution.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.siteindicepollution.I.2', '2'),
				),
			)
		),
		'sitepollue' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.sitepollue',		
			'config' => array (
				'type' => 'radio',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.sitepollue.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.sitepollue.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.sitepollue.I.2', '2'),
				),
			)
		),
		'siteindustrielle' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.siteindustrielle',		
			'config' => array (
				'type' => 'radio',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.siteindustrielle.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.siteindustrielle.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.siteindustrielle.I.2', '2'),
				),
			)
		),
		'sitedechetsdemolition' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.sitedechetsdemolition',		
			'config' => array (
				'type' => 'radio',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.sitedechetsdemolition.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.sitedechetsdemolition.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.sitedechetsdemolition.I.2', '2'),
				),
			)
		),
		'sitehydrocarbure' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.sitehydrocarbure',		
			'config' => array (
				'type' => 'radio',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.sitehydrocarbure.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.sitehydrocarbure.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.sitehydrocarbure.I.2', '2'),
				),
			)
		),
		'sitedechets' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.sitedechets',		
			'config' => array (
				'type' => 'radio',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.sitedechets.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.sitedechets.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.sitedechets.I.2', '2'),
				),
			)
		),
		'siteaccident' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.siteaccident',		
			'config' => array (
				'type' => 'radio',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.siteaccident.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.siteaccident.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offer.siteaccident.I.2', '2'),
				),
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'hidden;;1;;1-1-1, rolemaitreouvrage, rolearchitecte, roleingenieur, roleentrepreneur, roleautre, fe_cruser_id, contactname, contactfunction, commune, district, address, ouvragetype, permisconstdepose, permisconstobtenu, startdatework, enddatework, destcomblementsiteextraction, destdecharge, destautre, destautretxt, destkm, siteindicepollution, sitepollue, siteindustrielle, sitedechetsdemolition, sitehydrocarbure, sitedechets, siteaccident')
	),
	'palettes' => array (
		'1' => array('showitem' => 'starttime, endtime, fe_group')
	)
);



$TCA['tx_vdexcavationexchange_search'] = array (
	'ctrl' => $TCA['tx_vdexcavationexchange_search']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,starttime,endtime,fe_group,fe_cruser_id,volume,deliverydate,comment,archive,catmatterrial,designiationuscsmeuble,designiationsimplemeuble,designiationsimplemeubleha,designiationsimplemeublehb,designiationroche,designiationrocheautre,commune,address,district,materialuse,startdatework,enddatework'
	),
	'feInterface' => $TCA['tx_vdexcavationexchange_search']['feInterface'],
	'columns' => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array (
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'fe_group' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'  => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
					array('LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.any_login', -2),
					array('LLL:EXT:lang/locallang_general.xml:LGL.usergroups', '--div--')
				),
				'foreign_table' => 'fe_groups'
			)
		),
		'fe_cruser_id' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.fe_cruser_id',		
			'config' => array (
				'type' => 'group',	
				'internal_type' => 'db',	
				'allowed' => 'fe_groups',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'volume' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.volume',		
			'config' => array (
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array (
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
		'deliverydate' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.deliverydate',		
			'config' => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0'
			)
		),
		'comment' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.comment',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'archive' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.archive',		
			'config' => array (
				'type' => 'check',
			)
		),
		'catmatterrial' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.catmatterrial',		
			'config' => array (
				'type' => 'radio',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.catmatterrial.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.catmatterrial.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.catmatterrial.I.2', '2'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.catmatterrial.I.3', '3'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.catmatterrial.I.4', '4'),
				),
			)
		),
		'designiationuscsmeuble' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationuscsmeuble',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationuscsmeuble.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationuscsmeuble.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationuscsmeuble.I.2', '2'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationuscsmeuble.I.3', '3'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationuscsmeuble.I.4', '4'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationuscsmeuble.I.5', '5'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationuscsmeuble.I.6', '6'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationuscsmeuble.I.7', '7'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationuscsmeuble.I.8', '8'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationuscsmeuble.I.9', '9'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationuscsmeuble.I.10', '10'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationuscsmeuble.I.11', '11'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'designiationsimplemeuble' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeuble',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeuble.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeuble.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeuble.I.2', '2'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeuble.I.3', '3'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeuble.I.4', '4'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeuble.I.5', '5'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'designiationsimplemeubleha' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeubleha',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeubleha.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeubleha.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeubleha.I.2', '2'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeubleha.I.3', '3'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeubleha.I.4', '4'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'designiationsimplemeublehb' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeublehb',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeublehb.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeublehb.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeublehb.I.2', '2'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeublehb.I.3', '3'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationsimplemeublehb.I.4', '4'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'designiationroche' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationroche',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationroche.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationroche.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationroche.I.2', '2'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationroche.I.3', '3'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationroche.I.4', '4'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'designiationrocheautre' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.designiationrocheautre',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',
			)
		),
		'commune' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.commune',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'address' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.address',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'district' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.district',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.district.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.district.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.district.I.2', '2'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.district.I.3', '3'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.district.I.4', '4'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.district.I.5', '5'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.district.I.6', '6'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.district.I.7', '7'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.district.I.8', '8'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.district.I.9', '9'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.district.I.10', '10'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.district.I.11', '11'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.district.I.12', '12'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.district.I.13', '13'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.district.I.14', '14'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.district.I.15', '15'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'materialuse' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.materialuse',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'startdatework' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.startdatework',		
			'config' => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0'
			)
		),
		'enddatework' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_search.enddatework',		
			'config' => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0'
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'hidden;;1;;1-1-1, fe_cruser_id, volume, deliverydate, comment, archive, catmatterrial, designiationuscsmeuble, designiationsimplemeuble, designiationsimplemeubleha, designiationsimplemeublehb, designiationroche, designiationrocheautre, commune, address, district, materialuse, startdatework, enddatework')
	),
	'palettes' => array (
		'1' => array('showitem' => 'starttime, endtime, fe_group')
	)
);



$TCA['tx_vdexcavationexchange_response'] = array (
	'ctrl' => $TCA['tx_vdexcavationexchange_response']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,fe_group,lot_id,search_id,fe_cruser_id,firstname,lastname,company,address,location,phone,fax,email,rolemaitreouvrage,rolearchitecte,roleingenieur,roleentrepreneur,roleautre'
	),
	'feInterface' => $TCA['tx_vdexcavationexchange_response']['feInterface'],
	'columns' => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'fe_group' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'  => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
					array('LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.any_login', -2),
					array('LLL:EXT:lang/locallang_general.xml:LGL.usergroups', '--div--')
				),
				'foreign_table' => 'fe_groups'
			)
		),
		'lot_id' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_response.lot_id',		
			'config' => array (
				'type' => 'group',	
				'internal_type' => 'db',	
				'allowed' => 'tx_vdexcavationexchange_offerlot',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'search_id' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_response.search_id',		
			'config' => array (
				'type' => 'group',	
				'internal_type' => 'db',	
				'allowed' => 'pages',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'fe_cruser_id' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_response.fe_cruser_id',		
			'config' => array (
				'type' => 'group',	
				'internal_type' => 'db',	
				'allowed' => 'fe_users',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'firstname' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_response.firstname',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'lastname' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_response.lastname',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'company' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_response.company',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'address' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_response.address',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'location' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_response.location',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'phone' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_response.phone',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'fax' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_response.fax',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'email' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_response.email',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'rolemaitreouvrage' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_response.rolemaitreouvrage',		
			'config' => array (
				'type' => 'check',
			)
		),
		'rolearchitecte' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_response.rolearchitecte',		
			'config' => array (
				'type' => 'check',
			)
		),
		'roleingenieur' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_response.roleingenieur',		
			'config' => array (
				'type' => 'check',
			)
		),
		'roleentrepreneur' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_response.roleentrepreneur',		
			'config' => array (
				'type' => 'check',
			)
		),
		'roleautre' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_response.roleautre',		
			'config' => array (
				'type' => 'check',
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'hidden;;1;;1-1-1, lot_id, search_id, fe_cruser_id, firstname, lastname, company, address, location, phone, fax, email, rolemaitreouvrage, rolearchitecte, roleingenieur, roleentrepreneur, roleautre')
	),
	'palettes' => array (
		'1' => array('showitem' => 'fe_group')
	)
);



$TCA['tx_vdexcavationexchange_offerlot'] = array (
	'ctrl' => $TCA['tx_vdexcavationexchange_offerlot']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,offer_id,label2,fe_cruser_id,catmatterrial,designiationuscsmeuble,designiationsimplemeuble,designiationsimplemeubleha,designiationsimplemeublehb,designiationroche,designiationrocheautre,remarques,volume,prix,payereprise,archive,sursitefranco'
	),
	'feInterface' => $TCA['tx_vdexcavationexchange_offerlot']['feInterface'],
	'columns' => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'offer_id' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.offer_id',		
			'config' => array (
				'type' => 'select',	
				'foreign_table' => 'tx_vdexcavationexchange_offer',	
				'foreign_table_where' => 'AND tx_vdexcavationexchange_offer.pid=###STORAGE_PID### ORDER BY tx_vdexcavationexchange_offer.uid',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'label2' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.label2',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'fe_cruser_id' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.fe_cruser_id',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'catmatterrial' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.catmatterrial',		
			'config' => array (
				'type' => 'radio',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.catmatterrial.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.catmatterrial.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.catmatterrial.I.2', '2'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.catmatterrial.I.3', '3'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.catmatterrial.I.4', '4'),
				),
			)
		),
		'designiationuscsmeuble' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationuscsmeuble',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationuscsmeuble.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationuscsmeuble.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationuscsmeuble.I.2', '2'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationuscsmeuble.I.3', '3'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationuscsmeuble.I.4', '4'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationuscsmeuble.I.5', '5'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationuscsmeuble.I.6', '6'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationuscsmeuble.I.7', '7'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationuscsmeuble.I.8', '8'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationuscsmeuble.I.9', '9'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationuscsmeuble.I.10', '10'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationuscsmeuble.I.11', '11'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'designiationsimplemeuble' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeuble',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeuble.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeuble.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeuble.I.2', '2'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeuble.I.3', '3'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeuble.I.4', '4'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeuble.I.5', '5'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'designiationsimplemeubleha' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeubleha',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeubleha.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeubleha.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeubleha.I.2', '2'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeubleha.I.3', '3'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeubleha.I.4', '4'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'designiationsimplemeublehb' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeublehb',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeublehb.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeublehb.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeublehb.I.2', '2'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeublehb.I.3', '3'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationsimplemeublehb.I.4', '4'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'designiationroche' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationroche',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationroche.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationroche.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationroche.I.2', '2'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationroche.I.3', '3'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationroche.I.4', '4'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'designiationrocheautre' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.designiationrocheautre',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',
			)
		),
		'remarques' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.remarques',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'volume' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.volume',		
			'config' => array (
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array (
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
		'prix' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.prix',		
			'config' => array (
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array (
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
		'payereprise' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.payereprise',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.payereprise.I.0', '0'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.payereprise.I.1', '1'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.payereprise.I.2', '2'),
					array('LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.payereprise.I.3', '3'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'archive' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.archive',		
			'config' => array (
				'type' => 'check',
			)
		),
		'sursitefranco' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:vd_excavationexchange/locallang_db.xml:tx_vdexcavationexchange_offerlot.sursitefranco',		
			'config' => array (
				'type' => 'check',
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'hidden;;1;;1-1-1, offer_id, label2, fe_cruser_id, catmatterrial, designiationuscsmeuble, designiationsimplemeuble, designiationsimplemeubleha, designiationsimplemeublehb, designiationroche, designiationrocheautre, remarques, volume, prix, payereprise, archive, sursitefranco')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);
?>