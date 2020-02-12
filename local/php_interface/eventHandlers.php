<?php

use Bitrix\Highloadblock;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader;

AddEventHandler('iblock', 'OnBeforeIBlockSectionAdd', ['CustomEvents', 'checkSectionActivation']);
AddEventHandler('iblock', 'OnBeforeIBlockSectionUpdate', ['CustomEvents', 'checkSectionActivation']);
AddEventHandler('search', 'BeforeIndex', ['CustomEvents', 'BeforeIndexHandler']);
AddEventHandler('main', 'OnBeforeUserRegister', ['CustomEvents', 'OnBeforeUserUpdateHandler']);
AddEventHandler("main", "OnBeforeUserUpdate", ['CustomEvents', 'OnBeforeUserUpdateHandler']);

/**
 * @class CustomEvents - содержить обработчики кастомных событий
 */
class CustomEvents
{
    const CATALOG_IBLOCK_ID = 28;

    /**
     * Запрещает активировать служебные разделы каталога
     *
     * @param array $arFields
     */
    public function checkSectionActivation(&$arFields)
    {

        if (Loader::includeModule('highloadblock')) {

            $hl = 6;
            $hlBlock = Highloadblock\HighloadBlockTable::getById($hl)->fetch();
            $entity = Highloadblock\HighloadBlockTable::compileEntity($hlBlock);
            $entity_data_class = $entity->getDataClass();
            $element = $entity_data_class::getList([
                'select' => ['*']
            ])->fetch();

            if ($element) {

                $catalogIBlockId = 28;
                // список разделов, которые должны быть не активными
                $inactiveSectionsId = $element['UF_SECTION_ID'];
                $inactiveSectionsName = $element['UF_SECTION_NAME'];

                if (
                    $arFields['IBLOCK_ID'] == $catalogIBlockId &&
                    (in_array($arFields['ID'], $inactiveSectionsId) || in_array($arFields['NAME'], $inactiveSectionsName)) &&
                    $arFields['ACTIVE'] == 'Y'
                ) {
                    $arFields['ACTIVE'] = 'N';
                }

            }

        }

    }

    /**
     * @param $arFields
     *
     * @return array
     */
    function BeforeIndexHandler($arFields)
    {
        // элемент инфоблока 6 (не раздел)
        if ($arFields["MODULE_ID"] == "iblock" &&
            $arFields["PARAM2"] == self::CATALOG_IBLOCK_ID &&
            substr($arFields["ITEM_ID"], 0, 1) != "S") {

            $arFields["PARAMS"]["iblock_section"] = array();
            //Получаем разделы привязки элемента (их может быть несколько)
            $rsSections = CIBlockElement::GetElementGroups($arFields["ITEM_ID"], true);
            while ($arSection = $rsSections->Fetch()) {
                $nav = CIBlockSection::GetNavChain(self::CATALOG_IBLOCK_ID, $arSection["ID"]);

                while ($ar = $nav->Fetch()) {
                    //Сохраняем в поисковый индекс
                    $arFields["PARAMS"]["iblock_section"][] = $ar['ID'];
                }
            }

        }
        //Всегда возвращаем arFields
        return $arFields;
    }

    /**
     * Запись в группы при регистрации
     *
     * @param $arFields
     *
     * @return void
     */
    function OnBeforeUserUpdateHandler(&$arFields)
    {
        #file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logreg.txt', print_r($arFields,1));
        if ($arFields["UF_CHECKORG"] == "1") {
            $arFields["GROUP_ID"][] = 9;
        } elseif ($arFields["UF_CHECKORG"] == "0") {
            $arFields["GROUP_ID"][] = 8;
        }
		if($arFields["UF_DISCOUNTCARD"]!=""){
            $arGroups = CUser::GetUserGroup($arFields["ID"]);
            if (!in_array("10",$arGroups)){
                $arGroups[] = 10;
                CUser::SetUserGroup($arFields["ID"], $arGroups);
            }
        }
    }
}