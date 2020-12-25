<?php
if (\Bitrix\Main\ModuleManager::isModuleInstalled('iblock'))
{
	$updater->CopyFiles("install/components", "components");
}

if (
	$updater->CanUpdateDatabase()
	&& !\Bitrix\Main\ModuleManager::isModuleInstalled("bitrix24")
	&& \Bitrix\Main\Loader::includeModule("iblock")
)
{
	$basePath = $updater->CanUpdateKernel() ?
		$updater->curModulePath.'/lib/update/' : BX_ROOT.'/modules/iblock/lib/update/';

	$listFilters = [];
	$listTables = [];

	$connection = \Bitrix\Main\Application::getInstance()->getConnection();
	$sqlHelper = $connection->getSqlHelper();

	$catalogList = [];
	$offerIdList = [];
	if (\Bitrix\Main\Loader::includeModule("catalog"))
	{
		$offerIterator = \Bitrix\Catalog\CatalogIblockTable::getList([
			'select' => ['IBLOCK_ID', 'PRODUCT_IBLOCK_ID'],
		]);
		while ($row = $offerIterator->fetch())
		{
			$catalogId = (int)$row['IBLOCK_ID'];
			$parentId = (int)$row['PRODUCT_IBLOCK_ID'];
			$catalogList[$catalogId] = $catalogId;
			if ($parentId > 0)
			{
				$catalogList[$parentId] = $parentId;
				$offerIdList[$parentId] = $catalogId;
			}
		}
		unset($parentId, $catalogId, $row, $offerIterator);
	}

	$query = "select ".$sqlHelper->quote('FILTER_ID').
		" from ".$sqlHelper->quote('b_filters').
		" where ".$sqlHelper->quote('FILTER_ID')." in ('";

	$ratioPropertyFields = [];
	$ratioUfFields = [];
	$iblockTypeQueryObject = \Bitrix\Iblock\TypeTable::getList([
		'select' => ['ID']
	]);
	while ($iblockType = $iblockTypeQueryObject->fetch())
	{
		$iblockTypeId = $iblockType["ID"];
		$iblockQueryObject = \Bitrix\Iblock\IblockTable::getList([
			'select' => ['ID'],
			'filter' => ['=IBLOCK_TYPE_ID' => $iblockTypeId],
			'order' => ['ID' => 'ASC']
		]);
		while ($iblock = $iblockQueryObject->fetch())
		{
			$iblockId = (int)$iblock["ID"];
			$isCatalog = isset($catalogList[$iblockId]);
			$md5 = md5($iblockTypeId.".".$iblockId);
			$listTableId = [
				"tbl_catalog_section_".$md5."_filter",
				"tbl_iblock_element_".$md5."_filter",
				"tbl_iblock_list_".$md5."_filter",
				"tbl_iblock_property_admin_".$iblockId."_filter",
				"tbl_iblock_section_".$md5."_filter",
				"tbl_product_admin_".$md5."_filter",
				"tbl_product_list_".$md5."_filter"
			];

			$queryObject = $connection->query($query.implode("', '", $listTableId)."')");
			while ($filter = $queryObject->fetch())
			{
				$filterId = $filter["FILTER_ID"];
				$tableId = substr($filterId, 0, -7);

				if (!isset($ratioPropertyFields[$iblockId]))
				{
					$ratioPropertyFields[$iblockId] = getRatioPropertyFields($iblockId, false);
					if (isset($offerIdList[$iblockId]))
					{
						$ratioPropertyFields[$iblockId] = array_merge(
							$ratioPropertyFields[$iblockId], getRatioPropertyFields($offerIdList[$iblockId], true));
					}
				}

				if (!isset($ratioUfFields[$iblockId]))
				{
					$ratioUfFields[$iblockId] = [];
					$userFieldObject = $connection->query("select FIELD_NAME, SHOW_FILTER from b_user_field where ENTITY_ID = 'IBLOCK_".$iblockId."_SECTION'");
					while ($userField = $userFieldObject->fetch())
					{
						if ($userField["SHOW_FILTER"] != "N")
						{
							$ratioUfFields[$iblockId]["find_".$userField["FIELD_NAME"]] = $userField["FIELD_NAME"];
						}
					}
					unset($userField, $userFieldObject);
				}

				$ratioFields = [];
				switch ($tableId)
				{
					case "tbl_iblock_element_".$md5:
					case "tbl_product_admin_".$md5:
					case "tbl_iblock_list_".$md5:
					case "tbl_product_list_".$md5:
						$ratioFields = getRatioIblockFields($tableId, $md5, $isCatalog);
						if (!empty($ratioPropertyFields[$iblockId]))
						{
							$ratioFields = array_merge($ratioFields, $ratioPropertyFields[$iblockId]);
						}
						break;
					case "tbl_iblock_section_".$md5:
					case "tbl_catalog_section_".$md5:
						$ratioFields["find_section_name"] = "NAME";
						$ratioFields["find_section_section"] = "SECTION_ID";
						$ratioFields["find_section_id"] = "ID";
						$ratioFields["find_section_timestamp"] = "TIMESTAMP_X";
						$ratioFields["find_section_modified_by"] = "MODIFIED_BY";
						$ratioFields["find_section_date_create"] = "DATE_CREATE";
						$ratioFields["find_section_created_by"] = "CREATED_BY";
						$ratioFields["find_section_code"] = "CODE";
						$ratioFields["find_section_external_id"] = "EXTERNAL_ID";
						$ratioFields["find_section_active"] = "ACTIVE";
						if (!empty($ratioUfFields[$iblockId]))
						{
							$ratioFields = array_merge($ratioFields, $ratioUfFields[$iblockId]);
						}
						break;
					case "tbl_iblock_property_admin_".$iblockId:
						$ratioFields["find_name"] = "NAME";
						$ratioFields["find_code"] = "CODE";
						$ratioFields["find_active"] = "ACTIVE";
						$ratioFields["find_searchable"] = "SEARCHABLE";
						$ratioFields["find_filtrable"] = "FILTRABLE";
						$ratioFields["find_is_required"] = "IS_REQUIRED";
						$ratioFields["find_multiple"] = "MULTIPLE";
						$ratioFields["find_xml_id"] = "XML_ID";
						$ratioFields["find_property_type"] = "PROPERTY_TYPE";
						break;
				}

				$listFilters[$tableId] = [
					"tableId" => $tableId,
					"filterId" => $filterId,
					"ratioFields" => $ratioFields
				];
				unset($ratioFields);

				$listTables[$tableId] = $tableId;
			}
			unset($filter, $queryObject);
			if (isset($ratioPropertyFields[$iblockId]))
				unset($ratioPropertyFields[$iblockId]);
			if (isset($ratioUfFields[$iblockId]))
				unset($ratioUfFields[$iblockId]);
		}
		unset($iblock, $iblockQueryObject);
	}
	unset($iblockType, $iblockTypeQueryObject);
	unset($offerIdList, $catalogList);

	unset($sqlHelper, $connection);

	if (!empty($listFilters))
	{
		if (class_exists('Bitrix\Iblock\Update\FilterOption') ||
			include_once($_SERVER["DOCUMENT_ROOT"].$basePath."filteroption.php"))
		{
			foreach ($listFilters as $filter)
			{
				\Bitrix\Iblock\Update\FilterOption::setFilterToConvert(
					$filter["filterId"], $filter["tableId"], $filter["ratioFields"]);
			}

			\Bitrix\Iblock\Update\FilterOption::bind();
		}
	}
	unset($listFilters);

	if (!empty($listTables))
	{
		if (class_exists('Bitrix\Iblock\Update\GridOption') ||
			include_once($_SERVER["DOCUMENT_ROOT"].$basePath."gridoption.php"))
		{
			foreach ($listTables as $tableId)
			{
				Bitrix\Iblock\Update\GridOption::setGridToConvert($tableId);
			}

			Bitrix\Iblock\Update\GridOption::bind();
		}
	}
	unset($listTables);
}

