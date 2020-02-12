<?

global $USER;
$arFilter = array("ID" => $USER->GetID());
$arParams["SELECT"] = array("UF_DISCOUNTCARD");
$arRes = CUser::GetList($by,$desc,$arFilter,$arParams);
if ($res = $arRes->Fetch()) {
    $arResult['UF_DISCOUNTCARD'] = $res['UF_DISCOUNTCARD'];
}

?>