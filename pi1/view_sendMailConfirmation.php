<?php if (!defined ('TYPO3_MODE')) 	die ('Access denied.'); ?>

<?php 
   /** $data: the data to display
	* $form_action
	*/
	include_once('html_helpers_lib.php');

	// ********************************************
	// shortcuts
	// ********************************************
	if($this->annonceType == 'offer'){
		$piVars = $this->caller->piVars['offer'];
		$txName = 'tx_vdexcavationexchange_pi1[offer]';
		$formName = 'tx_vdexcavationexchange_pi1_offer';
		$data = $this->data;
	}else{
		$piVars = $this->caller->piVars['search'];
		$txName = 'tx_vdexcavationexchange_pi1[search]';
		$formName = 'tx_vdexcavationexchange_pi1_search';
		$data = $this->data;

	}
	
	function print_submit($type, $cmd){
		$txName = ($type == 'offer') ? 'tx_vdexcavationexchange_pi1[offer]':'tx_vdexcavationexchange_pi1[search]';
		$str = 'name="'.$txName.'[submit][sendEmail]"  value="Envoyer le mail" title="Envoyer le email"';
		echo $str;
	}
	function print_cancel($type, $cmd){
		$txName = ($type == 'offer') ?'tx_vdexcavationexchange_pi1[offer]':'tx_vdexcavationexchange_pi1[search]';
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

<h3>Vous êtes sur le point d'envoyer un email avec les détails de l'annonce à <?php echo($params[email]) ?> </h3>

<code>
<?php // display the details of the email
echo ($this->recordDetails);
?>
</code>

<div class='responseform'>
	<form name="<?php echo $formName; ?>" action="<?php echo $this->formAction; ?>" method="post">
		<input type="hidden" name="tx_vdexcavationexchange_pi1[action]" value="<?php echo $this->action?>" />
		<input type="hidden" name="<?php echo $txName; ?>[rec_id]" value="<?php echo $this->rec_id?>" />
		<input type="hidden" name="<?php echo $txName; ?>[send_email]" value="<?php echo $this->lotID?>" />
		<input type="hidden" name="tx_vdexcavationexchange_pi1[resp]" value="<?php echoXSS($this->caller->piVars[resp])?>" />
		<div class="submit-bloc">
			<input class="submit" type="submit" <?php print_submit($this->annonceType, $this->cmd) ?> />
			<input class="submit" type="submit" <?php print_cancel($this->annonceType, $this->cmd) ?> />
		</div>    	
	</form>
</div>
