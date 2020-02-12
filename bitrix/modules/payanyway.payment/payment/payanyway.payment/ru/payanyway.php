<?php 
/**
 * Массив языковых констант.
 */
global $MESS;

// Для админки.
$MESS['SALE_PAYANYWAY_DESCRIPTION'] = '<a href=\'https://payanyway.ru\' target=\'_blank\'>https://payanyway.ru</a>';
$MESS['SALE_PAYANYWAY_ACCOUNT_ID'] = 'Номер расширенного счета';
$MESS['SALE_PAYANYWAY_ACCOUNT_ID_DESC'] = 'Account ID - номер расширенного счета в системе Moneta.ru';
$MESS['SALE_PAYANYWAY_CODE'] = 'Код проверки целостности данных';
$MESS['SALE_PAYANYWAY_CODE_DESC'] = 'Секретный ключ, который вы устанавливаете в настройках вашего расширенного счета в системе Moneta.ru';
$MESS['SALE_AMOUNT'] = 'Сумма к оплате';
$MESS['TEST'] = 'Тестовый режим';
$MESS['DEMO'] = 'Демо режим';

$MESS['SALE_PAYANYWAY_DESC'] = 'Описание платежной системы PayAnyWay';
$MESS['SALE_PAYANYWAY_SHOP_IDP'] = 'Код магазина';
$MESS['SALE_PAYANYWAY_SHOP_IDP_DESC'] = 'Код магазина, полученный от PayAnyWay ';
$MESS['SALE_PAYANYWAY_SHOP_LOGIN'] = 'Логин';
$MESS['SALE_PAYANYWAY_SHOP_LOGIN_DESC'] = 'Логин, полученный от PayAnyWay ';
$MESS['SALE_PAYANYWAY_SHOP_PASSWORD'] = 'Пароль';
$MESS['SALE_PAYANYWAY_SHOP_PASSWORD_DESC'] = 'Пароль, полученный от PayAnyWay ';
$MESS['SALE_PAYANYWAY_SITE_NAME_LAT'] = 'Латинское наименование точки приема, присвоенное PayAnyWay ';
$MESS['SALE_PAYANYWAY_LIFE_TIME'] = 'Время жизни формы оплаты в секундах';
$MESS['SALE_PAYANYWAY_LIFE_TIME_DESC'] = 'Должно быть целым положительным числом. Если покупатель проведет на форме дольше, чем указанное время, то форма оплаты будет считаться устаревшей, и платеж не будет принят. Покупателю в таком случае будет предложено вернуться на сайт.';
$MESS['SALE_PAYANYWAY_TIME_PAID_CHANGE'] = 'Время, в течение которого статус "paid" может быть отменён';
$MESS['SALE_PAYANYWAY_TIME_PAID_CHANGE_DESC'] = 'Время считается в днях (по умолчанию 14 дней)';
$MESS['SALE_PAYANYWAY_TIME_ORDER_SYNC'] = 'Время, в течение которого будет выполняться синхронизация статусов платежей с заказами';
$MESS['SALE_PAYANYWAY_TIME_ORDER_SYNC_DESC'] = 'Время считается в днях (по умолчанию 30 дней)';
$MESS['SALE_PAYANYWAY_SUCCESS_URL'] = 'URL-Адрес при успешной оплате';
$MESS['SALE_PAYANYWAY_SUCCESS_URL_DESC'] = 'URL страницы, на которую должен вернуться покупатель после успешного осуществления платежа в системе PayAnyWay (максимум 128 символов)';
$MESS['SALE_PAYANYWAY_FAIL_URL'] = 'URL-Адрес при ошибке оплаты';
$MESS['SALE_PAYANYWAY_FAIL_URL_DESC'] = 'URL страницы, на которую должен вернуться покупатель после неуспешного осуществления платежа в системе PayAnyWay (максимум 128 символов)';
$MESS['SALE_PAYANYWAY_ORDER_ID'] = 'Номер заказа';
$MESS['SALE_PAYANYWAY_ORDER_ID_DESC'] = 'Номер заказа в Вашем Интернет-магазине';
$MESS['SALE_PAYANYWAY_EMAIL'] = 'E-mail покупателя';
$MESS['SALE_PAYANYWAY_EMAIL_DESC'] = 'Максимум 64 символа';
$MESS['SALE_PAYANYWAY_FIRST_NAME'] = 'Имя покупателя';
$MESS['SALE_PAYANYWAY_FIRST_NAME_DESC'] = 'Максимум 64 символа';
$MESS['SALE_PAYANYWAY_MIDDLE_NAME'] = 'Отчество покупателя';
$MESS['SALE_PAYANYWAY_MIDDLE_NAME_DESC'] = 'Максимум 64 символа';
$MESS['SALE_PAYANYWAY_LAST_NAME'] = 'Фамилия покупателя';
$MESS['SALE_PAYANYWAY_LAST_NAME_DESC'] = 'Максимум 64 символа';
$MESS['SALE_PAYANYWAY_ADDRESS'] = 'Адрес покупателя';
$MESS['SALE_PAYANYWAY_ADDRESS_DESC'] = 'Максимум 128 символов';
$MESS['SALE_PAYANYWAY_PHONE'] = 'Телефон покупателя';
$MESS['SALE_PAYANYWAY_PHONE_DESC'] = 'Максимум 64 символа';
$MESS['SALE_PAYANYWAY_CITY'] = 'Город покупателя';
$MESS['SALE_PAYANYWAY_CITY_DESC'] = 'Максимум 64 символа';
$MESS['SALE_PAYANYWAY_ZIP'] = 'Почтовый индекс';
$MESS['SALE_PAYANYWAY_ZIP_DESC'] = 'Максимум 64 символа';
$MESS['SALE_PAYANYWAY_LANGUAGE'] = 'Код языка интерфейса платёжной страницы';
$MESS['SALE_PAYANYWAY_LANGUAGE_DESC'] = 'Максимум 2 символа. Может быть \'en\' или \'ru\'';
$MESS['SALE_PAYANYWAY_COMMENT'] = 'Комментарий к платежу';
$MESS['SALE_PAYANYWAY_COMMENT_DESC'] = 'Максимум 255 символов';
$MESS['SALE_PAYANYWAY_COUNTRY'] = 'Код страны покупателя (ISO 3166)';
$MESS['SALE_PAYANYWAY_COUNTRY_DESC'] = 'Максимум 3 символа';
$MESS['SALE_PAYANYWAY_STATE'] = 'Код штата региона';
$MESS['SALE_PAYANYWAY_STATE_DESC'] = 'Максимум 3 символа';
$MESS['SALE_PAYANYWAY_PAID_STATUS'] = 'Статус оплаченного заказа';
$MESS['SALE_PAYANYWAY_PAID_STATUS_DESC'] = 'Для смены статуса в настройках расширенного счета установите Pay URL: http://ваш_сайт/payanyway/payanyway_notification.php в этом случае заказ получит указанный статус после успешной оплаты';
$MESS['SALE_PAYANYWAY_SHIPPING_TAX'] = 'Ставка НДС для доставки';
$MESS['SALE_PAYANYWAY_TAX_NONE'] = 'без НДС';
$MESS['SALE_PAYANYWAY_TAX_0'] = 'НДС по ставке 0%';
$MESS['SALE_PAYANYWAY_TAX_10'] = 'НДС по ставке 10%';
$MESS['SALE_PAYANYWAY_TAX_18'] = 'НДС по ставке 18%';
$MESS['SALE_PAYANYWAY_TAX_110'] = 'НДС по расчетной ставке 10/110';
$MESS['SALE_PAYANYWAY_TAX_118'] = 'НДС по расчетной ставке 18/118';
$MESS['SALE_PAYANYWAY_TEST_MODE'] = 'Тестовый режим';
$MESS['SALE_PAYANYWAY_TEST_MODE_DESC'] = 'В тестовом режиме можно проверить функционал и провести фиктивные оплаты (платежные операции не создаются)';
$MESS['SALE_PAYANYWAY_DEMO_MODE'] = 'Демо режим';
$MESS['SALE_PAYANYWAY_DEMO_MODE_DESC'] = 'Платежи будут совершаться через демо-сервер: https://demo.moneta.ru';
$MESS['SALE_PAYANYWAY_WORK_MODE'] = 'Рабочий режим';
$MESS['SALE_PAYANYWAY_YES'] = 'Да';
$MESS['SALE_PAYANYWAY_NO'] = 'Нет';
$MESS['SALE_PAYANYWAY_SEND_CUSTOMER'] = 'На почту покупателя';
$MESS['SALE_PAYANYWAY_SEND_CUSTOMER_DESC'] = 'Отправляется независимо от режима оплаты. Подтверждение не требуется.';
$MESS['SALE_PAYANYWAY_SEND_OTHER_EMAIL'] = 'На указанный(ые) email';
$MESS['SALE_PAYANYWAY_SEND_OTHER_EMAIL_DESC'] = 'Отправляется независимо от режима оплаты. Подтверждение не требуется.';
$MESS['SALE_PAYANYWAY_MAIL_FOR_SEND'] = 'От кого отправить ссылку (ваш Email)';
$MESS['SALE_PAYANYWAY_MAIL_FOR_SEND_DESC'] = 'Введите email от которого будет отправлено письмо со ссылкой на оплату.';


