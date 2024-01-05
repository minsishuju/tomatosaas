<?php declare (strict_types=1);
/*
 * TomatoAdmin 基础开发框架[tomatosaas]
 * HelperArr create at 2024/1/5 14:32
 * Author: 七彩枫叶 Email：424235748@qq.com
*/

namespace tomato\helper;


class HelperArr
{
    /**
     * 数组过滤
     * @param array $array 需要过滤的数组
     * @param array $filter 要过滤的内容数组 默认值包含空字符串
     */
    public static function tm_array_filter(array $array,array $filter=[]):array
    {
        if(count($filter)==0)
        {
            $filter=[''];
        }
        return array_filter($array, function($v)use($filter) {
            return !in_array($v,$filter);
        },0);
    }

    /**
     * 将二维数组的键换成某一列的值
     * @param array $array 原来的二维数组
     * @param string $key 指定二维数组中一维数组的列的下标
     */
    public static function tm_array_change_key(array $array, string $key)
    {
        return array_combine(array_column($array, $key), $array);
    }

    /**
     * 二维数组根据某个字段排序
     * @param array $array  要排序的数组
     * @param string $keys  要排序的键字段
     * @param int|string $sort  排序类型  SORT_ASC     SORT_DESC
     * @return array 排序后的数组
     */
    public static function tm_array_sort(array $array, string $keys, int|string $sort = SORT_DESC): array
    {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }

    /**
     * 二维数组转三维数组  按照指定字段进行分组
     * @param array $array 需要分组的二维数组
     * @param string $group_field 指定分组依据字段
     * @return array
     */
    public static function tm_array_subgroup(array $array,string $group_field): array
    {
        $data=[];
        array_map(function( $array ) use ( &$data ,$group_field) {
            $data[ $array[$group_field]][$array['name']] = $array;
        }, $array);

        return  $data;
    }

    /**
     * 向二维数组中每个一维数组中添加元素
     * @param array $array 原来的二维数组
     * @param array $parameter 要添加的数组
     * @return array
     */
    public static function tm_array_add_child(array $array,array $parameter)
    {
        array_walk($array, function (&$value, $key, $parameter) {
            $value = array_merge($value, $parameter);
        },$parameter);
        return $array;
    }

    /**
     * 保留二维数组中的每个一位数组中指定key的元素
     * @param array $array 原来的二维数组
     * @param array $parameter 要添加的数组
     * @return array
     */
    public static function tm_array_save_child(array $array,array $parameter)
    {
        array_walk($array, function (&$value, $key, $parameter) {
            $value = array_intersect_key($value,array_flip($parameter));
        },$parameter);
        return $array;
    }


    /**
     * 两个数组进行比较差集
     * @param array $array
     * @param array $second
     * @param string $option    key - 使用键名比较计算数组的差集
     */
    public static function tm_array_diff(array $array,array $second,$option)
    {

    }
    /**
     * 两个数组进行比较交集
     * @param array $array
     * @param array $second
     * @param string $option    key - 使用键名比较计算数组的交集
     */
    public static function tm_array_intersect(array $array,array $second,$option)
    {

    }
}