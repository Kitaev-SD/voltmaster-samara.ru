<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/local/classes/CResaveProduct.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/eventHandlers.php';

// AddEventHandler("subscribe", OnBeforeSubscriptionAdd, "GoogleCaptcha");
// AddEventHandler("subscribe", OnBeforeSubscriptionUpdate, "GoogleCaptcha");
// AddEventHandler("main", OnBeforeUserRegister, "GoogleCaptcha");

function GoogleCaptcha($arFields) {
    global $APPLICATION;
	# действие обработчика распространяется только на форму с ID=2
    $date = date('Y');
	if($_REQUEST['yearcheck'] !== date('Y')) {
	    $APPLICATION->throwException("Вы не прошли проверку подтверждения личности - указанный год неверен. Сейчас $date");
	    return false;
	}
}

AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserUpdateHandler");
function OnBeforeUserUpdateHandler(&$arFields) {
	#file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logreg.txt', print_r($arFields,1));
    if ($arFields["UF_CHECKORG"] == "1") {
		$arFields["GROUP_ID"][] = 9;
	} elseif($arFields["UF_CHECKORG"] == "0") {
		$arFields["GROUP_ID"][] = 8;
	}
}

#-----------------------------------------------------------------------
# Поиск по артикулу (по свойству CML2_BAR_CODE) в search.title
AddEventHandler("search", "BeforeIndex", "BeforeIndexHandler");
function BeforeIndexHandler($arFields) {
	if(!CModule::IncludeModule("iblock")) { // подключаем модуль
		return $arFields;
	}

	if($arFields["MODULE_ID"] == "iblock") {
		$db_props = CIBlockElement::GetProperty(		// Запросим свойства индексируемого элемента
			$arFields["PARAM2"],         				// BLOCK_ID индексируемого свойства
			$arFields["ITEM_ID"],         				// ID индексируемого свойства
			array(),       								// Сортировка (можно упустить)
			Array("CODE"=>"CML2_BAR_CODE")				// CODE свойства (в данном случае артикул)
		); 	

		if($ar_props = $db_props->Fetch()){
			$arFields["TITLE"] .= " ".$ar_props["VALUE"];   // Добавим свойство в конец заголовка индексируемого элемента
		}
	}
	return $arFields;
}
#-----------------------------------------------------------------------
# Перед отправкой почтового шаблона (почт шабл # 42 Изменение статуса заказа на "Выполнен") 
# проверяем способа отгрузки «Самовывоз» или иной способ отгрузки  
AddEventHandler("main", "OnBeforeEventAdd", "OnBeforeEventAddHandler");
function OnBeforeEventAddHandler(&$event, &$lid, &$arFields) {
	if ($event=='SALE_STATUS_CHANGED_F') { 
		$arOrder = CSaleOrder::GetByID($arFields['ORDER_ID']);
		$arDelivery = CSaleDelivery::GetByID($arOrder['DELIVERY_ID']);

	    if($arDelivery['ID'] == 3) {	# Самовывоз
	    	$arFields['ORDER_CONTENT'].= 'Новый статус заказа: Выполнен.<br>';
	    } else {
	    	$arFields['ORDER_CONTENT'].= 'Новый статус заказа: Передан на отгрузку.<br>';
	    	$arFields['ORDER_CONTENT'].= 'Информацию по отслеживанию вы получите в отдельном письме.<br>';
	    }

	    $arFields['ORDER_CONTENT'].= 'Спасибо что выбрали наш магазин.<br>';
	}
}
#-----------------------------------------------------------------------
# Вопросы и ответы (/info/faq/)
AddEventHandler("forum", "onAfterMessageAdd", "onAfterMessageAddHandler");

function onAfterMessageAddHandler ($id, $arFields) {
	$arFieldsSend = [];
	$arFieldsSend['AUTHOR'] = (!empty($arFields['AUTHOR_NAME'])) ? $arFields['AUTHOR_NAME'] : $arFields['SECOND_NAME'].' '.$arFields['LAST_NAME'];
	$arFieldsSend['AUTHOR_EMAIL'] = $arFields['AUTHOR_EMAIL'];
	$arFieldsSend['LOGIN'] = $arFields['LOGIN'];
	$arFieldsSend['EMAIL'] = $arFields['EMAIL'];
	$arFieldsSend['XML_ID'] = $arFields['XML_ID'];
	$arFieldsSend['ATTACH_IMG'] = $arFields['ATTACH_IMG'];
	$arFieldsSend['PERSONAL_PHOTO'] = $arFields['PERSONAL_PHOTO'];
	$arFieldsSend['COMMENT_DATE'] = (!empty($arFields['POST_DATE'])) ? $arFields['POST_DATE'] : $arFields['EDIT_DATE'];
	$arFieldsSend['COMMENT_TEXT'] = (!empty($arFields['POST_MESSAGE_HTML'])) ? $arFields['POST_MESSAGE_HTML'] : $arFields['POST_MESSAGE'];
	$arFieldsSend['BCC'] = COption::GetOptionString('main','all_bcc');


	#------ Debug block --------------------------------------
	 //    $file = '/home/bitrix/www/test_111.txt';
	 //    $output.= "\n________________________________________________________________\n"; 
	 //    $output.= serialize($arFields);
	 //    $output.= "\n________________________________________________________________\n"; 
		// file_put_contents($file, $output, LOCK_EX);
	#------ Debug block END ----------------------------------

	if(!empty($arFields)) {
		CEvent::Send(	#SendImmediate
			'NEW_BLOG_COMMENT_WITHOUT_TITLE',	# идентификатор типа почтового события.
			's1',								# идентификатор  сайта
			$arFieldsSend,						# массив полей
			"N",								# копия письма на адрес в настройках главного модуля. По умолчанию "Y"
			55,									# почтовый шаблон [TRIGGER_EMAIL] Триггерная рассылка
			array($arFieldsSend['ATTACH_IMG']),	# массив id-ков файлов которые используются классом CFile 
			225									# language_id
		);

		return true;
	} else {
		return false;
	}

	
}

