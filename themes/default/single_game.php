<?php
if (!isset($website) ) { header('HTTP/1.1 404 Not Found'); die; }
?>

  <div align="center">
  <div style="margin-top: 16px; margin-bottom: 10px;">
  <h2>
    <?=$GameData[0]["gamename"]?>, 
    <b><?=$lang["duration"]?>:</b> <?=$GameData[0]["duration"]?>, 
    <b><?=$lang["date"]?>:</b> <?=$GameData[0]["datetime"]?>
  </h2>
  </div>
  <?php
  $ScourgeRow = 0;
  $SentinelRow = 0;
  $counter = 0;
  foreach ($GameData as $Game) {
  $counter++;
  if ( $Game["newcolour"] >5 AND $ScourgeRow == 0 ) {
  $ScourgeRow = 1; 
  ?>


<table>
  <tr class="scourgeRow">
  <td width="850" class="aligncenter" align="center">
	 <?php
	 if ($GameData[0]["winner"] == 1) { ?><?=$lang["scou_loser"]?><?php }   else
	 if ($GameData[0]["winner"] == 2) { ?><?=$lang["scou_winner"]?><?php }  else
	 if ($GameData[0]["winner"] == 0) { ?><?=$lang["draw_game"]?><?php }
	 ?>
	 </td>
    </tr>
</table>
	
  <table>
  <tr>
    <th width="75" class="padLeft"><?=$lang["hero"]?></th>
    <th width="220" class="alignleft"><?=$lang["player"]?></th>
    <th width="90"  class="alignleft"><?=$lang["kda"]?></th>
	<th width="90"  class="alignleft"><?=$lang["cdn"]?></th>
	<th width="90"  class="alignleft"><?=$lang["trc"]?></th>
	<th width="90"  class="alignleft"><?=$lang["gold"]?></th>
	<th width="180" class="alignleft"><?=$lang["left"]?></th>
  </tr>
    </table>
  <?php
  }
  
  
  if ( $Game["newcolour"] <=5 AND $SentinelRow == 0 ) {
  $SentinelRow = 1; 
  ?>


<table>
  <tr class="sentinelRow">
  <td width="850" class="aligncenter" align="center">
	 <?php
	 if ($GameData[0]["winner"] == 1) { ?><?=$lang["sent_winner"]?><?php } else
	 if ($GameData[0]["winner"] == 2) { ?><?=$lang["sent_loser"]?><?php }  else
	 if ($GameData[0]["winner"] == 0) { ?><?=$lang["draw_game"]?><?php }
	 ?>
	 </td>
    </tr>
</table>
	
  <table>
  <tr>
    <th width="75" class="padLeft"><?=$lang["hero"]?></th>
    <th width="220" class="alignleft"><?=$lang["player"]?></th>
    <th width="90"  class="alignleft"><?=$lang["kda"]?></th>
	<th width="90"  class="alignleft"><?=$lang["cdn"]?></th>
	<th width="90"  class="alignleft"><?=$lang["trc"]?></th>
	<th width="90"  class="alignleft"><?=$lang["gold"]?></th>
	<th width="180" class="alignleft"><?=$lang["left"]?></th>
  </tr>
</table>
  <?php
  }
  
  ?>
  <table>
  <tr style="height: 70px;" class="row">
 <td width="75" class="padLeft slot<?=$counter?>">
 <img <?=ShowToolTip("<div>".$Game["description"]."</div>", $website.'img/heroes/'.($Game["hero"]), 100, 64, 64)?> src="<?=$website?>img/heroes/<?=$Game["hero"]?>" alt="hero" width="48" height="48" /></td>
 <td width="220" class="alignleft">
 <h4>
	<?php if (isset($Game["letter"]) AND !empty($Game["letter"]) ) { ?>
	<img <?=ShowToolTip($Game["country"], $website.'img/flags/'.strtoupper($Game["letter"]).'.gif', 130, 21, 15)?> class="imgvalign" width="21" height="15" src="<?=$website?>img/flags/<?=$Game["letter"]?>.gif" alt="" />
	<?php } ?>
  <a href="<?=$website?>?u=<?=$Game["name"]?>"><?=$Game["full_name"]?></a>
 </h4>
 <div>
 <img <?=ShowToolTip("<div>".($Game["itemname1"])."</div>", $website.'img/items/'.$Game["itemicon1"], 100, 64, 64)?> src="<?=$website?>img/items/<?=$Game["itemicon1"]?>" alt="item1" width="32" height="32" />
 <img <?=ShowToolTip("<div>".($Game["itemname2"])."</div>", $website.'img/items/'.$Game["itemicon2"], 100, 64, 64)?>src="<?=$website?>img/items/<?=$Game["itemicon2"]?>" alt="item2" width="32" height="32" />
 <img <?=ShowToolTip("<div>".($Game["itemname3"])."</div>", $website.'img/items/'.$Game["itemicon3"], 100, 64, 64)?> src="<?=$website?>img/items/<?=$Game["itemicon3"]?>" alt="item3" width="32" height="32" />
 <img <?=ShowToolTip("<div>".($Game["itemname4"])."</div>", $website.'img/items/'.$Game["itemicon4"], 100, 64, 64)?> src="<?=$website?>img/items/<?=$Game["itemicon4"]?>" alt="item4" width="32" height="32" />
 <img <?=ShowToolTip("<div>".($Game["itemname5"])."</div>", $website.'img/items/'.$Game["itemicon5"], 100, 64, 64)?> src="<?=$website?>img/items/<?=$Game["itemicon5"]?>" alt="item5" width="32" height="32" />
 <img <?=ShowToolTip("<div>".($Game["itemname6"])."</div>", $website.'img/items/'.$Game["itemicon6"], 100, 64, 64)?> src="<?=$website?>img/items/<?=$Game["itemicon6"]?>" alt="item6" width="32" height="32" />
 </div>
 </td>
 <td width="90" class="alignleft statsscore">
 	  <span class="won"><?=($Game["kills"])?></span> / 
	  <span class="lost"><?=$Game["deaths"]?></span> / 
	  <span class="assists"><?=$Game["assists"]?></span>
 </td>
 <td width="90" class="alignleft statsscore">
  	  <span class="won"><?=($Game["creepkills"])?></span> / 
	  <span class="lost"><?=$Game["creepdenies"]?></span> / 
	  <span class="assists"><?=$Game["neutralkills"]?></span>
 </td>
 <td width="90" class="alignleft statsscore">
   	  <span class="won"><?=($Game["towerkills"])?></span> / 
	  <span class="lost"><?=$Game["raxkills"]?></span> / 
	  <span class="assists"><?=$Game["courierkills"]?></span>
 </td>
 <td width="90" class="alignleft statsscore"><?=$Game["gold"]?></td>
 <td width="180" class="alignleft statsscore">
 <?=$Game["left"]?>
 <div class="left_reason overflow_hidden"><?=$Game["leftreason"]?></div>
 </td>
   </tr>
  </table>
  <?php
  }
  ?>

  <div style="margin-top: 10px;">
    <h2><?=$lang["best_player"] ?> <a href="<?=$website?>?u=<?=strtolower($BestPlayer)?>"><?=$BestPlayer?></a></h2>
  </div>
</div>