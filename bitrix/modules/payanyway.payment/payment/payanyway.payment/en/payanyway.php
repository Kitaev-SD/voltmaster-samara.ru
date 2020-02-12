<?php
global $MESS;

// Для админки.
$MESS['SALE_PAYANYWAY_DESCRIPTION'] = '<a href=\'https://payanyway.ru\' target=\'_blank\'>https://payanyway.ru</a>';
$MESS['SALE_PAYANYWAY_DESC'] = 'Description of the payment system PayAnyWay ';
$MESS['SALE_PAYANYWAY_SHOP_IDP'] = 'Shop ID';
$MESS['SALE_PAYANYWAY_SHOP_IDP_DESC'] = 'Shop ID received from PayAnyWay ';
$MESS['SALE_PAYANYWAY_SHOP_LOGIN'] = 'Login';
$MESS['SALE_PAYANYWAY_SHOP_LOGIN_DESC'] = 'Login, received from PayAnyWay ';
$MESS['SALE_PAYANYWAY_SHOP_PASSWORD'] = 'Password';
$MESS['SALE_PAYANYWAY_SHOP_PASSWORD_DESC'] = 'Password, received from PayAnyWay ';
$MESS['SALE_PAYANYWAY_SITE_NAME_LAT'] = 'Merchant name in Latin, assigned by PayAnyWay ';
$MESS['SALE_PAYANYWAY_LIFE_TIME'] = 'Payment page lifetime in seconds ';
$MESS['SALE_PAYANYWAY_LIFE_TIME_DESC'] = 'It should be in the form of integer positive number. If a Purchaser spends more time on the page than it is determined, the payment page will be considered stale and the payment will not be accepted. In this case, a Purchaser will be offered to return to a Merchant\'s site.';
$MESS['SALE_PAYANYWAY_TIME_PAID_CHANGE'] = 'The time, during which the "paid" status can be cancelled';
$MESS['SALE_PAYANYWAY_TIME_PAID_CHANGE_DESC'] = 'The time is calculated in days (14 days by default)';
$MESS['SALE_PAYANYWAY_TIME_ORDER_SYNC'] = 'The time within which will be synchronized with the payment status of orders';
$MESS['SALE_PAYANYWAY_TIME_ORDER_SYNC_DESC'] = 'The time is calculated in days (30 days by default)';
$MESS['SALE_PAYANYWAY_SUCCESS_URL'] = 'Page URL for successful transaction (URL_RETURN_OK)';
$MESS['SALE_PAYANYWAY_SUCCESS_URL_DESC'] = 'Page URL the purchaser should be returned to after a successful transaction in PayAnyWay system (maximum 128 symbols)';
$MESS['SALE_PAYANYWAY_FAIL_URL'] = 'Page URL for unsuccessful transaction (URL_RETURN_NO)';
$MESS['SALE_PAYANYWAY_FAIL_URL_DESC'] = 'URL of the page to return to in case of failed payment in PayAnyWay (maximum 128 characters)';
$MESS['SALE_PAYANYWAY_TESTMODE'] = 'Test mode';
$MESS['SALE_PAYANYWAY_TESTMODE_DESC'] = 'If an empty value - the store will operate as usual';
$MESS['SALE_PAYANYWAY_DEMOMODE'] = 'Demo mode';
$MESS['SALE_PAYANYWAY_DEMOMODE_DESC'] = 'If an empty value - the store will operate as usual';
$MESS['SALE_PAYANYWAY_ORDER_ID'] = 'Order number';
$MESS['SALE_PAYANYWAY_ORDER_ID_DESC'] = 'Order number in your e-Shop';
$MESS['SALE_PAYANYWAY_EMAIL'] = 'E-mail';
$MESS['SALE_PAYANYWAY_EMAIL_DESC'] = 'Maximum 64 characters';
$MESS['SALE_PAYANYWAY_FIRST_NAME'] = 'First name';
$MESS['SALE_PAYANYWAY_FIRST_NAME_DESC'] = 'Maximum 64 characters';
$MESS['SALE_PAYANYWAY_MIDDLE_NAME'] = 'Middle buyer';
$MESS['SALE_PAYANYWAY_MIDDLE_NAME_DESC'] = 'Maximum 64 characters';
$MESS['SALE_PAYANYWAY_LAST_NAME'] = 'Last name';
$MESS['SALE_PAYANYWAY_LAST_NAME_DESC'] = 'Maximum 64 characters';
$MESS['SALE_PAYANYWAY_ADDRESS'] = 'Address';
$MESS['SALE_PAYANYWAY_ADDRESS_DESC'] = 'Maximum 128 characters';
$MESS['SALE_PAYANYWAY_PHONE'] = 'Phone';
$MESS['SALE_PAYANYWAY_PHONE_DESC'] = 'Maximum 64 characters';
$MESS['SALE_PAYANYWAY_CITY'] = 'City';
$MESS['SALE_PAYANYWAY_CITY_DESC'] = 'Maximum 64 characters';
$MESS['SALE_PAYANYWAY_ZIP'] = 'Zip code';
$MESS['SALE_PAYANYWAY_ZIP_DESC'] = 'Maximum 64 characters';
$MESS['SALE_PAYANYWAY_LANGUAGE'] = 'Interface language code of the payment page';
$MESS['SALE_PAYANYWAY_LANGUAGE_DESC'] = 'Maximum 2 characters (\'en \' or \'ru \')';
$MESS['SALE_PAYANYWAY_COMMENT'] = 'Comment to the transaction';
$MESS['SALE_PAYANYWAY_COMMENT_DESC'] = 'Maximum 255 characters';
$MESS['SALE_PAYANYWAY_COUNTRY'] = 'Country code (ISO 3166)';
$MESS['SALE_PAYANYWAY_COUNTRY_DESC'] = 'Maximum 3 characters';
$MESS['SALE_PAYANYWAY_STATE'] = 'Code State Region';
$MESS['SALE_PAYANYWAY_STATE_DESC'] = 'Maximum 3 characters';
$MESS['SALE_PAYANYWAY_PAID_STATUS'] = 'Paid order status';
$MESS['SALE_PAYANYWAY_SHIPPING_TAX'] = 'NDS for Shipping';
$MESS['SALE_PAYANYWAY_SEND_CUSTOMER'] = 'To the customer\'s email';
$MESS['SALE_PAYANYWAY_SEND_CUSTOMER_DESC'] = 'Always send';
$MESS['SALE_PAYANYWAY_SEND_OTHER_EMAIL'] = 'To this email(s)';
$MESS['SALE_PAYANYWAY_SEND_OTHER_EMAIL_DESC'] = 'Always send';
$MESS['SALE_PAYANYWAY_MAIL_FOR_SEND'] = 'От кого отправить ссылку (ваш Email)';
$MESS['SALE_PAYANYWAY_MAIL_FOR_SEND_DESC'] = 'Введите email от которого будет отправлено письмо со ссылкой на оплату.';

