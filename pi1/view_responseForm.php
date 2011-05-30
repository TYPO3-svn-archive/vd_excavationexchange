<?php if (!defined ('TYPO3_MODE')) 	die ('Access denied.'); ?>

<?php 
/** $data: the data to display
	* $form_action
	*/
	include_once('html_helpers_lib.php');

	// ********************************************
	// shortcuts
	// ********************************************
	$piVars = $this->caller->piVars['resp'];
	$txName = 'tx_vdexcavationexchange_pi1[resp]';
	$formName = 'tx_vdexcavationexchange_pi1_resp';
	$act = $form_action;
	$data = $this->data;
	$valids  = $this->validation;
	
	function print_submit($action, $cmd){
		$txName = 'tx_vdexcavationexchange_pi1[resp]';
		if($action == 'create'){
			if($cmd == 'new'){
				$str = 'name="'.$txName.'[submit][save]"  value="Envoyer" title="Envoyer"';
			}elseif($cmd == 'saveNotValid'){
				$str = 'name="'.$txName.'[submit][save]"  value="Envoyer" title="Envoyer"';
			}elseif($cmd == 'saveFailed'){
				$str = 'name="'.$txName.'[submit][save]"  value="Envoyer" title="Envoyer"';
			}elseif($cmd == 'saveSuccess'){
				$str = 'name="'.$txName.'[submit][update]"  value="Envoyer" title="Envoyer" DISABLED ';
			}elseif($cmd == 'save'){
				$str = 'name="'.$txName.'[submit][save]"  value="Envoyer" title="Envoyer"';
			}elseif($cmd == 'cancel'){
				$str = 'name="'.$txName.'[submit][save]"  value="Envoyer" title="Envoyer"';
			}else{
				$str = 'name="'.$txName.'[submit][save]"  value="Envoyer" title="Envoyer"';
			}
		}
		if($action == 'update'){
			if($cmd == 'update'){
				$str = 'name="'.$txName.'[submit][save]"  value="Modifier" title="Modifier"';
			}elseif($cmd == 'updateForbidden'){
				$str = 'name="'.$txName.'[submit][nosave]"  value="Inactif" title="Envoyer"';
			}elseif($cmd == 'updateSuccess'){
				$str = 'name="'.$txName.'[submit][save]"  value="Modifier" title="Modifier"';
			}else{
				$str = 'name="'.$txName.'[submit][save]"  value="Envoyer" title="Envoyer"';
			}
		}    
		echo $str;
	}
	function print_cancel($action, $cmd){
		$txName = 'tx_vdexcavationexchange_pi1[resp]';
		if($cmd == 'saveSuccess'){
			$str = 'name="'.$txName.'[submit][cancel]"  value="Annuler" title="Annuler" DISABLED';
		}else{
			$str = 'name="'.$txName.'[submit][cancel]"  value="Annuler" title="Annuler"';
		}

		echo $str;
	}  

	
?>
	
<div class='warnings'>  
<?php 
	// print warnings and errors
	print_errors($this->cmd);
