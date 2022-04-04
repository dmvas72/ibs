<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var string $componentPath
 * @var string $componentName
 * @var array $arCurrentValues
 * */

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc as Loc;
use \Bitrix\Main\Application;

if (!Loader::includeModule("dmvas.shop")) {
    throw new \Exception('Не загружены необходимые модули для работы компонента');
}

try {

    $arComponentParameters = array(
        "GROUPS" => array(),
        "PARAMETERS" => array(
            "VARIABLE_ALIASES" => Array(
                "BRAND_ID" => Array("NAME" => GetMessage("DMVAS_SHOP_BRAND_DESC")),
                "MODEL_ID" => Array("NAME" => GetMessage("DMVAS_SHOP_MODEL_DESC")),
                "NOTEBOOK_ID" => Array("NAME" => GetMessage("DMVAS_SHOP_NOTEBOOK_DESC")),
            ),
            "SEF_MODE" => Array(
                "brand" => array(
                    "NAME" => GetMessage("DMVAS_SHOP_LIST_MODEL"),
                    "DEFAULT" => "#BRAND_ID#/",
                    "VARIABLES" => array("BRAND_ID"),
                ),
                "model" => array(
                    "NAME" => GetMessage("DMVAS_SHOP_LIST_MODEL_LAPTOP"),
                    "DEFAULT" => "#BRAND_ID#/#MODEL_ID#/",
                    "VARIABLES" => array("BRAND_ID", "MODEL_ID"),
                ),
                "detail" => array(
                    "NAME" => GetMessage("DMVAS_SHOP_DETAIL"),
                    "DEFAULT" => "detail/#NOTEBOOK_ID#/",
                    "VARIABLES" => array("NOTEBOOK_ID"),
                ),
            ),
        ),
    );
} catch (Main\LoaderException $e) {
    ShowError($e->getMessage());
}
