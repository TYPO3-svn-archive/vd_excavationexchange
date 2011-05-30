#
# Table structure for table 'tx_vdexcavationexchange_offer'
#
CREATE TABLE tx_vdexcavationexchange_offer (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	rolemaitreouvrage tinyint(3) DEFAULT '0' NOT NULL,
	rolearchitecte tinyint(3) DEFAULT '0' NOT NULL,
	roleingenieur tinyint(3) DEFAULT '0' NOT NULL,
	roleentrepreneur tinyint(3) DEFAULT '0' NOT NULL,
	roleautre tinyint(3) DEFAULT '0' NOT NULL,
	fe_cruser_id text,
	contactname varchar(255) DEFAULT '' NOT NULL,
	contactfunction varchar(255) DEFAULT '' NOT NULL,
	commune varchar(255) DEFAULT '' NOT NULL,
	district int(11) DEFAULT '0' NOT NULL,
	address varchar(255) DEFAULT '' NOT NULL,
	ouvragetype varchar(255) DEFAULT '' NOT NULL,
	permisconstdepose int(11) DEFAULT '0' NOT NULL,
	permisconstobtenu int(11) DEFAULT '0' NOT NULL,
	startdatework int(11) DEFAULT '0' NOT NULL,
	enddatework int(11) DEFAULT '0' NOT NULL,
	destcomblementsiteextraction tinyint(3) DEFAULT '0' NOT NULL,
	destdecharge tinyint(3) DEFAULT '0' NOT NULL,
	destautre tinyint(3) DEFAULT '0' NOT NULL,
	destautretxt varchar(255) DEFAULT '' NOT NULL,
	destkm int(11) DEFAULT '0' NOT NULL,
	siteindicepollution int(11) DEFAULT '0' NOT NULL,
	sitepollue int(11) DEFAULT '0' NOT NULL,
	siteindustrielle int(11) DEFAULT '0' NOT NULL,
	sitedechetsdemolition int(11) DEFAULT '0' NOT NULL,
	sitehydrocarbure int(11) DEFAULT '0' NOT NULL,
	sitedechets int(11) DEFAULT '0' NOT NULL,
	siteaccident int(11) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_vdexcavationexchange_search'
#
CREATE TABLE tx_vdexcavationexchange_search (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	fe_cruser_id text,
	volume int(11) DEFAULT '0' NOT NULL,
	deliverydate int(11) DEFAULT '0' NOT NULL,
	comment text,
	archive tinyint(3) DEFAULT '0' NOT NULL,
	catmatterrial int(11) DEFAULT '0' NOT NULL,
	designiationuscsmeuble int(11) DEFAULT '0' NOT NULL,
	designiationsimplemeuble int(11) DEFAULT '0' NOT NULL,
	designiationsimplemeubleha int(11) DEFAULT '0' NOT NULL,
	designiationsimplemeublehb int(11) DEFAULT '0' NOT NULL,
	designiationroche int(11) DEFAULT '0' NOT NULL,
	designiationrocheautre tinytext,
	commune varchar(255) DEFAULT '' NOT NULL,
	address varchar(255) DEFAULT '' NOT NULL,
	district int(11) DEFAULT '0' NOT NULL,
	materialuse text,
	startdatework int(11) DEFAULT '0' NOT NULL,
	enddatework int(11) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_vdexcavationexchange_response'
#
CREATE TABLE tx_vdexcavationexchange_response (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	lot_id text,
	search_id text,
	fe_cruser_id text,
	firstname varchar(255) DEFAULT '' NOT NULL,
	lastname varchar(255) DEFAULT '' NOT NULL,
	company varchar(255) DEFAULT '' NOT NULL,
	address varchar(255) DEFAULT '' NOT NULL,
	location varchar(255) DEFAULT '' NOT NULL,
	phone varchar(255) DEFAULT '' NOT NULL,
	fax varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	rolemaitreouvrage tinyint(3) DEFAULT '0' NOT NULL,
	rolearchitecte tinyint(3) DEFAULT '0' NOT NULL,
	roleingenieur tinyint(3) DEFAULT '0' NOT NULL,
	roleentrepreneur tinyint(3) DEFAULT '0' NOT NULL,
	roleautre tinyint(3) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_vdexcavationexchange_offerlot'
#
CREATE TABLE tx_vdexcavationexchange_offerlot (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	offer_id int(11) DEFAULT '0' NOT NULL,
	label2 varchar(255) DEFAULT '' NOT NULL,
	fe_cruser_id varchar(255) DEFAULT '' NOT NULL,
	catmatterrial int(11) DEFAULT '0' NOT NULL,
	designiationuscsmeuble int(11) DEFAULT '0' NOT NULL,
	designiationsimplemeuble int(11) DEFAULT '0' NOT NULL,
	designiationsimplemeubleha int(11) DEFAULT '0' NOT NULL,
	designiationsimplemeublehb int(11) DEFAULT '0' NOT NULL,
	designiationroche int(11) DEFAULT '0' NOT NULL,
	designiationrocheautre tinytext,
	remarques text,
	volume int(11) DEFAULT '0' NOT NULL,
	prix int(11) DEFAULT '0' NOT NULL,
	payereprise int(11) DEFAULT '0' NOT NULL,
	archive tinyint(3) DEFAULT '0' NOT NULL,
	sursitefranco tinyint(3) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);