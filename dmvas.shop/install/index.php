<?php

//use Bitrix\Main\Authentication\Application;
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader,
	Bitrix\Main\Application,
	Bitrix\Main\EventManager,
	Bitrix\Main\IO\Directory,
	Bitrix\Main\IO\InvalidPathException,
	Bitrix\Main\Config\Configuration,
	Bitrix\Main\ModuleManager,
    Bitrix\Main\Config\Option;
use Bitrix\Main\Web\WebPacker\Module;
use Bitrix\Main\Entity\Base;
use \Dmvas\Shop\ManufacturerTable,
    \Dmvas\Shop\ModelTable,
    \Dmvas\Shop\LaptopTable,
    \Dmvas\Shop\OptionTable,
    \Dmvas\Shop\LaptopOptionUsTable;

Loc::loadMessages(__FILE__);

class dmvas_shop extends CModule
{
	var $exclusionAdminFiles;

	function __construct()
	{
		$arModuleVersion = array();
		include(__DIR__."/version.php");

		$this->exclusionAdminFiles = array(
			'..',
			'.',
			'menu.php',
			'operation_description.php',
			'task_description.php'
		);

		$this->MODULE_ID = "dmvas.shop";
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = Loc::getMessage("DMVAS_SHOP_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("DMVAS_SHOP_MODULE_DESC");

		$this->PARTNER_NAME = Loc::getMessage("DMVAS_SHOP_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("DMVAS_SHOP_PARTNER_URI");

		$this->PARTNER_SUPER_ADMIN_GROUP_RIGHTS = "Y";
		$this->MODULE_GROUP_RIGHTS = "Y";
	}

	function DoInstall()
	{
		global $APPLICATION;
        
        $context = Application::getInstance()->getContext();
		$request = $context->getRequest();
        
		if($this->isVersionD7())
        {
            
			if ($request["step"] < 2)
				$APPLICATION->IncludeAdminFile(Loc::getMessage("DMVAS_SHOP_INSTALL_TITLE"), __DIR__ . "/step1.php");
			elseif ($request["step"] == 2) {
                
                ModuleManager::registerModule($this->MODULE_ID);
                
                if ($request["deldata"] == "Y")
                    $this->UnInstallDB();
                
				if ($this->InstallDB()) {
					$this->InstallEvents();
					$this->InstallFiles();

                    $this->addCntConfiguration("install");
				}
                
				$APPLICATION->IncludeAdminFile(Loc::getMessage("DMVAS_SHOP_INSTALL_TITLE"), __DIR__ . "/step2.php");
			}
		}
		else {
			$APPLICATION->ThrowException(Loc::getMessage("DMVAS_SHOP_INSTALL_ERROR_VERSION"));
		}
	}

	function DoUninstall()
	{
		global $APPLICATION;

		$context = Application::getInstance()->getContext();
		$request = $context->getRequest();

		if($request["step"] < 2) {
			$APPLICATION->IncludeAdminFile(Loc::getMessage("DMVAS_SHOP_UNINSTALL_TITLE"), __DIR__ . "/unstep1.php");
		}
		elseif($request["step"] == 2) {
			$this->UnInstallEvents();

			if ($request["savefiles"] != "Y")
				$this->UnInstallFiles();

			if ($request["savedata"] != "Y")
				$this->UnInstallDB();

			ModuleManager::unRegisterModule($this->MODULE_ID);
            
            $this->addCntConfiguration("uninstall");

			$APPLICATION->IncludeAdminFile(Loc::getMessage("DMVAS_SHOP_UNINSTALL_TITLE"), __DIR__ . "/unstep2.php");
		}
	}

	public function isVersionD7(){
		return CheckVersion(ModuleManager::getVersion('main'), '14.00.00');
	}

	public function GetPath($notDocumentRoot=false){
		if($notDocumentRoot)
			return str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__));
		else
			return dirname(__DIR__);
	}
    
