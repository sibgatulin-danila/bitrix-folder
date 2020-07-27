<div class="v_feedback">
   <?if($arResult['IS_UPDATE']=='Y'):?>
   <div class="v_feedback_top_i" style="position: absolute; left: 200px;  bottom: 84px;">     
     Еще раз, спасибо! 
    </div>    
   <?else:?>
    <div class="v_feedback_top_i">     
     Спасибо!<br>
     Вы нам очень помогли!
    </div>   
    <div class="v_feedback_bottom_i">
     Если у Вас есть комментарий,<br>
     оставьте его здесь.
    </div>
    <div class="v_feedback_comment">
      <form method="POST" action="">
      <textarea class="v_feedback_comment_field" name="text"></textarea>
       <input type="submit"  name="v_feedback_submit" value="Отправить" class="v_feedback_comment_submit" />
      </form>
    </div>
    <?endif?>
    
    
</div>