<?php
class User_service extends MY_Service
{
    // 配置路径
    // const CONFIG_PATH = 'service/user';

    function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        // $this->config->load(self::CONFIG_PATH, TRUE);
    }
    // 测试数据
    public function user_list(){
        $data =  $this->user_model->getAllUser();
        return json_encode($data);
    }
}