    public function addTestData(){
        $arrManufacturers = array("Lenovo", "Asus", "Huawei", "IRBIS", "HP");
        $arrModels = array("IdeaPad", "TUF Gaming A15", "MateBook", "NB275", "15s");
        $arrLaptops = array(
            array(
                "NAME" => "Ноутбук Lenovo IdeaPad S145-15IIL",
                "YEAR" => 2020,
                "PRICE" => 32200.80
            ),
            array(
                "NAME" => "Ноутбук ASUS TUF Gaming A15 FX506IC-HN025W",
                "YEAR" => 2016,
                "PRICE" => 55200.00
            ),
            array(
                "NAME" => "Ноутбук HUAWEI MateBook 16",
                "YEAR" => 2021,
                "PRICE" => 85000
            ),
            array(
                "NAME" => "Ноутбук IRBIS NB275",
                "YEAR" => 2018,
                "PRICE" => 52200.20
            ),
            array(
                "NAME" => "Ноутбук HP 15s-eq1280ur",
                "YEAR" => 2017,
                "PRICE" => 18400.00
            ),
            array(
                "NAME" => "Ноутбук HP 15s",
                "YEAR" => 2018,
                "PRICE" => 20500,
                "MODEL_ID" => 5
            ),
            array(
                "NAME" => "Ноутбук HUAWEI MateBook 17",
                "YEAR" => 2021,
                "PRICE" => 87000.00,
                "MODEL_ID" => 3
            ),
            array(
                "NAME" => "Ноутбук HUAWEI MateBook 18",
                "YEAR" => 2022,
                "PRICE" => 90000.00,
                "MODEL_ID" => 3
            ),
            array(
                "NAME" => "Ноутбук ASUS TUF Gaming A15 FX700IC",
                "YEAR" => 2018,
                "PRICE" => 70000.00,
                "MODEL_ID" => 2
            ),
            array(
                "NAME" => "Ноутбук Lenovo IdeaPad S200-15IIL",
                "YEAR" => 2018,
                "PRICE" => 35200.00,
                "MODEL_ID" => 1
            ),
        );
        $arrOptions = array("Windows", "Linux", "MacOS", "Оптический привод", "Встроенный микрофон", "Сканер отпечатка пальца", "Веб-камера", "Порт Ethernet", "Игровой ноутбук", "Цифровой блок клавиатуры");
        
        foreach($arrManufacturers as $itemManufacturer) {
            $res = ManufacturerTable::add(array("NAME" => $itemManufacturer));
        }
        
        foreach($arrModels as $kModel => $itemModel) {
            $res = ModelTable::add(array(
                "NAME" => $itemModel,
                "MANUFACTURER_ID" => $kModel+1
            ));
        }
        
        foreach($arrLaptops as $kLaptop => $arrLaptop) {
            $model_id = (!isset($arrLaptop["MODEL_ID"])) ? $kLaptop+1 : $arrLaptop["MODEL_ID"];
            $res = LaptopTable::add(array(
                "NAME" => $arrLaptop["NAME"],
                "YEAR" => $arrLaptop["YEAR"],
                "PRICE" => $arrLaptop["PRICE"],
                "MODEL_ID" => $model_id
            ));
        }
        
        foreach($arrOptions as $kOption => $itemOption) {
            $res = OptionTable::add(array(
                "NAME" => $itemOption,
            ));
        }
        
        /**
         * Рандомное заполнение таблицы LaptopOptionUsTable
         */
        foreach($arrLaptops as $kLaptop => $arrLaptop)
        {
            $randKeys = array_rand($arrOptions, rand(1, count($arrOptions)));//рандом ключей массива опций
            foreach($randKeys as $iKey)
            {
                $res = LaptopOptionUsTable::add(array(
                    "LAPTOP_ID" => $kLaptop+1,
                    "OPTION_ID" => $iKey+1,
                ));
            }
        }
    }
    
    /*
     * Права доступа для модуля
     */
    function GetModuleRightList()
    {
        return array(
            "reference_id" => array("D", "K", "S", "W"),
            "reference" => array(
                "[D] ".Loc::getMessage("DMVAS_SHOP_DENIED"),
                "[K] ".Loc::getMessage("DMVAS_SHOP_READ_COMPONENT"),
                "[S] ".Loc::getMessage("DMVAS_SHOP_WRITE_SETTINGS"),
                "[W] ".Loc::getMessage("DMVAS_SHOP_FULL"))
        );
    }
    
