<?php

namespace App\Classes;

use App\Models\Setting;

class MapTencent
{
    /**
     * 腾讯地图
     * https://lbs.qq.com/dev/console/key/setting
     * @param  [type] $address [description]
     * @return [type]          [description]
     */
    public static function get($address)
    {
        $res = Setting::where('title', 'tx_map_key')->where('seller_id', 0)->first();
        $key     = $res['content'] ?? '';
        $address = urlencode($address);
        $url = "https://apis.map.qq.com/ws/geocoder/v1/?address=" . $address . "&key=" . $key;
        $res = self::get_request($url);
        $res = json_decode($res, true);
        return $res['result']['location'];
    }
    public static function get_request($url)
    {
        $context = stream_context_create(array(
            'http' => array(
                'timeout' => 3000
            )
        ));
        $res = file_get_contents($url, 0, $context);
        return $res;
    }
}
