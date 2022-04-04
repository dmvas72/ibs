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

/**
 *  $arResult;
 *  $componentPage;
 */
class ShopComplex extends \CBitrixComponent
{
    const MODULE_ID = 'dmvas.shop';
    
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
    
    public function getResultSefMode()
    {
        global $APPLICATION;
        
        $arDefaultUrlTemplates404 = array(
            "brand" => "#BRAND_ID#/",
            "model" => "#BRAND_ID#/#MODEL_ID#/",
            "detail" => "detail/#NOTEBOOK_ID#/",
        );

        $arDefaultVariableAliases404 = array();

        $arDefaultVariableAliases = array();

        $arComponentVariables = array(
            "BRAND_ID",
            "MODEL_ID",
            "NOTEBOOK_ID",
        );
        
        if($this->arParams["SEF_MODE"] == "Y")
        {
            $arVariables = array();

            $arUrlTemplates = CComponentEngine::makeComponentUrlTemplates($arDefaultUrlTemplates404, $this->arParams["SEF_URL_TEMPLATES"]);
            $arVariableAliases = CComponentEngine::makeComponentVariableAliases($arDefaultVariableAliases404, $this->arParams["VARIABLE_ALIASES"]);

            $engine = new CComponentEngine($this);
            if (CModule::IncludeModule(self::MODULE_ID))
            {
                $engine->addGreedyPart("#BRAND_ID_PATH#");
                $engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
            }
            $componentPage = $engine->guessComponentPath(
                $this->arParams["SEF_FOLDER"],
                $arUrlTemplates,
                $arVariables
            );

            $b404 = false;
            if(!$componentPage)
            {
                $componentPage = "manufacturer";
                $b404 = true;
            }

            if($b404 && CModule::IncludeModule(self::MODULE_ID))
            {
                $folder404 = str_replace("\\", "/", $this->arParams["SEF_FOLDER"]);
                if ($folder404 != "/")
                    $folder404 = "/".trim($folder404, "/ \t\n\r\0\x0B")."/";
                if (mb_substr($folder404, -1) == "/")
                    $folder404 .= "index.php";

                if ($folder404 != $APPLICATION->GetCurPage(true))
                {
                    \Bitrix\Iblock\Component\Tools::process404(
                        ""
                        ,($this->arParams["SET_STATUS_404"] === "Y")
                        ,($this->arParams["SET_STATUS_404"] === "Y")
                        ,($this->arParams["SHOW_404"] === "Y")
                        ,$this->arParams["FILE_404"]
                    );
                }
            }

            CComponentEngine::initComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);

            $arResult = array(
                "FOLDER" => $this->arParams["SEF_FOLDER"],
                "URL_TEMPLATES" => $arUrlTemplates,
                "VARIABLES" => $arVariables,
                "ALIASES" => $arVariableAliases,
            );
        }
        else
        {
            $arVariableAliases = CComponentEngine::makeComponentVariableAliases($arDefaultVariableAliases, $this->arParams["VARIABLE_ALIASES"]);
            CComponentEngine::initComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);

            $componentPage = "";

            if(isset($arVariables["ELEMENT_ID"]) && intval($arVariables["ELEMENT_ID"]) > 0)
                $componentPage = "detail";
            elseif(isset($arVariables["ELEMENT_CODE"]) && $arVariables["ELEMENT_CODE"] <> '')
                $componentPage = "detail";
            elseif(isset($arVariables["SECTION_ID"]) && intval($arVariables["SECTION_ID"]) > 0)
            {
                $componentPage = "model";
            }
            elseif(isset($arVariables["SECTION_CODE"]) && $arVariables["SECTION_CODE"] <> '')
            {
                $componentPage = "model";
            }
            else
                $componentPage = "manufacturer";

            $arResult = array(
                "FOLDER" => "",
                "URL_TEMPLATES" => array(
                    "manufacturer" => htmlspecialcharsbx($APPLICATION->GetCurPage()),
                ),
                "VARIABLES" => $arVariables,
                "ALIASES" => $arVariableAliases
            );
        }
        
        $this->componentPage = $componentPage;
        $this->arResult = $arResult;
    }
    
    public function getResult()
    {
        $this->getResultSefMode();
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
                $this->includeComponentTemplate($this->componentPage); //$this->componentPage
            }
        }
        catch (\Exception $e)
        {
            ShowError($e->getMessage());
        }
	}
}
