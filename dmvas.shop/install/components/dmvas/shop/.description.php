<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc as Loc;

$arComponentDescription = array(
    "NAME" => Loc::getMessage('SHOP_NAME'),
    "DESCRIPTION" => Loc::getMessage('SHOP_DESCRIPTION'),
    "COMPLEX" => "Y",
    "PATH" => array(
        "ID" => "SHOP",
    ),
    "ICON" => "/images/icon.gif",
);
