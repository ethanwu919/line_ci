<?php
class Check_hash_tag_group_model extends CI_Model {
    function __construct() { // 建構值
        parent::__construct (); 
        {
            $this->load->database(); // 載入database資料庫設定
        }
    }
 
    function check_date($today) {  
        $this->db->select('id,bot_detail_id,start_at,end_at')->from('hash_tag_group');
        $query = $this->db->get();
        $res=$query->result();
        $obj =  json_decode( json_encode($res),true);//obj 轉arr
        error_log(__CLASS__ . '::' . __FUNCTION__ ." array  = ".print_r($obj,1)."\n", 3, "application/debug.log");
       return   $obj;

    }
}