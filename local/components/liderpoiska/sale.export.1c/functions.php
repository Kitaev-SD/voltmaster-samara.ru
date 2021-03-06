<?php

use Bitrix\Sale\BusinessValue;
use Bitrix\Sale\BusinessValueConsumer1C;
use Bitrix\Sale;
use Bitrix\Sale\Exchange\Internals\LoggerDiag;
use Bitrix\Sale\Exchange\Logger\Exchange;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/sale/general/export.php';

class CSaleExportCustom extends CSaleExport
{

    static function ExportOrders2XmlCustom ($arFilter = Array(), $nTopCount = 0, $currency = "", $crmMode = false, $time_limit = 0, $version = false, $arOptions = Array())
    {
        $lastOrderPrefix = '';
        $arCharSets = array();
        $lastDateUpdateOrders = array();
        $entityMarker = parent::getEntityMarker();

        parent::setVersionSchema($version);
        parent::setCrmMode($crmMode);
        parent::setCurrencySchema($currency);

        $count = false;
        if (IntVal($nTopCount) > 0)
            $count = Array("nTopCount" => $nTopCount);

        $end_time = parent::getEndTime($time_limit);

        if (IntVal($time_limit) > 0) {
            if (parent::$crmMode) {
                $lastOrderPrefix = md5(serialize($arFilter));
                if (!empty($_SESSION["BX_CML2_EXPORT"][$lastOrderPrefix]) && IntVal($nTopCount) > 0)
                    $count["nTopCount"] = $count["nTopCount"] + count($_SESSION["BX_CML2_EXPORT"][$lastOrderPrefix]);
            }
        }

        if (!parent::$crmMode) {
            $arFilter = parent::prepareFilter($arFilter);
            $timeUpdate = isset($arFilter[">=DATE_UPDATE"]) ? $arFilter[">=DATE_UPDATE"] : '';
            $lastDateUpdateOrders = parent::getLastOrderExported($timeUpdate);
        }

        parent::$arResultStat = array(
            "ORDERS" => 0,
            "CONTACTS" => 0,
            "COMPANIES" => 0,
        );

        $bExportFromCrm = parent::isExportFromCRM($arOptions);

        $arStore = parent::getCatalogStore();
        $arMeasures = parent::getCatalogMeasure();
        parent::setCatalogMeasure($arMeasures);
        $arAgent = parent::getSaleExport();

        if (parent::$crmMode) {
            parent::setXmlEncoding("UTF-8");
            $arCharSets = parent::getSite();
        }

        echo parent::getXmlRootName(); ?>

        <<?= CSaleExport::getTagName("SALE_EXPORT_COM_INFORMATION") ?> <?= parent::getCmrXmlRootNameParams() ?>><?

        $arOrder = array("DATE_UPDATE" => "ASC", "ID" => "ASC");

        $arSelect = array(
            "ID", "LID", "PERSON_TYPE_ID", "PAYED", "DATE_PAYED", "EMP_PAYED_ID", "CANCELED", "DATE_CANCELED",
            "EMP_CANCELED_ID", "REASON_CANCELED", "STATUS_ID", "DATE_STATUS", "PAY_VOUCHER_NUM", "PAY_VOUCHER_DATE", "EMP_STATUS_ID",
            "PRICE_DELIVERY", "ALLOW_DELIVERY", "DATE_ALLOW_DELIVERY", "EMP_ALLOW_DELIVERY_ID", "PRICE", "CURRENCY", "DISCOUNT_VALUE",
            "SUM_PAID", "USER_ID", "PAY_SYSTEM_ID", "DELIVERY_ID", "DATE_INSERT", "DATE_INSERT_FORMAT", "DATE_UPDATE", "USER_DESCRIPTION",
            "ADDITIONAL_INFO",
            "COMMENTS", "TAX_VALUE", "STAT_GID", "RECURRING_ID", "ACCOUNT_NUMBER", "SUM_PAID", "DELIVERY_DOC_DATE", "DELIVERY_DOC_NUM", "TRACKING_NUMBER", "STORE_ID",
            "ID_1C", "VERSION",
            "USER.XML_ID", "USER.TIMESTAMP_X"
        );

        $bCrmModuleIncluded = false;
        if ($bExportFromCrm) {
            $arSelect[] = "UF_COMPANY_ID";
            $arSelect[] = "UF_CONTACT_ID";
            if (IsModuleInstalled("crm") && CModule::IncludeModule("crm"))
                $bCrmModuleIncluded = true;
        }

        $arFilter['RUNNING'] = 'N';

        $filter = array(
            'select' => $arSelect,
            'filter' => $arFilter,
            'order' => $arOrder,
            'limit' => $count["nTopCount"]
        );

        if (!empty($arOptions['RUNTIME']) && is_array($arOptions['RUNTIME'])) {
            $filter['runtime'] = $arOptions['RUNTIME'];
        }

        $entity = parent::getParentEntityTable();

        $dbOrderList = $entity::getList($filter);

        while ($arOrder = $dbOrderList->Fetch()) {
            // if (!parent::$crmMode && parent::exportedLastExport($arOrder, $lastDateUpdateOrders)) {
            if(!self::$crmMode && (new Exchange(Sale\Exchange\Logger\ProviderType::ONEC_NAME))->isEffected($arOrder, $lastDateUpdateOrders)) {
                continue;
            }

            parent::$documentsToLog = array();
            $contentToLog = '';

            $order = parent::load($arOrder['ID']);

            $arOrder['DATE_STATUS'] = $arOrder['DATE_STATUS']->toString();
            $arOrder['DATE_INSERT'] = $arOrder['DATE_INSERT']->toString();
            $arOrder['DATE_UPDATE'] = $arOrder['DATE_UPDATE']->toString();

            foreach ($arOrder as $field => $value) {
                if (parent::isFormattedDateFields('Order', $field)) {
                    $arOrder[$field] = parent::getFormatDate($value);
                }
            }

            if (parent::$crmMode) {
                if (parent::getVersionSchema() > parent::DEFAULT_VERSION && is_array($_SESSION["BX_CML2_EXPORT"][$lastOrderPrefix]) && in_array($arOrder["ID"], $_SESSION["BX_CML2_EXPORT"][$lastOrderPrefix]) && empty($arFilter["ID"]))
                    continue;
                ob_start();
            }

            parent::$arResultStat["ORDERS"]++;

            $agentParams = (array_key_exists($arOrder["PERSON_TYPE_ID"], $arAgent) ? $arAgent[$arOrder["PERSON_TYPE_ID"]] : array());

            $arResultPayment = parent::getPayment($arOrder);
            $paySystems = $arResultPayment['paySystems'];
            $arPayment = $arResultPayment['payment'];

            $arResultShipment = parent::getShipment($arOrder);
            $arShipment = $arResultShipment['shipment'];
            $delivery = $arResultShipment['deliveryServices'];

            parent::setDeliveryAddress('');
            parent::setSiteNameByOrder($arOrder);

            $arProp = parent::prepareSaleProperty($arOrder, $bExportFromCrm, $bCrmModuleIncluded, $paySystems, $delivery, $locationStreetPropertyValue, $order);
            $agent = parent::prepareSalePropertyRekv($order, $agentParams, $arProp, $locationStreetPropertyValue);

            $arOrderTax = CSaleExport::getOrderTax($order);
            $xmlResult['OrderTax'] = parent::getXMLOrderTax($arOrderTax);
            parent::setOrderSumTaxMoney(parent::getOrderSumTaxMoney($arOrderTax));

            $xmlResult['Contragents'] = parent::getXmlContragents($arOrder, $arProp, $agent, $bExportFromCrm ? array("EXPORT_FROM_CRM" => "Y") : array());
            $xmlResult['OrderDiscount'] = parent::getXmlOrderDiscount($arOrder);
            $xmlResult['SaleStoreList'] = $arStore;
            $xmlResult['ShipmentsStoreList'] = parent::getShipmentsStoreList($order);
            // parent::getXmlSaleStoreBasket($arOrder,$arStore);
            $basketItems = parent::getXmlBasketItems('Order', $arOrder, array('ORDER_ID' => $arOrder['ID']), array(), $arShipment);

            $numberItems = array();
            foreach ($basketItems['result'] as $basketItem) {
                $number = parent::getNumberBasketPosition($basketItem["ID"]);

                if (in_array($number, $numberItems)) {
                    $r = new \Bitrix\Sale\Result();
                    $r->addWarning(new \Bitrix\Main\Error(GetMessage("SALE_EXPORT_REASON_MARKED_BASKET_PROPERTY") . '1C_Exchange:Order.export.basket.properties', 'SALE_EXPORT_REASON_MARKED_BASKET_PROPERTY'));
                    $entityMarker::addMarker($order, $order, $r);
                    $order->setField('MARKED', 'Y');
                    $order->setField('DATE_UPDATE', null);
                    $order->save();
                    break;
                } else {
                    $numberItems[] = $number;
                }
            }

            /**
             * Кастомный код начало
             *
             * собираем информацию для комментария
             */
            $propertyCollection = $order->getPropertyCollection(); // свойства заказа
            $paymentCollection = $order->getPaymentCollection(); // оплаты
            $deliveryCollection = $order->getShipmentCollection(); // доставки

            // пользовательские поля
            $customer = [];
            foreach ($propertyCollection as $prop) {
                $customer[$prop->getField('CODE')] = $prop->getViewHtml();
            }

            $deliveryName = $deliveryCollection->current()->getField('DELIVERY_NAME');
            $paymentVoucherNumber = $paymentCollection->current()->getField('PAY_VOUCHER_NUM'); // номер платежного поручения из PayAnyWay
            $paymentVoucherDate = $paymentCollection->current()->getField('PAY_VOUCHER_DATE'); // дата платежного поручения из PayAnyWay

            // собираем комментарий
            $comment = '[Номер документа на сайте: ' . $order->getId() . '] [';

            if ($customer['FIO']) {
                $comment .= $customer['FIO'] . '; ';
            }

            if ($customer['PHONE']) {
                $comment .= $customer['PHONE'] . '; ';
            }

            if ($deliveryName) {
                $comment .= $deliveryName . '; ';
            }

            if ($customer['ZIP']) {
                $comment .= $customer['ZIP'];
            }

            if ($customer['LOCATION']) {
                $comment .= ' ' . $customer['LOCATION'];
            }

            if ($customer['ADDRESS']) {
                $comment .= ', ' . $customer['ADDRESS'];
            }

            $comment .= '] [';

            if ($arProp['PROPERTY'][2]) {
                $comment .= $arProp['PROPERTY'][2] . '; ';
            }

            if ($arPayment[0]['PAY_SYSTEM_NAME']) {
                $comment .= $arPayment[0]['PAY_SYSTEM_NAME'] . '; ';
            }

            if ($paymentVoucherNumber) {
                $comment .= '[Оплата:' . $paymentVoucherDate . ' - ПП:' . $paymentVoucherNumber . '] ';
            }

            $comment .= ']';

            $customerComment = $order->getField('USER_DESCRIPTION');
            if ($customerComment) {
                $comment .= $customerComment;
            }

            // уходит в xml
            $arOrder['COMMENTS'] = $comment;
            // сохраняем в БД
            $order->setField('COMMENTS', $comment);
            $order->save();
            /**
             * Кастомный код конец
             */

            $xmlResult['BasketItems'] = $basketItems['outputXML'];
            $xmlResult['SaleProperties'] = parent::getXmlSaleProperties($arOrder, $arShipment, $arPayment, $agent, $agentParams, $bExportFromCrm);
            $xmlResult['RekvProperties'] = parent::getXmlRekvProperties($agent, $agentParams);


            if (parent::getVersionSchema() >= parent::CONTAINER_VERSION) {
                ob_start();
                echo '<' . CSaleExport::getTagName("SALE_EXPORT_CONTAINER") . '>';
            }

            parent::OutputXmlDocument('Order', $xmlResult, $arOrder);

            if (parent::getVersionSchema() >= parent::PARTIAL_VERSION) {
                parent::OutputXmlDocumentsByType('Payment', $xmlResult, $arOrder, $arPayment, $order, $agentParams, $arProp, $locationStreetPropertyValue);
                parent::OutputXmlDocumentsByType('Shipment', $xmlResult, $arOrder, $arShipment, $order, $agentParams, $arProp, $locationStreetPropertyValue);
                parent::OutputXmlDocumentRemove('Shipment', $arOrder);
            }

            if (parent::getVersionSchema() >= parent::CONTAINER_VERSION) {
                echo '</' . CSaleExport::getTagName("SALE_EXPORT_CONTAINER") . '>';
                $contentToLog = ob_get_contents();
                ob_end_clean();
                echo $contentToLog;
            }

            if (parent::$crmMode) {
                $c = ob_get_clean();
                $c = CharsetConverter::ConvertCharset($c, $arCharSets[$arOrder["LID"]], "utf-8");
                echo $c;
                $_SESSION["BX_CML2_EXPORT"][$lastOrderPrefix][] = $arOrder["ID"];
            } else {
                parent::saveExportParams($arOrder);
            }

            ksort(parent::$documentsToLog);

            foreach (parent::$documentsToLog as $entityTypeId => $documentsToLog) {
                foreach ($documentsToLog as $documentToLog) {
                    $fieldToLog = $documentToLog;
                    $fieldToLog['ENTITY_TYPE_ID'] = $entityTypeId;
                    if (parent::getVersionSchema() >= parent::CONTAINER_VERSION) {
                        if ($entityTypeId == \Bitrix\Sale\Exchange\EntityType::ORDER)
                            $fieldToLog['MESSAGE'] = $contentToLog;
                    }
                    parent::log($fieldToLog);
                }
            }

            if (parent::checkTimeIsOver($time_limit, $end_time)) {
                break;
            }
        }
        ?>

        </<?= CSaleExport::getTagName("SALE_EXPORT_COM_INFORMATION") ?>><?

        return parent::$arResultStat;
    }

}