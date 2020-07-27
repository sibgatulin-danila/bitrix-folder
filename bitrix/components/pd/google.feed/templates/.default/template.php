<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?='<?xml version="1.0" encoding="'.SITE_CHARSET.'"?>'?>
<rss version="2.0"  xmlns:g="http://base.google.com/ns/1.0">
<channel>
<title>PD_feed</title>
<link><?="http://".$arResult["SERVER_NAME"]?></link>
<description>Poison Drop — интернет-магазин украшений и аксессуаров</description>
<? foreach($arResult["ITEMS"] as $arItem):?>
<item>
<title><?=$arItem["title"]?></title>
<link><?=$arItem["link"]?></link>
<description><?=$arItem["description"]?></description>
<g:id><?=$arItem['ELEMENT']['ID']?></g:id>		
<g:condition>new</g:condition>
<? if($arResult['CATALOG'][$arItem['ELEMENT']['ID']]>0) 
 $avail = 'available for order';
 else
  $avail = 'out of stock';
?>
<g:price><?=$arResult['PRICES'][$arItem['ELEMENT']['ID']]?> RUB</g:price>
<g:availability><?=$avail?></g:availability>
<g:image_link><?="http://".$arResult["SERVER_NAME"].$arItem['DETAIL_PICTURE']?></g:image_link>
<g:product_type><?=$arItem["category"]?></g:product_type>
<g:mpn><?=$arItem['PROPERTIES']['CML2_ARTICLE']['VALUE']?></g:mpn>
<g:brand><?=$arItem['BREND']?></g:brand>
<?foreach($arResult['ALBUMS'][$arItem['PROPERTIES']['PHOTOGALLERY']['VALUE']] as $photo):?>
<g:additional_image_link><?="http://".$arResult["SERVER_NAME"].$photo?></g:additional_image_link>
<?endforeach?>
</item>
<?endforeach?>
</channel>
</rss>