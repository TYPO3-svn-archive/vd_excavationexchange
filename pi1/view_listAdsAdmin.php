<?php if (!defined ('TYPO3_MODE')) 	die ('Access denied.'); ?>

<?php 
  include_once('html_helpers_lib.php');  
  // ********************************************
  // shortcuts
  // ********************************************
  $piVars = $this->caller->piVars['lot'];
  $txName = 'tx_vdexcavationexchange_pi1';
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
<?php // ------------------------- STATISTICS  ----------------------------- 
  $feStats = $this->getFeUsersStats();
?>
<h3>STATISTIQUES:</h3>
  <div class = "ligne">
    <span class="label">Offres ouvertes: </span> <span class="value"><?php echo ($this->offerLot->get_recordsOpenCount()); ?></span>,   
    <span class="label">Offres archivées: </span> <span class="value"><?php echo $this->offerLot->get_recordsArchiveCount(); ?></span>
  </div>
  <div class = "ligne">
    <span class="label">Demandes ouvertes: </span><span class="value"><?php echo $this->search->get_recordsOpenCount();?></span>, 
    <span class="label">Demandes archivées: </span><span class="value"><?php echo $this->search->get_recordsArchiveCount();?></span>
  </div> 
  <div class = "ligne">
    <span class="label">Réponses: </span><span class="value"><?php echo $this->response->get_recordsCount();  ?></span>
  </div>
  <div class = "ligne">
    <span class="label">Utilisateurs:  </span><span class="value">  <?php echo $feStats[total] ?></span> 
  </div>  
  <div class = "ligne">
    <span class="label">Utilisateurs administrateurs:  </span><span class="value"><?php echo $feStats[FE_groupAdmin] ?></span> 
  </div>   
  <div class = "ligne">
    <span class="label">Utilisateurs confirmés:  </span><span class="value"> actifs: <?php echo $feStats[FE_groupActiv] ?> inactifs: <?php echo $feStats[FE_groupActivDisabled] ?> </span> 
  </div>  
  <div class = "ligne">
    <span class="label">Utilisateurs en attentes:  </span><span class="value">actifs: <?php echo $feStats[FE_groupPending] ?> inactifs: <?php echo $feStats[FE_groupPendingDisabled] ?> </span> 
  </div>    
  <hr/>