// Groups
$MESS['SALE_PAYANYWAY_G_STATUSES'] = 'Статус заказов';
$MESS['SALE_PAYANYWAY_G_CASHBOX'] = 'При использовании Онлайн-касс через сервис https://kassa.payanyway.ru';
$MESS['SALE_PAYANYWAY_G_MAIN'] = 'Основные настройки';
$MESS['SALE_PAYANYWAY_G_SEND'] = 'Всегда отправлять письмо со ссылкой на оплату';


// Для чека и обновления статусов.
$MESS['SASP_AS000'] = 'АВТОРИЗАЦИЯ УСПЕШНО ЗАВЕРШЕНА';
$MESS['SASP_AS100'] = 'ОТКАЗ В АВТОРИЗАЦИИ';
$MESS['SASP_AS101'] = 'ОТКАЗ В АВТОРИЗАЦИИ. Ошибочный номер карты';
$MESS['SASP_AS102'] = 'ОТКАЗ В АВТОРИЗАЦИИ. Недостаточно средств';
$MESS['SASP_AS104'] = 'ОТКАЗ В АВТОРИЗАЦИИ. Неверный срок действия карты';
$MESS['SASP_AS105'] = 'ОТКАЗ В АВТОРИЗАЦИИ. Превышен лимит';
$MESS['SASP_AS107'] = 'ОТКАЗ В АВТОРИЗАЦИИ. Ошибка приема данных';
$MESS['SASP_AS108'] = 'ОТКАЗ В АВТОРИЗАЦИИ. Подозрение на мошенничество';
$MESS['SASP_AS109'] = 'ОТКАЗ В АВТОРИЗАЦИИ. Превышен лимит операций PayAnyWay';
$MESS['SASP_AS200'] = 'ПОВТОРИТЕ АВТОРИЗАЦИЮ';
$MESS['SASP_AS998'] = 'ОШИБКА СИСТЕМЫ. Свяжитесь с PayAnyWay';

