<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if (empty($arResult["CATEGORIES"])) return;?>
<div class="bx_searche scrollbar">
	<br>

	<?
	$categ_count = 20;
	$prod_count = 15;
	$uniqeNameArr = $relev_prod = $other_prod = $relev_sect = $other_sect = array();
	$name_search_ = trim($arResult['query']);

	$k = 0;

	$resTv = CIBlockSection::GetList(Array(), Array("IBLOCK_ID"=>28, "%NAME"=>$name_search_), false, false, Array());
	while($arItem = $resTv->GetNext()){
	    if(!in_array($arItem["NAME"], $uniqeNameArr)){
	    	$uniqeNameArr[] = $arItem["NAME"];
	    	$name_ = strtolower(strip_tags($arItem['NAME']));
		    if(strpos($name_, $name_search_) == 0) {
		    	$relev_sect[] = $arItem;
		    } else {
		    	$other_sect[] = $arItem;
		    }
		}
	}

	foreach($arResult["CATEGORIES"] as $arCategory){
		foreach($arCategory["ITEMS"] as $arItem) {
			if (CModule::IncludeModule("iblock")) {
				$res = CIBlockElement::GetByID($arItem['ITEM_ID']);

				if ($ar_res = $res->GetNext()) { 
					$SECTION_ID = $ar_res['IBLOCK_SECTION_ID']; 
				}

				if (!empty($SECTION_ID)) {
					$res = CIBlockSection::GetByID($SECTION_ID);
					if($ar_res = $res->GetNext()){
						if(!in_array($ar_res["NAME"], $uniqeNameArr)){
							$uniqeNameArr[] = $ar_res["NAME"];
							$name_ = strtolower(strip_tags($ar_res['NAME']));
						    if(strpos($name_, $name_search_) === 0) {
						    	$relev_sect[] = $ar_res;
						    } else {
						    	$other_sect[] = $ar_res;
						    }
						}
					}
				}
			}
		}
	}

	$arSortedSection = $relev_sect + $other_sect;

	foreach ($arSortedSection as $arItem) {
		if($k >= $categ_count){break;}
		$k++;?>
		<div class="bx_item_element categ_name">
			<a class="all_result_title btn btn-default white bold" href="<?=$arItem['SECTION_PAGE_URL']?>"><?=mb_ucfirst($arItem["NAME"])?></a>
		</div>
	<?}
#-----------------------------------------------------------------------------------------------------------------------------------------------------
	$t = 0;
	foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
		<?foreach($arCategory["ITEMS"] as $i => $arItem):?>
			<?if($t >= $prod_count){break;}?>
			<?//=$arCategory["TITLE"]?>
			<?if($category_id === "all"):
				$t++;?>
				<div class="bx_item_block all_result">
					<div class="maxwidth-theme">
						<div class="bx_item_element">
							<a class="all_result_title btn btn-default white bold" href="<?=$arItem["URL"]?>"><?=$arItem["NAME"]?></a>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			<?elseif(isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]])):
				$t++;
				$arElement = $arResult["ELEMENTS"][$arItem["ITEM_ID"]];?>
				<a class="bx_item_block" href="<?=$arItem["URL"]?>">
					<div class="maxwidth-theme">
						<div class="bx_img_element">
							<?if(is_array($arElement["PICTURE"])):?>
								<img src="<?=$arElement["PICTURE"]["src"]?>">
							<?else:?>
								<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_small.png" width="38" height="38">
							<?endif;?>
						</div>
						<div class="bx_item_element">
							<span><?=$arItem["NAME"]?></span>
							<div class="price cost prices">
								<div class="title-search-price">
									<?if($arElement["MIN_PRICE"]){?>
										<?if($arElement["MIN_PRICE"]["DISCOUNT_VALUE"] < $arElement["MIN_PRICE"]["VALUE"]):?>
											<div class="price"><?=$arElement["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?></div>
											<div class="price discount">
												<strike><?=$arElement["MIN_PRICE"]["PRINT_VALUE"]?></strike>
											</div>
										<?else:?>
											<div class="price"><?=$arElement["MIN_PRICE"]["PRINT_VALUE"]?></div>
										<?endif;?>
									<?}else{?>
										<?foreach($arElement["PRICES"] as $code=>$arPrice):?>
											<?if($arPrice["CAN_ACCESS"]):?>
												<?if (count($arElement["PRICES"])>1):?>
													<div class="price_name"><?=$arResult["PRICES"][$code]["TITLE"];?></div>
												<?endif;?>
												<?if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
													<div class="price"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></div>
													<div class="price discount">
														<strike><?=$arPrice["PRINT_VALUE"]?></strike>
													</div>
												<?else:?>
													<div class="price"><?=$arPrice["PRINT_VALUE"]?></div>
												<?endif;?>
											<?endif;?>
										<?endforeach;?>
									<?}?>
								</div>
							</div>
						</div>
						<div style="clear:both;"></div>
					</div>
				</a>
			<?else:
				$t++;?>
				<?if($arItem["MODULE_ID"]):?>
					<a class="bx_item_block others_result" href="<?=$arItem["URL"]?>">
						<div class="maxwidth-theme">
							<div class="bx_item_element">
								<span><?=$arItem["NAME"]?></span>
							</div>
							<div style="clear:both;"></div>
						</div>
					</a>
				<?endif;?>
			<?endif;?>
		<?endforeach;?>
	<?endforeach;?>
</div>

<?function mb_ucfirst($string) {
    $string = mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
    return $string;
}?>