    function InstallDB()
    {
        Loader::includeModule($this->MODULE_ID);
        
        if(!Application::getConnection(ManufacturerTable::getConnectionName())->isTableExists(
            Base::getInstance('\Dmvas\Shop\ManufacturerTable')->getDBTableName()
            )
        )
        {
            Base::getInstance('\Dmvas\Shop\ManufacturerTable')->createDbTable();
        }
        
        if(!Application::getConnection(ModelTable::getConnectionName())->isTableExists(
            Base::getInstance('\Dmvas\Shop\ModelTable')->getDBTableName()
            )
        )
        {
            Base::getInstance('\Dmvas\Shop\ModelTable')->createDbTable();
        }
        
        if(!Application::getConnection(LaptopTable::getConnectionName())->isTableExists(
            Base::getInstance('\Dmvas\Shop\LaptopTable')->getDBTableName()
            )
        )
        {
            Base::getInstance('\Dmvas\Shop\LaptopTable')->createDbTable();
        }
        
        if(!Application::getConnection(OptionTable::getConnectionName())->isTableExists(
            Base::getInstance('\Dmvas\Shop\OptionTable')->getDBTableName()
            )
        )
        {
            Base::getInstance('\Dmvas\Shop\OptionTable')->createDbTable();
        }
        
        if(!Application::getConnection(LaptopOptionUsTable::getConnectionName())->isTableExists(
            Base::getInstance('\Dmvas\Shop\LaptopOptionUsTable')->getDBTableName()
            )
        )
        {
            Base::getInstance('\Dmvas\Shop\LaptopOptionUsTable')->createDbTable();
        }
        
        $this->addTestData();
        
        return true;
    }
    
    function UnInstallDB()
    {
        Loader::includeModule($this->MODULE_ID);
        
        Application::getConnection(ManufacturerTable::getConnectionName())->
            queryExecute('drop table if exists '.Base::getInstance('\Dmvas\Shop\ManufacturerTable')->getDBTableName());
        
        Application::getConnection(ModelTable::getConnectionName())->
            queryExecute('drop table if exists '.Base::getInstance('\Dmvas\Shop\ModelTable')->getDBTableName());
        
        Application::getConnection(LaptopTable::getConnectionName())->
            queryExecute('drop table if exists '.Base::getInstance('\Dmvas\Shop\LaptopTable')->getDBTableName());
        
        Application::getConnection(OptionTable::getConnectionName())->
            queryExecute('drop table if exists '.Base::getInstance('\Dmvas\Shop\OptionTable')->getDBTableName());
        
        Application::getConnection(LaptopOptionUsTable::getConnectionName())->
            queryExecute('drop table if exists '.Base::getInstance('\Dmvas\Shop\LaptopOptionUsTable')->getDBTableName());
        
        Option::delete($this->MODULE_ID);
    }
    
    public function addCntConfiguration($param){
        #.settings.php
        $configuration = Configuration::getInstance();
        $dmvas_module_shop = $configuration->get("dmvas_module_shop");
        $dmvas_module_shop[$param] = $dmvas_module_shop[$param] + 1;
        $configuration->add("dmvas_module_shop", $dmvas_module_shop);
        $configuration->saveConfiguration();
    }

	function InstallEvents()
	{
		EventManager::getInstance()->registerEventHandler($this->MODULE_ID, 'DmvasShopList', $this->MODULE_ID, '\Dmvas\Shop\Event', 'eventHandler');
	}

	function UnInstallEvents()
	{
		EventManager::getInstance()->unRegisterEventHandler($this->MODULE_ID, 'DmvasShopList', $this->MODULE_ID, '\Dmvas\Shop\Event', 'eventHandler');
	}

	function InstallFiles()
	{
        $pathComponent = $this->GetPath() . '/install/components';
		if (Directory::isDirectoryExists($pathComponent)) {
            CopyDirFiles($pathComponent, $_SERVER['DOCUMENT_ROOT'] . "/local/components", true, true);
        }
        else
            throw new InvalidPathException($pathComponent);
        
        /*$pathAdmin = $this->GetPath() . '/install/admin';
		if (Directory::isDirectoryExists($pathAdmin)) {
			CopyDirFiles($pathAdmin, $_SERVER['DOCUMENT_ROOT'] . "/local/admin");
		}
        else
            throw new InvalidPathException($pathAdmin);*/
		return true;
	}

	function UnInstallFiles()
	{
		Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . '/local/components/' . $this->PARTNER_NAME);

		if (Directory::isDirectoryExists($path = $this->GetPath() . '/install/admin')) {
			DeleteDirFiles(/*$_SERVER["DOCUMENT_ROOT"] . */$this->GetPath() . '/install/admin', $_SERVER["DOCUMENT_ROOT"] . "/local/admin");
		}
        
		return true;
	}
}