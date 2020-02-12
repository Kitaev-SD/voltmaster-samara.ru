<?php


AddEventHandler("search", "BeforeIndex", Array("IndexSearchClass", "BeforeIndexHandler"));

class IndexSearchClass
{
    const CATALOG_IBLOCK_ID = 28;

    // создаем обработчик события "BeforeIndex"
    function BeforeIndexHandler($arFields)
    {

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/BeforeIndex.txt', print_r('test_123', 1), FILE_APPEND);

        // элемент инфоблока 6 (не раздел)
        if($arFields["MODULE_ID"] == "iblock" &&
            $arFields["PARAM2"] == self::CATALOG_IBLOCK_ID &&
            substr($arFields["ITEM_ID"], 0, 1) != "S") {

            $arFields["PARAMS"]["iblock_section"] = array();
            //Получаем разделы привязки элемента (их может быть несколько)
            $rsSections = CIBlockElement::GetElementGroups($arFields["ITEM_ID"], true);
            while($arSection = $rsSections->Fetch())
            {
                $nav = CIBlockSection::GetNavChain(self::CATALOG_IBLOCK_ID, $arSection["ID"]);

                file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/BeforeIndex.txt', print_r($arSection["ID"], 1), FILE_APPEND);

                while($ar = $nav->Fetch()) {

                    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/BeforeIndex.txt', print_r($ar["ID"], 1), FILE_APPEND);

                    //Сохраняем в поисковый индекс
                    $arFields["PARAMS"]["iblock_section"][] = $ar['ID'];
                }
            }

        }

        if (array_key_exists("BODY", $arFields))
        {
            $arFields["BODY"] = "";
        }

        //Всегда возвращаем arFields
        return $arFields;
    }
}


/*
AddEventHandler(“sale”, OnBeforeOrderAdd, “OrderAddHandler”);
function OrderAddHandler( $arFields )
{
	$arFields['STATUS_ID'] = "W";
	$arFields["ORDER"]["STATUS_ID"] = "W";
	$arFields['STATUS'] = "W";	
	return $arFields;
}

AddEventHandler(“sale”, OnOrderAdd, “OrderAddHandler2”);
function OrderAddHandler2( $arFields )
{
	$arFields['STATUS_ID'] = "W";
	$arFields["ORDER"]["STATUS_ID"] = "W";
	$arFields['STATUS'] = "W";	
	return $arFields;
}

AddEventHandler(s1, OnBeforeOrderAdd, “OrderAddHandler3”);
function OrderAddHandler3( $arFields )
{
	$arFields['STATUS_ID'] = "W";
	$arFields["ORDER"]["STATUS_ID"] = "W";
	$arFields['STATUS'] = "W";	
	return $arFields;
}

AddEventHandler(s1, OnOrderAdd, “OrderAddHandler4”);
function OrderAddHandler4( $arFields )
{
	$arFields['STATUS_ID'] = "W";
	$arFields["ORDER"]["STATUS_ID"] = "W";
	$arFields['STATUS'] = "W";	
	return $arFields;
}*/

##Запись в группы при регистрации
AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserUpdateHandler");
function OnBeforeUserUpdateHandler(&$arFields)
{
	#file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logreg.txt', print_r($arFields,1));
    if ($arFields["UF_CHECKORG"] == "1") {
		$arFields["GROUP_ID"][] = 9;
	} elseif($arFields["UF_CHECKORG"] == "0") {
		$arFields["GROUP_ID"][] = 8;
	}
}