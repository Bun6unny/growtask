<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/adaptive.css">
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
        $url = 'https://growtask.ru/test-front/api/products';
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        $data = $data["data"];

        // Собираю новый массив с товарами, у которых количество больше 0
        $available = [];
        foreach ($data as $item) {
            if ($item["quantity"] > 0) {
                $available[] = $item;
            }
        }
        
        // Первоначальный подсчет
        $count = 0;
        $priceCount = 0;
        foreach ($available as $item) {
            $count += 1;
            $priceCount += $item["price"];
        }
        $formateCount = number_format($priceCount, 0, '.', ' ');
    ?>

    <div class="main-content-wrap">

    <div class="container">
        <header>
            <img class="logo mid-hide" src="img/logo.png" alt="ГРОТЕСК">
            <button class="cart-button mid-hide" data-price-count="<?php echo $priceCount; ?>">
                <img src="img/cart.svg" alt="Корзина">
                <div class="cart-button-count"><?php echo $count; ?></div>
                <div class="cart-button-price"><?php echo $formateCount; ?> ₽</div>
            </button>
            <p class="large-hide mid-show">Корзина</p>
        </header>

        <div class="breadcrumbs mid-hide">
            <a href="#" class="breadcrumb-start">
                <img src="img/home.svg" alt="На главную">
            </a> 
            <span>/</span>
            <p>Корзина</p>
        </div>

        <div class="main-content">
            <div class="products">
                <div class="page-name">
                    <h1>Корзина</h1>
                    <div class="cart-count"><?php echo $count; ?> шт.</div>
                </div>

                <div class="cart-control">
                    <label>
                        <input id="select-all" class="normal-checkbox" type="checkbox" checked>
                        <span class="mid-hide">Выбрать все</span>
                    </label>
                    <button id="delete-all">
                        <img class="garbage" src="img/garbage.svg" alt="Удалить всё">
                        <span>Очистить корзину</span>
                    </button>
                </div>

                <!-- Вывод товаров на страницу -->
                <?php
                    foreach ($available as $item) {
                        $price = number_format($item['price'], 0, '.', ' ');
                        echo '<div class="product-card" data-id="'.$item['id'].'" data-price="'.$item['price'].'" data-quantity="'.$item['quantity'].'">
                            <input class="normal-checkbox" type="checkbox" checked>
                            <img class="product-image" src="'.$item['image'].'" alt="Карточка товара">

                            <div class="product-info">
                                <p class="product-sku">'.$item['sku'].'</p>
                                <p class="product-name">'.$item['name'].'</p>
                            </div>

                            <div class="large-hide phone-break"></div>

                            <div class="product-pay-info">

                                <div>
                                    <p class="product-final-price">'.$price.' ₽</p>
                                    <p class="product-price">'.$price.' ₽</p>
                                </div>

                                <div>
                                    <div class="product-control">
                                        <div class="quantity-wrap">
                                            <button class="minus">
                                                <svg class="icon" viewBox="0 0 12 2" width="12" height="2">
                                                    <path d="M10.6667 0C11.0349 0 11.3333 0.298477 11.3333 0.666667C11.3333 1.03486 11.0349 1.33333 10.6667 1.33333H0.666667C0.298477 1.33333 0 1.03486 0 0.666667C0 0.298477 0.298477 0 0.666667 0H10.6667Z" fill="currentColor"/>
                                                </svg>
                                            </button>
                                            <input class="product-quantity" type="text" value="1" readonly>
                                            <button class="plus">
                                                <svg class="icon" viewBox="0 0 12 12" width="12" height="12">
                                                    <path d="M5.0013 10.6667V6.33333H0.666667C0.298477 6.33333 0 6.03486 0 5.66667C4.40632e-05 5.29851 0.298504 5 0.666667 5H5.0013V0.666667C5.0013 0.298477 5.29978 3.43602e-07 5.66797 0C6.03616 0 6.33464 0.298477 6.33464 0.666667V5H10.6667C11.0348 5 11.3333 5.29851 11.3333 5.66667C11.3333 6.03486 11.0349 6.33333 10.6667 6.33333H6.33464V10.6667C6.33464 11.0349 6.03616 11.3333 5.66797 11.3333C5.29978 11.3333 5.0013 11.0349 5.0013 10.6667Z" fill="currentColor"/>
                                                </svg>
                                            </button>
                                        </div>

                                        <button class="product-delete"><img src="img/garbage.svg" alt="Удалить из корзины"></button>
                                    </div>
                                    <p class="product-price-for-one">'.$price.' ₽</p> 
                                </div>

                            </div>
                        </div>';
                    }
                ?>
            </div>

            <div class="payment">
                <div class="pay-card">
                    <p class="pay-card-title">Ваша корзина:</p>

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

                    <button class="pay-button">
                        К оформлению 
                        <img src="img/next.svg" alt="Далее">
                    </button>
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
                        <input class="promo-input" type="text" placeholder="Введите промокод">
                        <svg id="clear-promo" class="icon" viewBox="0 0 16 16" width="16" height="16">
                            <path d="M14.2929 0.29289C14.6834 -0.0973697 15.3165 -0.097541 15.707 0.29289C16.0974 0.683326 16.0972 1.31641 15.707 1.70695L9.41398 7.99992L15.707 14.2929C16.0973 14.6834 16.0974 15.3165 15.707 15.707C15.3165 16.0973 14.6834 16.0973 14.2929 15.707L7.99992 9.41398L1.70695 15.707C1.31642 16.0973 0.683368 16.0974 0.29289 15.707C-0.0974445 15.3165 -0.0974462 14.6834 0.29289 14.2929L6.58586 7.99992L0.29289 1.70695C-0.0976315 1.31643 -0.0976283 0.683416 0.29289 0.29289C0.683411 -0.0976315 1.31643 -0.0976283 1.70695 0.29289L7.99992 6.58586L14.2929 0.29289Z" fill="currentColor"/>
                        </svg>
                    </div>
                    <button class="promo-button">Применить</button>
                </div>
            </div>
        </div>
    </div>

    </div>

    <div class="grower"></div>

    <footer>
        <div class="container">
            <div class="footer-content">
                <p>@ Company name 2026</p>
                <img class="logo" src="img/footer-logo.png" alt="ГРОТЕСК">
            </div>
        </div>
    </footer>

    <script>
        // Старался писать сам, но когда дошел до чекбоксов - что то подустал, поэтому именно на них уже использовал нейронку
        // Я знаю что const и let расставлены не везде супер логично, но я просто тороплюсь сделать работу
        function updateSelected() {
            let count = 0;
            let price = 0;
            let discount = parseInt($('.promo-wrap').data('promo'));
            let totalWithDiscount = 0;

            $('.product-card').each(function () {
                if ($(this).find('.normal-checkbox').prop('checked')) {
                    let quantity = parseInt($(this).find('.product-quantity').val());
                    let itemPrice = parseInt($(this).data('price'));
                    count += quantity;
                    price += itemPrice * quantity;
                }
            });

            let priseNoDiscount = price;

            if (discount == 1) {
                price = price * 0.8;
            }

            let discountAmount = priseNoDiscount - price;

            let formatePrice = price.toLocaleString('ru-RU');
            discountAmount = discountAmount.toLocaleString('ru-RU');
            priseNoDiscount = priseNoDiscount.toLocaleString('ru-RU');
            $('#total-count').text(count + " товаров:");
            $('#total-price').text(priseNoDiscount + " ₽");
            $('#discount-price').text(discountAmount + " ₽");
            $('#total-final-price').text(formatePrice + " ₽");
            $('.pay-button').prop('disabled', count === 0);
        }

        // Функция обновления корзины, цен и всего такого, делается после нажатия по кнопочкам - +
        function updateCart($card, $quantity, delta) {
            const $allItemsPrice = $('.cart-button');
            const $allItemsQuantity = $('.cart-button-count');

            let quantity = parseInt($quantity.val());
            let price = parseInt($card.data('price'));
            let allQuantity = parseInt($allItemsQuantity.text());
            let allPrice = parseInt($allItemsPrice.data('price-count'));
            let discount = parseInt($('.promo-wrap').data('promo'));

            let newQuantityValue = quantity + delta;
            $quantity.val(newQuantityValue);
            let total = newQuantityValue * price;
            let totalWithDiscount = 0;
            if (discount == 1) {
                totalWithDiscount = total * 0.8;
            } else {
                totalWithDiscount = total;
            }
            total = total.toLocaleString('ru-RU');
            let newQuantity = allQuantity + delta;
            let newAllPrice = allPrice + delta * price;
            let formateAllPrice = newAllPrice.toLocaleString('ru-RU');

            $card.find('.product-final-price').text(totalWithDiscount + " ₽");
            $card.find('.product-price').text(total + " ₽");
            $('.cart-button-count').text(newQuantity);
            $('.cart-count').text(newQuantity + " шт.");
            $('.cart-button-price').text(formateAllPrice + " ₽");
            $('.cart-button').data('price-count', newAllPrice);
            updateSelected();
        }

        $('.minus').on('click', function () {
            const $card = $(this).closest('.product-card');
            let $quantity = $(this).siblings('.product-quantity');

            let quantity = parseInt($quantity.val());

            if (quantity > 1) {
                updateCart($card, $quantity, -1);
            } else {
                removeFromCart($card);
            }
        });

        $('.plus').on('click', function () {
            const $card = $(this).closest('.product-card');
            let $quantity = $(this).siblings('.product-quantity');

            let quantity = parseInt($quantity.val());
            let max = parseInt($card.data('quantity'));

            if (quantity < max) {
                updateCart($card, $quantity, 1);
            } else {
                alert("Это максимальное количество для заказа")
            }
        });

        // Удалить выбранное из корзины
        $('#delete-all').on('click', function () {
            const $cards = $('.product-card');
            const $checkedCards = $cards.filter(function () {
                return $(this).find('.normal-checkbox').prop('checked');
            });

            if ($checkedCards.length === 0) {
                if (!confirm("Ни один товар не выбран. Удалить все товары из корзины?")) {
                    return;
                }
                $cards.remove();
                updateSelected();
                return;
            }

            if (!confirm(`Удалить выбранные товары из корзины?`)) {
                return;
            }

            $checkedCards.remove();

            updateSelected();
        });

        // Удалить что то отдельное из корзины
        function removeFromCart($card) {
            if (!confirm("Удалить этот товар из корзины?")) {
                return; 
            }

            let $quantity = $card.find('.product-quantity');
            let $allItemsQuantity = $('.cart-button-count');
            const $allItemsPrice = $('.cart-button');

            let quantity = parseInt($quantity.val());
            let allQuantity = parseInt($allItemsQuantity.text());
            let allPrice = parseInt($allItemsPrice.data('price-count'));
            let price = parseInt($card.data('price'));

            let newQuantity = allQuantity - quantity;
            let newAllPrice = allPrice - price * quantity;
            let formateAllPrice = newAllPrice.toLocaleString('ru-RU');

            $('.cart-button-count').text(newQuantity);
            $('.cart-count').text(newQuantity + " шт.");
            $('.cart-button-price').text(formateAllPrice + " ₽");
            $('.cart-button').data('price-count', newAllPrice);

            $card.remove();
            updateSelected();
        }

        $('.product-delete').on('click', function () {
            const $card = $(this).closest('.product-card');
            removeFromCart($card);
        });

        $('#select-all').on('change', function () {
            let checked = $(this).prop('checked');
            $('.product-card .normal-checkbox').prop('checked', checked);
            updateSelected();
        });

        $('.product-card .normal-checkbox').on('change', function () {
            let all = $('.product-card .normal-checkbox').length;
            let selected = $('.product-card .normal-checkbox:checked').length;
            $('#select-all').prop('checked', all === selected);
            updateSelected();
        });

        // Обработка клика по кнопке оплаты и переход далее
        $('.pay-button').on('click', function () {
            const $cards = $('.product-card');
            const allPrice = $('.cart-button-price').text();
            const allCount = $('.cart-button-count').text();
            const promoText = $('.promo-input').val();

            const items = $cards.map(function () {
                const $card = $(this);
                let $quantity = $card.find('.product-quantity');
                let quantity = parseInt($quantity.val());
                let checked = $card.find('.normal-checkbox').prop('checked');
                return {
                    id: $card.data('id'),
                    quantity: quantity,
                    checked: checked || 0,
                    allPrice: allPrice || 0,
                    allCount: allCount || 0,
                    promoText: promoText || ''
                };
            }).get();

            const json = JSON.stringify(items);   
            const promo = $('.promo-wrap').data('promo'); 

            $('<form>', {
                action: '/checkout/',
                method: 'POST',
                css: { display: 'none' }
            })
            .append($('<input>', { type: 'hidden', name: 'items', value: json }))
            .append($('<input>', { type: 'hidden', name: 'promo', value: promo }))
            .append($('<input>', { type: 'hidden', name: 'promoText', value: promoText }))
            .append($('<input>', { type: 'hidden', name: 'allPrice', value: allPrice }))
            .append($('<input>', { type: 'hidden', name: 'allCount', value: allCount }))
            .appendTo('body')
            .trigger('submit');
        });

        // Открыть блок с промо
        $('.discount-line').on('click', function () {
            $('.promo').toggleClass('visible');
            $('.arrow').toggleClass('rotate');
        });

        // Очистить поле промокода
        $('#clear-promo').on('click', function () {
            $('.promo-input').val('');
        });

        // Применение промо, очень лень было делать проверку через php, но я сделал. Скидка - 20%
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
                        $('.product-final-price').addClass('working-promo');

                        const $cards = $('.product-card');
                        let totalDiscount = 0;
                        let finalPrice = 0;
                        $cards.map(function () {
                            const $card = $(this);

                            if (!$card.find('.normal-checkbox').prop('checked')) {
                                return;
                            }

                            const $quantity = $card.find('.product-quantity');
                            const quantity = parseInt($quantity.val());
                            const price = parseInt($card.data('price'));

                            const priceNew = price * quantity  * 0.8;
                            const discount = price * quantity - priceNew;
                            totalDiscount += discount;
                            finalPrice += priceNew;

                            $card.find('.product-final-price').text(priceNew + " ₽");
                        });

                        totalDiscount = totalDiscount.toLocaleString('ru-RU');
                        finalPrice = finalPrice.toLocaleString('ru-RU');
                        $('#discount-price').text(totalDiscount + " ₽");
                        $('#total-final-price').text(finalPrice + " ₽");
                        $('.promo-wrap').data('promo', 1);
                    } else {
                        $('#promo-alert').addClass('bad').text("Неправильный промокод");
                    }
                },
                error: function () {
                    $('#promo-alert').addClass('bad').text('Ошибка сервера');
                }
            });
        });
    </script>

</body>
</html>