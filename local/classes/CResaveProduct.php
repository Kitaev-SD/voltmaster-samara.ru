<?php
###Класс для пересохранения товаров, т.к. при выгрузках они не считаются до конца перенесенными на сайт и попадают не во все поисковые индексы
abstract class CResaveProduct {

    public static function resaveProduct($page = 1) {
		$count = 0;
		CModule::IncludeModule('iblock');
		CModule::IncludeModule('catalog');
		global $USER;
		$arSelect = Array("ID");
		$arFilter = Array("IBLOCK_ID"=>28, "ACTIVE"=>"Y", "CATALOG_AVAILABLE"=>"Y");
		$res3 = CIBlockElement::GetList(Array(), $arFilter, false, array('iNumPage' => $page, 'nPageSize' => 5, 'checkOutOfRange' => true), $arSelect);
		while ($ob3 = $res3->GetNextElement())
		{
			$arFields3 = $ob3->GetFields();
			$el = new CIBlockElement;
			$arLoadProductArray = Array(
			   "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
			);
			$res = $el->Update($arFields3['ID'], $arLoadProductArray, false, true);
			$count++;
		}
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logloglog.txt', print_r(array($arFields3['ID'], $page),1));
		if ($count > 0) {
			return $count;
		} else {
			return false;
		}
    }
}