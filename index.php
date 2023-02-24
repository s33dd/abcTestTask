<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style/style.css">
    <title>Test</title>
</head>
<body>
<div class="wrapper">
    <div>
        <div class="error-message">
            <p class="error-text">
                <?
                    if (isset($error)){
                        echo "Ошибка:" . $error;
                    }
                ?>
            </p>
        </div>
        <form action="handler.php" method="POST">
            <input value="<? echo $_POST['date'] ?>" name="date" type="date">
            <button type="submit">Применить</button>
        </form>
        <div class="information">
            <span>Наименование</span>
            <span>Остаток на складе</span>
            <span>Цена на сегодня</span>
            <? if (isset($prices)) : ?>
                <? foreach ($prices as $key => $value) : ?>
                <div class="information__name">
                    <span>
                    <?
                        if (isset($key)) {
                            echo $key;
                        } else {
                            echo "—";
                        }
                    ?>
                    </span>
                </div>
                <div class="information__left">
                    <span>
                    <?
                        if (isset($remaining[$key])) {
                            echo $remaining[$key];
                        } else {
                            echo "—";
                        }
                    ?>
                    </span>
                </div>
                <div class="information__price">
                    <span>
                    <?
                        if (isset($value)) {
                            echo $value;
                        } else {
                            echo "—";
                        }
                    ?>
                    </span>
                </div>
            <? endforeach; ?>
            <? endif; ?>
        </div>
    </div>
</div>
</body>
</html>