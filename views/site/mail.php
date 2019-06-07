<?php

use app\models\Settings;
use PhpImap\Exceptions\InvalidParameterException;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/** Используется библиотека PHP Imap https://github.com/barbushin/php-imap */

$this->title = 'Получение почты'
?>

<div class="row">
    <?php
    $username = Settings::getValueByKey('login_yandex') . '@yandex.ru';
    $password = Settings::getValueByKey('password_yandex');
    $mail_server = 'imap.yandex.ru';
    $mail_dir = Url::to('@app/mail/imap');
    $mail_port = 993;
    $mail_imap_path = '{' . $mail_server . ':' . $mail_port . '/imap/ssl}INBOX';

    try {
        $mailbox = new PhpImap\Mailbox($mail_imap_path, $username, $password, $mail_dir);
    } catch (InvalidParameterException $e) {
        /** @noinspection PhpUnhandledExceptionInspection */
        throw new Exception($e->getMessage());
    }
    $mailbox->getImapStream();
    $mail_ids = [];
//    $mail_ids = $mailbox->searchMailbox('ALL'); //Все сообщения
//    $mail_ids = $mailbox->searchMailbox('UNSEEN'); //Не прочитанные
    //Сообщения за 3 дня.
    //    $mail_ids = $mailbox->searchMailBox('SINCE "'.date('d-M-Y',strtotime("-3 day")).'"');
    //Поиск сообщений с таким соответствием в заголовке TEXT. Русские буквы не ищет (на яндекс почте)
    //    $mail_ids = $mailbox->searchMailBox('TEXT "FWD"');
    //Поиск сообщений с таким соответствием в заголовке BODY.
    //    $mail_ids = $mailbox->searchMailBox('BODY "Информационное сообщение"');
    //Поиск по емейлу отправителя.
    //    $mail_ids = $mailbox->searchMailBox('FROM "noreply@passport.yandex.ru"');
    //Получить сообщения по заголовку SUBJECT
    //    $mail_ids = $mailbox->searchMailBox('SUBJECT "Выпущены обновления для вашего телефона"');
//    VarDumper::dump($mail_ids, 10, true);

    $mail_check = (array)$mailbox->checkMailbox();
    echo 'Новых сообщений: ';
    VarDumper::dump($mail_check['Recent'], 10, true);
    echo '<br>';

    $mail_ids = [163];
    foreach ($mail_ids as $mail_id) {
        //Получаем экземпляр объекта класса IncomingMail который содержит информацию о сообщении.
        $mail = $mailbox->getMail($mail_id);
        //Получаем файлы вложенные к данному сообщению если он есть.
        $attachments = $mail->getAttachments();

        VarDumper::dump($mail->date, 10, true);
        echo '<br>';
        VarDumper::dump($mail->to, 10, true);
        echo '<br>';
        echo 'Есть вложения: ';
        VarDumper::dump($mail->hasAttachments(), 10, true);
        echo '<br>';
        echo 'Вложения: ';
        foreach ($attachments as $key => $attachment){
            echo $key . ' ' .$attachment->name . ': ' . $attachment->filePath . '<br>';
//            VarDumper::dump($attachment->name, 10, true); //Имя вложения
//            VarDumper::dump($attachment->filePath, 10, true); //Путь к вложению

        }
        echo '<br> От: ';
        VarDumper::dump($mail->fromAddress, 10, true);
        echo '<br>Тело сообщения: ';
//Выводим сообщения.
        echo $mail->textHtml;
        echo '<br>=====================================================================<br>';
    }
    VarDumper::dump($mail_ids, 10, true);

    //    $id = 3;
    //    #Сохраняем сообщения по его ид:
    //    $mailbox->saveMail($id,$id.'.eml');
    //
    //    #Устанавливаем сообщения как непрочитанное по его id:
    //    $mailbox->markMailAsUnread($id);
    //
    //    #Устанавливаем сообщения как прочитанное по его id:
    //    $mailbox->markMailAsRead($id);
    //
    //    #Устанавливаем на сообщение пометку по его id:
    //    $mailbox->markMailAsImportant($id);
    //
    //    #Удаляем сообщения по его id:
    //    $mailbox->deleteMail($id);


    ?>
</div>
