<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
if (!$this->__component->__parent || strpos($this->__component->__parent->__name, "photogallery") === false)
{
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/photogallery/templates/.default/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/photogallery/templates/.default/themes/gray/style.css');
}

$GLOBALS['APPLICATION']->AddHeadScript('/bitrix/components/bitrix/photogallery.section.list/templates/.default/script.js');
?>
<style>
.photo-album-thumbs-list div.photo-item-cover-block-container,
.photo-album-thumbs-list div.photo-item-cover-block-outer,
.photo-album-thumbs-list div.photo-item-cover-block-inner{
	background-color: white;
	height:<?=($arParams["ALBUM_PHOTO_THUMBS_WIDTH"] + 16)?>px;
	width:<?=($arParams["ALBUM_PHOTO_THUMBS_WIDTH"] + 40)?>px;}
div.photo-album-thumbs-avatar{
	width:<?=$arParams["ALBUM_PHOTO_THUMBS_WIDTH"]?>px;
	height:<?=$arParams["ALBUM_PHOTO_THUMBS_WIDTH"]?>px;}
.photo-album-thumbs-list div.photo-item-info-block-inner {
	width:<?=($arParams["ALBUM_PHOTO_THUMBS_WIDTH"] + 48)?>px;}
.hidden {
	display:none !important; 
}
</style>

<?
if ($arParams["AJAX_CALL"] == "Y")
	$GLOBALS["APPLICATION"]->RestartBuffer();
?>
<script>window.oPhotoEditAlbumDialogError = false;</script>

<?if ($arResult["ERROR_MESSAGE"] != ""):?>
<script>
window.oPhotoEditAlbumDialogError = "<?= CUtil::JSEscape($arResult["ERROR_MESSAGE"]); ?>";
</script>
<?
if ($arParams["AJAX_CALL"] == "Y")
	{die();}
endif;
?>

