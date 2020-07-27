<?

 class CPayture {
   	
   public static function Init($arParams) {
      	
    if($curl = curl_init()) {
	$strParams = '';
	foreach($arParams as $k=>$param) {
	 $strParams.=$k.'='.$param.';';
	}

	
	curl_setopt($curl, CURLOPT_URL, 'https://secure.payture.com/apim/Init?Key=PoisonDropPSB935&Data='.urlencode($strParams.'IP='.$_SERVER['REMOTE_ADDR']));    
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$out = curl_exec($curl);    
	curl_close($curl);
	$xml = new SimpleXMLElement($out);
	
	if(strval($xml['Success']) == 'True') {
	  return array('SUCCESS'=>'Y','sessionId'=>strval($xml['SessionId']),'Amount'=>strval($xml['Amount']));	
	} 
    }
    return false;   
  }
  
  public static function PayStatus($orderid) {      	

    if($curl = curl_init()) {
	
	curl_setopt($curl, CURLOPT_URL, 'https://secure.payture.com/apim/PayStatus?Key=PoisonDropPSB935&OrderId='.$orderid);    
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$out = curl_exec($curl);    
	curl_close($curl);
	$xml = new SimpleXMLElement($out);
	
	if(strval($xml['Success']) == 'True') {
	  return array('SUCCESS'=>'Y','ORDER_ID'=>strval($xml['OrderId']),'STATE'=>strval($xml['State']),'AMOUNT'=>strval($xml['Amount']));	
	} else
         return array('SUCCESS'=>'N','ORDER_ID'=>strval($xml['OrderId']),'CODE'=>strval($xml['ErrCode']));	
        
    
    }
    return false;   
 }
 
 
   public static function Charge($orderid) {
      	
    if($curl = curl_init()) {
	
	curl_setopt($curl, CURLOPT_URL, 'https://secure.payture.com/apim/Charge?Key=PoisonDropPSB935&Password=ALz8Xt9K&OrderId='.$orderid);    
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$out = curl_exec($curl);    
	curl_close($curl);
	$xml = new SimpleXMLElement($out);	
	if(strval($xml['Success']) == 'True') {
	  return array('SUCCESS'=>'Y','AMOUNT'=>strval($xml['Amount']));	
	} 
    }
    return false;   
 }
 
  
  
}
?>