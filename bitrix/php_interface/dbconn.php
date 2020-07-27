<?

$remote_user = $_SERVER["REMOTE_USER"] 
? $_SERVER["REMOTE_USER"] : $_SERVER["REDIRECT_REMOTE_USER"];
$strTmp = base64_decode(substr($remote_user,6));
if ($strTmp) {
    list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', $strTmp);
}

require_once __DIR__ . '/../../../settings/.vars.php';
define("DBPersistent", false);
define('PRICE_TYPE','Розничная');

define("BX_USE_MYSQLI", true);
$DBType = "mysql";
$DBHost = POISONDBHOST;
$DBLogin = POISONDBLOGIN;
$DBPassword = POISONDBPASSWORD;
$DBName = POISONDBNAME;

define("BX_CACHE_TYPE", "files");
define("BX_CACHE_SID", $_SERVER["DOCUMENT_ROOT"]."#01");
//define("BX_MEMCACHE_HOST", "unix:///tmp/memcached.sock");
//define("BX_MEMCACHE_PORT", "0");

$DBDebug = true;
$DBDebugToFile = false;

@set_time_limit(3600);

define("DELAY_DB_CONNECT", true);
define("CACHED_b_file", 3600);
define("CACHED_b_file_bucket_size", 10);
define("CACHED_b_lang", 3600);
define("CACHED_b_option", 3600);
define("CACHED_b_lang_domain", 3600);
define("CACHED_b_site_template", 3600);
define("CACHED_b_event", 3600);
define("CACHED_b_agent", 3660);
define("CACHED_menu", 3600);

if(!(defined("CHK_EVENT") && CHK_EVENT===true)) {
	define("BX_CRONTAB_SUPPORT", true);
}  

define("BX_UTF", true);
define("BX_FILE_PERMISSIONS", 0644);
define("BX_DIR_PERMISSIONS", 0775);
//define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");

@umask(~BX_DIR_PERMISSIONS);
//@ini_set("memory_limit", "512M");
define("BX_DISABLE_INDEX_PAGE", true);

date_default_timezone_set("Europe/Moscow");
?>