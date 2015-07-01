<?php

/**
 * Created by PhpStorm.
 * User: zl
 * Date: 2015/7/1
 * Time: 15:51
 */
class Lottery_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    //根据用户入金金额,计算出其获取的抽奖次数
    public function get_count($amount/*入金金额*/)
    {
        $tmp = $amount / 250;
        if ($tmp >= 1 && $tmp < 3) {
            return $count = 1;
        } else if ($tmp >= 3 && $tmp < 7) {
            return $count = 2;
        } else if ($tmp >= 7 && $tmp < 15) {
            return $count = 3;
        } else if ($tmp >= 15 && $tmp < 63) {
            if ($amount >= 4999 && $amount <= 7750) {
                return $count = 5;
            } else {
                return $count = 4;
            }
        } else if ($amount >= 9999 && $amount < 14999) {
            return $count = 7;
        } else if ($amount >= 14999 && $amount <= 15000) {
            return $count = 10;
        }
    }
}