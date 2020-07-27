<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<? echo ShowError($arResult["ERROR_MESSAGE"]);	?>

<table border="0" cellspacing="0" cellpadding="1">
	<tr>		
	  <td valign="top">
			<div class="authWrap">				
			<?if($arResult["AUTH"]["new_user_registration"]=="Y"):?>
				<form method="post" action="<?= $arParams["PATH_TO_ORDER"]?>" name="order_reg_form">
					<?=bitrix_sessid_post()?>
					<table>
					 <tr>
					  <td align="center">
						<div class="topCaption color_dark-green">Первый заказ</div>
						<br/>
						<span class="small_text">Введите e-mail для получения подтверждения заказа и создания учетной записи</span>
						<br><br>
					  </td>	
					</tr>
					 <tr>
							<td>								
								<div class="text_wrap">			
									Имя
									<div class="input_wrap">								
									 <input type="text" name="NEW_NAME"  size="40" value="<?=$arResult["POST"]["NEW_NAME"]?>">
									</div>
								</div>
							</td>
						</tr>
					  <tr>
							<td>								
								<div class="text_wrap">			
									Фамилия
									<div class="input_wrap">								
									 <input type="text" name="NEW_LAST_NAME"  size="40" value="<?=$arResult["POST"]["NEW_LAST_NAME"]?>">
									</div>
								</div>
							</td>
						</tr>
					 
						<tr>
							<td>								
								<div class="text_wrap">			
									E-mail
									<div class="input_wrap">								
									 <input type="text" name="NEW_EMAIL" class="mailField" id="NEW_EMAIL" size="40" value="<?=$arResult["POST"]["NEW_EMAIL"]?>">
									</div>
								</div>
							</td>
						</tr>
						
						<tr>
						<td nowrap>
							<div class="text_wrap">			
							<?echo GetMessage("STOF_PASSWORD")?><span class="starrequired">*</span><br />
							<div class="input_wrap">								
							<input type="password" name="NEW_PASSWORD" maxlength="30" size="30"></td>
							</div>
							</div>
					        </tr>
						<tr>
						<td nowrap>
							<div class="text_wrap">			
							Пароль еще раз<span class="starrequired">*</span><br />
							<div class="input_wrap">								
							<input type="password" name="NEW_PASSWORD_CONFIRM" maxlength="30" size="30"></td>
							</div>
							</div>
					        </tr>
						<tr>
							<td align="center">
								<input type="submit" class="authNextSubmit site_button site_button-green" data-validate="#NEW_EMAIL"  value="Дальше">
								<input type="hidden" name="do_register" value="Y">
							</td>
						</tr>
					</table>
				</form>
			<?endif;?>
			</div>
		</td>
		<td width="25">&nbsp;</td>
		
		<td valign="top">
			<div class="authWrap">
				<form method="post" action="<?= $arParams["PATH_TO_ORDER"] ?>" name="order_auth_form">
			<table>
				
					
					<tr>
					  <td>
						<?=bitrix_sessid_post()?>
						<div class="topCaption color_dark-green">Постоянный клиент</div>
						<br/>
					  </td>	
					</tr>					
					<tr>
						<td nowrap>
						 <div class="text_wrap">			
						  E-mail
						  <div class="input_wrap">
							<input type="text" name="USER_LOGIN"  id="USER_LOGIN" class="mailField"  maxlength="30" size="30" value="<?=$arResult["USER_LOGIN"]?>">
						</div>
						 </div>
						 <br>
			
					 </td>
						
					</tr>
					<tr>
						<td nowrap>
						 <div class="text_wrap">
							<?=GetMessage("STOF_PASSWORD")?>
							<div class="input_wrap">
							<input type="password" name="USER_PASSWORD" maxlength="30" size="30">
						 	</div>
						 </div><br>
						</td>
					</tr>					
					<tr>
						<td nowrap align="center">
						  <input type="submit"  class="site_button site_button-green authNextSubmit"  data-validate="#USER_LOGIN" value="Дальше">
							<input type="hidden" name="do_authorize" value="Y">
						</td>
					</tr>
			
			</table>
				</form>
			</div>
		</td>
		
		
	</tr>
</table>

<div class="authSocnet">
 <span class="grc1">Или</span><br/>
Войти при помощи аккаунта
<?
$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "icons", 
	array(
		"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
		"AUTH_URL"=>$arResult['POST_URL'],
		"POST"=>$arResult["POST"],
		"POPUP"=>"Y",
		"SUFFIX"=>"form",
	), 
	$component, 
	array("HIDE_ICONS"=>"Y")
);
?>
</div>
<script>

function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}


 $(function() {
   $ (".authNextSubmit").click(function() {    
      if(!validateEmail($($(this).attr('data-validate')).val())) {
	$($(this).attr('data-validate')).focus().closest('.input_wrap').addClass('validate_fail');
	return false;
      }      
   })
   
   $('body').delegate('.mailField',"keyup",function() {     
     if($(this).closest('.input_wrap').hasClass('validate_fail') && validateEmail($(this).val())) {
	$(this).closest('.input_wrap').removeClass('validate_fail').addClass('validate_ok');
     }
      
     if($(this).closest('.input_wrap').hasClass('validate_ok') && !validateEmail($(this).val())) {
	$(this).closest('.input_wrap').removeClass('validate_ok').addClass('validate_fail');
     }       
     
   });
   
 });
</script>