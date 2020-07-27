
<?

if(CSite::InGroup(array(1))) {
   $canEditParams =  array(
		0 => "NAME",
		1 => "TAGS",		
		3 => "DETAIL_TEXT",
		4 => "DETAIL_PICTURE",
		5 => "17",
		6 => "18",
		7 => "5",
		8 => "15",
                9=>'ACTIVE',
                10=>'14',
                11=>'13',
                12=>'CODE',
                /*13=>'19',
                14=>'QUANTITY', */
                15=>'PRICE',
                16=>'16',
                17=>'27',
                18=>'29',
                19=>'SORT',
                20=>'32',
                21=>'36',
                22=>'35',
		23=>'54',
		24=>'55',
		25=>'56',
	       26=>'82'
                
	);
} else {
   $canEditParams =  array(
		0 => "NAME",
		1 => "TAGS",		
		3 => "DETAIL_TEXT",
		4 => "DETAIL_PICTURE",
		5 => "17",
		6 => "18",
		7 => "5",
		8 => "15",
                9=>'ACTIVE',
                10=>'14',
                11=>'13',
                12=>'CODE',                
                15=>'PRICE',
                16=>'16',  
                17=>'27',
                19=>'SORT',
                20=>'32',
                21=>'36',
                22=>'35',
		23=>'54',
		24=>'55',
		25=>'56',
		26=>'82'
                
	);
}
$arParams = array("PROPERTY_CODES_REQUIRED" => array(
	),
	"GROUPS" => array(
		0 => "1",
                1=>'19'
	),
	"STATUS" => "ANY", 
	"STATUS_NEW" => "N",
	"ALLOW_EDIT" => "Y",
	"ALLOW_DELETE" => "Y",
	"ELEMENT_ASSOC" => "CREATED_BY",
	"MAX_USER_ENTRIES" => "100000",
	"MAX_LEVELS" => "100000",
	"LEVEL_LAST" => "Y",
	"MAX_FILE_SIZE" => "0",
	"PREVIEW_TEXT_USE_HTML_EDITOR" => "N",
	"DETAIL_TEXT_USE_HTML_EDITOR" => "N",
	"SORT_BY1" => "ACTIVE_FROM",
	"SORT_ORDER1" => "DESC",
	"SORT_BY2" => "SORT",
	"SORT_ORDER2" => "ASC",
	"CHECK_DATES" => "Y",
	"SEF_MODE" => "N",
	"SEF_FOLDER" => "/staff/",
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "N",
	"CACHE_TIME" => "36000000",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"SET_TITLE" => "Y",
	"SET_STATUS_404" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
	"ADD_SECTIONS_CHAIN" => "Y",
	"USE_PERMISSIONS" => "N",
	"PREVIEW_TRUNCATE_LEN" => "",
    
    "NAV_ON_PAGE" => "30",
	"USE_CAPTCHA" => "N",
	"USER_MESSAGE_ADD" => "",
	"USER_MESSAGE_EDIT" => "",
	"DEFAULT_INPUT_SIZE" => "30",
	"RESIZE_IMAGES" => "N",
	"IBLOCK_TYPE" => "xmlcatalog",
	"IBLOCK_ID" => "1",
	"PROPERTY_CODES" =>$canEditParams );

?>
<?
$APPLICATION->IncludeComponent("pd:admin.add.form", "", $arParams, $component);
?>