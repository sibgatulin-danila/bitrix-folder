<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?='<?xml version="1.0" encoding="'.SITE_CHARSET.'"?>'?>
<torg_price  date="<?=date('Y-m-d H:i')?>">
<shop>
<shopname>PoisonDrop</shopname>
<company>ООО "Пойзондроп"</company>
<url>http://poisondrop.ru</url>
<currencies>
 <currency id="RUR" rate="1"/>
</currencies>
<categories>
<?foreach($arResult["SECTIONS"] as $id=>$section):?>
<category id="<?=$id?>" parentId="0"><?=$section?></category>
<?endforeach?>
</categories>
<offers>
<?foreach($arResult["ITEMS"] as $arItem):?>
<offer id="<?=$arItem['ELEMENT']['ID']?>">
<url><?=$arItem["link"]?></url>
<price><?=$arResult['PRICES'][$arItem['ELEMENT']['ID']]?></price>
<currencyId>RUR</currencyId>
<categoryId><?=$arItem['ELEMENT']['IBLOCK_SECTION_ID']?></categoryId>
<picture><?=$arItem['ELEMENT']["PREVIEW_PICTURE"]?></picture>
<vendor><?=$arItem['BREND']?></vendor>
<name><?=$arItem["title"]?></name>
<description><?=$arItem["description"]?></description>
</offer>
<?endforeach?>
</offers>
</shop>
</torg_price>