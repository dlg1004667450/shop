<?php
/**
 * @author  wanghui <whlives@qq.com>
 * @version 1.0，20160127
 * @package im接口
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Im_api
{
    /**
     * 获取用户信息
     * @param str $userid 用户名
     */
    function get_user($userid = '')
    {
        $im_appkey = config_item('im_appkey');
        if (!empty($userid) && !empty($im_appkey)) {
            require(APPPATH . 'third_party/taobao_sdk/TopSdk.php');
            $c            = new TopClient;
            $c->appkey    = config_item('im_appkey');
            $c->secretKey = config_item('im_secret');
            $c->format    = 'json';
            $req          = new OpenimUsersGetRequest;
            $req->setUserids(self::encryption($userid));
            $resp = $c->execute($req);
            $res  = json_decode(json_encode($resp), true);
            if (!empty($res['userinfos']['userinfos'][0])) {
                return $res['userinfos']['userinfos'][0];
            }
        }
        return false;
    }

    /**
     * 添加用户
     * @param str $userid   用户名
     * @param str $icon_url 头像
     * @param str $nick     昵称
     */
    function add_user($m_id = '', $icon_url = '', $nick = '')
    {
        $im_appkey = config_item('im_appkey');
        if (!empty($m_id) && !empty($im_appkey)) {
            $userid = self::encryption($m_id);
            require(APPPATH . 'third_party/taobao_sdk/TopSdk.php');
            $c                   = new TopClient;
            $c->appkey           = $im_appkey;
            $c->secretKey        = config_item('im_secret');
            $c->format           = 'json';
            $req                 = new OpenimUsersAddRequest;
            $userinfos           = new Userinfos;
            $userinfos->icon_url = $icon_url == '' ? '/public/images/user_header.jpg' : $icon_url;
            $userinfos->nick     = $nick;
            $userinfos->userid   = $userid;
            $userinfos->password = $userid;
            $req->setUserinfos(json_encode($userinfos));
            $resp = $c->execute($req);
            $res  = json_decode(json_encode($resp), true);
            if (!empty($res['uid_succ']['string'][0]) || !empty($res['uid_fail']['string'][0])) {
                //更新用户im状态
                $CI = &get_instance();
                $CI->load->model('loop_model');
                $CI->loop_model->update_id('member', array('im' => $userid), $m_id);
                return 'y';
            }
        }
    }

    /**
     * 修改用户
     * @param str $userid   用户名
     * @param str $icon_url 头像
     * @param str $nick     昵称
     */
    function update_user($m_id = '', $icon_url = '', $nick = '')
    {
        $im_appkey = config_item('im_appkey');
        if (!empty($m_id) && !empty($im_appkey)) {
            require(APPPATH . 'third_party/taobao_sdk/TopSdk.php');
            $c                   = new TopClient;
            $c->appkey           = $im_appkey;
            $c->secretKey        = config_item('im_secret');
            $c->format           = 'json';
            $req                 = new OpenimUsersUpdateRequest;
            $userinfos           = new Userinfos;
            if (!empty($icon_url))$userinfos->icon_url = $icon_url;
            if (!empty($nick))$userinfos->nick     = $nick;
            $userinfos->userid   = self::encryption($m_id);
            $userinfos->password = self::encryption($m_id);
            $req->setUserinfos(json_encode($userinfos));
            $resp = $c->execute($req);
            $res  = json_decode(json_encode($resp), true);
            if (!empty($res['uid_succ']['string'][0]) || !empty($res['uid_fail']['string'][0])) {
                return 'y';
            }
        }
    }

    /**
     * 用户名和密码加密规则
     * @param str $string 需要加密的字符
     */
    function encryption($string = '')
    {
        if (!empty($string)) {
            return md5($string . 'my_shop');
        }
        return false;
    }
}