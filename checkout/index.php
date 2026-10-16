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
    <title>Оформление заказа</title>
    <link rel="stylesheet" href="/style/style.css">
    <link rel="stylesheet" href="/style/adaptive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/vladivostok" rel="stylesheet">
    <!-- Jquery взял за основу js потому что он указан в ключевых навыках вакансии -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Маска для инпута телефона -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>
<body>

    <!-- Решил использовать бэк на php для обхода ошибки cors -->
    <!-- Почему именно php? Потому что больше всего работал именно на нём -->
    <?php
        $url = 'https://growtask.ru/test-front/api/products';
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        $data = $data["data"];
        $cartItems = json_decode($_POST['items']);
        $promo = $_POST['promo'];
        $allPrice = $_POST['allPrice'];
        $allCount = $_POST['allCount'];
        $promoText = $_POST['promoText'];

        // Собираю новый массив с товарами, у которых количество больше 0
        $available = [];
        foreach ($data as $item) {
            if ($item["quantity"] > 0) {
                $available[] = $item;
            }
        }

        $count = 0;
        $priceCount = 0;

        // Собираю еще один массив, отбираю товары из корзины и дополняю инфой из прошлого массива
        $itemsList = [];
        foreach ($cartItems as $cartItem) {
            foreach ($available as $item) {
                if ($cartItem->id == $item['id'] && $cartItem->checked != 0) {
                    $item['cart_quantity'] = $cartItem->quantity;
                    $itemsList[] = $item;
                    $count += $cartItem->quantity;
                    $priceCount += $cartItem->quantity * $item['price'];
                    break;
                }
            }
        }

        $formateCount = number_format($priceCount, 0, '.', ' ');
    ?>

    <div class="main-content-wrap">

    <div class="container">
        <header>
            <img class="logo mid-hide" src="/img/logo.png" alt="ГРОТЕСК">
            <button class="cart-button mid-hide" onclick="history.back(); return false;">
                <img src="/img/cart.svg" alt="Корзина">
                <div class="cart-button-count"><?php echo $allCount; ?></div>
                <div class="cart-button-price"><?php echo $allPrice; ?></div>
            </button>
            <!-- Почему не сделал адекватную ссылку? Просто потому что при таком переходе назад можно сказать сохраняются данные из прошлой формы. Бд у меня все таки нет, а отпралвять данные еще раз на прошлую страницу поленился -->
            <button onclick="history.back(); return false;" class="large-hide mid-show header-back-button"><img src="/img/back.svg" alt="Назад"></button>
            <p class="large-hide mid-show">Оформление заказа</p>
        </header>

        <div class="breadcrumbs mid-hide">
            <a href="#" onclick="history.back(); return false;" class="breadcrumb-start">
                <img src="/img/home.svg" alt="На главную">
            </a> 
            <span>/</span>
            <a class="breadcrumb-peace" href="#" onclick="history.back(); return false;">Корзина</a>
            <span>/</span>
            <p>Оформление заказа</p>
        </div>

        <form method="POST" action="/final/">
        <div class="main-content">
            <div class="products">
                <div class="page-name">
                    <a href="#" onclick="history.back(); return false;" class="back-to-cart mid-hide">
                        <img src="/img/back.svg" alt="Назад">
                        <p>В корзину</p>
                    </a>
                    <h1 class="order-title">Оформление заказа</h1>
                </div>

                <div class="active-items-block">
                    <p class="active-items-count"><?php echo $count; ?> товаров</p>

                    <div class="active-items-wrap">
                        <?php
                            foreach ($itemsList as $item) {
                                echo '<div class="active-items-img-wrap">
                                    <img src="'.$item['image'].'" alt="Карточка товара">
                                    <p>'.$item['cart_quantity'].'</p>
                                </div>';
                            }
                        ?>
                    </div>

                    <a class="second-back-to-cart" href="#" onclick="history.back(); return false;">
                        <img src="/img/back.svg" alt="Назад">
                        <p>Вернуться в корзину</p>
                    </a>
                </div>

                <div class="client-form">
                    <p class="client-title">Покупатель</p>
                    <p class="client-sub-title">Укажите свои данные для покупки наших товаров</p>

                    <div class="client-wrap">
                        <div class="client-input-wrap">
                            <p class="client-input-title">Имя*</p>
                            <input name="name" class="client-input" type="text" placeholder="Иван" required>
                            <svg class="icon clear" viewBox="0 0 16 16" width="16" height="16">
                                <path d="M14.2929 0.29289C14.6834 -0.0973697 15.3165 -0.097541 15.707 0.29289C16.0974 0.683326 16.0972 1.31641 15.707 1.70695L9.41398 7.99992L15.707 14.2929C16.0973 14.6834 16.0974 15.3165 15.707 15.707C15.3165 16.0973 14.6834 16.0973 14.2929 15.707L7.99992 9.41398L1.70695 15.707C1.31642 16.0973 0.683368 16.0974 0.29289 15.707C-0.0974445 15.3165 -0.0974462 14.6834 0.29289 14.2929L6.58586 7.99992L0.29289 1.70695C-0.0976315 1.31643 -0.0976283 0.683416 0.29289 0.29289C0.683411 -0.0976315 1.31643 -0.0976283 1.70695 0.29289L7.99992 6.58586L14.2929 0.29289Z" fill="currentColor"></path>
                            </svg>
                        </div>
                        <div class="client-input-wrap">
                            <p class="client-input-title">Фамилия*</p>
                            <input name="surname" class="client-input" type="text" placeholder="Иванов" required>
                            <svg class="icon clear" viewBox="0 0 16 16" width="16" height="16">
                                <path d="M14.2929 0.29289C14.6834 -0.0973697 15.3165 -0.097541 15.707 0.29289C16.0974 0.683326 16.0972 1.31641 15.707 1.70695L9.41398 7.99992L15.707 14.2929C16.0973 14.6834 16.0974 15.3165 15.707 15.707C15.3165 16.0973 14.6834 16.0973 14.2929 15.707L7.99992 9.41398L1.70695 15.707C1.31642 16.0973 0.683368 16.0974 0.29289 15.707C-0.0974445 15.3165 -0.0974462 14.6834 0.29289 14.2929L6.58586 7.99992L0.29289 1.70695C-0.0976315 1.31643 -0.0976283 0.683416 0.29289 0.29289C0.683411 -0.0976315 1.31643 -0.0976283 1.70695 0.29289L7.99992 6.58586L14.2929 0.29289Z" fill="currentColor"></path>
                            </svg>
                        </div>
                        <div class="client-input-wrap">
                            <p class="client-input-title">Телефон</p>
                            <img src="/img/phone.svg" alt="Телефон">
                            <input name="phone" class="client-input extra-padding phone-mask" type="text" placeholder="+7 800 555 35 35">
                            <svg class="icon clear" viewBox="0 0 16 16" width="16" height="16">
                                <path d="M14.2929 0.29289C14.6834 -0.0973697 15.3165 -0.097541 15.707 0.29289C16.0974 0.683326 16.0972 1.31641 15.707 1.70695L9.41398 7.99992L15.707 14.2929C16.0973 14.6834 16.0974 15.3165 15.707 15.707C15.3165 16.0973 14.6834 16.0973 14.2929 15.707L7.99992 9.41398L1.70695 15.707C1.31642 16.0973 0.683368 16.0974 0.29289 15.707C-0.0974445 15.3165 -0.0974462 14.6834 0.29289 14.2929L6.58586 7.99992L0.29289 1.70695C-0.0976315 1.31643 -0.0976283 0.683416 0.29289 0.29289C0.683411 -0.0976315 1.31643 -0.0976283 1.70695 0.29289L7.99992 6.58586L14.2929 0.29289Z" fill="currentColor"></path>
                            </svg>
                        </div>
                        <div class="client-input-wrap">
                            <p class="client-input-title">Почта</p>
                            <img src="/img/mail.svg" alt="Почта">
                            <input name="mail" class="client-input extra-padding" type="email" placeholder="mail@example.com">
                            <svg class="icon clear" viewBox="0 0 16 16" width="16" height="16">
                                <path d="M14.2929 0.29289C14.6834 -0.0973697 15.3165 -0.097541 15.707 0.29289C16.0974 0.683326 16.0972 1.31641 15.707 1.70695L9.41398 7.99992L15.707 14.2929C16.0973 14.6834 16.0974 15.3165 15.707 15.707C15.3165 16.0973 14.6834 16.0973 14.2929 15.707L7.99992 9.41398L1.70695 15.707C1.31642 16.0973 0.683368 16.0974 0.29289 15.707C-0.0974445 15.3165 -0.0974462 14.6834 0.29289 14.2929L6.58586 7.99992L0.29289 1.70695C-0.0976315 1.31643 -0.0976283 0.683416 0.29289 0.29289C0.683411 -0.0976315 1.31643 -0.0976283 1.70695 0.29289L7.99992 6.58586L14.2929 0.29289Z" fill="currentColor"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="client-warning">
                        <img src="/img/info.svg" alt="Информация">
                        Внимательно проверьте контактные данные, они нужны для получения заказа
                    </div>

                    <p class="client-input-title">Комментарий к заказу</p>
                    <textarea name="wish" class="client-textarea" placeholder="Напишите свои пожелания..."></textarea>

                    <!-- Не знаю почему этот блок только на мобильной версии, но так в дизайне -->
                    <div class="news-block">
                        <input type="hidden" name="news" value="0">
                        <input class="normal-checkbox" type="checkbox" name="news" value="1" checked>
                        Хочу получать уведомления о скидках и специальных предложениях
                    </div>
                </div>

                <div class="client-form pay-type-form">
                    <p class="client-title">Способ оплаты</p>
                    <p class="client-sub-title">Выберите удобный способ оплаты заказа</p>
     
                    <div class="radio-line">
                        <label class="online">
                            <input type="radio" name="payType" value="online" checked>
                            <span>Онлайн</span>
                            <p>Visa, Mastercard, Мир, SberPay</p>
                            <svg class="icon" viewBox="0 0 22 16" width="22" height="16">
                                <path d="M20 7H2V11.7998C2 12.3764 2.00127 12.7489 2.02442 13.0322C2.04662 13.3038 2.08419 13.4047 2.10938 13.4541C2.20522 13.6421 2.35793 13.7948 2.5459 13.8906C2.59534 13.9158 2.69619 13.9534 2.96778 13.9756C3.25112 13.9987 3.62365 14 4.2002 14H17.7998C18.3764 14 18.7489 13.9987 19.0322 13.9756C19.3038 13.9534 19.4047 13.9158 19.4541 13.8906C19.6421 13.7948 19.7948 13.6421 19.8906 13.4541C19.9158 13.4047 19.9534 13.3038 19.9756 13.0322C19.9987 12.7489 20 12.3764 20 11.7998V7ZM20 4.2002C20 3.62365 19.9987 3.25112 19.9756 2.96778C19.9534 2.69619 19.9158 2.59534 19.8906 2.5459C19.7948 2.35793 19.6421 2.20522 19.4541 2.10938C19.4047 2.08419 19.3038 2.04662 19.0322 2.02442C18.7489 2.00127 18.3764 2 17.7998 2H4.2002C3.62365 2 3.25112 2.00127 2.96778 2.02442C2.69619 2.04662 2.59534 2.08419 2.5459 2.10938C2.35793 2.20522 2.20522 2.35793 2.10938 2.5459C2.08419 2.59534 2.04662 2.69619 2.02442 2.96778C2.00127 3.25112 2 3.62365 2 4.2002V5H20V4.2002ZM22 11.7998C22 12.3434 22.0011 12.8116 21.9697 13.1953C21.9374 13.5906 21.8656 13.984 21.6729 14.3623C21.3853 14.9265 20.9265 15.3853 20.3623 15.6729C19.984 15.8656 19.5906 15.9374 19.1953 15.9697C18.8116 16.0011 18.3434 16 17.7998 16H4.2002C3.65665 16 3.18836 16.0011 2.80469 15.9697C2.40943 15.9374 2.016 15.8656 1.6377 15.6729C1.07348 15.3853 0.614727 14.9265 0.327153 14.3623C0.134401 13.984 0.0625889 13.5906 0.0302779 13.1953C-0.00106931 12.8116 4.40412e-06 12.3434 4.41543e-06 11.7998V4.2002C4.42675e-06 3.65665 -0.00106932 3.18836 0.0302779 2.80469C0.0625888 2.40943 0.134401 2.016 0.327153 1.6377C0.614727 1.07348 1.07348 0.614727 1.6377 0.327153C2.016 0.134401 2.40943 0.0625888 2.80469 0.0302779C3.18836 -0.00106927 3.65665 4.37174e-06 4.2002 4.42163e-06H17.7998C18.3434 4.47153e-06 18.8116 -0.00106934 19.1953 0.0302779C19.5906 0.0625888 19.984 0.134401 20.3623 0.327153C20.9265 0.614726 21.3853 1.07348 21.6729 1.6377C21.8656 2.016 21.9374 2.40944 21.9697 2.80469C22.0011 3.18836 22 3.65665 22 4.2002V11.7998Z" fill="currentColor"/>
                            </svg>
                        </label>
                        <label class="offline">
                            <input type="radio" name="payType" value="offline">
                            <span>При получении</span>
                            <p>Наличными или картой при получении</p>
                            <svg class="icon" viewBox="0 0 22 16" width="22" height="16">
                                <path d="M20 4.2002C20 3.62365 19.9987 3.25112 19.9756 2.96778C19.9534 2.69619 19.9158 2.59534 19.8906 2.5459C19.7948 2.35793 19.6421 2.20522 19.4541 2.10938C19.4047 2.08419 19.3038 2.04662 19.0322 2.02442C18.7489 2.00127 18.3764 2 17.7998 2H4.2002C3.62365 2 3.25112 2.00127 2.96778 2.02442C2.69619 2.04662 2.59534 2.08419 2.5459 2.10938C2.35793 2.20522 2.20522 2.35793 2.10938 2.5459C2.08419 2.59534 2.04662 2.69619 2.02442 2.96778C2.00127 3.25112 2 3.62365 2 4.2002V11.7998C2 12.3764 2.00127 12.7489 2.02442 13.0322C2.04662 13.3038 2.08419 13.4047 2.10938 13.4541C2.20522 13.6421 2.35793 13.7948 2.5459 13.8906C2.59534 13.9158 2.69619 13.9534 2.96778 13.9756C3.25112 13.9987 3.62365 14 4.2002 14H17.7998C18.3764 14 18.7489 13.9987 19.0322 13.9756C19.3038 13.9534 19.4047 13.9158 19.4541 13.8906C19.6421 13.7948 19.7948 13.6421 19.8906 13.4541C19.9158 13.4047 19.9534 13.3038 19.9756 13.0322C19.9987 12.7489 20 12.3764 20 11.7998V4.2002ZM12.5 8C12.5 7.17158 11.8284 6.5 11 6.5C10.1716 6.5 9.5 7.17158 9.5 8C9.5 8.82843 10.1716 9.5 11 9.5C11.8284 9.5 12.5 8.82843 12.5 8ZM4 10V6C4 5.44772 4.44772 5 5 5C5.55229 5 6 5.44772 6 6V10C6 10.5523 5.55229 11 5 11C4.44772 11 4 10.5523 4 10ZM16 10V6C16 5.44772 16.4477 5 17 5C17.5523 5 18 5.44772 18 6V10C18 10.5523 17.5523 11 17 11C16.4477 11 16 10.5523 16 10ZM14.5 8C14.5 9.933 12.933 11.5 11 11.5C9.06701 11.5 7.5 9.933 7.5 8C7.5 6.06701 9.06701 4.5 11 4.5C12.933 4.5 14.5 6.06701 14.5 8ZM22 11.7998C22 12.3434 22.0011 12.8116 21.9697 13.1953C21.9374 13.5906 21.8656 13.984 21.6729 14.3623C21.3853 14.9265 20.9265 15.3853 20.3623 15.6729C19.984 15.8656 19.5906 15.9374 19.1953 15.9697C18.8116 16.0011 18.3434 16 17.7998 16H4.2002C3.65665 16 3.18836 16.0011 2.80469 15.9697C2.40943 15.9374 2.016 15.8656 1.6377 15.6729C1.07348 15.3853 0.614727 14.9265 0.327153 14.3623C0.134401 13.984 0.0625889 13.5906 0.0302779 13.1953C-0.0010693 12.8116 4.40407e-06 12.3434 4.41539e-06 11.7998V4.2002C4.4267e-06 3.65665 -0.00106933 3.18836 0.0302779 2.80469C0.0625888 2.40943 0.134401 2.016 0.327153 1.6377C0.614727 1.07348 1.07348 0.614727 1.6377 0.327153C2.016 0.134401 2.40943 0.0625888 2.80469 0.0302779C3.18836 -0.00106928 3.65665 4.37178e-06 4.2002 4.42167e-06H17.7998C18.3434 4.47157e-06 18.8116 -0.00106934 19.1953 0.0302779C19.5906 0.0625888 19.984 0.134401 20.3623 0.327153C20.9265 0.614726 21.3853 1.07348 21.6729 1.6377C21.8656 2.016 21.9374 2.40944 21.9697 2.80469C22.0011 3.18836 22 3.65665 22 4.2002V11.7998Z" fill="currentColor"/>
                            </svg>
                        </label>
                    </div>

                    <div class="client-warning warning-no-bottom">
                        <img src="/img/info.svg" alt="Информация">
                        При оформлении заказа вы будете перенаправлены на страницу платёжной системы
                    </div>
                </div>

                <?php
                    $discountPrice = $priceCount * 0.8;
                    if ($promo == 1) {
                        echo '<input type="hidden" name="discountPrice" value="'.$discountPrice.'">';
                    } else {
                        echo '<input type="hidden" name="discountPrice" value="0">';
                    }
                    $allCount = $allCount - $count;
                ?>
                
                <input type="hidden" name="priceCartButton" value="<?php echo $allPrice; ?>">
                <input type="hidden" name="countCartButton" value="<?php echo $allCount; ?>">
                <input type="hidden" name="price" value="<?php echo $priceCount; ?>">
                <input type="hidden" name="promo" value="<?php echo $promo; ?>">
                <input type="hidden" name="items" value="<?php echo htmlspecialchars(json_encode($itemsList), ENT_QUOTES); ?>">

                <div class="order-final mid-hide">
                    <button id="final-button" type="submit" disabled>Оформить заказ</button>
                    
                    <div class="ready-check">
                        <img src="/img/light-info.svg" alt="Информация">
                        Вы указали все необходимые данные, теперь можно оформить заказ
                    </div>
                </div>
                
            </div>

            <div class="payment">
                <div class="pay-card order-new-mg">
                    <p class="pay-card-title">Ваш заказ:</p>

                    <div class="pay-card-line">
                        <p id="total-count" class="pay-card-text"><?php echo $count; ?> товаров:</p>
                        <p id="total-price" class="pay-card-text pay-card-black-text"><?php echo $formateCount; ?> ₽</p>
                    </div>
                    <div class="pay-card-line special-margin">
                        <p class="pay-card-text">Скидка:</p>
                        <p id="discount-price" class="pay-card-text pay-card-red-text">0 ₽</p>
                    </div>
                    <div class="pay-card-line special-padding">
                        <p class="pay-card-big-text">Итого:</p>
                        <p id="total-final-price" class="pay-card-big-text"><?php echo $formateCount; ?> ₽</p>
                    </div>

                    <button class="pay-button" type="submit" disabled>Оформить заказ</button>
                </div>

                <div class="discount-line">
                    <div class="discount-promo">
                        <svg class="icon" viewBox="0 0 18 20" width="18" height="20">
                            <path d="M16 5.79981C16 4.94342 15.9997 4.36117 15.9629 3.91113C15.9271 3.47272 15.8619 3.2482 15.7822 3.0918C15.5905 2.71555 15.2845 2.40951 14.9082 2.21778C14.7518 2.13809 14.5273 2.07293 14.0889 2.03711C13.6388 2.00035 13.0566 2 12.2002 2H5.79981C4.94342 2 4.36117 2.00035 3.91113 2.03711C3.47272 2.07293 3.2482 2.13809 3.0918 2.21778C2.71555 2.40951 2.40951 2.71555 2.21778 3.0918C2.13809 3.2482 2.07293 3.47272 2.03711 3.91113C2.00035 4.36117 2 4.94342 2 5.79981V17.0361L3.16211 16.1914L3.30371 16.1045C3.64774 15.9329 4.06708 15.9724 4.375 16.2188L6.2793 17.7422L8.41211 16.1914L8.54883 16.1074C8.87938 15.9402 9.28115 15.9684 9.58789 16.1914L11.7197 17.7422L13.625 16.2188C13.9768 15.9373 14.4735 15.9265 14.8379 16.1914L16 17.0361V5.79981ZM11.793 5.29297C12.1835 4.90245 12.8165 4.90245 13.207 5.29297C13.5976 5.6835 13.5976 6.31651 13.207 6.70703L8.70703 11.207C8.31651 11.5976 7.6835 11.5976 7.29297 11.207L5.29297 9.20703C4.90245 8.81651 4.90245 8.18349 5.29297 7.79297C5.6835 7.40245 6.31651 7.40245 6.70703 7.79297L8 9.08594L11.793 5.29297ZM18 19C18 19.3761 17.7892 19.7209 17.4541 19.8916C17.119 20.0623 16.7163 20.0297 16.4121 19.8086L14.2793 18.2568L12.375 19.7813C12.0232 20.0627 11.5265 20.0735 11.1621 19.8086L9 18.2354L6.83789 19.8086C6.47351 20.0735 5.97681 20.0627 5.625 19.7813L3.71973 18.2568L1.58789 19.8086C1.28372 20.0297 0.881028 20.0623 0.5459 19.8916C0.210765 19.7209 1.9493e-06 19.3761 1.9493e-06 19V5.79981C1.82682e-06 4.97632 -0.00102054 4.29843 0.0439473 3.74805C0.0898762 3.18599 0.188274 2.6689 0.435549 2.1836C0.819018 1.43109 1.43109 0.819018 2.1836 0.435549C2.6689 0.188274 3.18599 0.0898762 3.74805 0.0439473C4.29843 -0.00102053 4.97632 1.8265e-06 5.79981 1.94928e-06H12.2002C13.0237 1.8265e-06 13.7016 -0.00102053 14.252 0.0439473C14.814 0.0898761 15.3311 0.188274 15.8164 0.435549C16.5689 0.819018 17.181 1.43109 17.5645 2.1836C17.8117 2.6689 17.9101 3.18599 17.9561 3.74805C18.001 4.29843 18 4.97632 18 5.79981V19Z" fill="currentColor"/>
                        </svg>
                        <p>Промокод</p>
                    </div>
                    <svg class="icon arrow" viewBox="0 0 10 10" width="10" height="10">
                        <path d="M8.29289 5.70698C8.68342 6.09751 9.31643 6.09751 9.70696 5.70698C10.0975 5.31646 10.0975 4.68345 9.70696 4.29292L5.70696 0.292922C5.31643 -0.0976024 4.68342 -0.0976024 4.29289 0.292922L0.292893 4.29292C-0.0976311 4.68345 -0.0976311 5.31646 0.292893 5.70698C0.683418 6.09751 1.31643 6.09751 1.70696 5.70698L4.99992 2.41402L8.29289 5.70698Z" fill="currentColor"/>
                    </svg>
                </div>

                <div class="promo">
                    <p id="promo-alert">Промокод</p>
                    <div class="promo-wrap" data-promo="0">
                        <input class="promo-input" type="text" value="<?php echo $promoText; ?>" placeholder="Введите промокод">
                        <svg id="clear-promo" class="icon" viewBox="0 0 16 16" width="16" height="16">
                            <path d="M14.2929 0.29289C14.6834 -0.0973697 15.3165 -0.097541 15.707 0.29289C16.0974 0.683326 16.0972 1.31641 15.707 1.70695L9.41398 7.99992L15.707 14.2929C16.0973 14.6834 16.0974 15.3165 15.707 15.707C15.3165 16.0973 14.6834 16.0973 14.2929 15.707L7.99992 9.41398L1.70695 15.707C1.31642 16.0973 0.683368 16.0974 0.29289 15.707C-0.0974445 15.3165 -0.0974462 14.6834 0.29289 14.2929L6.58586 7.99992L0.29289 1.70695C-0.0976315 1.31643 -0.0976283 0.683416 0.29289 0.29289C0.683411 -0.0976315 1.31643 -0.0976283 1.70695 0.29289L7.99992 6.58586L14.2929 0.29289Z" fill="currentColor"/>
                        </svg>
                    </div>
                    <button class="promo-button" type="button">Применить</button>
                </div>
            </div>
        </div>
        </form>
    </div>

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
        // Вся база кода логики взята с корзины
        <?php
            if ($promo == 1) {
                echo "
                    $('#promo-alert').addClass('nice').text(\"Промокод применён\");
                    $('.promo-wrap').addClass('block');

                    const oldPrice = $priceCount;
                    let newPrice = oldPrice * 0.8;
                    let discountAmount = oldPrice - newPrice;
                    newPrice = newPrice.toLocaleString('ru-RU');
                    discountAmount = discountAmount.toLocaleString('ru-RU');
                    
                    $('#total-final-price').text(newPrice + \" ₽\");
                    $('#discount-price').text(discountAmount + \" ₽\");
                    $('.promo-wrap').data('promo', 1);
                ";
            }      
        ?>

        $('.promo-button').on('click', function () {
            const $promo = $('.promo-input');
            let promo = $promo.val();

            $.ajax({
                url: '/promo/',
                method: 'POST',
                data: { promo: promo },
                dataType: 'json',
                success: function (response) {
                    if (response.valid) {
                        $('#promo-alert').addClass('nice').text("Промокод применён");
                        $('.promo-wrap').addClass('block');

                        const oldPrice = <?php echo $priceCount; ?>;
                        let newPrice = oldPrice * 0.8;
                        let discountAmount = oldPrice - newPrice;
                        let postPrice = newPrice;
                        newPrice = newPrice.toLocaleString('ru-RU');
                        discountAmount = discountAmount.toLocaleString('ru-RU');
                        
                        $('#total-final-price').text(newPrice + " ₽");
                        $('#discount-price').text(discountAmount + " ₽");
                        $('.promo-wrap').data('promo', 1);
                        $('[name="discountPrice"]').val(postPrice);
                        $('[name="promo"]').val(1);
                    } else {
                        $('#promo-alert').addClass('bad').text("Неправильный промокод");
                    }
                },
                error: function () {
                    $('#promo-alert').addClass('bad').text('Ошибка сервера');
                }
            });
        });

        $('.discount-line').on('click', function () {
            $('.promo').toggleClass('visible');
            $('.arrow').toggleClass('rotate');
        });

        $('#clear-promo').on('click', function () {
            $('.promo-input').val('');
        });

        $('.clear').on('click', function () {
            $(this).siblings('.client-input').val('');
        });

        $('.phone-mask').mask('+7 (000) 000-00-00');

        // Проверка обязательных полей на наличие чего либо, чтобы надпись добавить, о том что всё гуд ту го
        function checkRequiredFields() {
            let allFilled = true;
            
            $('input[required]').each(function() {
                if ($(this).val().trim() === '') {
                    allFilled = false;
                    return false;
                }
            });
            
            if (allFilled) {
                $('.ready-check').addClass('visible');
                $('.pay-button').prop('disabled', false);
                $('#final-button').prop('disabled', false);
            } else {
                $('.ready-check').removeClass('visible');
                $('.pay-button').prop('disabled', true);
                $('#final-button').prop('disabled', true);
            }
        }

        $(document).on('input change', 'input[required]', checkRequiredFields);
        $(document).ready(checkRequiredFields);
    </script>

</body>
</html>