#-----------------------------------------------------------------------

// AddEventHandler("main", "OnEndBufferContent", "OnEndBufferContentHandler");
// function OnEndBufferContentHandler($content)
// {
//    global $APPLICATION, $USER;
//    $url = $APPLICATION->GetCurUri();
//    if(!substr_count($url,"/bitrix/") && substr_count($content,"</body>") && !$USER->IsAdmin()){
//        $hashUrl = hash('md5',$url);
//        $sliCont = substr($content,0,strpos($content,"</body>")+7);
//        $sliCont = substr($sliCont,strpos($sliCont,"<body>"));
//        $hashContent = hash('md5',$sliCont);
//        $hashArr = ["time" => time(), "hash" => $hashContent];
//        if(file_exists($_SERVER["DOCUMENT_ROOT"]."/upload/page_hash/".$hashUrl.".txt")){
//            $lastArr = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"]."/upload/page_hash/".$hashUrl.".txt"),true);
//            if(is_array($lastArr) && $lastArr["hash"] && $lastArr["hash"]==$hashArr["hash"]){
//                $lastModified =  gmdate("D, d M Y H:i:s \G\M\T", $lastArr["time"]);
//                header("Cache-Control: public");
//                header('Last-Modified: '.$lastModified);
//                if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $lastArr["time"]){
//                    header('HTTP/1.1 304 Not Modified'); exit();
//                }
//            }
//        }
//        if(!$lastModified)file_put_contents($_SERVER["DOCUMENT_ROOT"]."/upload/page_hash/".$hashUrl.".txt", json_encode($hashArr));
//    }
// }
#-----------------------------------------------------------------------

#-----------------------------------------------------------------------
# AddEventHandler(“sale”, OnBeforeOrderAdd, “OrderAddHandler”);
# function OrderAddHandler( $arFields ) {
# 	$arFields['STATUS_ID'] = "W";
# 	$arFields["ORDER"]["STATUS_ID"] = "W";
# 	$arFields['STATUS'] = "W";
# 	return $arFields;
# }

# AddEventHandler(“sale”, OnOrderAdd, “OrderAddHandler2”);
# function OrderAddHandler2( $arFields ) {
# 	$arFields['STATUS_ID'] = "W";
# 	$arFields["ORDER"]["STATUS_ID"] = "W";
# 	$arFields['STATUS'] = "W";
# 	return $arFields;
# }

# AddEventHandler(s1, OnBeforeOrderAdd, “OrderAddHandler3”);
# function OrderAddHandler3( $arFields ) {
# 	$arFields['STATUS_ID'] = "W";
# 	$arFields["ORDER"]["STATUS_ID"] = "W";
# 	$arFields['STATUS'] = "W";
# 	return $arFields;
# }

# AddEventHandler(s1, OnOrderAdd, “OrderAddHandler4”);
# function OrderAddHandler4( $arFields ) {
# 	$arFields['STATUS_ID'] = "W";
# 	$arFields["ORDER"]["STATUS_ID"] = "W";
# 	$arFields['STATUS'] = "W";
# 	return $arFields;
# }
#-----------------------------------------------------------------------

#-----------------------------------------------------------------------
# AddEventHandler("main", "OnEndBufferContent", "minifyHTML");
# function minifyHTML(&$content) {
# 	global $USER;
# 	if(is_object($USER) && $USER->IsAuthorized()) return;
# 	$content = preg_replace('|\s+|', ' ', $content);
# }
# AddEventHandler("main", "OnEndBufferContent", "addPreload");
# function addPreload(&$content){
# 	$content = preg_replace('/<link href="\/bitrix\/templates\/aspro_next\/vendor\/fonts\/font-awesome\/css\/font-awesome.min.css?157112310430802" data-template-style="true" rel="stylesheet">/', '<link rel="preload" href="/bitrix/templates/aspro_next/vendor/fonts/font-awesome/css/font-awesome.min.css" data-template-style="true" as="style">', $content);
# }
#-----------------------------------------------------------------------

#-----------------------------------------------------------------------
# Убираем поиск в описаниях у товаров
# AddEventHandler("search", "BeforeIndex", "BeforeIndexHandler");
# function BeforeIndexHandler($arFields) {
#    $arFields["BODY"] = '';
#    return $arFields;
# }
#-----------------------------------------------------------------------


# Обработчик события для смены статуса заказа

if (isset($_GET['type'], $_GET['mode']) && $_GET['type'] === 'shop' && $_GET['mode'] === 'import') {
	use Bitrix\Main; 
	Main\EventManager::getInstance()->addEventHandler(
		'sale',
		'OnSaleOrderBeforeSaved',
		'myFunction'
	);

	function myFunction(Main\Event $event) {
		$order = $event->getParameter("ENTITY");
		$status = $order->getField('STATUS_ID');
		$delivery = $order->getField('DELIVERY_ID');

		if ($status == 'OP' && $delivery == 3) {
			$order->setField('STATUS_ID', 'AC');
		}	
	}
}

#------ Debug block --------------------------------------
// $file = '/var/www/voltmaster-samara.ru/test_111.txt';
// $output = implode(" :: ", $events)."\n";
// file_put_contents($file, $output, LOCK_EX);
#------ Debug block END ----------------------------------