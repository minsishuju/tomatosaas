<?php
// 应用公共文件
use think\facade\Lang;

// +----------------------------------------------------------------------
// | 常用助手函数
// +----------------------------------------------------------------------
if (!function_exists('__')) {

    /**
     * 语言翻译
     * @param string $name 被翻译字符
     * @param array  $vars 替换字符数组
     * @param string $lang 翻译语言
     * @return mixed
     */
    function __(string $name, array $vars = [], string $lang = ''): mixed
    {
        if (is_numeric($name) || !$name) {
            return $name;
        }
        return Lang::get($name, $vars, $lang);
    }
}

if (!function_exists('get_sys_config')) {

    /**
     * 获取站点的系统配置，不传递参数则获取所有配置项
     * @param string $name    变量名
     * @param string $group   变量分组，传递此参数来获取某个分组的所有配置项
     * @param bool   $concise 是否开启简洁模式，简洁模式下，获取多项配置时只返回配置的键值对
     * @return mixed
     * @throws Throwable
     */
    function get_sys_config(string $name = '', string $group = '', bool $concise = true): mixed
    {
        if ($name) {
            // 直接使用->value('value')不能使用到模型的类型格式化
            $config = configModel::cache($name, null, configModel::$cacheTag)->where('name', $name)->find();
            if ($config) $config = $config['value'];
        } else {
            if ($group) {
                $temp = configModel::cache('group' . $group, null, configModel::$cacheTag)->where('group', $group)->select()->toArray();
            } else {
                $temp = configModel::cache('sys_config_all', null, configModel::$cacheTag)->order('weigh desc')->select()->toArray();
            }
            if ($concise) {
                $config = [];
                foreach ($temp as $item) {
                    $config[$item['name']] = $item['value'];
                }
            } else {
                $config = $temp;
            }
        }
        return $config;
    }
}

if(!function_exists('build_uuid'))
{
    /**
     * @param string $prefix
     * @param bool $more_entropy
     * @return string
     */
    function build_uuid(string $prefix='' ,bool $more_entropy = false): string
    {
        return uniqid($prefix, $more_entropy);
    }
}


// +----------------------------------------------------------------------
// | 文件操作函数开始
// +----------------------------------------------------------------------

