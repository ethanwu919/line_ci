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
      $accessToken = 'uGxr+DL6t7mBqPoXDOoHjbifRdiVWyFvyZUEGeNHyLY6Rs55IJdoezAy/KoFKS+IUMDoE2T1s8RGZ9Qei46THxOTDbAQRCQsHUXTpTHiFKodRE4Igg2aMJ5OrWTqV9d4A/zJgt3UObl9nWKzSTSPrgdB04t89/1O/w1cDnyilFU=';
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

        //$this->push_line_msg($accessToken,$user_id);
        
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
                    "text" => "恭喜成為普匯小幫手line好友 請按數字2   直接接收本公司目前最新活動消息"
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
                    "type" => "image",
                    "originalContentUrl" => "https://lh6.googleusercontent.com/zuhErUOObcnReXnuGhWuCKg1Q_uJqGd4xTvwDJ2VUOpIdfMzM1TAtlKpApRHgPUjtL6LvooEWw=w1488",
                    "previewImageUrl" => "https://www.pu-hey.com/wp-content/uploads/2019/04/%E9%AB%98%E6%87%89%E5%A4%A7%E6%BC%94%E8%AC%9BDM-370x230.jpg",
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

    public function lecture_event() {

        $accessToken = 'uGxr+DL6t7mBqPoXDOoHjbifRdiVWyFvyZUEGeNHyLY6Rs55IJdoezAy/KoFKS+IUMDoE2T1s8RGZ9Qei46THxOTDbAQRCQsHUXTpTHiFKodRE4Igg2aMJ5OrWTqV9d4A/zJgt3UObl9nWKzSTSPrgdB04t89/1O/w1cDnyilFU=';
       

       $jsonString = file_get_contents('php://input');
       //轉成JSON
       $jsonObj = json_decode($jsonString);
        error_log(__CLASS__ . '::' . __FUNCTION__ . ' jsonObj== ' .print_r($jsonObj,1)."\n", 3, "application/debug.log");


       $userIds='U2de07b3c0cc2ce2371e5b0a63741fba6';
       
        $payload = [
            'to' => $userIds, //要推給誰
            'messages' => array(
                array(
                    'type' => 'template', // 訊息類型 (模板)
                    'altText' => 'Example confirm template', // 替代文字
                    'template' => array(
                        'type' => 'confirm', // 類型 (確認)
                        'text' => '是否有參與今日 4/25（四）擁抱AI，創新金融科技座談會 ?', // 文字
                        'actions' => array(
                            array(
                                'type' => 'message', // 類型 (訊息)
                                'label' => 'Yes', // 標籤 1
                                'text' => 'Yes' // 用戶發送文字 1
                            ),
                            array(
                                'type' => 'message', // 類型 (訊息)
                                'label' => 'No', // 標籤 2
                                'text' => 'No' // 用戶發送文字 2
                            )
                        )
                    )
                )
            )
         ];    
        error_log(__CLASS__ . '::' . __FUNCTION__ . ' payload ' .print_r($payload,1)."\n", 3, "application/debug.log");

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
        'Authorization: Bearer ' . $accessToken
        ]);
        $result = curl_exec($ch);
        error_log(__CLASS__ . '::' . __FUNCTION__ . ' result = ' .print_r($result,1)."\n", 3, "application/debug.log");

        curl_close($ch);

    
    }


 
}