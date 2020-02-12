<?php
/**
 * Options PayAnyWay module
 */
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	exit();
}
include(GetLangFileName(dirname(__FILE__) . '/', '/payanyway.php'));

$psTitle = 'PayAnyWay';
$psDescription = GetMessage('SALE_PAYANYWAY_DESCRIPTION');

$statuses = CSaleStatus::GetList();
$statuses_select = array();
while ($s = $statuses->Fetch()) {
	$statuses_select[$s['ID']] = array(
		'NAME' => $s['NAME'],
		'VALUE' => $s['ID'],
	);
}

$taxes = array(
	'none' => array('NAME' => GetMessage('SALE_PAYANYWAY_TAX_NONE')),
	'vat0' => array('NAME' => GetMessage('SALE_PAYANYWAY_TAX_0')), 
	'vat10' => array('NAME' => GetMessage('SALE_PAYANYWAY_TAX_10')), 
	'vat18' => array('NAME' => GetMessage('SALE_PAYANYWAY_TAX_18')), 
	'vat110' => array('NAME' => GetMessage('SALE_PAYANYWAY_TAX_110')), 
	'vat118' => array('NAME' => GetMessage('SALE_PAYANYWAY_TAX_118')),
);

$arPSCorrespondence = array(
	'PAW_DEMO' => array(
		'NAME' => GetMessage('SALE_PAYANYWAY_DEMO_MODE'),
		'DESCR' => GetMessage('SALE_PAYANYWAY_DEMO_MODE_DESC'),
		'VALUE' => array(
			'1' => array(
				'NAME' => GetMessage('SALE_PAYANYWAY_DEMO_MODE'),
				'VALUE' => '1',
				),
			'0' => array(
				'NAME' => GetMessage('SALE_PAYANYWAY_WORK_MODE'),
				'VALUE' => '0',
				),
			),
		'TYPE' => 'SELECT',
		'DEFAULT' => '0',
		'SORT' => '1',
	),
	'PAW_TEST' => array(
		'NAME' => GetMessage('SALE_PAYANYWAY_TEST_MODE'),
		'DESCR' => GetMessage('SALE_PAYANYWAY_TEST_MODE_DESC'),
		'VALUE' => array(
			'1' => array(
				'NAME' => GetMessage('SALE_PAYANYWAY_TEST_MODE'),
				'VALUE' => '1',
				),
			'0' => array(
				'NAME' => GetMessage('SALE_PAYANYWAY_WORK_MODE'),
				'VALUE' => '0',
				),
			),
		'TYPE' => 'SELECT',
		'DEFAULT' => '0',
		'SORT' => '1',
	),
	'ACCOUNT_ID' => array(
		'NAME' => GetMessage('SALE_PAYANYWAY_ACCOUNT_ID'),
		'DESCR' => GetMessage('SALE_PAYANYWAY_ACCOUNT_ID_DESC'),
		'VALUE' => '',
		'TYPE' => '',
		'GROUP' => GetMessage('SALE_PAYANYWAY_G_MAIN'),
		'DEFAULT' => '',
		'SORT' => '2',
	),
	'ACCOUNT_CODE' => array(
		'NAME' => GetMessage('SALE_PAYANYWAY_CODE'),
		'DESCR' => GetMessage('SALE_PAYANYWAY_CODE_DESC'),
		'VALUE' => '',
		'TYPE' => '',
		'GROUP' => GetMessage('SALE_PAYANYWAY_G_MAIN'),
		'DEFAULT' => '',
		'SORT' => '3',
	),
	'SUCCESS_URL' => array(
		'NAME' => GetMessage('SALE_PAYANYWAY_SUCCESS_URL'),
		'DESCR' => GetMessage('SALE_PAYANYWAY_SUCCESS_URL_DESC'),
		'VALUE' => '',
		'TYPE' => '',
		'GROUP' => GetMessage('SALE_PAYANYWAY_G_MAIN'),
		'DEFAULT' => '',
		'SORT' => '5',
	),
	'FAIL_URL' => array(
		'NAME' => GetMessage('SALE_PAYANYWAY_FAIL_URL'),
		'DESCR' => GetMessage('SALE_PAYANYWAY_FAIL_URL_DESC'),
		'VALUE' => '',
		'TYPE' => '',
		'GROUP' => GetMessage('SALE_PAYANYWAY_G_MAIN'),
		'DEFAULT' => '',
		'SORT' => '6',
	),
	
	'PAID_STATUS' => array(
		'NAME' => GetMessage('SALE_PAYANYWAY_PAID_STATUS'),
		'DESCR' => GetMessage('SALE_PAYANYWAY_PAID_STATUS_DESC'),
		'VALUE' => $statuses_select,
		'TYPE' => 'SELECT',
		'GROUP' => GetMessage('SALE_PAYANYWAY_G_STATUSES'),
		'DEFAULT' => '',
		'SORT' => '11',
	),
	'SHIPPING_TAX' => array(
		'NAME' => GetMessage('SALE_PAYANYWAY_SHIPPING_TAX'),
		'DESCR' => '',
		'VALUE' => $taxes,
		'TYPE' => 'SELECT',
		'GROUP' => GetMessage('SALE_PAYANYWAY_G_CASHBOX'),
		'DEFAULT' => '',
		'SORT' => '32',
	), 
	'SEND_CUSTOMER' => array(
		'NAME' => GetMessage('SALE_PAYANYWAY_SEND_CUSTOMER'),
		'DESCR' => GetMessage('SALE_PAYANYWAY_SEND_CUSTOMER_DESC'),
		'VALUE' => array(
			'0' => array(
				'NAME' => GetMessage('SALE_PAYANYWAY_NO'),
				'VALUE' => '0',
				),
			'1' => array(
				'NAME' => GetMessage('SALE_PAYANYWAY_YES'),
				'VALUE' => '1',
				),
		),
		'TYPE' => 'SELECT',
		'GROUP' => GetMessage('SALE_PAYANYWAY_G_SEND'),
		'DEFAULT' => '',
		'SORT' => '21',
	),
	'SEND_OTHER_EMAIL' => array(
		'NAME' => GetMessage('SALE_PAYANYWAY_SEND_OTHER_EMAIL'),
		'DESCR' => GetMessage('SALE_PAYANYWAY_SEND_OTHER_EMAIL_DESC'),
		'VALUE' => array(
			'0' => array(
				'NAME' => GetMessage('SALE_PAYANYWAY_NO'),
				'VALUE' => '0',
				),
			'1' => array(
				'NAME' => GetMessage('SALE_PAYANYWAY_YES'),
				'VALUE' => '1',
				),
		),
		'TYPE' => '',
		'GROUP' => GetMessage('SALE_PAYANYWAY_G_SEND'),
		'DEFAULT' => '',
		'SORT' => '22',
	),
        'MAIL_FOR_SEND' => array(
		'NAME' => GetMessage('SALE_PAYANYWAY_MAIL_FOR_SEND'),
		'DESCR' => GetMessage('SALE_PAYANYWAY_MAIL_FOR_SEND_DESC'),
		'VALUE' => '',
		'TYPE' => '',
		'GROUP' => GetMessage('SALE_PAYANYWAY_G_SEND'),
		'DEFAULT' => '',
		'SORT' => '23',
	),

);