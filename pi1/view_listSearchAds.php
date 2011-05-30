<?php if (!defined ('TYPO3_MODE')) 	die ('Access denied.'); ?>

<?php 
	/** $data: display my list of ads
	 *
	 */
  include_once('html_helpers_lib.php'); 
	
	$conf = array('parameter' =>  $this->conf['pagesRedirect.']['responseSearchPage'],'useCashHash' => true,'returnLast' => 'url',);							
	$redirectResponse = $this->cObj->typoLink('', $conf);
  // ********************************************
  // shortcuts
  // ********************************************
  $piVars = $this->caller->piVars['search'];
  $txName = 'tx_vdexcavationexchange_pi1[resp][type]=search&tx_vdexcavationexchange_pi1[resp][type_id]';
	
	// change data for display
	$this->fieldsItems['catmatterrial'][0] = 'Toutes les catégories'; 	
	$this->fieldsItems['district'][0] =  'Toute la romandie'; 
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

<?php // ------------------------- FILTRE ----------------------------- ?>
<form class="filter" name="tx_vdexcavationexchange_pi1_filter" action="<?php echo $this->formAction; ?>" method="post">
  <fieldset>
    <legend>Rechercher</legend>
    <div class="filter-bloc-A">
			<div style="margin-bottom: 6px">
				<div class="ligne">
					<label <?php p_labelFor('filter','[search][uid]') ?> >Lot No:</label>
					<input type="text" SIZE="12" <?php p_idName('filter','[search][uid]'); p_inputValue('uid',$this->filters[search]); ?> style="text-align: right;" />
					<span class ="error"> <?php p_validation('uid',$this->validation); ?></span>
				</div>				
				<div class="ligne">
					<label <?php p_labelFor('filter','[search][catmatterrial]') ?> >Catégorie matériaux :</label>
					<select <?php p_idName('filter','[search][catmatterrial]'); ?>  style="width: 240px" >
						<?php
							p_options($this->fieldsItems['catmatterrial'],$this->filters[search]['catmatterrial']);
						?>
					</select>
				</div>
      <div class="ligne">
        <label <?php p_labelFor('filter','[search][district]') ?> >Région :</label>
        <select <?php p_idName('filter','[search][district]'); ?>  style="width: 240px" >
          <?php
            p_options($this->fieldsItems['district'],$this->filters['search']['district']);
          ?>
        </select>
      </div>     
      
			
			<div class="ligne">
				<label <?php p_labelFor('filter','[search][startdatework]') ?> >Date de livraison du :</label>
				<input type=text SIZE=12 <?php p_idName('filter','[search][startdatework]'); p_inputValue('startdatework',$this->filters[search]); ?> style="text-align: right;" />
				<span class = "calendarBt" >&nbsp;<?php echo (tx_rlmpdateselectlib::getInputButton ('tx_vdexcavationexchange_pi1[filter][search][startdatework]',$dateSelectorConf) );?></span>
				<span>jj.mm.aaaa</span>
				<span  class ="error"> <?php p_validation('startdatework',$this->validation); ?></span>
			</div>
			<div class="ligne">
				<label <?php p_labelFor('filter','[search][enddatework]') ?> >Date de livraison au :</label>
				<input type=text SIZE=12 <?php p_idName('filter','[search][enddatework]'); p_inputValue('enddatework',$this->filters[search]); ?> style="text-align: right;" />
				<span class = "calendarBt" >&nbsp;<?php echo (tx_rlmpdateselectlib::getInputButton ('tx_vdexcavationexchange_pi1[filter][search][enddatework]',$dateSelectorConf) );?></span>
				<span>jj.mm.aaaa</span>
				<span  class ="error"> <?php p_validation('enddatework',$this->validation); ?></span>
			</div>			
			
    </div>
    </div>

    <div class="submit-bloc">
      <input name = "tx_vdexcavationexchange_pi1[submit][search]" class="submit" type="submit" value="Chercher" title="Lancer la recherche" />
      <input name = "tx_vdexcavationexchange_pi1[submit][cancel]" class="cancel" type="submit" value="Annuler" title="Annuler la recherche"/>
    </div>
  </fieldset>
</form>

<?php // ------------------ PAGINATION ----------------------------- ?>

  <?php echo $this->paginationView?>

<?php // ------------------ TABLE OF RECORDS START ----------------------------- ?>
<table class="contenttable" summary="tableau des lots" >
  <thead>
    <tr>
      <th class='lot'>Lot</th>
      <th class='descr'>Description</th>
      <th class='dispo'>Lieu<br/>Date de livraison</th> 
    </tr>
  </thead>
  <tbody>
  <?php // ------------------------- LOTS ----------------------------- ?>
  <?php 
  if(sizeOf($this->data)>0){
    foreach($this->data as $rec=>$val){ 
      $oddeven=($oddeven=='even')?'odd':'even';
    ?>
      <tr class="<?php echo $oddeven;?>">
        <td class='lot'>
          <strong><?php echo $val['uid']?></strong>
          <div class="repondre"><a href="<?php echo $redirectResponse.'?'.$txName.'='.$val['uid'] ?>" title="Répondre à l'annonce">Répondre</a></div>
        </td>
        <td class='descr'>
						<div><strong><?php echoXSS($val['catmatterrial_label'])?></strong></div>
            <div>Matériaux: <?php echoXSS($val['infoMat_label'])?></div>
            <div>Utilisation: <?php echoXSS($val['materialuse'])?></div>
            <div>Remarques: <?php echoXSS($val['comment'])?></div>
            <br/>
            <div>Volume: <?php echo $val['volume']?> [m3]</div>
        </td>
        <td class='dispo'>
          <div><?php echoXSS($val['district_label']) ?> </div>        
          <div><?php echoXSS($val['commune']) ?></div>
          <div><?php echoXSS($val['address']) ?></div>
          <br/>
					<div>Du: <span style="padding-left:3px"><?php echo $val['startdatework']?></span></div>
					<div>Au: <span style="padding-left:5px"><?php echo $val['enddatework']?></span></div>
        </td>
      </tr>    
  <?php
    }
  }else{ 
    // <h3>Aucun enregistrement ne correspond à votre recherche</h3
  }?>

  </tbody>
</table>
<?php // ------------------ PAGINATION ----------------------------- ?>

<?php echo $this->paginationView?>