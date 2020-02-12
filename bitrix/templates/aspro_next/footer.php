						<?CNext::checkRestartBuffer();?>
						<?IncludeTemplateLangFile(__FILE__);?>
							<?if(!$isIndex):?>
								<?if($isBlog):?>
									</div> <?// class=col-md-9 col-sm-9 col-xs-8 content-md?>
									<div class="col-md-3 col-sm-3 hidden-xs hidden-sm right-menu-md">
										<div class="sidearea">
											<?$APPLICATION->ShowViewContent('under_sidebar_content');?>
											<?CNext::get_banners_position('SIDE', 'Y');?>
											<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "sect", "AREA_FILE_SUFFIX" => "sidebar", "AREA_FILE_RECURSIVE" => "Y"), false);?>
										</div>
									</div>
								</div><?endif;?>
								<?if($isHideLeftBlock && !$isWidePage):?>
									</div> <?// .maxwidth-theme?>
								<?endif;?>
								</div> <?// .container?>
							<?else:?>
								<?CNext::ShowPageType('indexblocks');?>
							<?endif;?>
							<?CNext::get_banners_position('CONTENT_BOTTOM');?>
						</div> <?// .middle?>
					<?//if(!$isHideLeftBlock && !$isBlog):?>
					<?if(($isIndex && $isShowIndexLeftBlock) || (!$isIndex && !$isHideLeftBlock) && !$isBlog):?>
						</div> <?// .right_block?>				
						<?if($APPLICATION->GetProperty("HIDE_LEFT_BLOCK") != "Y" && !defined("ERROR_404")):?>
							<div class="left_block">
								<?CNext::ShowPageType('left_block');?>
							</div>
						<?endif;?>
					<?endif;?>
				<?if($isIndex):?>
					</div>
				<?elseif(!$isWidePage):?>
					</div> <?// .wrapper_inner?>				
				<?endif;?>
			</div> <?// #content?>
			<?CNext::get_banners_position('FOOTER');?>
		</div><?// .wrapper?>
		<footer id="footer">
      <?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	".default", 
	array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => SITE_DIR."include/mainpage/comp_tizers.php",
		"EDIT_TEMPLATE" => "standard.php",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false,
	array(
		"ACTIVE_COMPONENT" => "N"
	)
);?>
			<?if($APPLICATION->GetProperty("viewed_show") == "Y" || $is404):?>
				<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	"basket", 
	array(
		"COMPONENT_TEMPLATE" => "basket",
		"PATH" => SITE_DIR."include/footer/comp_viewed.php",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"AREA_FILE_RECURSIVE" => "Y",
		"EDIT_TEMPLATE" => "standard.php",
		"PRICE_CODE" => array(
			0 => "Розничная",
		),
		"STORES" => array(
			0 => "1",
			1 => "",
		),
		"BIG_DATA_RCM_TYPE" => "bestsell",
		"STIKERS_PROP" => "HIT",
		"SALE_STIKER" => "SALE_TEXT",
		"SHOW_DISCOUNT_PERCENT_NUMBER" => "N"
	),
	false
);?>					
			<?endif;?>
			<?CNext::ShowPageType('footer');?>
		</footer>
		<div class="bx_areas">
			<?CNext::ShowPageType('bottom_counter');?>
		</div>
		<?CNext::ShowPageType('search_title_component');?>
		<?CNext::setFooterTitle();
		CNext::showFooterBasket();?>
<script>

setTimeout(function() {
	[].forEach.call(document.querySelectorAll('img[data-src]'), function(img) {
        img.setAttribute('src', img.getAttribute('data-src'));
        img.onload = function() {
            img.removeAttribute('data-src');
			img.classList.add("lazy-view");
        };
    });

	[].forEach.call(document.querySelectorAll('div[data-style]'), function(img) {
		img.setAttribute('style', img.getAttribute('data-style'));
        img.onload = function() {
            img.removeAttribute('data-style');
			img.classList.add("lazy-view");
        };		
    });	

	[].forEach.call(document.querySelectorAll('a[data-style]'), function(img) {
		img.setAttribute('style', img.getAttribute('data-style'));
        img.onload = function() {
            img.removeAttribute('data-style');
			img.classList.add("lazy-view");
        };		
    });	

	[].forEach.call(document.querySelectorAll('img[load-src]'), function(img) {
		img.setAttribute('src', img.getAttribute('load-src'));
		img.onload = function() {
			img.removeAttribute('load-src');
			img.classList.add("lazy-view");
		};		
	});		
	
}, 3500);

$(function(){
	$('body').on('click', '.tab_slider_wrapp ul.tabs > li', function() {
		setTimeout(function() {
			[].forEach.call(document.querySelectorAll('img[load-src]'), function(img) {
				img.setAttribute('src', img.getAttribute('load-src'));
				img.onload = function() {
					img.removeAttribute('load-src');
					img.classList.add("lazy-view");
				};		
			});				
		}, 2500);
	});
});

</script>
<?
###Скрытие быстрого заказа для авторизованных пользователей
global $USER;
if ($USER->IsAuthorized()):?>
    <style>
        .fastorder {
            display: none !important;
        } 
		.fast_order {
            display: none !important;
        }
    </style>
<?endif;?>
	</body>
</html>
