<?php

# 0 9 * * * /usr/bin/php -f /home/bitrix/www/Trigger_email.php &>> /home/bitrix/www/Trigger_email.log &

$startTime = microtime(true);
$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/www";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
CModule::IncludeModule("highloadblock");
CModule::IncludeModule("iblock");

$hlbl = 8; 				# ID highloadblock блока
$dayCount = 7;			# срок хранения заказа
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();
$arrListHLB = getListHLB($entity_data_class);

$ORDERS = getOrdersData();

foreach ($ORDERS as $value) {
	$totalPriceWithoutDsicount = 0;
	$basketItems = getBasketItems($value['ORDER_ID']);
	$currency = ($value['CURRENCY'] == 'RUB') ? ' руб.' : ' $';
	$daysInterval = daysPassed($value['DATE_STATUS']);
	$validDate = dateTo($value['DATE_STATUS'], $dayCount+3);		# получаем дату до которого будет хранится заказ

	#--------------- BASKET ITEMS -------------------------------------------------------------------------------------------------------------
	$basketItemsTemplate = '';
	$basketItemsTemplate.= '<p style="line-height: 1.5;padding:0;margin:0; color:#222222;">Состав заказа №'.$value['ORDER_ID'].':</p>';
	$basketItemsTemplate.= '<table style="width:100%; border-collapse:collapse; color:#222222;">
								<thead>
									<tr style="background-color:#ddd; font-size:13px; font-weight:bold;">
										<th style="width:40%; border:1px solid black; padding-top:5px; padding-bottom:5px;">Наименование</th>
										<th style="width:15%; border:1px solid black;">Количество</th>
										<th style="width:15%; border:1px solid black;">Цена</th>
										<th style="width:15%; border:1px solid black;">Цена со скидкой</th>
										<th style="width:15%; border:1px solid black;">Сумма со скидкой</th>
									</tr>
								</thead>
								<tbody>';

	foreach ($basketItems as $basketItem) {
		if(empty($basketItem['BASE_PRICE'])) {$basketItem['BASE_PRICE'] = $basketItem['PRICE'];}
		$sumPrice = $basketItem['PRICE']*$basketItem['QUANTITY'];
		$totalPriceWithoutDsicount+= $basketItem['BASE_PRICE']*$basketItem['QUANTITY'];
		$basketItem['BASE_PRICE'] = CCurrencyLang::CurrencyFormat($basketItem['BASE_PRICE'], $value['CURRENCY'], false);
		$basketItem['PRICE'] = CCurrencyLang::CurrencyFormat($basketItem['PRICE'], $value['CURRENCY'], false);
		$basketItem['QUANTITY'] = CCurrencyLang::CurrencyFormat($basketItem['QUANTITY'], $value['CURRENCY'], false);
		
		$basketItemsTemplate.= 		'<tr>';
		$basketItemsTemplate.= 		'<td style="border:1px solid black; padding-top:3px; padding-bottom:3px; padding-left:10px;">'. $basketItem['NAME'] .'</td>';
		$basketItemsTemplate.= 		'<td style="border:1px solid black; padding-top:3px; padding-bottom:3px; text-align:center;">'. $basketItem['QUANTITY'] .'</td>';
		$basketItemsTemplate.= 		'<td style="border:1px solid black; padding-top:3px; padding-bottom:3px; text-align:center;">'. $basketItem['BASE_PRICE'] . $currency .'</td>';
		$basketItemsTemplate.= 		'<td style="border:1px solid black; padding-top:3px; padding-bottom:3px; text-align:center;">'. $basketItem['PRICE'] . $currency .'</td>';
		$basketItemsTemplate.= 		'<td style="border:1px solid black; padding-top:3px; padding-bottom:3px; text-align:center;">'. $sumPrice . $currency .'</td>';
		$basketItemsTemplate.= 		'</tr>';
	}
	$basketItemsTemplate.= 		'</tbody></table><br>';

	$totalDiscount = floatval($totalPriceWithoutDsicount) - floatval($value['TOTAL_PRICE']);
	$totalDiscount = CCurrencyLang::CurrencyFormat($totalDiscount, $value['CURRENCY'], false);
	$totalPriceWithoutDsicount = CCurrencyLang::CurrencyFormat($totalPriceWithoutDsicount, $value['CURRENCY'], false);
	$totalPriceWithDsicount = CCurrencyLang::CurrencyFormat($value['TOTAL_PRICE'], $value['CURRENCY'], false);
	
	$basketItemsTemplate.= '<table style="width:100%; border-collapse:collapse; max-width:400px; color:#222222;">
								<tbody>
									<tr style="font-size:13px;">
										<td style="background-color:#ddd; width:50%; border:1px solid black; padding-top:3px; padding-bottom:3px; padding-left:10px; font-weight:bold;">Товаров на:</td>
										<td style="width:50%; border:1px solid black; padding-top:3px; padding-bottom:3px; text-align:center;">'.$totalPriceWithoutDsicount.$currency.'</td>
									</tr>
									<tr style="font-size:13px;">
										<td style="background-color:#ddd; width:50%; border:1px solid black; padding-top:3px; padding-bottom:3px; padding-left:10px; font-weight:bold;">Скидка:</td>
										<td style="width:50%; border:1px solid black; padding-top:3px; padding-bottom:3px; text-align:center;">'.$totalDiscount.$currency.'</td>
									</tr>
									<tr style="font-size:13px;">
										<td style="background-color:#ddd; width:50%; border:1px solid black; padding-top:3px; padding-bottom:3px; padding-left:10px; font-weight:bold;">Итого со скидкой:</td>
										<td style="width:50%; border:1px solid black; padding-top:5px; padding-bottom:5px; text-align:center;">'.$totalPriceWithDsicount.$currency.'</td>
									</tr>
								</tbody>
							</table>';
	#--------------- BASKET ITEMS END ---------------------------------------------------------------------------------------------------------							

	$arFields = [
		'USER_ID'			=> $value['USER_ID'],
		'USER_NAME'			=> ($value['PERSON_TYPE_ID'] == '1') ? $value['USER_NAME'] : $value['CONTACT_PERSON'],
		'ORDER_ID'			=> $value['ORDER_ID'],
		'ORDER_DATE'		=> $value['DATE_INSERT'],
		'ORDER_STATUS'		=> $value['STATUS_ID'],
		'EMAIL'				=> $value['USER_EMAIL'],
		'BASKET_TEMPLATE'	=> $basketItemsTemplate,
		'BCC'				=> COption::GetOptionString('main','all_bcc'),
		'SALE_EMAIL' 		=> COption::GetOptionString('main','email_from'),
		'AZAD' 				=> 'azad@liderpoiska.ru',
		'MARINA'			=> 'marina@liderpoiska.ru',
		'ORDER_LIFETIME'	=> 	(intval($dayCount)+3),
		'ORDER_VALID_DATE'	=> 	$validDate['day']."&nbsp;".$validDate["month"]."&nbsp;".$validDate["year"],
		'ORDER_PUBLIC_URL'	=> '',
		'ORDER_DESCRIPTION'	=> '',
		'TEXT'				=> ''
	];

	$item = isExistInHighload($arrListHLB, $value['ORDER_ID']);


	# заказ не пустой, доставка - самовывоз, статус заказа - "Товар подобран", заказ не отменен, дата обновления статуса больше равно $dayCount
	if(!empty($value) && $value['DELIVERY_ID'] == 3 && $value['STATUS_ID'] == 'OP' && $value['CANCELED'] != 'Y' && $daysInterval >= $dayCount){
		if(!$item){
			if($isSend = sendEmail($arFields)){
				addItemHLB($entity_data_class, $value['ORDER_ID'], '1');
			}
		} else {
			if($item['EMAIL_IS_SEND'] == '0'){
				if($isSend = sendEmail($arFields)){
					setItemHLB($entity_data_class, $item['ID'], $value['ORDER_ID'], '1');
				}
			}
		}
	}
}

