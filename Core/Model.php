<?php

namespace Core;

use App\Config\Config;

class Model extends \Vendor\PDOWrapper
{

	public function __construct()
    {
      $con = Config::$arr;
      parent::__construct($con);
    }
	public static function create($classname=null)
	{
		if($classname===null){
            $classname=get_called_class();
		}
		static $instance=array();
		if( !isset($instance[$classname])){
			$instance[$classname] =new $classname();
		} 
		return $instance[$classname];
	}
	public function selectAll($where =' 2 > 1')
	{   
		//static::$stable是指调取子类的静态属性
        $sql = "SELECT * FROM `".static::$table."` WHERE {$where} ";
        return  $this->getAll($sql);    
 	}
 	public function selectOne($where)
	{   
		//static::$stable是指调取子类的静态属性
        $sql = "SELECT * FROM `".static::$table."` WHERE {$where} ";
        return  $this->getOne($sql);    
 	}
 	public function add($arr = array())
 	{   
 		$fields = '';
 		$fieldsvalue = '';
        foreach ($arr as $field => $fieldvalue) {
           $fields .= "`{$field}`,";
           $fieldsvalue .= "'{$fieldvalue}',";  
        }
        $fields = rtrim($fields, ',');//将字符末尾字符去掉
        $fieldsvalue = rtrim($fieldsvalue, ',');
 		$sql = "INSERT INTO `".static::$table."` ({$fields}) VALUES ({$fieldsvalue})";
 		return $this->exec($sql);
 	}
 	public function update($arr = array(), $id)
 	{ 
 		/*$sets = "";
        foreach ($arr as $field => $fieldValue) {
            $sets .= "`{$field}`='{$fieldValue}',";
        }
        $sets = rtrim($sets, ',');
        $table = static::$table;
        $sql = "UPDATE `{$table}` SET {$sets} WHERE id='{$id}'";
        return $this->exec($sql);*/
 		$sets = "";
        foreach ($arr as $field => $fieldvalue) {
            $sets .= "`{$field}`='{$fieldvalue}',";
        }
        $sets = rtrim($sets, ',');
       
 		$sql = "UPDATE `".static::$table."` SET {$sets} WHERE id = '{$id}'";
 		return $this->exec($sql);
 	}
 	public function delete($id)
 	{
      $sql = "DELETE FROM `".static::$table."` WHERE id in ({$id})";
      return $this->exec($sql);
 	}
 	/*public function count($where =' 2 > 1')
 	{
 		$sql = "SELECT * FROM `".static::$table."` WHERE {$where} ";
 		return $this->getCount($sql);
 	}*/
    public function getCount($where =' 2 > 1')
    {
        $sql = "SELECT count(*) as a FROM `".static::$table."` WHERE {$where} ";
        return $this->getOne($sql)->a;
    }
}

?>