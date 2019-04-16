<?php
/**
 * Created by inFluxfin.
 * Developer: Hsiang Wu
 * Date: 2019-04-15
 * 與LINE API結合
 * 存取資料庫訊息
 * 
 */

Class Line_push_reply extends CI_Controller
{

    public function index()
    {
      error_log(__CLASS__ . '::' . __FUNCTION__ . ' # API Start ' ."\n", 3, "application/debug.log");
      $accessToken = 'PEVQ6hVQ2jr/awPqheuItFYjFC6bViubXX6EVVse1FqeCrzGPmg+dybQyEz6iLAbsANSn2iu+rckkq9ffKUc9XyM8bt0h355eRj1tM9gsBI+GZzXFeA7opyFrUPr87mr7cFremw7aX+9gElhJH+nugdB04t89/1O/w1cDnyilFU=';
       // $userid= $this->get_userid();
      
        //取得user丟過來的訊息
        $jsonString = file_get_contents('php://input');
        //轉成JSON
        $jsonArry = json_decode($jsonString,1);
        error_log(__CLASS__ . '::' . __FUNCTION__ . ' json== ' .print_r($jsonArry,1)."\n", 3, "application/debug.log");
        $user_id=$jsonArry['events'][0]['source']['userId'];
        $replyToken=$jsonArry['events'][0]['replyToken'];
        error_log(__CLASS__ . '::' . __FUNCTION__ . ' jsonArry 內 user id== ' .print_r($user_id,1)."\n", 3, "application/debug.log");
        $one=$jsonArry['events'][0]['message']['text'];
        if($one==1)
        {
        //先檢查是否有重複的登記userID  
        $check= $this->check_user_id($user_id); 
        if($check==1)//需要insert 2:不需要insert
          {
            $this->insert_user_id($user_id);
            $this->reply_line_msg($replyToken,$accessToken);
          }
        }elseif($one==2)
        {
             $this->reply_line_msg_2($replyToken,$accessToken);
        }else{
            //將使用者發送訊息記錄在資料庫
            $this->insert_user_log($user_id,$one);
        }
        
    }
     
     public function insert_user_id($user_id) {
        error_log(__CLASS__ . '::' . __FUNCTION__ .' user_id ='.$user_id."\n", 3, "application/debug.log");
        $this->load->model('insert_user_id_model'); // 載入model
        $this->insert_user_id_model->insert_user_id($user_id);
    }
    
    public function insert_user_log($user_id,$msg) {
        error_log(__CLASS__ . '::' . __FUNCTION__ .' user_id ='.$user_id."\n", 3, "application/debug.log");
        error_log(__CLASS__ . '::' . __FUNCTION__ .' msg ='.$msg."\n", 3, "application/debug.log");
        $this->load->model('insert_user_log_model'); // 載入model
        $this->insert_user_log_model->insert_user_log($user_id,$msg);
    }
 
 
    public function check_user_id($user_id) {
        error_log(__CLASS__ . '::' . __FUNCTION__ .' user_id ='.$user_id."\n", 3, "application/debug.log");
        $this->load->model('check_user_id_model'); // 載入model
        $check= $this->check_user_id_model->check_user_id($user_id);
       return $check;

    }
    
    public function reply_line_msg($replyToken,$accessToken) {
        error_log(__CLASS__ . '::' . __FUNCTION__ ."\n", 3, "application/debug.log");

        //回覆的訊息,replyToken
        $postData = [
            "replyToken" => $replyToken,
            "messages" => [
                [
                    "type" => "text",
                    "text" => "恭喜成為本公司好友 請按數字2   可以接收本公司最新優惠消息"
                ]
            ]
        ];

        error_log(__CLASS__ . '::' . __FUNCTION__ ."postData= ".print_r( $postData,1)."\n", 3, "application/debug.log");

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

    public function reply_line_msg_2($replyToken,$accessToken) {
        error_log(__CLASS__ . '::' . __FUNCTION__ ."\n", 3, "application/debug.log");

        //回覆的訊息,replyToken
        $postData = [
            "replyToken" => $replyToken,
            "messages" => [
                [
                    "type" => "text",
                    "text" => "目前優惠產品：ＸＸＸＸＸ"
                ]
            ]
        ];

        error_log(__CLASS__ . '::' . __FUNCTION__ ."postData= ".print_r( $postData,1)."\n", 3, "application/debug.log");

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