$(document).ready(function(){
	if(altasib_floataction_init_vars())
		altasib_floataction_load();
});

function altasib_floataction_load(){
	altasib_floataction_text_insert();
	if(altasib_floataction.load_flag=='N'){
		var pageName=altasib_floataction_path_one_page(altasib_floataction.path_name)
		if(typeof altasib_floataction.delay != 'undefined'){
			setTimeout(function(){altasib_floataction_file_open(pageName);},altasib_floataction.delay);
		}else
			altasib_floataction_file_open(pageName);
	}
}

function altasib_floataction_init_vars(){
	if(typeof altasib_floataction !== "undefined"){
		altasib_floataction.file_loaded=0;
		altasib_floataction.load_flag='P';
		altasib_floataction.is_br_ie6=false;
		altasib_floataction.win_is_shown=false;

		altasib_floataction.anim_dur=parseInt(altasib_floataction.anim_dur);
		altasib_floataction.margin=parseInt(altasib_floataction.margin);
		altasib_floataction.delay=parseInt(altasib_floataction.delay);

		altasib_floataction.opacity=parseFloat(altasib_floataction.opacity.replace(",","."));
		altasib_floataction.opacity=altasib_floataction_area_out(altasib_floataction.opacity,0,1);
		return true;
	}else
		return false;
}

function altasib_floataction_area_out(variable,min,max){
	if(variable > max){
		return max;
	}
	else{
		if(variable < min)
			return min;
		else
			return variable;
	}
}

function altasib_floataction_init(){
	altasib_floataction.load_flag='Y';
	var mark=$('div#altasib_floataction_mark');
	$('div#altasib_floataction_window').css({
		'opacity':altasib_floataction.opacity,
		'margin':'0 '+altasib_floataction.margin+'px'
	});

	mark.css({'width':altasib_floataction.ledge_size});

	var pos=altasib_floataction.pos;
	if(pos=='02' || pos=='12' || pos=='22'){
		$("#altasib_floataction_close_kr").css('right','7px');
		mark.css('left',0);
	}
	else if(pos=='00' || pos=='10' || pos=='20'){
		$("#altasib_floataction_close_kr").css('left','7px');
		mark.css('right',0);
	}
	altasib_floataction_offset();
}

function altasib_floataction_onload(img){
	var $img=$(img);
	if(altasib_floataction.show_load_full=='Y')
		altasib_floataction_show('full');
	else
		altasib_floataction_show();
	$img.parent().addClass('altasib_floataction_img_loaded');
}

function altasib_floataction_toggle_mark(){
	var mark=$('div#altasib_floataction_mark');
	if(!altasib_floataction.win_is_shown){
		altasib_floataction_show_fully();
		mark.attr('title',altasib_floataction_GetMessage.collapse);
		altasib_floataction.win_is_shown=true;
	}else{
		altasib_floataction_hide_fully();
		mark.attr('title',altasib_floataction_GetMessage.expand);
		altasib_floataction.win_is_shown=false;
	}
}

function altasib_floataction_text_insert(){
	if(altasib_floataction.load_flag=='P'){
		var input_div=$('<div>',{id:'altasib_floataction_page_block'});
		$('body').append(input_div);

		altasib_floataction.load_flag='N';
	}
}

function altasib_floataction_file_open(path_name){
	var rezult=$.ajax({
		'url':'/bitrix/tools/altasib.floataction/altasib_floataction_init.php',
		'dataType':'html',
		'data':{'AJAX_CALL':'Y',
			'FILE_PATH':path_name,
			'SITE_ID':BX.message('SITE_ID'),
			'lang':BX.message('LANGUAGE_ID'),
			'sessid':BX.message('bitrix_sessid')
		},
		'type':'POST',
		'success':function(data){
			$('#altasib_floataction_page_block').append(data);
			altasib_floataction_init();

			if(altasib_floataction.right_liter >= 'W')
				altasib_floataction_edpan();

			$('#altasib_floataction_edit_pan').bind('blur',function(){
				if($('#altasib_floataction_page_action').is(':visible'))
					altasib_floataction_page_action_hide();
			});
		}
	});
}