$duration = microtime(true) - $startTime;
echo "\n_____________________________________________________________";
echo "\n Триггерная рассылка ";
echo "\n Время выполнения скрипта: " . date('d-m-Y G:s:i');
echo "\n Продолжительность выполнения скрипта: " . $duration;

#____________________________________ FUNCTIONS ___________________________________________________
#---------- Получение списка заказов --------------------------------------------------------------
function getOrdersData(){
	$arOrders = [];
	$arFilter = Array(">ID" => 2416);
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
			'COMPANY' => getOrderPropByCode($order['ID'], 'COMPANY'),
			'COMPANY_ADR' => getOrderPropByCode($order['ID'], 'COMPANY_ADR'),
			'INN' => getOrderPropByCode($order['ID'], 'INN'),
			'KPP' => getOrderPropByCode($order['ID'], 'KPP'),
			'CONTACT_PERSON' => getOrderPropByCode($order['ID'], 'CONTACT_PERSON'),
			'PHONE' => getOrderPropByCode($order['ID'], 'PHONE'),
			'CITY' => getOrderPropByCode($order['ID'], 'CITY'),
			'ADDRESS' => getOrderPropByCode($order['ID'], 'ADDRESS'),
			'USER_EMAIL' => getOrderPropByCode($order['ID'], 'EMAIL'),
			'DATE_INSERT' => accessProtected($order['DATE_INSERT'], 'value')->getTimestamp(),
			'DATE_UPDATE' => accessProtected($order['DATE_UPDATE'], 'value')->getTimestamp(),
			'DATE_STATUS' => accessProtected($order['DATE_STATUS'], 'value')->getTimestamp(),
			'TOTAL_PRICE' => $order['PRICE'],
			'CURRENCY' => $order['CURRENCY'],
			'CANCELED' => $order['CANCELED']
		];
	}

	return $arOrders;
}

