
<?php  

//正常日期转时间戳
echo mktime(23, 0, 0, 9, 26, 2014) . "\n";
//echo mktime(0, 0, 0, 9, 25, 2011) . "\n";
echo "<br />";echo "<br />";
echo "<br />";
echo mktime(17, 20, 0, 9, 26, 2014) . "\n";
//echo mktime(0, 0, 0, 9, 26, 2014) . "\n";
echo "<br />";echo "<br />";
echo "<br />";
/*
time();
是获得当前时间,但获得的是一整型
*/
//可以对此进行格式化
echo "time()显示年月日时分秒:" . date("Y-m-d H:i:s", time()) . "\n";
echo "<br />";
echo "<br />";
//这样连时,分秒一起显示
//echo "time()只显示年月日：" . date("Y-m-d ", time()) . "\n"; //只年示年月日
 
echo "23时间戳格式化：" . date("Y-m-d H:i:s", 1411772400) . "\n"; //直接使用时间戳
echo "<br />";
echo "<br />";
echo "now时间戳格式化：" . date("Y-m-d H:i:s", 1411752000) . "\n"; //直接使用时间戳
/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */