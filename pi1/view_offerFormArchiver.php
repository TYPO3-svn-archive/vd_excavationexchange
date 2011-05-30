<?php if (!defined ('TYPO3_MODE')) 	die ('Access denied.'); ?>

<?php 
/** $data: the data to display
  * $form_action
  */
  include_once('html_helpers_lib.php');
  

  function print_cancel($action, $cmd){
    $txName = 'tx_vdexcavationexchange_pi1[offer]';
    $str = 'name="'.$txName.'[submit][cancel]"  value="Annuler" title="Annuler"';
    echo $str;
  }  

?>

  
<div class='warnings'>  
<?php 
  // print warnings and errors
  print_errors($this->cmd);
?>
</div>



<?php 
  // redirect
  if($this->caller->piVars['redirect']){ 
  }else{	
    $this->caller->piVars['redirect'] = 'myAds';    
  }

  if($red = $this->caller->piVars['redirect']){
    // Redirect to the page "Mon dossier"
		$conf = array('parameter' =>  $this->conf['pagesRedirect.'][$red],'useCashHash' => true,'returnLast' => 'url',);							
		$url = $this->cObj->typoLink('', $conf);			
    $redirectTo = t3lib_div::getIndpEnv('TYPO3_SITE_URL').$url;
    echo('<br/><br/><br/><div class="actionLink" align="center"><a href="'.$redirectTo.'">Retour</a></div>');
  }
?>

