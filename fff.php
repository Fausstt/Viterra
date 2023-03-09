<?php
// exit(1);
$fname = ($_POST['FName']) ? $_POST['FName'] : 'test'; // имя
$lname = ($_POST['LName']) ? $_POST['LName'] : 'test'; // фамилия
$fullphone = ($_POST['Phone']) ? $_POST['Phone'] : '1111111111'; // нелефон
$email = ($_POST['Email']) ? $_POST['Email'] : 'test@gmail.com'; // емаил
$ip = ($_SERVER['REMOTE_ADDR'] == '::1') ? '173.176.183.21' : $_SERVER['REMOTE_ADDR']; // ip пользователя
$domain = $_SERVER['SERVER_NAME']; // название домена
$ipCR = 'test'; //  id страны
$coun = 'test'; //  страна пользователя
$sours = 'Zerno'; //  название проекта
$num_rows = ''; // количество строк
$lids_today = ''; // лидов за сегодня
$re_entry = true;
$scl = mysqli_connect('fdmarket.mysql.tools', 'fdmarket_db', 'XAn8mJ68rF6z', 'fdmarket_db'); // вход в БД
$tg_bot_token = '5987793418:AAH7O8AjmcmD29ig0H-K31jjzXAfwrjo6c4'; // Токен телеграм
$chat_id = '-1001541410143'; // ID чата телеграм
//////////////////////////////////////////////////////////////////////
// проверка gmail и phone на валидность
$post = mysqli_query($scl, "SELECT count(*) AS count FROM lids WHERE Phone = '+$fullphone'");
$phone_valid = mysqli_fetch_assoc($post);
$phone_valid = $phone_valid['count'];
$post = mysqli_query($scl, "SELECT count(*) AS count FROM lids WHERE Email = '$email'");
$email_valid = mysqli_fetch_assoc($post);
$email_valid = $email_valid['count'];
if ($phone_valid || $email_valid) {
    // получение $ipCR
    $ip_data = file_get_contents("http://api.sypexgeo.net/json/" . $ip);
    $ip_data = json_decode($ip_data);
    foreach ($ip_data as $i => $vl) {
        if ($i === 'country') {
            foreach ($vl as $d => $va) {
                if ($d === 'id') {
                    $ipCR = $va;
                }
            }
        }
    }
    // получение $coun
    foreach ($ip_data as $ic => $vla) {
        if ($ic === 'country') {
            foreach ($vla as $di => $vda) {
                if ($di === 'name_en') {
                    $coun = $vda;
                }
            }
        }
    }
    // Телеграм бот на повтор
    if ($phone_valid) {
        $post = mysqli_query($scl, "SELECT * FROM `lids` WHERE `Phone` = '+$fullphone'");
        $date_reg = mysqli_fetch_assoc($post);
        $date_reg = json_encode($date_reg['Data']);
    } else {
        $post = mysqli_query($scl, "SELECT * FROM `lids` WHERE `Email` = '$email'");
        $date_reg = mysqli_fetch_assoc($post);
        $date_reg = json_encode($date_reg['Data']);
    }
    // Составление сообщения
    $text = '';
    $text .= "\n" . '🟡Повторный Lid🟡';
    $text .= "\n" . 'Источник: ' . $sours;
    $text .= "\n" . 'Fast Name: ' . $fname;
    $text .= "\n" . 'Last Name: ' . $lname;
    $text .= "\n" . 'Email: ' . $email;
    $text .= "\n" . 'Phone: +' . $fullphone;
    $text .= "\n" . 'country: ' . $coun;
    $text .= "\n" . 'ip: ' . $_SERVER['REMOTE_ADDR'];
    $text .= "\n" . 'Data: ' . date('d.m.y H:i:s', strtotime('+2hour'));
    $text .= "\n" . 'Дата первой регистрации: ' . $date_reg;
    // 
    $param = [
        "chat_id" => $chat_id,
        "text" => $text
    ];
    $url = "https://api.telegram.org/bot" . $tg_bot_token . "/sendMessage?" . http_build_query($param);
    file_get_contents($url);
    $url = "https://api.telegram.org/bot" . $tg_bot_token . "/sendDocument";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ["chat_id" => $chat_id]);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $out = curl_exec($ch);
    curl_close($ch);
    exit(false);
}
// получение $ipCR
$ip_data = file_get_contents("http://api.sypexgeo.net/json/" . $ip);
$ip_data = json_decode($ip_data);
foreach ($ip_data as $i => $vl) {
    if ($i === 'country') {
        foreach ($vl as $d => $va) {
            if ($d === 'id') {
                $ipCR = $va;
            }
        }
    }
}
// получение $coun
foreach ($ip_data as $ic => $vla) {
    if ($ic === 'country') {
        foreach ($vla as $di => $vda) {
            if ($di === 'name_en') {
                $coun = $vda;
            }
        }
    }
}
$ip_data = json_encode($ip_data);
// получение $num_rows
$post = mysqli_query($scl, "SELECT * FROM lids");
$num_rows = mysqli_num_rows($post);
$num_rows = $num_rows + 1;
// получение количество лидов за сегодня
$dat = date('Y-m-d', strtotime('+2hour'));
$post = mysqli_query($scl, "SELECT count(*) AS count FROM lids WHERE Data LIKE '%$dat%'");
$lids_today = mysqli_fetch_assoc($post);
$lids_today = $lids_today['count'] + 1;

