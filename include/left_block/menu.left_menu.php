<?global $arTheme;?>
<?$bHideCatalogMenu = (isset($arParams["HIDE_CATALOG"]) && $arParams["HIDE_CATALOG"] == "Y");?>
<?if(!CNext::IsMainPage()):?>
    <?if(CNext::IsCatalogPage()):?>
        <?if(!$bHideCatalogMenu):?>
            <?$APPLICATION->IncludeComponent(
                "bitrix:menu",
                "left_front_catalog",
                array(
                    "ROOT_MENU_TYPE" => "left",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_TIME" => "3600000",
                    "MENU_CACHE_USE_GROUPS" => "N",
                    "CACHE_SELECTED_ITEMS" => "N",
                    "MENU_CACHE_GET_VARS" => array(
                    ),
                    "MAX_LEVEL" => "4",
                    "CHILD_MENU_TYPE" => "left",
                    "USE_EXT" => "Y",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "N",
                    "COMPONENT_TEMPLATE" => "left_front_catalog"
                ),
                false,
                array(
                    "ACTIVE_COMPONENT" => "Y"
                )
            );?>
        <?endif;?>
    <?else:?>
        <?$APPLICATION->IncludeComponent(
            "bitrix:menu",
            "left_front_catalog",
            array(
                "ROOT_MENU_TYPE" => "left",
                "MENU_CACHE_TYPE" => "A",
                "MENU_CACHE_TIME" => "3600000",
                "MENU_CACHE_USE_GROUPS" => "N",
                "MENU_CACHE_GET_VARS" => array(
                ),
                "MAX_LEVEL" => "4",
                "CHILD_MENU_TYPE" => "left",
                "USE_EXT" => "Y",
                "DELAY" => "N",
                "ALLOW_MULTI_SELECT" => "N",
                "COMPONENT_TEMPLATE" => "left_front_catalog"
            ),
            false,
            array(
                "ACTIVE_COMPONENT" => "Y"
            )
        );?>
    <?endif;?>
<?elseif(!$bHideCatalogMenu):?>
    <?$APPLICATION->IncludeComponent("bitrix:menu", "left_front_catalog", array(
        "ROOT_MENU_TYPE" => "left",
        "MENU_CACHE_TYPE" => "A",
        "MENU_CACHE_TIME" => "3600000",
        "MENU_CACHE_USE_GROUPS" => "N",
        "CACHE_SELECTED_ITEMS" => "N",
        "MENU_CACHE_GET_VARS" => "",
        "MAX_LEVEL" => $arTheme["MAX_DEPTH_MENU"]["VALUE"],
        "CHILD_MENU_TYPE" => "left",
        "USE_EXT" => "Y",
        "DELAY" => "N",
        "ALLOW_MULTI_SELECT" => "N" ),
        false, array( "ACTIVE_COMPONENT" => "Y" )
    );?>
<?endif;?>