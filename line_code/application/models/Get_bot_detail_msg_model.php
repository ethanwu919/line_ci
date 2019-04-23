<?php
class Get_bot_detail_msg_model extends CI_Model {
    function __construct() { // 建構值
        parent::__construct (); 
        {
            $this->load->database(); // 載入database資料庫設定
        }
    }
 
    function get_msg($id) {  
        $this->db->select('msg')->from('bot_detail')->where('id',$id);
        $query = $this->db->get();
        $res=$query->result();
        $res =  json_decode( json_encode($res),true);//obj 轉arr
 
        $res = $res[0]['msg'];
        error_log(__CLASS__ . '::' . __FUNCTION__ ." res  = ".print_r($res,1)."\n", 3, "application/debug.log");

       return   $res;

    }
}