function altasib_floataction_panel_notfound(){
	altasib_floataction_edpan.cur_text=altasib_floataction_GetMessage.panel_text_add;
	altasib_floataction_edpan.cur_title=altasib_floataction_GetMessage.panel_title_add;
	altasib_floataction_edpan.panel_text=altasib_floataction_GetMessage.panel_text_add;
	altasib_floataction_edpan.panel_title=altasib_floataction_GetMessage.panel_title_add;
	return altasib_floataction_edpan;
}

function altasib_floataction_panel_get_btn_notfound(){
	var panel_add_btn='<div class="bx-core-popup-menu-separator"></div>'
	+'<span class="bx-core-popup-menu-item" title="'+ altasib_floataction_GetMessage.edit_recurs_title +'">'
	+'<span class="bx-core-popup-menu-item-icon"></span>'
	+'<span id="bx_topmenu_btn_edit_html_rec" class="bx-core-popup-menu-item-text" onclick="altasib_floataction_edit_action(altasib_floataction.rec_file_path);BX.removeClass(this.parentNode.parentNode,\'bx-panel-button-icon-active\');">'
	+ altasib_floataction_GetMessage.edit_recurs_text+'</span>'
	+'</span>';

	return panel_add_btn;
}

function altasib_floataction_adm_panel_add_btn(){
	var add_btn_adm_pan='<div class="bx-core-popup-menu-separator"></div>'
	+ '<span class="bx-core-popup-menu-item" title="'+ altasib_floataction_GetMessage.ap_rec_title+'">'
	+ '<span class="bx-core-popup-menu-item-icon"></span>'
	+ '<span class="bx-core-popup-menu-item-text" onclick="altasib_floataction_edit_action(altasib_floataction.rec_file_path);altasib_floataction_adm_panel_btn_deact();">'+ altasib_floataction_GetMessage.ap_rec_text+'</span>'
	+ '</span>';

	$('div:contains('+ altasib_floataction_GetMessage.add_ap_text +').bx-core-popup-menu-level0').append(add_btn_adm_pan);
}

function altasib_floataction_adm_panel_btn_deact(){
	$('div:contains('+ altasib_floataction_GetMessage.add_ap_text +').bx-core-popup-menu-level0').hide();

	$('span:contains('+altasib_floataction_GetMessage.add_ap_text+').bx-panel-small-button')
	.removeClass('bx-panel-small-button-arrow-hover bx-panel-small-button-arrow-active');
}

function altasib_floataction_pan_check_add_btn(){
	if(altasib_floataction.recurs=='Y')
		if(typeof altasib_floataction.rec_file_path !== 'undefined'){
			var path_one_pname=altasib_floataction_path_one_page(altasib_floataction.path_name);
			if(altasib_floataction.rec_file_path !== path_one_pname){
				altasib_floataction_adm_panel_add_btn();

				var el=$('div:contains('+ altasib_floataction_GetMessage.add_ap_text +').bx-core-popup-menu-level0')
				.children('span.bx-core-popup-menu-item[title="'+altasib_floataction_GetMessage.ap_rec_title+'"]').html();
				if(el==null){
					altasib_floataction_adm_panel_add_btn();
				}
				else{
					$('span:contains('+altasib_floataction_GetMessage.add_ap_text+').bx-panel-small-button > span .bx-panel-small-button-arrow').unbind('click',altasib_floataction_pan_check_add_btn);
				}
			}
		}
}