// 检测目录是否存在
if (!function_exists('check_dir')) {
    /**
     * @param string $path 目录路径
     * @param bool $create 目录不存在时，是否创建
     * @return bool
     */
    function check_dir(string $path, bool $create = false): bool
    {
        if (is_dir($path)) {
            return true;
        } elseif ($create) {
            return create_dir($path);
        }
    }
}
// 创建目录
if (!function_exists('create_dir')) {
    /**
     * @param string $path 目录路径
     * @return bool
     */
    function create_dir(string $path): bool
    {
        if (!file_exists($path)) {
            if (mkdir($path, 0777, true)) {
                return true;
            }
        }
        return false;
    }
}
// 检查文件是否存在
if (!function_exists('check_file')) {
    /**
     * @param string $path 文件路径
     * @param bool $create 指定文件不存在时，是否创建
     * @param string|null $content
     * @return bool
     */
    function check_file(string $path, bool $create = false, string|null $content = null): bool
    {
        if (file_exists($path)) {
            return true;
        } elseif ($create) {
            return create_file($path, $content);
        }
    }
}
// 创建文件
if (!function_exists('create_file')) {
    /**
     * @param string $path 文件路径
     * @param string|null $content 文件内容
     * @param bool $over 是否覆盖
     * @return bool
     */
    function create_file(string $path, string $content = null, bool $over = false): bool
    {
        if (file_exists($path) && !$over) {
            return false;
        } elseif (file_exists($path)) {
            @unlink($path);
        }
        check_dir(dirname($path), true);
        $handle = fopen($path, 'w') or error('创建文件失败，请检查目录权限！');
        fwrite($handle, $content);
        return fclose($handle);
    }
}
// 获取目录文件夹列表
if (!!function_exists('dir_list')) {
    /**
     * @param string $path 指定的目录
     * @return array
     */
    function dir_list(string $path): array
    {
        $list = array();
        if (!is_dir($path) || !$filename = scandir($path)) {
            return $list;
        }
        $files = count($filename);
        for ($i = 0; $i < $files; $i++) {
            $dir = $path . '/' . $filename[$i];
            if (is_dir($dir) && $filename[$i] != '.' && $filename[$i] != '..') {
                $list[] = $filename[$i];
            }
        }
        return $list;
    }
}
// 获取指定目录的文件列表
if (!function_exists('file_list')) {
    /**
     * @param string $path 指定的目录
     * @return array
     */
    function file_list(string $path): array
    {
        $list = array();
        if (!is_dir($path) || !$filename = scandir($path)) {
            return $list;
        }
        $files = count($filename);
        for ($i = 0; $i < $files; $i++) {
            $dir = $path . '/' . $filename[$i];
            if (is_file($dir)) {
                $list[] = $filename[$i];
            }
        }
        return $list;
    }
}
// 获取指定目录下的文件及文件夹列表
if (!function_exists('path_list')) {
    /**
     * @param string $path 指定的目录
     * @return array
     */
    function path_list(string $path): array
    {
        $list = array();
        if (!is_dir($path) || !$filename = scandir($path)) {
            return $list;
        }
        $files = count($filename);
        for ($i = 0; $i < $files; $i++) {
            $dir = $path . '/' . $filename[$i];
            if (is_file($dir) || (is_dir($dir) && $filename[$i] != '.' && $filename[$i] != '..')) {
                $list[] = $filename[$i];
            }
        }
        return $list;
    }
}
// 删除目录及目录下所有文件或删除指定文件
if (!function_exists('path_delete')) {
    /**
     * @param string $path 待删除目录路径
     * @param bool $delDir 是否删除目录，true删除目录，false则只删除文件保留目录
     * @return bool           返回删除状态
     */
    function path_delete(string $path, bool $delDir = false, array $exFile = array())
    {
        $result = true; // 对于空目录直接返回true状态
        if (!file_exists($path)) {
            return $result;
        }
        if (is_dir($path)) {
            if (!!$dirs = scandir($path)) {
                foreach ($dirs as $value) {
                    if ($value != "." && $value != ".." && !in_array($value, $exFile)) {
                        $dir = $path . '/' . $value;
                        $result = is_dir($dir) ? path_delete($dir, $delDir, $exFile) : unlink($dir);
                    }
                }
                if ($result && $delDir) {
                    return rmdir($path);
                } else {
                    return $result;
                }
            } else {
                return false;
            }
        } else {
            return unlink($path);
        }
    }
}
// 获取目录和子目录下所有文件
if (!function_exists('get_dir')) {
    /**
     * @param string $path 指定的目录
     * @return array
     */
    function get_dir(string $path): array
    {
        $files = array();
        if (is_dir($path)) {
            if ($handle = opendir($path)) {
                while (($file = readdir($handle)) !== false) {
                    if ($file != "." && $file != ".." && $file != "file") {
                        if (is_dir($path . "/" . $file)) {
                            $files[$file] = get_dir($path . "/" . $file);
                        } else {
                            $files[] = $path . "/" . $file;
                        }
                    }
                }
                closedir($handle);
                return $files;
            }
        }
        return $files;
    }
}
// 拷贝文件夹
if (!function_exists('dir_copy')) {
    /**
     * @param string $src 文件路径
     * @param string $des 复制到哪里的 路径
     * @param bool $son 是否复制子文件夹
     * @return bool
     */
    function dir_copy(string $src, string $des, bool $son = true): bool
    {
        if (!is_dir($src)) {
            return false;
        }

        if (!is_dir($des)) {
            create_dir($des);
        }
        $handle = dir($src);
        while (!!$path = $handle->read()) {
            if (($path != ".") && ($path != "..")) {
                if (is_dir($src . "/" . $path)) {
                    if ($son)
                        dir_copy($src . "/" . $path, $des . "/" . $path, $son);
                } else {
                    copy($src . "/" . $path, $des . "/" . $path);
                }
            }
        }
        return true;
    }
}
// 检查文件是不是指定的图片格式
if (!function_exists('is_image')) {
    /**
     * @param string $path 指定的文件路径
     * @return bool
     */
    function is_image(string $path): bool
    {
        $types = '.gif|.jpeg|.png|.bmp|.webp|.svg|.apng'; // 定义检查的图片类型
        if (file_exists($path)) {
            $info = getimagesize($path);
            $ext = image_type_to_extension($info['2']);
            if (stripos($types, $ext) !== false)
                return true;
        }
        return false;
    }
}

