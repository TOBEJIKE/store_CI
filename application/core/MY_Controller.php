<?php
class MY_Controller extends CI_Controller{
    protected $ci;

    public $body_template = '';

    public $result = '';

    public $openid = '';

    function __construct()
    {
        parent::__construct();
        $this->load->helper('cookie');
        $this->load->helper('url');
        $this->ci = get_instance();
    }

    protected function check()
    {
        
    }
}