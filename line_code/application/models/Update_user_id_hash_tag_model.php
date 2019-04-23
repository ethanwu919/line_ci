<?php
class Update_user_id_hash_tag_model extends CI_Model {
    function __construct() { // 建構值
        parent::__construct (); 
        {
            $this->load->database (); // 載入database資料庫設定
        }
    }
 
    function update_hash_tag($user_id) {  
       //id先寫死 1:高雄活動
        $sql="UPDATE line_user
        SET hash_tag = 1
        WHERE line_user_id= " ."'". "$user_id"."'";
        error_log(__CLASS__ . '::' . __FUNCTION__ ."sql = ".print_r($sql,1)."\n", 3, "application/debug.log");
        $result=$this->db->query($sql); 
        error_log(__CLASS__ . '::' . __FUNCTION__ ." result = ".print_r($result,1)."\n", 3, "application/debug.log");    
        if($result==1){
            error_log(__CLASS__ . '::' . __FUNCTION__ ."客戶 活動資料 insert   成功"."\n", 3, "application/debug.log");    
        }else{
            error_log(__CLASS__ . '::' . __FUNCTION__ ."客戶 活動資料 insert    失敗". " SQL = ".ptint($sql)."\n", 3, "application/debug.log");   
        }
 
    }
}