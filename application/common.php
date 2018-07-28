<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function sprint($array) {
	echo "<pre>";
	print_r($array);
}

/**
 *  密码生成规则
 */
function pwdRule($pwd, $salt) {
    return md5(md5($pwd).$salt);
}
/**
 * 无限分类树状结构
 */
function genTree($items, $id='department_id', $pid='parent_id', $son = 'children'){
    $tree   = array();  // 格式化的树
    $tmpMap = array();  // 临时扁平数据

    foreach ($items as $item) {
        $tmpMap[$item[$id]] = $item;
    }

    foreach ($items as $item) {
        if (isset($tmpMap[$item[$pid]])) {
            $tmpMap[$item[$pid]][$son][] = & $tmpMap[$item[$id]];
        } else {
            $tree[] = & $tmpMap[$item[$id]];
        }
    }

    return $tree;
}
/**
 * 无限分类构成select的option
 */
function getTreeSelect($list, $depth = 0, $id = 'department_id', $name = 'department_name', $children = 'children', $depthStyle = "das") {
    $html = '';
    foreach ($list as $value) {
        $html .= '<option value="' . $value[$id] .'">' . str_repeat($depthStyle, $depth * 4) . $value[$name] . '</option>';
        if (isset($value[$children])) {
            $html .= getTreeSelect($value[$children], $depth+1, $id, $name, $children, $depthStyle);
        }
    }
    return $html;
}
