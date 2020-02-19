<?php 
#------------- РЕЛЕВАНТНАЯ СОРТИРОВКА ТОВАРОВ ---------------------------------------------------------------

$MAX_PROD_COUNT = 100;
global $searchArr;	# этот параметр искусственно выведен из компонеты search.page (/bitrix/components/bitrix/search.page/component.php)

if(!empty($_GET['s']) && empty($_GET['sort'])){
	$relevItems = $otherItems = $relevItemsNames = $otherItemsNames = array();
	foreach ($arResult['ITEMS'] as $key => $arItem) {
		$pos = strpos(mb_strtolower($arItem['NAME']), mb_strtolower($searchArr['QUERY']));
		if($pos !== false && $pos === 0){ 
			$relevItemsNames[$key] = $arItem['NAME'];
		} else {
			$otherItemsNames[$key] = $arItem['NAME'];
		}
	}
	asort($relevItemsNames);
	asort($relevItemsNames);
	$j = 0;
	foreach ($relevItemsNames as $key => $item) {
		$j++;
		if($j >= $MAX_PROD_COUNT) {break;}
		$relevItems[] = $arResult['ITEMS'][$key];
	}
	foreach ($otherItemsNames as $key => $item) {
		$j++;
		if($j >= $MAX_PROD_COUNT) {break;}
		$otherItems[] = $arResult['ITEMS'][$key];
	}
	$arResult['ITEMS'] = array_merge($relevItems, $otherItems);
}

#------------- РЕЛЕВАНТНАЯ СОРТИРОВКА ТОВАРОВ END -----------------------------------------------------------

?>