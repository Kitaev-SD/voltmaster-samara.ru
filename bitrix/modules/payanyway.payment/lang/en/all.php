<?php
/**
 * Файл со всеми языковыми константами.
 *
 * Выдержка из помощи 1C-Bitrix:
 * 		"Подключаемый языковой файл должен иметь то же имя, что и подключающий файл, и быть расположен на диске в каталоге:
 * 		/bitrix/modules/ID модуля/lang/ID языка/путь к файлу относительно корня модуля."
 * Было принято решение разместить все языковые константы в один общий файл, чтобы не создавать множество различных маленьких файлов.   
 */

global $MESS;
// PAYANYWAY.payment/install/index.php
$MESS['PAYANYWAY.PAYMENT_INSTALL_NAME'] = '«PayAnyWay payment system»';
$MESS['PAYANYWAY.PAYMENT_INSTALL_DESCRIPTION'] = 'Description of the «PayAnyWay payment system» module <a href="/bitrix/admin/settings.php?lang=en&mid=payanyway.payment&mid_menu=1" target="_blank">Go to the Help page</a>';
$MESS['PAYANYWAY.PAYMENT_PREINSTALL_DESCRIPTION'] = 'Description of the «PayAnyWay payment system» module <a href="#" onclick="alert(\'Install the «PayAnyWay payment system» module, please!\')">Go to the Help page</a>';
$MESS['PAYANYWAY.PAYMENT_INSTALL_ERROR'] = 'An error occurred while copying the files during the «PayAnyWay payment system» module installation.';

// PAYANYWAY.payment/install/step_ok.php
$MESS['PAYANYWAY.PAYMENT_INSTALL_MESSAGE'] = '«PayAnyWay payment system» module has been successfully installed.';

// PAYANYWAY.payment/install/unstep_ok.php
$MESS['PAYANYWAY.PAYMENT_BTN_CANCEL'] = 'Cancel';
$MESS['PAYANYWAY.PAYMENT_SAVE_TABLES'] = 'Do you want to save the «PayAnyWay payment system» module table? ';

// PAYANYWAY.payment/options.php & PAYANYWAY.payment/install/step_ok.php & PAYANYWAY.payment/admin/PAYANYWAY_agent_log.php
$MESS['PAYANYWAY.PAYMENT_BTN_HELP'] = 'Help';

// admin/PAYANYWAY_agent_log.php
$MESS['PAYANYWAY.AGENT_ORDER_ID'] = 'Order ID';
$MESS['PAYANYWAY.AGENT_INSERT_DATATIME'] = 'Error adding time';
$MESS['PAYANYWAY.AGENT_TYPE_ERROR'] = 'Error type';
$MESS['PAYANYWAY.AGENT_TEXT_ERROR'] = 'Error text';
$MESS['PAYANYWAY.AGENT_LOGS_TITLE'] = '«PayAnyWay payment system» module logs';
$MESS['PAYANYWAY.AGENT_LOGS_DEL'] = 'Delete';
$MESS['PAYANYWAY.AGENT_DEL_ERROR'] = 'The record cannot be removed.';
$MESS['PAYANYWAY.AGENT_NAV'] = 'Error logs';
$MESS['PAYANYWAY.AGENT_DEL_CONF'] = 'Do you want to remove the record?';
$MESS['PAYANYWAY.AGENT_F_ORDER_ID'] = 'Order ID';
$MESS['PAYANYWAY.AGENT_F_INSERT_DATATIME'] = 'Error adding time';
$MESS['PAYANYWAY.AGENT_F_TYPE_ERROR'] = 'Error type';
$MESS['PAYANYWAY.AGENT_F_TEXT_ERROR'] = 'Error text';
$MESS['PAYANYWAY.AGENT_FIND'] = 'Find';
$MESS['PAYANYWAY.AGENT_F_FIND_TYTLE'] = 'Enter the search line';
$MESS['POST_ALL'] = 'All';

