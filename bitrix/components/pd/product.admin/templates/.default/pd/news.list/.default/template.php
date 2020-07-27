<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="news-list">
<a href="/staff/?edit=Y">Добавить</a>
<table>
<tr>
<th>Фото</th>
<th>
Наименование
</th>
<th>Активность</th>
</tr>
<?foreach($arResult["ITEMS"] as $arItem):
$arItem["DETAIL_PAGE_URL"]='/staff/?edit=Y&CODE='.$arItem['ID'];
?>
<tr>
 <td><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img class="preview_picture" border="0" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"  width="90" /></a></td>
<td><a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><b><?echo $arItem["NAME"]?></b></a></td>
<td>

<?if($arItem['ACTIVE']=='Y'):?>да<?else:?>нет<?endif?></td>
</tr>
<?endforeach;?>
</table>
<a href="/staff/?edit=Y">Добавить</a>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
