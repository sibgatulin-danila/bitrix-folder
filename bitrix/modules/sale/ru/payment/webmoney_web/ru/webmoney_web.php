<?
global $MESS;

$MESS["SWMWP_DTITLE"] = "Оплата через WebMoney (Web)";
$MESS["SWMWP_DDESCR"] = "Оплата через WebMoney с помощью сервиса <b>Web Merchant Interface</b> <a href=\"https://merchant.webmoney.ru/conf/guide.asp\" target=\"_blank\">https://merchant.webmoney.ru/conf/guide.asp</a>.<br>Необходимо настроить сервис Web Merchant Interface для обработки платежей, выполняемых клиентом на ваш кошелек, в разделе Настройки. На сайте <a href=\"https://merchant.webmoney.ru\" target=\"_blank\">https://merchant.webmoney.ru</a> выберите пункт меню \"Настройки\". Пройдите авторизацию и выберите кошелек, на который вы будете принимать платежи через сервис Web Merchant Interface. Вы получите страницу для настройки параметров.<br>Необходимо настроить параметры:<UL style=\"font-size: 100%;\"><LI><b>Result URL</b> - для автоматического отслеживания платежей.</LI><LI><b>Success URL</b> - страница, на которую будет перенаправлен покупатель в случае успешного платежа <nobr>(http://адрес_сайта/success.html)</nobr>. Укажите подходящую существующую страницу или создайте новую.</LI><LI><b>Fail URL</b> - страница, на которую будет перенаправлен покупатель, если платеж в сервисе Web Merchant Interface не был выполнен по каким-то причинам <nobr>(http://адрес_сайта/fail.html)</nobr>. Укажите подходящую существующую страницу или создайте новую.</LI><LI>Метод формирования контрольной подписи - <b>SHA256</b>, либо <b>MD5</b> (перестаёт работать с 01.09.2014)</LI><LI><b>Secret Key</b> - любой набор символов, выбранный вами. Установите, если хотите использовать автоматическое отслеживание платежей.</LI><LI>Другие параметры, если это необходимо</LI></UL>";

$MESS["SWMWP_NUMBER"] = "Номер R кошелька";
$MESS["SWMWP_NUMBER_DESC"] = "В виде буквы R и 12 цифр. Его необходимо взять из подписанного соглашения.";
$MESS["SWMWP_TEST"] = "Тестовый режим";
$MESS["SWMWP_TEST_DESC"] = "В режиме тестирования: 0 - успешный платеж; 1 - не успешный; 2 - около 80% успешных, остальные - не успешные";
$MESS["SWMWP_KEY"] = "Secret Key";
$MESS["SWMWP_KEY_DESC"] = "Устанавливается в настройках сервиса Web Merchant Interface";
$MESS["SWMWP_ORDER_ID"] = "ID заказа";
$MESS["SWMWP_DATE"] = "Дата заказа";
$MESS["SWMWP_SUMMA"] = "Сумма к оплате";
$MESS["SWMWP_URL"] = "Адрес для оповещения";
$MESS["SWMWP_URL_DESC"] = "URL (на веб-сайте продавца), на который будет сервис Web Merchant Interface посылает HTTP POST оповещение о совершении платежа с его детальными реквизитами. URL должен иметь префикс http:// или https://";
$MESS["SWMWP_URL_OK"] = "Адрес при успешной оплате";
$MESS["SWMWP_URL_OK_DESC"] = "URL (на веб-сайте продавца), на который будет переведен интернет-браузер покупателя в случае успешного выполнения платежа в сервисе Web Merchant Interface. URL должен иметь префикс http:// или https://.";
$MESS["SWMWP_URL_ERROR"] = "Адрес при ошибке оплаты";
$MESS["SWMWP_URL_ERROR_DESC"] = "URL (на веб-сайте продавца), на который будет переведен интернет-браузер покупателя в том случае, если платеж в сервисе Web Merchant Interface не был выполнен по каким-то причинам. URL должен иметь префикс http:// или https://.";
$MESS["SWMWP_PHONE"] = "Телефон покупателя";
$MESS["SWMWP_MAIL"] = "Email покупателя";
$MESS["PYM_CHANGE_STATUS_PAY"] = "Автоматически оплачивать заказ при получении успешного статуса оплаты";
$MESS["PYM_CHANGE_STATUS_PAY_DESC"] = "Y - оплачивать, N - не оплачивать.";
$MESS["SWMWP_HASH_ALGO"] = "Алгоритм формирования контрольной подписи";
$MESS["SWMWP_HASH_ALGO_DESC"] = "Возможные варианты: sha256 либо md5(перестаёт работать с 01.09.2014).";
?>