<?php

$options = [
    0 => ["pipe", "r"],  // 标准输入，子进程从此管道中读取数据
    1 => ["pipe", "w"],  // 标准输出，子进程向此管道中写入数据
    2 => ["file", "/tmp/error-output.txt", "a"] // 标准错误，写入到一个文件
];
$string = <<<END
<xml>
 <ToUserName><![CDATA[toUser]]></ToUserName>
 <FromUserName><![CDATA[fromUser]]></FromUserName>
 <CreateTime>1348831860</CreateTime>
 <MsgType><![CDATA[text]]></MsgType>
 <Content><![CDATA[nihao]]></Content>
 <MsgId>1234567890123456</MsgId>
 </xml>
END;
$file = __DIR__ . '/index.php';
$process = proc_open("php $file", $options, $pipes, "/tmp");
if (is_resource($process)) {
    fwrite($pipes[0], $string);
    fwrite($pipes[0], "\n");
    fclose($pipes[0]);
    echo stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    proc_close($process);
}
