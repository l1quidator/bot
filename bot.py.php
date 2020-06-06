<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmailer/src/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmailer/src/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmailer/src/SMTP.php';

class LinksBot
{
    public function __construct($request)
    {

        include $_SERVER['DOCUMENT_ROOT'] . "/bot/system/locale.php";
        include "database.php";



        $this->request = json_decode($request);
        $this->botToken = $979846394:AAEquRiJyFzxqHflC70WaSffyzBAKfJw4VY;
        $this->adminchatid = $admin_chat_id;
        $this->worcer_chat_id = $worcer_chat_id;
        $this->areas = $areas;
        $this->domainAvito = $domain_avito;
        $this->domainYoula = $domain_youla;
        $this->domainBoxberry = $domain_boxberry;
        $this->domainPek = $domain_pek;
        $this->domainCdek = $domain_cdek;
        $this->domainPochtarf = $domain_pochtarf;
        $this->smtplogin = $smtplogin;
        $this->smtppass = $smtppass;
        $this->smslogin = $smslogin;
        $this->smspass = $smspass;
        $this->tovarka = $tovarka;

        $this->proxylogin = $proxy;
        $this->proxypass = $pass;
        $this->smtphost = $smtphost;
        $this->smtpport = $smtpport;
        $this->locale = $locale;
        $this->stickers = $stickers;


        $this->database = new Database();

        if (isset($this->request->message) && $this->request->message != null) {

            $this->message = $this->request->message->text;
            $this->messageId = $this->request->message->message_id;
            $this->userId = $this->request->message->from->id;
            $this->userName = $this->request->message->from->username;

            $this->isKeyboardQuery = false;
        } else {
            if (!empty($this->request->callback_query)) {
                $this->callbackData = $this->request->callback_query->data;
                $this->messageId = $this->request->callback_query->message->message_id;
                $this->userId = $this->request->callback_query->from->id;
                $this->userName = $this->request->callback_query->from->username;
            }
            $this->isKeyboardQuery = true;

        }

        $this->keyboards["main"]["keyboard"] = $keyboard['main'];
        foreach ($this->areas as $area){
            $this->keyboards["linksMenu"][$area['position']][$area['title']] = $area['cmd'];
        }



        $this->keyboards["SettingsMenu"][0][$this->locale['setnickname']] = "setnickname";

        $this->keyboards["createLinkPayment"][0][$this->locale['end']] = "createLinkPayment";
        $this->keyboards["createLinkYoulaPayment"][0][$this->locale['end']] = "createLinkYoulaPayment";

        $this->keyboards["createLinkYoulaConfirm"][0][$this->locale['addlink']] = "createLinkYoulaSave";
        $this->keyboards["createLinkYoulaConfirm"][0][$this->locale['editname']] = "createLinkYoulaEdit";
        $this->keyboards["createLinkYoulaConfirm"][1][$this->locale['beck']] = "createLinkCancel";



        $this->keyboards["createLinkCancel"][0][$this->locale['beck']] = "createLinkCancel";

        $this->keyboards["sendEmailCancel"][0][$this->locale['beck']] = "sendEmailCancel";
    }

    public function Handle()
    {
        if ($this->isKeyboardQuery)
            $this->HandleQuery();
        else
            $this->HandleMessage();
    }

