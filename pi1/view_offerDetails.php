<?php if (!defined ('TYPO3_MODE')) 	die ('Access denied.'); ?>

<?php 
include_once('html_helpers_lib.php');
   /** $data: the data to display
	* $form_action
	*/
	// ********************************************
	// shortcuts
	// ********************************************
	$data = $this->data;
	$lot = $this->lot;
?>

<br/>
Détails du lot : <?php echo $lot[uid] ?> <br/>
<br/>
--------------------------------------------------------------------------------------------------------<br/>
1. Annonceur<br/>
--------------------------------------------------------------------------------------------------------<br/>
Nom : <?php echoXSS($data[contactname]) ?><br/>
Société : <?php echoXSS($data[contactfunction])?><br/>
Email : <?php echoEmail($lot[fe_cruser_id_label][email])?><br/>
Rôle dans le projet de construction : <?php echoXSS($data[roles_label])?><br/>
--------------------------------------------------------------------------------------------------------<br/>
2. Données sur le projet de construction<br/>
--------------------------------------------------------------------------------------------------------<br/>
Région : <?php echoXSS($data[district_label]) ?><br/>
Commune : <?php echoXSS($data[commune]) ?><br/>
Lieu-dit / Adresse : <?php echoXSS($data[address]) ?><br/>
Type d'ouvrage : <?php echoXSS($data[ouvragetype]) ?><br/>
Date prévue de début des travaux :  <?php echo $data[startdatework] ?><br/>
Date prévue de fin des travaux :  <?php echo $data[enddatework] ?><br/>
--------------------------------------------------------------------------------------------------------<br/>	
3. Données connues sur la provenance des matériaux	
--------------------------------------------------------------------------------------------------------<br/>
Le sol du site présente-t-il des indices de pollution ? <?php if($data[siteindicepollution]==1){ echo('Oui');}elseif($data[siteindicepollution]==0){echo('Non');}else{echo 'Ne sais pas';} ?><br/> 
Des activités industrielles se sont-elles déroulées sur le site ? <?php if($data[siteindustrielle]==1){ echo('Oui');}elseif($data[siteindustrielle]==0){echo('Non');}else{echo 'Ne sais pas';} ?><br/> 
Des déchets de démolition ont-ils été déposés ou enfouis sur le site ? <?php if($data[sitedechetsdemolition]==1){ echo('Oui');}elseif($data[sitedechetsdemolition]===0){echo('Non');}else{echo 'Ne sais pas';} ?><br/> 
Des hydrocarbures ont-ils été stockés sur le site ? <?php if($data[sitehydrocarbure]==1){ echo('Oui');}elseif($data[sitehydrocarbure]==0){echo('Non');}else{echo 'Ne sais pas';} ?><br/> 
Des déchets ont-ils été éliminés ou stockés sur le site ? <?php if($data[sitedechets]==1){ echo('Oui');}elseif($data[sitedechets]==0){echo('Non');}else{echo 'Ne sais pas';} ?><br/> 
--------------------------------------------------------------------------------------------------------<br/>	
4. Données sur les matériaux:
--------------------------------------------------------------------------------------------------------<br/>	
Catégorie des matériaux:  <?php echoXSS($lot[catmatterrial_label]) ?><br/>   
Désignation des matériaux : <?php echoXSS($lot[infoMat_label]) ?><br/>
Remarques : <?php echoXSS($lot[remarques]) ?><br/>
Volume en place [m3] : <?php echo $lot[volume] ?><br/>
Prix net demandé (TVA incluse) [Fr] : <?php echo $lot[prix] ?><br/>

Modalités:  <?php 	switch ($lot[payereprise]){
						case 0 : echo ('-'); break; 
						case 1 : echo ('Paye pour la reprise'); break; 
						case 2 : echo 'Donne le matériel'; break;
						case 3 : echo 'Vend le matériel';break;
					}?>
<?php if($lot[sursitefranco]==1){ echo('  sur site');}else{echo('  franco');} ?><br/>


