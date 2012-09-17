<?php
if (!isset($website) ) { header('HTTP/1.1 404 Not Found'); die; }
 
  if ( !isset($search_bans) ) $search_bans="Search bans...";
  ?>
  <div align="center">
  
<div style="margin-bottom: 12px;">
  <form action="" method="get">
  <input type="hidden" name="bans" />
   <input 
   type="text" 
   value="<?=$search_bans?>" 
   style="height: 26px;" 
   onblur= "if (this.value == '')  {this.value = '<?=$search_bans?>';}"
   onfocus="if (this.value == '<?=$search_bans?>') {this.value = '';}" 
   name="search_bans"
   />
   <input type="submit" value="Search" class="menuButtons" />
   </form>
</div>
  <table>
   <tr>
     <th width="160" class="alignleft padLeft"><?=$lang["player"] ?></th>
	 <th width="180" class="alignleft"><?=$lang["reason"] ?></th>
	 <th width="180" class="alignleft"><?=$lang["game_name"]?></th>
	 <th width="130" class="alignleft"><?=$lang["date"]?></th>
	 <th width="120" class="alignleft"><?=$lang["bannedby"]?></th>
   </tr>
  <?php
  foreach ($BansData as $Ban) {
  ?>
  <tr class="row">
    <td width="160" class="alignleft padLeft">
	  <?php if (isset($Ban["letter"]) ) { ?>
	  <img <?=ShowToolTip($Ban["country"], $website.'img/flags/'.($Ban["letter"]).'.gif', 130, 21, 15)?>  class="imgvalign" width="21" height="15" src="<?=$website?>img/flags/<?=$Ban["letter"]?>.gif" alt="" />
	  <?php } ?>
	  <a href="<?=$website?>?u=<?=strtolower($Ban["name"])?>"><?=$Ban["name"]?></a>
	  <?php if (is_logged() AND isset($_SESSION["level"] ) AND $_SESSION["level"]>=9 ) { ?>
	  <a style="float: right; font-size:11px; padding-right: 5px;" href="<?=$website?>adm/?bans&amp;edit=<?=$Ban["id"]?>">Edit</a>
	  <?php } ?>
	</td>
	 <td width="180" class="alignleft overflow_hidden" >
	 <span <?=ShowToolTip( strip_quotes($Ban["reason"]), '', 180, 0, 0 )?>><?=$Ban["reason"]?></span>
	 </td>
	 <td width="180" class="alignleft overflow_hidden"><?=$Ban["gamename"]?></td>
	 <td width="130" class="alignleft"><?=$Ban["date"]?></td>
	 <td width="120" class="alignleft"><?=$Ban["admin"]?></td>
  </tr>
  <?php
  }
  ?>
  </table>
  </div>
  <?php
  $SHOW_TOTALS = 1;
  include('inc/pagination.php');
?>