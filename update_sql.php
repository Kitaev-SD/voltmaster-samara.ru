<?php 
exit;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$user_id = 12874;
$order_id = 1762;

$arOrder = CSaleOrder::GetByID($order_id);
if ($arOrder) {
   $arFields = array(
      "EMP_STATUS_ID" => $user_id,
      // "EMP_PAYED_ID" => $user_id,
      // "EMP_ALLOW_DELIVERY_ID" => $user_id,
      // "COMMENTS" => 123
   );
   
   print_r(CSaleOrder::Update($order_id, $arFields));
}

?>