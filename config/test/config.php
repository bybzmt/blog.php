<?php
/*
 * 测试环境配置
 */
return array_replace_recursive(
    require __DIR__ . '/../product/config.php',
    array(
        //其它环境中的配置，用来替换product中的配置
    )
);
