<?
/**
 * @global $APPLICATION
 * @var $arParams
 */

// is ajax query
// for search in catalog hints
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    $APPLICATION->RestartBuffer();
}

$searchableSectionId = (int)htmlspecialchars($_REQUEST['section_search']);
if ($searchableSectionId > 0) {
    global $arSectionFilter;

    $arSectionFilter = [
        'PARAMS' => [
            'iblock_section' => $searchableSectionId
        ]
    ];
}

$arElements = $APPLICATION->IncludeComponent(
    "bitrix:search.page",
    "empty",
    Array(
        "USE_LANGUAGE_GUESS" => "Y",
        // "ORDER"=>"rank",
        "DEFAULT_SORT"=>"rank",
        "RESTART" => "N",
        "CHECK_DATES" => "Y",
        "arrWHERE" => array(),
        "FILTER_NAME" => "arSectionFilter",
        "arrFILTER" => array(
            0 => "iblock_" . $arParams['IBLOCK_TYPE'],
        ),
        "arrFILTER_iblock_" . $arParams['IBLOCK_TYPE'] => array(
            0 => $arParams['IBLOCK_ID'],
        ),
        "SHOW_WHERE" => "N",
        "PAGE_RESULT_COUNT" => "1000",
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "TAGS_SORT" => "NAME",
        "TAGS_PAGE_ELEMENTS" => "20",
        "TAGS_PERIOD" => "",
        "TAGS_URL_SEARCH" => "",
        "TAGS_INHERIT" => "Y",
        "FONT_MAX" => "50",
        "FONT_MIN" => "10",
        "COLOR_NEW" => "000000",
        "COLOR_OLD" => "C8C8C8",
        "PERIOD_NEW_TAGS" => "",
        "SHOW_CHAIN" => "Y",
        "COLOR_TYPE" => "Y",
        "WIDTH" => "100%",
    ),
    $component
);

// is ajax query
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    die();
}
