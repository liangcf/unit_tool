<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/7/20
 * Time: 14:36
 */

//mysqli 持久化连接测试
for ($i = 0; $i < 15; $i++) {
    $links[$i] =  mysqli_connect('p:127.0.0.1', 'root', 'Abcd4321', 'fr_test', 3306);
    var_dump(mysqli_thread_id($links[$i]));    //如果你担心被close掉了，这是新建的TCP链接，那么你可以打印下thread id，看看是不是同一个ID，就区分开了
    mysqli_close($links[$i]);
}
echo '<hr>';
for ($y = 0; $y < 2; $y++) {
    $links[$y] =  mysqli_connect('p:127.0.0.1', 'root', 'Abcd4321', 'mytest1', 3306);
    var_dump(mysqli_thread_id($links[$y]));    //如果你担心被close掉了，这是新建的TCP链接，那么你可以打印下thread id，看看是不是同一个ID，就区分开了
    mysqli_close($links[$y]);
}