?>
</div>
<div class='responseform'>
	&nbsp;

	<?php 
	if($this->responseType=='lot'){ 
		$offerID = $this->foreignData[$this->type_id]['offer_id'];
	?>
		<div class='descriptionlot'>
			<h3><strong>Lot: <?php echo $this->foreignData[$this->type_id]['uid']?></strong></h3>
			<label class="label">Type de matériel: </label><div class="value"><?php echoXSS($this->foreignData[$this->type_id]['catmatterrial_label'])?></div>
			<label class="label">Description du matériel: </label><div class="value">
					<?php echoXSS($this->foreignData[$this->type_id]['infoMat_label'])?> 		
			</div>
			
			<label class="label">Volume en place [m3]: </label><div class="value"><?php echo $this->foreignData[$this->type_id]['volume']?></div>
			<label class="label">Lieu de production (excavation): </label><div class="value"><?php echoXSS($this->offerData[$offerID]['district_label']); ?></div>
			<label class="label">Période de disponibilité: </label><div class="value">du <?php echo $this->offerData[$offerID]['startdatework']?> au <?php echo $this->offerData[$offerID]['enddatework']?></div>      
			<label class="label">Remarques et aptitudes à la réutilisation:  </label><div class="value"><?php echoXSS($this->foreignData[$this->type_id]['remarques'])?></div>

			<div class="ligne">
				<label class="label">Fonction(s): </label>
				<div class="value">
					<?php 
					// display rôles
					echoXSS($this->offerData[$offerID]['roles_label']);
					?>
				</div>
			</div>          
		</div>
	<?php 
	}elseif($this->responseType=='search'){ ?>
		<div class='descriptionlot'>
			<h3><strong>Lot: <?php echo $this->foreignData[$this->type_id]['uid']?></strong></h3>
			
			<label class="label">Type de matériel: </label><div class="value"><?php echoXSS($this->foreignData[$this->type_id]['catmatterrial_label'])?></div>
			<label class="label">Description du matériel: </label><div class="value">
					<?php echoXSS($this->foreignData[$this->type_id]['infoMat_label'])?> 		
			</div>			
			<label class="label">Utilisation: </label><div class="value"><?php echoXSS($this->foreignData[$this->type_id]['materialuse'])?></div>
			<label class="label">Remarques:  </label><div class="value"><?php echoXSS($this->foreignData[$this->type_id]['comment'])?></div>			
			<label class="label">Volume en place [m3]: </label><div class="value"><?php echo $this->foreignData[$this->type_id]['volume']?></div>
			<label class="label">Lieu d'utilisation:  </label><div class="value"><?php echoXSS($this->foreignData[$this->type_id]['district_label'])?></div>
			<label class="label">Période de livraison souhaitée: </label><div class="value">du <?php echo $this->foreignData[$this->type_id]['startdatework']?> au <?php echo $this->foreignData[$this->type_id]['enddatework']?></div>      
			
		</div>

	<?php 
	} ?>  
		
	<form name="<?php echo $formName; ?>" action="<?php echo $this->formAction; ?>" method="post">
		<fieldset class="cadre">
			<legend>Vos coordonnées</legend>   
			<div class="ligne">
				<label class="label" <?php p_labelFor('resp','[firstname]') ?> >Prénom :</label>
				<div class="value"><input type=text SIZE=50 <?php p_idName('resp','[firstname]'); p_inputValue('firstname',$data); ?> /></div>
			</div>
			<div  class ="error"> <?php p_validation('firstname',$valids); ?></div>
			<div class="ligne">
				<label class="label" <?php p_labelFor('resp','[lastname]') ?> >Nom :</label>
				<div class="value"><input type=text SIZE=50 <?php p_idName('resp','[lastname]'); p_inputValue('lastname',$data); ?> /></div>
			</div>
			<div  class ="error"> <?php p_validation('lastname',$valids); ?></div>
			<div class="ligne">
				<label class="label" <?php p_labelFor('resp','[company]') ?> >Société <span class="redstar">*</span> :</label>
				<div class="value"><input type=text SIZE=50 <?php p_idName('resp','[company]'); p_inputValue('company',$data); ?> />
					<div  class ="error"> <?php p_validation('company',$valids); ?></div>
				</div>
				
			</div>
			
			<div class="ligne">
				<label class="label" <?php p_labelFor('resp','[address]') ?> >Adresse :</label>
				<div class="value"><input type=text SIZE=50 <?php p_idName('resp','[address]'); p_inputValue('address',$data); ?> /></div>
			</div>
			<div  class ="error"> <?php p_validation('address',$valids); ?></div>
			<div class="ligne">
				<label class="label" <?php p_labelFor('resp','[location]') ?> >NPA et localité :</label>
				<div class="value"><input type=text SIZE=50 <?php p_idName('resp','[location]'); p_inputValue('location',$data); ?> /></div>
			</div>
			<div  class ="error"> <?php p_validation('location',$valids); ?></div>
			<div class="ligne">
				<label class="label" <?php p_labelFor('resp','[location]') ?> >Fonction:</label>
				<div class="value">
						<input type=checkbox <?php p_idName('resp','[rolemaitreouvrage]'); p_checked('rolemaitreouvrage',$data); ?> value=1 /> 
						<label <?php p_labelFor('resp','[rolemaitreouvrage]') ?> >Maître d'ouvrage</label>
						<input type=checkbox <?php p_idName('resp','[rolearchitecte]'); p_checked('rolearchitecte',$data); ?> value=1 />
						<label <?php p_labelFor('resp','[rolearchitecte]') ?> >Architecte</label>
						<input type=checkbox <?php p_idName('resp','[roleingenieur]'); p_checked('roleingenieur',$data); ?> value=1 />
						<label <?php p_labelFor('resp','[roleingenieur]') ?> >Ingénieur</label>
						<input type=checkbox <?php p_idName('resp','[roleentrepreneur]'); p_checked('roleentrepreneur',$data); ?> value=1 /> 
						<label <?php p_labelFor('resp','[roleentrepreneur]') ?> >Entrepreneur</label>
						<div>
							<input type=checkbox <?php p_idName('resp','[roleautre]'); p_checked('roleautre',$data); ?> value=1 />
							<label <?php p_labelFor('resp','[roleautre]') ?>>Autre</label>
						</div>
				</div>        
			</div> 
			<div  class ="error"> <?php p_validation('roles',$valids); ?></div>

			<div class="ligne">
				<label class="label" <?php p_labelFor('resp','[email]') ?> >E-mail <span class="redstar">*</span> :</label>
				<div class="value"><input type=text SIZE=50 <?php p_idName('resp','[email]'); p_inputValue('email',$data); ?> />
					<div class ="error"> <?php p_validation('email',$valids); ?></div>   
				</div>
			</div>
			
			<div class="ligne">
				<label class="label" <?php p_labelFor('resp','[phone]') ?> >Téléphone :</label>
				<div class="value"><input type=text SIZE=30 <?php p_idName('resp','[phone]'); p_inputValue('phone',$data); ?> /></div>
			</div>
			<div  class ="error"> <?php p_validation('phone',$valids); ?></div>
			<div class="ligne">
				<label class="label" <?php p_labelFor('resp','[fax]') ?> >Téléfax :</label>
				<div class="value"><input type=text SIZE=30 <?php p_idName('resp','[fax]'); p_inputValue('fax',$data); ?> /></div>
			</div>
			<div  class ="error"> <?php p_validation('fax',$valids); ?></div>
		</fieldset>
		<input type="hidden" name="tx_vdexcavationexchange_pi1[action]" value="<?php echo $this->action?>" />
		<input type="hidden" name="tx_vdexcavationexchange_pi1[resp][rec_id]" value="<?php echo $this->rec_id?>" />
		<input type="hidden" name="tx_vdexcavationexchange_pi1[resp][type]" value="<?php echo $this->responseType?>" />
		<input type="hidden" name="tx_vdexcavationexchange_pi1[resp][type_id]" value="<?php echo $this->type_id?>" />
		<input type="hidden" name="tx_vdexcavationexchange_pi1[formsession][key]" value="<?php echo $this->formSession['key']?>" />
		
		<br/>
		<?php
		if($this->cmd=='saveSuccess'){
			// do not dispaly captcha
			// redirect
			if($this->responseType=='lot'){
				$red = 'offresListPage';
			}elseif($this->responseType=='search'){
				$red = 'searchListPage';
			}
			if($red){
				// Redirect to the page "Mon dossier"
				$conf = array('parameter' =>  $this->conf['pagesRedirect.'][$red],'useCashHash' => true,'returnLast' => 'url',);							
				$url = $this->cObj->typoLink('', $conf);			
				$redirectTo = t3lib_div::getIndpEnv('TYPO3_SITE_URL').$url;
				echo('<br/><br/><br/><div class="actionLink" align="center"><a href="'.$redirectTo.'">Continuer<a></div>');
			}
		}else{  
		?>  
		<!--###CAPTCHA_INSERT### this subpart is removed if CAPTCHA is not enabled! -->
		<div class="tx-your-extension-id-pi1-captcha">
			<label for="tx_vdexcavationexchange_pi1[captcha_response]"><?php echo ($this->captchArray['###SR_FREECAP_NOTICE###']);?></label>
			
			<?php echo ($this->captchArray['###SR_FREECAP_CANT_READ###']);?>
			<br />
			
			<?php if($this->captchaError === true){
				echo('<div  class ="error">Le captcha n\'est pas valide </div>');
			}?>
			<input type="text" size="15" id="tx_vdexcavationexchange_pi1[captcha_response]" name="tx_vdexcavationexchange_pi1[captcha_response]" title="<?php echo ($this->captchArray['###SR_FREECAP_NOTICE###']);?>" value="" />
			<?php echo ($this->captchArray['###SR_FREECAP_IMAGE###']);?>
			<?php echo ($this->captchArray['###SR_FREECAP_ACCESSIBLE###']);?>
		</div>
		<!--###CAPTCHA_INSERT###-->
		
		<div class="submit-bloc">
			<input class="submit" type="submit" <?php print_submit($this->action, $this->cmd) ?> />
			<input class="submit" type="submit" <?php print_cancel($this->action, $this->cmd) ?> />
		</div>    
		
		<?php 
		}?>
	
	</form>
</div>