// Для чека.
$MESS['SALE_PAYANYWAY_MERCH_NAME'] = 'Торговое наименование мёрчанта: ';
$MESS['SALE_PAYANYWAY_MERCH_NAME_LAT'] = 'Латинское наименование точки приема, присвоенное PayAnyWay: ';
$MESS['SALE_PAYANYWAY_MERCH_UNIQ_URL'] = 'Электронный адрес магазина: ';
$MESS['SALE_PAYANYWAY_MERCH_TEL'] = 'Контактный телефон предприятия: ';
$MESS['SALE_PAYANYWAY_MERCH_EMAIL'] = 'Контактная электронная почта предприятия: ';
$MESS['SALE_PAYANYWAY_PS_SUM'] = 'Сумма операции в валюте: ';
$MESS['SALE_PAYANYWAY_PS_DATE'] = 'Дата операции: ';
$MESS['SALE_PAYANYWAY_BILLNUMBER'] = 'Уникальный идентификатор транзакции: ';
$MESS['SALE_PAYANYWAY_USER_FIO'] = 'Ф.И.О: ';
$MESS['SALE_PAYANYWAY_APPROVEL_CODE'] = 'Код подтверждения: ';
$MESS['SALE_PAYANYWAY_TRANSACTION_TYPE'] = 'Тип операции: ';
$MESS['SALE_PAYANYWAY_ERROR'] = 'Для данного заказа в системе PayAnyWay не существует соответствующего платежа.';
$MESS['SALE_PAYANYWAY_PRINT_CHECK'] = 'Печать чека';
$MESS['SALE_PAYANYWAY_CLOSE_WINDOW'] = 'Закрыть окно';

// Для страницы оплаты.
$MESS['SUSP_DESC'] = 'Для оплаты Вы будете перенаправлены на защищенную платежную страницу процессинговой компании <a href="https://payanyway.ru" >PayAnyWay</a>.
 Страница оплаты отвечает последним международным требованиям безопасности платежных систем Visa и MasterCard.';
$MESS['SUSP_ORDER_SUM'] = 'Сумма к оплате: ';
$MESS['SUSP_ACCOUNT_NO'] = 'Заказ №: ';
$MESS['SUSP_ORDER_FROM'] = ' от ';
$MESS['SUSP_PAYANYWAY_PAY_BUTTON'] = 'Оплатить';