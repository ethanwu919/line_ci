<?php

Class LineBot extends CI_Controller
{

    public function index()
    {
        //從Line Developer上申請的Token
        $accessToken = 'na/4Pzt9Q1xWprGEneEamXRtemwbZ7bG2+gMP60DnTMq44MbXbhApDT2XlRMdfKnC8oLKvFwya8D1/PwQSqxKya9B75Q8pLQRXlKIW8Pyj+rmurvywTYA+xIMWdwPFcfGRFEvrMIROxEwGpi11UpDAdB04t89/1O/w1cDnyilFU=';

        //取得機器人丟過來的訊息
        $jsonString = file_get_contents('php://input');
        //轉成JSON
        $jsonObj = json_decode($jsonString);

        //設定變數給JSON的各欄位
        $event = $jsonObj->{"events"}[0];
        $type = $event->{"message"}->{"type"};
        $message = $event->{"message"};
        $replyToken = $event->{"replyToken"};

        //回覆的訊息,replyToken
        $postData = [
            "replyToken" => $replyToken,
            "messages" => [
                [
                    "type" => "text",
                    "text" => 'test hsiang'
                ]
            ]
        ];

         //post url init
        $ch = curl_init("https://api.line.me/v2/bot/message/reply");

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
            //'Authorization: Bearer '. TOKEN
        ));

        curl_exec($ch);
        curl_close($ch);
    }
}