<script>
BX.ready(function(){
	if (!window.BXPH_MESS)
		BXPH_MESS = {};
	BXPH_MESS.UnknownError = '<?= GetMessage('P_UNKNOWN_ERROR')?>';

	if (window.oPhotoEditAlbumDialog)
	{
		window.oPhotoEditAlbumDialog.SetTitle('<?= GetMessage('P_EDIT_ALBUM_TITLE')?>');
	}
	else if (window.oPhotoEditAlbumDialogError)
	{
		var pError = BX('bxph_error_row');
		if (pError)
		{
			pError.style.display = "";
			pError.cells[0].innerHTML = window.oPhotoEditAlbumDialogError;
		}
	}

	<?if ($arParams["AJAX_CALL"] == "Y"):?>
	if (BX('bxph_pass_row'))
	{
		BX('bxph_use_password').onclick = function()
		{
			var ch = !!this.checked;
			BX('bxph_pass_row').style.display = ch ? '' : 'none';
			BX('bxph_photo_password').disabled = !ch;
			if (ch)
				BX.focus(BX('bxph_photo_password'));

			if (window.oEditAlbumDialog)
				oEditAlbumDialog.adjustSizeEx();
		};
	}
	<?endif;?>
});
</script>
<div class="photo-window-edit" id="photo_section_edit_form">
<form method="post" action="<?= POST_FORM_ACTION_URI?>" name="form_photo" id="form_photo" class="photo-form">
	<input type="hidden" name="save_edit" value="Y" />
	<input type="hidden" name="edit" value="Y" />
	<input type="hidden" name="sessid" value="<?=bitrix_sessid()?>" />
	<input type="hidden" name="IBLOCK_SECTION_ID" value="<?=$arResult["FORM"]["IBLOCK_SECTION_ID"]?>" />
	<input type="hidden" name="multiple_action" value="N" />

	<table class="photo-dialog-table" <? if ($arParams["AJAX_CALL"] != "Y") echo 'style="width: 600px;"'?>>
	<tr id="bxph_error_row" style="display: none;">
		<td class="photo-dialog-warning" colSpan="2" style="color: red!important;"></td>
	</tr>
	<?if ($arParams["ACTION"] == "NEW"): /* * * * * * * * * * * * * simple create form for new album * * * * * * * * * * * * */?>
	<? if ($arParams["ACTION"] != "CHANGE_ICON"):?>
	<tr>
		<td class="photo-dialog-prop-title photo-dialog-req"><label for="bxph_name"><?=GetMessage("P_ALBUM_NAME")?>:</label></td>
		<td class="photo-dialog-prop-param photo-inp-width">
		<input type="text" name="NAME" id="bxph_name" value="<?=$arResult["FORM"]["NAME"]?>" />
		</td>
	</tr>
	<tr>
		<td class="photo-dialog-prop-title"><label for="DATE_CREATE"><?=GetMessage("P_ALBUM_DATE")?>:</label></td>
		
		<td class="photo-dialog-prop-param">
		<?$GLOBALS["APPLICATION"]->IncludeComponent("bitrix:system.field.edit",
				$arResult["FORM"]["~DATE"]["USER_TYPE"]["USER_TYPE_ID"],
				array(
					"bVarsFromForm" => $arResult["bVarsFromForm"],
					"arUserField" => $arResult["FORM"]["~DATE"],
					"form_name" => "form_photo"
				),
				$component,
			array("HIDE_ICONS"=>"Y"));?>
		</td>
	</tr>

	<tr>
		<td class="photo-dialog-prop-title" valign="top"><label for="bxph_description"><?=GetMessage("P_ALBUM_DESCRIPTION")?>:</label></td>
		<td class="photo-dialog-prop-param"><textarea id="bxph_description" name="DESCRIPTION"><?=$arResult["FORM"]["DESCRIPTION"]?></textarea></td>
	</tr>

	<? if (!empty($arResult["FORM"]["~PASSWORD"]["VALUE"])): /* pasword already exist - we can only drop it down*/?>
	<tr>
		<td class="photo-dialog-prop-title">
		<input type="hidden" id="DROP_PASSWORD" name="DROP_PASSWORD" value="N" />
		<input type="checkbox" id="USE_PASSWORD" name="USE_PASSWORD" value="Y" onclick="this.form.DROP_PASSWORD.value = this.checked ? 'N' : 'Y';" checked="checked" />
		</td>
		<td class="photo-dialog-prop-param"><label for="USE_PASSWORD"><?=GetMessage("P_SET_PASSWORD")?></label></td>
	</tr>
	<?else:?>
	<tr>
		<td class="photo-dialog-prop-title"><input type="checkbox" id="bxph_use_password" name="USE_PASSWORD" value="Y"/></td>
		<td class="photo-dialog-prop-param"><label for="bxph_use_password"><?=GetMessage("P_SET_PASSWORD")?></label></td>
	</tr>
	<tr id="bxph_pass_row" style="display: none;">
		<td class="photo-dialog-prop-title"></td>
		<td class="photo-dialog-prop-param"><label for="bxph_photo_password"><?=GetMessage("P_PASSWORD")?>:</label>&nbsp;&nbsp;&nbsp;<input type="password" name="PASSWORD" id="bxph_photo_password" value="" disabled="disabled" /></td>
	</tr>
	<?endif;/* !empty($arResult["FORM"]["~PASSWORD"]["VALUE"]) */?>
	<?endif; /* $arParams["ACTION"] != "CHANGE_ICON" */?>


	<? if ($arParams["AJAX_CALL"] != "Y"):?>
	<tr>
		<td>
			<br />
			<input type="submit" name="name_submit" value="<?=GetMessage("P_SUBMIT");?>" />
			<input type="submit" name="cancel" value="<?=GetMessage("P_CANCEL");?>" />
		</td>
	</tr>
	<?endif;?>

	<? /* For edit album we use extended form with list of all photos*/
	else: /* * * * * * * * * * * * * $arParams["ACTION"] != "NEW" * * * * * * * * * * * * */ ?>
	<!-- Album properties -->
        
	<?if ($arParams['AFTER_UPLOAD_MODE'] != "Y"):?>
	<tr class="photo-album-edit-cont">		
		<td>
			<a rel="nofollow" href="<?=$arResult["SECTION"]["DROP_LINK"]?>" onclick="return confirm('<?=CUtil::JSEscape(GetMessage('P_SECTION_DELETE_ASK'))?>');"><span><?=GetMessage("P_SECTION_DELETE")?></span></a>
			<label class="photo-al-ed-label-top" for="bxph_name"><?=GetMessage("P_ALBUM_NAME")?>:</label>
			<input class="photo-al-ed-width" type="text" name="NAME" id="bxph_name" value="<?=$arResult["FORM"]["NAME"]?>" />
		</td>
	</tr>	
	<tr><td>
			<input type="submit" name="name_submit" value="<?=GetMessage("P_SUBMIT");?>" />
		</td>
	</tr>

	<!-- List of the photo-albums if they exists -->
	<?if ($arResult["SECTION"]["SECTIONS_CNT"] > 0):?>
	<tr class="photo-album-edit-heading"><td><?= GetMessage("P_ALBUM_ALBUMS")?></td></tr>
	<tr>
		<td>
			<ul class="photo-items-list photo-album-thumbs-list">
			
			</ul>
		</td>
	</tr>
	<?endif;?>

	<?else: /*if ($arParams['AFTER_UPLOAD_MODE'] != "Y"):*/?>
	<tr><td>
		<a href="<?= $arResult["SECTION"]["~EDIT_LINK"]?>"><?= GetMessage("P_EDIT_WHOLE_ALBUM")?></a>
	</td></tr>
	<?endif; /*if ($arParams['AFTER_UPLOAD_MODE'] != "Y"):*/?>

	<? if (count($arResult["PHOTOS_JS"]) > 0):?>
	<!-- List of the photos with titles, descriptions, tags -->
	<tr class="photo-album-edit-heading"><td colSpan="2"><?= ($arParams['AFTER_UPLOAD_MODE'] == "Y" ?  GetMessage("P_ALBUM_LOADED_PHOTOS") : GetMessage("P_ALBUM_PHOTOS"))?><span id="bxph_n_from_m<?= $arResult["JSID"]?>"></span></td></tr>
        <tr><td>
	 <a href="/bitrix/admin/iblock_list_admin.php?PAGEN_1=1&IBLOCK_ID=47&type=photos&lang=ru&find_section_section=<?=$_GET['SECTION_ID']?>">Редактировать цвета</a>		
	</td></tr>
	<tr class="photo-album-edit-cont">
		<td>
			
			<div id="bxph_elements_list<?= $arResult["JSID"]?>" class="photo-ed-al-items-list"></div>
			<? if ($arResult["SHOW_MORE_PHOTOS"]):?>
			<div  class="photo-ed-al-show-more">
			<span id="more_photos<?= $arResult["JSID"]?>"><?= GetMessage("P_MORE_PHOTOS")?></span>
			<div class="photo-wait"></div>
			</div>
			<?endif;?>
			
		</td>
	</tr>

	<!-- Save buttons for album -->
	<tr>
		<td>
			<input type="submit" name="name_submit" value="<?=GetMessage("P_SUBMIT");?>" />

			<div id="bxph_elements_actions<?= $arResult["JSID"]?>" class="photo-ed-al-group-actions">
				<span  id="bxph_multi_del<?= $arResult["JSID"]?>" class="photo-ed-al-gract-del"><?= strtolower(GetMessage("P_SECTION_DELETE"))?></span>
				<span class="photo-ed-al-move-cnt">
					<span id="bxph_multi_move<?= $arResult["JSID"]?>" class="photo-ed-al-gract-move"><?= strtolower(GetMessage("P_MOVE"))?></span>
					<div id="bxph_multi_move_popup<?= $arResult["JSID"]?>" class="" style="display: none;">
					<?foreach($arResult["SECTIONS_LIST"] as $sect):?>
						<a href="javascript: void(0);" id="bxph_sect<?= $sect['ID']?>" style="padding-left: <?= (6 + $sect['DEPTH'] * 20)?>px" title="<?= $sect['NAME']?>"><?= $sect['NAME']?></a>
					<?endforeach;?>
					</div>
				</span>
				<input type="hidden" name="move_to" value="0" />

				<span id="bxph_sel_all<?= $arResult["JSID"]?>" class="photo-ed-al-sel-all">
					<span class="photo-ed-al-sel"><?=GetMessage("P_SELECT_ALL")?></span>
					<span class="photo-ed-al-desel"><?=GetMessage("P_DESELECT_ALL")?></span>
				</span>
			</div>
		</td>
	</tr>
	<?endif; /* count($arResult["PHOTOS_JS"]) > 0 */?>

	<?endif; /* $arParams["ACTION"] == "NEW" */?>
	</table>