function altasib_floataction_edpan(){
	var path_one_fname=altasib_floataction_path_one_page(altasib_floataction.path_name);

	var cur_text='';
		cur_title='',
		parent_text='',
		parent_title='',
		panel_text='',
		panel_title='',

		panel_add_btn='',
		all_panel_str='';

	if(typeof altasib_floataction.rec_file_path !== 'undefined' && altasib_floataction.file_loaded==1){

			if(altasib_floataction.rec_file_path==path_one_fname){
				altasib_floataction_edpan.cur_text=altasib_floataction_GetMessage.edit_cur_text;
				altasib_floataction_edpan.cur_title=altasib_floataction_GetMessage.edit_cur_title;
				altasib_floataction_edpan.parent_text=altasib_floataction_GetMessage.edit_recurs_text;
				altasib_floataction_edpan.parent_title=altasib_floataction_GetMessage.edit_recurs_title;
				altasib_floataction_edpan.panel_text=altasib_floataction_GetMessage.panel_edit_text;
				altasib_floataction_edpan.panel_title=altasib_floataction_GetMessage.edit_cur_title;

			}else if(altasib_floataction.recurs=='Y'){
				altasib_floataction_panel_notfound();

				panel_add_btn=altasib_floataction_panel_get_btn_notfound();
				$('#altasib_floataction_page_action').append(panel_add_btn);
				$('span:contains('+altasib_floataction_GetMessage.add_ap_text+').bx-panel-small-button > span .bx-panel-small-button-arrow').bind('click',altasib_floataction_pan_check_add_btn);
			}

		if($('a#bx-panel-toggle').hasClass('bx-panel-toggle-on')){

			var all_panel_str='<div id="altasib_floataction_edit_pan">'+
				'<div class="bx-component-opener" id="altasib_floataction_page_panel">'
				+'<span class="bx-context-toolbar"> <span class="bx-context-toolbar-inner">'
					+'<span class="bx-context-toolbar-vertical-line"></span>'
					+'<br />'
					+'<span class="bx-content-toolbar-default">'
						+'<span class="bx-context-toolbar-button-wrapper">'
							+'<span id="altasib_floataction_btn_edit_tools" class="bx-context-toolbar-button " title="'+ altasib_floataction_edpan.panel_title +'">'
								+'<span class="bx-context-toolbar-button-inner">'
									+'<a id="bx_topmenu_btn_edit_a" onclick="altasib_floataction_page_action_click();altasib_floataction_edit_action(altasib_floataction.path_name);BX.removeClass(this.parentNode.parentNode,\'bx-panel-button-icon-active\');" href="javascript:void(0)" onmouseover="if(!altasib_floataction_is_arrow()) $(\'#altasib_floataction_btn_edit_tools\').toggleClass(\'bx-context-toolbar-button-text-hover\',true);" onmouseout="if(!altasib_floataction_is_arrow()) $(\'#altasib_floataction_btn_edit_tools\').toggleClass(\'bx-context-toolbar-button-text-hover\',false);">'
										+'<span class="bx-context-toolbar-button-icon bx-context-toolbar-edit-icon"></span>'
										+'<span class="bx-context-toolbar-button-text" >'
											+ altasib_floataction_edpan.panel_text
										+'</span>'
									+'</a>'
									+'<a class="bx-context-toolbar-button-arrow" href="javascript:void(0)">'
										+'<span class="bx-context-toolbar-button-arrow" title="'+altasib_floataction_GetMessage.expend_list_text+'" onmouseover="if(!altasib_floataction_is_arrow()) $(\'#altasib_floataction_btn_edit_tools\').toggleClass(\'bx-context-toolbar-button-arrow-hover\',true);" onmouseout="if(!altasib_floataction_is_arrow()) $(\'#altasib_floataction_btn_edit_tools\').toggleClass(\'bx-context-toolbar-button-arrow-hover\',false);" onclick="altasib_floataction_page_action_click();"></span>'
									+'</a>'
								+'</span>'
							+'</span>'
						+'</span>'
					+'</span>'
					+'<br />'
					+'<span class="bx-context-toolbar-icons">'
						+'<span class="bx-context-toolbar-separator"></span>'
						+'<a id="altasib_floataction_tb_pin" class="bx-context-toolbar-pin" href="javascript:void(0)" onclick="altasib_floataction_tb_pin_click();" title="'+ altasib_floataction_GetMessage.fixed_panel_text +'"></a>'
					+'</span>'
					+'</span>'
				+'</span>'
			+'</div>'

			+'<div id="altasib_floataction_page_action" class="bx-core-popup-menu bx-core-popup-menu-bottom bx-core-popup-menu-level0 bx-core-popup-menu-no-icons" onblur="altasib_floataction_page_action_hide();">'
			+'<span class="bx-core-popup-menu-angle" style="left:134px;"></span>'
			+'<span class="bx-core-popup-menu-item" title="'+ altasib_floataction_edpan.cur_title +'">'
			+'<span class="bx-core-popup-menu-item-icon"></span>'
			+'<span id="bx_topmenu_btn_edit_izm" class="bx-core-popup-menu-item-text" onclick="altasib_floataction_edit_action(altasib_floataction.path_name);BX.removeClass(this.parentNode.parentNode,\'bx-panel-button-icon-active\');" >'
			+ altasib_floataction_edpan.cur_text+'</span>'
			+'</span>'
			+ panel_add_btn
			+'</div>';

			$('#altasib_floataction_window').prepend(all_panel_str);

			var pos=altasib_floataction.pos;
			if(pos=='02' || pos=='12' || pos=='22')
				$("div#altasib_floataction_edit_pan").css('right','50px');
			else if(pos=='00' || pos=='10' || pos=='20')
				$("div#altasib_floataction_edit_pan").css('left','50px');

			altasib_floataction_win_hover();
		}
	}
}

