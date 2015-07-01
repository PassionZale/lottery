<!doctype html>
<?php
$this->db2 = $this->load->database('mt4', true);
$this->db2->where('EMAIL', $this->userdetail->email);
$query = $this->db2->get('MT4_USERS');
$this->load->library('encrypt');
for ($j = 1; $j < 10; $j++) {
    $reward[$j] = $this->encrypt->encode($j . time());
}
?>
<html>
<head>
    <meta charset="utf-8">
    <title>转盘</title>
</head>
<!--core css-->
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link href="/css/base.css" rel="stylesheet">
<body>
<!--QR-->
<div id="qrcode-wrap">
    <img src="http://www.dooforex.com/wp-content/uploads/2015/06/wechat-qrcode.png">

    <p>扫一扫，及时获取我们的最新活动与资讯 :D</p>
</div>
<!--Dialog Model-->
31
<div class="modal fade" id="user_login">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">请先选择奖金录入的账号</h4>
            </div>
            <div class="modal-body">
                <select id="select_account" name="login" class="form-control">
                    <?php foreach ($query->result() as $row) { ?>
                        <option value="<?php echo $row->LOGIN; ?>">
                            <?php echo $row->LOGIN; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirm_btn">确定</button>
            </div>
        </div>
    </div>
</div>
<!--Info Modal-->
<div class="modal fade" id="send_info" style="top:30%;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">您中奖啦!</h4>
            </div>
            <div class="modal-body">
                <p id="info" class="text-danger"></p>
            </div>
        </div>
    </div>
</div>

<div class="header"></div>
<div class="box">
    <div class="description-wrap">
        <h2>参与资格</h2>
        <br>

        <p>所有在活动期间开户并入金激活交易账户的新客户均可参加抽奖（新客户即活动前未有在 dooForex
            开立过账户或未曾拥有交易账号的客户），同名客户再开立新账户，不享有参加这次抽奖的机会。每个客户根据入金金额可享有不同的抽奖次数。</p>
        <br>

        <h2>活动规则</h2>
        <br>
        <ol>
            <li>抽奖赠金均为信用额形式的赠金，在客户完成抽奖后即根据抽奖结果注入该客户的交易账户；</li>
            <li>客户在参加本次抽奖活动的同时，抽奖所得的赠金将不享有参加其他优惠活动的权利；</li>
            <li>抽奖赠金不可直接提取，在交易信用额的状态下，客户可以提取通过交易信用额产生的盈利；</li>
        </ol>
        <button onclick='window.open("http://www.dooforex.com/?page_id=612","_blank")' class="btn btn-primary btn-sm">
            查看详细 >>
        </button>
    </div>
    <div class="ly-plate">
        <div class="rotate-bg" onClick="javascript:$('#t').val(1);$('#lotteryBtn').click();">
            <a href="javascript:;"></a>
        </div>
        <div style="width:445px;height:447px;background:url(/imges/pan_01.png);">
            <div class="lottery-star">
                <img src="/imges/ly-plate.png" id="lotteryBtn">
                <input type="hidden" name="t" id="t" value="0"/>
            </div>
        </div>
    </div>
    <div class="count-wrap"><h2>剩余抽奖次数：<span class="count error"><?php isset($count) ? $count : ''; ?></span>次</h2>
    </div>

</div>
<div id="mask"></div>
<div id="data" style='display:none;'>
    <ul>
        <?php foreach ($reward as $value) { ?>
            <li><?php echo $value; ?></li>
        <?php } ?>
    </ul>
</div>
</body>
<!--core js-->
<script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="/js/jQueryRotate.2.2.js"></script>
<script src="/js/jquery.easing.min.js"></script>
<script src="/js/main.js"></script>

</html>
