<?
global $MESS;
$PathInstall = str_replace("\\", "/", __FILE__);
$PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen("/index.php"));
//IncludeModuleLangFile($PathInstall."/install.php");
 
if(class_exists("poisondrop")) return;
 
Class poisondrop extends CModule
{
    var $MODULE_ID = "poisondrop";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = "Y";
 
    function poisondrop()
    {
        
        
         $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path . "/version.php");

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
            $this->MODULE_NAME = $arModuleVersion["MODULE_NAME"];
            $this->MODULE_DESCRIPTION = $arModuleVersion["MODULE_DESCRIPTION"];
        } else {
            //укажите собственные значения
            $this->MODULE_VERSION = '1.0.0';
            $this->MODULE_VERSION_DATE = '2011-01-01 00:00:00';
            $this->MODULE_NAME = "Модуль PoisonDrop";
            $this->MODULE_DESCRIPTION = "";
        }
        
        
        
       
    }
 
    function DoInstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        $this->InstallFiles();
        RegisterModule("poisondrop");
        
    }
     
    function InstallFiles($arParams = array())
    {        
        return true;
    }
    function UnInstallFiles()
    {
        //DeleteDirFilesEx("/bitrix/components/user/elements/");
        return true;
    }
    
    function InstallEvents()
	{
		return true;
	}
     
    function DoUninstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        $this->UnInstallFiles();
        UnRegisterModule("poisondrop");
      //  $APPLICATION->IncludeAdminFile("Деинсталляция модуля dev_module", $DOCUMENT_ROOT."/bitrix/modules/dev_module/install/unstep1.php");
    }
}
?>