// +----------------------------------------------------------------------
// | 时间相关函数开始
// +----------------------------------------------------------------------

if (!function_exists('set_timezone')) {

    /**
     * 设置时区
     * @throws Throwable
     */
    function set_timezone($timezone = null): void
    {
        $defaultTimezone = Config::get('app.default_timezone');
        $timezone        = is_null($timezone) ? get_sys_config('time_zone') : $timezone;
        if ($timezone && $defaultTimezone != $timezone) {
            Config::set([
                'app.default_timezone' => $timezone
            ]);
            date_default_timezone_set($timezone);
        }
    }
}
// 获取某天前时间戳
if (!function_exists('linux_time')) {
    /**
     * @param int $day 天数
     * @return int 返回时间戳
     */
    function linux_time(int $day): int
    {
        $day = intval($day);
        return mktime(23, 59, 59, intval(date("m")), intval(date("d")) - $day, intval(date("y")));
    }
}
// 返回今天还剩多少秒
if (!function_exists('today_seconds')) {
    /**
     * @return int
     */
    function today_seconds(): int
    {
        $mtime = mktime(23, 59, 59, intval(date("m")), intval(date("d")), intval(date("y")));
        return $mtime - time();
    }
}
// 计算给定的时间，与现在的时间比较，相差多少天数
if (!function_exists('distance_day')) {
    /**
     * @param $time
     * @return mixed
     */
    function distance_day($time): mixed
    {
        if (!$time) {
            return false;
        }

        if (!is_numeric($time)) {
            $time = strtotime($time);
        }

        $time = time() - $time;
        return ceil($time / (60 * 60 * 24));
    }
}
// 返回时间戳格式化日期时间，默认当前
if (!function_exists('get_datetime')) {
    /**
     * @param int|null $timestamp 时间戳
     * @param string $style 返回的时间格式 默认 Y-m-d H:i:s
     * @return string
     */
    function get_datetime(int $timestamp = null, string $style = 'Y-m-d H:i:s'): string
    {
        if (!$timestamp)
            $timestamp = time();
        return date($style, $timestamp);
    }
}
// 返回时间戳格式化日期，默认当前
if (!function_exists('get_date')) {
    /**
     * @param int|null $timestamp
     * @return string
     */
    function get_date(int $timestamp = null): string
    {
        if (!$timestamp)
            $timestamp = time();
        return date('Y-m-d', $timestamp);
    }
}
// 返回时间戳差值部分，年、月、日
if (!function_exists('get_date_diff')) {
    /**
     * @param int $startstamp 开始时间戳
     * @param int $endstamp 结束时间戳
     * @param string $return 返回的内容部分 Y 年数 m 月数  d 天数
     */
    function get_date_diff(int $startstamp, int $endstamp, string $return = 'm'): string
    {
        $y = date('Y', $endstamp) - date('Y', $startstamp);
        $m = date('m', $endstamp) - date('m', $startstamp);

        switch ($return) {
            case 'y':
                if ($y <= 1) {
                    $y = $m / 12;
                }
                $string = $y;
                break;
            case 'm':
                $string = $y * 12 + $m;
                break;
            case 'd':
                $string = ($endstamp - $startstamp) / 86400;
                break;
        }
        return $string;
    }
}
// 发布格式化时间  例如 2天前 2小时前
if (!function_exists('published_date')) {
    /**
     * @param [type] $time
     * @param bool $unix
     * @return string
     */
    function published_date($time, bool $unix = true): string
    {
        if (!$unix) {
            $time = strtotime($time);
        }
        $currentTime = time() - $time;
        $published = array(
            '86400' => '天',
            '3600' => '小时',
            '60' => '分钟',
            '1' => '秒'
        );
        if ($currentTime == 0) {
            return '1秒前';
        } else if ($currentTime >= 604800 || $currentTime < 0) {
            return date('Y-m-d H:i:s', $time);
        } else {
            foreach ($published as $k => $v) {
                if (0 != $c = floor($currentTime / (int)$k)) {
                    return $c . $v . '前';
                }
            }
        }
        return date('Y-m-d H:i:s', $time);
    }
}
// 判断给定时间是否为当天
if (!function_exists('is_today')) {
    /**
     * @param string|int $time 指定时间
     * @return bool
     */
    function is_today(string|int $time): bool
    {
        if (!$time) {
            return false;
        }
        $today = date('Y-m-d');
        if (strstr($time, '-')) {
            $time = strtotime($time);
        }
        if ($today == date('Y-m-d', $time)) {
            return true;
        } else {
            return false;
        }
    }
}

