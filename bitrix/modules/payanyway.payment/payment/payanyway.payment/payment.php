<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
include(GetLangFileName(dirname(__FILE__) . '/', '/payanyway.php'));

$sOrderID = $GLOBALS['SALE_INPUT_PARAMS']['ORDER']['ID'];
$sOrderID = trim($sOrderID);
$sOrderID = (strlen($ORDER_ID > 0)) ? $ORDER_ID : $sOrderID;
$arOrder = CSaleOrder::GetByID($sOrderID);

$statuses_paid = array(CSalePaySystemAction::GetParamValue('PAID_STATUS'));

// If payment exists than show result
if (in_array($arOrder['STATUS_ID'], $statuses_paid)) {
    $arCurrentStatus = CSaleStatus::GetByID($arOrder['STATUS_ID']);
    echo '<br><strong>' . $arCurrentStatus['NAME'] . '</strong>';
}
else {
// PAW payment form generate here
$URL_RETURN_OK = trim(CSalePaySystemAction::GetParamValue('SUCCESS_URL'));
if ($URL_RETURN_OK == '') {
    $URL_RETURN_OK = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/personal/order/detail/';
}

$URL_RETURN_OK .= $sOrderID . '/';

$URL_RETURN_NO = trim(CSalePaySystemAction::GetParamValue('FAIL_URL'));
if ($URL_RETURN_NO == '') {
    $URL_RETURN_NO = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/personal/order/detail/';
}
$URL_RETURN_NO .= $sOrderID . '/';

$is_test = (trim(CSalePaySystemAction::GetParamValue('PAW_TEST')) != '0');
$is_demo = (trim(CSalePaySystemAction::GetParamValue('PAW_DEMO')) != '0');
$account_id = trim(CSalePaySystemAction::GetParamValue('ACCOUNT_ID'));
$account_code = trim(CSalePaySystemAction::GetParamValue('ACCOUNT_CODE'));

$url_payanyway_pay = $is_demo ? 'https://demo.moneta.ru/assistant.htm' : 'https://moneta.ru/assistant.htm';

// PAW params
$MNT_ID = trim(CSalePaySystemAction::GetParamValue('ACCOUNT_ID'));

// order ID
$MNT_TRANSACTION_ID = $arOrder["ID"];
if (isset($arOrder["ORDER_PAYMENT_ID"])) {
    $MNT_TRANSACTION_ID .= "_" . $arOrder["ORDER_PAYMENT_ID"];
}
else {
    $MNT_TRANSACTION_ID .= "_";
}

// custom number of order account
if (isset($arOrder["ACCOUNT_NUMBER"])) {
    $MNT_TRANSACTION_ID .= "_" . $arOrder["ACCOUNT_NUMBER"];
}
else {
    $MNT_TRANSACTION_ID .= "_";
}

$MNT_TRANSACTION_ID .= "_" . $sOrderID;

$MNT_CURRENCY_CODE = strtoupper(trim($arOrder['CURRENCY']));
$MNT_CURRENCY_CODE = ($MNT_CURRENCY_CODE == 'RUR') ? 'RUB' : $MNT_CURRENCY_CODE;
$MNT_AMOUNT = number_format($arOrder['PRICE'], 2, '.', '');
$MNT_TEST_MODE = ($is_test) ? '1' : '0';

$MNT_SUCCESS_URL = $URL_RETURN_OK;
$MNT_FAIL_URL = $URL_RETURN_NO;

if ($is_test) {
    $MNT_TRANSACTION_ID = $MNT_TRANSACTION_ID . '_TEST_' . time();
}

$MNT_SIGNATURE = md5($MNT_ID . $MNT_TRANSACTION_ID . $MNT_AMOUNT . $MNT_CURRENCY_CODE . $MNT_TEST_MODE . trim(CSalePaySystemAction::GetParamValue('ACCOUNT_CODE')));

$MNT_DESCRIPTION = urlencode(GetMessage('SALE_PAYANYWAY_ORDER_ID') . ' #' . $arOrder['ID']);

// get customer's email
$user_email = '';
$res = CSaleOrderPropsValue::GetOrderProps($arOrder['ID']);
while ($row = $res->Fetch()) {
    if ($row['IS_EMAIL'] == 'Y') {
        $user_email = $row['VALUE'];
    }
}

if (empty($user_email) && ($user = CUser::GetByID($arOrder['USER_ID'])->Fetch())) {
    $user_email = $user['EMAIL'];
}

$paysys = CSalePaySystem::GetByID($arOrder[PAY_SYSTEM_ID]);

// link to pay
$link = $url_payanyway_pay . '?MNT_ID=' . $MNT_ID . '&MNT_TRANSACTION_ID=' . $MNT_TRANSACTION_ID . '&MNT_CURRENCY_CODE=' . $MNT_CURRENCY_CODE . '&MNT_AMOUNT=' . $MNT_AMOUNT . '&MNT_TEST_MODE=' . $MNT_TEST_MODE . '&MNT_SUCCESS_URL = ' . $MNT_SUCCESS_URL . '&MNT_FAIL_URL=' . $MNT_FAIL_URL . '&MNT_SIGNATURE=' . $MNT_SIGNATURE . '&MNT_DESCRIPTION=' . $MNT_DESCRIPTION;
if ($user_email) {
    $link .= '&additionalParameters.ownerLogin=' . $user_email;
}
$link = str_replace("%", "%25", $link);
$headers = "From: " . trim(CSalePaySystemAction::GetParamValue('MAIL_FOR_SEND')) . "\r\n" . 'Content-Type: text/html; charset=UTF-8; format=flowed Content-Transfer-Encoding: 8bit';
$message = str_replace(array('{$order_id}', '{$amount}', '{$link}'), array($arOrder['ID'], $arOrder['PRICE'], $link), file_get_contents(dirname(__FILE__) . '/payanyway_mail.html'));
$mail_subject = $MNT_DESCRIPTION;
if ($user_email && trim(CSalePaySystemAction::GetParamValue('SEND_CUSTOMER')) == '1') {
    @mail($user_email, $mail_subject, $message, $headers);
}
if (trim(CSalePaySystemAction::GetParamValue('SEND_OTHER_EMAIL'))) {
    @mail(trim(CSalePaySystemAction::GetParamValue('SEND_OTHER_EMAIL')), $mail_subject, $message, $headers);
}
?>

<div class="payanywayPayform">
    <p>
        <?php echo GetMessage('SUSP_ACCOUNT_NO') ?>
        <span><?php echo $arOrder['ID'] ?></span>
        <i><?php echo GetMessage('SUSP_ORDER_FROM') . $arOrder['DATE_INSERT'] ?></i>
    </p>
    <p>
        <?php echo GetMessage('SUSP_ORDER_SUM') ?>
        <span><?php echo SaleFormatCurrency($arOrder['PRICE'], $arOrder['CURRENCY']) ?></span>
    </p>
    <span class="payanywayDesc"><?php echo GetMessage('SUSP_DESC') ?></span>
    <form action="<?php echo $url_payanyway_pay ?>" method="post" accept-charset="utf-8">
        <input type="hidden" name="MNT_ID" value="<?php echo $MNT_ID ?>">
        <input type="hidden" name="MNT_TRANSACTION_ID" value="<?php echo $MNT_TRANSACTION_ID ?>">
        <input type="hidden" name="MNT_CURRENCY_CODE" value="<?php echo $MNT_CURRENCY_CODE ?>">
        <input type="hidden" name="MNT_AMOUNT" value="<?php echo $MNT_AMOUNT ?>">
        <input type="hidden" name="MNT_TEST_MODE" value="<?php echo $MNT_TEST_MODE ?>">
        <input type="hidden" name="MNT_SIGNATURE" value="<?php echo $MNT_SIGNATURE ?>">
        <input type="hidden" name="MNT_SUCCESS_URL" value="<?php echo $MNT_SUCCESS_URL ?>">
        <input type="hidden" name="MNT_FAIL_URL" value="<?php echo $MNT_FAIL_URL ?>">
        <input type="hidden" name="followup" value="true">
        <input type="hidden" name="javascriptEnabled" value="true">
        <?php if ($user_email) echo '<input type="hidden" name="additionalParameters.ownerLogin" value="' . $user_email . '">' ?>
        <input type="submit" value="<?php echo GetMessage('SUSP_PAYANYWAY_PAY_BUTTON') ?>">
    </form>
</div>
<style>
    .payanywayPayform {
        border: 2px solid #5cb85c;
        padding: 10px;
    }
    .payanywayPayform P SPAN {
        font-weight: bold;
    }
    .payanywayPayform .payanywayDesc {
        font-size: 11px;
        line-height: 7px;
    }
    .payanywayPayform FORM {
        text-align: center;
    }
    .payanywayPayform INPUT {
        padding: 10px 16px;
        font-size: 18px;
        line-height: 1.33;
        border-radius: 6px;
        color: #fff;
        background-color: #5cb85c;
        font-weight: 400;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        white-space: nowrap;
        border: 0px;
    }
</style>
<?php } ?>