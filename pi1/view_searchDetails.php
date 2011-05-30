<?php if (!defined ('TYPO3_MODE')) 	die ('Access denied.'); ?>



<?php 
/** $data: the data to display
	* $form_action
	*/
	// ********************************************
	// shortcuts
	// ********************************************
	include_once('html_helpers_lib.php');
	$data = $this->data;
	?>

<br/>
Détails du lot : <?php echo $data[uid] ?> <br/>
<br/>
--------------------------------------------------------------------------------------------------------<br/>
1. Annonceur<br/>
--------------------------------------------------------------------------------------------------------<br/>
Nom : <?php echoXSS($data[fe_cruser_id_label][last_name]) ?><br/>
Email : <?php echoEmail($data[fe_cruser_id_label][email])?><br/>
--------------------------------------------------------------------------------------------------------<br/>
2. Données sur le projet de construction<br/>
--------------------------------------------------------------------------------------------------------<br/>
Région : <?php echoXSS($data[district_label]) ?><br/>
Commune : <?php echoXSS($data[commune]) ?><br/>
Lieu-dit / Adresse : <?php echoXSS($data[address]) ?><br/>
Période de livraison:  <?php echo ($data[startdatework] .'-'.$data[enddatework]) ?><br/>
--------------------------------------------------------------------------------------------------------<br/>	
3. Données sur les matériaux:
--------------------------------------------------------------------------------------------------------<br/>	
Catégorie des matériaux:  <?php echoXSS($data[catmatterrial_label]) ?><br/>   
Désignation des matériaux : <?php echoXSS($data[infoMat_label]) ?><br/>
Remarques : <?php echoXSS($data[comment]) ?><br/>
Utilisation : <?php echoXSS($data[materialuse]) ?><br/>
Volume en place [m3] : <?php echo $data[volume] ?><br/>

