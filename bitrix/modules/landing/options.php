<?php
$module_id = 'landing';

use \Bitrix\Landing\Manager;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\SiteTemplateTable;

if (!\Bitrix\Main\Loader::includeModule('landing'))
{
	return;
}

// vars
$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();
$mid = $request->get('mid');
$backUrl = $request->get('back_url_settings');
$docRoot = Manager::getDocRoot();
$postRight = $APPLICATION->GetGroupRight($module_id);

// lang
IncludeModuleLangFile($docRoot . '/bitrix/modules/main/options.php');
Loc::loadMessages(__FILE__);

// local function for build iblocks tree
$getIblocksTree = function()
{
	static $iblocks = null;

	if ($iblocks !== null)
	{
		return $iblocks;
	}

	$iblocks = [];
	if (\Bitrix\Main\Loader::includeModule('iblock'))
	{
		// first gets types
		$iblockTypes = [];
		$res = \CIBlockType::getList();
		while($row = $res->fetch())
		{
			if ($typeLang = \CIBlockType::getByIDLang($row['ID'], LANG))
			{
				$iblockTypes[$typeLang['IBLOCK_TYPE_ID']] = [
					'NAME' => $typeLang['NAME'],
					'SORT' => $typeLang['SORT']
				];
			}
		}

		// and iblocks then
		$res = \CIBlock::getList(['sort' => 'asc']);
		while ($row = $res->GetNext(true, false))
		{
			if (!isset($iblocks[$row['IBLOCK_TYPE_ID']]))
			{
				$iblocks[$row['IBLOCK_TYPE_ID']] = [
					'ID' => $row['IBLOCK_TYPE_ID'],
					'NAME' => $iblockTypes[$row['IBLOCK_TYPE_ID']]['NAME'],
					'SORT' => $iblockTypes[$row['IBLOCK_TYPE_ID']]['SORT'],
					'ITEMS' => []
				];
			}
			$iblocks[$row['IBLOCK_TYPE_ID']]['ITEMS'][] = [
				'ID' => $row['ID'],
				'NAME' => $row['NAME']
			];
		}

		// sorting by sort
		usort($iblocks,
		  	function($a, $b)
			{
				if ($a['SORT'] == $b['SORT'])
				{
					return 0;
				}
				return ($a['SORT'] < $b['SORT']) ? -1 : 1;
			}
		);

		return $iblocks;
	}
};

