<?php
class User_service extends MY_Service
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }
    // 测试数据
    public function user_list(){
        $data =  $this->user_model->getAllUser();
        return json_encode($data);
    }
}