function altasib_floataction_is_arrow(){
	return ($('#altasib_floataction_btn_edit_tools').hasClass('bx-context-toolbar-button-arrow-active') );
}

function altasib_floataction_win_hover(){
	$('#altasib_floataction_window').stop().hover(
		function(){
			$('#altasib_floataction_page_panel').clearQueue().delay(400,'altasib_floataction_pp').show(1);
		},
		function(){
			altasib_floataction_tb_close(800);
		}
	);
}
function altasib_floataction_tb_close(del){
	if(!altasib_floataction_is_arrow() && !$('#altasib_floataction_tb_pin').hasClass('bx-context-toolbar-pin-fixed'))
		$('#altasib_floataction_page_panel').clearQueue().delay(del).clearQueue('altasib_floataction_pp').hide(1);
}
function altasib_floataction_tb_pin_click(){
	$('#altasib_floataction_tb_pin').toggleClass('bx-context-toolbar-pin-fixed');
	$('#altasib_floataction_tb_pin').toggleClass('bx-context-toolbar-pin');
}

function altasib_floataction_edit_action(path_location){
	if(altasib_floataction_check_parent_page(path_location)){
		(new BX.CDialog({
			'content_url':'/bitrix/tools/altasib.floataction/altasib_floataction_dialog.php',
			'content_post':{
				'PATH_LINK':path_location,//altasib_floataction.path_name,
				'SITE_ID':BX.message('SITE_ID'),
				'LOAD':'Y',
				'sessid':BX.message('bitrix_sessid')
			},
			'resizable':'true',
			'draggable':'true',
			'height':'460',
			'width':'735',
			'min_height':'270',
			'min_width':'500'
		})).Show();
	}
}

function altasib_floataction_check_parent_page(path_location){
	if(path_location != altasib_floataction.path_name){
		if(confirm(altasib_floataction_GetMessage.conf_edit+path_location+altasib_floataction_GetMessage.conf_numb+altasib_floataction.action_id+altasib_floataction_GetMessage.conf_contin)){
			return true;
		}else{
			return false;
		}
	}else{
		return true;
	}
}

function altasib_floataction_page_action_click(){
	if($('div#altasib_floataction_page_action').css('display')=='block')
		altasib_floataction_page_action_hide();
	else
		altasib_floataction_page_action_show();
}

function altasib_floataction_page_action_show(){
	$('#altasib_floataction_btn_edit_tools').toggleClass('bx-context-toolbar-button-arrow-active',true);
	$('#altasib_floataction_page_action').show();
}

function altasib_floataction_page_action_hide(){
	$('#altasib_floataction_btn_edit_tools').toggleClass('bx-context-toolbar-button-arrow-active',false);
	$('#altasib_floataction_page_action').hide();
}

function altasib_floataction_show_hide_click(show){
	if($('#altasib_floataction_window').css('display')=='block'){
		if(!altasib_floataction.win_is_shown){
			altasib_floataction_show_fully();
			$('div#altasib_floataction_mark').attr('title',altasib_floataction_GetMessage.collapse);
			altasib_floataction.win_is_shown=true;
		}else
			altasib_floataction_hide();
	}
	else{
		if(altasib_floataction.show_load_full=='Y')
			altasib_floataction_show('full');
		else
			altasib_floataction_show();
	}
}