#---------- Получение свойств заказа по id заказа и коду свойства ---------------------------------
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

#---------- Получение состава заказа --------------------------------------------------------------
function getBasketItems($order_id){
	$orderBasketArr = [];
	$obBasket = \Bitrix\Sale\Basket::getList(array('filter' => array('ORDER_ID' => $order_id)));
	while($item = $obBasket->Fetch()){
	   $orderBasketArr[] = $item;
	}
	return $orderBasketArr;
}

#---------- Доступ к объекту с защитой чтения -----------------------------------------------------
function accessProtected($obj, $prop) {
	$reflection = new ReflectionClass($obj);
	$property = $reflection->getProperty($prop);
	$property->setAccessible(true);
	return $property->getValue($obj);
}

#---------- Отправляем письмо ---------------------------------------------------------------------
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

		return true;
	} else {
		return false;
	}
}

#---------- Делаем выборку из highloadblock блока метод getlist -----------------------------------
function getListHLB($entity_data_class_){
	$rsData = $entity_data_class_::getList(array(
	   "select" => array("*"),
	   "order" => array(),
	   "filter" => array()  // Задаем параметры фильтра выборки
	));

	$arrListHLB_ = array();

	while($arData = $rsData->Fetch()){
		array_push($arrListHLB_, array('ID' => $arData['ID'], 'ORDER_ID' => $arData['UF_ORDER_ID'], 'EMAIL_IS_SEND' => $arData['UF_EMAIL_IS_SEND'],));
	}

	return $arrListHLB_;
}

#---------- Проверка существования записи в highloadblock по SECTION_ID ---------------------------
function isExistInHighload($arrListHLB_, $order_id_){
	$item = false;
	foreach ($arrListHLB_ as $key => $line) {
		if($line['ORDER_ID'] == $order_id_){
			$item = [
					'ID' => $line['ID'],
					'EMAIL_IS_SEND' => $line['EMAIL_IS_SEND']
				];
			break;
		}
	}

	return $item;
}

#---------- Добавление записи в highloadblock -----------------------------------------------------
function addItemHLB($entity_data_class_, $order_id_, $email_is_send_){
	$data = array(
		"UF_ORDER_ID"=> $order_id_,
		"UF_EMAIL_IS_SEND"=> $email_is_send_
	);
	$result = $entity_data_class_::add($data);
}

#---------- Обновленине записи в highloadblock ----------------------------------------------------
function setItemHLB($entity_data_class_, $item_id_, $order_id_, $email_is_send_){
	$data = array(
		"UF_ORDER_ID"=> $order_id_,
		"UF_EMAIL_IS_SEND"=> $email_is_send_
	);
	$result = $entity_data_class_::update($item_id_, $data);
}

#---------- Сколько прошло дней -------------------------------------------------------------------
function daysPassed($from_date){
	$daysPassedValue = false;
	$date_upd = new DateTime();
	$date_upd->setTimestamp(intval($from_date));
	$dateNow = date_create('now',new DateTimeZone('Europe/Moscow'));
	$interval = date_diff($date_upd, $dateNow);
	$daysPassedValue = intval($interval->format('%a'));
	return $daysPassedValue;
}

function dateTo($from_date, $dayCount){
	$dateToArr = false;
	$date = new DateTime();
	$date->setTimestamp(intval($from_date));
	date_modify($date, '+'.$dayCount.' day');

	$dateToArr = [
		'year'	=> $date->format('Y'),
		'month'	=> getMonthName($date->format('n')),
		'day'	=> $date->format('j')
	];

	return $dateToArr;
}

#---------- Месяцы в родит. падеже ----------------------------------------------------------------
function getMonthName($month_id){
	switch ($month_id) {
		case 1:
			return 'января';
			break;
		case 2:
			return 'февраля';
			break;
		case 3:
			return 'марта';
			break;
		case 4:
			return 'апреля';
			break;
		case 5:
			return 'мая';
			break;
		case 6:
			return 'июня';
			break;
		case 7:
			return 'июля';
			break;
		case 8:
			return 'августа';
			break;
		case 9:
			return 'сентября';
			break;
		case 10:
			return 'октября';
			break;
		case 11:
			return 'ноября';
			break;
		case 12:
			return 'декабря';
			break;
	}
}

?>