// +----------------------------------------------------------------------
// | 字符串处理相关
// +----------------------------------------------------------------------

// 验证密码强度 不能小于6位
if (!function_exists('pass_strength')) {
    /**
     * @param string $password 密码明文
     * @return bool
     */
    function pass_strength(string $password): bool
    {
        $strength = 0;
        if (strlen($password) < 6) {
            return false;
        }
        if (strlen($password) >= 8) {
            $strength += 1;
        }
        if (preg_match('/[a-z]+/', $password)) {
            $strength += 1;
        }
        if (preg_match('/[A-Z]+/', $password)) {
            $strength += 1;
        }
        if (preg_match('/\d+/', $password)) {
            $strength += 1;
        }
        if (preg_match('/\W+/', $password)) {
            $strength += 1;
        }
        if ($strength >= 3) {
            return true;
        } else {
            return false;
        }
    }
}
// 验证输入的手机号码
if (!function_exists('is_mobile')) {
    /**
     * 正则表达式判断手机号
     * @param $mobile
     * @return bool
     */
    function is_mobile($mobile): bool
    {
        if (preg_match('/^1[3456789]\d{9}$/', $mobile)) {
            return true;
        } else {
            return false;
        }
    }
}
// 获取指定长度的随机字母数字组合的字符串
if (!function_exists('random')) {
    /**
     * @param int $length
     * @param int|null $type
     * @param string $addChars
     * @return string
     */
    function random(int $length = 6, int $type = null, string $addChars = ''): string
    {
        $str = '';
        $chars = match ($type) {
            0 => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars,
            1 => str_repeat('0123456789', 3),
            2 => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars,
            3 => 'abcdefghijklmnopqrstuvwxyz' . $addChars,
            4 => "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书" . $addChars,
            default => 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars,
        };
        if ($length > 10) {
            $chars = $type == 1 ? str_repeat($chars, $length) : str_repeat($chars, 5);
        }
        if ($type != 4) {
            $chars = str_shuffle($chars);
            $str = substr($chars, 0, $length);
        } else {
            for ($i = 0; $i < $length; $i++) {
                $str .= mb_substr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
            }
        }
        return $str;
    }
}
// 将一个字符串部分字符用*替代隐藏
if (!function_exists('hide_str')) {
    /**
     * @param string $string 待转换的字符串
     * @param int $begin 起始位置，从0开始计数，当$type=4时，表示左侧保留长度
     * @param int $len 需要转换成*的字符个数，当$type=4时，表示右侧保留长度
     * @param int $type 转换类型：0，从左向右隐藏；1，从右向左隐藏；2，从指定字符位置分割前由右向左隐藏；3，从指定字符位置分割后由左向右隐藏；4，保留首末指定字符串中间用***代替
     * @param string $glue 分割符
     * @return string   处理后的字符串
     */
    function hide_str(string $string, int $begin = 3, int $len = 4, int $type = 0, string $glue = "@"): bool|string
    {
        if (empty($string)) {
            return false;
        }

        $array = array();
        if ($type == 0 || $type == 1 || $type == 4) {
            $strlen = $length = mb_strlen($string);
            while ($strlen) {
                $array[] = mb_substr($string, 0, 1, "utf8");
                $string = mb_substr($string, 1, $strlen, "utf8");
                $strlen = mb_strlen($string);
            }
        }
        if ($type == 0) {
            for ($i = $begin; $i < ($begin + $len); $i++) {
                if (isset($array[$i])) {
                    $array[$i] = "*";
                }
            }
            $string = implode("", $array);
        } elseif ($type == 1) {
            $array = array_reverse($array);
            for ($i = $begin; $i < ($begin + $len); $i++) {
                if (isset($array[$i])) {
                    $array[$i] = "*";
                }
            }
            $string = implode("", array_reverse($array));
        } elseif ($type == 2) {
            $array = explode($glue, $string);
            if (isset($array[0])) {
                $array[0] = hide_str($array[0], $begin, $len, 1);
            }
            $string = implode($glue, $array);
        } elseif ($type == 3) {
            $array = explode($glue, $string);
            if (isset($array[1])) {
                $array[1] = hide_str($array[1], $begin, $len, 0);
            }
            $string = implode($glue, $array);
        } elseif ($type == 4) {
            $left = $begin;
            $right = $len;
            $tem = array();
            for ($i = 0; $i < ($length - $right); $i++) {
                if (isset($array[$i])) {
                    $tem[] = $i >= $left ? "" : $array[$i];
                }
            }
            $tem[] = '*****';
            $array = array_chunk(array_reverse($array), $right);
            $array = array_reverse($array[0]);
            for ($i = 0; $i < $right; $i++) {
                if (isset($array[$i])) {
                    $tem[] = $array[$i];
                }
            }
            $string = implode("", $tem);
        }
        return $string;
    }
}
//将字节转换为可读文本
if (!function_exists('format_bytes')) {

    /**
     * @param int $size 大小
     * @param string $delimiter 分隔符
     * @return string
     */
    function format_bytes(int $size, string $delimiter = ' '): string
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 6; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $delimiter . $units[$i];
    }
}
// 获取拼音
if (!function_exists('pinyin')) {
    /**
     * @param string $chinese
     * @param bool $onlyFirst
     * @param string $delimiter
     * @param bool $ucFirst
     * @return string
     */
    function pinyin(string $chinese, bool $onlyFirst = false, string $delimiter = '', bool $ucFirst = false): string
    {
        $pinyin = new Overtrue\Pinyin\Pinyin();
        $result = $onlyFirst ? $pinyin->abbr($chinese, $delimiter) : $pinyin->permalink($chinese, $delimiter);
        if ($ucFirst) {
            $pinyinArr = explode($delimiter, $result);
            $result = implode($delimiter, array_map('ucfirst', $pinyinArr));
        }

        return $result;
    }
}
// 字符串截取(同时去掉HTML与空白)
if (!function_exists('msubstr')) {
    /**
     * @param string $str
     * @param int $start
     * @param int $length
     * @param string $charset
     * @param bool $suffix
     * @return string
     */
    function msubstr(string $str, int $start = 0, int $length = 100, string $charset = "utf-8", bool $suffix = true): string
    {

        $str = preg_replace('/<[^>]+>/', '', preg_replace("/[\r\n\t ]{1,}/", ' ', delNt(strip_tags($str))));
        $str = preg_replace('/&(\w{4});/i', '', $str);

        // 直接返回
        if ($start == -1) {
            return $str;
        }

        if (function_exists("mb_substr")) {
            $slice = mb_substr($str, $start, $length, $charset);
        } elseif (function_exists('iconv_substr')) {
            $slice = iconv_substr($str, $start, $length, $charset);

        } else {
            $re['utf-8'] = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
            $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
            $re['gbk'] = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
            $re['big5'] = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("", array_slice($match[0], $start, $length));
        }

        $fix = '';
        if (strlen($slice) < strlen($str)) {
            $fix = '...';
        }
        return $suffix ? $slice . $fix : $slice;
    }
}
// 获取静态版本
if (!function_exists('release')) {
    /**
     * @return mixed
     */
    function release(): mixed
    {
        return config('app.version');
    }
}
// 去掉换行
if (!function_exists('delNr')) {
    /**
     * @param string $str 字符串
     * @return string
     */
    function delNr(string $str): string
    {
        $str = str_replace(array("<nr/>", "<rr/>"), array("\n", "\r"), $str);
        return trim($str);
    }
}
// 去掉连续空白
if (!function_exists('delNt')) {
    /**
     * @param string $str 字符串
     * @return string
     */
    function delNt(string $str): string
    {
        $str = str_replace("　", ' ', str_replace("", ' ', $str));
        $str = preg_replace("/[\r\n\t ]{1,}/", ' ', $str);
        return trim($str);
    }
}
// 读取文件内容
if (!function_exists('read_file')) {
    /**
     * @param string $file 文件路径
     * @return false|string content
     */
    function read_file(string $file): bool|string
    {
        return !is_file($file) ? '' : @file_get_contents($file);
    }
}
// 将数组转为字符串
if (!function_exists('var_exports')) {
    /**
     * 数组语法(方括号)
     * @param array $expression 数组
     * @param bool $return 返回类型
     * @return string
     */
    function var_exports(array $expression, bool $return = true)
    {
        $export = var_export($expression, true);
        $patterns = [
            "/array \(/" => '[',
            "/^([ ]*)\)(,?)$/m" => '$1]$2',
            "/=>[ ]?\n[ ]+\[/" => '=> [',
            "/([ ]*)(\'[^\']+\') => ([\[\'])/" => '$1$2 => $3',
        ];

        $export = preg_replace(array_keys($patterns), array_values($patterns), $export);
        if ($return) {
            return $export;
        } else {
            echo $export;
        }

        return false;
    }
}
//数组写入文件
if (!function_exists('arr2file')) {
    /**
     * @param string $file 文件路径
     * @param array $array 数组数据
     * @return false|int
     */
    function arr2file(string $file, $array = '')
    {
        if (is_array($array)) {
            $cont = var_exports($array);
        } else {
            $cont = $array;
        }
        $cont = "<?php\nreturn $cont;";
        return create_file($file, $cont);
    }
}

// +----------------------------------------------------------------------
// | 数据加密函数开始
// +----------------------------------------------------------------------

// hash - 密码加密
if (!function_exists('encryptPwd')) {
    /**
     * @param string $pwd 明文密码
     * @param string $salt 密码盐
     * @param string $encrypt 加密方式
     */
    function encryptPwd(string $pwd, string $salt = 'swift', string $encrypt = 'md5')
    {
        return $encrypt($pwd . $salt);
    }
}
// 字符串双层MD5加密 没有加盐
if (!function_exists('encrypt_string')) {
    /**
     * @param string $string 需要加密的字符串
     * @return string
     */
    function encrypt_string(string $string): string
    {
        return md5(md5($string));
    }
}

