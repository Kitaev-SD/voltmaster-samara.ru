<?php
/**
 * Created by PhpStorm.
 * User: Николай Сандрюхин
 * Date: 26.02.2019
 * Time: 11:49
 */

use Bitrix\Main;

Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleOrderSaved',
    'changeOrder'
);

// собирает данные по заказу и обновляем комментарий
function changeOrder($event)
{
//    $order = $event->getParameter('ENTITY');
//    $propertyCollection = $order->getPropertyCollection();
//    $paymentCollection = $paymentCollection = $order->getPaymentCollection();
//    $deliveryCollection = $order->getShipmentCollection();
//
//    $customer = [];
//    foreach ($propertyCollection as $prop) {
//        $customer[$prop->getField('CODE')] = $prop->getViewHtml();
//    }
//
//    $deliveryName = $deliveryCollection->current()->getField('DELIVERY_NAME');
//    $paymentVoucherNumber = $paymentCollection->current()->getField('PAY_VOUCHER_NUM');
//    $paymentVoucherDate = $paymentCollection->current()->getField('PAY_VOUCHER_DATE');
//
//    $comment = '[' . $customer['FIO'] . '; ' . $customer['PHONE'] . '; ';
//
//    if($deliveryName) {
//        $comment .= $deliveryName . '; ';
//    }
//
//    $comment .= $customer['ZIP'] . ' ' . $customer['LOCATION'] . ', ' . $customer['ADDRESS'] . '] ';
//
//    if($paymentVoucherNumber) {
//        $comment .= '[Оплата:' . $paymentVoucherDate . ' - ПП:' . $paymentVoucherNumber . '] ';
//    }
//
//    $customerComment = $order->getField('USER_DESCRIPTION');
//    if($customerComment) {
//        $comment .= $customerComment;
//    }

//    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/testOrderComments.php', print_r($customer, 1) . "\r\n", FILE_APPEND);
//    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/testOrderComments.php', print_r($deliveryName, 1) . "\r\n", FILE_APPEND);
//    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/testOrderComments.php', print_r($paymentVoucherNumber, 1) . "\r\n", FILE_APPEND);
//    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/testOrderComments.php', print_r($paymentVoucherDate, 1) . "\r\n", FILE_APPEND);
//    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/testOrderComments.php', "\r\n\r\n", FILE_APPEND);

//    if ($comment) {
//        CSaleOrder::Update($order->getId(), ['COMMENTS' => $comment]);
//    }

}