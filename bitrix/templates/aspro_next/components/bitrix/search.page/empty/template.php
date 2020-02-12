<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])) {

    if (count($arResult['SEARCH']) > 0) {
        $elementIdList = [];
        foreach ($arResult['SEARCH'] as $item) {
            $elementIdList[] = $item['ITEM_ID'];
        }

        $elementIterator = CIBlockElement::GetList(false, ['ID' => $elementIdList], false, false, ['ID', 'NAME', 'PREVIEW_PICTURE']);
        $elementList = [];
        while ($element = $elementIterator->Fetch()) {
            $elementList[$element['ID']] = [
                'NAME' => $element['NAME'],
                'PREVIEW_PICTURE' => $element['PREVIEW_PICTURE']
            ];
        }

        foreach ($arResult['SEARCH'] as &$foundItem) {
            $foundItem['ITEM_NAME'] = $elementList[$foundItem['ITEM_ID']]['NAME'];

            if(strlen($elementList[$foundItem['ITEM_ID']]['PREVIEW_PICTURE']) > 0) {
                $foundItem['PICTURE'] = CFile::GetPath($elementList[$foundItem['ITEM_ID']]['PREVIEW_PICTURE']);
            } else {
                $foundItem['PICTURE'] = SITE_TEMPLATE_PATH . '/images/catalog_category_noimage.png';
            }
        }

        unset($foundItem);
    }

    echo json_encode($arResult['SEARCH']);
}