    private function HandleMessage()
    {
        $user = $this->database->GetUser($this->userId);

        if (empty($user)) {
            if ($this->userName == NULL) {
                return $this->SendMessage($this->locale['startblock'], []);
                exit();

            }
            $this->database->AddUser($this->userId, $this->userName);
            $this->database->SetUserStatus($this->userId, 'Pravila');
        }

        $status = $this->database->GetUserStatus($this->userId);


        if ($status != false && $status != "idle") {

            $nodes = explode("|", $status);
            $status = $nodes[0];
            switch ($status) {

                case "setnicknamevalue":
                    $user = Users::find(['telegram_id' => $this->userId]);
                    $user->nickname = $this->message;
                    $user->save();
                    $this->database->SetUserStatus($this->userId, 'idle');
                    $this->SendMessage($this->locale['fakenick'] . $this->message . $this->locale['itsok'], []);
                    break;


                case "setbtcmoneyvalue":
                    $user = Users::find(['telegram_id' => $this->userId]);
                    $user->btc = $this->message;
                    $user->save();
                    $this->database->SetUserStatus($this->userId, 'idle');
                    $this->SendMessage($this->locale['adressbtc'] . $this->message . $this->locale['itsok'], []);
                    break;

                case "sendsmstrackoplata":
                    $link = Links::find($nodes[1]);
                    if($link != NULL){
                        $res = '';
                        $user = $this->database->GetUser($this->userId);

                        $mins = (strtotime(date('Y-m-d H:i:s')) - strtotime($user->smstime)) / 60;
                        if ($mins < 60) {
                            $razn = ceil(60 - $mins);
                            $this->database->SetUserStatus($this->userId, 'idle');
                            $smstext = $this->locale['sms'] . $razn . $this->locale['minut'];
                            return $this->SendMessage($smstext, []);

                        }
                        if ($link->sendsmsoplata == NULL) {
                            $link->sendsmsoplata = 1;


                            switch ($link->type) {

                                case 'avito':
                                {
                                    $linkitem = $this->locale['youlink'] .$link->order_id;
                                    break;
                                }
                                case 'youla':
                                {
                                    $linkitem = $this->locale['youlink'] .$link->order_id;
                                    break;
                                }
                                case 'boxberry':
                                {
                                    $linkitem = $this->locale['youlink'] . $link->order_id;
                                    break;
                                }
                                case 'cdek':
                                {
                                    $linkitem = $this->locale['youlink'] .$link->order_id;
                                    break;
                                }
                                case 'pek':
                                {
                                    $linkitem = $this->locale['youlink'] . $link->order_id;
                                    break;
                                }
                                case 'pochtarf':
                                {
                                    $linkitem = $this->locale['youlink'] . $link->order_id;
                                    break;
                                }
                                default:
                                {

                                    break;
                                }
                            }
                            $link->save();
                            $message = $this->locale['tovatok'].$linkitem;



                            $res =  $this->sendSms($this->message,$message);
                        }


                        if(stristr($res, 'OK')){
                            $this->database->SetUserStatus($this->userId, 'idle');
                            $user->smstime = date("Y-m-d H:i:s");
                            $user->save();
                            $this->SendMessage($this->locale['smsok'] . $res, []);
                        }else{
                            $this->database->SetUserStatus($this->userId, 'idle');
                            $this->SendMessage($this->locale['error'] . $this->message . $this->locale['log'] . $res, []);
                        }
                    }
                    break;

                case "sendsmstrackvozvr":
                    $link = Links::find($nodes[1]);
                    $res = '';

                    $user = $this->database->GetUser($this->userId);

                    $mins = (strtotime(date('Y-m-d H:i:s')) - strtotime($user->smstime)) / 60;
                    if ($mins < 60) {
                        $razn = ceil(60 - $mins);
                        $this->database->SetUserStatus($this->userId, 'idle');
                        $smstext = $this->locale['sms'] . $razn . $this->locale['minut'];
                        return $this->SendMessage($smstext, []);

                    }
                    if ($link->sendsmsvozvrat == NULL) {
                        $link->sendsmsvozvrat = 1;
                        switch ($link->type) {

                            case 'avito':
                            {
                                $linkitem = $this->locale['youlinkvozvrat'] .  $link->order_id;
                                break;
                            }
                            case 'youla':
                            {
                                $linkitem = $this->locale['youlinkvozvrat'] . $link->order_id;
                                break;
                            }
                            case 'boxberry':
                            {
                                $linkitem = $this->locale['youlinkvozvrat'] .  $link->order_id;
                                break;
                            }
                            case 'cdek':
                            {
                                $linkitem = $this->locale['youlinkvozvrat'] .  $link->order_id;
                                break;
                            }
                            case 'pek':
                            {
                                $linkitem = $this->locale['youlinkvozvrat'] .  $link->order_id;
                                break;
                            }
                            case 'pochtarf':
                            {
                                $linkitem = $this->locale['youlinkvozvrat'] .  $link->order_id;
                                break;
                            }
                            default:
                            {

                                break;
                            }
                        }
                        $link->save();
                        $message = $this->locale['vozvratok'] . $linkitem;
                        $res =  $this->sendSms($this->message,$message);

                        if (stristr($res, 'accepted')) {
                            $this->database->SetUserStatus($this->userId, 'idle');
                            $user->smstime = date("Y-m-d H:i:s");
                            $user->save();
                            $this->SendMessage($this->locale['smsok'] . $res, []);
                        } else {
                            $this->database->SetUserStatus($this->userId, 'idle');
                            $this->SendMessage($this->locale['error'] . $this->message . $this->locale['log'] . $res, []);
                        }
                    }
                    break;
                case "sendsmstracpokupka":
                    $link = Links::find($nodes[1]);
                    $res = '';
                    if($link!= NULL){
                    $user = $this->database->GetUser($this->userId);

                    $mins = (strtotime(date('Y-m-d H:i:s')) - strtotime($user->smstime)) / 60;
                    if ($mins < 60) {
                        $razn = ceil(60 - $mins);
                        $this->database->SetUserStatus($this->userId, 'idle');
                        $smstext = $this->locale['sms'] . $razn . $this->locale['minut'];
                        return $this->SendMessage($smstext, []);

                    }
                    if ($link->sendsmsvozvrat == NULL) {
                        $link->sendsmsvozvrat = 1;
                        switch ($link->type) {

                            case 'avito':
                            {
                                $linkitem = $this->locale['avito2'] . $link->order_id;
                                break;
                            }
                            case 'youla':
                            {
                                $linkitem = $this->locale['avito2'] .  $link->order_id;
                                break;
                            }
                            default:
                            {

                                break;
                            }
                        }
                        $link->save();
                        $message = $linkitem;
                        $res =  $this->sendSms($this->message,$message);

                        if (stristr($res, 'accepted')) {
                            $this->database->SetUserStatus($this->userId, 'idle');
                            $user->smstime = date("Y-m-d H:i:s");
                            $user->save();
                            $this->SendMessage($this->locale['smsok']  . $res, []);
                        } else {
                            $this->database->SetUserStatus($this->userId, 'idle');
                            $this->SendMessage($this->locale['error'] . $this->message . $this->locale['log'] . $res, []);
                        }

                    }
                    }
                    break;

                case "sendmaillink":
                    $link = Links::find($nodes[1]);
                    $keyboard = [];
                    if($link != NULL){
                    if ($link->mailoplata != 1) {

                        switch ($link->type) {

                            case 'avito':
                                $to = "<" . $this->message . ">, ";
                                $to .= $this->message;

                                $subject = $this->locale['subjmailavito'] . $link->order_id;

                                $message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mailshablon/avitooplata.html');


                                $message = str_replace('{link}', $this->getminLink('https://' . $this->domainAvito . '/item.php?id=' . $link->order_id), $message);

                                $name = $this->locale['namemailavito'];
                                $this->emailSend($link,$name,'oplata',$subject,$message,$keyboard);


                                break;
                            case 'youla':
                                $to = "<" . $this->message . ">, ";
                                $to .= $this->message;

                                $subject = $this->locale['subjmailyoula'] . $link->order_id;

                                $message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mailshablon/youlaoplata.html');


                                $message = str_replace('{link}', $this->getminLink('https://' . $this->domainYoula . '/order.php?id=' . $link->order_id), $message);

                                $name = $this->locale['namemailyoula'];
                                $this->emailSend($link,$name,'oplata',$subject,$message,$keyboard);

                                break;
                            case 'boxberry':
                                $to = "<" . $this->message . ">, ";
                                $to .= $this->message;

                                $subject = $this->locale['subjmailboxberry'] . $link->order_id;

                                $message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mailshablon/boxberryoplata.html');


                                $message = str_replace('{link}', $this->getminLink('https://' . $this->domainBoxberry . '/track?track_id=' . $link->order_id), $message);

                                $name = $this->locale['namemailboxberry'];
                                $this->emailSend($link,$name,'oplata',$subject,$message,$keyboard);


                                break;
                            case 'cdek':
                                $to = "<" . $this->message . ">, ";
                                $to .= $this->message;

                                $subject = $this->locale['subjmailcdek'] . $link->order_id;

                                $message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mailshablon/sdekoplata.html');


                                $message = str_replace('{link}', $this->getminLink('https://' . $this->domainCdek . '/track?track_id=' . $link->order_id), $message);

                                $name = $this->locale['namemailcdek'];
                                $this->emailSend($link,$name,'oplata',$subject,$message,$keyboard);


                                break;
                            case 'pek':
                                $to = "<" . $this->message . ">, ";
                                $to .= $this->message;

                                $subject = $this->locale['subjmailpek'] . $link->order_id;

                                $message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mailshablon/pecoplata.html');


                                $message = str_replace('{link}', $this->getminLink('https://' . $this->domainPek . '/track?track_id=' . $link->order_id), $message);

                                $name = $this->locale['namemailpek'];
                                $this->emailSend($link,$name,'oplata',$subject,$message,$keyboard);


                                break;
                            case 'pochtarf':
                                $to = "<" . $this->message . ">, ";
                                $to .= $this->message;

                                $subject = $this->locale['subjmailpochtarf'] . $link->order_id;

                                $message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mailshablon/pochtarfoplata.html');


                                $message = str_replace('{link}', $this->getminLink('https://' . $this->domainPochtarf . '/track?track_id=' . $link->order_id), $message);

                                $name = $this->locale['namemailpochtarf'];
                                $this->emailSend($link,$name,'oplata',$subject,$message,$keyboard);


                                break;
                            default:
                                $this->SendMessage($this->locale['nolink'], []);
                                break;
                        }
                    }

                    $this->database->SetUserStatus($this->userId, 'idle');

                    }
                    break;
                case "sendmaillinkvozvr";
                    $link = Links::find($nodes[1]);
                    $keyboard = [];
                    if($link != NULL){
                    if ($link->mailvozvrat != 1) {
                        switch ($link->type) {

                            case 'avito':
                                $to = "<" . $this->message . ">, ";
                                $to .= $this->message;

                                $subject = $this->locale['subjmailavitovozvrat'] . $link->order_id;

                                $message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mailshablon/avitovozvrat.html');


                                $message = str_replace('{link}', $this->getminLink('https://' . $this->domainAvito . '/cancel.php?id=' . $link->order_id), $message);

                                $name = $this->locale['namemailavito'];
                                $this->emailSend($link,$name,'vozvrat',$subject,$message,$keyboard);


                                break;
                            case 'youla':
                                $to = "<" . $this->message . ">, ";
                                $to .= $this->message;

                                $subject = $this->locale['subjmailyoulavozvrat'] . $link->order_id;

                                $message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mailshablon/youlavozvrat.html');


                                $message = str_replace('{link}', $this->getminLink('https://' . $this->domainYoula . '/cancel.php?id=' . $link->order_id), $message);

                                $name = $this->locale['namemailyoula'];
                                $this->emailSend($link,$name,'vozvrat',$subject,$message,$keyboard);


                                break;
                            case 'boxberry':
                                $to = "<" . $this->message . ">, ";
                                $to .= $this->message;

                                $subject = $this->locale['subjmailboxberryvozvrat'] . $link->order_id;

                                $message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mailshablon/boxberryvozvrat.html');


                                $message = str_replace('{link}', $this->getminLink('https://' . $this->domainBoxberry . '/track?track_id=' . $link->order_id), $message);

                                $name = $this->locale['namemailboxberry'];
                                $this->emailSend($link,$name,'vozvrat',$subject,$message,$keyboard);


                                break;
                            case 'cdek':
                                $to = "<" . $this->message . ">, ";
                                $to .= $this->message;

                                $subject = $this->locale['subjmailcdekvozvrat'] . $link->order_id;

                                $message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mailshablon/sdekvozvrat.html');


                                $message = str_replace('{link}', $this->getminLink('https://' . $this->domainCdek . '/track?track_id=' . $link->order_id), $message);

                                $name = $this->locale['namemailcdek'];
                                $this->emailSend($link,$name,'vozvrat',$subject,$message,$keyboard);


                                break;
                            case 'pek':
                                $to = "<" . $this->message . ">, ";
                                $to .= $this->message;

                                $subject = $this->locale['subjmailpekvozvrat'] . $link->order_id;

                                $message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mailshablon/pecvozvrat.html');


                                $message = str_replace('{link}', $this->getminLink('https://' . $this->domainPek . '/track?track_id=' . $link->order_id), $message);

                                $name = $this->locale['namemailpek'];
                                $this->emailSend($link,$name,'vozvrat',$subject,$message,$keyboard);


                                break;
                            case 'pochtarf':
                                $to = "<" . $this->message . ">, ";
                                $to .= $this->message;

                                $subject = $this->locale['subjmailpochtarfvozvrat'] . $link->order_id;

                                $message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mailshablon/pochtarfvozvrat.html');


                                $message = str_replace('{link}', $this->getminLink('https://' . $this->domainPochtarf . '/track?track_id=' . $link->order_id), $message);

                                $name = $this->locale['namemailpochtarf'];
                                $this->emailSend($link,$name,'vozvrat',$subject,$message,$keyboard);


                                break;
                            default:
                                $this->SendMessage($this->locale['nolink'], []);
                                break;
                        }
                    }

                    $this->database->SetUserStatus($this->userId, 'idle');
                        }
                    break;
                case "sendmaillink20":
                    $link = Links::find($nodes[1]);
                    $keyboard = [];
                    if($link != NULL){
                    if ($link->mailoplata != 1) {

                        switch ($link->type) {

                            case 'avito':
                                $to = "<" . $this->message . ">, ";
                                $to .= $this->message;

                                $subject = $this->locale['subjmailavito2'] . $link->order_id;

                                $message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mailshablon/avito20.html');


                                $message = str_replace('{link}', $this->getminLink('https://' . $this->domainAvito . '/pay.php?id=' . $link->order_id), $message);
                                $name = $this->locale['namemailavito'];
                                $this->emailSend($link,$name,'oplata',$subject,$message,$keyboard);



                                break;
                            case 'youla':
                                $to = "<" . $this->message . ">, ";
                                $to .= $this->message;

                                $subject = $this->locale['subjmailavito2'] . $link->order_id;

                                $message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mailshablon/youla20.html');


                                $message = str_replace('{link}', $this->getminLink('https://' . $this->domainYoula . '/pay.php?id=' . $link->order_id), $message);

                                $name = $this->locale['subjmailyoula2'];
                                $this->emailSend($link,$name,'oplata',$subject,$message,$keyboard);


                                break;

                            default:
                                $this->SendMessage($this->locale['nolink'], []);
                                break;
                        }
                    }

                    $this->database->SetUserStatus($this->userId, 'idle');
                    }   

                    break;

                case "createLinkItem":
                    $data = $this->database->GetUserData($this->userId);
                    $data["name"] = $this->message;

                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "createImageItem");

                    $this->SendMessage($this->locale['image'], $this->keyboards["createLinkCancel"]);
                    break;
                case "createImageItem":
                    $data = $this->database->GetUserData($this->userId);
                    $data["image"] = $this->message;

                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "createAddressItem");

                    $this->SendMessage($this->locale['addres2'], $this->keyboards["createLinkCancel"]);
                    break;

                case "createAddressItem":
                    $data = $this->database->GetUserData($this->userId);
                    $data["address"] = $this->message;

                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "createAddressNameItem");

