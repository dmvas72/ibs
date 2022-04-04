<?php

use Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) return;

Loc::loadMessages(__FILE__);
?>
<form action="<?= $APPLICATION->GetCurPage(); ?>">
	<?= bitrix_sessid_post(); ?>
	<input type="hidden" name="lang" value="<?= LANGUAGE_ID; ?>">
	<input type="hidden" name="id" value="dmvas.shop">
	<input type="hidden" name="install" value="Y">
	<input type="hidden" name="step" value="2">
	<?= CAdminMessage::ShowMessage(Loc::getMessage('MOD_INST_WARN')); ?>
	<p><input type="checkbox" name="deldata" id="deldata" value="Y" checked><label for="deldata"><?= Loc::getMessage('DMVAS_SHOP_INST_SAVE_TABLES'); ?></label></p>
	<input type="submit" name="inst" value="<?= Loc::getMessage('MOD_INSTALL'); ?>">
</form>