<?$APPLICATION->IncludeComponent(
	"bitrix:search.title", 
	"corp", 
	array(
		"NUM_CATEGORIES" => "1",
		"TOP_COUNT" => "100",
		"ORDER" => "rank",				// date or rank
		"USE_LANGUAGE_GUESS" => "Y",
		"CHECK_DATES" => "Y",
		"SHOW_OTHERS" => "Y",
		"PAGE" => CNext::GetFrontParametrValue("CATALOG_PAGE_URL"),
		"CATEGORY_0_TITLE" => "ALL",
		"CATEGORY_OTHERS_TITLE" => "OTHER",
		"CATEGORY_0_iblock_aspro_next_catalog" => array(
			0 => "all",
		),	
		"CATEGORY_0_iblock_aspro_next_content" => array(
			0 => "all",
		),
		"SHOW_INPUT" => "Y",
		"INPUT_ID" => "title-search-input_fixed",
		"CONTAINER_ID" => "title-search_fixed",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"SHOW_ANOUNCE" => "N",
		"PREVIEW_TRUNCATE_LEN" => "50",
		"SHOW_PREVIEW" => "Y",
		"PREVIEW_WIDTH" => "38",
		"PREVIEW_HEIGHT" => "38",
		"CONVERT_CURRENCY" => "N",
		"SHOW_INPUT_FIXED" => "Y",
		"COMPONENT_TEMPLATE" => "corp",
		"CATEGORY_0" => array(
			0 => "iblock_1c_catalog",
		),
		"CATEGORY_0_iblock_1c_catalog" => array(
			0 => "28",
		)
	),
	false,
	array(
		"ACTIVE_COMPONENT" => "Y"
	)
);?>
