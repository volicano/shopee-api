<?php
/**
 * Created by PhpStorm.
 * User: patpat
 * Date: 2020/6/29
 * Time: 15:59
 */

namespace Shopee;


class ShoppeeApi
{
    const DEFAULT_BASE_URL = 'https://partner.shopeemobile.com';
    const ENV_SECRET_NAME = 'SHOPEE_API_SECRET';
    const ENV_PARTNER_ID_NAME = 'SHOPEE_PARTNER_ID';
    const ENV_SHOP_ID_NAME = 'SHOPEE_SHOP_ID';

    public function getOrderList($shopid,$partner_id,$page=0,$days=3){
        $pageSize = 10;
        $offSet = $page * $pageSize;
        $timestamp=time();
        //通过订单更新时间来检索订单，查询三天内有更新的订单
        $update_time_from = $timestamp-$days*24*3600;
        $update_time_to = $timestamp;
        $url=self::DEFAULT_BASE_URL."/api/v1/orders/basics";
        $body=array('update_time_from'=>$update_time_from,'update_time_to'=>$update_time_to,'shopid'=>intval($shopid),'partner_id'=>intval($partner_id),'timestamp'=>$timestamp,'pagination_entries_per_page'=>$pageSize,'pagination_offset'=>$offSet);
        $data_dt = self::postCurl($partner_id,$url,$body);
        $data = $data_dt['orders'];
        if($data&&count($data)&&$data_dt['more']){
            $page++;
            $tmp = $this->getOrderList($shopid,$partner_id,$page);
            $data = array_merge($data,$tmp);
        }
        if(!$data){
            return $data;
        } else{
            return $data;
        }
    }

    public function getOrderAddress($shopid,$partner_id,$orderId){
        $timestamp=time();
        $url=self::DEFAULT_BASE_URL."/api/v1/orders/detail";
        $body=array('ordersn_list'=>[$orderId],'shopid'=>intval($shopid),'partner_id'=>intval($partner_id),'timestamp'=>$timestamp);
        return self::postCurl($partner_id,$url,$body);
    }

    public function getOrderDetails($shopid,$partner_id,$orderId){
        $timestamp=time();
        $url=self::DEFAULT_BASE_URL."/api/v1/orders/my_income";
        $body=array('ordersn'=>$orderId,'shopid'=>intval($shopid),'partner_id'=>intval($partner_id),'timestamp'=>$timestamp);
        return self::postCurl($partner_id,$url,$body);
    }

    private function postCurl($key,$url,$body=array()){
        $bodyjson=json_encode($body);
        //拼接基础验证码
        $had=$url.'|'.$bodyjson;
        //获取哈希加密 16进制验证码
        $has=bin2hex(hash_hmac('sha256',$had,$key,true));
        //拼接请求头部
        $header=array('Content-Type: application/json',"Authorization:$has");
        //构建curl请求
        $c=curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c,CURLOPT_HTTPHEADER,$header);
        curl_setopt($c,CURLOPT_POST,1);
        curl_setopt($c,CURLOPT_POSTFIELDS,$bodyjson);
        curl_setopt($c,CURLOPT_RETURNTRANSFER,1);
        //获取到数据 json格式
        $datajson=curl_exec($c);
        curl_close($c);
        return json_decode($datajson,true);
    }
}