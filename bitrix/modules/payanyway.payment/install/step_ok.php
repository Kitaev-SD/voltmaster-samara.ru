<?php
/**
 * Resultinstall page
 */
if (!check_bitrix_sessid()) {
	return;
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/payanyway.payment/prolog.php');

global $errors;

if ($errors === false || !CModule::IncludeModule('payanyway.payment')) {
	echo CAdminMessage::ShowNote(GetMessage('PAYANYWAY.PAYMENT_INSTALL_MESSAGE'));
} else {
	for ($i = 0; $i < count($errors); $i++) {
		$alErrors .= $errors[$i] . '<br>';
	}
	echo CAdminMessage::ShowMessage(Array('TYPE' => 'ERROR', 'MESSAGE' => GetMessage('MOD_INST_ERR'), 'DETAILS' => $alErrors, 'HTML' => true));
};

?>
<form action="<?= $APPLICATION->GetCurPage() ?>">
	<input type="hidden" name="lang" value="<?= LANG ?>">
	<input type="submit" name="" value="<?= GetMessage('MOD_BACK') ?>">
<?php if ($errors === false): ?>
	<input type="button" onclick="document.location='/bitrix/admin/settings.php?lang=<?= LANGUAGE_ID ?>&mid_menu=1&mid=payanyway.payment'" value="<?= GetMessage('PAYANYWAY.PAYMENT_BTN_HELP')?>">
<?php endif; ?>
</form>