function getRatioIblockFields($tableId, $md5, $catalog)
{
	$ratioFields = [];

	switch ($tableId)
	{
		case "tbl_iblock_list_".$md5:
		case "tbl_product_list_".$md5:
			$prefix = "find_";
			break;
		default:
			$prefix = "find_el_";
	}

	$ratioFields[$prefix."id"] = "ID";
	$ratioFields[$prefix."find_section_section"] = "SECTION_ID";
	$ratioFields[$prefix."timestamp"] = "DATE_MODIFY_FROM";
	$ratioFields[$prefix."modified_user_id"] = "MODIFIED_USER_ID";
	$ratioFields[$prefix."created"] = "DATE_CREATE";
	$ratioFields[$prefix."created_user_id"] = "CREATED_USER_ID";
	$ratioFields[$prefix."date_active_from"] = "DATE_ACTIVE_FROM";
	$ratioFields[$prefix."date_active_to"] = "DATE_ACTIVE_TO";
	$ratioFields[$prefix."active"] = "ACTIVE";
	$ratioFields[$prefix."name"] = "NAME";
	$ratioFields[$prefix."intext"] = "DESCRIPTION";
	$ratioFields[$prefix."code"] = "CODE";
	$ratioFields[$prefix."external_id"] = "EXTERNAL_ID";
	$ratioFields[$prefix."tags"] = "TAGS";
	if ($catalog)
	{
		$ratioFields["find_el_catalog_type"] = "CATALOG_TYPE";
		$ratioFields["find_el_catalog_bundle"] = "CATALOG_BUNDLE";
		$ratioFields["find_el_catalog_available"] = "CATALOG_AVAILABLE";
	}

	return $ratioFields;
}

function getRatioPropertyFields($iblockId, $boolSku = false)
{
	$ratioPropertyFields = [];

	$propertyIterator = Bitrix\Iblock\PropertyTable::getList([
		"select" => ["ID", "USER_TYPE", "PROPERTY_TYPE", "SORT", "NAME"],
		"filter" => ["=IBLOCK_ID" => $iblockId, "=ACTIVE" => "Y", "=FILTRABLE" => "Y"],
		"order" => ["SORT" => "ASC", "NAME" => "ASC"]
	]);
	while ($property = $propertyIterator->fetch())
	{
		$oldFieldName = ($boolSku ? "find_sub_el_property_".$property["ID"] : "find_el_property_".$property["ID"]);

		if (!empty($property["USER_TYPE"]))
		{
			switch ($property["USER_TYPE"])
			{
				case "EAutocomplete":
				case "SectionAuto":
				case "SKU":
					$oldFieldName = "inp_".$oldFieldName;
					break;
			}
		}
		else
		{
			switch ($property["PROPERTY_TYPE"])
			{
				case "N":
					$oldFieldName = $oldFieldName."_integer";
					break;
			}
		}

		$ratioPropertyFields[$oldFieldName] = "PROPERTY_".$property["ID"];
	}

	return $ratioPropertyFields;
}