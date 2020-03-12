<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( false );
global $APPLICATION;
if(!function_exists("template_content")) {
	function template_content($arResult) {
		if( !empty( $arResult ) ){
			global $arTheme;?>
			<div class="menu_top_block catalog_block">
				<ul class="menu dropdown">
					<?foreach( $arResult as $key => $arItem ){?>
						<li class="full <?=($arItem["CHILD"] ? "has-child" : "");?> <?=($arItem["SELECTED"] ? "current opened" : "");?> m_<?=strtolower($arTheme["MENU_POSITION"]["VALUE"]);?> v_<?=strtolower($arTheme["MENU_TYPE_VIEW"]["VALUE"]);?>">
							<a class="icons_fa <?=($arItem["CHILD"] ? "parent" : "");?>" href="<?=$arItem["SECTION_PAGE_URL"]?>" >
								<?if($arItem["IMAGES"] && $arTheme["LEFT_BLOCK_CATALOG_ICONS"]["VALUE"] == "Y"){?>
									<span class="image"><img src="<?=$arItem["IMAGES"]["src"];?>" alt="<?=$arItem["NAME"];?>" /></span>
								<?}?>
								<span class="name"><?=$arItem["NAME"]?>
								<?
								// if(!$arResult[$key]['elmCount'] || $arResult[$key]['elmCount'] == '') {
								// 	$arResult[$key]['elmCount'] = getCountElement($arItem['IBLOCK_ID'], $arItem["ID"]);
								// }
								?>
								(<?=CIBlockSection::GetSectionElementsCount($arItem["ID"], Array("CNT_ACTIVE" => "Y", "AVAILABLE" => "Y"));?>)
								</span>
								<div class="toggle_block"></div>
								<div class="clearfix"></div>
							</a>
							<?if($arItem["CHILD"]){?>
								<ul class="dropdown">
									<?foreach($arItem["CHILD"] as $key2 => $arChildItem){?>
										<li class="<?=($arChildItem["CHILD"] ? "has-childs" : "");?> <?if($arChildItem["SELECTED"]){?> current <?}?>">
											<?if($arChildItem["IMAGES"] && $arTheme['SHOW_CATALOG_SECTIONS_ICONS']['VALUE'] == 'Y' && $arTheme["MENU_TYPE_VIEW"]["VALUE"] !== 'BOTTOM'){?>
												<span class="image"><a href="<?=$arChildItem["SECTION_PAGE_URL"];?>"><img src="<?=$arChildItem["IMAGES"]["src"];?>" alt="<?=$arChildItem["NAME"];?>" /></a></span>
											<?}?>
											<a class="section" href="<?=$arChildItem["SECTION_PAGE_URL"];?>"><span>
											<?=$arChildItem["NAME"];?>
											<?
											// if(!$arResult[$key][$key2]['elmCount'] || $arResult[$key][$key2]['elmCount'] == '') {
											// 	$arResult[$key][$key2]['elmCount'] = getCountElement($arItem['IBLOCK_ID'], $arChildItem["ID"]);
											// }
											?>
											(<?//=$arResult[$key][$key2]['elmCount'];?>)
											</span></a>
											<?if($arChildItem["CHILD"]){?>
												<ul class="dropdown">
													<?foreach($arChildItem["CHILD"] as $key3 => $arChildItem1){?>
														<li class="menu_item <?if($arChildItem1["SELECTED"]){?> current <?}?>">
															<a class="parent1 section1" href="<?=$arChildItem1["SECTION_PAGE_URL"];?>"><span>
															<?=$arChildItem1["NAME"];?>
															<?
															// if(!$arResult[$key][$key2][$key3]['elmCount'] || $arResult[$key][$key2][$key3]['elmCount'] == '') {
															// 	$arResult[$key][$key2][$key3]['elmCount'] = getCountElement($arItem['IBLOCK_ID'], $arChildItem1["ID"]);
															// }
															?>
															(<?//=$arResult[$key][$key2][$key3]['elmCount'];?>)
															</span></a>
														</li>
													<?}?>
												</ul>
											<?}?>
											<div class="clearfix"></div>
										</li>
									<?}?>
								</ul>
							<?}?>
						</li>
					<?}?>
				</ul>
			</div>
		<?}
		return $arResult;
	}
}

if(!function_exists("getCountElement")){
	function getCountElement($iblock_id, $section_id){
		// if(!CModule::IncludeModule("iblock")){ return; }
		$ar_result = array();
		$ar_fltr = array(
			"IBLOCK_ID"=>$iblock_id,
			"SECTION_ID"=>$section_id,
			"CATALOG_AVAILABLE" => "Y",
			"INCLUDE_SUBSECTIONS" => "Y",
			"ACTIVE" => "Y"
		);
		$arProjElem = CIBlockElement::GetList(array(),$ar_fltr);
		$count = $arProjElem->SelectedRowsCount();
		return $count;                       
	}
}

$start = microtime(true);


$obCache = new CPHPCache; 
if ($obCache->InitCache('604800', 'catalog_menu')) {	// время кеширование = одна неделя
   	$vars = $obCache->GetVars(); 
   	template_content($vars);
} else if ($obCache->StartDataCache()) {
	$arResult = template_content($arResult);
	$obCache->EndDataCache($arResult);
}

$duration = microtime(true) - $start;
echo $duration;
?>