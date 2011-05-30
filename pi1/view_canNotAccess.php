<?php if (!defined ('TYPO3_MODE')) 	die ('Access denied.'); ?>

<?php 
/** $data: the data to display
  * $form_action
  */
  include_once('html_helpers_lib.php');
?>

<div class='warnings'>  
<?php 
  // print warnings and errors
  print_errors($this->cmd);
?>
</div>
<br/>
<div>Vous devez vous identifier ou créer un compte utilisateur avant de pouvoir ajouter une offre ou une demande.</div>
<br/>
<div><?php 
		$conf = array('parameter' =>  $this->conf['pagesRedirect.']['loginPage'],'useCashHash' => true);
		echo ($redirectTo = $this->cObj->typoLink('S\'identifier', $conf));
		?>
</div>
<br/>
<div><?php 
		$conf = array('parameter' =>  $this->conf['pagesRedirect.']['createAccount'],'useCashHash' => true);
		echo ($redirectTo = $this->cObj->typoLink('Créer un compte', $conf));?>
</div>