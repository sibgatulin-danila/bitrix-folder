<?
if($USER->IsAuthorized()) {
 $currentUser  = CUser::GetByID($USER->GetID())->GetNext();	   
  $arWishList  =  explode(',',$currentUser['UF_WISHLIST']); 
  if(isset($_SESSION["SUBSCRIBE_PRODUCT"][$arResult['ID']]) || in_array($arResult['ID'], $arWishList)) { 
    $APPLICATION->set_cookie('IN_WISHLIST',1);
  }  else
   $APPLICATION->set_cookie('IN_WISHLIST',0);
  
}
?>

<?if(!isset($arParams['IS_AJAX'])): ?>

    <div id="CrossssInsertionPoint"></div>

    <script language="javascript"> 

    window.CrossssData = {
      updateCatalog : false,  
      pageType: 0,
      product: {
        id: <?=$arResult['ID']?>
      }, 
      basketProducts: []
    };

    </script>

<?endif?>
