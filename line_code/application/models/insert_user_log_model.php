<?php
class insert_user_log_model extends CI_Model {
    function __construct() { // 建構值
        parent::__construct (); 
        {
            $this->load->database (); // 載入database資料庫設定
        }
    }
 
    function insert_user_log($user_id,$msg) {  
      
        $sql="INSERT INTO  line_user_log (line_user_id,msg)  VALUES " . " (" ."'".$user_id."'".",". "'".$msg."'". ") ";
        error_log(__CLASS__ . '::' . __FUNCTION__ ."sql = ".print_r($sql,1)."\n", 3, "application/debug.log");
        $result=$this->db->query($sql); 
        error_log(__CLASS__ . '::' . __FUNCTION__ ." result = ".print_r($result,1)."\n", 3, "application/debug.log");    
        if($result==1){
            error_log(__CLASS__ . '::' . __FUNCTION__ ."客戶資料insert log 成功"."\n", 3, "application/debug.log");    
        }else{
            error_log(__CLASS__ . '::' . __FUNCTION__ ."客戶資料insert  log 失敗". " SQL = ".ptint($sql)."\n", 3, "application/debug.log");   
        }
 
    }
}