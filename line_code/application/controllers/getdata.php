<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class getdata extends CI_Controller {
    public function index() {
        error_log(__CLASS__ . '::' . __FUNCTION__ . ' go getdata ' ."\n", 3, "application/debug.log");
        $this->load->model ( 'getsqlmod' ); // 載入model
        $query = $this->getsqlmod->getdata ()->result_array (); // 使用getsqlmod裡的getdata功能
        $data = array ( // 存入列陣
                'data' => $query
        );
        $this->load->view ( 'getsqltest', $data ); // 載入視界並且把資料$data丟進去
    }
}