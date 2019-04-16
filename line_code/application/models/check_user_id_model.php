<?php
class check_user_id_model extends CI_Model {
    function __construct() { // 建構值
        parent::__construct (); 
        {
            $this->load->database(); // 載入database資料庫設定
        }
    }
 
    function check_user_id($user_id) {  
        error_log(__CLASS__ . '::' . __FUNCTION__ ."\n", 3, "application/debug.log");
        $this->db->select('line_user_id')->from('line_user')->where('line_user_id',$user_id);
        $query = $this->db->get();
        $res=$query->result();
        $object =  json_decode( json_encode($res),true);//obj 轉arr
        error_log(__CLASS__ . '::' . __FUNCTION__ ." array  = ".print_r($object[0]['line_user_id'],1)."\n", 3, "application/debug.log");
        $result=$object[0]['line_user_id'];
        if(empty($result)){
            error_log(__CLASS__ . '::' . __FUNCTION__ ." 查無此用戶資料 需要insert , user_id = ".$user_id."\n", 3, "application/debug.log");
           return 1 ;
        }else{
            error_log(__CLASS__ . '::' . __FUNCTION__ ." 已有此用戶資料 不需要insert , user_id = ".$user_id."\n", 3, "application/debug.log");
            return 2;
        }

    }
}