// PAYANYWAY.payment/options.php
$MESS['PAYANYWAY.AGENT_LOGS'] = 'Logs';
$MESS['PAYANYWAY.SALE_HELP_TEXT'] = '
<h3>Installation and Setup Guide for PayAnyWay payment system modules in CMS 1C-Bitrix</h3>
<b>Module installation</b>
<p>
<div>1. Log in to the «Control panel» on the site with the administrator credentials</div>
<div>2. Go to «Marketpalce» section (Settings–>Marketplace). Find «PayAnyWay payment system» module in the list, double-click it and click «Download». </div>
<div>3. When PayAnyWay payment system module is downloaded, click «Install» button near it. When the installation process is finished the «PayAnyWay payment system module has been successfully installed» message will appear.</div>
<div>4. Click «Back to list» button. PayAnyWay payment system will appear with «Installed» status in the modules list.</div>
</p>
<h3>Module setup</h3>
<b>PayAnyWay payment system setup</b>
<p>
<div>1. Go to «Payment systems» section (e-Store–>e-Store settings–>Payment systems)</div>
<div>2. Click «New payment system» button above the payment systems table and in the drop-down list choose the site the payment system will be installed for. A section for a new payment system will appear.</div>
<div>3. To add a new payment system, go to «Payment system» tab and fill all required fields in. Into the «Currency» field, enter the payment system currency. Into the «Name» field, enter «PayAnyWay». Tick off the «Active» field.  Into the «Sorting index» field, enter value 1. Into the «Description» field, enter a short description on the payment system.</div>
<div>4. Go to the Individuals tab.</div>
<div>&nbsp;&nbsp;a. If the chosen payment system should work with Individuals, tick off the «Applied to this payer type» field. Specify the name of the handler for the payment system in the «Name» field.  In the «Handler» field, choose PayAnyWay from the drop-down list. Fill in the additional fields.</div>
<div>&nbsp;&nbsp;b. Fill in the «Description of the payment system» field and all other fields. </div>
<div>5. Apply all the above mentioned steps both for Individual tab and for Legal entity tab.</div>
<div>6. Click «Save» button.</div>
<div>7. PayAnyWay payment system will appear in the list.</div>
</p>
<b>Remove the module</b>
<p>
<div>1. Go to «Modules» section (Settings–>Modules). Choose «PayAnyWay payment system» in the list and press «Remove». «Attention! The module will be removed from the system with all settings.» message will appear.</div>
<div>2. To save the module tables in the database, tick off the «Do you want to save the PayAnyWay payment system module table?» field.</div>
<div>3. Click «Remove». PayAnyWay payment system module will appear with «Not installed» status in the list of modules.</div>
<div>&nbsp;&nbsp;<b>Additionally:</b></div>
<div>&nbsp;&nbsp;1. If to remove the tick from «Do you want to save the PayAnyWay payment system module table?», the table with error logs will be removed from the database. If the tick is kept for this field, the table will remain in the database with the saved and available data.</div>
<div>&nbsp;&nbsp;2. If the module is removed, «Folder_with_bitrix/bitrix/modules/payanyway.payment» folder with the installation package will be kept in the system. All other files will be removed.</div>
</p>
<b>Update the module</b>
<p>
<div>1. Go to «Partner update» section (Settings–>Marketplace–>Partner update).</div>
<div>2. Go to «Updates» tab. Select all updates for «PayAnyWay payment system», if any available. Click «Install updates». When the updates installation is finished, «Successfully installed updates: (number)» will be displayed.</div>
</p>
<b>Additionally</b>
<p>
<div>All injections of the PayAnyWay plugin code made into 1C-Bitrix files are marked with special comments:</<div>
<div>1. Start of the code added: «PayAnyWayPlugin add».</<div>
<div>2. End of the code added: «/PayAnyWayPlugin add».</div>
<div>3. Start of the code changed: «PayAnyWayPlugin change».</div>
<div>4. End of the code changed: «/PayAnyWayPlugin change».</div>
</p>';
?>