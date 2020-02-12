<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

ini_set('max_execution_time', 400); ##Потому что не хватает времени при 15 секундах.
ini_set('memory_limit', '512M'); ##Потому что иногда вылетает при 128.
CModule::IncludeModule("iblock");

$rows = array_map('str_getcsv', file('s_users2.csv'));
$header = array_shift($rows);
$csv = array();
foreach ($rows as $row) {
  $csv[] = array_combine($header, $row);
}

echo "<pre>";
$i = 0;
foreach ($csv as $k=>$value) {
	//if($k < 10) {
	if ($value['discount_id'] > 1) {
		$i++;
//		$rsUser = CUser::GetByLogin($value['email']);
//		$arUser = $rsUser->Fetch();
//		if ($arUser["ID"]) {
//			$arGroups = array();
//			$user_id = $arUser["ID"];
//			$arGroups = CUser::GetUserGroup($user_id);
//			$arGroups[] = 10;
//			CUser::SetUserGroup($user_id, $arGroups);
//
//			$user = new CUser;
//			$fields = Array(
//				"UF_DISCOUNTCARD" => $value['discount_id'],
//			);
//			$user->Update($user_id, $fields);
//			echo $user_id . "<br>";
//		}


		
//	    $user = new CUser;
//	    if($value['type']=="2") {
//		    $group = array(2,8);
//		    $checkorg = 0;
//	    } else {
//		    $group = array(2,5,9);
//		    $checkorg = 1;
//	    }
//	    if(strlen($value['password']) < 6) {
//		    $password = $value['email'].time();
//	    } else {
//		    $password = $value['password'];
//	    }
//	    $arFields = Array(
//	      "NAME"              => $value['name'],
//	      "EMAIL"             => $value['email'],
//	      "LOGIN"             => $value['email'],
//	      "ACTIVE"            => "Y",
//	      "GROUP_ID"          => $group,
//	      "PASSWORD"          => $password,
//	      "CONFIRM_PASSWORD"  => $password,
//	      "ADMIN_NOTES"  => $value['comment'],
//	      "PERSONAL_PHONE"  => $value['phone'],
//	      "PERSONAL_STREET"  => $value['address'],
//	      "UF_CHECKORG" => $checkorg,
//	      "UF_ORGNAME" => $value['full_name'],
//	      "UF_BIK" => $value['bik'],
//	      "UF_ACCOUNT" => $value['account'],
//	      "UF_INN" => $value['inn'],
//	      "UF_LEGALADDRESS" => $value['legal_address'],
//	      "UF_BANK" => $value['bank'],
//	      "UF_CORRACC" => $value['corr_account'],
//	    );
//	    #var_dump($arFields);
//	    $ID = $user->Add($arFields);
//	    if (intval($ID) > 0)
//		    echo "";
//	    else {
//		    echo $user->LAST_ERROR; echo $value['email'];
//	    }

		
	}
}

echo $i;




?>
