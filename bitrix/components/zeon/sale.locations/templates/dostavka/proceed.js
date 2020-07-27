function getLocation(country_id, region_id, city_id, arParams, site_id)
{
	BX.showWait();
	
	property_id = arParams.CITY_INPUT_NAME;
	
	function getLocationResult(res)
	{
		BX.closeWait();		
		var obContainer = document.getElementById('LOCATION_' + property_id);
		if (obContainer) {
			obContainer.innerHTML = res;
		}
		cuSel({changedEl: ".styled",visRows: 13, scrollArrows: true});
		
		 $("#ICITY").combobox();
		 
	}

	arParams.COUNTRY = parseInt(country_id);
	arParams.REGION = parseInt(region_id);
	arParams.SITE_ID = site_id;
	
	if(isNaN(arParams.COUNTRY) || !city_id) {
         	   
	}
	
 
	var url = '/bitrix/components/pd/sale.locations/templates/dostavka/ajax.php';
	BX.ajax.post(url, arParams, getLocationResult)
}
