<?php

Class Linepush extends CI_Controller
{

    public function index()
    {

        error_log(__CLASS__ . '::' . __FUNCTION__ . ' hsiang   == ' ."\n", 3, "application/debug.log");

        //從Line Developer上申請的Token
        $accessToken = 'nj24/TxPds394e/2cS0ISdWp6KHgtBOpKRkQjFYX2eDjtknNPnUO6MTb4AeZSbOMhFjWeXaT32Zzx44x05s77BaHfbxaKf6i4nkx9for8KIRLDZ6coFWnOZpebtuwmfbjCTGQsaVnhOO06QWYhpKUAdB04t89/1O/w1cDnyilFU=';

        $channelAccessToken = 'nj24/TxPds394e/2cS0ISdWp6KHgtBOpKRkQjFYX2eDjtknNPnUO6MTb4AeZSbOMhFjWeXaT32Zzx44x05s77BaHfbxaKf6i4nkx9for8KIRLDZ6coFWnOZpebtuwmfbjCTGQsaVnhOO06QWYhpKUAdB04t89/1O/w1cDnyilFU=';
$message = isset($argv[1]) ? $argv[1] : 'Hello!';
$dbFilePath = __DIR__ . '/line-db.json';  // user info database file path
error_log(__CLASS__ . '::' . __FUNCTION__ . ' dbFilePath ' .print_r($dbFilePath,1)."\n", 3, "application/debug.log");

// open json database
// if (!file_exists($dbFilePath)) {
//    file_put_contents($dbFilePath, json_encode(['user' => []]));
// }
// $db = json_decode(file_get_contents($dbFilePath), true);
 
// if (count($db['user']) === 0) {
//     error_log(__CLASS__ . '::' . __FUNCTION__ . ' No user login ' ."\n", 3, "application/debug.log");

//    echo 'No user login.';
//    exit(1);
// } else {
//    foreach ($db['user'] as &$userInfo) {
//        $userIds[] = $userInfo['userId'];
//        error_log(__CLASS__ . '::' . __FUNCTION__ . ' userIds ='. print_r($userIds,1) ."\n", 3, "application/debug.log");
//    }
// }
 
// make payload

$userIds='U9e5d5a962e7aad007bf9754a59424813';
$payload = [
   'to' => $userIds,
   'messages' => [
       [
           'type' => 'text',
           'text' => $message
       ]
   ]
];
error_log(__CLASS__ . '::' . __FUNCTION__ . ' payload ' .print_r($payload,1)."\n", 3, "application/debug.log");


// Send Request by CURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/v2/bot/message/push');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
   'Content-Type: application/json',
   'Authorization: Bearer ' . $channelAccessToken
]);
$result = curl_exec($ch);
error_log(__CLASS__ . '::' . __FUNCTION__ . ' result = ' .print_r($result,1)."\n", 3, "application/debug.log");

curl_close($ch);
}

}