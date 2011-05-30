<?php if (!defined ('TYPO3_MODE')) 	die ('Access denied.'); ?>

<?php 
	/** $data: displays my list of ads
	 *
	 */
	
	require_once('html_helpers_lib.php');  
	$conf = array('parameter' =>  $this->conf['pagesRedirect.']['searchUpdate'],'useCashHash' => true,'returnLast' => 'url',);							
	$redirectSearch = $this->cObj->typoLink('', $conf);
	$conf = array('parameter' =>  $this->conf['pagesRedirect.']['responseRead'],'useCashHash' => true,'returnLast' => 'url',);							
	$redirectResponse = $this->cObj->typoLink('', $conf);	
	
// ********************************************
// shortcuts
// ********************************************
$act = $form_action;
$txName = 'tx_vdexcavationexchange_pi1';
$txNameSearch = 'tx_vdexcavationexchange_pi1[search]';  
?>

<div class='warnings'>  
<?php 
// print warnings and errors
print_errors($this->cmd, $this->cmdErrorMsg);
?>
</div>

<?php 
	if($red = $this->caller->piVars['redirect']){
		// Redirect to the page "Mon dossier"
		$conf = array('parameter' =>  $this->conf['pagesRedirect.'][$red],'useCashHash' => true,'returnLast' => 'url',);	
		$url = $this->cObj->typoLink('', $conf);
		$redirectTo = t3lib_div::getIndpEnv('TYPO3_SITE_URL').$url;
		echo('<br/><br/><br/><div class="actionLink" align="center"><a href="'.$redirectTo.'"> Continuer <a></div>');
	}
?>

<?php 
// LISTE DES LOTS ------------------------------------------
if(sizeof($this->searchads)>0 && is_array($this->searchads)){
	foreach($this->searchads as $rec=>$val){ ?>
		<div class="lot"> 
			<div class = "ligne">
				<div class="label-titel">Demande: <?php echo $val['uid']?></div>
				<div class="action">
					<?php // display archives or actives searchs 
					if($this->archivesCase==1){?>
						<a href="<?php echo 
								$redirectSearch.'?'.$txNameSearch.'[rec_id]='.$val['uid'].
								'&'.$txNameSearch.'[submit_activer]['.$val['uid'].']' . '&'.$txName.'[redirect]=myArchivesSearch' ?>" title="Activer l'annonce <?php echo $val[uid] ?>" >Activer</a>  
					<?php
					}else{
					?>
						<a href="<?php echo $redirectSearch.'?'.$txNameSearch.'[rec_id]='.$val['uid']?>" title="Modifier l'annonce  <?php echo $val[uid] ?>">Modifier</a> | 
						<a href="<?php echo 
								$redirectSearch.'?'.$txNameSearch.'[rec_id]='.$val['uid'].
								'&'.$txNameSearch.'[submit_archiver]['.$val['uid'].']' . '&'.$txName.'[redirect]=myAdsSearch' ?>" title="Archiver le lot <?php echo $val[uid] ?>" >Archiver</a>        
					<?php  
					}
					?>
				</div>
			</div>
			<div class = "ligne"><div class="label">Matériaux demandés: </div><div class="value"><?php echoXSS($val['catmatterrial_label'])?></div></div>
			<div class = "ligne"><div class="label">Informations complémentaires: </div><div class="value"><?php echoXSS($val['infoMat_label']); ?></div></div>			
			<div class = "ligne"><div class="label">Type d'utilisation : </div><div class="value"><?php echoXSS($val['materialuse'])?></div></div>
			<div class = "ligne"><div class="label">Quantité souhaitée [m3]: </div><div class="value"><?php echo $val['volume']?></div></div>
			<div class = "ligne"><div class="label">Région : </div><div class="value"><?php echoXSS($val['district_label']); ?></div></div>
			<div class = "ligne">
			<div class="label">Période de livraison:</div>
				<div class="value">
					<span>Du <?php echo $val['startdatework']?></span>
					<span>au <?php echo $val['enddatework']?></span>
				</div>
			</div>
			<div class = "ligne"><div class="label">Remarques : </div><div class="value"><?php echoXSS($val['comment'])?></div></div>
			
			
			
		</div>

		<div class="response">
		<div class="label-titel">Réponses: </div>
		<?php
		if($this->searchadsResponses[$val['uid']]){
			foreach($this->searchadsResponses[$val['uid']] as $rec2=>$val2){ ?>
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
								$redirectSearch.'?'.$txNameSearch.'[rec_id]='.$val['uid'].
								'&'.$txNameSearch.'[send_email]['.$val['uid'].']' . '&'.$txName.'[resp]='.$val2[uid] ?>" title="Envoyer les détails du lot <?php echo $val[uid] ?>" >Envoyer les détails du lot</a></strong>
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
	} 
}  ?>