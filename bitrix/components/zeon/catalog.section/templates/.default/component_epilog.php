<?
if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {  

}
else {
	?>

		<script language="javascript"> 		

		window.CrossssData = {
			updateCatalog : false,	
			pageType: 10,
			category: {
				id: <?=$arResult['ID']?>,
				name: "<?=$arResult['NAME']?>"
			}, 
			basketProducts: []
		};
		</script>

	<?
}