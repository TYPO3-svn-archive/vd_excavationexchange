<?php if (!defined ('TYPO3_MODE')) 	die ('Access denied.'); ?>

<?php 
	
include_once('html_helpers_lib.php');  
	
$conf = array('parameter' =>  $this->conf['pagesRedirect.']['offerUpdate'],'useCashHash' => true,'returnLast' => 'url',);							
$redirectOffre = $this->cObj->typoLink('', $conf);
$conf = array('parameter' =>  $this->conf['pagesRedirect.']['responseRead'],'useCashHash' => true,'returnLast' => 'url',);							
$redirectResponse = $this->cObj->typoLink('', $conf);

// ********************************************
// shortcuts
// ********************************************
$act = $form_action;
$txName = 'tx_vdexcavationexchange_pi1';
$txNameOffer = 'tx_vdexcavationexchange_pi1[offer]';

 
?>
	
<div class='warnings'>  
<?php 
// print warnings and errors
print_errors($this->cmd, $this->cmdErrorMsg);
?>
</div>

<?php 
// LISTE DES LOTS ------------------------------------------

foreach($this->lots as $rec=>$val){ ?>
	<div class="lot">
		<div class = "ligne">
			<div class="label-titel">Offre: <?php echo $val['offer_id']?> lot: <?php echo $val['uid']?></div>
			<div class="action">
				<?php // display archives or actives lots 
				if($this->archivesCase==1){?>
					<a href="<?php echo 
							$redirectOffre.'?'.$txNameOffer.'[rec_id]='.$val['offer_id'].
							'&'.$txNameOffer.'[submit_activer]['.$val['uid'].']' . '&'.$txName.'[redirect]=myArchives' ?>" title="Activer le lot <?php echo $val[uid] ?>" >Activer</a>  
				<?php
				}else{
				?>
					<a href="<?php echo $redirectOffre.'?'.$txNameOffer.'[rec_id]='.$val['offer_id'].'#lot'.$val['uid'] ?>" title="Modifier l'annonce">Modifier</a> | 
					<a href="<?php echo 
							$redirectOffre.'?'.$txNameOffer.'[rec_id]='.$val['offer_id'].
							'&'.$txNameOffer.'[submit_archiver]['.$val['uid'].']' . '&'.$txName.'[redirect]=myAds' ?>" title="Archiver le lot <?php echo $val[uid] ?>" >Archiver</a>        
				<?php  
				}
				?>      
			</div>
		</div>	
		<div class = "ligne"><div class="label">Catégorie du matériel: </div><div class="value"><?php echoXSS($val['catmatterrial_label'])?></div></div>
		<div class = "ligne"><div class="label">Informations complémentaires: </div><div class="value"><?php echoXSS($val['infoMat_label']); ?></div></div>
		<div class = "ligne"><div class="label">Remarques et aptitudes à la réutilisation: </div><div class="value"><?php echoXSS($val['remarques'])?></div></div>
		<div class = "ligne"><div class="label">Volume en place: </div><div class="value"><?php echo $val['volume']?></div></div>
		<div class = "ligne"> <div class="label">Lieu de production (excavation): </div><div class="value"><?php echoXSS($this->offers[$val['offer_id']]['commune'])?> / <?php echoXSS($this->offers[$val['offer_id']]['district_label'])?> </div></div>
		<div class = "ligne">
			<div class="label">Période de disponibilité:</div>
			<div class="value">
				<span>Du <?php echo $this->offers[$val['offer_id']]['startdatework']?></span>
				<span>au <?php echo $this->offers[$val['offer_id']]['enddatework']?></span>
			</div>
		</div>
	</div>

	<div class="response">
	<div class="label-titel">Réponses: </div>
	<?php
	if($this->lotsResponses[$val['uid']]){
		foreach($this->lotsResponses[$val['uid']] as $rec2=>$val2){ ?>
			<div class = "ligne">&nbsp;
				<div class="respA">
					<div class="label"><strong>Le <?php echoXSS($val2['crdate_label'])?></strong></div>
					<div class="label"><?php echoXSS($val2['firstname'])?></div>
					<div class="label"><?php echoXSS($val2['lastname'])?></div>
					<div class="label"><?php echoXSS($val2['company'])?></div>
					<div class="label"><?php echoXSS($val2['address'])?></div>
					<div class="label"><?php echoXSS($val2['location'])?></div>
					<div class="label">Fonction(s):<br/>          
						<?php echoXSS($val2['roles_label']) ?>
					</div>		
				</div>
				<div class="respB">
					<div class="label">tél: </div><div class="value"><?php echoXSS($val2['phone'])?></div>
					<div class="label">fax: </div><div class="value"><?php echoXSS($val2['fax'])?></div>
					<div class="label">email: </div><div class="value"><?php echoEmail($val2['email'])?></div>
					
					<?php if($this->archivesCase==0){?>
					<div class="value">
						<br/>
							<strong><a href="<?php echo 
							$redirectOffre.'?'.$txNameOffer.'[rec_id]='.$val['offer_id'].
							'&'.$txNameOffer.'[send_email]['.$val['uid'].']' . '&'.$txName.'[resp]='.$val2[uid] ?>" title="Envoyer les détails du lot <?php echo $val[uid] ?>" >Envoyer les détails du lot</a>
						</strong>
					</div>
					<?php } ?>
					
				</div>
			</div>
			<?php  
		}      
	} 
	?>
	
	</div>  
	
	<?php 
} ?>