<?php

/**
 * MemcachedHelper 缓存管理
 * @author Administrator
 *
 */
class MemcachedHelper
{

    /**
     * 声明静态成员变量 $m:表示类内部维护的memcached连接对象
     */
    private static $m = NULL;

    /**
     * $cache：缓存当前类的实例对象
     */
    private static $cache = NULL;

    /**
     * 私有构造中初始化当前类的实例对象
     */
    private function __construct()
    {
        // 在CLI下运行失败
        // 原因未知
        self::$m = new Memcache();
        self::$m->connect('127.0.0.1', '11211');
        
        // 或者
        // self::$m = memcache_connect('127.0.0.1', 11211);
    }

    private static function get_instance()
    {
        self::$cache = new MemcachedHelper();
        return self::$m;
    }

    // **************************************开始实现方法*************************************** //
    
    /**
     * 添加缓存数据
     * $time=0 表示永久有效
     *
     * @param string $key
     *            获取数据唯一key
     * @param String|Array $value
     *            缓存数据
     * @param $time memcache生存周期(秒)
     *            86400【一天】 默认三天：259200
     */
    public static function addKey($key, $value, $time)
    {
        self::get_instance()->set($key, $value, 0, $time);
    }

    /**
     * 获取缓存数据
     * 获取失败返回：false
     *
     * @param string $key
     */
    public static function getKey($key)
    {
        return self::get_instance()->get($key);
    }

    /**
     * 删除对应缓存数据
     *
     * @param string $key
     */
    public static function delKey($key)
    {
        self::get_instance()->delete($key);
    }

    /**
     * 删除所有缓存数据
     */
    public static function delAllKey()
    {
        self::get_instance()->flush();
    }

    /**
     * 获取memcached的状态
     */
    public static function getStats()
    {
        return self::get_instance()->getStats();
    }

    // **************************************加入表级别缓存*************************************** //
    
    private static function insertKey($tabName, $key)
    {
        // 先获取与指定表 相关的缓存keys【keys是个数组】
        $keys = self::get_instance()->get($tabName);
        
        // empty:可以用来坚持数组是空数组 此处没问题
        if(empty($keys))
        {
            $keys = array();
        }
        
        // 如果key不存在,就添加一个
        if(! in_array($key, $keys))
        {
            $keys[] = $key; // 将新的key添加到本表的keys中
            self::get_instance()->set($tabName, $keys, MEMCACHE_COMPRESSED, 0);
            // 不存在返回true
            return true;
        }
        else
        {
            // 存在返回false
            return false;
        }
        // 【备注】此函数的实现在于在添加Key的同时 写入一个“表名字”作为键 然后值是一个数组 数组保留和该表相关的所有缓存
        // 在一个表中可以有和该表相关的很多缓存
    }

    /**
     * 向memcache中添加数据
     * param string $tabName 需要缓存数据表的表名
     * param string $sql 使用sql作为memcache的key
     * param mixed $data 需要缓存的数据
     */
    public static function addCache($tabName, $sql, $data)
    {
        $key = md5($sql);
        // 如果不存在
        if(self::insertKey($tabName, $key))
        // if($this->addKey($tabName, $key))
        {
            self::get_instance()->set($key, $data, MEMCACHE_COMPRESSED, 0);
        }
    }

    /**
     * 获取memcahce中保存的数据
     * param string $sql 使用SQL的key
     * return mixed 返回缓存中的数据
     */
    public static function getCache($sql)
    {
        $key = md5($sql);
        return self::get_instance()->get($key);
    }

    /**
     * 删除和同一个表相关的所有缓存
     * param string $tabName 数据表的表名
     */
    public static function delCache($tabName)
    {
        $keys = self::get_instance()->get($tabName);
        
        // 删除同一个表的所有缓存
        if(! empty($keys))
        {
            foreach ($keys as $key)
            {
                // 0 表示立刻删除
                self::get_instance()->delete($key, 0);
            }
        }
        // 删除表的所有sql的key
        self::get_instance()->delete($tabName, 0);
    }

    /**
     * 删除单独一个语句的缓存
     * param string $sql 执行的SQL语句
     */
    public static function delone($sql)
    {
        $key = md5($sql);
        // 0 表示立刻删除
        self::get_instance()->delete($key, 0);
    }
    // **************************************结束表级别缓存*************************************** //
}

?>

<?php
// 获取状态
var_dump(MemcachedHelper::getStats());

// $key = 'myKey';
// $value = 'insert into 12334234';
// // 默认十秒
// $time = 10;

// // 测试失效时间
// // 写入缓存
// // var_dump(MemcachedHelper::addKey($key, $value, $time));

// // 无效
// // var_dump(Memcached::getResultCode());
// // sleep(20);

// // 读取缓存
// var_dump(MemcachedHelper::getKey($key));

// // 问题：如果删除所有缓存之后再获取
// // var_dump(MemcachedHelper::delAllKey());
// // var_dump(MemcachedHelper::getKey($key));

// // 问题：如果删除指定缓存之后再获取
// // var_dump(MemcachedHelper::delKey($key));
// // var_dump(MemcachedHelper::getKey($key));

// // 10秒之后再获取

?>

<?php

// 测试表级别缓存
// 模拟数据定义

$table = "products";

$sqla = "Select * from1";
$sqlb = "Select * fromb";

// 模拟sqla 查询出的数据
$sqldataA = array(
    1,
    2,
    3,
    4
);
$sqldataB = array(
    9,
    8,
    7,
    6
);

// 开始插入
MemcachedHelper::addCache($table, $sqla, $sqldataA);
MemcachedHelper::addCache($table, $sqlb, $sqldataB);

// 获取
var_dump(MemcachedHelper::getCache($sqla));
var_dump(MemcachedHelper::getCache($sqlb));

// 删除
MemcachedHelper::delCache($table);

// 再次获取
var_dump(MemcachedHelper::getCache($sqla)); // false
var_dump(MemcachedHelper::getCache($sqlb)); // false

?>













