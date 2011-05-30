<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

// print id and name for the element
function p_idName($table, $name){
	$table = secXSS($table);
	$name = secXSS($name);
	$txName = 'tx_vdexcavationexchange_pi1['.$table.']';
	$res = 'id="'.$txName . $name.'" name="'.$txName . $name.'"';
	echo $res;
}
// print id and name for the element
function p_idNameRadio($table, $name, $id){
	$table = secXSS($table);
	$name = secXSS($name);
	$id = intval($id);
	$txName = 'tx_vdexcavationexchange_pi1['.$table.']';
	$res = 'id="'.$txName . $id.'" name="'.$txName . $name.'"';
	echo $res;
}
// print for for label
function p_labelFor($table, $name){
	$table = secXSS($table);
	$name = secXSS($name);
	$txName = 'tx_vdexcavationexchange_pi1['.$table.']'.$name;
	$res = 'for="'.$txName.'"';
	echo $res;
}
// print checked value
function p_checked($fieldName, $data){
	// value from piVars or from the record
	$val = $data[$fieldName];
	$res = $val?' checked="true" ':'  ';
	echo $res;
}
// print checked value radio
function p_checkedRadio($fieldName, $data, $test){
	// value from piVars or from the record
	$val = $data[$fieldName];
	if($val<>''){
		$res = ($val == $test)?' checked="true" ':'  ';
	}elseif($test == 0){
		// 0 is default
		$res = ' checked="true" ';
	}
	echo $res;
}
// print input value
function p_inputValue($fieldName, $data){
	$val = secXSS($data[$fieldName]);
	$res = $val<>''?' value="'.$val.'" ':' ';
	echo $res;
}
// print input value
function p_options($options, $select){
	foreach($options as $k=>$v){
		$selected = ($select==$k)?'selected=1' :'';
		$label = 'label';
		$res .= '<option value="'. secXSS($k) .'" '. $selected .' >'.secXSS($v).'</option>';
	}
	echo $res;
}
// print validation error
function p_validation($fieldName, $validation){
	$msg = $validation[$fieldName];
	// security own message
	echo secXSS($msg);
}

// print errors
function print_errors($cmd){
	switch ($cmd) {
		case 'new':
		case 'read':
		case 'cancel':
		case 'update':
			break;
		case 'canNotRead':
			echo( "Vous n'êtes pas autorisé à afficher cette page. ");
			break;
		case 'canNotCreate':
			echo( "Vous n'êtes pas autorisé à créer un nouvel enregistrement. ");
			break;

		case 'noRecords':
			echo( "Aucun enregistrement trouvé.");
			break;
		case 'updateSuccess':
		case 'saveSuccess':
			echo( "Les données ont été sauvées."
			);
			break;
		case 'updateNotValid':
		case 'saveNotValid':
			echo( "Les données n'ont pas été sauvées. Complétez le formulaire selon les indications puis enregistrez le à nouveau. "
			);
			break;
		case 'archiveSuccess':
			echo( "Le lot a été archivé. ");
			break;
		case 'archiveFailed':
			echo( "Le lot n' a pas été archivé.");
			break;
		case 'activationSuccess':
			echo( "Le lot a été activé. ");
			break;
		case 'activationFailed':
			echo( "Le lot n' a pas été activé.");
			break;
		case 'nsubmitError':
			echo( "Modifications non enregistrées. Vous avez déjà envoyé le formulaire ou cliqué sur Envoyer");
			break;
		case 'readError':
			echo( "Désolé mais vous n'êtes pas autorisé à voir cette page");
			break;
		case 'noContent':
			echo( "La liste est vide. Aucun enregistrement à afficher.");
			break;
		case 'saveFailed':
			echo( "L'enregistrement n'a pas été sauvé");
			break;		
			
		case 'deleteFailed':
			echo( "Un problème est survenu lors de la suppression de l'enregistrement.");
			break;
		case 'deleteSuccess':
			echo( "L'enregistrement à été effacé.");
			break;
		default:
			echo( "Une cas non identifié s'est produit sur la page : ".secXSS($cmd));
	}
}

function echoXSS($str){
	echo secXSS($str);
}
function echoEmail($str){
	echo secXSS($str);
}

function secXSS($data, $charset = 'UTF-8', $quote_style = ENT_QUOTES, $all_entities = false, $double_encode = true)
{
	if(version_compare(PHP_VERSION, '5.2.3', '>='))
		if($all_entities) $data = htmlentities($data, $quote_style, $charset, $double_encode);
		else $data = htmlspecialchars($data, $quote_style, $charset, $double_encode);
	else
		if($all_entities) $data = htmlentities($data, $quote_style, $charset);
		else $data = htmlspecialchars($data, $quote_style, $charset);

	# Ok it should have been done on the inputs already
	# but lets be paranoid for just some milliseconds more just in case
	return removeInvalidChars($data, $charset = 'UTF-8', TRUE);
}

function removeInvalidChars($data, $charset = 'UTF-8', $entities = FALSE)
{
	# since some variable-length charset are vulnerable to an exploit
	# that ditch the next quote, dont trust the input and reencode the strings
	# reencoding the string with the same encoding will remove all malformed chars
	# supports all php charsets http://www.php.net/manual/en/function.htmlspecialchars.php as of 2008-06-01
	# cf http://ha.ckers.org/weird/variable-width-encoding.cgi for the exploit methodology

	switch($charset)
	{
		case 'UTF-8':
		case 'BIG5':
		case '950':
		case 'GB2312':
		case '936':
		case 'BIG5-HKSCS':
		case 'Shift_JIS':
		case 'SJIS':
		case '932':
		case 'EUC-JP':
		case 'EUCJP':
			return mb_convert_encoding( $data, $charset, $charset );

		case 'ISO-8859-1':
		case 'ISO8859-1':
		case 'ISO-8859-15':
		case 'ISO8859-15':
		case 'cp866':
		case 'ibm866':
		case '866':
		case 'cp1251':
		case 'Windows-1251':
		case 'win-1251':
		case '1251':
		case 'cp1252':
		case 'Windows-1252':
		case '1252':
		case 'KOI8-R':
		case 'KOI8-ru':
		case 'koi8r':
			return $data;

		default:
			if($entities) trigger_error('WRONG CHARSET USED, not supported by php, see http://www.php.net/manual/en/function.htmlspecialchars.php', E_USER_ERROR);
			return $data;
	}
}
?>