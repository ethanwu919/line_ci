<?php
class getsqlmod extends CI_Model {
    function __construct() { // 建構值
        parent::__construct (); // 改寫父親
        {
            $this->load->database (); // 載入database資料庫設定
        }
    }
    function getdata() { // getdata副程式
        $query = $this->db->get ( 'name.name' ); // 抓取資料庫指令
        error_log(__CLASS__ . '::' . __FUNCTION__ . ' query == ' .print_r($query ,1)."\n", 3, "application/debug.log");
        return $query; // 回傳抓取到的資料庫資料
    }
}