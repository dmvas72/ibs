<?
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Config\Configuration;

if (!check_bitrix_sessid())
	return;

#.settings.php
$install_count = Configuration::getInstance()->get('dmvas_module_shop');
$cache_type = Configuration::getInstance()->get('cache');
#.settings.php

#notice
if($ex = $APPLICATION->GetException()) {
	echo CAdminMessage::ShowMessage(array(
		"TYPE" => "ERROR",
		"MESSAGE" => Loc::getMessage("MOD_INST_ERR"),
		"DETAILS" => $ex->GetString(),
		"HTML" => true,
	));
}
else {
	echo CAdminMessage::ShowNote(Loc::getMessage("MOD_INST_OK"));
}

#.settings.php
echo CAdminMessage::ShowMessage(array(
	"MESSAGE" => Loc::getMessage("DMVAS_SHOP_INSTALL_COUNT") . $install_count['install'],
	"TYPE" => "OK",
));

#cache
if(!$cache_type['type'] || $cache_type['type'] == 'none'){
	echo CAdminMessage::ShowMessage(array(
		"MESSAGE" => Loc::getMessage("DMVAS_SHOP_NO_CACHE"),
		"TYPE" => "ERROR")
	);
}
#.settings.php
?>

<form action="<?echo $APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?echo LANGUAGE_ID?>">
	<input type="submit" name="" value="<?echo Loc::getMessage("MOD_BACK")?>">
<form>