// получение токена от БД лиама
$addToken = array(
    'username' => "MetaLive",
    'password' => "HvoGeZ0574aL"
);
$addToken = json_encode($addToken);
$url = 'https://api.alphatech.proftit.com/api/user/v3/tokens';
$header = array();
$header[] = 'Accept: application/json';
$header[] = 'Content-Type: application/json';
$header[] = 'cache-control: no-cache';
$crl = curl_init();
curl_setopt($crl, CURLOPT_URL, $url);
curl_setopt($crl, CURLOPT_HTTPHEADER, $header);
curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($crl, CURLOPT_POST, true);
curl_setopt($crl, CURLOPT_POSTFIELDS, $addToken);
curl_setopt($crl, CURLOPT_INTERFACE, '185.233.116.51');
$rest = curl_exec($crl);
$rest = json_decode($rest);
$token = false;
if ($rest) {
    foreach ($rest as $val) {
        if (gettype($val) == 'string') {
            if (strlen($val) > 60) {
                $token = $val;
            }
        }
    };
}
curl_close($crl);
// отправка в БД лама
$apiData = array(
    "isLead" => true,
    "firstName" => $fname,
    "lastName" => $lname,
    "email" => $email,
    "phone" => $fullphone,
    "password" => "a1234",
    "brandId" => "1",
    "countryId" => $ipCR,
    "campaignId" => 1005,
    "productName" => $sours,
    "marketingInfo" => "additionalFunnelInfo",
);
$apiData = json_encode($apiData);
$url = 'https://api.alphatech.proftit.com/api/user/v3/customers';
$header = array();
$header[] = 'Accept: application/json';
$header[] = 'Content-Type: application/json';
$header[] = 'cache-control: no-cache,no-cache';
$header[] = 'authorization:' . $token;
$crl = curl_init();
curl_setopt($crl, CURLOPT_URL, $url);
curl_setopt($crl, CURLOPT_HTTPHEADER, $header);
curl_setopt($crl, CURLOPT_VERBOSE, 0);
curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($crl, CURLOPT_POST, true);
curl_setopt($crl, CURLOPT_POSTFIELDS, $apiData);
curl_setopt($crl, CURLOPT_INTERFACE, '185.233.116.51');
$rest = curl_exec($crl);
curl_close($crl);
// Телеграм бот
// Составление сообщения
$text = '';
if ($fname == 'test') {
    $text .= "\n" . '🔵Тестовй лид:🔵';
} else {
    $text .= "\n" . '🟢Новый лид:🟢 №' . $num_rows;
}
$text .= "\n" . 'Источник: ' . $sours;
$text .= "\n" . 'Fast Name: ' . $fname;
$text .= "\n" . 'Last Name: ' . $lname;
$text .= "\n" . 'Email: ' . $email;
$text .= "\n" . 'Phone: +' . $fullphone;
$text .= "\n" . 'country: ' . $coun;
$text .= "\n" . 'ip: ' . $_SERVER['REMOTE_ADDR'];
$text .= "\n" . 'Data: ' . date('d.m.y H:i:s', strtotime('+2hour'));
// 
$param = [
    "chat_id" => $chat_id,
    "text" => $text
];
$url = "https://api.telegram.org/bot" . $tg_bot_token . "/sendMessage?" . http_build_query($param);
file_get_contents($url);
$url = "https://api.telegram.org/bot" . $tg_bot_token . "/sendDocument";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, ["chat_id" => $chat_id]);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$out = curl_exec($ch);
curl_close($ch);

// Запись в БД
if ($fname == 'test') {
    $post = mysqli_query($scl, "INSERT INTO `Test` (`FName`, `LName`, `Email`, `Phone`, `ip`, `Country`, `source`) VALUES('$fname', '$lname', '$email', '+$fullphone', '$ip', '$coun', '$sours')");
} else {
    $post = mysqli_query($scl, "INSERT INTO `lids` (`FName`, `LName`, `Email`, `Phone`, `ip`, `Country`, `source`) VALUES('$fname', '$lname', '$email', '+$fullphone', '$ip', '$coun', '$sours')");
}
$scl = mysqli_close($scl);
exit(true);
