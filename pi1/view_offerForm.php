<?php if (!defined ('TYPO3_MODE')) 	die ('Access denied.'); ?>


<?php 
   /** $data: the data to display
	* $form_action
	*/
	include_once('html_helpers_lib.php');
	
	// ********************************************
	// shortcuts
	// ********************************************
	$piVars = $this->caller->piVars['offer'];
	$txName = 'tx_vdexcavationexchange_pi1[offer]';
	$formName = 'tx_vdexcavationexchange_pi1_offer';
	$act = $form_action;
	$data = $this->data;
	$valids  = $this->validation;
	function print_submit($action, $cmd){
		$txName = 'tx_vdexcavationexchange_pi1[offer]';
		if($action == 'create'){
			if($cmd == 'new'){
				$str = 'name="'.$txName.'[submit][save]"  value="Créer" title="Créer"';
			}elseif($cmd == 'saveNotValid'){
				$str = 'name="'.$txName.'[submit][save]"  value="Créer" title="Créer"';
			}elseif($cmd == 'saveFailed'){
				$str = 'name="'.$txName.'[submit][save]"  value="Créer" title="Créer"';
			}elseif($cmd == 'saveSuccess'){
				$str = 'name="'.$txName.'[submit][update]"  value="Enregistrer" title="Enregistrer"';
			}elseif($cmd == 'save'){
				$str = 'name="'.$txName.'[submit][save]"  value="Créer" title="Créer"';
			}elseif($cmd == 'cancel'){
				$str = 'name="'.$txName.'[submit][save]"  value="Créer" title="Créer"';
			}else{
				$str = 'name="'.$txName.'[submit][save]"  value="Créer" title="Créer"';
			}
		}
		if($action == 'update'){
			if($cmd == 'update'){
				$str = 'name="'.$txName.'[submit][save]"  value="Enregistrer" title="Enregistrer"';
			}elseif($cmd == 'updateForbidden'){
				$str = 'name="'.$txName.'[submit][nosave]"  value="Inactif" title="Enregistrer" disabled';
			}elseif($cmd == 'updateSuccess'){
				$str = 'name="'.$txName.'[submit][save]"  value="Enregistrer" title="Enregistrer"';
			}elseif($cmd == 'cancel'){
				$str = 'name="'.$txName.'[submit][save]"  value="Enregistrer" title="Enregistrer"';
			}elseif($cmd == 'saveAddLot'){
				$str = 'name="'.$txName.'[submit][saveAddLot]"  value="Enregistrer et ajouter un lot" title="Enregistere et ajouter un lot"';        
			}else{
				$str = 'name="'.$txName.'[submit][save]"  value="Enregistrer" title="Enregistrer"';
			}
		}    
		echo $str;
	}
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
if($this->cmd =='canNotCreate'){
	// do not display the form
}else{?>

	<form class='offerSearch' name="<?php echo formName; ?>" action="<?php echo$this->formAction; ?>" method="post"  >
		<input type="hidden" name="tx_vdexcavationexchange_pi1[action]" value="<?php echo $this->action?>" />
		<input type="hidden" name="tx_vdexcavationexchange_pi1[offer][rec_id]" value="<?php echo $this->rec_id?>">
		<input type="hidden" name="tx_vdexcavationexchange_pi1[offer][dateCompare]" value="1">
		<input type="hidden" name="tx_vdexcavationexchange_pi1[formsession][key]" value="<?php echo $this->formSession['key']?>" />
		<fieldset class="cadre">
			<legend>1. Annonceur</legend>
			<h3>1.1 Rôle dans le projet de construction<span class="redstar">*</span>:</h3>
			<div class="ligne">
				<input type=checkbox <?php p_idName('offer','[rolemaitreouvrage]'); p_checked('rolemaitreouvrage',$data); ?> value=1 > 
				<label <?php p_labelFor('offer','[rolemaitreouvrage]') ?> >Maître d'ouvrage</label>
				<input type=checkbox <?php p_idName('offer','[rolearchitecte]'); p_checked('rolearchitecte',$data); ?> value=1>
				<label <?php p_labelFor('offer','[rolearchitecte]') ?> >Architecte</label>
				<input type=checkbox <?php p_idName('offer','[roleingenieur]'); p_checked('roleingenieur',$data); ?> value=1>
				<label <?php p_labelFor('offer','[roleingenieur]') ?> >Ingénieur</label>
				<input type=checkbox <?php p_idName('offer','[roleentrepreneur]'); p_checked('roleentrepreneur',$data); ?> value=1> 
				<label <?php p_labelFor('offer','[roleentrepreneur]') ?> >Entrepreneur</label>     
			</div>  
			<div class="ligne">
				<input type=checkbox <?php p_idName('offer','[roleautre]'); p_checked('roleautre',$data); ?> value=1>
				<label <?php p_labelFor('offer','[roleautre]') ?>>Autre</label>
				<div class ="error"> <?php p_validation('roles',$valids); ?></div>
			</div>
	
			<div class="retour"></div>
			<h3>1.2 Personne de contact :</h3>
			<div class="ligne">
				<label class="label" <?php p_labelFor('offer','[contactname]') ?> >Nom, prénom<span class="redstar">*</span>:</label>
				<div class="field"><input type=text SIZE=50 <?php p_idName('offer','[contactname]'); p_inputValue('contactname',$data); ?> /></div>
				<div class ="error"> <?php p_validation('contactname',$valids); ?></div>
			</div>
			<div class="ligne">
				<label class="label" <?php p_labelFor('offer','[contactfunction]') ?> >Société<span class="redstar">*</span>:</label>
				<div class="field"><input type=text SIZE=50 <?php p_idName('offer','[contactfunction]'); p_inputValue('contactfunction',$data); ?> /></div>
				<div  class ="error"> <?php p_validation('contactfunction',$valids); ?></div>
			</div>
		</fieldset>

		<fieldset class="cadre">
			<legend>2. Données sur le projet de construction</legend>
			<h3>2.1 Localisation de l'ouvrage projeté :</h3>
			<div class="ligne">
				<label class="label" <?php p_labelFor('offer','[commune]') ?> >Commune :</label>
				<div class="field"><input type=text SIZE=50 <?php p_idName('offer','[commune]'); p_inputValue('commune',$data); ?> /></div>
			</div>

			<div class="ligne">
				<label class="label" <?php p_labelFor('offer','[district]') ?> >Région<span class="redstar">*</span>:</label>
				<div class="field">
					<select <?php p_idName('offer','[district]'); ?>  style="width: 200px" >
						<?php
							p_options($this->fieldsItemsOffer['district'],$data['district']);
						?>
					</select>
				</div>
				<div  class ="error"> <?php p_validation('district',$valids); ?></div>
			</div>	
			<div class="ligne">
				<label class="label" <?php p_labelFor('offer','[address]') ?> >Lieu-dit / Adresse :</label>
				<div class="field"><input type=text SIZE=50 <?php p_idName('offer','[address]'); p_inputValue('address',$data); ?> /></div>
			</div>
			<div class="retour"></div>
			<h3>2.2 Type d'ouvrage :</h3>
			<div class="ligne">
				<label class="label" <?php p_labelFor('offer','[ouvragetype]') ?> >Type d'ouvrage :</label>
				<div class="field"><input type=text SIZE=50 <?php p_idName('offer','[ouvragetype]'); p_inputValue('ouvragetype',$data); ?> /></div>
			</div>
			<div class="retour"></div>
			<h3>2.3 Stade de la procédure :</h3>
				<div class="ligne">
					<div class="column1">Avez-vous déposé une demande de permis de construire ?</div>
					<div class="column3">
						<input type="radio" <?php p_idNameRadio('offer','[permisconstdepose]','[permisconstdepose][0]'); p_checkedRadio('permisconstdepose',$data,1); ?> value=1 />
						<label <?php p_labelFor('offer','[permisconstdepose][0]') ?> >Oui</label>
						<input type="radio" <?php p_idNameRadio('offer','[permisconstdepose]','[permisconstdepose][1]'); p_checkedRadio('permisconstdepose',$data,0); ?> value=0 />
						<label <?php p_labelFor('offer','[permisconstdepose][1]') ?> >Non</label>
					</div>
				</div>
				<div class="ligne">
					<div class="column1">Si oui, avez-vous déjà obtenu le permis de construire ?</div>
					<div class="column3">
						<input type="radio" <?php p_idNameRadio('offer','[permisconstobtenu]','[permisconstobtenu][0]'); p_checkedRadio('permisconstobtenu',$data,1); ?> value=1 />
						<label <?php p_labelFor('offer','[permisconstobtenu][0]') ?> >Oui</label>
						<input type="radio" <?php p_idNameRadio('offer','[permisconstobtenu]','[permisconstobtenu][1]'); p_checkedRadio('permisconstobtenu',$data,0); ?> value=0 />
						<label <?php p_labelFor('offer','[permisconstobtenu][1]') ?> >Non</label>
					</div>
				</div> 
			<div class="retour"></div>
			
			<?php
			// calendar add javascript
			tx_rlmpdateselectlib::includeLib();
			$dateSelectorConf = Array (
					'calConf.' => Array (
							'dateTimeFormat' => 'dd.mm.yy',
							'stylesheet' => $GLOBALS['TYPO3_LOADED_EXT'][$this->extKey]['siteRelPath'].'/res/calendar-system.css',
							'inputFieldDateTimeFormat' => '%d.%m.%Y'
					)
			); 
			?>
			
			<h3>2.4 Planning :</h3>
			<div class="ligne">
				<div class="column1" <?php p_labelFor('offer','[startdatework]') ?> >Date prévue de début des travaux<span class="redstar">*</span>:</div>
				<div  class="column3"><input type=text SIZE=12 <?php p_idName('offer','[startdatework]'); p_inputValue('startdatework',$data); ?> style="text-align: right;" /></div>
				<span class = "calendarBt" >&nbsp;<?php echo (tx_rlmpdateselectlib::getInputButton ('tx_vdexcavationexchange_pi1[offer][startdatework]',$dateSelectorConf) ); ?></span>
				<span class ="error"><?php p_validation('startdatework',$valids); ?></span>
			</div>
			
			<div class="ligne">
				<div class="column1" <?php p_labelFor('offer','[enddatework]') ?> >Date prévue de fin des travaux<span class="redstar">*</span>:</div>
				<div  class="column3"><input type=text SIZE=12 <?php p_idName('offer','[enddatework]'); p_inputValue('enddatework',$data); ?> style="text-align: right;" /></div>
				<span class = "calendarBt" >&nbsp;<?php echo (tx_rlmpdateselectlib::getInputButton ('tx_vdexcavationexchange_pi1[offer][enddatework]',$dateSelectorConf) );?></span> 
				<div  class ="error"> <?php p_validation('enddatework',$valids); ?></div>
			</div>
			<div class="ligne">
				<div  class ="error"> <?php p_validation('dateCompare',$valids); ?></div>
			</div>
			<div class="retour"></div>
			
			<?php /*
			<h3>2.5 Destination des matériaux d'excavation et distance de transport prévues faute de preneur intéressé :</h3>
			<div class="ligne">
			Destination prévue :
				<input type=checkbox <?php p_idName('offer','[destcomblementsiteextraction]'); p_checked('destcomblementsiteextraction',$data); ?> value=1 > 
				<label <?php p_labelFor('offer','[destcomblementsiteextraction]') ?> >Comblement de site d'extraction.</label>
				<input type=checkbox <?php p_idName('offer','[destdecharge]'); p_checked('destdecharge',$data); ?> value=1 > 
				<label <?php p_labelFor('offer','[destdecharge]') ?> >Mise en décharge.</label>
				<input type=checkbox <?php p_idName('offer','[destautre]'); p_checked('destautre',$data); ?> value=1 > 
				<label <?php p_labelFor('offer','[destautre]') ?> >Autre.</label>
				<div class="label" <?php p_labelFor('offer','[destautretxt]') ?> >Si autre, destination :</div>
				<div class="field"><input type=text SIZE=50 <?php p_idName('offer','[destautretxt]'); p_inputValue('destautretxt',$data); ?> ></div>
			</div>
			<div class="ligne">
				<div class="label" <?php p_labelFor('offer','[destkm]') ?> >Distance de transport estimée [Km] :</div>
				<div class="field"><input type=text SIZE=50 <?php p_idName('offer','[destkm]'); p_inputValue('destkm',$data); ?> ></div>
			</div>    
			*/ ?>
				
		</fieldset> 
		
		<fieldset class="cadre">
			<legend>3. Données connues sur la provenance des matériaux</legend>
			<div class="retour"></div>
			<div class="ligne">
				<div class="column1">Le sol du site présente-t-il des indices de pollution ?</div>
				<div class="column3">
					<input type="radio" <?php p_idNameRadio('offer','[siteindicepollution]','[siteindicepollution][0]'); p_checkedRadio('siteindicepollution',$data,1); ?> value=1 />
					<label <?php p_labelFor('offer','[siteindicepollution][0]') ?> >Oui</label>
					<input type="radio" <?php p_idNameRadio('offer','[siteindicepollution]','[siteindicepollution][1]'); p_checkedRadio('siteindicepollution',$data,0); ?> value=0 />
					<label <?php p_labelFor('offer','[siteindicepollution][1]') ?> >Non</label>
					
				</div>
			</div>
			<?php /*
			<div class="ligne">
				<div class="column1">Le site est-il reconnu par les autorités cantonales comme pollué, ou est-il soupçonné de l'être ?</div>
				<div class="column3">
					<input type="radio" <?php p_idNameRadio('offer','[sitepollue]','[sitepollue][0]'); p_checkedRadio('sitepollue',$data,1); ?> value=1 />
					<label <?php p_labelFor('offer','[sitepollue][0]') ?> >Oui</label>
					<input type="radio" <?php p_idNameRadio('offer','[sitepollue]','[sitepollue][1]'); p_checkedRadio('sitepollue',$data,0); ?> value=0 />
					<label <?php p_labelFor('offer','[sitepollue][1]') ?> >Non</label>
				</div>  
			</div>
			*/ ?>
			<div class="ligne">
				<div class="column1">Des activités industrielles se sont-elles déroulées sur le site ?</div>
				<div class="column3">
					<input type="radio" <?php p_idNameRadio('offer','[siteindustrielle]','[siteindustrielle][0]'); p_checkedRadio('siteindustrielle',$data,1); ?> value=1 />
					<label <?php p_labelFor('offer','[siteindustrielle][0]') ?> >Oui</label>
					<input type="radio" <?php p_idNameRadio('offer','[siteindustrielle]','[siteindustrielle][1]'); p_checkedRadio('siteindustrielle',$data,0); ?> value=0 />
					<label <?php p_labelFor('offer','[siteindustrielle][1]') ?> >Non</label>
				</div>
			</div>    
			<div class="ligne">
				<div class="column1">Des déchets de démolition ont-ils été déposés ou enfouis sur le site ?</div>
				<div class="column3">
				<input type="radio" <?php p_idNameRadio('offer','[sitedechetsdemolition]','[sitedechetsdemolition][0]'); p_checkedRadio('sitedechetsdemolition',$data,1); ?> value=1 />
				<label <?php p_labelFor('offer','[sitedechetsdemolition][0]') ?> >Oui</label>
				<input type="radio" <?php p_idNameRadio('offer','[sitedechetsdemolition]','[sitedechetsdemolition][1]'); p_checkedRadio('sitedechetsdemolition',$data,0); ?> value=0 />
				<label <?php p_labelFor('offer','[sitedechetsdemolition][1]') ?> >Non</label>
				</div>
			</div>
			<div class="ligne">
				<div class="column1">Des hydrocarbures ont-ils été stockés sur le site ?</div>
				<div class="column3">
				<input type="radio" <?php p_idNameRadio('offer','[sitehydrocarbure]','[sitehydrocarbure][0]'); p_checkedRadio('sitehydrocarbure',$data,1); ?> value=1 />
				<label <?php p_labelFor('offer','[sitehydrocarbure][0]') ?> >Oui</label>
				<input type="radio" <?php p_idNameRadio('offer','[sitehydrocarbure]','[sitehydrocarbure][1]'); p_checkedRadio('sitehydrocarbure',$data,0); ?> value=0 />
				<label <?php p_labelFor('offer','[sitehydrocarbure][1]') ?> >Non</label>
				</div>
			</div>
			<div class="ligne">
				<div class="column1">Des déchets ont-ils été éliminés ou stockés sur le site ?</div>
				<div class="column3">
				<input type="radio" <?php p_idNameRadio('offer','[sitedechets]','[sitedechets][0]'); p_checkedRadio('sitedechets',$data,1); ?> value=1 >
				<label <?php p_labelFor('offer','[sitedechets][0]') ?> >Oui</label>
				<input type="radio" <?php p_idNameRadio('offer','[sitedechets]','[sitedechets][1]'); p_checkedRadio('sitedechets',$data,0); ?> value=0 >
				<label <?php p_labelFor('offer','[sitedechets][1]') ?> >Non</label>
				</div>
			</div>
			<div class="ligne">
				<div class="column1">Un accident s'est-il produit sur le site (incendie, accident de transport, ...) ?</div>
				<div class="column3">
				<input type="radio" <?php p_idNameRadio('offer','[siteaccident]','[siteaccident][0]'); p_checkedRadio('siteaccident',$data,1); ?> value=1 />
				<label <?php p_labelFor('offer','[siteaccident][0]') ?> >Oui</label>
				<input type="radio" <?php p_idNameRadio('offer','[siteaccident]','[siteaccident][1]'); p_checkedRadio('siteaccident',$data,0); ?> value=0 />
				<label <?php p_labelFor('offer','[siteaccident][1]') ?> >Non</label>
				</div>
			</div>  
		</fieldset>
		
		<?php if($this->action == 'create'){
		}else{
		?>
		<div class="submit-bloc">
			<input class="submit" type="submit" <?php print_submit($this->action, $this->cmd) ?> />
			<input class="submit" type="submit" <?php print_cancel($this->action, $this->cmd) ?> />
		</div>
		<?php
		}
		?>
		<?php /* ----------------------------------------------------------
			* LOTS EXISTANTS FOR UPDATE CASE
			---------------------------------------------------------------------- */ ?>
		<hr/><h2>LOTS</h2><hr/>
		<?php
		if(sizeof($this->lots)>0){
			foreach($this->lots as $k=>$lot){
				// print lot
				unset($params);
				$params = array();
				$params['lotName'] = 'lot_'.$lot['uid'];
				$params['lotData'] = $lot;
				$params['lotValidation'] = $this->validationLot[$lot['uid']];
				$content = $this->get_include_contents('viewpart_lotForm.php', $params);
				echo($content); 

			} ?>
			<div class="submit-bloc">
				<input class="submit" type="submit" <?php print_submit($this->action, $this->cmd) ?> />
				<input class="submit" type="submit" <?php print_cancel($this->action, $this->cmd) ?> />
			</div>
		<?php   
		}?>
		
		<?php if($this->action == 'create'){
			// hide the addLot button
		}else{
		?>
			<div align="center"><h2><br/><span id="actionLink1" class ="actionLink" onclick="offerForm_showAddLot('addLot')" 
			onMouseOver="actionLinkMouseOver('actionLink1')" onMouseOut="actionLinkMouseOut('actionLink1')"><a>Ajouter un lot</a></span></h2></div>
		<?php 
		} ?>

		
		<div id="addLot" class="addLot">
			<?php /* ----------------------------------------------------------
				*NEW LOT SUPPLEMENTAIRE EMPTY WITH NO VALUES
				---------------------------------------------------------------------- */ ?>
			<?php
				unset($params);
				$params = array();
				$params['lotName'] = 'lotNew';
				$params['lotData'] = $this->lotNew;
				$params['lotValidation'] = $this->validationLotNew;
				$content = $this->get_include_contents('viewpart_lotForm.php', $params);
				echo($content); 
			?>
			<?php /* ----------------------------------------------------------
				*NEW LOT SUPPLEMENTAIRE END
				---------------------------------------------------------------------- */ ?>
			<div class="submit-bloc">
				<input class="submit" type="submit" <?php print_submit($this->action, 'saveAddLot') ?> />
				<input class="submit" type="submit" <?php print_cancel($this->action, $this->cmd) ?> />
			</div>
		</div>
		
	</form>
<?php
	// display the lot if the action is create
	if($this->action == 'create'){
		echo('
		<script type="text/javascript" language="Javascript">
		<!--
			offerForm_showAddLot("addLot");
		//-->
		</script>');
	}


} ?>