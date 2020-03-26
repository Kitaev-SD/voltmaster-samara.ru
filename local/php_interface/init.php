<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/local/classes/CResaveProduct.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/eventHandlers.php';

AddEventHandler("subscribe", OnBeforeSubscriptionAdd, "GoogleCaptcha");
AddEventHandler("subscribe", OnBeforeSubscriptionUpdate, "GoogleCaptcha");
AddEventHandler("main", OnBeforeUserRegister, "GoogleCaptcha");
function GoogleCaptcha($arFields) {
    global $APPLICATION;
  # действие обработчика распространяется только на форму с ID=2
    $date = date('Y');
	if($_REQUEST['yearcheck'] !== date('Y')) {
	    $APPLICATION->throwException("Вы не прошли проверку подтверждения личности - указанный год неверен. Сейчас $date");
	    return false;
	}
    ##file_put_contents($_SERVER["DOCUMENT_ROOT"].'/logmail.txt', print_r($_REQUEST,1));
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