if ($postRight >= 'R'):

	// sites list
	$sites = [];
	$res = \Bitrix\Main\SiteTable::getList(array(
		'select' => array(
			'*'
		),
		'filter' => array(
			'ACTIVE' => 'Y'
		),
		'order' => array(
			'SORT' => 'ASC'
		)
	));
	while ($row = $res->fetch())
	{
		$row['NAME']  = \htmlspecialcharsbx($row['NAME']);
		$sites[] = $row;
	}

	// site templates
	$allOptions[] = array(
		'site_template_id',
		Loc::getMessage('LANDING_OPT_SITE_TEMPLATE_ID') . ':',
		array('text', 32)
	);
	$allOptions[] = array(
		'header',
		Loc::getMessage('LANDING_OPT_SITE_TEMPLATE_ID_SITES')
	);
	foreach ($sites as $row)
	{
		$allOptions[] = array(
			'site_template_id_' . $row['LID'],
			$row['NAME'] . ' [' . $row['LID'] . ']:',
			array('text', 32)
		);
	}

	// paths for sites
	$allOptions[] = array(
		'header',
		Loc::getMessage('LANDING_OPT_PUB_PATH_HEADER'),
		Loc::getMessage('LANDING_OPT_PUB_PATH_HELP')
	);
	foreach ($sites as $row)
	{
		$allOptions[] = array(
			'pub_path_' . $row['LID'],
			$row['NAME'] . ' [' . $row['LID'] . ']:',
			array('text', 32),
			\Bitrix\Landing\Manager::getPublicationPathConst()
		);
	}

	// other options
	$allOptions[] = array(
		'header',
		Loc::getMessage('LANDING_OPT_OTHER')
	);
	$allOptions[] = array(
		'google_images_key',
		Loc::getMessage('LANDING_OPT_GOOGLE_IMAGES_KEY') . ':',
		array('text', 32)
	);
	if (Manager::isB24())
	{
		$allOptions[] = array(
			'portal_url',
			Loc::getMessage('LANDING_OPT_PORTAL_URL') . ':',
			array('text', 32)
		);
	}
	$allOptions[] = array(
		'deleted_lifetime_days',
		Loc::getMessage('LANDING_OPT_DELETED_LIFETIME_DAYS') . ':',
		array('text', 4)
	);
	if (Manager::isB24())
	{
		$allOptions[] = array(
			'rights_extended_mode',
			Loc::getMessage('LANDING_OPT_RIGHTS_EXTENDED_MODE') . ':',
			array('checkbox')
		);
	}
	$allOptions[] = array(
		'source_iblocks',
		Loc::getMessage('LANDING_OPT_SOURCE_IBLOCKS') . ':',
		array(
			'selectboxtree',
			$getIblocksTree(),
			'multiple="multiple" size="10"'
		)
	);

	// tabs
	$tabControl = new \CAdmintabControl('tabControl', array(
		array('DIV' => 'edit1', 'TAB' => Loc::getMessage('MAIN_TAB_SET'), 'ICON' => ''),
		array('DIV' => 'edit2', 'TAB' => Loc::getMessage('MAIN_TAB_RIGHTS'), 'ICON' => '')
	));

	// post save
	if (
		$Update . $Apply <> '' &&
		($postRight=='W' || $postRight=='X') &&
		\check_bitrix_sessid()
	)
	{
		$clearTmplCache = false;
		foreach ($allOptions as $arOption)
		{
			if ($arOption[0] == 'header')
			{
				continue;
			}
			$name = $arOption[0];
			if ($arOption[2][0] == 'text-list')
			{
				$val = '';
				for ($j = 0; $j < count($$name); $j++)
				{
					if (trim(${$name}[$j]) <> '')
					{
						$val .= ($val <> '' ? ',':'') . trim(${$name}[$j]);
					}
				}
			}
			elseif ($arOption[2][0] == 'doubletext')
			{
				$val = ${$name.'_1'} . 'x' . ${$name.'_2'};
			}
			elseif (
				$arOption[2][0] == 'selectbox' ||
				$arOption[2][0] == 'selectboxtree'
			)
			{
				$val = '';
				if (isset($$name))
				{
					for ($j=0; $j<count($$name); $j++)
					{
						if (trim(${$name}[$j]) <> '')
						{
							$val .= ($val <> '' ? ',':'') . trim(${$name}[$j]);
						}
					}
				}
			}
			else
			{
				$val = $$name;
			}

			if ($arOption[2][0] == 'checkbox' && $val<>'Y')
			{
				$val = 'N';
			}

			$val = trim($val);

			// set new references site <> templates
			$prefix = 'site_template_id_';
			if ($arOption[0] == 'site_template_id')// base template
			{
				$valOld = trim(\COption::getOptionString(
					$module_id,
					'site_template_id'
				));
				if (!$val)
				{
					$val = $valOld;
				}
				if ($valOld != $val)
				{
					$res = SiteTemplateTable::getList(array(
						'filter' => array(
							'=TEMPLATE' => $valOld
						)
					));
					while ($row = $res->fetch())
					{
						$clearTmplCache = true;
						SiteTemplateTable::update($row['ID'], [
								'TEMPLATE' => $val
							]
						);
					}
				}
			}
			elseif (strpos($arOption[0], $prefix) === 0)// individual templates
			{
				$valDefault = trim(\COption::getOptionString(
					$module_id,
					'site_template_id'
				));
				$valOld = \COption::getOptionString(
					$module_id,
					$arOption[0]
				);
				if ($valOld != $val)
				{
					$siteId = substr($arOption[0], strlen($prefix));
					$res = SiteTemplateTable::getList(array(
						'filter' => array(
							'=SITE_ID' => $siteId,
							'=TEMPLATE' => $valOld ? $valOld : $valDefault
						)
 					));
					while ($row = $res->fetch())
					{
						$clearTmplCache = true;
						SiteTemplateTable::update($row['ID'], [
								'TEMPLATE' => $val ? $val : $valDefault
							]
						);
					}
				}
			}

			\COption::setOptionString($module_id, $name, $val);
		}

		$Update = $Update . $Apply;
		if ($clearTmplCache)
		{
			Manager::getCacheManager()->clean('b_site_template');
		}

		// access settings save
		ob_start();
		require_once($docRoot . '/bitrix/modules/main/admin/group_rights.php');
		ob_end_clean();

		if ($Update <> '' && $backUrl <> '')
		{
			\LocalRedirect($backUrl);
		}
		else
		{
			\LocalRedirect(
				$APPLICATION->GetCurPage() .
				'?mid=' . urlencode($mid) .
				'&lang=' . urlencode(LANGUAGE_ID) .
				'&back_url_settings=' . urlencode($backUrl) .
				'&' . $tabControl->ActiveTabParam());
		}
	}

	?><form method="post" action="<?= $APPLICATION->GetCurPage()?>?mid=<?= urlencode($mid)?>&amp;lang=<?= LANGUAGE_ID?>"><?
	$tabControl->Begin();
	$tabControl->BeginNextTab();
	foreach($allOptions as $Option):
		if ($Option[0] == 'header')
		{
			?>
			<tr class="heading">
				<td colspan="2">
					<?= $Option[1];?>
				</td>
			</tr>
			<?if (isset($Option[2])):?>
			<tr>
				<td></td>
				<td>
					<?
					echo BeginNote();
					echo $Option[2];
					echo EndNote();
					?>
				</td>
			</tr>
			<?endif;?>
			<?
			continue;
		}
		$type = $Option[2];
		$val = \COption::getOptionString(
			$module_id,
			$Option[0],
			isset($Option[3]) ? $Option[3] : null
		);
		?>
		<tr>
			<td valign="top" width="40%"><?
				if ($type[0]=='checkbox')
				{
					echo '<label for="' . \htmlspecialcharsbx($Option[0]) . '">'.$Option[1].'</label>';
				}
				else
				{
					echo $Option[1];
				}
		?></td>
		<td valign="middle" width="60%"><?
			if ($type[0] == 'checkbox'):
				?><input type="checkbox" name="<?echo \htmlspecialcharsbx($Option[0])?>" id="<?echo \htmlspecialcharsbx($Option[0])?>" value="Y"<?if($val == 'Y') echo ' checked="checked"';?> /><?
			elseif ($type[0] == 'text'):
				?><input type="text" size="<?echo $type[1]?>" maxlength="255" value="<?echo \htmlspecialcharsbx($val)?>" name="<?echo \htmlspecialcharsbx($Option[0])?>" /><?
			elseif ($type[0] == 'doubletext'):
				list($val1, $val2) = explode('x', $val);
				?><input type="text" size="<?echo $type[1]?>" maxlength="255" value="<?echo \htmlspecialcharsbx($val1)?>" name="<?echo \htmlspecialcharsbx($Option[0].'_1')?>" /><?
				?><input type="text" size="<?echo $type[1]?>" maxlength="255" value="<?echo \htmlspecialcharsbx($val2)?>" name="<?echo \htmlspecialcharsbx($Option[0].'_2')?>" /><?
			elseif ($type[0] == 'textarea'):
				?><textarea rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo \htmlspecialcharsbx($Option[0])?>"><?echo \htmlspecialcharsbx($val)?></textarea><?
			elseif ($type[0] == 'text-list'):
				$aVal = explode(",", $val);
				for($j=0; $j<count($aVal); $j++):
					?><input type="text" size="<?echo $type[2]?>" value="<?echo \htmlspecialcharsbx($aVal[$j])?>" name="<?echo \htmlspecialcharsbx($Option[0]).'[]'?>" /><br /><?
				endfor;
				for($j=0; $j<$type[1]; $j++):
					?><input type="text" size="<?echo $type[2]?>" value="" name="<?echo \htmlspecialcharsbx($Option[0]).'[]'?>" /><br /><?
				endfor;
			elseif ($type[0] == 'selectbox'):
				$arr = $type[1];
				$arr_keys = array_keys($arr);
				$currValue = explode(',', $val);
				?><select name="<?echo \htmlspecialcharsbx($Option[0])?>[]"<?= $type[2]?>><?
					for($j = 0; $j < count($arr_keys); $j++):
						?><option value="<?echo $arr_keys[$j]?>"<?if(in_array($arr_keys[$j], $currValue))echo ' selected="selected"'?>><?echo \htmlspecialcharsbx($arr[$arr_keys[$j]])?></option><?
					endfor;
					?></select><?
			elseif ($type[0] == 'selectboxtree'):
				$arr = $type[1];
				$currValue = explode(',', $val);

				$output = '<select name="'.\htmlspecialcharsbx($Option[0]).'[]"'.$type[2].'>';
				$output .= '<option></option>';
				foreach ($getIblocksTree() as $rowType)
				{
					$strIBlocksCpGr = '';
					foreach ($rowType['ITEMS'] as $rowIb)
					{
						if (in_array($rowIb['ID'], $currValue))
						{
							$sel = ' selected="selected"';
						}
						else
						{
							$sel = '';
						}
						$strIBlocksCpGr .= '<option value="' . $rowIb['ID'] . '"' . $sel . '>' .
										   		$rowIb['NAME'] .
										   '</option>';
					}
					if ($strIBlocksCpGr != '')
					{
						$output .= '<optgroup label="'.$rowType['NAME'].'">';
						$output .= $strIBlocksCpGr;
						$output .= '</optgroup>';
					}
				}
				$output .= '</select>';
				echo $output;
			endif;
			echo $Option[4];?>
		</td>
		<?
	endforeach;

	// access tab
	$tabControl->BeginNextTab();
	require_once($docRoot . '/bitrix/modules/main/admin/group_rights.php');

	$tabControl->Buttons();
	?>
	<input <?if ($postRight < 'W') echo 'disabled="disabled"' ?> type="submit" name="Update" value="<?= Loc::getMessage('MAIN_SAVE')?>" title="<?= Loc::getMessage('MAIN_OPT_SAVE_TITLE')?>" />
	<input <?if ($postRight < 'W') echo 'disabled="disabled"' ?> type="submit" name="Apply" value="<?= Loc::getMessage('MAIN_OPT_APPLY')?>" title="<?= Loc::getMessage('MAIN_OPT_APPLY_TITLE')?>" />
	<?if ($backUrl <> ''):?>
		<input <?if ($postRight < 'W') echo 'disabled="disabled"' ?> type="button" name="Cancel" value="<?= Loc::getMessage('MAIN_OPT_CANCEL')?>" title="<?= Loc::getMessage('MAIN_OPT_CANCEL_TITLE')?>" onclick="window.location='<?echo \htmlspecialcharsbx(CUtil::addslashes($backUrl))?>'" />
		<input type="hidden" name="back_url_settings" value="<?=\htmlspecialcharsbx($backUrl)?>" />
	<?endif?>
	<?=bitrix_sessid_post();?>
	<?$tabControl->End();?>
	</form>

<?endif;?>
