<?php

/**
 
 * 自定义模型基类
 
 * @date 2018年3月23日 
 
 * @author ZhangYan
 
 */
class MY_Model extends CI_Model
{
    // 请求成功
    const REQUEST_SUCCESS = 1000;
    
    // 请求参数错误
    const REQUEST_PARAM_ERROR = 2000;
    // 签名错误
    const SIGN_ERROR = 2001;
    // 系统错误
    const SYSTEM_ERROR = 6000;

    protected $_table;

    protected $_pk;

    function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * 添加
     *
     * @date 2018年3月23日
     *
     * @author ZhangYan
     *        
     * @param array $data            
     *
     * @return boolean
     *
     */
    public function add($data)
    {
        return $this->db->insert($this->_table, $data);
    }

    /**
     *
     * 批量添加
     *
     * @date 2018年3月23日
     *
     * @author ZhangYan
     *        
     * @param array $datas            
     *
     * @return mixed
     *
     */
    public function add_batch($datas)
    {
        return $this->db->insert_batch($this->_table, $datas);
    }

    /**
     *
     * 根据主键修改
     *
     * @date 2018年3月23日
     *
     * @author ZhangYan
     *        
     * @param int $id            
     * @param array $data            
     *
     * @return boolean
     *
     */
    public function update($id, $data)
    {
        $this->db->where($this->_pk, $id);
        return $this->db->update($this->_table, $data);
    }

    /**
     *
     * 批量修改
     *
     * @date 2018年3月23日
     *
     * @author ZhangYan
     *        
     * @param array $datas            
     *
     * @return mixed
     *
     */
    public function update_batch($datas)
    {
        return $this->db->update_batch($this->_table, $datas, $this->_pk);
    }

    /**
     *
     * 获取
     *
     * @date 2018年3月23日
     *
     * @author ZhangYan
     *        
     * @param array $search            
     * @param boolean $alone            
     * @param array $order            
     *
     * @return array
     *
     */
    public function get($search = array(), $alone = FALSE, $order = array())
    {
        $this->db->where($search);
        if (! empty($order)) {
            foreach ($order as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
        $res = $this->db->get($this->_table);
        if (empty($alone)) {
            return $res->result_array();
        } else {
            return $res->row_array();
        }
    }

    /**
     *
     * 根据主键批量删除
     *
     * @date 2018年3月23日
     *
     * @author ZhangYan
     *        
     * @param array $ids            
     *
     * @return mixed
     *
     */
    public function del($ids)
    {
        $this->db->where_in($this->_pk, $ids);
        return $this->db->delete($this->_table);
    }

    /**
     *
     * 分页获取
     *
     * @date 2018年3月23日
     *
     * @author ZhangYan
     *        
     * @param array $search            
     * @param int $page            
     * @param int $num            
     * @param array $order            
     *
     * @return array
     *
     */
    public function get_datas($search = array(), $page, $num, $order = array())
    {
        $this->db->where($search);
        if (! empty($order)) {
            foreach ($order as $key => $value) {
                $this->db->order_by($key, $value);
            }
        } else {
            $this->db->order_by($this->_table . '.' . $this->_pk, 'DESC');
        }
        $this->db->limit($num, ($page - 1) * $num);
        $res = $this->db->get($this->_table);
        return $res->result_array();
    }

    /**
     *
     * 获取总数
     *
     * @date 2018年3月23日
     *
     * @author ZhangYan
     *        
     * @param array $search            
     *
     * @return int
     *
     */
    public function get_nums($search = array())
    {
        $this->db->where($search);
        return $this->db->count_all_results($this->_table);
    }

    /**
     *
     * 获取最后一个自增号
     *
     * @date 2018年3月23日
     *
     * @author ZhangYan
     *        
     * @return int
     *
     */
    public function get_last_id()
    {
        return $this->db->insert_id();
    }
}