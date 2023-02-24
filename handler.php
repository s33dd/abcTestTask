<?php
include_once 'funcs.php';

$preorderStarts = '2021-01-13';

if ($_POST['date'] < $preorderStarts or $_POST['date'] >= '2021-02-13') {
    $error = ' Введённая дата некорректна. Слишком далеко от последней поставки.';
}
else {
    if(!CountRemaining($_POST['date'])) {
        $error = ' Дата некорректна. Количество предзаказов превышает количество доступных товаров.';
    }
    else {
        NewOrders($_POST['date']);
        $remaining = GetRemaining($_POST['date']);
        $prices = CountPrices($_POST['date']);
    }
}
require_once ('index.php');