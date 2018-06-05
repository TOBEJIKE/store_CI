<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Api extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->service('user_service');
    }
    // 测试
    public function user_list()
    {
        echo $this->user_service->user_list();
    }
}