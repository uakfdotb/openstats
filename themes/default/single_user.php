<?php
if (!isset($website) ) { header('HTTP/1.1 404 Not Found'); die; }

  foreach ( $UserData as $User ) {
  ?>
  <div align="center">
  
  <h1>
    <?php if (isset($User["letter"]) ) { ?>
    <img <?=ShowToolTip($User["country"], $website.'img/flags/'.($User["letter"]).'.gif', 130, 21, 15)?> class="imgvalign" width="21" height="15" src="<?=$website?>img/flags/<?=$User["letter"]?>.gif" alt="" />
    <?php } ?>
    <?=$User["player"]?>   
	<?php if ( isset( $User["banname"] ) AND !empty( $User["banname"] ) ) { ?> - <span class="banned"><?=$lang["banned"]?></span><?php } ?>
  </h1>
  
  <?php
  if ( isset( $User["banname"] ) AND !empty( $User["banname"] ) ) {
  ?>
  <div class="padTop"><b><?=$lang["reason"]?>:</b> <span class="banned padTop"><?=$User["reason"]?></span></div>
  <div><b><?=$lang["bannedby"]?>:</b> <span class="banned"><a href="<?=$website?>?u=<?=strtolower($User["admin"])?>"><?=$User["admin"]?></a></span></div>
  <?php
  }
  ?>
  
  <div style="margin-top: 16px;">
  <table>
    <tr class="row">
	  <td class="alignleft padLeft" width="90"><b><?=$lang["score"]?>:</b></td>
	  <td class="alignleft" width="160"><?=$User["score"]?></td>
	  <td class="alignleft" width="60"><b><?=$lang["win_percent"] ?>:</b></td>
	  <td class="alignleft" width="160"><?=$User["winslosses"]?> %</td>
	</tr>
	
    <tr class="row">
	  <td class="alignleft padLeft" width="90"><b><?=$lang["kills"]?>:</b></td>
	  <td class="alignleft" width="160"><?=$User["kills"]?></td>
	  <td class="alignleft" width="60"><b><?=$lang["assists"]?>:</b></td>
	  <td class="alignleft" width="160"><?=$User["assists"]?></td>
	</tr>
	
    <tr class="row">
	  <td class="alignleft padLeft" width="90"><b><?=$lang["deaths"]?>:</b></td>
	  <td class="alignleft" width="160"><?=$User["deaths"]?></td>
	  <td class="alignleft" width="60"><b><?=$lang["kd_ratio"]?>:</b></td>
	  <td class="alignleft" width="160"><?=($User["kd"])?></td>
	</tr>
	
    <tr class="row">
	  <td class="alignleft padLeft" width="90"><b><?=$lang["games"]?>:</b></td>
	  <td class="alignleft" width="160"><a href="<?=$website?>?games&amp;uid=<?=$User["id"]?>"><?=$User["games"]?></a></td>
	  <td class="alignleft" width="60"><b><?=$lang["wl"] ?>:</b></td>
	  <td class="alignleft" width="160"><?=($User["wins"])?> / <?=($User["losses"])?></td>
	</tr>
	
    <tr class="row">
	  <td class="alignleft padLeft" width="90"><b><?=$lang["ck"] ?>:</b></td>
	  <td class="alignleft" width="160"><?=$User["creeps"]?></td>
	  <td class="alignleft" width="60"><b><?=$lang["towers"]?>:</b></td>
	  <td class="alignleft" width="160"><?=($User["towers"])?></td>
	</tr>
	
    <tr class="row">
	  <td class="alignleft padLeft" width="90"><b><?=$lang["cd"]?>:</b></td>
	  <td class="alignleft" width="160"><?=$User["denies"]?></td>
	  <td class="alignleft" width="60"><b><?=$lang["rax"]?>:</b></td>
	  <td class="alignleft" width="160"><?=($User["rax"])?></td>
	</tr>
	
    <tr class="row">
	  <td class="alignleft padLeft" width="90"><b><?=$lang["kpm"]?>:</b></td>
	  <td class="alignleft" width="160"><?=$User["kpm"]?></td>
	  <td class="alignleft" width="60"><b><?=$lang["dpm"]?>:</b></td>
	  <td class="alignleft" width="160"><?=($User["dpm"])?></td>
	</tr>
	
    <tr class="row">
	  <td class="alignleft padLeft" width="90"><b><?=$lang["neutrals"]?>:</b></td>
	  <td class="alignleft" width="160"><?=$User["neutrals"]?></td>
	  <td class="alignleft" width="60"></td>
	  <td class="alignleft" width="160"></td>
	</tr>
	
	
  </table>
  </div>
  
  <div style="margin-top: 12px;">
  <h2><a name="game_history" href="<?=$website?>?games&amp;uid=<?=$User["id"]?>"><?=$lang["user_game_history"] ?></a></h2>
  </div>
  
   <table>
    <tr>
	 <th width="220" class="alignleft padLeft"><?=$lang["game"]?></th>
	 <?php if (isset($_GET["u"]) ) { ?>
	 <th width="40" class="alignleft"><?=$lang["hero"]?></th>
	 <th width="90" class="alignleft"><?=$lang["kda"]?></th>
	 <th width="90" class="alignleft"><?=$lang["cdn"]?></th>
	 <?php } ?>
	 <th width="80" class="alignleft"><?=$lang["duration"]?></th>
	 <th width="50" class="alignleft"><?=$lang["type"]?></th>
	 <th width="140" class="alignleft"><?=$lang["date"]?></th>
	 <?php if (!isset($_GET["u"]) ) { ?>
	 <th width="160" class="alignleft"><?=$lang["map"]?></th>
	 <?php } ?>
	 <th width="160" class="alignleft"><?=$lang["creator"]?></th>
   </tr>
  <?php
  
  foreach ($GamesData as $Games) {
  ?>
  <tr class="row GameHistoryRow">
	 <td width="220" class="alignleft padLeft overflow_hidden"><a href="<?=$website?>?game=<?=$Games["id"]?>"><span class="winner<?=$Games["winner"]?>"><?=$Games["gamename"]?></span></a></td>
	 <?php if (isset($_GET["u"]) ) { ?>
	 <td width="40" height="40" class="alignleft"><img width="24" height="24" src="<?=$website?>img/heroes/<?=($Games["hero"])?>.gif" alt="Hero" /></td>
	 <td width="90" class="alignleft">
	 	<span class="won"><?=($Games["kills"])?></span> / 
	    <span class="lost"><?=$Games["deaths"]?></span> / 
	    <span class="assists"><?=$Games["assists"]?></span>
	 </td>
	 <td width="90" class="alignleft">
	 	<span class="won"><?=($Games["creepkills"])?></span> / 
	    <span class="lost"><?=$Games["creepdenies"]?></span> / 
	    <span class="assists"><?=$Games["neutrals"]?></span>
	 </td>
	 <?php } ?>
	 <td width="80" class="alignleft"><?=secondsToTime($Games["duration"])?></td>
	 <td width="50" class="alignleft"><?=$Games["type"]?></td>
	 <td width="140" class="alignleft"><?=date($DateFormat, strtotime($Games["datetime"]))?></td>
	 <?php if (!isset($_GET["u"]) ) { ?>
	 <td width="160" class="alignleft"><?=$Games["map"]?></td>
	 <?php } ?>
	 <td width="160" class="alignleft"><?=$Games["ownername"]?></td>
   </tr>
  <?php
  }
  ?>
  </table> 
  </div>
  <?php
   $SHOW_TOTALS = 1;
   include('inc/pagination.php');
  }
 ?>