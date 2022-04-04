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

$arEntityType = array(
    "MANUFACTURER"=>GetMessage("DMVAS_SHOP_DESC_MANUFACTURER"),
    "MODEL"=>GetMessage("DMVAS_SHOP_DESC_MODEL"),
    "LAPTOP"=>GetMessage("DMVAS_SHOP_DESC_LAPTOP"),
);

$arSorts = array("ASC"=>GetMessage("DMVAS_SHOP_DESC_ASC"), "DESC"=>GetMessage("DMVAS_SHOP_DESC_DESC"));
$arSortFields = array(
    "ID"=>GetMessage("DMVAS_SHOP_DESC_FID"),
    "NAME"=>GetMessage("DMVAS_SHOP_DESC_FNAME"),
    "YEAR"=>GetMessage("DMVAS_SHOP_DESC_YEAR"),
    "PRICE"=>GetMessage("DMVAS_SHOP_DESC_PRICE")
);

try {

    $arComponentParameters = array(
        "GROUPS" => array(),
        "PARAMETERS" => array(
            "ENTITY_TYPE" => array(
                "PARENT" => "BASE",
                "NAME" => GetMessage("DMVAS_SHOP_DESC_LIST_TYPE"),
                "TYPE" => "LIST",
                "VALUES" => $arEntityType,
                "DEFAULT" => "MANUFACTURER",
                "REFRESH" => "Y",
            ),
            "SORT_BY1" => array(
                "PARENT" => "DATA_SOURCE",
                "NAME" => GetMessage("DMVAS_SHOP_DESC_IBORD1"),
                "TYPE" => "LIST",
                "DEFAULT" => "ID",
                "VALUES" => $arSortFields,
                "ADDITIONAL_VALUES" => "Y",
            ),
            "SORT_ORDER1" => array(
                "PARENT" => "DATA_SOURCE",
                "NAME" => GetMessage("DMVAS_SHOP_DESC_IBBY1"),
                "TYPE" => "LIST",
                "DEFAULT" => "DESC",
                "VALUES" => $arSorts,
                "ADDITIONAL_VALUES" => "Y",
            ),
            "SORT_BY2" => array(
                "PARENT" => "DATA_SOURCE",
                "NAME" => GetMessage("DMVAS_SHOP_DESC_IBORD2"),
                "TYPE" => "LIST",
                "DEFAULT" => "NAME",
                "VALUES" => $arSortFields,
                "ADDITIONAL_VALUES" => "Y",
            ),
            "SORT_ORDER2" => array(
                "PARENT" => "DATA_SOURCE",
                "NAME" => GetMessage("DMVAS_SHOP_DESC_IBBY2"),
                "TYPE" => "LIST",
                "DEFAULT" => "ASC",
                "VALUES" => $arSorts,
                "ADDITIONAL_VALUES" => "Y",
            ),
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
