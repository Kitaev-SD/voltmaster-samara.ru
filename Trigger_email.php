<?php

# 0 9 * * * /usr/bin/php -f /home/bitrix/www/Trigger_email.php &>> /home/bitrix/www/Trigger_email.log &

$startTime = microtime(true);
$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/www";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Entity;
CModule::IncludeModule("iblock");

foreach (getOrdersData() as $value) {
	$arFields = [
		'USER_ID'			=> $value['USER_ID'],
		'USER_NAME'			=> $value['USER_NAME'],
		'ORDER_ID'			=> $value['ORDER_ID'],
		'ORDER_DATE'		=> $value['DATE_INSERT'],
		'ORDER_STATUS'		=> $value['STATUS_ID'],
		'EMAIL'				=> $value['USER_EMAIL'],
		'ORDER_DESCRIPTION'	=> '',
		'TEXT'				=> '',
		'SALE_EMAIL' 		=> 'azad@liderpoiska.ru',
		'ORDER_PUBLIC_URL'	=> ''
	];

	if(!empty($value) && $value['DELIVERY_ID'] == 3){
		sendEmail($arFields);
	}
}

$duration = microtime(true) - $startTime;
// echo "\n_____________________________________________________________";
// echo "\n Время выполнения скрипта: " . date('d-m-Y G:s:i');
// echo "\n Продолжительность выполнения скрипта: " . $duration;
#____________________________________ FUNCTIONS ____________________________________________________

function getOrdersData(){
	$arOrders = [];
	$arFilter = Array("ID" => 2175);
	$orders = CSaleOrder::GetList(array(),$arFilter,false,false,array(),array());

	foreach ($orders->arResult as $order) {
		$arOrders[] = [
			'ORDER_ID' => $order['ID'],
			'PAY_SYSTEM_ID' => $order['PAY_SYSTEM_ID'],
			'DELIVERY_ID' => $order['DELIVERY_ID'],
			'PERSON_TYPE_ID' => $order['PERSON_TYPE_ID'],
			'USER_ID' => $order['USER_ID'],
			'PAYED' => $order['PAYED'],
			'DATE_PAYED' => $order['DATE_PAYED'],
			'STATUS_ID' => $order['STATUS_ID'],
			'USER_LOGIN' => $order['USER_LOGIN'],
			'USER_NAME' => getOrderPropByCode($order['ID'], 'FIO'),
			'PHONE' => getOrderPropByCode($order['ID'], 'PHONE'),
			'CITY' => getOrderPropByCode($order['ID'], 'CITY'),
			'ADDRESS' => getOrderPropByCode($order['ID'], 'ADDRESS'),
			'USER_EMAIL' => getOrderPropByCode($order['ID'], 'EMAIL'),
			'DATE_INSERT' => accessProtected($order['DATE_INSERT'], 'value')->getTimestamp(),
			'DATE_UPDATE' => accessProtected($order['DATE_UPDATE'], 'value')->getTimestamp(),
			'DATE_STATUS' => accessProtected($order['DATE_STATUS'], 'value')->getTimestamp()
		];
	}

	// $arOrders = $orders->arResult;
	# 1 - перебираем все заказы у которых:
	#	 - статус заказа - ?????
	#	 - способ доставки - самовывоз 
	#	 - прошло 7 дней

	return $arOrders;
}

function getOrderPropByCode($order_id, $code){
	$output = false;
	$arProps = CSaleOrderPropsValue::GetOrderProps($order_id)->arResult;
	foreach ($arProps as $prop) {
		if($prop['CODE'] == $code){
			$output = $prop['PROXY_VALUE'];
			break;
		}
	}
	return $output;
}

function accessProtected($obj, $prop) {
	$reflection = new ReflectionClass($obj);
	$property = $reflection->getProperty($prop);
	$property->setAccessible(true);
	return $property->getValue($obj);
}

function sendEmail($arFields){
	if(!empty($arFields)) {
		CEvent::Send(
			'TRIGGER_EMAIL',	# идентификатор типа почтового события.
			's1',				# идентификатор  сайта
			$arFields,			# массив полей
			"N",				# копия письма на адрес в настройках главного модуля. По умолчанию "Y"
			53,					# почтовый шаблон [TRIGGER_EMAIL] Триггерная рассылка
			array(),			# массив id-ков файлов которые используются классом CFile 
			223					# language_id
		);
	}
}

?>