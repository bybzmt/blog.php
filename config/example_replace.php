<?php
/**
 * 配置文继承写法
 */
return array_replace_recursive(
    require __DIR__ . '/../product/config.php',
    array(
        //其它环境中的配置，用来替换product中的配置
    )
);

//注意！如果数组key是数字那它会合并到一起！
//会得到['a', 'b', 'c']
var_dump(array_replace_recursive(
    ["k" => ['a']],
    ["k" => ['b', 'c']]
));

//如果不想它合并应该这么写！
var_dump(array_replace_recursive(
    ["k" => ['a']],
    ["k" => new ArrayObject(['b', 'c'])]
));

