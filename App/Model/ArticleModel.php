<?php

namespace App\Model;

class ArticleModel extends \Core\Model
{
    public static $table='article';

    public function selectJoin($where='2>1',/*$start = false, $pageSize = false */$pageStart=null, $pageSize=null)
    {
        
    	$sql = "SELECT `article`.* , `category`.`name` as category_name , `user`.`name` as user_name , count(`comment`.`id`) as article_comment
            FROM `article` 
            left join `category` on `article`.`category_id` = `category`.`id` 
            left join `user` on `article`.`user_id` = `user`.`id` 
            left join `comment` on `article`.`id` = `comment`.`article_id`
            where {$where} 
            group by `article`.`id`";
    	
		
    	if (!empty($pageSize)) {

    		$sql .= " limit {$pageStart},{$pageSize}";
    	} /*
    	  if ($start !== false) {
            $sql .= " LIMIT {$start}, {$pageSize}";
        }*/
    	/*echo "<pre>";
		print_r($sql);
        echo "</pre>";*/
    	return $this->getAll($sql); 
    }
    public function addRead($id)
    {
        $sql = <<<SQL
        UPDATE  `article` SET `read_count` = `read_count`+1 where `id` = {$id}
SQL;
     return $this->exec($sql);
    }
    public function selecttime($id)
    {
        $sql = <<<SQL
        SELECT `goodtime` FROM `article` WHERE `id` = {$id}
SQL;
       return $this->getOne($sql)->goodtime;
    }
    public function addgood($id,$time)
    {
        $sql = <<<SQL
        UPDATE `article` set `good` = `good` + 1,`goodtime` = {$time}  where id = {$id}
SQL;
     return $this->exec($sql);
    }
}