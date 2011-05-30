<?php if (!defined ('TYPO3_MODE')) 	die ('Access denied.'); ?>

<?php 
   /** $data: the data to display
	* $form_action
	*/
	include_once('html_helpers_lib.php');

	// ********************************************
	// shortcuts
	// ********************************************
	$piVars = $this->caller->piVars['search'];
	$txName = 'tx_vdexcavationexchange_pi1[search]';
	$formName = 'tx_vdexcavationexchange_pi1_search';
	$act = $form_action;
	$data = $this->data;
	$valids  = $this->validation;

	function print_submit($action, $cmd){
		$txName = 'tx_vdexcavationexchange_pi1[search]';
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
			}else{
				$str = 'name="'.$txName.'[submit][save]"  value="Enregistrer" title="Enregistrer"';
			}
		}    
		echo $str;
	}
	function print_cancel($action, $cmd){
		$txName = 'tx_vdexcavationexchange_pi1[search]';
		$str = 'name="'.$txName.'[submit][cancel]"  value="Annuler" title="Annuler"';
		echo $str;
	}  

?>

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
	
	
<div class='warnings'>  
<?php 
	// print warnings and errors
	print_errors($this->cmd);
?>
</div>

<form class='offerSearch' name="<?php echo formName; ?>" action="<?php echo$this->formAction; ?>" method="post">
	<fieldset class="cadre">
		<legend>Matériaux recherchés</legend>
			
			
	 <?php 
		// convert variables so we use the same as for the offer form
		$lotName = 'search'; $lotData = $data; $lotValidation = $valids;
	 ?>		
		
	<h3>Catégorie de matériaux<span class="redstar">*</span>:</h3>	
	<div class="ligne">
			<input type="radio" <?php p_idNameRadio($lotName,'[catmatterrial]','[catmatterrial][0]'); p_checkedRadio('catmatterrial',$lotData,1); ?> 
			value=1 onclick="refresh_categoryMaterial('<?php echo $lotData[uid]?>' ,this.value)" />
			<label <?php p_labelFor($lotName,'[catmatterrial][0]') ?> >Terre végétale (horizon A)</label>
			<br/>
			<input type="radio" <?php p_idNameRadio($lotName,'[catmatterrial]','[catmatterrial][1]'); p_checkedRadio('catmatterrial',$lotData,2); ?>
			value=2 onclick="refresh_categoryMaterial('<?php echo $lotData[uid]?>',this.value)" />
			<label <?php p_labelFor($lotName,'[catmatterrial][1]') ?> >Terre minérale (horizon B)</label>
			<br/>
			<input type="radio" <?php p_idNameRadio($lotName,'[catmatterrial]','[catmatterrial][2]'); p_checkedRadio('catmatterrial',$lotData,3); ?> 
			value=3 onclick="refresh_categoryMaterial('<?php echo $lotData[uid]?>',this.value)" />
			<label <?php p_labelFor($lotName,'[catmatterrial][2]') ?> >Matériaux d'excavation (horizon C meuble)</label> 			
			<br/>
			<input type="radio" <?php p_idNameRadio($lotName,'[catmatterrial]','[catmatterrial][3]'); p_checkedRadio('catmatterrial',$lotData,4); ?>
			value=4 onclick="refresh_categoryMaterial('<?php echo $lotData[uid]?>',this.value)" />
			<label <?php p_labelFor($lotName,'[catmatterrial][3]') ?> >Matériaux d'excavation (horizon C rocheux)</label> 
					
			<div class ="error"> <?php p_validation('catmatterrial',$lotValidation); ?></div>
	</div>  
	<div class="retour"></div>
	<div>Désignation des matériaux :</div>
	
	<div id="designiationuscsmeuble_<?php echo $lotData['uid']?>" class="ligne">
		<label class="label" <?php p_labelFor($lotName,'[designiationuscsmeuble]') ?> >Désignation USCS :</label>
		<div class="field">
			<select <?php p_idName($lotName,'[designiationuscsmeuble]'); ?>  style="width: 200px" >
				<?php
					p_options($this->fieldsItems['designiationuscsmeuble'],$lotData['designiationuscsmeuble']);
				?>
			</select>
		</div>
		<div class ="error"> <?php p_validation('designiationuscsmeuble',$lotValidation); ?></div>
	</div>

	<div id="designiationsimplemeubleha_<?php echo $lotData['uid']?>" class="ligne">
		<label class="label" <?php p_labelFor($lotName,'[designiationsimplemeubleha]') ?> >Désignation simplifiée HA<span class="redstar">*</span>:</label>
		<div class="field">
			<select <?php p_idName($lotName,'[designiationsimplemeubleha]'); ?>  style="width: 200px" >
				<?php
					p_options($this->fieldsItems['designiationsimplemeubleha'],$lotData['designiationsimplemeubleha']);
				?>
			</select>
		</div>
		<div  class ="error"> <?php p_validation('designiationsimplemeubleha',$lotValidation); ?></div>
	</div>

	<div id="designiationsimplemeublehb_<?php echo $lotData['uid']?>" class="ligne">
		<label class="label" <?php p_labelFor($lotName,'[designiationsimplemeublehb]') ?> >Désignation simplifiée HB<span class="redstar">*</span>:</label>
		<div class="field">
			<select <?php p_idName($lotName,'[designiationsimplemeublehb]'); ?>  style="width: 200px" >
				<?php
					p_options($this->fieldsItems['designiationsimplemeublehb'],$lotData['designiationsimplemeublehb']);
				?>
			</select>
		</div>
		<div class ="error"><?php p_validation('designiationsimplemeublehb',$lotValidation); ?></div>
	</div>  

	<div id="designiationsimplemeuble_<?php echo $lotData['uid']?>" class="ligne">
		<label class="label" <?php p_labelFor($lotName,'[designiationsimplemeuble]') ?> >Désignation simplifiée HC<span class="redstar">*</span>:</label>
		<div class="field">
			<select <?php p_idName($lotName,'[designiationsimplemeuble]'); ?>  style="width: 200px" >
				<?php
					p_options($this->fieldsItems['designiationsimplemeuble'],$lotData['designiationsimplemeuble']);
				?>
			</select>
		</div>
		<div class ="error"> <?php p_validation('designiationsimplemeuble',$lotValidation); ?></div>
	</div>
	
	<div id="designiationroche_<?php echo $lotData['uid']?>" >
		<div class="ligne">
			<label class="label" <?php p_labelFor($lotName,'[designiationroche]') ?> >Désignation <span class="redstar">*</span>:</label>
			<div class="field">
				<select <?php p_idName($lotName,'[designiationroche]'); ?>  style="width: 200px" >
					<?php
						p_options($this->fieldsItems['designiationroche'],$lotData['designiationroche']);
					?>
				</select>
			</div>
		</div>
		<div class="ligne">
			<label class="label" <?php p_labelFor($lotName,'[designiationrocheautre]') ?> >Si autre :</label>
			<div class="field"><input type=text SIZE=50 <?php p_idName($lotName,'[designiationrocheautre]'); p_inputValue('designiationrocheautre',$lotData); ?> /></div>
			<div  class ="error"> <?php p_validation('designiationroche',$lotValidation); ?></div>
		</div>  
	</div>
	
	
	
	
	<SCRIPT TYPE="text/javascript">
	<!-- 
		refresh_categoryMaterial('<?php echo $lotData[uid]?>','<?php echo($lotData['catmatterrial']); ?>');
	// -->
	</SCRIPT>
	
	<div class="retour"></div>
	
		<h3>Type d'utilisation:</h3>
		<div class="ligne">
			<label class="label" <?php p_labelFor('search','[materialuse]') ?> >Type d'utilisation:</label>
			<div class="field"><textarea rows="4" cols="50" <?php p_idName('search','[materialuse]');?>><?php echoXSS($data['materialuse']);?></textarea></div>
			<div  class ="error"> <?php p_validation('materialuse',$valids); ?></div>
		</div>

		
		<h3>Lieu d'utilisation:</h3>
		<div class="ligne">
			<label class="label" <?php p_labelFor('search','[district]') ?> >Région<span class="redstar">*</span>:</label>
			<div class="field">
				<select <?php p_idName('search','[district]'); ?>  style="width: 200px" >
					<?php
						p_options($this->fieldsItems['district'],$data['district']);
					?>
				</select>
			</div>
			<div  class ="error"> <?php p_validation('district',$valids); ?></div>
		</div>
		
		<div class="ligne">
			<label class="label" <?php p_labelFor('search','[commune]') ?> >Commune :</label>
			<div class="field"><input type=text SIZE=50 <?php p_idName('search','[commune]'); p_inputValue('commune',$data); ?> /></div>
		</div>

		<div class="ligne">
			<label class="label" <?php p_labelFor('search','[address]') ?> >Lieu-dit / Adresse :</label>
			<div class="field"><input type=text SIZE=50 <?php p_idName('search','[address]'); p_inputValue('address',$data); ?> /></div>
		</div>
		<div class="retour"></div>    
		
		<h3>Volume</h3>
		<div class="ligne">
			<label class="label" <?php p_labelFor('search','[volume]') ?> >Volume en place [m3]<span class="redstar">*</span>:</label>
			<div class="field" ><input type=text SIZE=16 <?php p_idName('search','[volume]'); p_inputValue('volume',$data); ?> style="text-align: right;" /></div>
			<div class ="error"> <?php p_validation('volume',$valids); ?></div>
		</div>
		
		
		<h3>Planning :</h3>
		<div class="ligne">
			<div class="label" <?php p_labelFor('search','[startdatework]') ?> >Date prévue de début de livraison<span class="redstar">*</span>:</div>
			<div  class="field"><input type=text SIZE=12 <?php p_idName('search','[startdatework]'); p_inputValue('startdatework',$data); ?> style="text-align: right;" /></div>
			<span class = "calendarBt" >&nbsp;<?php echo (tx_rlmpdateselectlib::getInputButton ('tx_vdexcavationexchange_pi1[search][startdatework]',$dateSelectorConf) ); ?></span>
			<span class ="error"><?php p_validation('startdatework',$valids); ?></span>
		</div>		
		<div class="ligne">
			<div class="label" <?php p_labelFor('search','[enddatework]') ?> >Date prévue de fin de livraison<span class="redstar">*</span>:</div>
			<div  class="field"><input type=text SIZE=12 <?php p_idName('search','[enddatework]'); p_inputValue('enddatework',$data); ?> style="text-align: right;" /></div>
			<span class = "calendarBt" >&nbsp;<?php echo (tx_rlmpdateselectlib::getInputButton ('tx_vdexcavationexchange_pi1[search][enddatework]',$dateSelectorConf) );?></span> 
			<div  class ="error"> <?php p_validation('enddatework',$valids); ?></div>
		</div>
		<div class="ligne">
			<div  class ="error"> <?php p_validation('dateCompare',$valids); ?></div>
		</div>
		<div class="retour"></div>
		
		<h3>Remarques:</h3>
		<div class="ligne">
			<label class="label" <?php p_labelFor('search','[comment]') ?> >&nbsp;</label>
			<div class="field"><textarea rows="4" cols="50" <?php p_idName('search','[comment]');?>><?php echoXSS($this->data['comment']);?></textarea></div>
			<div  class ="error"> <?php p_validation('comment',$valids); ?></div> 
		</div>
		
		
	</fieldset>
	
	<input type="hidden" name="tx_vdexcavationexchange_pi1[action]" value="<?php echo $this->action?>"/>
	<input type="hidden" name="tx_vdexcavationexchange_pi1[search][rec_id]" value="<?php echo $this->rec_id?>"/>
	<input type="hidden" name="tx_vdexcavationexchange_pi1[search][dateCompare]" value="1">
	<input type="hidden" name="tx_vdexcavationexchange_pi1[formsession][key]" value="<?php echo $this->formSession['key']?>" />
	
	<div class="submit-bloc">
		<input class="submit" type="submit" <?php print_submit($this->action, $this->cmd) ?> />
		<input class="submit" type="submit" <?php print_cancel($this->action, $this->cmd) ?> />
	</div>
	
	
</form>
