<?php

namespace saber\WorkWechat\Core;
use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;

class Collection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    protected $items = [];

    public function __construct($options = [])
    {
        if (!empty($options)) {
            $this->items = $this->convertToArray($options);
        }
    }


    public function all()
    {
        return $this->items;
    }


    /**
     * 合并数组
     * @access public
     * @param mixed $items
     * @return \app\common\lib\requset\core\Collection
     */
    public function merge($items)
    {
        return new static(array_merge($this->items, $this->convertToArray($items)));
    }

    /**
     * 比较数组，返回交集
     *
     * @access public
     * @param mixed $items 数据
     * @param string $indexKey 指定比较的键名
     * @return \saber\VoiceToText\core\Collection
     */
    public function intersect($items, $indexKey = null)
    {
        if ($this->isEmpty() || is_scalar($this->items[0])) {
            return new static(array_diff($this->items, $this->convertToArray($items)));
        }

        $intersect = [];
        $dictionary = $this->dictionary($items, $indexKey);

        if (is_string($indexKey)) {
            foreach ($this->items as $item) {
                if (isset($dictionary[$item[$indexKey]])) {
                    $intersect[] = $item;
                }
            }
        }

        return new static($intersect);
    }

    /**
     * 按指定键整理数据
     *
     * @access public
     * @param mixed $items 数据
     * @param string $indexKey 键名
     * @return array
     */
    public function dictionary($items = null, &$indexKey = null)
    {
        if ($items instanceof self ) {
            $items = $items->all();
        }

        $items = is_null($items) ? $this->items : $items;

        if ($items && empty($indexKey)) {
            $indexKey = is_array($items[0]) ? 'id' : $items[0]->getPk();
        }

        if (isset($indexKey) && is_string($indexKey)) {
            return array_column($items, null, $indexKey);
        }

        return $items;
    }

    /**
     * 返回数组中所有的键名
     *
     * @access public
     * @return array
     */
    public function keys()
    {
        $current = current($this->items);

        if (is_scalar($current)) {
            $array = $this->items;
        } elseif (is_array($current)) {
            $array = $current;
        } else {
            $array = $current->toArray();
        }

        return array_keys($array);
    }

    /**
     *
     * @param $options
     */
    public function make($options)
    {
        $this->items = $this->convertToArray($options);
    }

    /**
     * 删除数组中首个元素，并返回被删除元素的值
     *
     * @access public
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->items);
    }


    /**
     * 把一个数组分割为新的数组块.
     *
     * @access public
     * @param int $size
     * @param bool $preserveKeys
     * @return Collection
     */
    public function chunk($size, $preserveKeys = false)
    {
        $chunks = [];

        foreach (array_chunk($this->items, $size, $preserveKeys) as $chunk) {
            $chunks[] = new static($chunk);
        }

        return new static($chunks);
    }

    /**
     * 给每个元素执行个回调
     * @access public
     * @param callable $callback
     * @return Collection
     */
    public function each(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            $result = $callback($item, $key);

            if (false === $result) {
                break;
            }

            if (!is_object($item)) {
                $this->items[$key] = $result;
            }
        }
        return $this;
    }


    /**
     * 将来item 转化为数组
     * @return array
     */
    public function toArray()
    {
        return $this->convertToArray($this->items);
    }

    public function get($key,$value=null)
    {
        if (isset($this->items[$key])){
            return $this->items[$key];
        }
        if (!empty($value)){
            return $value;
        }
        return  null;
    }

    /**
     * 转换成数组
     *
     * @access public
     * @param mixed $items
     * @return array
     */
    protected function convertToArray($items)
    {
        if ($items instanceof self) {
            return $items->all();
        }

        return (array)$items;
    }

    /**
     * 是否为空
     * @access public
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    public static function create($items = [])
    {
        return new static($items);
    }


    // ArrayAccess
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    //Countable
    public function count()
    {
        return count($this->items);
    }

    //IteratorAggregate
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    //JsonSerializable
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * 转换当前数据集为JSON字符串
     * @access public
     * @param integer $options json参数
     * @return string
     */
    public function toJson($options = JSON_UNESCAPED_UNICODE)
    {
        return json_encode($this->toArray(), $options);
    }

    public function __toString()
    {
        return $this->toJson();
    }

    public function __get($name)
    {

        if ($this->offsetExists($name)) {
            return $this->offsetGet($name);
        }
        return '';
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }
}