function altasib_floataction_get_scroll(){
	if(window.pageYOffset != undefined){
		return pageYOffset;
	}
	else{
		var html=document.documentElement;
		var body=document.body;
		var top=html.scrollTop || body && body.scrollTop || 0;
		top -= html.clientTop;
		return top;
	}
}

function altasib_floataction_show(show){
	if( $('#altasib_floataction_window').is(':hidden')){
		var flwin=$('div#altasib_floataction_window');
		if(altasib_floataction.load_flag=='Y' && !altasib_floataction_closing_check() && (altasib_floataction.file_loaded != 0 || show=='show')){
			var out_margin=40;
			var fl_margin=altasib_floataction.margin;
			var path=altasib_floataction.img_width+fl_margin+out_margin;

			if(show=='full'){
				var anim_path=0;
				altasib_floataction.win_is_shown=true;
			}
			else
				var anim_path=altasib_floataction.ledge_size - altasib_floataction.img_width - fl_margin;

			flwin.css({'width':altasib_floataction.img_width,'height':altasib_floataction.img_height});
			$('div#altasib_floataction_mark').css('height',altasib_floataction.img_height);

			var pos=altasib_floataction.pos;

			// left
			if(pos=='00' || pos=='10' || pos=='20'){
				flwin.css('left',-path);
				flwin.show();
				flwin.stop().animate({left:anim_path},altasib_floataction.anim_dur);
			}
			// right
			else if(pos=='02' || pos=='12' || pos=='22'){
				flwin.css('right',-path);
				flwin.show();
				flwin.stop().animate({right:anim_path},altasib_floataction.anim_dur);
			}
		}
	}
}

function altasib_floataction_show_fully(){
	var flwin=$('#altasib_floataction_window');
	var pos=altasib_floataction.pos;
	// left
	if(pos=='00' || pos=='10' || pos=='20'){
		flwin.show();
		flwin.stop().animate({left:0},altasib_floataction.anim_dur);
	}
	// right
	else if(pos=='02' || pos=='12' || pos=='22'){
		flwin.show();
		flwin.stop().animate({right:0},altasib_floataction.anim_dur);
	}
}

function altasib_floataction_show_click(show){
	altasib_floataction_setCookie('altasib_floataction_close_id_'+ altasib_floataction.action_id,0,altasib_floataction.days_close);
	if(altasib_floataction.show_load_full=='Y')
		altasib_floataction_show('full');
	else
		altasib_floataction_show();
}

function altasib_floataction_close(){
	altasib_floataction_hide();
	altasib_floataction_setCookie('altasib_floataction_close_id_'+ altasib_floataction.action_id,1,altasib_floataction.days_close);
}

function altasib_floataction_closing_check(){
	if(altasib_floataction_getCookie('altasib_floataction_close_id_'+ altasib_floataction.action_id)=='1')
			return true;
		else
			return false;
}

function altasib_floataction_hide_fully(){
	var flwin=$('#altasib_floataction_window');
	var out_margin=40;
	var path=altasib_floataction.margin - altasib_floataction.ledge_size;
	var pos=altasib_floataction.pos;

	if(pos=='00' || pos=='10' || pos=='20'){
		path += altasib_floataction.img_width;
		flwin.stop().animate({left:'-='+path},altasib_floataction.anim_dur);
	}
	// right
	else if(pos=='02' || pos=='12' || pos=='22'){
		path += altasib_floataction.img_width;
		flwin.stop().animate({right:'-='+path},altasib_floataction.anim_dur);
	}
}

