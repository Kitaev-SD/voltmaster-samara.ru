<?php

$iblock_id = 28; 		# ID инфоблока
$hlbl = 7; 				# ID highloadblock блока

# 0 0 * * 0 /usr/bin/php -f /var/www/voltmaster-samara.ru/SetHighloadBlock.php &>> /var/www/voltmaster-samara.ru/SetHighloadBlock.log &
# 0 0 * * 0 /usr/bin/php -f /var/www/voltmaster-samara.ru/test2.php &>> /var/www/voltmaster-samara.ru/test2.log &
$startTime = microtime(true);
$_SERVER["DOCUMENT_ROOT"] = "/var/www/voltmaster-samara.ru";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;
CModule::IncludeModule("highloadblock");
CModule::IncludeModule("iblock");

$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();
$arrListHLB = getListHLB($entity_data_class);

foreach (getCategoryList($iblock_id) as $section_id) {
	$section_id = intval($section_id);
	$element_count = intval(getCountElement($iblock_id, $section_id));

	# Добавляем/обновляем highloadblock
	$item = isExistInHighload($arrListHLB, $section_id);

	if(!$item) {
		addItemHLB($entity_data_class, $section_id, $element_count);
	} else {
		if(intval($item['ELM_COUNT']) != $element_count) {
			setItemHLB($entity_data_class, $item['ID'], $section_id, $element_count);
		}
	}
}

$duration = microtime(true) - $startTime;
echo "<br>\nВремя выполнения скрипта: " . $duration;
#____________________________________ FUNCTIONS ____________________________________________________
#---------- Делаем выборку из highloadblock блока метод getlist ------------------------------------
function getListHLB($entity_data_class_){
	$rsData = $entity_data_class_::getList(array(
	   "select" => array("*"),
	   "order" => array(),
	   "filter" => array()  // Задаем параметры фильтра выборки
	));

	$arrListHLB_ = array();

	while($arData = $rsData->Fetch()){
		array_push($arrListHLB_, array('ID' => $arData['ID'], 'SECTION_ID' => $arData['UF_SECTION_ID'], 'ELEMENT_COUNT' => $arData['UF_ELEMENT_COUNT'],));
	}

	return $arrListHLB_;
}

#---------- Добавление записи в highloadblock -----------------------------------------------------
function addItemHLB($entity_data_class_, $section_id_, $element_count_){
	$data = array(
		"UF_SECTION_ID"=> $section_id_,
		"UF_ELEMENT_COUNT"=> $element_count_
	);
	$result = $entity_data_class_::add($data);
}

#---------- Обновленине записи в highloadblock ---------------------------------------------------
function setItemHLB($entity_data_class_, $item_id_, $section_id_, $element_count_){
	$data = array(
		"UF_SECTION_ID"=> $section_id_,
		"UF_ELEMENT_COUNT"=> $element_count_
	);
	$result = $entity_data_class_::update($item_id_, $data);
}

#---------- Проверка существования записи в highloadblock по SECTION_ID ---------------------------
function isExistInHighload($arrListHLB_, $section_id_){
	$item = false;
	foreach ($arrListHLB_ as $key => $line) {
		if($line['SECTION_ID'] == $section_id_){
			$item = [
					'ID' => $line['ID'],
					'ELM_COUNT' => $line['UF_ELEMENT_COUNT']
				];
			break;
		}
	}

	return $item;
}

#---------- Получаем список разделов --------------------------------------------------------------
function getCategoryList($iblock_id) {
	$arFilter = Array('IBLOCK_ID' => $iblock_id, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y');
	$res = CIBlockSection::GetList(Array(), $arFilter, false, Array());
	while($ob = $res->GetNext()) {
		$sections_id[] = $ob['ID'];
	}
	return $sections_id;
}

#---------- Получаем количество элементов в разделе по id раздела ---------------------------------
function getCountElement($iblock_id, $section_id){
	$ar_fltr = array(
		"IBLOCK_ID"=>$iblock_id,
		"SECTION_ID"=>$section_id,
		"CATALOG_AVAILABLE" => "Y",
		"INCLUDE_SUBSECTIONS" => "Y",
		"ACTIVE" => "Y"
	);
	$arProjElem = CIBlockElement::GetList(array(),$ar_fltr);
	$count = $arProjElem->SelectedRowsCount();
	return $count;                       
}


?>