<script><?php
$weixinservice=weixinapi::getInstance();
 $signPackage = $weixinservice->GetSignPackage();
?>
</script>
<!DOCTYPE html>
<html class="no-js" lang="zh-CN">
	<head>
	<META content="text/html; charset=utf-8" http-equiv=Content-Type>
        <meta name="viewport" content="width=640,target-densitydpi=device-dpi,maximum-scale=5.0,minimum-scale=0.2, user-scalable=no">
        <link rel="stylesheet" type="text/css" href="css/style.css"/>
		<title>聚有钱</title>
		{{ stylesheet_link('css/style.css') }}
		  {{elements.GetHomeJs()}}
        <script >


                $(document).on("pageinit","#pageone",function(){
          $("#kwd").bind("taphold", function(){

            var doc = document,
                text = doc.getElementById("kwd"),
                range,
                selection;
            if (doc.body.createTextRange) { //IE
                range = document.body.createTextRange();
                range.moveToElementText(text);
                range.select();

            } else if (window.getSelection) {   //FF CH SF
                selection = window.getSelection();
                range = document.createRange();
                range.selectNodeContents(text);
                selection.removeAllRanges();
                selection.addRange(range);


                //测试
                console.log(text.textContent);
                text.innerText && console.log(text.innerText);  //FireFox不支持innerText
                console.log(text.textContent.length);
                text.innerText && console.log(text.innerText.length);   //在Chrome下长度比IE/FF下多1
                console.log(text.firstChild.textContent.length);
                text.innerText && console.log(text.firstChild.innerText.length);
                console.log(text.firstChild.innerHTML.length);

                //注意IE9-不支持textContent
                makeSelection(0, text.firstChild.textContent.length, 0, text.firstChild);
                /*
                if(selection.setBaseAndExtent){
                    selection.selectAllChildren(text);
                    selection.setBaseAndExtent(text, 0, text, 4);
                }
                */
            }else{
                alert("浏览器不支持长按复制功能");
            }


                        });
        });


        function makeSelection(start, end, child, parent) {
            var range = document.createRange();
            //console.log(parent.childNodes[child]);
            range.setStart(parent.childNodes[child], start);
            range.setEnd(parent.childNodes[child], end);

            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        }
        	</script>
	</head>
<body >
{{ flash.output() }}
    {{ content() }}
       {{ javascript_include('js/WeiJs.js') }}
       {{ javascript_include('assets/global/plugins/jquery-1.11.0.min.js') }}
        <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</body>

{{ javascript_include('js/jquery.qrcode.min.js') }}
<script type="text/javascript">
$(function(){
	//var str = "http://www.ihmedia.com.cn";
	var str = <?php echo "'".$qrurl."'"; ?>;
	$('#myqrcode').qrcode(str);
	
	//$("#myqrcode").html(str);

	
})
   

</script>

<script>

jQuery(document).ready(function() {
btnadd.init();
});
{{elements.SetWeiXinIndex()}}
 wx.config({
        debug: false,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: <?php echo $signPackage["timestamp"];?>,
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'hideOptionMenu',
            'showOptionMenu',
            'hideAllNonBaseMenuItem',
            'closeWindow',
            'showAllNonBaseMenuItem'
        ]
    });
    wx.ready(function () {
     {{elements.SetRightMenu() }}
    });
</script>
</html>