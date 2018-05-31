<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/5/31
 * Time: 18:06
 */

namespace huoybb\core;


use ArrayAccess;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class myExcel implements ArrayAccess
{
    use \huoybb\core\myPresenterTrait;
    public static $file;//excel文件的名称
    public static $worksheet;//工作表名称
    public static $range;//抽取数据的范围
    public static $rowsets;//数据缓存，避免多次提取，不知道是否有作用
    public $data;//单条数据的缓存，应是一个数组

    /**
     * process constructor.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public static function findByID($ID)
    {
        $data = static::getData()
            ->first(function($row,$key) use ($ID){
                return $key == $ID;
            });
        $data['id'] = $ID;
        return new static($data);
    }

    public static function getData($file=null,$worksheet=null, $range=null){
        $file = $file ?? static::$file;
        $worksheet = $worksheet ?? static::$worksheet;
        $range = $range ?? static::$range;

        try {
            $spreadsheet = IOFactory::load($file);
            $data = $spreadsheet->getSheetByName($worksheet)->rangeToArray($range, NULL, TRUE, TRUE, TRUE);

        } catch (Exception $e) {
            dd($e->getMessage());
        }

        return collect($data);
    }

    /**
     * @param $column
     * @param $keyword
     * @return static[] | \Illuminate\Support\Collection
     */
    public static function filterByColumn($column, $keyword)
    {
        return static::getData()
            ->filter(function($row) use ($column,$keyword){
                if(is_callable($keyword)) return $keyword($row[$column]);
                return preg_match('%'.$keyword.'%i',$row[$column]);
            })
            ->map(function($row){ return new static($row);});
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function findAll()
    {
        if(!static::$rowsets) static::$rowsets = static::getData()->map(function($row,$key){
            $row['id'] = $key;
            return new static($row);
        });
        return static::$rowsets;
    }
    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset] ?? null;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function __get($property)
    {
        return $this->data[$property] ?? null;
    }

}