</form>
</div>

<?if ($arParams["ACTION"] != "NEW"):?>
<script>
BX.ready(function(){
	window.oBXPhotoList = new window.BXPhotoList({
		id: '<?= $arResult["JSID"]?>',
		navPageCount: '<?= intVal($arResult["NAV_PAGE_COUNT"])?>',
		navPageSize: '<?= intVal($arResult["NAV_PAGE_SIZE"])?>',
		itemsCount: '<?= intVal($arResult["NAV_SELECTED_COUNT"])?>',
		actionUrl: '<?= CUtil::JSEscape($arParams['ACTION_URL'])?>',
		thumbSize: '<?= $arParams['THUMBNAIL_SIZE']?>',
		showTitle: <?= ($arParams['USE_PHOTO_TITLE'] != "N" ? 'true' : 'false')?>,
		showTags: <?= ($arParams['SHOW_TAGS'] != "N" ? 'true' : 'false')?>,
		items: <?= CUtil::PhpToJSObject($arResult["PHOTOS_JS"])?>,
		bPassword: <?= (empty($arResult["FORM"]["~PASSWORD"]["VALUE"]) ? 'false' : 'true')?>,
		bAfterUpload: <?= ($arParams['AFTER_UPLOAD_MODE'] == "Y" ? 'true' : 'false')?>,
		MESS: {
			albumTitle: '<?=GetMessage("P_ALBUM_NAME")?>',
			albumDesc: '<?=GetMessage("P_ALBUM_DESCRIPTION")?>',
			addTags: '<?=GetMessage("P_EDIT_ADD_TAGS")?>',
			del: '<?= strtolower(GetMessage("P_SECTION_DELETE"))?>',
			restore: '<?= strtolower(GetMessage("P_RESTORE"))?>',
			rotateLeft: '<?=GetMessage("P_ROTATE_LEFT")?>',
			rotateRight: '<?= GetMessage("P_ROTATE_RIGHT")?>',
			nFromM: '<?= GetMessage("P_ALBUM_PHOTOS_1")?>',
			MultiDelConfirm: '<?= GetMessage("P_DEL_ITEMS_CONFIRM")?>',
			MultiMoveConfirm: '<?= GetMessage("P_MOVE_ITEMS_CONFIRM")?>',
			MorePhotos: '<?= GetMessage("P_MORE_PHOTOS")?>',
			EditTags: '<?= GetMessage("P_EDIT_TAGS")?>'
		}
	});
});
</script>
<?endif;?>

<?if ($arParams["AJAX_CALL"] == "Y"):?>
<?
	$GLOBALS["APPLICATION"]->ShowHeadScripts();
	$GLOBALS["APPLICATION"]->ShowHeadStrings();
	die();
endif;
?>