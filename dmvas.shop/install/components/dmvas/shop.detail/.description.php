<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc as Loc;

$arComponentDescription = array(
    "NAME" => Loc::getMessage('REVIEW_LIST_NAME'),
    "DESCRIPTION" => Loc::getMessage('REVIEW_LIST_DESCRIPTION'),
    "PATH" => array(
        "ID" => "SHOP",
        "CHILD" => array(
            "ID" => "shop_detail",
            "NAME" => Loc::getMessage('REVIEW_LIST_NAME')
        )
    ),
    "ICON" => "/images/icon.gif",
);
