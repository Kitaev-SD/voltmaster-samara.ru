<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<footer id="footer">
    <?if(!CSite::InDir('/basket/') && !CSite::InDir('/order/') ):?>
    <div class="wrap_bg footer-subscribe">
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_DIR."include/footer/subscribe.php",
            "EDIT_TEMPLATE" => "include_area.php"
        ),
            false,
            array(
                "ACTIVE_COMPONENT" => "Y"
            )
        );?>
    </div>
    <?endif;?>
    <? if ($APPLICATION->GetCurPage(false) === '/'): ?>

        <?$APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."include/footer/footer-tizers.php",
                "EDIT_TEMPLATE" => "include_area.php"
            )
        );?>
    <? endif; ?>
    <div class="footer_inner <?=($arTheme["SHOW_BG_BLOCK"]["VALUE"] == "Y" ? "fill" : "no_fill");?>">
        <div class="wrapper_inner">
            <div class="footer_bottom_inner">
                <div class="left_block">
                    <div class="copyright">
                        <?$APPLICATION->IncludeFile(SITE_DIR."include/footer/copy/copyright.php", Array(), Array("MODE" => "html", "NAME"  => GetMessage("COPYRIGHT"), "TEMPLATE" => "include_area.php",));?>
                    </div>
                    <span class="pay_system_icons">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/copy/pay_system_icons.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("PHONE"), "TEMPLATE" => "include_area.php",));?>
			</span>
                    <div id="bx-composite-banner"></div>
                </div>
                <div class="right_block">
                    <div class="middle">
                        <div class="row">
                            <div class="item_block col-md-9 menus">
                                <div class="row wrap_md">
                                    <div class="item_block col-md-3 col-sm-4">
                                        <?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", array(
                                            "ROOT_MENU_TYPE" => "bottom_company",
                                            "MENU_CACHE_TYPE" => "Y",
                                            "MENU_CACHE_TIME" => "3600000",
                                            "MENU_CACHE_USE_GROUPS" => "N",
                                            "CACHE_SELECTED_ITEMS" => "N",
                                            "MENU_CACHE_GET_VARS" => array(),
                                            "MAX_LEVEL" => "1",
                                            "USE_EXT" => "N",
                                            "DELAY" => "N",
                                            "ALLOW_MULTI_SELECT" => "N"
                                        ),false
                                        );?>
                                    </div>
                                    <div class="item_block col-md-2 hidden-lg hidden-md hidden-sm hidden-xs img">
                                        <?$APPLICATION->IncludeComponent(
                                            "bitrix:main.include",
                                            "front",
                                            Array(
                                                "AREA_FILE_SHOW" => "file",
                                                "PATH" => SITE_DIR."include/mainpage/company/front_img.php",
                                                "EDIT_TEMPLATE" => ""
                                            )
                                        );?>
                                    </div>
                                    <div class="col-md-9 col-sm-7">
                                        <?$APPLICATION->IncludeComponent(
                                            "bitrix:main.include",
                                            "front",
                                            Array(
                                                "AREA_FILE_SHOW" => "file",
                                                "PATH" => SITE_DIR."include/mainpage/company/front_info.php",
                                                "EDIT_TEMPLATE" => ""
                                            )
                                        );?>
                                    </div>
                                </div>
                            </div>
                            <div class="item_block col-md-3 soc">
                                <div class="soc_wrapper">
                                    <div class="phones">
                                        <div class="phone_block">
                                            <div class="phone ">
                                                <i class="svg svg-phone"></i>
                                                <a rel="nofollow" href="tel:88005502911"> 8-800-55-02-911</a>
                                                <br>
                                                <i class="svg svg-phone"></i>
                                                <a class="phone--smaller" rel="nofollow" href="tel:8462022911"> 8-(846) 20-22-911</a>
                                            </div>
<!--                                            --><?//=CNext::ShowHeaderPhones();?>
                                            <?if($arTheme['SHOW_CALLBACK']['VALUE'] == 'Y'):?>
                                                <span class="order_wrap_btn">
											<span class="callback_btn animate-load" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage('CALLBACK')?></span>
										</span>
                                            <?endif;?>
                                        </div>
                                    </div>

                                    <div class="adresses">
                                        <div class="adress_block">
                                            <?$APPLICATION->IncludeComponent(
                                                "bitrix:main.include",
                                                "",
                                                Array(
                                                    "AREA_FILE_SHOW" => "file",
                                                    "AREA_FILE_SUFFIX" => "inc",
                                                    "EDIT_TEMPLATE" => "include_area.php",
                                                    "PATH" => SITE_DIR."include/footer/footer-adress.php"
                                                )
                                            );?>
                                        </div>
                                    </div>


                                    <div class="social">
                                        <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                                            array(
                                                "COMPONENT_TEMPLATE" => ".default",
                                                "PATH" => SITE_DIR."include/footer/social.info.next.default.php",
                                                "AREA_FILE_SHOW" => "file",
                                                "AREA_FILE_SUFFIX" => "",
                                                "AREA_FILE_RECURSIVE" => "Y",
                                                "EDIT_TEMPLATE" => "include_area.php"
                                            ),
                                            false
                                        );?>
                                    </div>

                                </div>

                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mobile_copy">
                <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                    array(
                        "COMPONENT_TEMPLATE" => ".default",
                        "PATH" => SITE_DIR."include/footer/copyright.php",
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "",
                        "AREA_FILE_RECURSIVE" => "Y",
                        "EDIT_TEMPLATE" => "include_area.php"
                    ),
                    false
                );?>
            </div>
        </div>
    </div>
</footer>