<?
IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/modules/main/options.php');
IncludeModuleLangFile(__FILE__);

CModule::IncludeModule('iblock');
$aTabs = array(
    array('DIV' => 'edit1',
        'TAB' => GetMessage('MAIN_TAB_SET'),
        'ICON' => 'shopolia_images_settings',
        'TITLE' => GetMessage('MAIN_TAB_TITLE_SET')
    )
);

$tabControl = new CAdminTabControl('tabControl', $aTabs);

// Сохранение настроек модуля
if ($REQUEST_METHOD === 'POST' AND strlen($Update.$Apply.$RestoreDefaults)>0 AND check_bitrix_sessid()) {
    $arErrors = array();

    if (count($arErrors) !== 0) {
        echo ShowError(implode('<br>', $arErrors));
    } else {
        // Сохранение настроек с первой вкладки
        if (strlen($RestoreDefaults)>0) {
            COption::RemoveOption('capybara.seoparsercsv');
        } else {
            COption::SetOptionString('capybara.seoparsercsv', 'capybara_parser_IBLOCK', $_REQUEST['capybara_parser_IBLOCK']);
            COption::SetOptionString('capybara.seoparsercsv', 'capybara_parser_PATH', $_REQUEST['capybara_parser_PATH']);
            COption::SetOptionString('capybara.seoparsercsv', 'capybara_parser_PROPCODE', $_REQUEST['capybara_parser_PROPCODE']);

        }
        if (strlen($Update)>0 && strlen($_REQUEST['back_url_settings'])>0) {
            LocalRedirect($_REQUEST['back_url_settings']);
        }
        else {
            LocalRedirect($APPLICATION->GetCurPage().'?mid='.urlencode($mid).'&lang='.urlencode(LANGUAGE_ID).'&back_url_settings='.urlencode($_REQUEST['back_url_settings']).'&'.$tabControl->ActiveTabParam());
        }
    }
}

$tabControl->Begin();
?>

<form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>"><?
    $tabControl->BeginNextTab();
    ?>
    <tr class="heading">
        <td colspan="2"><?=GetMessage('CAPYBARA_PARSER_PATH_1')?></td>
    </tr>
    <tr>
        <td width="40%" class="adm-detail-content-cell-l"><?=GetMessage('CAPYBARA_PARSER_IBLOCK')?>:</td>
        <td width="60%" class="adm-detail-content-cell-r">
            <input type="text" name="capybara_parser_IBLOCK" value="<?=COption::GetOptionString('capybara.seoparsercsv', 'capybara_parser_IBLOCK')?>" size="5">
        </td>
    </tr>
    <tr>
        <td width="40%" class="adm-detail-content-cell-l"><?=GetMessage('CAPYBARA_PARSER_PATH')?>:</td>
        <td width="60%" class="adm-detail-content-cell-r">
            <input type="text" name="capybara_parser_PATH" value="<?=COption::GetOptionString('capybara.seoparsercsv', 'capybara_parser_PATH')?>" size="70">
        </td>
    </tr>
        <tr>
        <td width="40%" class="adm-detail-content-cell-l"><?=GetMessage('CAPYBARA_PARSER_PROPCODE')?>:</td>
        <td width="60%" class="adm-detail-content-cell-r">
            <input type="text" name="capybara_parser_PROPCODE" value="<?=COption::GetOptionString('capybara.seoparsercsv', 'capybara_parser_PROPNAME')?>" size="70">
        </td>
    </tr>

    <?$tabControl->Buttons();?>
    <input type="submit" name="Update" value="<?=GetMessage('MAIN_SAVE')?>" title="<?=GetMessage('MAIN_OPT_SAVE_TITLE')?>">
    <input type="submit" name="Apply" value="<?=GetMessage('MAIN_OPT_APPLY')?>" title="<?=GetMessage('MAIN_OPT_APPLY_TITLE')?>">
    <?if(strlen($_REQUEST['back_url_settings'])>0):?>
        <input type="button" name="Cancel" value="<?=GetMessage('MAIN_OPT_CANCEL')?>" title="<?=GetMessage('MAIN_OPT_CANCEL_TITLE')?>" onclick="window.location='<?echo htmlspecialchars(CUtil::addslashes($_REQUEST['back_url_settings']))?>'">
        <input type="hidden" name="back_url_settings" value="<?=htmlspecialchars($_REQUEST['back_url_settings'])?>">
    <?endif?>
    <input type="submit" name="RestoreDefaults" title="<?echo GetMessage('MAIN_HINT_RESTORE_DEFAULTS')?>" OnClick="confirm('<?echo AddSlashes(GetMessage('MAIN_HINT_RESTORE_DEFAULTS_WARNING'))?>')" value="<?echo GetMessage('MAIN_RESTORE_DEFAULTS')?>">
    <?=bitrix_sessid_post();?>
    <?$tabControl->End();?>
</form>