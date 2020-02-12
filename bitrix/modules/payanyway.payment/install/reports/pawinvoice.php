<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/xml; charset=<? echo LANG_CHARSET ?>"/>
</head>
<body>

<?php

$orderId = isset($_GET['ORDER_ID']) ? $_GET['ORDER_ID'] : $arOrder['ID'];

CSalePaySystemAction::InitParamArrays($arOrder, $orderId);

$MNT_ID = 0;
$MNT_TEST_MODE = "0";
$integrityCode = "";

if (isset($arOrder['PAY_SYSTEM_ID'])) {
    $paw = CSalePaySystemAction::GetByID($arOrder['PAY_SYSTEM_ID']);
	if (isset($paw['PARAMS'])) {
		$pawParams = @unserialize($paw['PARAMS']);
		if (isset($pawParams['ACCOUNT_ID']['VALUE']) && isset($pawParams['ACCOUNT_CODE']['VALUE']) && isset($pawParams['PAW_TEST']['VALUE'])) {
			$MNT_ID = $pawParams['ACCOUNT_ID']['VALUE'];
			$integrityCode = $pawParams['ACCOUNT_CODE']['VALUE'];
			$MNT_TEST_MODE = $pawParams['PAW_TEST']['VALUE'] == "1" ? "1" : "0";
		}
	}
}

$MNT_TEST_MODE_OLD = CSalePaySystemAction::GetParamValue("PAW_TEST", "0") == "1" ? "1" : "0";

$MNT_PAYMENT_SERVER = "www.payanyway.ru";
$action = "https://" . $MNT_PAYMENT_SERVER . "/assistant.htm";

$MNT_ID = ($MNT_ID) ? $MNT_ID : trim( CSalePaySystemAction::GetParamValue("ACCOUNT_ID", 0) );
$integrityCode = ($integrityCode) ? $integrityCode : CSalePaySystemAction::GetParamValue("ACCOUNT_CODE", "");
$MNT_TEST_MODE = ($MNT_TEST_MODE) ? $MNT_TEST_MODE : $MNT_TEST_MODE_OLD;

$MNT_TRANSACTION_ID = $orderId;
$MNT_CURRENCY_CODE = $arOrder['CURRENCY'];
$MNT_AMOUNT = number_format($arOrder['PRICE'], 2, ".", "");

$MNT_SIGNATURE = md5($MNT_ID . $MNT_TRANSACTION_ID . $MNT_AMOUNT . $MNT_CURRENCY_CODE . $MNT_TEST_MODE . $integrityCode);

$host = COption::GetOptionString("main", "server_name", $_SERVER["HTTP_HOST"]);
if($host == "") $host = $_SERVER["HTTP_HOST"];
$host = $_SERVER['HTTPS'] == 'on' ? 'https://'.$host : 'http://'.$host;

?>
<title>PayAnyWay %D1%81%D1%81%D1%8B%D0%BB%D0%BA%D0%B0:</title>

<p>%D0%97%D0%B0%D0%BA%D0%B0%D0%B7+%D0%BD%D0%BE%D0%BC%D0%B5%D1%80: <b><?= $MNT_TRANSACTION_ID?></b></p>
<p>%D0%A1%D1%83%D0%BC%D0%BC%D0%B0+%D0%B7%D0%B0%D0%BA%D0%B0%D0%B7%D0%B0: <b><?= $MNT_AMOUNT?> <?= $MNT_CURRENCY_CODE?></b></p>