function altasib_floataction_hide(){
	altasib_floataction_page_action_hide();
	$('#altasib_floataction_btn_edit_tools').toggleClass('bx-context-toolbar-button-arrow-hover',false).toggleClass('bx-context-toolbar-button-arrow-active',false);
	altasib_floataction_tb_close(0);
	var flwin=$('#altasib_floataction_window');

	if(flwin.is(':visible')){
		var out_margin=30;
		var path=out_margin+altasib_floataction.margin;
		var pos=altasib_floataction.pos;

		if(pos=='00' || pos=='10' || pos=='20'){ // left
			path += altasib_floataction.img_width+out_margin;
			flwin.stop().animate({left:'-='+path},altasib_floataction.anim_dur,function(){ flwin.hide();} );
		}
		else if(pos=='02' || pos=='12' || pos=='22'){ // right
			path += altasib_floataction.img_width+out_margin;
			flwin.stop().animate({right:'-='+path},altasib_floataction.anim_dur,
				function(){ flwin.hide();} );
		}
	}else
		flwin.hide();
}

function altasib_floataction_path_minus(path_name){
	var p_name=path_name;
	var pnlen=path_name.length;
	if(pnlen > 1){
		var i=pnlen-1;
		var slesh='/';
		var slesh_numb=i;
		while (i >= 0){
			if(slesh==p_name.charAt(i)){
				if(i != (pnlen - 1)){
					slesh_numb=i;break;
				}
			} i--;
		}
		p_name=p_name.substr(0,slesh_numb+1);
	}
	return p_name;
}

function altasib_floataction_path_one_page(path_name){
	var p_name=path_name;
	var p_len=p_name.length;
	if(p_name.charAt(p_len - 1) != '/' && p_len > 1)
		p_name=altasib_floataction_path_minus(p_name);
	return(p_name);
}

function altasib_floataction_offset(){
	var flwin=$('div#altasib_floataction_window');
	var per=false;
	var ofs=altasib_floataction.offset;
	if(ofs[ofs.length-1]=='%')
		per=true;
	ofs=parseInt(ofs);

	if(!altasib_floataction.is_br_ie6)
		flwin.css('position','fixed');

	switch(altasib_floataction.pos){
		case '00':{
			flwin.css('left','0px');
			if(!altasib_floataction.is_br_ie6)
				flwin.css('top',ofs+(per?'%':'px'));//'0px');
			break;
		}
		case '02':{
			flwin.css('right','0px');
			if(!altasib_floataction.is_br_ie6)
				flwin.css('top',ofs+(per?'%':'px'));
			break;
		}
		case '10':{
			flwin.css('left','0px');
			if(!altasib_floataction.is_br_ie6)
				if(per)
					flwin.css({'top':50+ofs+'%','margin-top':-altasib_floataction.img_height/2+'px'});
				else
					flwin.css({'top':'50%','margin-top':-altasib_floataction.img_height/2+ofs+'px'});
			break;
		}
		case '12':{
			flwin.css('right','0px');
			if(!altasib_floataction.is_br_ie6)
				if(per)
					flwin.css({'top':50+ofs+'%','margin-top':-altasib_floataction.img_height/2+'px'});
				else
					flwin.css({'top':'50%','margin-top':-altasib_floataction.img_height/2+ofs+'px'});
			break;
		}
		case '20':{
			flwin.css('left','0px');
			if(!altasib_floataction.is_br_ie6)
				flwin.css('bottom',ofs+(per?'%':'px'));
			break;
		}
		case '22':{
			flwin.css('right','0px');
			if(!altasib_floataction.is_br_ie6)
				flwin.css('bottom',ofs+(per?'%':'px'));
			break;
		}
		default:break;
	}
}

//-------
function altasib_floataction_setCookie(name,value,days,path){
	if(days){
		var date=new Date();
		date.setTime(date.getTime()+(days*86400000));//24*60*60*1000
		var expires=';expires='+date.toGMTString();
	}
	else var expires='';
	if(typeof path==='undefined')
		path='/';
	document.cookie=name+'='+value+expires+';path='+path;
}
function altasib_floataction_getCookie(name){
	var nameEQ=name+'=';
	var ca=document.cookie.split(';');
	for(var i=0;i<ca.length;i++){
		var c=ca[i];
		while (c.charAt(0)==' ')
			c=c.substring(1,c.length);
		if(c.indexOf(nameEQ)==0)
			return c.substring(nameEQ.length,c.length);
	}
	return null;
}
function altasib_floataction_deleteCookie(name){
	altasib_floataction_setCookie(name,'',-1);
}