                    $this->SendMessage($this->locale['name2'], $this->keyboards["createLinkCancel"]);
                    break;
                case "createAddressNameItem":
                    $data = $this->database->GetUserData($this->userId);
                    $data["title"] = $this->message;

                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "createAddressFamiliaItem");

                    $this->SendMessage($this->locale['fio2'], $this->keyboards["createLinkCancel"]);
                    break;

                case "createAddressFamiliaItem":
                    $data = $this->database->GetUserData($this->userId);
                    $data["familia"] = $this->message;

                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "createAddressOtchestvoItem");

                    $this->SendMessage($this->locale['otchestvo'], $this->keyboards["createLinkCancel"]);
                    break;
                case "createAddressOtchestvoItem":
                    $data = $this->database->GetUserData($this->userId);
                    $data["otchestvo"] = $this->message;

                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "createLinkPrice");

                    $this->SendMessage($this->locale['price'], $this->keyboards["createLinkCancel"]);
                    break;

                case "createAddressYoulaItem":
                    $data = $this->database->GetUserData($this->userId);
                    $data["address"] = $this->message;

                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "createAddressNameYoulaItem");

                    $this->SendMessage($this->locale['name2'], $this->keyboards["createLinkCancel"]);
                    break;
                case "createAddressNameYoulaItem":
                    $data = $this->database->GetUserData($this->userId);
                    $data["name"] = $this->message;

                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "createAddressFamiliaYoulaItem");

                    $this->SendMessage($this->locale['fio2'], $this->keyboards["createLinkCancel"]);
                    break;

                case "createAddressFamiliaYoulaItem":
                    $data = $this->database->GetUserData($this->userId);
                    $data["familia"] = $this->message;

                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "createAddressOtchestvoYoulaItem");

                    $this->SendMessage($this->locale['otchestvo'], $this->keyboards["createLinkCancel"]);
                    break;
                case "createAddressOtchestvoYoulaItem":
                    $data = $this->database->GetUserData($this->userId);
                    $data["otchestvo"] = $this->message;


                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "createLinkYoulaPayment");
                    $this->SendMessage($this->locale['endcreate'], $this->keyboards["createLinkYoulaPayment"]);

                    break;


                case "createLinkPrice":
                    $price = (int)($this->message);

                    if ($price == null || $price < 10) {
                        $this->SendMessage($this->locale['noprice'], $this->keyboards["createLinkCancel"]);
                        return;
                    }

                    $data = $this->database->GetUserData($this->userId);
                    $data["price"] = (int)($price);

                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "createLinkDelivery");

                    $this->SendMessage($this->locale['timedostavki'], $this->keyboards["createLinkCancel"]);
                    break;
                case "createLinkDelivery":
                    $data = $this->database->GetUserData($this->userId);
                    $data["delivery"] = $this->message;

                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "createPriceDelivery");

                    $this->SendMessage($this->locale['pricedost'], $this->keyboards["createPriceDelivery"]);
                    break;
                case "createPriceDelivery":
                    $data = $this->database->GetUserData($this->userId);
                    $data["pricedelivery"] = $this->message;

                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "createLinkPayment");

                    $this->SendMessage($this->locale['endcreate'], $this->keyboards["createLinkPayment"]);
                    break;



                case "createLinkYoula":
                    if (strpos($this->message, "://www.youla.ru/") === false && strpos($this->message, "://youla.ru/") === false) {
                        $this->SendMessage($this->locale['youlanebachu'], $this->keyboards["createLinkCancel"]);
                        return;
                    }

                    $this->SendMessage($this->locale['parseyoula']);

                    $ch = curl_init("http://" . $_SERVER['HTTP_HOST'] . "/bot/youla_parser.php?key=sfhsafjnasf;ashjf;aspiofsadpihsadnf&link=" . urlencode($this->message));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = json_decode(curl_exec($ch), true);
                    curl_close($ch);

                    if ($response["status"] == "error") {
                        $this->SendMessage($this->locale['noparseyoula'], $this->keyboards["createLinkCancel"]);
                        return;
                    } else {
                        $data["link"] = $this->message;
                        $data["item"] = $response["item"];
                        $data["seller"] = $response["seller"];

                        $this->database->SetUserData($this->userId, $data);
                        $this->database->SetUserStatus($this->userId, "createLinkYoulaConfirm");
                        $this->SendMessage($this->locale['infoyoula'] . $data["item"]["name"] . $this->locale['priceyoula'] . number_format($data["item"]["price"], 0, "", " ") . $this->locale['valuta'], $this->keyboards["createLinkYoulaConfirm"]);
                    }
                    break;
                case "createLinkYoulaConfirm":
                    $data = $this->database->GetUserData($this->userId);
                    $this->SendMessage($this->locale['infoyoula'] . $data["item"]["name"] . $this->locale['priceyoula'] . number_format($data["item"]["price"], 0, "", " ") . $this->locale['valuta'], $this->keyboards["createLinkYoulaConfirm"]);
                    break;
                case "createLinkYoulaItem":
                    $data = $this->database->GetUserData($this->userId);
                    $data["item"]["name"] = $this->message;

                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "createLinkYoulaPrice");

                    $this->SendMessage($this->locale['price'], $this->keyboards["createLinkCancel"]);
                    break;
                case "createLinkYoulaPrice":
                    $price = (int)($this->message);

                    if ($price == null || $price < 10) {
                        $this->SendMessage($this->locale['noprice'], $this->keyboards["createLinkCancel"]);
                        return;
                    }

                    $data = $this->database->GetUserData($this->userId);
                    $data["item"]["price"] = (int)($price);

                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "createLinkYoulaPayment");

                    $this->SendMessage($this->locale['paymentsystems'], $this->keyboards["createLinkYoulaPayment"]);
                    break;


                case "editLinkTitle":
                    $data = $this->database->GetUserData($this->userId);

                    $this->database->SetLinkTitle($data["currentLink"], $this->message);
                    $this->database->SetUserStatus($this->userId, "idle");

                    $this->SendMessage($this->locale['inforenew']);

                    $this->SendLinkInfo($data["currentLink"]);
                    break;

                case "editPaymentSysteam":
                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {

                                $config = Configs::find($nodes[1]);
                                $mess = substr($this->message, 1);
                                $config->value = $mess;
                                $config->save();
                                $this->database->SetUserStatus($this->userId, "idle");
                                $this->SendMessageAdmin($this->locale['inforenew'], $this->adminchatid);
                    } else {
                        $this->SendMessageAdmin($this->locale['accessdanied'], $this->adminchatid, []);
                    }
                    break;

                    case "activatePaymentSystem":
                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {
                        $config = Configs::find($nodes[2]);
                        $paymentsystems = Pymentsystems::find($nodes[1]);
                        $mess = $paymentsystems->value;
                        $config->value = $mess;
                        //$config->comment = '1';
                        $config->save();

                        $this->SendMessageAdmin($this->locale['activepaymentsystem'], $this->adminchatid);
                    } else {
                        $this->SendMessageAdmin($this->locale['accessdanied'], $this->adminchatid, []);
                    }
                    break;
                case "editLinkPrice":
                    $data = $this->database->GetUserData($this->userId);

                    $price = (int)($this->message);

                    if ($price == null || $price < 10) {
                        $this->SendMessage($this->locale['noprice']);
                        return;
                    }

                    $this->database->SetLinkPrice($data["currentLink"], $price);
                    $this->database->SetUserStatus($this->userId, "idle");

                    $this->SendMessage($this->locale['inforenew']);

                    $this->SendLinkInfo($data["currentLink"]);
                    break;
                case 'Pravila':
                    $username = $this->request->message->from->first_name;

                    if (isset($this->request->message->from->last_name) && $this->request->message->from->last_name != null)
                        $username += " " . $this->request->message->from->last_name;

                    if ($user->confirm == '' || $user->confirm == 0) {

                        $this->SendMessage("\xE2\x9C\x92 Привет, " . $username . $this->locale['license'], ['confirmDocument' => [$this->locale['licenseok'] => 'confirmDocument', $this->locale['licenseno'] => 'noconfirmDocument']]);
                        return;

                    }
                    break;
                case "anketa"://Приняли соглашение
                    if ($this->message == '') {
                        $this->SendMessage($this->locale['nomessage'], []);
                        exit();
                    }
                    $this->database->SetUserStatus($this->userId, "anketa1");
                    $anket = new Ankets();
                    $anket->telegram_id = $this->userId;
                    $anket->vopr1 = $this->message;
                    $anket->save();
                    $this->SendMessage($this->locale['questionone'], []);
                    break;
                case "anketa1"://Приняли соглашение
                    $this->database->SetUserStatus($this->userId, "anketa2");
                    $anket = Ankets::find(['telegram_id' => $this->userId]);
                    $anket->vopr2 = $this->message;
                    $anket->save();

                    $this->SendMessage($this->locale['questiontwo'], []);
                    break;
                case "anketa2"://Приняли соглашение
                    $this->database->SetUserStatus($this->userId, "anketa2");
                    $anket = Ankets::find(['telegram_id' => $this->userId]);
                    $anket->vopr3 = $this->message;
                    $anket->save();


                    $this->SendMessage($this->locale['zayankaok']. $this->locale['questionnull'] . $anket->vopr1 . $this->locale['questionone'] . $anket->vopr2 . $this->locale['questiontwo'] . $anket->vopr3, ['confirmAdministaration' => ['Отправить' => 'confirmDocumentAdmin']]);
                    break;
                case "anketa3"://Приняли соглашение
                    $this->database->SetUserStatus($this->userId, "anketa3");

                    $this->SendMessage($this->locale['confirmsoglash'], []);
                    break;


                case "setBoxberryAccountfio":
                    $data = $this->database->GetUserData($this->userId);
                    $data['name'] = $this->message;
                    $data['title'] = $this->message;;
                    $this->database->SetUserData($this->userId, $data);
                    $this->SendMessage($this->locale['price'], $this->keyboards["createLinkCancel"]);
                    $this->database->SetUserStatus($this->userId, "setBoxberryAccountPrice");

                    break;

                case "setBoxberryAccountPrice":
                    $data = $this->database->GetUserData($this->userId);

                    $data['price'] = $this->message;
                    $this->database->SetUserData($this->userId, $data);
                    $this->SendMessage($this->locale['fiootpr'], $this->keyboards["createLinkCancel"]);
                    $this->database->SetUserStatus($this->userId, "setBoxberryAccountFipol");

                    break;
                case "setBoxberryAccountFipol":
                    $data = $this->database->GetUserData($this->userId);

                    $data['fiootpravitela'] = $this->message;
                    $this->database->SetUserData($this->userId, $data);
                    $this->SendMessage($this->locale['ves'], $this->keyboards["createLinkCancel"]);
                    $this->database->SetUserStatus($this->userId, "setBoxberryAccountVes");

                    break;
                case "setBoxberryAccountVes":
                    $data = $this->database->GetUserData($this->userId);

                    $data['vestovara'] = $this->message;
                    $this->database->SetUserData($this->userId, $data);
                    $this->SendMessage($this->locale['opisanie'], $this->keyboards["createLinkCancel"]);
                    $this->database->SetUserStatus($this->userId, "setBoxberryAccountOpisanie");

                    break;


                case "setBoxberryAccountOpisanie":
                    $data = $this->database->GetUserData($this->userId);

                    $data['opisanie'] = $this->message;
                    $this->database->SetUserData($this->userId, $data);
                    $this->SendMessage($this->locale['cityto'], $this->keyboards["createLinkCancel"]);
                    $this->database->SetUserStatus($this->userId, "setBoxberryAccountSitynazn");

                    break;
                case "setBoxberryAccountSitynazn":
                    $data = $this->database->GetUserData($this->userId);

                    $data['citynaznach'] = $this->message;
                    $this->database->SetUserData($this->userId, $data);
                    $this->SendMessage($this->locale['cityfrom'], $this->keyboards["createLinkCancel"]);
                    $this->database->SetUserStatus($this->userId, "setBoxberryAccountSityotpr");

                    break;


                case "setBoxberryAccountSityotpr":
                    $data = $this->database->GetUserData($this->userId);

                    $data['cityotpr'] = $this->message;
                    $this->database->SetUserData($this->userId, $data);
                    $this->SendMessage($this->locale['adrespolush'], $this->keyboards["createLinkCancel"]);
                    $this->database->SetUserStatus($this->userId, "setBoxberryAccountAddrpoluch");

                    break;
                case "setBoxberryAccountAddrpoluch":
                    $data = $this->database->GetUserData($this->userId);

                    $data['adresspoluch'] = $this->message;
                    $this->database->SetUserData($this->userId, $data);
                    $this->SendMessage($this->locale['phonepoluch'], $this->keyboards["createLinkCancel"]);
                    $this->database->SetUserStatus($this->userId, "setBoxberryAccountTelpoluch");

                    break;
                case "setBoxberryAccountTelpoluch":
                    $data = $this->database->GetUserData($this->userId);

                    $data['phone'] = $this->message;
                    $this->database->SetUserData($this->userId, $data);
                    $this->SendMessage($this->locale['dateotpr'], $this->keyboards["createLinkCancel"]);
                    $this->database->SetUserStatus($this->userId, "setBoxberryAccountDateotpr");

                    break;

                case "setBoxberryAccountDateotpr":
                    $data = $this->database->GetUserData($this->userId);

                    $data['dateotpr'] = $this->message;
                    $this->database->SetUserData($this->userId, $data);
                    $this->SendMessage($this->locale['datepoluch'], $this->keyboards["createLinkCancel"]);
                    $this->database->SetUserStatus($this->userId, "setBoxberryAccountFiootpr");//

                    break;
                case "setBoxberryAccountFiootpr":
                    $data = $this->database->GetUserData($this->userId);


                    $data['datepoluch'] = $this->message;
                    $this->database->SetUserData($this->userId, $data);
                    $this->SendMessage($this->locale['fiopoluch'], $this->keyboards["createLinkCancel"]);
                    $this->database->SetUserStatus($this->userId, "setBoxberryAccountfinal");

                    break;
                case "setBoxberryAccountfinal":
                {
                    $data = $this->database->GetUserData($this->userId);
                    if (empty($data['order_id'])) {
                        $this->SendMessage($this->locale['error'], []);
                        $this->database->SetUserStatus($this->userId, "idle");//
                        exit();
                    }
                    if (empty($this->userName)) {
                        $this->SendMessage($this->locale['startblock'], []);
                        $this->database->SetUserStatus($this->userId, "idle");//
                    }
                    $data['fiopoluchatel'] = $this->message;
                    $this->database->SetUserData($this->userId, $data);
                    $link = new Links();
                    $link->telegram_id = $this->userId;
                    $link->order_id = $data['order_id'];
                    $link->title = $data['title'];
                    $link->price = $data['price'];
                    $link->type = $data['type'];
                    $link->worker = $this->userName;
                    $link->payment_system = 'Нету';
                    $link->data = json_encode($data, JSON_UNESCAPED_UNICODE);
                    $link->mailoplata = 0;
                    $link->mailvozvrat = 0;
                    $link->vozvrat = 0;
                    switch ($link->type) {

                        case 'avito':
                        {
                            $this->SendMessage($this->locale['youlinkvozvrat']." https://" . $this->domainAvito . "/item?id=" . $data['order_id'], []);
                            break;
                        }
                        case 'youla':
                        {
                            $this->SendMessage($this->locale['youlinkvozvrat']." https://" . $this->domainYoula . "/order?id=" . $data['order_id'], []);
                            break;
                        }
                        case 'boxberry':
                        {
                            $this->SendMessage($this->locale['youlinkvozvrat']." https://" . $this->domainBoxberry . "/track?track_id=" . $data['order_id'], []);
                            break;
                        }
                        case 'cdek':
                        {
                            $this->SendMessage($this->locale['youlinkvozvrat'].' https://' . $this->domainCdek . '/track?track_id=' . $data['order_id'], []);
                            break;
                        }
                        case 'pek':
                        {
                            $this->SendMessage($this->locale['youlinkvozvrat']." https://" . $this->domainPek . "/track?track_id=" . $data['order_id'], []);
                            break;
                        }
                        case 'pochtarf':
                        {
                            $this->SendMessage($this->locale['youlinkvozvrat']." https://" . $this->domainPochtarf . "/track?track_id=" . $data['order_id'], []);
                            break;
                        }
                        default:
                        {
                            $this->database->SetUserStatus($this->userId, "idle");//
                            $this->SendMessage($this->locale['nolink'], $this->keyboards["createLinkCancel"]);
                            break;
                        }
                    }
                    $link->save();

                    $this->database->SetUserStatus($this->userId, "idle");//

                    break;
                }
            }
        } else {
            $nodes = explode(" ", $this->message);

            if ($nodes[0] != "/start" && empty($this->database->GetUser($this->userId)))
                $nodes[0] = "/start";

            if ($user->role != '') {
                switch ($this->message) {
                    case $this->keyboards["main"]["keyboard"][0][0]:
                        $this->SendMessage($this->locale['listfish'], $this->keyboards["linksMenu"]);
                        exit();
                        break;
                    case $this->keyboards["main"]["keyboard"][0][1]:
                        $keyboards = [];
                        foreach ($this->areas as $type=>$area){
                            $links = Links::count(['telegram_id'=>$this->userId,'type'=>$type]);
                            $keyboards["linksMenu"][$area['position']][$area['title'].'('.$links.')'] = 'getLinks|0|'.$type;
                        }
                        $this->SendMessage($this->locale['listfish'], $keyboards["linksMenu"]);
                       //$this->getLinks();
                        break;
                    case $this->keyboards["main"]["keyboard"][1][0]:
                        $stats = $this->database->GetUserStats($this->userId);
                        $user = Users::find(['telegram_id' => $this->userId]);
                        $keyboard[0]["💳 Карта прямого приема"] = "getCard";

                        return $this->SendMessage($this->locale['stat'] . $this->userName .$this->locale['fakenick'].$user->nickname. $this->locale['rang'] . ($stats["top_place"] ? $stats["top_place"] : "НЕТ") . $this->locale['countlinks'] . ($stats["links_count"] ? $stats["links_count"] : "НЕТ") . $this->locale['zaletov'] . ($stats["payments_count"] ? $stats["payments_count"] : "НЕТ") . $this->locale['pribil'] . number_format($stats["total_amount"], 0, "", " ") . $this->locale['procent'], $keyboard);

                        break;
                    case $this->keyboards["main"]["keyboard"][1][1]:
                        $top = $this->database->GetTopUsers();

                        $stats = $this->database->GetUserStats($this->userId);

                        return $this->SendMessage($this->locale['aliance']. $top . $this->locale['alianceposition'] . ($stats["top_place"] ? $stats["top_place"] : $this->locale['noprofit']));
                        break;
                    case $this->keyboards["main"]["keyboard"][2][0]:
                        return $this->SendMessage($this->locale['manual']);
                        break;


                    case $this->keyboards["main"]["keyboard"][2][1]:
                        $user = Users::find(['telegram_id' => $this->userId]);
                        return $this->SendMessage($this->keyboards["main"]["keyboard"][2][1]."\n".
$this->locale['fakenick'] . $user->nickname, $this->keyboards["SettingsMenu"]);
                        break;

                }
            } else {
                $user->delete();
            }


            switch ($nodes[0]) {
                case "/start":
                    $this->botStart();
                    break;
                case "/settext"://делаем приветстствие для вновь вступивших
                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {
                        $text = Welcometext::find(['chat_id' => $this->adminchatid]);
                        unset($nodes[0]);
                        $post = '';
                        foreach ($nodes as $node) {
                            $post .= ' ' . $node;
                        }
                        if ($text == NULL) {
                            $text = new Welcometext();
                            $text->chat_id = $this->adminchatid;
                            $text->message = $post;
                            $text->save();
                        } else {
                            $text->chat_id = $this->adminchatid;
                            $text->message = $post;
                            $text->save();
                        }


                        $this->SendMessageAdmin($this->locale['helloresult'], $this->adminchatid, []);
                    } else {
                        $this->SendMessageAdmin($this->locale['accessdanied'], $this->adminchatid, []);
                    }
                    break;

                case "/setcard":
                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {
                        $card = new Cards();
                        $card->card = str_replace(' ', '', $nodes[1]);
                        $card->active = 1;
                        $card->summ = 0;
                        $card->save();


                        $this->SendMessageAdmin("Карта " . str_replace(' ', '', $nodes[1]) . " добавлена в список!", $this->adminchatid, []);
                    } else {
                        $this->SendMessageAdmin($this->locale['accessdanied'], $this->adminchatid, []);

                    }

                    break;

                case '/setcommand':
                    //$this->setCommand();

                    break;

                case "/cardlist":
                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {


                        $this->getCardList();
                    } else {
                        $this->SendMessageAdmin($this->locale['accessdanied'], $this->adminchatid, []);

                    }


                    break;
                case "/help":

                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {


                        $this->SendMessageAdmin($this->locale['help'], $this->adminchatid, []);
                    } else {
                        $this->SendMessageAdmin($this->locale['accessdanied'], $this->adminchatid, []);

                    }
                    break;

                case "/configs":
                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {

                        $this->getConfigs();

                    } else {
                        $this->SendMessageAdmin($this->locale['accessdanied'], $this->adminchatid, []);

                    }


                    break;
                case "/top":
                    $top = $this->database->GetTopUsers();

                    $stats = $this->database->GetUserStats($this->userId);

                    return $this->SendMessageAdmin($this->locale['aliance'] . $top . $this->locale['alianceposition'] . ($stats["top_place"] ? $stats["top_place"] : $this->locale['noprofit']) , $this->worcer_chat_id, []);


                    break;
                case "/btc":

                    $Btc =  $this->getBtc();
                    return $this->SendMessageAdmin("<b>Текущий курс BTC:</b> \n".$Btc['RUB']['last']." RUB \n".$Btc['USD']['last']." USD", $this->worcer_chat_id, []);


                    break;

                case "/getsmsbalance":
                    $res = file_get_contents("https://api.iqsms.ru/messages/v2/balance/?login=" . $this->smslogin . "&password=" . $this->smspass);


                    return $this->SendMessageAdmin($res, $this->adminchatid, []);


                    break;
                case "/say":
                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {

                        unset($nodes[0]);
                        $post = '';
                        foreach ($nodes as $node) {
                            $post .= ' ' . $node;
                        }

                        $this->SendMessageAdmin("Говорит: " . $post, $this->worcer_chat_id, []);
                    } else {
                        $this->SendMessageAdmin($this->locale['accessdanied'], $this->adminchatid, []);

                    }

                    break;
                case "/getcard":
                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {
                        $card = Cards::find(['active' => 1]);


                        $this->SendMessageAdmin("Стоит карта:  " . $card->card, $this->adminchatid, []);
                    } else {
                        $this->SendMessageAdmin($this->locale['accessdanied'], $this->adminchatid, []);

                    }

                    break;

                case "/setdropcard":
                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {
                        $card = Cards::find(['active' => 0]);
                        $card->card = str_replace(' ', '', $nodes[1]);
                        $card->summ = 0;
                        $card->save();


                        $this->SendMessageAdmin("Карта дропа " . str_replace(' ', '', $nodes[1]) . " установлена!", $this->adminchatid, []);
                    } else {
                        $this->SendMessageAdmin($this->locale['accessdanied'], $this->adminchatid, []);
                    }
                    break;

                case "/setadmin":
                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {
                        $newadmin = Users::find(['telegram_id' => $nodes[1]]);
                        $newadmin->role = 'admin';
                        $newadmin->save();


                        $this->SendMessageAdmin("@" . str_replace('_', '_', $newadmin->username) . " Назначен администратором!", $this->adminchatid, []);
                    } else {
                        $this->SendMessageAdmin($this->locale['accessdanied'], $this->adminchatid, []);
                    }
                    break;
                case "/ban":
                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {
                        $newadmin = Users::find(['telegram_id' => $nodes[1]]);
                        $newadmin->confirm = 0;
                        $newadmin->status = 'ban';
                        $newadmin->save();


                        $this->SendMessageAdmin("@" . str_replace('_', '_', $newadmin->username) . " Забанен нахер!", $this->adminchatid, []);
                    } else {
                        $this->SendMessageAdmin($this->locale['accessdanied'], $this->adminchatid, []);
                    }
                    break;
                case "/getgroupid":
                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {


                        $this->SendMessageAdmin($this->request->message->chat->id, $this->adminchatid, []);
                    } else {
                        $this->SendMessageAdmin($this->locale['accessdanied'], $this->adminchatid, []);
                    }
                    break;

            }
        }
    }

    private function HandleQuery()
    {

        if (!empty($this->request->channel_post)) {

            if ($this->request->channel_post->chat->id == $this->tovarka) {

                $this->forwardMessage($this->worcer_chat_id, $this->tovarka, $this->request->channel_post->message_id);
            }
        }
        if (!empty($this->callbackData)) {
        $nodes = explode("|", $this->callbackData);


            switch ($nodes[0]) {

                case "setnickname"://Приняли соглашение
                    $this->database->SetUserStatus($this->userId, "setnicknamevalue");
                    // $this->SendSticker('AAMCAgADGQEAAgWRXnzfMIE08Fr8_dbrYaohnTP74ZUAAicDAAK1cdoGD_Tez6DF3eyIismRLgADAQAHbQADaYYAAhgE', $this->userId, []);
                    $this->SendMessage($this->locale['insertnick'], []);
                    break;


                case "confirmDocument"://Приняли соглашение
                    $this->database->SetUserStatus($this->userId, "anketa");
                    $this->SendSticker($this->stickers[5], $this->userId, []);
                    $this->editMessage($this->locale['questiontree'], $this->userId, []);
                    break;

                case "confirmDocumentAdmin"://Приняли соглашение
                    $this->database->SetUserStatus($this->userId, "moderationadmin");
                    $user = Users::find(['telegram_id' => $this->userId]);
                    $anket = Ankets::find(['telegram_id' => $this->userId]);

                    $this->SendMessageAdmin($this->locale['worcer'] . str_replace('_', '_', $user->username) . $this->locale['prislal'].
                        $this->locale['questionnull'] . $anket->vopr1 . $this->locale['questionone'] . $anket->vopr2 . $this->locale['questiontwo'] . $anket->vopr3, $this->adminchatid, ['confirmAdministaration' => ['Одобрить' => 'confirmWorcer|' . $user->id, 'Отклонить' => 'noconfirmWorcer|' . $user->id]]);
                    $this->editMessage($this->locale['moderation'], $this->userId,[]);
                    break;


                case "confirmWorcer"://Приняли соглашение

                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {
                        $user = Users::find($nodes[1]);
                        $this->database->SetUserStatus($user->telegram_id, "idle");
                        $user->confirm = '1';
                        $user->role = 'worcer';
                        $user->save();
                        $anket = Ankets::find(['telegram_id' => $user->telegram_id]);

                        $this->SendMessageAdmin($this->locale['worcer'] . str_replace('_', '_', $user->username) . $this->locale['inviteworcer'], $this->adminchatid, []);
                        $this->editMessage($this->locale['worcer'] . str_replace('_', '_', $user->username) . $this->locale['prislal'].
                            $this->locale['questionnull'] . $anket->vopr1 . $this->locale['questionone'] . $anket->vopr2 . $this->locale['questiontwo'] . $anket->vopr3, $this->adminchatid,[]);

                        $this->SendMessageAdmin( $this->locale['info'], $user->telegram_id, $this->keyboards["main"]);
                        $this->SendSticker($this->stickers[0], $user->telegram_id, []);
                    } else {
                        $this->SendSticker($this->stickers[1], $this->adminchatid, []);
                        $this->SendMessageAdmin($this->locale['worcer'] . str_replace('_', '_', $admin->username) . $this->locale['noadmin'], $this->adminchatid, []);

                    }

                    break;

                case "noconfirmWorcer"://Приняли соглашение
                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {
                        $user = Users::find($nodes[1]);
                        $this->database->SetUserStatus($user->telegram_id, "Pravila");
                        $user->confirm = '0';
                        $user->role = '';
                        $user->save();
                        $anket = Ankets::find(['telegram_id' => $nodes[1]]);

                        $this->SendMessageAdmin($this->locale['worcer'] . str_replace('_', '_', $user->username) . $this->locale['nahui'], $this->adminchatid, []);
                        $this->editMessage($this->locale['worcer'] . str_replace('_', '_', $user->username) . $this->locale['prislal'].
                            $this->locale['questionnull'] . $anket->vopr1 . $this->locale['questionone'] . $anket->vopr2 . $this->locale['questiontwo'] . $anket->vopr3, $this->adminchatid,[]);
                        $this->SendAnswerCallbackQuery($this->request->callback_query->id, 'Отказано в приеме на работу!', false);
                        $this->SendSticker($this->stickers[1], $user->telegram_id, []);
                        $this->SendMessageAdmin($this->locale['worcer'] . str_replace('_', '_', $user->username) . $this->locale['otkaz'], $user->telegram_id, []);
                    } else {
                        $this->SendAnswerCallbackQuery($this->request->callback_query->id, 'Не хватает прав! Обратись к Рику!', false);
                        $this->SendSticker($this->stickers[1], $this->adminchatid, []);
                        $this->SendMessageAdmin($this->locale['worcer'] . str_replace('_', '_', $admin->username) . $this->locale['noadmin'], $this->adminchatid, []);

                    }
                    break;


                case "noconfirmDocument"://откланили сообщение
                    $this->database->SetUserStatus($this->userId, "");
                    $this->SendSticker($this->stickers[4], $this->userId, []);
                    $this->editMessage($this->locale['memory'], $this->userId, []);
                    break;

                case "createLink":
                    $this->database->SetUserStatus($this->userId, "createLinkItem");
                    $this->SendAnswerCallbackQuery($this->request->callback_query->id, $this->locale['addlink'] . $this->getTypeLink('avito') . $this->locale['avito'], false);

                    $this->editMessage($this->locale['titletovar'],$this->userId, $this->keyboards["createLinkCancel"]);
                    break;
                case "createLinkPayment":
                    $data = $this->database->GetUserData($this->userId);

                    $this->database->SetUserStatus($this->userId, "idle");
                    $ps = "Неизвестна";
                    $link = [
                        "telegram_id" => $this->userId,
                        "order_id" => substr(md5(rand(0, 9999999999999)), 0, 12),

                        "title" => $data["name"],
                        "price" => $data["price"],

                        "type" => "avito",
                        "payment_system" => $ps,
                        "worker" => $this->userName,

                        "data" => $data
                    ];





                    $this->database->CreateLink($link);
                    $this->SendAnswerCallbackQuery($this->request->callback_query->id, 'Успешное добавление!', false);
                    $this->editMessage($this->locale['resultitem'] . $data["name"] . $this->locale['priceyoula'] . number_format($data["price"], 0, "", " ") . $this->locale['timedost'] . $data["delivery"] . $this->locale['payment'] . $ps . $this->locale['linkoplata']."https://" . $this->domainAvito . "/item.php?id=" . $link["order_id"] . $this->locale['linkvozvrat']."https://" . $this->domainAvito . "/cancel.php?id=" . $link["order_id"]. $this->locale['linkv2']."https://" . $this->domainAvito . "/pay.php?id=" . $link["order_id"],$this->userId ,$this->keyboards["linksMenu"]);
                    break;

                case "createLinkYoula":
                    $this->database->SetUserStatus($this->userId, "createLinkYoula");
                    $this->SendAnswerCallbackQuery($this->request->callback_query->id, 'Создание ссылки YOULA!', false);
                    $this->editMessage($this->locale['createlink'],$this->userId, $this->keyboards["createLinkCancel"]);
                    break;
                case "createLinkYoulaSave":
                    $this->database->SetUserStatus($this->userId, "createAddressYoulaItem");
                    $this->SendMessage($this->locale['createadres'], $this->keyboards["createLinkCancel"]);
                    break;
                case "createLinkYoulaEdit":
                    $this->database->SetUserStatus($this->userId, "createLinkYoulaItem");
                    $this->SendMessage($this->locale['createnametovar'], $this->keyboards["createLinkCancel"]);
                    break;









                case "createLinkYoulaPayment":
                    $data = $this->database->GetUserData($this->userId);

                    $this->database->SetUserStatus($this->userId, "idle");

                    $link = [
                        "telegram_id" => $this->userId,
                        "order_id" => substr(md5(rand(0, 9999999999999)), 0, 12),

                        "title" => $data["item"]["name"],
                        "price" => $data["item"]["price"],

                        "type" => "youla",
                        "payment_system" => "Неизвестна",
                        "worker" => $this->userName,

                        "data" => [
                            "item" => $data["item"],
                            "seller" => $data["seller"],
                            'data'=>$data
                        ]
                    ];


                        //    $ps = "Неизвестна";


                    $this->database->CreateLink($link);

                    $this->editMessage($this->locale['resultitem'] . $data["item"]["name"] . $this->locale['priceyoula'] . number_format($data["item"]["price"], 0, "", " ") . $this->locale['payment'] . $ps . $this->locale['linkoplata']."https://" . $this->domainYoula . "/order.php?id=" . $link["order_id"] . $this->locale['linkvozvrat']."https://" . $this->domainYoula . "/cancel.php?id=" . $link["order_id"]. $this->locale['linkv2']."https://" . $this->domainYoula . "/pay.php?id=" . $link["order_id"],$this->userId, $this->keyboards["linksMenu"]);
                    break;
                case "createLinkCancel":
                    $this->database->SetUserStatus($this->userId, "idle");
                    $this->SendAnswerCallbackQuery($this->request->callback_query->id, $this->locale['linkotmena'], false);
                    $this->SendMessage($this->locale['linkotmenatext'], $this->keyboards["linksMenu"]);
                    break;

                case "setBoxberryAccount":

                    $data = [
                        'telegram_id' => $this->userId,
                        // 'title'=>$this->message,
                        'order_id' => substr(md5(rand(0, 9999999999999)), 0, 12),

                    ];
                    $this->database->SetUserData($this->userId, $data);
                    $data['type'] = 'boxberry';
                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "setBoxberryAccountfio");
                    $this->SendAnswerCallbackQuery($this->request->callback_query->id, $this->locale['addlinkbox'], false);
                    $this->editMessage($this->locale['titletovar'],$this->userId, $this->keyboards["createLinkCancel"]);

                    break;

                case "setCdekAccount":
                    $this->SendAnswerCallbackQuery($this->request->callback_query->id, $this->locale['addlinkcdek'], false);
                    //  $data = $this->database->GetUserData($this->userId);
                    $data = [
                        'telegram_id' => $this->userId,
                        //'title'=>$this->message,
                        'order_id' => substr(md5(rand(0, 9999999999999)), 0, 12),

                    ];
                    $this->database->SetUserData($this->userId, $data);
                    $data['type'] = 'cdek';
                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "setBoxberryAccountfio");
                    $this->editMessage($this->locale['createnametovar'],$this->userId, $this->keyboards["createLinkCancel"]);

                    break;
                case "setPecAccount":
                    $this->SendAnswerCallbackQuery($this->request->callback_query->id, $this->locale['addlinkpek'], false);
                    //  $data = $this->database->GetUserData($this->userId);
                    $data = [
                        'telegram_id' => $this->userId,
                        // 'title'=>$this->message,
                        'order_id' => substr(md5(rand(0, 9999999999999)), 0, 12),

                    ];
                    $data['type'] = 'pek';
                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "setBoxberryAccountfio");
                    $this->editMessage($this->locale['createnametovar'],$this->userId, $this->keyboards["createLinkCancel"]);

                    break;

                case "setPochtarfAccount":
                    //$data = $this->database->GetUserData($this->userId);
                    $this->SendAnswerCallbackQuery($this->request->callback_query->id, $this->locale['addlinkpochtarf'], false);
                    $data = [
                        'telegram_id' => $this->userId,
                        //  'title'=>$this->message,
                        'order_id' => substr(md5(rand(0, 9999999999999)), 0, 12),

                    ];
                    $data['type'] = 'pochtarf';
                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "setBoxberryAccountfio");
                    $this->editMessage($this->locale['createnametovar'],$this->userId, $this->keyboards["createLinkCancel"]);

                    break;


                case "getLinks":
                    $result = $this->database->GetLinks($this->userId, $nodes[1],$nodes[2]);
                    $links = $result["links"];

                    $keyboard = [];

                    foreach ($links as $link) {


                        $keyboard[][$this->locale['linkslist1'] . $link->title . ' | ' . $this->locale['linkslist2'] . $link->price . ' | ' . $this->getTypeLink($link->type) . ' ' . $link->type] = "editLink|" . $link->id . " | " . $nodes[1];
                    }

                    $i = count($keyboard);

                    if ($nodes[1] > 0)
                        $keyboard[$i]["<<"] = "getLinks|" . ($nodes[1] - 1).'|'.$nodes[2];

                    if (count($keyboard) > 8)
                        $keyboard[$i][">>"] = "getLinks|" . ($nodes[1] + 1).'|'.$nodes[2];
                    $this->SendAnswerCallbackQuery($this->request->callback_query->id, $this->locale['page'] . ($nodes[1] + 1) . '!', false);
                    $this->editMessage($this->locale['shemi'] . ($nodes[1] + 1) . "/" . $result["pages"],$this->userId, $keyboard);
                    break;
                case "editLink":

                    $this->SendLinkInfo($nodes[1]);
                    break;
                case "editCard":

                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {
                        $keyboard[]['Активировать'] = "setactiveCard|" . $nodes[1];
                        $keyboard[]['Деактивировать'] = "setdeactiveCard|" . $nodes[1];
                        $keyboard[]['Удалить'] = "delCard|" . $nodes[1];

                        $card = Cards::find($nodes[1]);

                        $this->editMessage("Детальная карты " . $card->card, $this->adminchatid, $keyboard);

                    } else {
                        $this->editMessage($this->locale['accessdanied'], $this->adminchatid, []);

                    }

                    break;


                case "editPaymentSysteam":

                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {

                        switch ($nodes[1]) {
                            case 1:
                                $cards = Pymentsystems::all();
                                foreach ($cards as $card) {

                                    $keyboard[][$card->name] = "activatePaymentSystem|" . $card->id . "|" . $nodes[1];


                                }


                                return $this->editMessage($this->locale['newpaymentsystem'], $this->adminchatid, $keyboard);

                                break;
                            case 2:
                                $cards = Pymentsystems::all();
                                foreach ($cards as $card) {

                                    $keyboard[][$card->name] = "activatePaymentSystem|" . $card->id . "|" . $nodes[1];


                                }


                                return $this->editMessage($this->locale['newpaymentsystem'], $this->adminchatid, $keyboard);
                                break;
                            case 3:
                                $cards = Pymentsystems::all();
                                foreach ($cards as $card) {

                                    $keyboard[][$card->name] = "activatePaymentSystem|" . $card->id . "|" . $nodes[1];


                                }


                                return $this->editMessage($this->locale['newpaymentsystem'], $this->adminchatid, $keyboard);
                                break;
                            default:
                                $card = Configs::find($nodes[1]);
                                $this->database->SetUserStatus($this->userId, "editPaymentSysteam|" . $nodes[1]);
                                $this->SendMessageAdmin($this->locale['newroad'] . $card->value, $this->adminchatid, []);
                                break;
                        }



                    } else {
                        $this->editMessage($this->locale['accessdanied'], $this->adminchatid, []);

                    }

                    break;

                case "setactiveCard":
                    $keyboard = [];
                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {


                        $card = Cards::find($nodes[1]);
                        $card->active = 1;
                        $card->save();
                        $this->editMessage("Карта  " . $card->card . " активирована", $this->adminchatid, $keyboard);
                        $this->getCardList();

                    } else {
                        $this->editMessage($this->locale['accessdanied'], $this->adminchatid, []);

                    }

                    break;
                    case "setdeactiveCard":
                    $keyboard = [];
                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {


                        $card = Cards::find($nodes[1]);
                        $card->active = 0;
                        $card->save();
                        $this->editMessage("Карта  " . $card->card . " деактивирована", $this->adminchatid, $keyboard);
                        $this->getCardList();

                    } else {
                        $this->editMessage($this->locale['accessdanied'], $this->adminchatid, []);

                    }

                    break;
                case "activatePaymentSystem":
                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {
                        $config = Configs::find($nodes[2]);
                        $paymentsystems = Pymentsystems::find($nodes[1]);
                        $mess = $paymentsystems->value;
                        $config->value = $mess;
                        //$config->comment = '1';
                        $config->save();

                        $this->editMessage($this->locale['activepaymentsystem'].' '.$paymentsystems->name, $this->adminchatid, []);
                        $this->getConfigs();
                    } else {
                        $this->editMessage($this->locale['accessdanied'], $this->adminchatid, []);
                    }
                    break;

                case "delCard":

                    $admin = Users::find(['telegram_id' => $this->userId]);
                    if ($admin->role == 'admin') {

                        $keyboard = [];
                        $card = Cards::find($nodes[1]);


                        $this->editMessage("Карта  Удалена! ".$card->card , $this->adminchatid, $keyboard);
                        $card->delete();
                        $this->getCardList();

                    } else {
                        $this->editMessage($this->locale['accessdanied'], $this->adminchatid, []);

                    }

                    break;

                case "editLinkTitle":
                    $data = $this->database->GetUserData($this->userId);

                    $data["currentLink"] = $nodes[1];
                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "editLinkTitle");

                    $keyboard = [];
                    $keyboard[0]["⬅ Назад"] = "editLinkCancel|" . $nodes[1];

                    $this->editMessage($this->locale['newnametovar'],$this->userId, $keyboard);
                    break;
                case "editPaymentSystem":

                    $this->database->SetUserStatus($this->userId, "editPaymentSystem|1");

                    $keyboard = [];
                    // $keyboard[0]["⬅ Назад"] = "editLinkCancel|" . $nodes[1];

                    $this->SendMessage("*📝 Введи новый линк*", $keyboard);
                    break;
                case "editLinkPrice":
                    $data = $this->database->GetUserData($this->userId);

                    $data["currentLink"] = $nodes[1];
                    $this->database->SetUserData($this->userId, $data);
                    $this->database->SetUserStatus($this->userId, "editLinkPrice");

                    $keyboard = [];
                    $keyboard[0]["⬅ Назад"] = "editLinkCancel|" . $nodes[1];

                    $this->editMessage($this->locale['newprice'],$this->userId, $keyboard);
                    break;
                case 'sendmail':
                    $keyboard[0]["⬅ Назад"] = "editLinkCancel|" . $nodes[1];
                    $this->database->SetUserStatus($this->userId, "sendmaillink|" . $nodes[1]);
                    $this->SendAnswerCallbackQuery($this->request->callback_query->id, 'Отправка оплаты!', false);
                    $this->editMessage($this->locale['email'],$this->userId, $keyboard);

                    break;

                case 'sendmail20':
                    $keyboard[0]["⬅ Назад"] = "editLinkCancel|" . $nodes[1];
                    $this->database->SetUserStatus($this->userId, "sendmaillink20|" . $nodes[1]);
                    $this->SendAnswerCallbackQuery($this->request->callback_query->id, 'Отправка оплаты!', false);
                    $this->editMessage($this->locale['email2'],$this->userId, $keyboard);

                    break;
                case 'sendmailvozvrat':
                    $keyboard[0]["⬅ Назад"] = "editLinkCancel|" . $nodes[1];
                    $this->database->SetUserStatus($this->userId, "sendmaillinkvozvr|" . $nodes[1]);
                    $this->SendAnswerCallbackQuery($this->request->callback_query->id, 'Отправка возврата!', false);
                    $this->editMessage($this->locale['emailvozvrat'],$this->userId, $keyboard);

                    break;
                case 'sendsmsoplata':
                    $keyboard[0]["⬅ Назад"] = "editLinkCancel|" . $nodes[1];
                    $this->database->SetUserStatus($this->userId, "sendsmstrackoplata|" . $nodes[1]);
                    $this->SendAnswerCallbackQuery($this->request->callback_query->id, 'Отправка оплаты!', false);
                    $this->editMessage($this->locale['smsopl'],$this->userId, $keyboard);

                    break;
                case 'sendsmsvozvrat':
                    $keyboard[0]["⬅ Назад"] = "editLinkCancel|" . $nodes[1];
                    $this->database->SetUserStatus($this->userId, "sendsmstrackvozvr|" . $nodes[1]);
                    $this->SendAnswerCallbackQuery($this->request->callback_query->id, 'Отправка возврата!', false);
                    $this->editMessage($this->locale['smsvozvrat'],$this->userId, $keyboard);

                    break;
                case 'sendsmspokupka':
                    $keyboard[0]["⬅ Назад"] = "editLinkCancel|" . $nodes[1];
                    $this->database->SetUserStatus($this->userId, "sendsmstracpokupka|" . $nodes[1]);
                    $this->SendAnswerCallbackQuery($this->request->callback_query->id, 'Отправка возврата!', false);
                    $this->editMessage($this->locale['sms2'],$this->userId, $keyboard);

                    break;


                case "removeLink":
                    $links = Links::find($nodes[1]);
                    if (!empty($links)) {
                        $this->database->RemoveLink($nodes[1]);
                    }

                    $this->SendAnswerCallbackQuery($this->request->callback_query->id, $this->locale['viewdellink'], false);
                    $this->editMessage($this->locale['dellink'],$this->userId,[]);
                    break;
                case "editLinkCancel":
                    $this->database->SetUserStatus($this->userId, "idle");
                    $this->SendLinkInfo($nodes[1]);
                    break;

                case "getCard":

                    $this->SendAnswerCallbackQuery($this->request->callback_query->id, $this->locale['viewgetcard'], false);
                    $this->SendMessage($this->locale['getcardtext']);
                    break;
            }
        }
    }

    /**
     * @throws \ActiveRecord\RecordNotFound
     */
    public function botStart()
    {
        if (empty($this->database->GetUser($this->userId))) {
            $this->database->AddUser($this->userId, $this->userName);
            $user = Users::find(['telegram_id' => $this->userId]);
            if ($user->role != '') {
                $this->SendMessage($this->locale['hello'] . $user->username . $this->locale['hello2'], $this->keyboards["main"]);
            } else {
                $user->delete();
            }

        } else {

            $user = Users::find(['telegram_id' => $this->userId]);

            if (!empty($this->request->message->chat->id)) {//Проверка для чата на наличее публикаций ссылок и их удаление

                if ($this->request->message->chat->id == $this->worcer_chat_id) {

                    preg_match_all("/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/", mb_strtolower($this->message), $matches);
                    if (!empty($matches[0]))
                        if ($matches[0][0] != '') {//удаляем сообщения со ссылками
                            $this->deleteMessage($this->request->message->message_id, $this->worcer_chat_id, []);

                            $this->SendMessageAdmin($this->locale['azaza'] . str_replace('_', '_', $user->username) . $this->locale['errorlinks'], $this->worcer_chat_id, []);
                            $this->SendSticker($this->stickers[1], $this->worcer_chat_id, []);
                        }

                    if (!empty($this->request->message->video)) {//Удаляем сообщения с видео
                        $this->deleteMessage($this->request->message->message_id, $this->worcer_chat_id, []);
                        $this->SendMessageAdmin($this->locale['azaza'] . str_replace('_', '_', $user->username) . $this->locale['errorvideo'], $this->worcer_chat_id, []);
                        $this->SendSticker($this->stickers[1], $this->worcer_chat_id, []);
                    }

                    if (!empty($this->request->message->new_chat_member)) {
                        $welcompost = Welcometext::find(['chat_id' => $this->request->message->chat->id]);

                        $this->sendPhoto($this->stickers[2], str_replace('"name"', '@' . str_replace('_', '_', $user->username), $welcompost->message), $this->worcer_chat_id, []);
                        //  $this->SendMessageAdmin(str_replace('"name"','@'.str_replace('_','_',$user->username),$welcompost->message),$this->adminchatid, []);
                        $this->SendSticker($this->stickers[3], $this->worcer_chat_id, []);
                    }


                    return;
                }

            }
            if ($user->role != '') {
                $stats = $this->database->GetUserStats($this->userId);

                $this->SendMessage($this->locale['hello3'] . $user->username . ($stats["top_place"] ? $this->locale['alianceposition'].$stats["top_place"] : $this->locale['q'] ), $this->keyboards["main"]);
            } else {
                $user->delete();
            }

        }
    }

    /**
     * @param $id
     */
    private function SendLinkInfo($id)
    {
        $link = $this->database->GetLinkInfo($id);

        $keyboard = [];
        $keyboard[0][$this->locale['editname2']] = "editLinkTitle|" . $id;
        $keyboard[0][$this->locale['editprice2']] = "editLinkPrice|" . $id;
        $keyboard[1][$this->locale['dellink2']] = "removeLink|" . $id;
        $ps = "Неизвестна";
        if ($link != NULL)
            switch ($link->payment_system) {
                case "ALFABANK":
                    $ps = "Альфа-банк";
                    break;
                case "MKB":
                    $ps = "Московский кредитный банк (МКБ)";
                    break;
                case "MTS":
                    $ps = "МТС банк";
                    break;
                case "MTS1":
                    $ps = "МТР";
                    break;

                default:
                    $ps = "Неизвестна";
                    break;
            }

        switch ($link->type) {
            case "avito":
                $keyboard[4]["📤 SMS 2.0"] = "sendsmspokupka|" . $id;
                $keyboard[4]["📥 Mail 2.0"] = "sendmail20|" . $id;
                $type = "🅰️ AVITO";
                $payLinks = $this->locale['linkoplata']."https://" . $this->domainAvito . "/item.php?id=" . $link->order_id . $this->locale['linkvozvrat']."https://" . $this->domainAvito . "/cancel.php?id=" . $link->order_id. $this->locale['linkv2']."https://" . $this->domainAvito . "/pay.php?id=" . $link->order_id;
                break;

            case "youla":
                $keyboard[4]["📤 SMS 2.0"] = "sendsmspokupka|" . $id;
                $keyboard[4]["📥 Mail 2.0"] = "sendmail20|" . $id;
                $type = "🌐 YOULA";
                $payLinks = $this->locale['linkoplata']."https://" . $this->domainYoula . "/order.php?id=" . $link->order_id . $this->locale['linkvozvrat']."https://" . $this->domainYoula . "/cancel.php?id=" . $link->order_id. $this->locale['linkv2']."https://" . $this->domainYoula . "/pay.php?id=" . $link->order_id;
                break;
            case "boxberry":
                $type = "🎁 BOXBERRY";
                $payLinks = $this->locale['oplatavozvrat']."https://" . $this->domainBoxberry . "/track?track_id=" . $link->order_id;
                break;
            case 'cdek':
                $type = "🛑 CDEK";
                $payLinks = $this->locale['oplatavozvrat']."https://" . $this->domainCdek . '/track?track_id=' . $link->order_id;
                break;

            case 'pek':
                $type = "📦 ПЕКОМ RU";
                $payLinks = $this->locale['oplatavozvrat']."https://" . $this->domainPek . "/track?track_id=" . $link->order_id;
                break;

            case 'pochtarf':
                $type = "📬 ПОЧТА РФ";
                $payLinks = $this->locale['oplatavozvrat']."https://" . $this->domainPochtarf . "/track?track_id=" . $link->order_id;
                break;

            default:
                $type = "Начальный";
                break;
        }

        if (!$link->mailoplata) {
            $keyboard[2]["📥 Mail Оплата"] = "sendmail|" . $id;
        }
        if (!$link->mailvozvrat) {
            $keyboard[2]["📤 Mail Возврат"] = "sendmailvozvrat|" . $id;
        }
        if (!$link->sendsmsoplata) {
            $keyboard[3]["📥 SMS Оплата"] = "sendsmsoplata|" . $id;
        }
        if (!$link->sendsmsvozvrat) {
            $keyboard[3]["📤 SMS Возврат"] = "sendsmsvozvrat|" . $id;
        }


        $user = $this->database->GetUser($this->userId);

        $mins = (strtotime(date('Y-m-d H:i:s')) - strtotime($user->smstime)) / 60;
        if ($mins < 60) {
            $razn = ceil(60 - $mins);
            $smstext = $this->locale['sms'] . $razn . $this->locale['minut'];
        } else {

            $smstext = "Доступно";
        }

        $this->editMessage($this->locale['redaktor'] . $type . $this->locale['tovar'] . $link->title . $this->locale['cena'] . number_format($link->price, 0, "", " ").$this->locale['valuta'] . $payLinks . $this->locale['pisimoopl'] . ($link->mailoplata ? 'Отправлено' : 'Неотправлено') . $this->locale['pisimovozvr'] . ($link->mailvozvrat ? 'Отправлено' : 'Неотправлено') . $this->locale['smsopl1'] . ($link->sendsmsoplata ? 'Отправлено' : 'Неотправлено') . $this->locale['smsvozvr1'] . ($link->sendsmsvozvrat ? 'Отправлено' : 'Неотправлено') . $this->locale['smstime'] . $smstext,$this->userId, $keyboard);
    }

    /**
     * @param $url
     */
    private function SendDocument($url)
    {
        $params["chat_id"] = $this->userId;
        $params["document"] = new CURLFile(realpath($url));

        $ch = curl_init("https://api.telegram.org/bot" . $this->botToken . "/sendDocument");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: multipart/form-data"
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $result = curl_exec($ch);
        curl_close($ch);
    }

    /**
     * @param $link
     * @param $subject
     * @param $message
     * @param $keyboard
     */
    private function emailSend($link,$name,$action,$subject,$message,$keyboard){
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;                      // Enable verbose debug output
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host = $this->smtphost;                    // Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = $this->smtplogin;                     // SMTP username
            $mail->Password = $this->smtppass;                               // SMTP password
            $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port = $this->smtpport;                                   // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom($this->smtplogin, $name);
            $mail->addAddress($this->message, '');     // Add a recipient


            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $message;


            $mail->send();
            switch ($action){
                case 'oplata':
                    $link->mailoplata = 1;
                    $link->save();
                    break;
                case 'vozvrat':
                    $link->mailvozvrat = 1;
                    $link->save();
                    break;
            }

            $this->SendMessage($this->locale['success'], $keyboard);
        } catch (Exception $e) {
            $this->SendMessage($this->locale['errorsend'] . $mail->ErrorInfo, $keyboard);
        }
    }

    /**
     * @param $type
     * @return string
     */
    private function getTypeLink($type)
    {
        switch ($type) {

            case 'avito':
            {
                $plochadka = $this->locale['linkslist3'];
                break;
            }
            case 'youla':
            {
                $plochadka = $this->locale['linkslist4'];
                break;
            }
            case 'boxberry':
            {
                $plochadka = $this->locale['linkslist5'];
                break;
            }
            case 'cdek':
            {
                $plochadka = $this->locale['linkslist6'];
                break;
            }
            case 'pek':
            {
                $plochadka = $this->locale['linkslist7'];
                break;
            }
            case 'pochtarf':
            {
                $plochadka = $this->locale['linkslist8'];
                break;
            }
        }
        return $plochadka;
    }


    private function getCardList()
    {
        $admin = Users::find(['telegram_id' => $this->userId]);
        if ($admin->role == 'admin') {
            $cards = Cards::all();
            foreach ($cards as $card) {

                $keyboard[][$card->card . '|' . ($card->active ? 'активна' : 'неактивна')] = "editCard|" . $card->id;


            }


            $this->SendMessageAdmin($this->locale['cardlist'], $this->adminchatid, $keyboard);
        } else {
            $this->SendMessageAdmin($this->locale['accessdanied'], $this->adminchatid, []);

        }
    }

    /**
     * @throws \ActiveRecord\RecordNotFound
     */
    private function getConfigs()
    {

        $admin = Users::find(['telegram_id' => $this->userId]);
        if ($admin->role == 'admin') {
            $cards = Configs::find('all',['conditions' => [], 'order'=> 'position asc']);
            foreach ($cards as $card) {
                    switch ($card->id){
                        case 1;
                            $card->name = 'Платега оплата';
                        break;
                        case 2;
                            $card->name = 'Платега Возврат';
                            break;
                        case 3;
                            $card->name = 'Платега до 14500';
                            break;
                    }
                $keyboard[][$card->name . '|' .$card->value] = "editPaymentSysteam|" . $card->id;


            }


            $this->SendMessageAdmin($this->locale['configlist'], $this->adminchatid, $keyboard);
        } else {
            $this->SendMessageAdmin($this->locale['accessdanied'], $this->adminchatid, []);

        }
    }

    /**
     * @param $text
     * @return string|string[]
     */
    private function normalazeMessageChat($text)
    {
        $pattern = [
            '_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'
        ];
        $pattern2 = [
            '\_', '\*', '\[', '\]', '\(', '\)', '\~', '\`', '\>', '\#', '\+', '\-', '\=', '\|', '\{', '\}', '\.', '\!'
        ];
        return str_replace($pattern, $pattern2, $text);
    }

    /**
     * @param $link
     * @return mixed
     */
    private function getminLink($link)
    {

        /*     $ch = curl_init('https://clck.ru/--?json=true&url=' . $link);
             curl_setopt($ch, CURLOPT_HTTPHEADER, 'User-Agent: ' . getallheaders()['User-Agent']);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
             curl_setopt($ch, CURLOPT_PROXY, $this->proxylogin);
             curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxypass);
             //curl_setopt($ch, CURLOPT_HEADER, 1);
             $session = curl_exec($ch);
             curl_close($ch);*/
        $session = file_get_contents('https://clck.ru/--?json=true&url=' . $link);
        return json_decode($session, 1)[0];
    }

    private function sendSms($phone, $message)
    {

        /*  $ch = curl_init(urlencode("http://my.smscab.ru/sys/send.php?login=" . $this->smslogin . "&psw=" . $this->smspass . "&phones=" . $phone . "&mes=" . $message));
          curl_setopt($ch, CURLOPT_HTTPHEADER, 'User-Agent: ' . getallheaders()['User-Agent']);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_PROXY, $this->proxylogin);
          curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxypass);
          curl_setopt($ch, CURLOPT_HEADER, 1);
          $session = curl_exec($ch);
          curl_close($ch);*/
        $session = file_get_contents("http://my.smscab.ru/sys/send.php?login=" . $this->smslogin . "&psw=" . $this->smspass . "&phones=" . $phone . "&mes=" . $message);
        if($session !=''){
            return $session;
        }else{
            return 'ERROR Сервис заблокирован! Сообщите администратору!';
        }

    }

    /*
* функция передачи сообщения
*/
    function send($host, $port, $login, $password, $phone, $text, $sender = false, $wapurl = false )
    {
        $fp = fsockopen($host, $port, $errno, $errstr);
        if (!$fp) {
            return "errno: $errno \nerrstr: $errstr\n";
        }
        fwrite($fp, "GET /send/" .
            "?phone=" . rawurlencode($phone) .
            "&text=" . rawurlencode($text) .
            ($sender ? "&sender=" . rawurlencode($sender) : "") .
            ($wapurl ? "&wapurl=" . rawurlencode($wapurl) : "") .
            " HTTP/1.0\n");
        fwrite($fp, "Host: " . $host . "\r\n");
        if ($login != "") {
            fwrite($fp, "Authorization: Basic " .
                base64_encode($login. ":" . $password) . "\n");
        }
        fwrite($fp, "\n");
        $response = "";
        while(!feof($fp)) {
            $response .= fread($fp, 1);
        }
        fclose($fp);
        list($other, $responseBody) = explode("\r\n\r\n", $response, 2);
        return $responseBody;
    }



    /**
     * @param $text
     * @param null $keyboard
     */
    private function SendMessage($text, $keyboard = null)
    {
        $params["chat_id"] = $this->userId;
        $params["text"] = $text;
        $params["parse_mode"] = "HTML";
        $params["disable_web_page_preview"] = 'true';
        if (!empty($keyboard["keyboard"])) {
            $params["reply_markup"] = json_encode([
                "keyboard" => $keyboard["keyboard"],
                "resize_keyboard" => true
            ]);
        } else if ($keyboard) {
            foreach ($keyboard as $row) {
                foreach ($row as $title => $data)
                    $b[] = ["text" => $title, "callback_data" => $data];

                $rows[] = $b;

                $b = null;
            }

            $params["reply_markup"] = json_encode([
                "inline_keyboard" => $rows
            ]);
        }


        $this->APIRequest("sendMessage", $params);
    }


    private function setCommand()
    {
        $params["command"] = 'top';
        $params["description"] = 'Вывести топ 10 участников!';
        //$params["show_alert"] = $show_alert;

        $this->APIRequest("setMyCommands", $params);
    }

    /**
     * @return array
     */
    private function getBtc()
    {


        /*$ch = curl_init("https://apirone.com/api/v1/ticker?currency=btc");
        curl_setopt($ch, CURLOPT_HTTPHEADER, 'User-Agent: ' . getallheaders()['User-Agent']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXY, $this->proxylogin);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxypass);*/
        //curl_setopt($ch, CURLOPT_HEADER, 1);
        $session = $session = file_get_contents('https://apirone.com/api/v1/ticker?currency=btc');
        $Btc = json_decode($session, 1);
        return $Btc;//$Btc['RUB']['last']

    }


    private function SendAnswerCallbackQuery($callback_query_id, $text, $show_alert)
    {
        $params["callback_query_id"] = $callback_query_id;
        $params["text"] = $text;
        $params["show_alert"] = $show_alert;

        $this->APIRequest("answerCallbackQuery", $params);
    }

    /**
     * @param $text
     * @param $chat_id
     * @param null $keyboard
     */
    private function SendMessageAdmin($text, $chat_id, $keyboard = null)
    {
        $params["chat_id"] = $chat_id;
        $params["text"] = $text;
        $params["parse_mode"] = "HTML";
        $params["disable_web_page_preview"] = 'true';


        if (!empty($keyboard["keyboard"])) {
            $params["reply_markup"] = json_encode([
                "keyboard" => $keyboard["keyboard"],
                "resize_keyboard" => true
            ]);
        } else if ($keyboard) {
            foreach ($keyboard as $row) {
                foreach ($row as $title => $data)
                    $b[] = ["text" => $title, "callback_data" => $data];

                $rows[] = $b;

                $b = null;
            }

            $params["reply_markup"] = json_encode([
                "inline_keyboard" => $rows
            ]);
        }

        $this->APIRequest("sendMessage", $params);
    }


    /**
     * @param $photo_id
     * @param $caption
     * @param $chat_id
     * @param null $keyboard
     */
    private function sendPhoto($photo_id, $caption, $chat_id, $keyboard = null)
    {
        $params["chat_id"] = $chat_id;
        $params["photo"] = $photo_id;
        $params["caption"] = $caption;
        $params["parse_mode"] = "markdown";

        if (!empty($keyboard["keyboard"])) {
            $params["reply_markup"] = json_encode([
                "keyboard" => $keyboard["keyboard"],
                "resize_keyboard" => true
            ]);
        } else if ($keyboard) {
            foreach ($keyboard as $row) {
                foreach ($row as $title => $data)
                    $b[] = ["text" => $title, "callback_data" => $data];

                $rows[] = $b;

                $b = null;
            }

            $params["reply_markup"] = json_encode([
                "inline_keyboard" => $rows
            ]);
        }

        $this->APIRequest("sendPhoto", $params);
    }

    /**
     * @param $chat_id
     * @param $from_chat_id
     * @param $message_id
     */
    private function forwardMessage($chat_id, $from_chat_id, $message_id)
    {
        $params["chat_id"] = $chat_id;
        $params["from_chat_id"] = $from_chat_id;
        $params["message_id"] = $message_id;


        $this->APIRequest("forwardMessage", $params);
    }

    /**
     * @param $message_id
     * @param $chat_id
     * @param null $keyboard
     */
    private function deleteMessage($message_id, $chat_id, $keyboard = null)
    {
        $params["chat_id"] = $chat_id;
        $params["message_id"] = $message_id;
        $params["parse_mode"] = "markdown";

        if (!empty($keyboard["keyboard"])) {
            $params["reply_markup"] = json_encode([
                "keyboard" => $keyboard["keyboard"],
                "resize_keyboard" => true
            ]);
        } else if ($keyboard) {
            foreach ($keyboard as $row) {
                foreach ($row as $title => $data)
                    $b[] = ["text" => $title, "callback_data" => $data];

                $rows[] = $b;

                $b = null;
            }

            $params["reply_markup"] = json_encode([
                "inline_keyboard" => $rows
            ]);
        }

        $this->APIRequest("deleteMessage", $params);
    }

    /**
     * @param $stick_id
     * @param $chat_id
     * @param null $keyboard
     */
    private function SendSticker($stick_id, $chat_id, $keyboard = null)
    {
        $params["chat_id"] = $chat_id;
        $params["sticker"] = $stick_id;
        $params["parse_mode"] = "markdown";

        if (!empty($keyboard["keyboard"])) {
            $params["reply_markup"] = json_encode([
                "keyboard" => $keyboard["keyboard"],
                "resize_keyboard" => true
            ]);
        } else if ($keyboard) {
            foreach ($keyboard as $row) {
                foreach ($row as $title => $data)
                    $b[] = ["text" => $title, "callback_data" => $data];

                $rows[] = $b;

                $b = null;
            }

            $params["reply_markup"] = json_encode([
                "inline_keyboard" => $rows
            ]);
        }

        $this->APIRequest("sendSticker", $params);
    }

    /**
     * @param $text
     * @param $chat_id
     * @param null $keyboard
     */
    private function editMessage($text,$chat_id , $keyboard = null)
    {
        $params["chat_id"] = $chat_id;
        $params["message_id"] = $this->messageId;
        $params["text"] = $text;
        $params["parse_mode"] = "HTML";
        $params["disable_web_page_preview"] = 'true';
        if (!empty($keyboard["keyboard"])) {
            $params["reply_markup"] = json_encode([
                "keyboard" => $keyboard["keyboard"],
                "resize_keyboard" => true
            ]);
        } else if ($keyboard) {
            foreach ($keyboard as $row) {
                foreach ($row as $title => $data)
                    $b[] = ["text" => $title, "callback_data" => $data];

                $rows[] = $b;

                $b = null;
            }

            $params["reply_markup"] = json_encode([
                "inline_keyboard" => $rows
            ]);
        }

        $result = $this->APIRequest("editMessageText", $params);
    }
    private function getLinks($type)
    {
        $result = $this->database->GetLinks($this->userId,0,$type);
        $links = $result["links"];

        $keyboard = [];

        foreach ($links as $link) {


            $keyboard[][$link->title . ' | ' . '💵 ' . $link->price . ' | ' . $this->getTypeLink($link->type) . ' ' . $link->type] = "editLink|" . $link->id . "|0";
        }


        $i = count($keyboard);

        if (count($keyboard) >= 8)
            $keyboard[$i][">>"] = "getLinks|1|".$type;

        return $this->SendMessage($this->locale['pages1'] . $result["pages"], $keyboard);
    }

    /**
     * @param $method
     * @param $params
     * @return bool|string
     */
    private function APIRequest($method, $params)
    {
        $ch = curl_init("https://api.telegram.org/bot" . $this->botToken . "/" . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }


}

$request = file_get_contents("php://input");
file_put_contents('logfile.txt', print_r(json_decode($request, 1), 1));
file_put_contents('logfile1.txt', print_r($_POST, 1));
$bot = new LinksBot($request);
$bot->Handle();
http_response_code(200);

?>