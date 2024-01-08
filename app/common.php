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
     * @param string $scene 验证码应用场景 默认为空
    */
    function build_uuid(string $prefix='' ,bool $more_entropy = false)
    {
        return uniqid($prefix, $more_entropy);
    }
}
// +----------------------------------------------------------------------
// | 文件操作函数开始
// +----------------------------------------------------------------------
if (!function_exists('read_file')) {
    /**
     * 获取文件内容
     * @param string $file 文件路径
     * @return false|string content
     */
    function read_file(string $file): bool|string
    {
        return !is_file($file) ? '' : @file_get_contents($file);
    }
}

if (!function_exists('write_file')) {
    /**
     * 数据写入文件
     * @param $file
     * @param $content
     * @return false|int
     */
    function write_file($file, $content): bool|int
    {
        $dir = dirname($file);
        if (!is_dir($dir)) {
            mk_dirs($dir);
        }
        return @file_put_contents($file, $content);
    }
}

if (!function_exists('copy_file')) {
    /**
     * 复制文件
     * @param string $src
     * @param string $dst
     * @return bool
     */
    function copy_file(string $src, string $dst): bool
    {
        $dir = dirname($dst);
        if (!is_dir($dir)) {
            mk_dirs($dir);
        }
        return @copy($src, $dst);
    }
}

if (!function_exists('mk_dirs')) {
    /**
     * 递归创建文件夹
     * @param $path
     * @param int $mode 文件夹权限
     * @return bool
     */
    function mk_dirs($path, int $mode = 0777): bool
    {
        if (!is_dir(dirname($path))) {
            mk_dirs(dirname($path));
        }

        if (!file_exists($path)) {
            return mkdir($path, $mode);
        }

        return true;
    }
}

if (!function_exists('remove_empty_dir')) {
    /**
     * 删除空目录
     * @param string $dir 目录
     */
    function remove_empty_dir(string $dir)
    {
        try {
            if (is_dir($dir)) {
                $handle = opendir($dir);
                while (($file = readdir($handle)) !== false) {
                    if ($file != "." && $file != "..") {
                        remove_empty_dir($dir . "/" . $file);
                    }
                }

                if (!readdir($handle)) {
                    @rmdir($dir);
                }

                closedir($handle);
            }
        } catch (\Exception $e) {
        }
    }
}

if (!function_exists('recursive_delete')) {
    /**
     * 递归删除目录
     */
    function recursive_delete($dir)
    {
        // 打开指定目录
        if ($handle = @opendir($dir)) {

            while (($file = readdir($handle)) !== false) {
                if (($file == ".") || ($file == "..")) {
                    continue;
                }
                if (is_dir($dir . '/' . $file)) { // 递归
                    recursive_delete($dir . '/' . $file);
                } else {
                    unlink($dir . '/' . $file); // 删除文件
                }
            }

            @closedir($handle);
            @rmdir($dir);
        }
    }
}

if (!function_exists('traverse_scanDir')) {
    /**
     * 递归遍历文件夹
     * @param bool $bool 是否递归
     * @param string $dir 文件夹路径
     * @return array
     */
    function traverse_scanDir(string $dir, bool $bool = true): array
    {
        $array = [];
        $handle = opendir($dir);
        while (($file = readdir($handle)) !== false) {
            if ($file != '.' && $file != '..') {
                $child = $dir . '/' . $file;
                if (is_dir($child) && $bool) {
                    $array[$file] = traverse_scanDir($child);
                } else {
                    $array[] = $file;
                }
            }
        }
        return $array;
    }
}

if (!function_exists('copydirs')) {
    /**
     * 复制文件夹
     * @param string $source 源文件夹
     * @param string $dest 目标文件夹
     */
    function copydirs(string $source, string $dest)
    {

        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }

        $handle = opendir($source);
        while (($file = readdir($handle)) !== false) {
            if ($file != "." && $file != "..") {
                if (is_dir($source . "/" . $file)) {
                    copydirs($source . "/" . $file, $dest . "/" . $file);
                } else {
                    copy($source . "/" . $file, $dest . "/" . $file);
                }
            }
        }

        closedir($handle);
    }
}

// +----------------------------------------------------------------------
// | 字符串函数开始
// +----------------------------------------------------------------------
if (!function_exists('delNr')) {
    /**
     * 去掉换行
     * @param string $str 字符串
     * @return string
     */
    function delNr(string $str): string
    {
        $str = str_replace(array("<nr/>", "<rr/>"), array("\n", "\r"), $str);
        return trim($str);
    }
}

if (!function_exists('delNt')) {
    /**
     * 去掉连续空白
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

if (!function_exists('msubstr')) {
    /**
     * 字符串截取(同时去掉HTML与空白)
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

if (!function_exists('pinyin')) {
    /**
     * 获取拼音
     * @param $chinese
     * @param bool $onlyFirst
     * @param string $delimiter
     * @param bool $ucFirst
     * @return string
     */
    function pinyin($chinese, bool $onlyFirst = false, string $delimiter = '', bool $ucFirst = false): string
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

if (!function_exists('format_bytes')) {

    /**
     * 将字节转换为可读文本
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

if (!function_exists('hide_str')) {
    /**
     * 将一个字符串部分字符用*替代隐藏
     * @param string $string 待转换的字符串
     * @param int $begin 起始位置，从0开始计数，当$type=4时，表示左侧保留长度
     * @param int $len 需要转换成*的字符个数，当$type=4时，表示右侧保留长度
     * @param int $type 转换类型：0，从左向右隐藏；1，从右向左隐藏；2，从指定字符位置分割前由右向左隐藏；3，从指定字符位置分割后由左向右隐藏；4，保留首末指定字符串中间用***代替
     * @param string $glue 分割符
     * @return string   处理后的字符串
     */
    function hide_str(string $string, int $begin = 3, int $len = 4, int $type = 0, string $glue = "@")
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

if (!function_exists('linux_time')) {
    /**
     * 获取某天前时间戳
     * @param  $day
     * @return int
     */
    function linux_time($day): int
    {
        $day = intval($day);
        return mktime(23, 59, 59, intval(date("m")), intval(date("d")) - $day, intval(date("y")));
    }
}

if (!function_exists('today_seconds')) {
    /**
     * 返回今天还剩多少秒
     * @return int
     */
    function today_seconds(): int
    {
        $mtime = mktime(23, 59, 59, intval(date("m")), intval(date("d")), intval(date("y")));
        return $mtime - time();
    }
}