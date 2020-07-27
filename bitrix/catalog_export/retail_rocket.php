<?if (!isset($_GET["referer1"]) || strlen($_GET["referer1"])<=0) $_GET["referer1"] = "yandext";?><? $strReferer1 = htmlspecialchars($_GET["referer1"]); ?><?if (!isset($_GET["referer2"]) || strlen($_GET["referer2"]) <= 0) $_GET["referer2"] = "";?><? $strReferer2 = htmlspecialchars($_GET["referer2"]); ?><? header("Content-Type: text/xml; charset=windows-1251");?><? echo "<"."?xml version=\"1.0\" encoding=\"windows-1251\"?".">"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="2020-07-06 12:00">
<shop>
<name>PoisonDrop</name>
<company>PoisonDrop</company>
<url>http://poisondrop.ru</url>
<platform>1C-Bitrix</platform>
<currencies>
<currency id="RUB" rate="1" />
</currencies>
