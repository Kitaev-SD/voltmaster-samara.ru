<?php
exit;
# 0 0 * * 0 /usr/bin/php -f /home/bitrix/www/SetHighloadBlock.php &>> /home/bitrix/www/SetHighloadBlock.log &
$iblock_id = 28; 		# ID инфоблока



$startTime = microtime(true);
$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/www";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Entity;
CModule::IncludeModule("iblock");

getProp($iblock_id);

#____________________________________ FUNCTIONS ____________________________________________________

#---------- Получаем количество элементов в разделе по id раздела ---------------------------------
function getProp($iblock_id){

	$iterator = CIBlockElement::GetPropertyValues($iblock_id, array(), true, array());
	$count = $iterator->SelectedRowsCount();
	$i=0;
	while ($row = $iterator->Fetch()){
		if(empty($row['287'])) {
			$i++;
		}
	}

	echo '----------------------------------------------------<br>';
	echo 'Всего элементов: ' . $i;
	echo '<br>----------------------------------------------------<br>';
	?>

	<table>
		<thead>
			<tr>
				<th>Название</th>
				<th>Значене</th>
			</tr>
		</thead>
		<tbody>

		<?
		$iterator2 = CIBlockElement::GetPropertyValues($iblock_id, array(), true, array());
		while ($row2 = $iterator2->Fetch()){?>
			<tr>
				<td> <?echo $row2['287'];?> </td>
				
			</tr>
		<?}?>

		</tbody>
	</table>
<?}?>

<style>

	table {
		text-align: center;
	}

	td,th {
		border: 1px solid grey;
		padding: 0 5px;
	}
	
</style>