<?php

/**
 
 * 自定义服务基类
 
 * @date 2018年3月23日 
 
 * @author ZhangYan
 
 */
class MY_Service
{
    
    // const HOST_FORM_INFO_URL = 'http://wxb1da4e0fd6ad1262.mp.weixinhost.com/addon/app-private-intel-form?a=get_form_detail';
    const HOST_FORM_INFO_URL = 'http://wx20aaf1189e47f6c6.mp.intel-social.com/addon/app-private-intel-form?a=get_form_detail';
    
    // const INTEL_ERPM_URL = 'https://precrpmws-rest.intel.com/erpm-r-ws/Common.svc/GetAttributesJson?clientName=AEM&enggCode=ITP&attribute=ACCOUNTPROFILE&loginId=';
    const INTEL_ERPM_URL = 'https://crpmws-rest.intel.com/erpm-r-ws/Common.svc/GetAttributesJson?clientName=AEM&enggCode=ITP&attribute=ACCOUNTPROFILE&loginId=';

    public function __construct()
    {
        $this->load->library('Session');
    }

    function __get($key)
    {
        $CI = & get_instance();
        return $CI->$key;
    }

    /**
     *
     * 统一返回方法
     *
     * @date 2018年3月23日
     *
     * @author ZhangYan
     *        
     * @param int $code            
     * @param array $data            
     *
     * @return json
     *
     */
    public function return_data($code, $data = array())
    {
        $result = array(
            'code' => $code,
            'data' => $data
        );
        log_message('info', serialize($result));
        return json_encode($result);
    }

    /**
     *
     * 获取HOST签名
     *
     * @date 2018年3月27日
     *
     * @author ZhangYan
     *        
     * @param string $form_id            
     *
     * @return string
     *
     */
    public function get_host_sign($form_id)
    {
        $key = $this->config->item('host_sign_key');
        $sign = hash_hmac("sha1", $form_id, $key, true);
        $sign = $this->_base64_urlencode($sign);
        return $sign;
    }

    /**
     *
     * 检查表单签名
     *
     * @date 2018年3月23日
     *
     * @author ZhangYan
     *        
     * @param array $params            
     *
     * @return boolean
     *
     */
    public function check_form_sign($params)
    {
        $sign = $params['sign'];
        if (empty($sign)) {
            return false;
        }
        $sign_params = array();
        $sign_params['openid'] = $params['openid'];
        $sign_params['form_id'] = $params['form_id'];
        $sign_params['nickname'] = $params['nickname'];
        ksort($sign_params);
        $strA = '';
        foreach ($sign_params as $key => $value) {
            if (empty($strA)) {
                $strA = $key . '=' . $value;
            } else
                $strA = $strA . '&' . $key . '=' . $value;
        }
        $TempA = $strA . '&key=' . $this->config->item('sign_key');
        $new_sign = strtoupper(md5($TempA));
        if ($sign !== $new_sign) {
            return false;
        }
        return true;
    }

    /**
     *
     * 检查参数
     *
     * @date 2018年3月23日
     *
     * @author ZhangYan
     *        
     * @param array $param            
     * @param array $field            
     *
     * @return boolean
     *
     */
    public function check_param($param, $field)
    {
        foreach ($field['must'] as $value) {
            if (empty($param[$value])) {
                return false;
            }
        }
        foreach ($param as $key => $value) {
            if (! in_array($key, $field['must']) && ! in_array($key, $field['no_must'])) {
                return false;
            }
        }
        return true;
    }

    /**
     *
     * GET请求
     *
     * @date 2018年3月27日
     *
     * @author ZhangYan
     *        
     * @param string $url            
     *
     * @return json
     *
     */
    public function curl_get($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $tmp = curl_exec($ch);
        curl_close($ch);
        return $tmp;
    }

    /**
     *
     * 带basic auth的get 请求
     *
     * @date 2018年3月28日
     *
     * @author ZhangYan
     *        
     * @param string $url            
     *
     * @return json
     *
     */
    public function curl_basic_auth_get($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($ch, CURLOPT_USERPWD, "ed\crpm_test:Ugomd!J#YM!j(ZiK");
        curl_setopt($ch, CURLOPT_USERPWD, "crpm_test:Ugomd!J#YM!j(ZiK");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $tmp = curl_exec($ch);
        curl_close($ch);
        return $tmp;
    }

    /**
     *
     * 函数用途描述
     *
     * @date 2018年3月27日
     *
     * @author ZhangYan
     *        
     * @param string $str            
     *
     * @return $str
     *
     */
    private function _base64_urlencode($str)
    {
        $find = array(
            '+',
            '/'
        );
        $replace = array(
            '-',
            '_'
        );
        return str_replace($find, $replace, base64_encode($str));
    }
}