<?php // ------------------------- FILTRE ----------------------------- ?>
<form class="filter" name="tx_vdexcavationexchange_pi1_filter" action="<?php echoXSS($this->formAction); ?>" method="post">
  <fieldset>
    <legend>Rechercher</legend>
    <div class="filter-bloc-A">
    <div style="margin-bottom: 6px">
    
      <div class="ligne">
        <label <?php p_labelFor('filter','[excav][typeAnnonce]') ?> >Type d'annonce :</label>
        <select <?php p_idName('filter','[excav][typeAnnonce]'); ?>  style="width: 200px" >
          <?php
            $options = array('Offres','Demandes');
            p_options($options,$this->filters['excav']['typeAnnonce']);
          ?>          
        </select>
      </div>
      <div class="ligne">
        <label <?php p_labelFor('filter','[excav][statusAnnonce]') ?> >Status :</label>
        <select <?php p_idName('filter','[excav][statusAnnonce]'); ?>  style="width: 200px" >
          <?php
            $options = array('Ouvert','Archivé');
            p_options($options,$this->filters['excav']['statusAnnonce']);
          ?>          
        </select>
      </div>
      <div class="ligne">
        <label <?php p_labelFor('filter','[excav][lotNo]') ?> >No de lot :</label>
        <input type="text" SIZE=16 <?php p_idName('filter','[excav][lotNo]'); p_inputValue('lotNo',$this->filters[excav]);  ?> >
      </div>      
      <div class="ligne">
        <label <?php p_labelFor('filter','[excav][user]') ?> >Utilisateur :</label>
        <input type="text" SIZE=34 <?php p_idName('filter','[excav][user]'); p_inputValue('user',$this->filters[excav]);  ?> >
      </div>    

      <div class="ligne">
        <label <?php p_labelFor('filter','[excav][district]') ?> >Région :</label>
        <select <?php p_idName('filter','[excav][district]'); ?>  style="width: 200px" >
          <?php
            p_options($this->fieldsItemsOffer['district'],$this->filters['excav']['district']);
          ?>
        </select>
      </div>

      
      <div class="ligne">
        <label <?php p_labelFor('filter','[excav][tstampStart]') ?> >Date de modification du:</label>
        <input type=text SIZE=12 <?php p_idName('filter','[excav][tstampStart]'); p_inputValue('tstampStart',$this->filters[excav]); ?> style="text-align: right;" />
        <span class = "calendarBt" >&nbsp;<?php echo (tx_rlmpdateselectlib::getInputButton ('tx_vdexcavationexchange_pi1[filter][excav][tstampStart]',$dateSelectorConf) );?></span> 
        <div  class ="error"> <?php p_validation('tstampStart',$this->validationOffer); ?></div>
      </div>
      <div class="ligne">
        <label <?php p_labelFor('filter','[excav][tstampEnd]') ?> >Au :</label>
        <input type=text SIZE=12 <?php p_idName('filter','[excav][tstampEnd]'); p_inputValue('tstampEnd',$this->filters[excav]); ?> style="text-align: right;" />
        <span class = "calendarBt" >&nbsp;<?php echo (tx_rlmpdateselectlib::getInputButton ('tx_vdexcavationexchange_pi1[filter][excav][tstampEnd]',$dateSelectorConf) );?></span> 
        <div  class ="error"> <?php p_validation('tstampEnd',$this->validationOffer); ?></div>
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
<table class="contenttable" summary="tableau des lots" width="100%" >
  <thead>
    <tr>
      <th >Lot</th>
      <th >Matériel</th>
      <th >Lieu / Disponible du-au</th> 
    </tr>
  </thead>
  <tbody>
  <?php // ------------------------- LOTS OFFERS ----------------------------- ?>
  <?php 
  if($this->annonceType=='offer'){
    if(sizeOf($this->data)>0){
      foreach($this->data as $rec=>$val){ 
        $oddeven=($oddeven=='even')?'odd':'even';
        
      ?>
        <tr class="<?php echo $oddeven;?>">
          <td class='lot_admin'>
            <strong><?php echo $val['uid']?></strong>
              
            <div>Utilisateur: <?php echo $val['fe_cruser_id_label'][uid]?></div>
            <div>Nom: <?php echoXSS($val['fe_cruser_id_label'][username])?></div>
            <div>Email: <?php echoEmail($val['fe_cruser_id_label'][email])?></div>
             <?php if($val['fe_cruser_id_label'][disable] ==1 || $val['fe_cruser_id_label'][deleted] == 1){
              echo ('<div class=\'warnings\'>Utilisateur inactif</div>'); 
             }?>

            <div>
              <br/>
              <?php // display archives or actives lots
							

              $conf = array('parameter' =>  $this->conf['pagesRedirect.']['offerUpdate'],'useCashHash' => true,'returnLast' => 'url',);							
							$redirectOffre = $this->cObj->typoLink('', $conf);
              $txNameOffer = 'tx_vdexcavationexchange_pi1[offer]';

              if($this->archivesCase==1){?>
                <a href="<?php echo $redirectOffre.'?'.$txNameOffer.'[rec_id]='.$val['offer_id'].
                    '&'.$txNameOffer.'[submit_activer]['.$val['uid'].']' . '&'.$txName.'[redirect]=adminPage"' ?>" title="Activer le lot <?php echo $val[uid] ?>" ><strong>activer</strong></a><br/>
                <a href="<?php echo 
                    $redirectOffre.'?'.$txNameOffer.'[rec_id]='.$val['offer_id'].
                    '&'.$txNameOffer.'[submit_delete]['.$val['uid'].']' . '&'.$txName.'[redirect]=adminPage"' ?>" title="Archiver le lot <?php echo $val[uid] ?>" ><strong>supprimer</strong></a>  
              <?php
              }else{ ?>
                <a href="<?php echo $redirectOffre.'?'.$txNameOffer.'[rec_id]='.$val['offer_id'].'#lot'.$val['uid'] ?>" title="Modifier l'annonce"><strong>modifier</strong></a> <br/>
                <a href="<?php echo 
                    $redirectOffre.'?'.$txNameOffer.'[rec_id]='.$val['offer_id'].
                    '&'.$txNameOffer.'[submit_archiver]['.$val['uid'].']' . '&'.$txName.'[redirect]=adminPage"' ?>" title="Archiver le lot <?php echo $val[uid] ?>" ><strong>archiver</strong></a><br/>
                                    <a href="<?php echo 
                    $redirectOffre.'?'.$txNameOffer.'[rec_id]='.$val['offer_id'].
                    '&'.$txNameOffer.'[submit_delete]['.$val['uid'].']' . '&'.$txName.'[redirect]=adminPage"' ?>" title="Archiver le lot <?php echo $val[uid] ?>" ><strong>supprimer</strong></a>   
              <?php  
              } ?>
            </div>                  
               
               
          </td>
          <td class='descr_admin'>
              <div><?php echoXSS($val['catmatterrial_label'])?></div>
              <div>Description des matériaux: <?php echoXSS($val['designiationuscsmeuble_label'])?></div>
              <div>Informations complémentaires: <?php echoXSS($val['designiationsimplemeuble_label'])?></div>
              <div>Aptitudes à la réutilisation: <?php echoXSS($val['remarques'])?></div>
              <div>Remarques: <?php echoXSS($val['remarques'])?></div>
              <br/>
              <div>Volume: <?php echo $val['volume']; ?> [m3]</div>
          </td>
          <td class='dispo_admin'>
            <div><?php if($this->offers[$val['offer_id']]['district']){echoXSS($this->offers[$val['offer_id']]['district_label']);} ?> </div>        
            <div><?php echoXSS($this->offers[$val['offer_id']]['commune'])?></div>
            <div><?php echoXSS($this->offers[$val['offer_id']]['address'])?> </div>
            <br/>
            <div>Du: <span style="padding-left:3px"><?php echo $this->offers[$val['offer_id']]['startdatework']?></span></div>
            <div>Au: <span style="padding-left:5px"><?php echo $this->offers[$val['offer_id']]['enddatework']?></span></div>
            <br/>
            <div>Date de création: <span style="padding-left:3px"><?php echo  t3lib_BEfunc::date($val['crdate'])?></span></div>
            <div>Date de modification: <span style="padding-left:5px"><?php echo t3lib_BEfunc::date($val['tstamp'])?></span></div>
          </td>

        </tr>    
    <?php
      }
    }else{ 
      // <h3>Aucun enregistrement ne correspond à votre recherche</h3
    }
   // ------------------------- LOTS SEARCH ----------------------------- 
  }elseif($this->annonceType=='search'){
    if(sizeOf($this->data)>0){
      foreach($this->data as $rec=>$val){ 
        $oddeven=($oddeven=='even')?'odd':'even';
      ?>
        <tr class="<?php echo $oddeven;?>">
          <td class='lot_admin'>
          
            <strong><?php echo $val['uid']?></strong>
            <div>Utilisateur: <?php echoXSS($val['fe_cruser_id_label'][uid])?></div>
            <div>Nom: <?php echoXSS($val['fe_cruser_id_label'][username])?></div>
            <div>Email: <?php echoEmail($val['fe_cruser_id_label'][email])?></div>
             <?php if($val['fe_cruser_id_label'][disable] ==1 || $val['fe_cruser_id_label'][deleted] == 1){
              echo ('<div class=\'warnings\'>Utilisateur inactif</div>'); 
             }?>
               
            <br/>
            <div class="action_admin">
              <?php // display archives or actives searchs 
              $conf = array('parameter' =>  $this->conf['pagesRedirect.']['searchUpdate'],'useCashHash' => true,'returnLast' => 'url',);							
							$redirectSearch = $this->cObj->typoLink('', $conf);						
              $txNameSearch = 'tx_vdexcavationexchange_pi1[search]';
              if($this->archivesCase==1){?>
                <a href="<?php echo 
                    $redirectSearch.'?'.$txNameSearch.'[rec_id]='.$val['uid'].
                    '&'.$txNameSearch.'[submit_activer]['.$val['uid'].']' . '&'.$txName.'[redirect]=adminPage"' ?>" title="Activer l'annonce <?php echo $val[uid] ?>" >activer</a>  
              <?php
              }else{
              ?>
                <a href="<?php echo $redirectSearch.'?'.$txNameSearch.'[rec_id]='.$val['uid']?>" title="Modifier l'annonce  <?php echo $val[uid] ?>">modifier</a>
                <br/>
                <a href="<?php echo 
                    $redirectSearch.'?'.$txNameSearch.'[rec_id]='.$val['uid'].
                    '&'.$txNameSearch.'[submit_archiver]['.$val['uid'].']' . '&'.$txName.'[redirect]=adminPage"' ?>" title="Archiver le lot <?php echo $val[uid] ?>" >archiver</a>
                <br/>
                <a href="<?php echo 
                    $redirectSearch.'?'.$txNameSearch.'[rec_id]='.$val['uid'].
                    '&'.$txNameSearch.'[submit_delete]['.$val['uid'].']' . '&'.$txName.'[redirect]=adminPage"' ?>" title="Supprimer le lot <?php echo $val[uid] ?>" >supprimer</a>         
              <?php  
              }
              ?>
              
            </div>            

            
          </td>
          <td class='descr_admin'>      
              <div>Matériau: <?php echoXSS($val['catmatterrial_label'])?></div>
              <div>Utilisation: <?php echoXSS($val['materialuse'])?></div>
              <div>Remarques: <?php echoXSS($val['comment'])?></div>
              <br/>
              <div>Volume: <?php echoXSS($val['volume'])?> [m3]</div>
          </td>
          <td class='dispo_admin'>
            <div><?php echoXSS($val['district_label']) ?> </div>        
            <div><?php echoXSS($val['commune']) ?></div>
            <div><?php echoXSS($val['address']) ?></div>
            <br/>

						<div>Du: <span style="padding-left:3px"><?php echo $val['startdatework']?></span></div>
            <div>Au: <span style="padding-left:5px"><?php echo $val['enddatework']?></span></div>
            <br/>
            <div>Date de création: <span style="padding-left:3px"><?php echo  t3lib_BEfunc::date($val['crdate'])?></span></div>
            <div>Date de modification: <span style="padding-left:5px"><?php echo t3lib_BEfunc::date($val['tstamp'])?></span></div>            
            
          </td>
        </tr>    
    <?php
      }
    }else{ 
      // <h3>Aucun enregistrement ne correspond à votre recherche</h3
    }
  }
  // ------------------------- LOTS SEARCH END -----------------------------   
    ?>
  </tbody>
</table>
<?php // ------------------ PAGINATION ----------------------------- ?>

  <?php echo $this->paginationView?>