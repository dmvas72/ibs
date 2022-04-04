<?

use Bitrix\Main\Config\Option,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader,
    Bitrix\Main\Application;

$module_id = 'dmvas.shop';

Loc::loadMessages($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
Loc::loadMessages(__FILE__);

#проверка прав доступа к группе у текущего пользователя
if($APPLICATION->GetGroupRight($module_id) < "S")
{
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

if (!Loader::includeModule($module_id))
	return;

$request = Application::getInstance()->getContext()->getRequest();

$aTabs = array(
    array(
        "DIV" => "edit2",
        "TAB" => GetMessage("MAIN_TAB_RIGHTS"),
        "TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")
    ),
);

#saving
if($request->isPost() && $request['Update'] && check_bitrix_sessid())
{
    //или можно использовать AdmSettingsSaveOptions($MODULE_ID, $arOptions);
    foreach ($aTabs as $aTab) {
        foreach ($aTab["OPTIONS"] as $arOption) {
            if(!is_array($arOption)) //Строка с подсветкой. Используется для разделения настроек в одной вкладке
                continue;
            
            if($arOption['note']) //уведомление с подсветкой
                continue;
            
            //или AdmSettingsSaveOptions($MODULE_ID, $arOptions);
            $optionName = $arOption[0];
            
            $optionValue = $request->getPost($optionName);
            
            Option::set($module_id, $optionName, is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
        }
    }
}

#show
$tabControl = new CAdminTabControl("tabControl", $aTabs);

$tabControl->Begin(); ?>
<form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($request["mid"])?>&amp;lang=<?echo LANGUAGE_ID?>">
    
    <?
    
    foreach ($aTabs as $aTab) {
        if($aTab['OPTIONS']){
            $tabControl->BeginNextTab();
            __AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
        }
    }
      
    $tabControl->BeginNextTab();   
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights.php");
      
    $tabControl->Buttons();?>
    
	<input type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
	<input type="reset" name="reset" value="<?=GetMessage("MAIN_RESET")?>">
    <?=bitrix_sessid_post();?>
</form>

<?$tabControl->End();?>