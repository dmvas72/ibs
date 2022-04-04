<?php

use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Dmvas\Shop\ManufacturerTable,
    Dmvas\Shop\ModelTable,
    Dmvas\Shop\LaptopTable;

//use Bitrix\Sale\Registry;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

Loc::loadMessages(__FILE__);

class ShopList extends \CBitrixComponent
{
    const MODULE_ID = 'dmvas.shop';
    
    public function getParamsSort()
    {
        if($this->arParams["ENTITY_TYPE"] != "LAPTOP")
        {
            $this->arParams["SORT_BY1"] = "ID";
            $this->arParams["SORT_BY2"] = "NAME";
        }
    }
    
    public function getResManufacturer()
    {
        $result = ManufacturerTable::getList(array(
            'select' => array('ID', 'NAME'),
            'order' => array($this->arParams["SORT_BY1"] => $this->arParams["SORT_ORDER1"], $this->arParams["SORT_BY2"] => $this->arParams["SORT_ORDER2"])
        ));
        
        return $result;
    }
    
    public function getResModel()
    {
        $result = ModelTable::getList(array(
            'select' => array('ID', 'NAME'),
            'order' => array($this->arParams["SORT_BY1"] => $this->arParams["SORT_ORDER1"], $this->arParams["SORT_BY2"] => $this->arParams["SORT_ORDER2"])
        ));
        
        return $result;
    }
    
    public function getResLaptop()
    {
        $result = LaptopTable::getList(array(
            'select' => array(
                'ID',
                'NAME',
                'YEAR',
                'PRICE',
                'MODEL_NAME' => 'MODEL.NAME',
                'MANUFACTURER_NAME' => 'MODEL.MANUFACTURER.NAME',
                //'OPTION_NAME' => '\Dmvas\Shop\LaptopOptionUsTable:LAPTOP.OPTION.NAME',
            ),
            'order' => array($this->arParams["SORT_BY1"] => $this->arParams["SORT_ORDER1"], $this->arParams["SORT_BY2"] => $this->arParams["SORT_ORDER2"])
        ));
        
        return $result;
    }
    
    public function getResultEntity()
    {
        switch ($this->arParams["ENTITY_TYPE"]) {
            case "MANUFACTURER":
                $result = $this->getResManufacturer();
                break;
            case "MODEL":
                $result = $this->getResModel();
                break;
            case "LAPTOP":
                $result = $this->getResLaptop();
                break;
        }
        
        return $result;
    }
    
    public function getResult()
    {
        $result = $this->getResultEntity();
        
        $this->arResult['ITEMS'] = $result->fetchAll();
    }
    
    /**
     * проверяет подключение необходимых модулей
     * @throws LoaderException
     */
    protected function checkModules()
    {
        if(!Loader::includeModule(self::MODULE_ID))
            throw new Main\LoadedException(Loc::getMessage("DMVAS_SHOP_MOSULE_NOT_INSTALLED"));
    }
    
    protected function checkAccess()
    {
        global $APPLICATION;
        if($APPLICATION->GetGroupRight(self::MODULE_ID) < "K")
        {
            ShowError(Loc::getMessage("ACCESS_DENIED"));
            return false;
        }
        
        return true;
    }

    public function executeComponent()
	{
        global $USER;
        global $APPLICATION;
        
        try {
            
            $this->includeComponentLang('class.php');
            $this->checkModules();

            if($this->checkAccess())
            {
                $this->getParamsSort();
                $this->getResult();
                $this->includeComponentTemplate();
            }
        }
        catch (\Exception $e)
        {
            ShowError($e->getMessage());
        }
	}
}