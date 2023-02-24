<?php
function ConnectDB() {
    $params = array(
        'host' => '127.0.0.1',
        'login' => 'root',
        'password' => 'root',
        'nameDB' => 'paneltest',
    );

    $db = new PDO("mysql:host={$params['host']};
                        dbname={$params['nameDB']}",
                        $params['login'],
                        $params['password']
    );

    return $db;
}

function CountRemaining($date) {
    $db = ConnectDB();

    $sql ='SELECT item_id FROM item';
    $query = $db->query($sql);

    $ids = array();

    while($row = $query->fetch()) {
        $ids[] = $row['item_id'];
    }

    $sql ='SELECT item, quantity FROM supply WHERE date <= :date';
    $query = $db->prepare($sql);
    $query->bindParam(':date', $date, PDO::PARAM_STR);
    $query->execute();

    $newItems = array();

    while($row = $query->fetch()) {
        $newItems[$row['item']] += $row['quantity'];
    }

    $sql = 'SELECT item, quantity FROM preorder WHERE date < :date';
    $query = $db->prepare($sql);
    $query->bindParam(':date', $date, PDO::PARAM_STR);
    $query->execute();

    $preordered = array();

    while($row = $query->fetch()) {
        $preordered[$row['item']] += $row['quantity'];
    }

    $result = array();

    foreach ($newItems as $key => $value) {
        $result[$key] = $value - $preordered[$key];
        if ($result[$key] < 0) {
            return false;
        }
    }

    $sql = 'UPDATE item SET remaining = :remaining WHERE item_id = :item ';
    foreach ($ids as $id) {
        $query = $db->prepare($sql);
        $insertValue = 0 + $result[$id];
        $query->bindParam(':remaining', $insertValue, PDO::PARAM_INT);
        $query->bindParam(':item', $id, PDO::PARAM_INT);
        $query->execute();
    }
    return true;
}

function CountPrices($date){
    $db = ConnectDB();

    $sql = 'SELECT DISTINCT item FROM preorder WHERE date = :date';
    $query = $db->prepare($sql);
    $query->bindParam(':date', $date, PDO::PARAM_STR);
    $query->execute();

    $preordered = array();

    while($row = $query->fetch()) {
        $preordered[] = $row['item'];
    }

    $prices = array();
    $sql = 'SELECT name, MAX(price/quantity) as price, item FROM supply INNER JOIN item ON supply.item = item.item_id ' .
           'WHERE date <= :date AND item = :item';
    foreach ($preordered as $id) {
        $query = $db->prepare($sql);
        $query->bindParam(':date', $date, PDO::PARAM_STR);
        $query->bindParam(':item', $id, PDO::PARAM_INT);
        $query->execute();
        $row = $query->fetch();
        $prices[$row['name']] = round($row['price'] + 0.3 * $row['price'], 2);
    }

    return $prices;
}

function GetRemaining($date) {
    $db = ConnectDB();

    $sql = 'SELECT DISTINCT item FROM preorder WHERE date = :date';
    $query = $db->prepare($sql);
    $query->bindParam(':date', $date, PDO::PARAM_STR);
    $query->execute();

    $preordered = array();

    while($row = $query->fetch()) {
        $preordered[] = $row['item'];
    }

    $sql = 'SELECT remaining, name FROM item WHERE item_id = :id';
    $remaining = array();
    foreach ($preordered as $id) {
        $query = $db->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $row = $query->fetch();
        $remaining[$row['name']] = $row['remaining'];
    }

    return $remaining;
}

function GetValueForFibo($date) {
    $db = ConnectDB();
    $sql = 'SELECT quantity, date FROM preorder WHERE date = :date';
    $query = $db->prepare($sql);
    $query->bindParam(':date', $date, PDO::PARAM_STR);
    $query->execute();
    $row = $query->fetch();

    return $row['quantity'];
}

function NewOrders($date) {
    $preorderStarts = '2021-01-13';
    $row = '';

    $db = ConnectDB();

    $id = 3;

    $start = new DateTime($preorderStarts);
    $currentDate = new DateTime($date);
    $diff = $currentDate->diff($start)->days;

    $sql = 'SELECT quantity, date FROM preorder WHERE date = :date';

    for($i = 0; $i <= $diff; $i++){
        $modifier = "+" . $i. " day";
        $date = date_modify(date_create($preorderStarts), $modifier)->format('Y-m-d');
        $query = $db->prepare($sql);
        $query->bindParam(':date', $date, PDO::PARAM_STR);
        $query->execute();
        $row = $query->fetch();
        if ($row['quantity'] == NULL)  {
            $oneBeforeValue = GetValueForFibo(date_modify(date_create($date), '- 1 day')->format('Y-m-d'));
            $twoBeforeValue = GetValueForFibo(date_modify(date_create($date), '- 2 day')->format('Y-m-d'));
            $value = $oneBeforeValue + $twoBeforeValue;
            $addQuery = 'INSERT INTO preorder (item, quantity, date) VALUES (:item, :quantity, :date)';
            $query = $db->prepare($addQuery);
            $query->bindParam(':date', $date, PDO::PARAM_STR);
            $query->bindParam(':item', $id, PDO::PARAM_INT);
            $query->bindParam(':quantity', $value, PDO::PARAM_INT);
            $query->execute();
        }
    }
}

/*function isEnoughItems($date) {
    $db = ConnectDB();
    $sql = "SELECT remaining, item_id FROM item";
    $query = $db->prepare($sql);
    $query->execute();

    $remaining = array();

    while($row = $query->fetch()) {
        $remaining[$row['item_id']] = $row['remaining'];
    }

    $sql = "SELECT quantity FROM preorder WHERE item = :id AND date = :date";
    foreach ($remaining as $key => $value){
        $query = $db->prepare($sql);
        $query->bindParam(':id', $key, PDO::PARAM_INT);
        $query->bindParam(':date', $date, PDO::PARAM_STR);
        $query->execute();
        $row = $query->fetch();
        if($row['quantity'] > $value){
            return false;
        }
    }
    return true;
}*/