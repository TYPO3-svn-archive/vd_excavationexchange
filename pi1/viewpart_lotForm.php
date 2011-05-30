<?php 
	// parameters
	$lotName = $params['lotName'];
	$lotData = $params['lotData'];
	$lotValidation = $params['lotValidation'];
	
?>

<fieldset class="cadre" title="lot" >
	<legend>
		<?php 
		if($lotData['uid']){
			echo(' Lot :');
		}else{
			echo(' Lot supplémentaire :');
		}
		?>
	</legend>
	
	<h2>
	<?php if($lotData['uid']){
		echo('<a name="lot'.$lotData['uid'].'" ></a>Lot No: ' . $lotData['uid']);
	}else{
		echo('Le numéro du lot est attribué automatiquement');
		$lotData['uid'] = 'new';
	}
	?> </h2>
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
			
			<div  class ="error"> <?php p_validation('catmatterrial',$lotValidation); ?></div>
	</div>  
	<div class="retour"></div>
	<div>Désignation des matériaux :</div>
	
	<div id="designiationuscsmeuble_<?php echo $lotData['uid']?>" class="ligne">
		<div class="label" <?php p_labelFor($lotName,'[designiationuscsmeuble]') ?> >Désignation USCS<span class="redstar">*</span>:</div>
		<div class="field">
			<select <?php p_idName($lotName,'[designiationuscsmeuble]'); ?>  style="width: 200px" >
				<?php
					p_options($this->fieldsItems['designiationuscsmeuble'],$lotData['designiationuscsmeuble']);
				?>
			</select>
		</div>
		<span class ="error"> <?php p_validation('designiationuscsmeuble',$lotValidation); ?></span>
	</div>
	<div id="designiationsimplemeubleha_<?php echo $lotData['uid']?>" class="ligne">
		<div class="label" <?php p_labelFor($lotName,'[designiationsimplemeubleha]') ?> >Désignation simplifiée HA<span class="redstar">*</span>:</div>
		<div class="field">
			<select <?php p_idName($lotName,'[designiationsimplemeubleha]'); ?>  style="width: 200px" >
				<?php
					p_options($this->fieldsItems['designiationsimplemeubleha'],$lotData['designiationsimplemeubleha']);
				?>
			</select>
		</div>
		<span  class ="error"> <?php p_validation('designiationsimplemeubleha',$lotValidation); ?></span>
	</div>
	<div id="designiationsimplemeublehb_<?php echo $lotData['uid']?>" class="ligne">
		<div class="label" <?php p_labelFor($lotName,'[designiationsimplemeublehb]') ?> >Désignation simplifiée HB<span class="redstar">*</span>:</div>
		<div class="field">
			<select <?php p_idName($lotName,'[designiationsimplemeublehb]'); ?>  style="width: 200px" >
				<?php
					p_options($this->fieldsItems['designiationsimplemeublehb'],$lotData['designiationsimplemeublehb']);
				?>
			</select>
		</div>
		<span class ="error"><?php p_validation('designiationsimplemeublehb',$lotValidation); ?></span>
	</div>
	<div id="designiationsimplemeuble_<?php echo $lotData['uid']?>" class="ligne">
		<div class="label" <?php p_labelFor($lotName,'[designiationsimplemeuble]') ?> >Désignation simplifiée HC<span class="redstar">*</span>:</div>
		<div class="field">
			<select <?php p_idName($lotName,'[designiationsimplemeuble]'); ?>  style="width: 200px" >
				<?php
					p_options($this->fieldsItems['designiationsimplemeuble'],$lotData['designiationsimplemeuble']);
				?>
			</select>
		</div>
		<div class ="error"><?php p_validation('designiationsimplemeuble',$lotValidation); ?></div>
	</div>
	
	<div id="designiationroche_<?php echo $lotData['uid']?>" >
		<div class="ligne">
			<div class="label" <?php p_labelFor($lotName,'[designiationroche]') ?> >Désignation<span class="redstar">*</span>:</div>
			<div class="field">
				<select <?php p_idName($lotName,'[designiationroche]'); ?>  style="width: 200px" >
					<?php
						p_options($this->fieldsItems['designiationroche'],$lotData['designiationroche']);
					?>
				</select>
			</div>
		</div>
		<div class="ligne">
			<div class="label" <?php p_labelFor($lotName,'[designiationrocheautre]') ?> >Si autre :</div>
			<div class="field"><input type=text SIZE=50 <?php p_idName($lotName,'[designiationrocheautre]'); p_inputValue('designiationrocheautre',$lotData); ?> /></div>
		</div>  
		<div  class ="error"> <?php p_validation('designiationroche',$lotValidation); ?></div>
	</div>
	
	
	
	
	<SCRIPT TYPE="text/javascript">
	<!-- 
		refresh_categoryMaterial('<?php echo $lotData[uid]?>','<?php echo($lotData['catmatterrial']); ?>');
	// -->
	</SCRIPT>
	
	<div class="retour"></div>
	<h3>Remarques :</h3>
	<div class="ligne">
		<div class="label" <?php p_labelFor($lotName,'[remarques]') ?> >Particularités, aptitudes à la réutilisation :</div>
		<div class="field"><textarea rows="4" cols="50" <?php p_idName($lotName,'[remarques]');?>><?php echoXSS($lotData['remarques']);?></textarea></div>
		<div  class ="error"> <?php p_validation('remarques',$lotValidation); ?></div>	
	</div>
	<div class="retour"></div>
	<h3>Quantité  :</h3>
	<div class="ligne">
		<div class="label" <?php p_labelFor($lotName,'[volume]') ?> >Volume en place [m3]<span class="redstar">*</span>:</div>
		<div class="field" ><input type=text SIZE=10 <?php p_idName($lotName,'[volume]'); p_inputValue('volume',$lotData); ?> style="text-align: right;" /></div>
		<div class ="error"> <?php p_validation('volume',$lotValidation); ?></div>	
	</div>
	<div class="retour"></div>
	<h3>Prix (facultatif) :</h3>
	<div class="ligne">
		<div class="label" <?php p_labelFor($lotName,'[payereprise]') ?> >Modalités :</div>
		<div class="field">
			<select <?php p_idName($lotName,'[payereprise]'); ?>  style="width: 200px" >
				<?php
					p_options($this->fieldsItems['payereprise'],$lotData['payereprise']);
				?>
			</select>
		</div>
		<div class ="error"><?php p_validation('payereprise',$lotValidation); ?></div>	
	</div>
	<div class="ligne">
		<div class="label" <?php p_labelFor($lotName,'[prix]') ?> >Prix net (TVA incluse) [Fr/m3] :</div>
		<div class="field"><input type=text SIZE=10 <?php p_idName($lotName,'[prix]'); p_inputValue('prix',$lotData); ?> style="text-align: right;" /></div>
	</div>		

	<div class="ligne">
		<div class="label">
		</div>
		<div class="field">
			<input type="radio" <?php p_idNameRadio($lotName,'[sursitefranco]','[sursitefranco][0]'); p_checkedRadio('sursitefranco',$lotData,1); ?> value=1 />
			<label <?php p_labelFor($lotName,'[sursitefranco][0]') ?> >Sur site</label>    
			<input type="radio" <?php p_idNameRadio($lotName,'[sursitefranco]','[sursitefranco][1]'); p_checkedRadio('sursitefranco',$lotData,0); ?> value=0 />
			<label <?php p_labelFor($lotName,'[sursitefranco][1]') ?> >Franco</label>
		</div>
		<div  class ="error"> <?php p_validation('payereprise',$lotValidation); ?></div> 
	</div>

    
</fieldset>