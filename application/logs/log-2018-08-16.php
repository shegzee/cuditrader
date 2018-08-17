<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2018-08-16 07:59:33 --> Query error: La table 'cudi_trader.items' n'existe pas - Invalid query: SELECT items.name, SUM(transactions.quantity) as 'totSold' FROM items 
                INNER JOIN transactions ON items.code=transactions.itemCode GROUP BY transactions.itemCode ORDER BY totSold DESC LIMIT 5
ERROR - 2018-08-16 08:25:59 --> Query error: La table 'cudi_trader.items' n'existe pas - Invalid query: SELECT items.name, SUM(transactions.quantity) as 'totSold' FROM items 
                INNER JOIN transactions ON items.code=transactions.itemCode GROUP BY transactions.itemCode ORDER BY totSold DESC LIMIT 5
ERROR - 2018-08-16 08:27:02 --> Query error: La table 'cudi_trader.items' n'existe pas - Invalid query: SELECT items.name, SUM(transactions.quantity) as 'totSold' FROM items 
                INNER JOIN transactions ON items.code=transactions.itemCode GROUP BY transactions.itemCode ORDER BY totSold ASC LIMIT 5
ERROR - 2018-08-16 08:27:06 --> Query error: La table 'cudi_trader.items' n'existe pas - Invalid query: SELECT items.name, SUM(transactions.quantity) as 'totSold' FROM items 
                INNER JOIN transactions ON items.code=transactions.itemCode GROUP BY transactions.itemCode ORDER BY totSold ASC LIMIT 5
ERROR - 2018-08-16 08:27:54 --> Query error: La table 'cudi_trader.transactions' n'existe pas - Invalid query: SELECT SUM(quantity) as 'totalTransToday' FROM transactions WHERE DATE(transDate) = CURRENT_DATE
ERROR - 2018-08-16 08:28:07 --> Query error: La table 'cudi_trader.transactions' n'existe pas - Invalid query: SELECT count(DISTINCT REF) as 'totalTrans' FROM transactions
ERROR - 2018-08-16 08:29:39 --> Severity: Parsing Error --> syntax error, unexpected '$data' (T_VARIABLE) C:\wamp\www\cudi\application\controllers\admin\Dashboard.php 46
ERROR - 2018-08-16 08:30:03 --> Query error: La table 'cudi_trader.transactions' n'existe pas - Invalid query: SELECT `transDate`, `totalPrice`
FROM `transactions`
WHERE YEAR(transDate) = '2018'
ERROR - 2018-08-16 08:30:03 --> Query error: La table 'cudi_trader.transactions' n'existe pas - Invalid query: SELECT `modeOfPayment`
FROM `transactions`
WHERE YEAR(transDate) = '2018'
GROUP BY `ref`
ERROR - 2018-08-16 08:30:03 --> Query error: La table 'cudi_trader.transactions' n'existe pas - Invalid query: SELECT SUM(totalMoneySpent) as 'totalEarnedToday' FROM transactions WHERE DATE(transDate) = CURRENT_DATE
ERROR - 2018-08-16 13:33:18 --> Severity: Error --> Call to undefined function form_eror() C:\wamp\www\cudi\application\views\user\register.php 12
ERROR - 2018-08-16 15:28:28 --> Severity: Parsing Error --> syntax error, unexpected '->' (T_OBJECT_OPERATOR) C:\wamp\www\cudi\application\models\User_model.php 13
ERROR - 2018-08-16 15:28:46 --> Severity: Parsing Error --> syntax error, unexpected ';' C:\wamp\www\cudi\application\models\User_model.php 42
ERROR - 2018-08-16 15:28:58 --> Severity: Parsing Error --> syntax error, unexpected '}' C:\wamp\www\cudi\application\models\User_model.php 47
ERROR - 2018-08-16 15:29:23 --> Severity: Error --> Call to undefined function enforce_login() C:\wamp\www\cudi\application\controllers\User.php 102
ERROR - 2018-08-16 15:29:44 --> Severity: Warning --> Missing argument 1 for User_model::user_profile(), called in C:\wamp\www\cudi\application\controllers\User.php on line 104 and defined C:\wamp\www\cudi\application\models\User_model.php 49
ERROR - 2018-08-16 15:29:44 --> Severity: Notice --> Undefined variable: user_id C:\wamp\www\cudi\application\models\User_model.php 50
ERROR - 2018-08-16 15:29:44 --> Query error: La table 'cudi_trader.user_profile' n'existe pas - Invalid query: SELECT *
FROM `user_profile`
WHERE `user_id` IS NULL
ERROR - 2018-08-16 15:30:31 --> Query error: La table 'cudi_trader.user_profile' n'existe pas - Invalid query: SELECT *
FROM `user_profile`
WHERE `user_id` = '2'
ERROR - 2018-08-16 15:32:09 --> Severity: Parsing Error --> syntax error, unexpected '$user' (T_VARIABLE), expecting ',' or ';' C:\wamp\www\cudi\application\views\user\profile.php 7
ERROR - 2018-08-16 15:32:23 --> Severity: Notice --> Trying to get property of non-object C:\wamp\www\cudi\application\views\user\profile.php 13
ERROR - 2018-08-16 15:33:16 --> Severity: Parsing Error --> syntax error, unexpected '>' C:\wamp\www\cudi\application\views\user\profile.php 16
