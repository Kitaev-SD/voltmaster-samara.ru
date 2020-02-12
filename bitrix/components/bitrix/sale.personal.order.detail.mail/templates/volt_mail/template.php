<style>
		body
		{
			font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
			font-size: 14px;
			color: #000;
		}
		td{
			border: 1px solid #ddd;
			border-collapse: collapse;
		}
</style>

<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<p class="bx_order_list">
	<?if(strlen($arResult["ERROR_MESSAGE"])):?>
		<?=ShowError($arResult["ERROR_MESSAGE"]);?>
	<?else:?>	
		<?if($arParams["SHOW_ORDER_BASE"]=='Y' || $arParams["SHOW_ORDER_USER"]=='Y' || $arParams["SHOW_ORDER_PARAMS"]=='Y' || $arParams["SHOW_ORDER_BUYER"]=='Y' || $arParams["SHOW_ORDER_DELIVERY"]=='Y' || $arParams["SHOW_ORDER_PAYMENT"]=='Y'):?>
		
		
		
		<table class="bx_order_list_table" cellpadding="0" cellspacing="0" width="850" style="background-color: #d1d1d1; border-radius: 2px; border:1px solid #d1d1d1; margin: 0 auto;" border="1" bordercolor="#d1d1d1">

			<thead>
				<tr>
					<td colspan="2" height="83" width="850" bgcolor="#eaf3f5" style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;">
						<table cellpadding="0" cellspacing="0" width="100%">
						<tbody>
						<tr>
							<td bgcolor="#ffffff" height="75" style="font-weight: bold; text-align: center; font-size: 26px; color: #0b3961;">
								 Вами оформлен заказ в магазине Вольтмастер-Самара
							</td>
						</tr>
						<tr>
							<td bgcolor="#bad3df" height="11">
							</td>
						</tr>
						</tbody>
						</table>
					</td>
				</tr>
			
				<tr>
					<td colspan="2" width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
						<p style="margin-top:30px; margin-bottom: 28px; font-weight: bold; font-size: 19px;">
							 Уважаемый <?=$arResult["USER_NAME"]?>,
						</p>
						<p style="margin-top: 0; margin-bottom: 20px; line-height: 20px;">
							Ваш заказ номер <?=$arResult["ACCOUNT_NUMBER"]?>
						<?if(strlen($arResult["DATE_INSERT_FORMATED"])):?>
						 <?=GetMessage("SPOD_FROM")?> <?=$arResult["DATE_INSERT_FORMATED"]?>
						<?endif?>
						 принят
						</p>

					</td>
				</tr>
			</thead>
			<tbody>
			<?if($arParams["SHOW_ORDER_BASE"]=='Y'):?>
				<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
					<td bgcolor="#ddd">
						<?=GetMessage('SPOD_ORDER_STATUS')?>:
					</td>
					<td>
						<?=htmlspecialcharsbx($arResult["STATUS"]["NAME"])?>
						<?if(strlen($arResult["DATE_STATUS_FORMATED"])):?>
							(<?=GetMessage("SPOD_FROM")?> <?=$arResult["DATE_STATUS_FORMATED"]?>)
						<?endif?>
					</td>
				</tr>
				<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
					<td bgcolor="#ddd">
						<?=GetMessage('SPOD_ORDER_PRICE')?>:
					</td>
					<td>
						<?=$arResult["PRICE_FORMATED"]?>
						<?if(floatval($arResult["SUM_PAID"])):?>
							(<?=GetMessage('SPOD_ALREADY_PAID')?>:&nbsp;<?=$arResult["SUM_PAID_FORMATED"]?>)
						<?endif?>
					</td>
				</tr>

				<?/*if($arResult["CANCELED"] == "Y" || $arResult["CAN_CANCEL"] == "Y"):?>
					<tr>
						<td><?=GetMessage('SPOD_ORDER_CANCELED')?>:</td>
						<td>
							<?if($arResult["CANCELED"] == "Y"):?>
								<?=GetMessage('SPOD_YES')?>
								<?if(strlen($arResult["DATE_CANCELED_FORMATED"])):?>
									(<?=GetMessage('SPOD_FROM')?> <?=$arResult["DATE_CANCELED_FORMATED"]?>)
								<?endif?>
							<?elseif($arResult["CAN_CANCEL"] == "Y"):?>
								<?=GetMessage('SPOD_NO')?>&nbsp;&nbsp;&nbsp;[<a href="<?=$arResult["URL_TO_CANCEL"]?>"><?=GetMessage("SPOD_ORDER_CANCEL")?></a>]
							<?endif?>
						</td>
					</tr>
				<?endif*/?>
				<tr height="23" width="850" bgcolor="#f7f7f7" style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;"><td colspan="2" style="border:none"></td></tr>
			<?endif?>
				
			<?if($arParams["SHOW_ORDER_USER"]=='Y'):?>
				<?if(intval($arResult["USER_ID"])):?>

					<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
						<td colspan="2"><?=GetMessage('SPOD_ACCOUNT_DATA')?></td>
					</tr>
					<?if(strlen($arResult["USER_NAME"])):?>
						<tr height="83" width="850" bgcolor="#f7f7f7" style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;">
							<td bgcolor="#ddd"><?=GetMessage('SPOD_ACCOUNT')?>:</td>
							<td><?=htmlspecialcharsbx($arResult["USER_NAME"])?></td>
						</tr>
					<?endif?>
					<tr height="83" width="850" bgcolor="#f7f7f7" style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;">
						<td bgcolor="#ddd"><?=GetMessage('SPOD_LOGIN')?>:</td>
						<td><?=htmlspecialcharsbx($arResult["USER"]["LOGIN"])?></td>
					</tr>
					<tr height="83" width="850" bgcolor="#f7f7f7" style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;">
						<td bgcolor="#ddd"><?=GetMessage('SPOD_EMAIL')?>:</td>
						<td><a href="mailto:<?=htmlspecialcharsbx($arResult["USER"]["EMAIL"])?>"><?=htmlspecialcharsbx($arResult["USER"]["EMAIL"])?></a></td>
					</tr>

					<tr height="23" width="850" bgcolor="#f7f7f7" style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;"><td colspan="2" style="border:none"></td></tr>

				<?endif?>
			<?endif?>

			<?if($arParams["SHOW_ORDER_PARAMS"]=='Y'):?>
				<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
					<td colspan="2"><?=GetMessage('SPOD_ORDER_PROPERTIES')?></td>
				</tr>
				<tr height="83" width="850" bgcolor="#f7f7f7" style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;">
					<td bgcolor="#ddd"><?=GetMessage('SPOD_ORDER_PERS_TYPE')?>:</td>
					<td><?=htmlspecialcharsbx($arResult["PERSON_TYPE"]["NAME"])?></td>
				</tr>
			<?endif?>
			
			<?if($arParams["SHOW_ORDER_BUYER"]=='Y'):?>
				<?foreach($arResult["ORDER_PROPS"] as $prop):?>

					<?/*if($prop["SHOW_GROUP_NAME"] == "Y"):?>

						<tr height="23" width="850" bgcolor="#f7f7f7" style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;"><td colspan="2" style="border:none"></td></tr>
						<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
							<td colspan="2"><?=$prop["GROUP_NAME"]?></td>
						</tr>

					<?endif*/?>

					<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
						<td bgcolor="#ddd"><?=$prop['NAME']?>:</td>
						<td>

							<?if($prop["TYPE"] == "Y/N"):?>
								<?=GetMessage('SPOD_'.($prop["VALUE"] == "Y" ? 'YES' : 'NO'))?>
							<?elseif ($prop["TYPE"] == "FILE"):?>
								<?=$prop["VALUE"]?>
							<?else:?>
								<?=htmlspecialcharsbx($prop["VALUE"])?>
							<?endif?>

						</td>
					</tr>

				<?endforeach?>

				
				<?if(!empty($arResult["USER_DESCRIPTION"])):?>

					<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
						<td bgcolor="#ddd"><?=GetMessage('SPOD_ORDER_USER_COMMENT')?>:</td>
						<td><?=$arResult["USER_DESCRIPTION"]?></td>
					</tr>

				<?endif?>

				<tr height="23" width="850" bgcolor="#f7f7f7" style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;"><td colspan="2" style="border:none"></td></tr>
			<?endif?>

			<?if($arParams["SHOW_ORDER_PAYMENT"]=='Y'):?>
				<?/*<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
					<td colspan="2"><?=GetMessage("SPOD_ORDER_PAYMENT")?></td>
				</tr>*/?>
				<?/*<tr>
					<td><?=GetMessage('SPOD_PAY_SYSTEM')?>:</td>
					<td>
						<?if(intval($arResult["PAY_SYSTEM_ID"])):?>
							<?=htmlspecialcharsbx($arResult["PAY_SYSTEM"]["NAME"])?>
						<?else:?>
							<?=GetMessage("SPOD_NONE")?>
						<?endif?>
					</td>
				</tr>?>
				<tr>
					<td><?=GetMessage('SPOD_ORDER_PAYED')?>:</td>
					<td>
						<?if($arResult["PAYED"] == "Y"):?>
							<?=GetMessage('SPOD_YES')?>
							<?if(strlen($arResult["DATE_PAYED_FORMATED"])):?>
								(<?=GetMessage('SPOD_FROM')?> <?=$arResult["DATE_PAYED_FORMATED"]?>)
							<?endif?>
						<?else:?>
							<?=GetMessage('SPOD_NO')?>
							<?if($arResult["CAN_REPAY"]=="Y" && $arResult["PAY_SYSTEM"]["PSA_NEW_WINDOW"] == "Y"):?>
								&nbsp;&nbsp;&nbsp;[<a href="<?=$arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"]?>" target="_blank"><?=GetMessage("SPOD_REPEAT_PAY")?></a>]
							<?endif?>
						<?endif?>
					</td>
				</tr>*/?>

				<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
					<td bgcolor="#ddd"><?=GetMessage("SPOD_ORDER_DELIVERY")?>:</td>
					<td>
						<?if(strpos($arResult["DELIVERY_ID"], ":") !== false || intval($arResult["DELIVERY_ID"])):?>
							<?=htmlspecialcharsbx($arResult["DELIVERY"]["NAME"])?>

							<?if(intval($arResult['STORE_ID']) && !empty($arResult["DELIVERY"]["STORE_LIST"][$arResult['STORE_ID']])):?>

								<?$store = $arResult["DELIVERY"]["STORE_LIST"][$arResult['STORE_ID']];?>
								<div class="bx_ol_store">
									<div class="bx_old_s_row_title">
										<?=GetMessage('SPOD_TAKE_FROM_STORE')?>: <b><?=$store['TITLE']?></b>

										<?if(!empty($store['DESCRIPTION'])):?>
											<div class="bx_ild_s_desc">
												<?=$store['DESCRIPTION']?>
											</div>
										<?endif?>

									</div>
									
									<?if(!empty($store['ADDRESS'])):?>
										<div class="bx_old_s_row">
											<b><?=GetMessage('SPOD_STORE_ADDRESS')?></b>: <?=$store['ADDRESS']?>
										</div>
									<?endif?>

									<?if(!empty($store['SCHEDULE'])):?>
										<div class="bx_old_s_row">
											<b><?=GetMessage('SPOD_STORE_WORKTIME')?></b>: <?=$store['SCHEDULE']?>
										</div>
									<?endif?>

									<?if(!empty($store['PHONE'])):?>
										<div class="bx_old_s_row">
											<b><?=GetMessage('SPOD_STORE_PHONE')?></b>: <?=$store['PHONE']?>
										</div>
									<?endif?>

									<?if(!empty($store['EMAIL'])):?>
										<div class="bx_old_s_row">
											<b><?=GetMessage('SPOD_STORE_EMAIL')?></b>: <a href="mailto:<?=$store['EMAIL']?>"><?=$store['EMAIL']?></a>
										</div>
									<?endif?>
								</div>

							<?endif?>

						<?else:?>
							<?=GetMessage("SPOD_NONE")?>
						<?endif?>
					</td>
				</tr>

				<?if($arResult["TRACKING_NUMBER"]):?>

					<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
						<td bgcolor="#ddd"><?=GetMessage('SPOD_ORDER_TRACKING_NUMBER')?>:</td>
						<td><?=$arResult["TRACKING_NUMBER"]?></td>
					</tr>

					<tr height="23" width="850" bgcolor="#f7f7f7" style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;"><td colspan="2" style="border:none"></td></tr>

				<?endif?>
			<?endif?>
			<?if($arParams["SHOW_ORDER_BASKET"]=='Y'):?>
			<tr height="23" width="850" bgcolor="#f7f7f7" style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;"><td colspan="2" style="border:none"></td></tr>
			<tr height="23" width="850" bgcolor="#f7f7f7" style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;"><td colspan="2" style="border:none"></td></tr>
			
			<tr>
			<tr width="850" bgcolor="#f7f7f7" style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;">
				<td colspan="2">
				<h3><?=GetMessage('SPOD_ORDER_BASKET')?></h3>
				</td>
			</tr>
			<td colspan="2" style="border:0">
					<table class="bx_order_list_table_order" width="100%">
			<thead>
				<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">			
					<?
					foreach ($arParams["CUSTOM_SELECT_PROPS"] as $headerId):						
						if($headerId == 'PICTURE' && in_array('NAME', $arParams["CUSTOM_SELECT_PROPS"]))
							continue;
							
						$colspan = "";
						if($headerId == 'NAME' && in_array('PICTURE', $arParams["CUSTOM_SELECT_PROPS"]))
							$colspan = 'colspan="2"';
						
						$headerName = GetMessage('SPOD_'.$headerId);
						if(strlen($headerName)<=0)
						{
							foreach(array_values($arResult['PROPERTY_DESCRIPTION']) as $prop_head_desc):
								if(array_key_exists($headerId, $prop_head_desc))
									$headerName = $prop_head_desc[$headerId]['NAME'];
							endforeach;
						}
						?><td <?=$colspan?>><?=$headerName?></td><?
					endforeach;
					?>
				</tr>
			</thead>
			<tbody>
				<?//echo "<pre>".print_r($arParams['CUSTOM_SELECT_PROPS'], true).print_R($arResult["BASKET"], true)."</pre>"?>
				<?
				foreach($arResult["BASKET"] as $prod):
					?><tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;"><?
					
					$hasLink = !empty($prod["DETAIL_PAGE_URL"]);
					$actuallyHasProps = is_array($prod["PROPS"]) && !empty($prod["PROPS"]);
					
					foreach ($arParams["CUSTOM_SELECT_PROPS"] as $headerId):
						
						?><td class="custom"><?
						
						if($headerId == "NAME"):
							
							if($hasLink):
								?><a href="<?=$prod["DETAIL_PAGE_URL"]?>" target="_blank"><?
							endif;
							?><?=$prod["NAME"]?><?
							if($hasLink):
								?></a><?
							endif;
							
						elseif($headerId == "PICTURE"):
							
							if($hasLink):
								?><a href="<?=$prod["DETAIL_PAGE_URL"]?>" target="_blank"><?
							endif;
							if($prod['PICTURE']['SRC']):
								?><img src="<?=$prod['PICTURE']['SRC']?>" width="<?=$prod['PICTURE']['WIDTH']?>" height="<?=$prod['PICTURE']['HEIGHT']?>" alt="<?=$prod['NAME']?>" /><?
							endif;
							if($hasLink):
								?></a><?
							endif;
							
						elseif($headerId == "PROPS" && $arResult['HAS_PROPS'] && $actuallyHasProps):
							
							?>
							<table cellspacing="0" class="bx_ol_sku_prop">
								<?foreach($prod["PROPS"] as $prop):?>
									<tr>
										<td><nobr><?=htmlspecialcharsbx($prop["NAME"])?>:</nobr></td>
										<td style="padding-left: 10px !important"><b><?=htmlspecialcharsbx($prop["VALUE"])?></b></td>
									</tr>
								<?endforeach?>
							</table>
							<?

						elseif($headerId == "QUANTITY"):
						
							?>
							<?=$prod["QUANTITY"]?>
							<?if(strlen($prod['MEASURE_TEXT'])):?>
								<?=$prod['MEASURE_TEXT']?>
							<?else:?>
								<?=GetMessage('SPOD_DEFAULT_MEASURE')?>
							<?endif?>
							<?
							
						else:
							$headerId = strtoupper($headerId);
							echo $prod[(strpos($headerId, 'PROPERTY_')===0 ? $headerId."_VALUE" : $headerId)];
						endif;
						
						?></td><?
						
					endforeach;
					
					?></tr><?
					
				endforeach;
				?>
			</tbody>
		</table>
			</td>
			</tr>
			<?endif?>
			<?if($arParams["SHOW_ORDER_SUM"]=='Y'):?>
			<tr height="23" width="850" bgcolor="#f7f7f7" style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;"><td colspan="2" style="border:none"></td></tr>
			<tr>
			<td colspan="2" style="border:0">
					<table class="bx_ordercart_order_sum" width="100%">
			<tbody>

				<? ///// WEIGHT ?>
				<?if(floatval($arResult["ORDER_WEIGHT"])):?>
					<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
						<td class="custom_t1"><?=GetMessage('SPOD_TOTAL_WEIGHT')?>:</td>
						<td class="custom_t2"><?=$arResult['ORDER_WEIGHT_FORMATED']?></td>
					</tr>
				<?endif?>

				<? ///// PRICE SUM ?>
				<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
					<td class="custom_t1"><?=GetMessage('SPOD_PRODUCT_SUM')?>:</td>
					<td class="custom_t2"><?=$arResult['PRODUCT_SUM_FORMATED']?></td>
				</tr>

				<? ///// DELIVERY PRICE: print even equals 2 zero ?>
				<?if(strlen($arResult["PRICE_DELIVERY_FORMATED"])):?>
					<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
						<td class="custom_t1"><?=GetMessage('SPOD_DELIVERY')?>:</td>
						<td class="custom_t2"><?=$arResult["PRICE_DELIVERY_FORMATED"]?></td>
					</tr>
				<?endif?>

				<? ///// TAXES DETAIL ?>
				<?foreach($arResult["TAX_LIST"] as $tax):?>
					<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
						<td class="custom_t1"><?=$tax["TAX_NAME"]?>:</td>
						<td class="custom_t2"><?=$tax["VALUE_MONEY_FORMATED"]?></td>
					</tr>	
				<?endforeach?>

				<? ///// TAX SUM ?>
				<?if(floatval($arResult["TAX_VALUE"])):?>
					<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
						<td class="custom_t1"><?=GetMessage('SPOD_TAX')?>:</td>
						<td class="custom_t2"><?=$arResult["TAX_VALUE_FORMATED"]?></td>
					</tr>
				<?endif?>

				<? ///// DISCOUNT ?>
				<?if(floatval($arResult["DISCOUNT_VALUE"])):?>
					<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
						<td class="custom_t1"><?=GetMessage('SPOD_DISCOUNT')?>:</td>
						<td class="custom_t2"><?=$arResult["DISCOUNT_VALUE_FORMATED"]?></td>
					</tr>
				<?endif?>

				<tr width="850" bgcolor="#f7f7f7" valign="top" style="border: none; padding-top: 0; padding-right: 44px; padding-bottom: 16px; padding-left: 44px;">
					<td class="custom_t1 fwb"><?=GetMessage('SPOD_SUMMARY')?>:</td>
					<td class="custom_t2 fwb"><?=$arResult["PRICE_FORMATED"]?></td>
				</tr>
			</tbody>
		</table>
			</td>
			</tr>
			<?endif?>
		<tr height="23" width="850" bgcolor="#f7f7f7" style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;"><td colspan="2" style="border:none"></td></tr>
		<tr>
		<td colspan="2" width="850" bgcolor="#f7f7f7" style="border: none; padding-top: 23px; padding-right: 17px; padding-bottom: 24px; padding-left: 17px;">
			<p style="margin-top: 0; margin-bottom: 20px; line-height: 20px;">
				<br>
			</p>
			<p style="margin-top: 0; margin-bottom: 20px; line-height: 20px;">
				<br>
			</p>
			<p style="line-height:20px;margin-bottom:20px;margin-top:0">
		
				 Вы можете следить за выполнением своего заказа (на какой стадии выполнения он находится), войдя в Ваш персональный раздел сайта Вольтмастер-Самара.
			</p>
					<p style="margin-top: 0; margin-bottom: 20px; line-height: 20px;">
				<br>
			</p>
			<p style="margin-top: 0; margin-bottom: 20px; line-height: 20px;">
				<span style="background-color: #d1d1d1;">Для получения подробной информации по заказу пройдите на сайт&nbsp;</span><a href="https://<?=$_SERVER['HTTP_HOST']?><?=$arParams['PATH_TO_LIST']?><?=$arResult['ACCOUNT_NUMBER']?>" style="background-color: #d1d1d1;">https://<?=$_SERVER['HTTP_HOST']?><?=$arParams['PATH_TO_LIST']?><?=$arResult['ACCOUNT_NUMBER']?></a><br>
			</p>
			<p style="margin-top: 0; margin-bottom: 20px; line-height: 20px;">
	 <br>
				 Обратите внимание, что для входа в этот раздел Вам необходимо будет ввести логин и пароль пользователя сайта Вольтмастер-Самара.<br>
	 <br>
				 Для того, чтобы аннулировать заказ, воспользуйтесь функцией отмены заказа, которая доступна в Вашем персональном разделе сайта Вольтмастер-Самара.<br>
	 <br>
				 Пожалуйста, при обращении к администрации сайта Вольтмастер-Самара ОБЯЗАТЕЛЬНО указывайте номер Вашего заказа - <?=$arResult["ACCOUNT_NUMBER"]?>.<br>
	 <br>
				 Спасибо за покупку!<br>
			</p>
		
		</td>
		</tr>
			
			</tbody>
			
			
			
			
			
		</table>

	<?endif?>
</p>
