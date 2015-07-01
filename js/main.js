/**
 * Created by zl on 2015/6/22.
 */
$(function () {

    var $count_node = $('.count'),
        $lottery_btn = $('#lotteryBtn'),
        $mask = $('#mask');
    /*ajax后台获取抽奖次数*/
    (function () {
        $.ajax({
            type: 'POST',
            url: '/huodong/get_count',
            dataType: 'json',
            success: function (ret) {
                $count_node.html(ret.info);
            }
        });

    })();
    var data = 0;//奖项等级变量
    var arr = [];
    var md_code = '';
    var node_arr = $('#data li');
    for (var i = 0; i < 9; i++) {
        md_code = node_arr[i]['innerHTML'];
        arr.push(md_code);
    }
    console.log(arr);
    /*
     * data=1 --->8$
     * data=2 --->18$
     * data=3 --->28$
     * data=4 --->48&
     * data=5 --->88$
     * data=6 --->188$
     * data=7 --->288&
     * data=8 --->488$
     * data=9 --->888$
     * */
    var rotateFunc = function (awards, angle, text) {  //awards:奖项，angle:奖项对应的角度
        $lottery_btn.rotate({
            angle: 0,
            duration: 3000,
            animateTo: angle + 1800, //angle是图片上各奖项对应的角度，1800是我要让指针旋转5圈
            easing: $.easing.easeOutSine,//别问我,我也不知道easing是个啥....
            callback: function () {
                //TODO
                alert(text);
                $mask.hide();
//		    location.reload(true);//F5强制刷新页面
            }
        });
    };
    //通过随机1-100整数控制抽奖概率
    var getRandNum = function () {
        return Math.floor(Math.random() * 99 + 1);
    }
    /*post提交奖项*/
    var dataBack = function (awards, totalCount) {
        $.ajax({
            type: 'POST',
            url: '/huodong/turnround',
            dataType: 'json',
            data: {'awards': awards, 'count': totalCount},
            error: function (ret) {
                console.log(ret.info);
            },
            success: function (ret) {
                $count_node.html(ret.info);
            }
        });
    }


    $lottery_btn.on('click', function () {
        $mask.show();
        var count = $count_node.html();//抽奖次数
        var t = $("#t").val();
        if (t == 1) {
            $("#t").val(0);
            var rand_num = getRandNum();//获取随机数
            if (count > 0) {
                if (count <= 5) {//抽奖次数5次以内
                    if (rand_num > 0 && rand_num <= 80) {
                        data = arr[0];//80%概率->8$
                        rotateFunc(1, 25, '恭喜您抽中8$现金!');
                        dataBack(data, count);
                    } else if (rand_num > 80 && rand_num <= 95) {
                        data = arr[1];//15%概率->18$
                        rotateFunc(2, 265, '恭喜您抽中18$现金!');
                        dataBack(data, count);
                    } else {
                        data = arr[2];//5%概率->28$
                        rotateFunc(3, 145, '恭喜您抽中28$现金!');
                        dataBack(data, count);
                    }
                } else if (count = 6) {//抽奖次数6次
                    if (rand_num && rand_num <= 30) {
                        data = arr[0];//30%概率->8$
                        rotateFunc(1, 25, '恭喜您抽中8$现金!');
                        dataBack(data, count);
                    } else if (rand_num > 30 && rand_num <= 55) {
                        data = arr[1];//25%概率->18$
                        rotateFunc(2, 265, '恭喜您抽中18$现金!');
                        dataBack(data, count);

                    } else if (rand_num > 55 && rand_num <= 70) {
                        data = arr[2];//25%概率->28$
                        rotateFunc(3, 145, '恭喜您抽中28$现金!');
                        dataBack(data, count);

                    } else if (rand_num > 70 && rand_num <= 80) {
                        data = arr[3];//10%概率->48$
                        rotateFunc(4, 340, '恭喜您抽中48$现金!');
                        dataBack(data, count);

                    } else {
                        data = arr[4];//10%概率->88$
                        rotateFunc(5, 220, '恭喜您抽中88$现金!');
                        dataBack(data, count);

                    }
                } else if (count >= 7 && count <= 9) {//抽奖次数7~9次
                    if (rand_num && rand_num <= 30) {
                        data = arr[0];//30%概率->8$
                        rotateFunc(1, 25, '恭喜您抽中8$现金!');
                        dataBack(data, count)

                    } else if (rand_num > 30 && rand_num <= 55) {
                        data = arr[1];//25%概率->18$
                        rotateFunc(2, 265, '恭喜您抽中18$现金!');
                        dataBack(data, count)

                    } else if (rand_num > 55 && rand_num <= 70) {
                        data = arr[2];//15%概率->28$
                        rotateFunc(3, 145, '恭喜您抽中28$现金!');
                        dataBack(data, count)

                    } else if (rand_num > 70 && rand_num <= 80) {
                        data = arr[3];//10%概率->48$
                        rotateFunc(4, 340, '恭喜您抽中48$现金!');
                        dataBack(data, count)

                    } else if (rand_num > 80 && rand_num <= 90) {
                        data = arr[4];//10%概率->88$
                        rotateFunc(5, 220, '恭喜您抽中88$现金!');
                        dataBack(data, count)

                    } else if (rand_num > 90 && rand_num <= 95) {
                        data = arr[5];//5%概率->188$
                        rotateFunc(6, 100, '恭喜您抽中188$现金!');
                        dataBack(data, count)

                    } else {
                        data = arr[6];//5%概率->288$
                        rotateFunc(7, 305, '恭喜您抽中288$现金!');
                        dataBack(data, count)

                    }
                } else if (count >= 10) {//抽奖次数10次及以上
                    if (rand_num && rand_num <= 30) {
                        data = arr[0];//30%概率->8$
                        rotateFunc(1, 25, '恭喜您抽中8$现金!');
                        dataBack(data, count)

                    } else if (rand_num > 30 && rand_num <= 50) {
                        data = arr[1];//20%概率->18$
                        rotateFunc(2, 265, '恭喜您抽中18$现金!');
                        dataBack(data, count)

                    } else if (rand_num > 50 && rand_num <= 65) {
                        data = arr[2];//15%概率->28$
                        rotateFunc(3, 145, '恭喜您抽中28$现金!');
                        dataBack(data, count)

                    } else if (rand_num > 65 && rand_num <= 75) {
                        data = arr[3];//10%概率->48$
                        rotateFunc(4, 340, '恭喜您抽中48$现金!');
                        dataBack(data, count)

                    } else if (rand_num > 75 && rand_num <= 85) {
                        data = arr[4];//10%概率->88$
                        rotateFunc(5, 220, '恭喜您抽中88$现金!');
                        dataBack(data, count)

                    } else if (rand_num > 85 && rand_num <= 90) {
                        data = arr[5];//5%概率->188$
                        rotateFunc(6, 100, '恭喜您抽中188$现金!');
                        dataBack(data, count)

                    } else if (rand_num > 90 && rand_num <= 95) {
                        data = arr[6];//5%概率->288$
                        rotateFunc(7, 305, '恭喜您抽中288$现金!');
                        dataBack(data, count)

                    } else if (rand_num > 95 && rand_num <= 99) {
                        data = arr[7];//4%概率->488$
                        rotateFunc(8, 185, '恭喜您抽中488$现金!');
                        dataBack(data, count)

                    } else {
                        data = arr[8];//1%概率->888$
                        rotateFunc(9, 65, '恭喜您抽中888$现金!');
                        dataBack(data, count)
                    }
                }
            } else {
                alert('您没有抽奖次数了~!');
            }
        }
    });
})
