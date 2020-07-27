<?if($USER->IsAdmin()):?>
<style>
 .contentWrapper {
  padding-top:0 !important;
}
</style>
<?endif?>
<div class="nameplate_content clearfix">
  
<div class="nameplate_view clearfix">
 <div class="nameplate_content__left">    
   <div class="nameplate__options">
    <div class="nameplate__options_block">
       <div class="nameplate__option">
        <div class="nameplate__option_name nameplate__option_text">
          <span>Напишите имя/слово не более 10 символов</span>
          <div class="nameplate__option_name_buble"><img src="/i/nameplate/buble.png"/></a></div>
        </div>
        <div class="nameplate__option_content option_text">
            <div class="inputWrap">
             <input type="text" class="chain_text" maxlength="10" value="<?=$arResult['OPTIONS']['text']?>">
            </div>
        </div>
       </div>
       
       <div class="nameplate__option">
        <div class="nameplate__option_name">Выберите шрифт</div>
        <div class="nameplate__option_content clearfix">
         <?foreach($arResult['FONTS'] as $i=>$font):?>
           <div id="<?=$font?>" class="font_letter <?if($arResult['OPTIONS']['f']==$font):?>active<?endif?>"><img src="<?=$templateFolder.'/img/'.$font.'.png'?>"/></div>
          <?endforeach?>
        </div>
       </div>       
       
        <div class="nameplate__option  clearfix">
        <div class="nameplate__option_name">Вид плетения цепочки (длина 40 – 45 см)</div>
        <div class="nameplate__option_content len_l clearfix">          
         <?foreach($arResult['CHAIN_TYPE'] as $i=>$ct):?>
            <div class="chain__type <?=$ct?> <?if($arResult['OPTIONS']['ct']==$ct):?>active<?endif?>" data-type="<?=$ct?>"><img src="<?=$templateFolder?>/img/<?=$ct?>.png?2"/></div>
         <?endforeach?>
        </div>
       </div>
        
       <div class="nameplate__option">
        <div class="nameplate__option_name">Покрытие</div>
        <div class="nameplate__option_content clearfix">
            <?foreach($arResult['METALL'] as $i=>$metall):?>
            <div class="metall<?=$metall['COLOR']==$arResult['OPTIONS']['c']?' active':''?>" data-color="<?=$metall['COLOR']?>" data-offer='<?=$metall['JS_OFFER']?>'>            
              <?=$metall['NAME']?>            
            </div>
            <?endforeach?>
        </div>
       </div>       
       <div class="b_nameplate_price">
         Стоимость&nbsp;&nbsp;<span class="nprice grc5"><span id="nameplate_price"><?=$arResult['METALL'][0]['PRICE']?></span> <span class="rubSymbol">a</span></span>
         <span style="margin-left:15px;">(Готовность: <?=$arResult['DATE_READY']?>)</span>
       </div>
       
        <div class="catalogElement">
          <input type="button" class="addToCart" data-href="">
          <input type="button" class="to__wishList" data-id="<?=$arResult['METALL'][0]['ID']?>">
        </div>
        <div class="left_bottom_info">
          <div class="left_bottom_info__title"><span>Придумали свой дизайн?</span></div>
          <div class="clear"></div>
           <div class="left_bottom_info__description">
              Мы произведем подвеску по вашему описанию <br>
              или эскизу. Пишите подробности на адрес<br>
              <a href="mailto:support@poisondrop.ru">support@poisondrop.ru</a>
           </div>
        </div>
      
    </div>
   </div>

        <div class="nameplate_share clearfix">
         <div id="yashare" style="margin-top:14px;float:left;border:1px solid #5c6057;height:34px;" data-yashareL10n="ru" data-yashareQuickServices="vkontakte,facebook,twitter" data-yashareTheme="icon"></div>
         <div class="nameplate_share_button"></div>
         <div class="nameplate_share_text">Сомневаетесь?<br>Спросите у друзей!</div>
         </div>  

 </div>
 <div class="nameplate_content__right">
  <div class="nameplate_content__right_top"> 
      <div class="tab_content font__content active">
         <img src="/i/nameplate/green.jpg" class="nameplate_text_bg active" data-color="green" />
         <img src="/i/nameplate/red.jpg" class="nameplate_text_bg" data-color="red" />
         <img src="/i/nameplate/blue.jpg" class="nameplate_text_bg" data-color="blue" />
         <img src="/i/nameplate/grey.jpg" class="nameplate_text_bg" data-color="gray" />

         <? if(!empty($_GET['text']) && !empty($_GET['f']) && !empty($_GET['c']) && !empty($_GET['ct'])):?>
           <img id="nameplate_text"  src="/namenecklaceconstructor/?text=<?=$_GET['text']?>&f=<?=$_GET['f']?>&c=<?=$_GET['c']?>&ct=<?=$_GET['ct']?>"  onload="$('#loaderImage').hide()"/>
         <?else:?> 
          <img id="nameplate_text" style="display:none;"  onload="$('#loaderImage').hide()"/>
         <?endif?>
         <div id="loaderImage" ></div>
      </div>
      
      <div class="nameplate_nav_w">
        <div class="nameplate_nav green active" data-color="green"></div>
        <div class="nameplate_nav red" data-color="red"></div>
        <div class="nameplate_nav blue" data-color="blue"></div>
        <div class="nameplate_nav gray" data-color="gray"></div>
      </div>  
   </div>   
 </div>
 </div>
<div style="width:990px;margin:0 auto">
 <?=$arResult['TEXT']?>
 </div>
</div>
<img src="/i/nameplate/fon-green.jpg" style="display:none;">
<img src="/i/nameplate/fon-red.jpg" style="display:none;">
<img src="/i/nameplate/fon-grey.jpg" style="display:none;">
<img src="/i/nameplate/fon-blue.jpg" style="display:none;">
<?if($arResult['WISHLIST'] =='Y'):?>
 <script>
  var wishList = <?=CUtil::PhpToJSObject($arResult['NP_DATA'])?>;
 </script>
<?endif?>

<script type="text/javascript">
	var cSpeed=10;	
	var cTotalFrames=12;
	var cFrameWidth=32;
	var cImageSrc='/i/nameplateloader_sprites.png';
	
	var cImageTimeout=false;
	var cIndex=0;
	var cXpos=0;
	var cPreloaderTimeout=false;
	var SECONDS_BETWEEN_FRAMES=0;	
	function startAnimation(){		
		document.getElementById('loaderImage').style.backgroundImage='url('+cImageSrc+')';
		FPS = Math.round(100/cSpeed);
		SECONDS_BETWEEN_FRAMES = 1 / FPS;		
		cPreloaderTimeout=setTimeout('continueAnimation()', SECONDS_BETWEEN_FRAMES/1000);
		
	}
	
	function continueAnimation(){
		
		cXpos += cFrameWidth;		
		cIndex += 1;
		if (cIndex >= cTotalFrames) {
			cXpos =0;
			cIndex=0;
		}
		
		if(document.getElementById('loaderImage'))
			document.getElementById('loaderImage').style.backgroundPosition=(-cXpos)+'px 0';
		
		cPreloaderTimeout=setTimeout('continueAnimation()', SECONDS_BETWEEN_FRAMES*1000);
	}
	
	
	function imageLoader(s, fun)
	{
		clearTimeout(cImageTimeout);
		cImageTimeout=0;
		genImage = new Image();
		genImage.onload=function (){cImageTimeout=setTimeout(fun, 0)};
		genImage.onerror=new Function('alert(\'Could not load the image\')');
		genImage.src=s;
	}	
	new imageLoader(cImageSrc, 'startAnimation()');
</script>