<br/>
<p>%D0%9A%D0%BD%D0%BE%D0%BF%D0%BA%D0%B0 PayAnyWay %D0%B4%D0%BB%D1%8F+%D0%BE%D0%BF%D0%BB%D0%B0%D1%82%D1%8B:</p>
<form action="<?= $action?>" method="post" accept-charset="utf-8">
	<font class="tablebodytext">
		<input type="hidden" name="MNT_ID" value="<?= $MNT_ID?>">
		<input type="hidden" name="MNT_TRANSACTION_ID" value="<?= $MNT_TRANSACTION_ID?>">
		<input type="hidden" name="MNT_CURRENCY_CODE" value="<?= $MNT_CURRENCY_CODE?>">
		<input type="hidden" name="MNT_AMOUNT" value="<?= $MNT_AMOUNT?>">
		<input type="hidden" name="MNT_TEST_MODE" value="<?= $MNT_TEST_MODE?>">
		<input type="hidden" name="MNT_SIGNATURE" value="<?= $MNT_SIGNATURE?>">
		<input type="hidden" name="MNT_DESCRIPTION" value="%D0%97%D0%B0%D0%BA%D0%B0%D0%B7%20%D0%BD%D0%BE%D0%BC%D0%B5%D1%80%20#<?= $MNT_TRANSACTION_ID?>">
		<input type="hidden" name="paymentSystem" value="<?= $payment_system?>">
		<input type="hidden" name="MNT_SUCCESS_URL" value="<?= $host . "/personal/order/"?>">
		<input type="hidden" name="MNT_FAIL_URL" value="<?= $host. "/personal/order/"?>">

		<? foreach($extraParameters as $name=>$value):?>
		    <input type="hidden" name="<?= $name?>" value="<?= $value?>">
		<?endforeach;?>
			
		<? if ($unit_id):?>
		    <input type="hidden" name="paymentSystem.unitId" value="<?= $unit_id?>">
		<? endif;?>
			
		<? if ($account_id):?>
		    <input type="hidden" name="paymentSystem.accountId" value="<?= $account_id?>">
		<? endif;?>
			
		<? if ($payment_system !== 'payanyway'):?>
		    <input type="hidden" name="followup" value="true">
		    <input type="hidden" name="javascriptEnabled" value="true">
		<? endif;?>

		<input type="submit" value="%D0%9E%D0%BF%D0%BB%D0%B0%D1%82%D0%B8%D1%82%D1%8C">
	</font>
</form>

<br/>
<p>%D0%A1%D1%81%D1%8B%D0%BB%D0%BA%D0%B0 PayAnyWay %D0%B4%D0%BB%D1%8F+%D0%BE%D0%BF%D0%BB%D0%B0%D1%82%D1%8B:</p>
<a href="<?= $action?>?MNT_ID=<?= $MNT_ID?>&MNT_TRANSACTION_ID=<?= $MNT_TRANSACTION_ID?>&MNT_CURRENCY_CODE=<?= $MNT_CURRENCY_CODE?>&MNT_AMOUNT=<?= $MNT_AMOUNT?>&MNT_TEST_MODE=<?= $MNT_TEST_MODE?>&MNT_SIGNATURE=<?= $MNT_SIGNATURE?>&MNT_DESCRIPTION=%D0%97%D0%B0%D0%BA%D0%B0%D0%B7%20%D0%BD%D0%BE%D0%BC%D0%B5%D1%80%20<?= $MNT_TRANSACTION_ID?>&paymentSystem=<?= $payment_system?>&MNT_SUCCESS_URL=<?= $host . "/personal/order/"?>&MNT_FAIL_URL=<?= $host. "/personal/order/"?><? foreach($extraParameters as $name=>$value):?>&<?= $name?>=<?= $value?><?endforeach;?><? if ($unit_id):?>&paymentSystem.unitId=<?= $unit_id?><? endif;?><? if ($account_id):?>&paymentSystem.accountId=<?= $account_id?>&followup=true&javascriptEnabled=true">
  <?= $action?>?MNT_ID=<?= $MNT_ID?>&MNT_TRANSACTION_ID=<?= $MNT_TRANSACTION_ID?>&MNT_CURRENCY_CODE=<?= $MNT_CURRENCY_CODE?>&MNT_AMOUNT=<?= $MNT_AMOUNT?>&MNT_TEST_MODE=<?= $MNT_TEST_MODE?>&MNT_SIGNATURE=<?= $MNT_SIGNATURE?>&MNT_DESCRIPTION=%D0%97%D0%B0%D0%BA%D0%B0%D0%B7%20%D0%BD%D0%BE%D0%BC%D0%B5%D1%80%20<?= $MNT_TRANSACTION_ID?>&paymentSystem=<?= $payment_system?>&MNT_SUCCESS_URL=<?= $host . "/personal/order/"?>&MNT_FAIL_URL=<?= $host. "/personal/order/"?><? foreach($extraParameters as $name=>$value):?>&<?= $name?>=<?= $value?><?endforeach;?><? if ($unit_id):?>&paymentSystem.unitId=<?= $unit_id?><? endif;?><? if ($account_id):?>&paymentSystem.accountId=<?= $account_id?>&followup=true&javascriptEnabled=true
</a>

</body>
</html>