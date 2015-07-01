<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lottery extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('lottery_model');
        $this->load->library('encrypt');
        $this->load->library('session');
        //判断用户是否登录
        $this->isLogin = $this->user_model->is_login();
        if ($this->isLogin) {
            //如果登录则将用户信息写入session中
            $this->session->set_userdata('last_activity', time());
            $this->userdetail = $this->user_model->userDetail($this->session->userdata('user_data'));
        } else {
            //如果未登录则跳转至登录页面
            header("location://login");
        }
    }

    public function index()
    {
        $this->db = $this->load->database('default', true);
        $this->db->where('cmd', 0);
        $this->db->where('status', 1);
        $this->db->where('applytime >', 14345530359/*unix时间戳,设置为抽奖活动开始的时间*/);
        $this->db->where('amount >=', 250/*最低入金250及以上才有抽奖资格*/);
        $this->db->where('swift', Null);
        $this->db->where('email = ', $this->userdetail->email/*用户的email,从session中获取*/);
        $deposit = $this->db->get('tbRecord')->result();//获取该DB table中该用户入金的全部字段数据
        //根据金额获取抽奖次数
        $count = 0;
        $tmp = 0;
        $tmp_amount = 0;
        foreach ($deposit as $row) {
            /*
             * 将用户
             */
            $amount = $row->amount;//用户金额
            $id = $row->id;//用户的ID
            $this->db->where('id', $id);
            //swift标记为1,表示此条入金记录已经被计算过抽奖次数
            //swift标记为Null,表示此条还未被处理
            $data = array('swift' => 1);
            $this->db->update('tbRecord', $data);

            if ($tmp == 0) {//$tmp=0说明用户是活动开始首次入金
                $tmp_amount = $amount;
                $count = $count + $this->lottery_model->get_count($amount);
            } else {//不为0,则非首次入金
                if ($amount >= $tmp_amount * 2) {//该用户本次入金金额$amount必须是上一次入金金额$tmp_amount的2倍及以上
                    $count = $count + $this->lottery_model->get_count($amount);
                    $tmp_amount = $amount;
                }
            }
            $tmp++;//$tmp累加,保证首次入金的计算只会出现一次
        }
        $email = $this->session->userdata('user_data');
        $this->db->where('email', $email);
        $result = $this->db->get('tbLottery');
        $isFlag = $result->num_rows;

        if ($isFlag == 0) {
            $data = array(
                'email' => $email,
                'count' => $count
            );
            $this->db->insert('tbLottery', $data);
        } else if ($isFlag == 1) {
            $prev = $result->row()->count;
            $count = $count + $prev;
            $data = array(
                'count' => $count
            );
            $this->db->where('email', $email);
            $this->db->update('tbLottery', $data);
        }
        $this->load->view('round', $data);
    }

    /*获取抽奖次数*/
    public function get_count()
    {
        $email = $this->session->userdata('user_data');
        $this->db->where('email', $email);
        $count = $this->db->get('tbLottery')->row()->count;
        $ret = array('info' => $count);
        echo json_encode($ret);
    }

    public function turnround()
    {
        $ret = array();
        $email = $this->session->userdata('user_data');
        $tmp = $this->input->post();
        $str = $tmp['awards'];//奖项等级
        $count = $tmp['count'];//抽奖次数
        $login = $tmp['login'];//账号

        $reward = $this->encrypt->decode($str);
        $reward = substr($reward, 0, 1);//解析ajax提交的加密'奖项',获取奖项等级(1-9)
        switch ($reward) {//对应奖金,单位$
            case 1:
                $reward = 8;
                break;
            case 2:
                $reward = 18;
                break;
            case 3:
                $reward = 28;
                break;
            case 4:
                $reward = 48;
                break;
            case 5:
                $reward = 88;
                break;
            case 6:
                $reward = 188;
                break;
            case 7:
                $reward = 288;
                break;
            case 8:
                $reward = 488;
                break;
            case 9:
                $reward = 888;
                break;
            default:
                $reward = 0;
                break;
        }
        if (isset($count)) {
            $this->db->where('email', $email);
            $result = $this->db->get('tbLottery');
            if ($result->num_rows == 1) {
                $count = (int)$result->row()->count;
//				$tmp = ($count <= 5 && ($reward != 8 || $reward != 18 || $reward !=28));
//				var_dump($tmp);exit();
                if ($count >= 1) {
                    //检验前端ajax提交过来的抽奖次数$count和奖金$reward的对应关系
                    //如果不是与规则想对应的关系,则用户私自修改了数据,exit()掉
                    if ($count <= 5 && ($reward != 8 && $reward != 18 && $reward != 28)) {
                        exit('超时!');
                    } else if ($count == 6 && ($reward != 8 && $reward != 18 && $reward != 48)) {
                        exit('超时!');
                    } else if ($count >= 7 && $count <= 9 && ($reward != 8 && $reward != 18 && $reward != 28 && $reward != 48 && $reward != 88)) {
                        exit('超时!');
                    } else if ($count >= 10 && ($reward != 8 && $reward != 18 && $reward != 28 && $reward != 48 && $reward != 88 && $reward != 288 && $reward != 488 && $reward != 888)) {
                        exit('超时!');
                    }
                    $count--;
                    $this->db->where('email', $email);
                    $data = array('count' => $count);
                    $this->db->update('tbLottery', $data);
                    $data = array(
                        'reward' => $reward,
                        'email' => $email,
                        'applytime' => time(),
                        'login' => $login
                    );
                    $this->db->insert('tbReward', $data);
                    $ret = array('info' => $count, 'status' => 0);
                } else {
                    $ret = array('info' => '您没有抽奖次数了~!', 'status' => 1);
                }
            }
        }
        echo json_encode($ret);
    }
}
