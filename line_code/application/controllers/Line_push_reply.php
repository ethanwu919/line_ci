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
        $today = date("Y-m-d H:i:s");  //時間格式 2019-04-22 21:55:05
        error_log(__CLASS__ . '::' . __FUNCTION__ . ' today' .print_r($today,1)."\n", 3, "application/debug.log");
        
        if($one==9)//關鍵字回覆
        {
        //先檢查是否有重複的登記userID  
        $check= $this->check_user_id($user_id); 
        if($check==1)//1.需要insert 2:不需要insert
          { 
            $this->insert_user_id($user_id);
            $this->reply_line_msg($replyToken,$accessToken);
            
            //活動＋＋
            $this->check_hash_tag_group($today,$user_id);//check 加好友時當下有沒有活動
            //$this->lecture_event($user_id);
            //活動--
          }
        }else{
            //將使用者發送訊息記錄在資料庫
            $this->insert_user_log($user_id,$one);
        }

        //活動專屬功能＋＋
        if($one=='我有參加'){
            //待討論
            $this->update_user_id_hash_tag($user_id);
            
        }
        //活動專屬功能--
        

        //$this->push_line_msg($accessToken,$user_id);
        
    }
     
     public function insert_user_id($user_id) {
        error_log(__CLASS__ . '::' . __FUNCTION__ .' user_id ='.$user_id."\n", 3, "application/debug.log");
        $this->load->model('Insert_user_id_model'); // 載入model
        $this->Insert_user_id_model->insert_user_id($user_id);
    }
    

    public function update_user_id_hash_tag($user_id) {
        error_log(__CLASS__ . '::' . __FUNCTION__ .' user_id ='.$user_id."\n", 3, "application/debug.log");
        $this->load->model('Update_user_id_hash_tag_model'); // 載入model
        $this->Update_user_id_hash_tag_model->update_hash_tag($user_id);
    }


    public function insert_user_log($user_id,$msg) {
        error_log(__CLASS__ . '::' . __FUNCTION__ .' user_id ='.$user_id."\n", 3, "application/debug.log");
        error_log(__CLASS__ . '::' . __FUNCTION__ .' msg ='.$msg."\n", 3, "application/debug.log");
        $this->load->model('insert_user_log_model'); // 載入model
        $this->insert_user_log_model->insert_user_log($user_id,$msg);
    }
 
 
    public function check_user_id($user_id) {
        error_log(__CLASS__ . '::' . __FUNCTION__ .' user_id = '.$user_id."\n", 3, "application/debug.log");
        $this->load->model('Check_user_id_model'); // 載入model
        $check= $this->Check_user_id_model->check_user_id($user_id);
       return $check;

    }

    public function get_bot_detail_msg($bot_detail_id) {
        error_log(__CLASS__ . '::' . __FUNCTION__ .' bot_detail_id = '.$bot_detail_id."\n", 3, "application/debug.log");
        $this->load->model('Get_bot_detail_msg_model'); // 載入model
        $msg= $this->Get_bot_detail_msg_model->get_msg($bot_detail_id);
       return $msg;

    }

    public function check_hash_tag_group($today,$user_id) {
        error_log(__CLASS__ . '::' . __FUNCTION__ .' today = '.$today."\n", 3, "application/debug.log");
        $this->load->model('Check_hash_tag_group_model'); // 載入model
        $check= $this->Check_hash_tag_group_model->check_date($today);
        error_log(__CLASS__ . '::' . __FUNCTION__ ." check = ".print_r($check,1)."\n", 3, "application/debug.log");
        foreach($check as $key => $value){
            if((strtotime($check[$key]['start_at']) <=strtotime($today))  &&  (strtotime($check[$key]['end_at'])>=strtotime($today))){
              //如果當下時間（加好友）時為活動區間 則發活動資訊給用戶
              error_log(__CLASS__ . '::' . __FUNCTION__ ." 準備發送bot msg 給用戶 "."\n", 3, "application/debug.log");
              $bot_detail_id=$check[$key]['bot_detail_id'];
              $this->send_bot_detail_msg($user_id,$bot_detail_id);          
            }

          
       }
    }


     public function reply_line_msg($replyToken,$accessToken) {
        error_log(__CLASS__ . '::' . __FUNCTION__ ."\n", 3, "application/debug.log");

        //回覆的訊息,replyToken
        $postData = [
            "replyToken" => $replyToken,
            "messages" => [
                [
                    "type" => "text",
                    "text" => "恭喜成為普匯小幫手line好友 "
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
 



    //活動通知 ＋＋
    public function send_bot_detail_msg($user_id,$bot_detail_id) {
       $accessToken = 'uGxr+DL6t7mBqPoXDOoHjbifRdiVWyFvyZUEGeNHyLY6Rs55IJdoezAy/KoFKS+IUMDoE2T1s8RGZ9Qei46THxOTDbAQRCQsHUXTpTHiFKodRE4Igg2aMJ5OrWTqV9d4A/zJgt3UObl9nWKzSTSPrgdB04t89/1O/w1cDnyilFU=';
       $jsonString = file_get_contents('php://input');
       //轉成JSON
       $jsonObj = json_decode($jsonString);
        error_log(__CLASS__ . '::' . __FUNCTION__ . ' jsonObj== ' .print_r($jsonObj,1)."\n", 3, "application/debug.log");
       //抓要傳送的資訊
       $text=$this->get_bot_detail_msg($bot_detail_id);
       error_log(__CLASS__ . '::' . __FUNCTION__ . ' text== ' .print_r($text,1)."\n", 3, "application/debug.log");
  
        
        $payload = [
            'to' => $user_id, //要推給誰
            'messages' => array(
                array(
                    'type' => 'template', // 訊息類型 (模板)
                    'altText' => '活動通知', // 替代文字
                    'template' => array(
                        'type' => 'confirm', // 類型 (確認)
                        'text' => $text, // 文字
                        'actions' => array(
                            array(
                                'type' => 'message', // 類型 (訊息)
                                'label' => 'Yes', // 標籤 1
                                'text' => '我有參加' // 用戶發送文字 1
                            ),
                            array(
                                'type' => 'message', // 類型 (訊息)
                                'label' => 'No', // 標籤 2
                                'text' => '沒參加' // 用戶發送文字 2
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
    //活動通知 --

    public function push_test_video() {

        $accessToken = 'uGxr+DL6t7mBqPoXDOoHjbifRdiVWyFvyZUEGeNHyLY6Rs55IJdoezAy/KoFKS+IUMDoE2T1s8RGZ9Qei46THxOTDbAQRCQsHUXTpTHiFKodRE4Igg2aMJ5OrWTqV9d4A/zJgt3UObl9nWKzSTSPrgdB04t89/1O/w1cDnyilFU=';
       

       $jsonString = file_get_contents('php://input');
       //轉成JSON
       $jsonObj = json_decode($jsonString);
        error_log(__CLASS__ . '::' . __FUNCTION__ . ' jsonObj== ' .print_r($jsonObj,1)."\n", 3, "application/debug.log");


       $userIds='U2de07b3c0cc2ce2371e5b0a63741fba6';
       
        $payload = [
            'to' => $userIds, //要推給誰
            'messages' => [
                    [
                        "type" => "video",
                        "originalContentUrl" => "https://api.reh.tw/line/bot/example/assets/videos/example.mp4",
                        "previewImageUrl" => "https://www.pu-hey.com/wp-content/uploads/2018/12/Men-01-3-270x200.png"
                    ]
                ]
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