// Для чека и обновления статусов.
$MESS['SASP_AS000'] = 'SUCCESSFUL AUTHORIZATION';
$MESS['SASP_AS100'] = 'AUTHORIZATION DENIAL';
$MESS['SASP_AS101'] = 'AUTHORIZATION DENIAL. Invalid card number ';
$MESS['SASP_AS102'] = 'AUTHORIZATION DENIAL. Not enough funds ';
$MESS['SASP_AS104'] = 'AUTHORIZATION DENIAL. Wrong expiration date ';
$MESS['SASP_AS105'] = 'AUTHORIZATION DENIAL. Limit is exceeded ';
$MESS['SASP_AS107'] = 'AUTHORIZATION DENIAL. Data receive error';
$MESS['SASP_AS108'] = 'AUTHORIZATION DENIAL. Fraud suspicion ';
$MESS['SASP_AS109'] = 'AUTHORIZATION DENIAL. The limit for PayAnyWay operations is exceeded';
$MESS['SASP_AS200'] = 'REPEAT AUTHORIZATION ';
$MESS['SASP_AS998'] = 'SYSTEM ERROR. Please, contact PayAnyWay';

// Для чека.
$MESS['SALE_PAYANYWAY_MERCH_NAME'] = 'Merchant name: ';
$MESS['SALE_PAYANYWAY_MERCH_NAME_LAT'] = 'Merchant e-Shop name in Latin, assigned by PayAnyWay: ';
$MESS['SALE_PAYANYWAY_MERCH_UNIQ_URL'] = 'Merchant URL: ';
$MESS['SALE_PAYANYWAY_MERCH_TEL'] = 'Company contact phone: ';
$MESS['SALE_PAYANYWAY_MERCH_EMAIL'] = 'Company contact e-mail: ';
$MESS['SALE_PAYANYWAY_PS_SUM'] = 'Transaction amount (currency): ';
$MESS['SALE_PAYANYWAY_PS_DATE'] = 'Transaction date: ';
$MESS['SALE_PAYANYWAY_BILLNUMBER'] = 'Bill number: ';
$MESS['SALE_PAYANYWAY_USER_FIO'] = 'Cardholder name: ';
$MESS['SALE_PAYANYWAY_APPROVEL_CODE'] = 'Verification code: ';
$MESS['SALE_PAYANYWAY_TRANSACTION_TYPE'] = 'Transaction type: ';
$MESS['SALE_PAYANYWAY_ERROR'] = 'This order does not any corresponding payment in the PayAnyWay system.';
$MESS['SALE_PAYANYWAY_PRINT_CHECK'] = 'Print check';
$MESS['SALE_PAYANYWAY_CLOSE_WINDOW'] = 'Close window';

// Groups
$MESS['SALE_PAYANYWAY_G_STATUSES'] = 'Statuses';
$MESS['SALE_PAYANYWAY_G_CASHBOX'] = 'Using online-cashbox';
$MESS['SALE_PAYANYWAY_G_MAIN'] = 'Main options';
$MESS['SALE_PAYANYWAY_G_SEND'] = 'Send always payment link';

// Для страницы оплаты.
$MESS['SUSP_DESC_TITLE'] = 'Description of the payment system: ';
$MESS['SUSP_ORDER_SUM'] = 'Total: ';
$MESS['SUSP_ACCOUNT_NO'] = 'Order no.';
$MESS['SUSP_ORDER_FROM'] = ' from ';
$MESS['SUSP_PAYANYWAY_PAY_BUTTON'] = 'Pay';