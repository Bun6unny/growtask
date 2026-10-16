<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статус заказа</title>
    <link rel="stylesheet" href="/style/style.css">
    <link rel="stylesheet" href="/style/adaptive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/vladivostok" rel="stylesheet">
    <!-- Jquery взял за основу js потому что он указан в ключевых навыках вакансии -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

    <!-- Решил использовать бэк на php для обхода ошибки cors -->
    <!-- Почему именно php? Потому что больше всего работал именно на нём -->
    <?php
        // Очередной псевдокод для подсчета остатков в корзине
        $allCount = $_POST['countCartButton'];
        $allPrice = $_POST['priceCartButton'];

        $allPrice = preg_replace('/\D/', '', $allPrice);
        $allPrice = (int) $allPrice;
        $allPrice = $allPrice - (int) $_POST['price'];
        $allPrice = number_format($allPrice, 0, ',', ' ') . ' ₽';

        $data = $_POST;
        $data['items'] = json_decode($data['items']);
    ?>

    <div class="main-content-wrap">

        <div class="container">
            <header>
                <img class="logo mid-hide" src="/img/logo.png" alt="ГРОТЕСК">
                <button class="cart-button mid-hide" onclick="window.location.href='/'">
                    <img src="/img/cart.svg" alt="Корзина">
                    <div class="cart-button-count"><?php echo $allCount; ?></div>
                    <div class="cart-button-price"><?php echo $allPrice; ?></div>
                </button>
                <p class="large-hide mid-show">Статус заказа</p>
            </header>

            <div class="breadcrumbs mid-hide">
                <a href="#" onclick="history.back(); return false;" class="breadcrumb-start">
                    <img src="/img/home.svg" alt="На главную">
                </a> 
                <span>/</span>
                <a class="breadcrumb-peace" href="/">Корзина</a>
                <span>/</span>
                <!-- Не понимаю если честно зачем нужна ссылка на оформление заказа, если он уже оформлен. Поэтому просто пересылаю в корзину -->
                <a class="breadcrumb-peace" href="/">Оформление заказа</a>
                <span>/</span>
                <p>Статус заказа</p>
            </div>
        </div>
        
    </div>

    <div class="success">
        <img src="/img/success.svg" alt="Успех">
        <h1>Заказ №001 успешно создан</h1>
        <p>Статус заказа вы можете отслеживать в личном кабинете</p>
        <a href="/">В корзину</a>
    </div>

    <div class="grower"></div>

    <footer>
        <div class="container">
            <div class="footer-content">
                <p>@ Company name 2026</p>
                <img class="logo" src="/img/footer-logo.png" alt="ГРОТЕСК">
            </div>
        </div>
    </footer>

    <script>
        const data = <?php echo json_encode($data); ?>;
        console.log(data);
    </script>

</body>
</html>