<?php
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
		CModule::IncludeModule('iblock');
		CModule::IncludeModule('catalog');
		global $USER;
		$arSelect = Array("ID");
		$arFilter = Array("IBLOCK_ID"=>28, "ACTIVE"=>"Y", "ID"=>61377);
		$res3 = CIBlockElement::GetList(Array(), $arFilter, false, array(), $arSelect);
		while ($ob3 = $res3->GetNextElement())
		{
			$arFields3 = $ob3->GetFields();
			$el = new CIBlockElement;
			$arLoadProductArray = Array(
			   "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
			);
			$res = $el->Update($arFields3['ID'], $arLoadProductArray, false, true);
		}
?>