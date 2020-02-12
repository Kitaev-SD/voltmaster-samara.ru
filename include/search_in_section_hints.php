<?php

$searchQuery = htmlspecialchars($_REQUEST['search_query']);
if (strlen($searchQuery) > 0) {

    include_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

    CModule::IncludeModule('iblock');
    CModule::IncludeModule('search');

    $sectionId = htmlspecialchars($_REQUEST['section_search']);
    $obSearch = new CSearch;

    $obSearch->Search([
        'QUERY' => $searchQuery,
        'SITE_ID' => LANG,
        'MODULE_ID' => 'iblock',
        'PARAMS' => [
            'iblock_section' => strlen($sectionId) > 0 ? $sectionId : false
        ]
    ]);

    $searchResult = [];
    while ($arSearch = $obSearch->Fetch()) {
        $searchResult[] = $arSearch['ITEM_ID'];
    }

    if (count($searchResult) > 0) {
        $elementIterator = CIBlockElement::GetList(
            ['ID' => 'ASC'],
            [
                'IBLOCK_ID' => 28,
                'ACTIVE' => 'Y',
                'GLOBAL_ACTIVE' => 'Y',
                'ID' => $searchResult,
                'CATALOG_AVAILABLE' => 'Y'
            ],
            false,
            [
                'iNumPage' => 1,
                'nPageSize' => 10
            ],
            [
                'ID',
                'NAME',
                'PREVIEW_PICTURE',
                'DETAIL_PAGE_URL'
            ]
        );

        $jsonResponse = [];
        while ($element = $elementIterator->GetNext(false, false)) {
            $jsonResponse[] = [
                'ITEM_ID' => $element['ID'],
                'URL' => $element['DETAIL_PAGE_URL'],
                'PICTURE' => CFile::GetPath($element['PREVIEW_PICTURE']),
                'ITEM_NAME' => $element['NAME']
            ];
        }

        echo json_encode($jsonResponse);
    }

}

