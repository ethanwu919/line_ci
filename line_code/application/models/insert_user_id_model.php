<?php
class insert_user_id_model extends CI_Model {
    function __construct() { // 建構值
        parent::__construct (); 
        {
            $this->load->database (); // 載入database資料庫設定
        }
    }
 
    function insert_user_id($user_id) {  
        error_log(__CLASS__ . '::' . __FUNCTION__ ."\n", 3, "application/debug.log");
        $sql="INSERT INTO  line_user (line_user_id)  VALUES " . " (" ."'".$user_id."'".") ";
        error_log(__CLASS__ . '::' . __FUNCTION__ ."sql = ".print_r($sql,1)."\n", 3, "application/debug.log");
        $result=$this->db->query($sql); 
        error_log(__CLASS__ . '::' . __FUNCTION__ ." result = ".print_r($result,1)."\n", 3, "application/debug.log");    
        if($result==1){
            error_log(__CLASS__ . '::' . __FUNCTION__ ."客戶資料insert成功"."\n", 3, "application/debug.log");    
        }else{
            error_log(__CLASS__ . '::' . __FUNCTION__ ."客戶資料insert 失敗". " SQL = ".ptint($sql)."\n", 3, "application/debug.log");   
        }

    }
}