<?php

    require_once 'bootstrap.php';

    $start = microtime(true);


    $host = '192.168.1.2';
    $port = '23';
    $user = 'Admin';
    $password = 'CP_SMS753';
    $server = new \SmsSender\TelnetServer($host, $user, $password, $port);

    $sender = SmsSender\SmsSender::buildInstance($server);


    $message = str_repeat('a', 161);
    $phoneNumber = '724355315';

    $smses = \SmsSender\Sms::make($phoneNumber, $message);

    var_dump($sender->sendSmses($smses));

    echo "Took:" . (microtime(true) - $start);