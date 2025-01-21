<?php

namespace App\Classes;

class Acl
{
    public static $name = 'admin';
    public static $allowMenu;
    public static function check($url)
    {
        if (substr($url, 0, 1) != '/') {
            $url = '/'.trim($url, '/');
        }
        $user = \Auth::user();
        if ($user->isAdmin()) {
            return true;
        }
        $admin_default_allow = include config_path('acl/'.self::$name.'_default_allow.php');
        if (in_array($url, $admin_default_allow)) {
            return true;
        }
        $list = self::getList($user);
        if (!$list) {
            return false;
        }
        $allow = [];
        foreach ($list as $k => $v) {
            if ($v['checked']) {
                $allow[$v['key']] = $v['url'];
            }
        }
        if (!$allow) {
            return false;
        }
        foreach ($allow as $k => $v) {
            if (is_array($v)) {
                if (in_array($url, $v)) {
                    return true;
                }
            } else {
                if ($url == $v) {
                    return true;
                }
            }
        }
        return false;
    }
    public static function getAll()
    {
        $all = include config_path('acl/'.self::$name.'.php');
        return $all;
    }

    public static function getUrls()
    {
        $all = self::getAll();
        $urls = [];
        foreach ($all as $key => $value) {
            foreach ($value as $k => $v) {
                if (is_array($v)) {
                    $urls = array_merge($urls, $v);
                } else {
                    $urls[] = $v;
                }
            }
        }
        return $urls;
    }

    public static function getAllowMenu($menus)
    {
        foreach ($menus as $key => $value) {
            if (isset($value['children'])) {
                foreach ($value['children'] as $k => $v) {
                    if (isset($v['acl'])) {
                        $flag = self::check($v['acl']);
                        if (!$flag) {
                            unset($menus[$key]['children'][$k]);
                        }
                    }
                }
            }
            if (isset($value['acl'])) {
                $flag = self::check($value['acl']);
                if (!$flag) {
                    unset($menus[$key]);
                }
            }
        }
        if ($menus) {
            foreach ($menus as $k => $v) {
                if (isset($v['children'])) {
                    if (!$v['children']) {
                        unset($menus[$k]);
                    }
                }
            }
        }
        return $menus;
    }

    public static function getList($user = '')
    {
        if (!$user) {
            return;
        }
        $userAcl = $user->userAcl ?? [];

        $acl = [];
        if ($userAcl) {
            foreach ($userAcl as $k => $v) {
                $acl[] = $v->acl;
            }
        }
        $data = self::getAll();
        $list = [];
        $menu = [];
        foreach ($data as $k => $v) {
            $i = 0;
            foreach ($v as $key => $value) {
                $checked = 0;
                $uni = md5($k.'-'.$key);
                if ($acl && in_array($uni, $acl)) {
                    $checked = 1;
                }
                $title = $k;
                if ($i > 0) {
                    $title = '';
                }
                $list[] = [
                   'title' => $title,
                   'acl' => $key,
                   'checked' => $checked,
                   'key' => $uni,
                   'url' => $value,
                ];
                if ($checked) {
                    $menu[$k][$key] = true;
                }
                $i++;
            }
        }
        self::$allowMenu = $menu;
        return $list;
    }
}
