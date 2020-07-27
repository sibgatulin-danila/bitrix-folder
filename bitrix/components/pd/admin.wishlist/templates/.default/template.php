<table class="wishlisttable" cellspacing="0">
<? foreach($arResult as $item):?>
<tr>
 <td width="250"><a target="_blank"  href="<?=$item['PRODUCT']['DETAIL_PAGE_URL']?>"><img src="<?=$item['PRODUCT']['IMG']?>" style="width:200px;" /></a></td>   
 <td><a  target="_blank"  href="<?=$item['PRODUCT']['DETAIL_PAGE_URL']?>"><?=$item['PRODUCT']['NAME']?></a> (<?=$item['CNT']?>)</td>
 <td style="padding-left:20px;">
     
<?
foreach($item['USERS'] as $kt =>$type):?>
  <?
   $c=0;
  foreach($type as $user) {
   if($user['ID']=='noreg' && $user['CNT']>1) {
    $c=$user['CNT']-1;      
   }
  } 
    ?>
  
 <div class="b_<?=$kt?> ii"><?=count($type)+$c?></div>
 <?foreach($type as $user):?>
  <?if($user['ID']=='noreg'):?>
    <?=$user['LAST_NAME'].' '.$user['NAME']?>
    <?if($user['CNT']>1):?>
    (<?=$user['CNT']?>)
    <?endif?>    
    <br/>
  <?else:?>
   <a target="_blank" href="/bitrix/admin/user_edit.php?lang=ru&ID=<?=$user['ID']?>"><?=$user['LAST_NAME'].' '.$user['NAME']?></a><br>
   <?endif?>
  <?endforeach?>
  <br>
  <?endforeach?>  
 </td>
</tr>
<?endforeach?>
</table>