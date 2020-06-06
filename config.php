<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/activerecord/ActiveRecord.php';



ActiveRecord\Config::initialize(function($cfg)
{
    $cfg->set_model_directory($_SERVER['DOCUMENT_ROOT']. '/activerecord/models');
    $cfg->set_connections(array('development' => 'mysql://ScamHub_scamhub:ScamHub_scamhub@localhost/ScamHub_scamhub;charset=utf8'));

    // you can change the default connection with the below
    //$cfg->set_default_connection('production');
});



/**
 * токен бота
 */
$bot_token  = Configs::find(13);

$bot_token = $bot_token->979846394:AAEquRiJyFzxqHflC70WaSffyzBAKfJw4VY;
/**
 * Админ конфа
 */
$admin_chat_id  = Configs::find(14);
$admin_chat_id = $admin_chat_id->value;
/**
 * воркер конфа
 */
$worcer_chat_id  = Configs::find(15);
$worcer_chat_id = $worcer_chat_id->value;
/**
 * канал залетов
 */
$zalet_chat_id = Configs::find(19);
$zalet_chat_id = $zalet_chat_id->value;
/**
 * канал товарки
 */
$tovarka  = Configs::find(16);
$tovarka = $tovarka->value;
/**
 * ссылка на канал залетов
 */
$zalet_links  = Configs::find(20);
$zalet_links = $zalet_links->value;
/**
 * ссылка на чат воркеров
 */
$worker_links  = Configs::find(21);
$worker_links = $worker_links->value;
/**
 * ссылка на канал товарки
 */
$tovarka_links  = Configs::find(26);
$tovarka_links = $tovarka_links->value;
/**
 * название команды
 */
$team  = Configs::find(22);
$team = $team->value;
/**
 * процент выплат
 */
$viplaty  = Configs::find(23);
$viplaty = $viplaty->value;
/**
 * процент возврата
 */
$vozvrat  = Configs::find(24);
$vozvrat = $vozvrat->value;
/**
 * ник администратора
 */
$admin_tg  = Configs::find(25);
$admin_tg = $admin_tg->value;


$gmailpass= Configs::find(5);
$gmaillogin= Configs::find(6);
$smtplogin = $gmaillogin->value;
$smtppass = $gmailpass->value;
$smtphost = "smtp.gmail.com";
$smtpport = 587;

$smslogin  = Configs::find(17);
$smslogin = $smslogin->value;
$smspass  = Configs::find(18);
$smspass = $smspass->value;


$config= Configs::find(4);
  $px = explode(':',$config->value);  
$proxy = $px[0].':'.$px[1]; // IP:PORT
$pass = $px[2].':'.$px[3]; // USER:PASS


$domain_avito = Configs::find(7);
$domain_avito = $domain_avito->value;
$domain_youla  = Configs::find(8);
$domain_youla = $domain_youla->value;
$domain_boxberry  = Configs::find(9);
$domain_boxberry = $domain_boxberry->value;
$domain_pek  = Configs::find(10);
$domain_pek = $domain_pek->value;
$domain_cdek  = Configs::find(11);
$domain_cdek = $domain_cdek->value;
$domain_pochtarf  = Configs::find(12);
$domain_pochtarf = $domain_pochtarf->value;
?>