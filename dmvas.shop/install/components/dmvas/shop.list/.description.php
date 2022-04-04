<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc as Loc;

$arComponentDescription = array(
    "NAME" => Loc::getMessage('REVIEW_LIST_NAME'),
    "DESCRIPTION" => Loc::getMessage('REVIEW_LIST_DESCRIPTION'),
    "PATH" => array(
        "ID" => "SHOP",
    ),
    "ICON" => "/images/icon.gif",
);
