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
    
    public function getResult()
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
            'order' => array('ID' => 'DESC')
        ));
        
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
