<?php
class User_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->_table = 'test';
    }
    /**
     * 测试代码
     */
    public function getAllUser() {
        $res = $this->db->get($this->_table);
        return $res->result_array(); 
    }
}