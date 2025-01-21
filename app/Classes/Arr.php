<?php

namespace App\Classes;

class Arr
{
    public static function toTree($list, $pk = 'id', $pid = 'parent_id', $son = 'children')
    {
        $tree = [];
        $refer = [];
        foreach ($list as $k => $v) {
            $refer[$v[$pk]] = &$list[$k];
        }
        foreach ($list as $k => $v) {
            $parentId = $v[$pid];
            if ($parentId == 0) {
                $tree[] = &$list[$k];
            } else {
                if (isset($refer[$parentId])) {
                    $parent = &$refer[$parentId];
                    $parent[$son][] = &$list[$k];
                }
            }
        }
        return $tree;
    }
}
