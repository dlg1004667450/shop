<?php
/**
 * @author wanghui <whlives@qq.com>
 * @version 1.0，20160127
 * @package 发送短信
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_send {

    function send_to($data)
    {
        $cache_name = 'sms_'.$data['type'].$data['tel'];
        $sms_code = cache('get',$cache_name);//查询已经发送
        if(!empty($sms_code) && time()-$sms_code['time']<60)
        {
            return '60秒内只能发送一次';
        }
        switch($data['type'])
        {
            case 'reg':
                $sign_name = '注册验证';
                $tmp_code = 'SMS_5485052';
                break;
            case 'login':
                $sign_name = '登录验证';
                $tmp_code = 'SMS_5485054';
                break;
            case 'find_password':
                $sign_name = '变更验证';
                $tmp_code = 'SMS_5485050';
                break;
            default:
                $sign_name = '身份验证';
                $tmp_code = 'SMS_5485055';
        }
        if(!empty($data['tel']))
        {
            if(!empty($data['content']))
            {
                $data['content']['product'] = config_item('website_title');//产品名称
                require(APPPATH . 'third_party/taobao_sdk/TopSdk.php');
                $c = new TopClient;
                $c->appkey    = config_item('sms_appkey');
                $c->secretKey = config_item('sms_secret');
                $c->format    = 'json';
                $req = new AlibabaAliqinFcSmsNumSendRequest;
                $req->setSmsType("normal");
                $req->setSmsFreeSignName($sign_name);
                $req->setSmsParam(ch_json_encode($data['content']));
                $req->setRecNum($data['tel']);
                $req->setSmsTemplateCode($tmp_code);
                $resp = $c->execute($req);
                $res = json_decode( json_encode( $resp),true);
                if($res['result']['success']==true)
                {
                    $sms_code = cache('save', $cache_name, array('content'=>$data['content'],'time'=>time()), 600);//写入缓存
                    return 'success';
                }
                else
                {
                    return '发送失败';//'error'.$res['result']['err_code'];
                }
            }
            else
            {
                //'短信内容不能为空';
                return false;
            }
        }
        else
        {
            //'接收号码不能为空';
            return false;
        }
    }
}