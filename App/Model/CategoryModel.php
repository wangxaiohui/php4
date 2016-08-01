<?php

namespace App\Model;

class CategoryModel extends \Core\Model
{
    public static $table = 'category';

    public  function limitlessLevelCategory($category, $parent_id = 0, $level = 0)
	{
		//为什么静态的？不会被初始化
		//print_r($category);
        static  $arr = array();
        foreach ($category as $c) {
            if ($c->parent_id == $parent_id) {
            						
                  $c->level = $level;
                  $arr[] = $c;
                  $this->limitlessLevelCategory($category, $c->id, $level + 1);
            }
        }
         
        return $arr;
	}
	/* public function limitlessLevelCategory($categories, $parentId = 0, $level = 0)
    {
        static $limitlessCategories = array();
        foreach($categories as $category) {
            // 只返回顶级分类 parent_id=0，最顶级的级别是0，顶级的下一级1
            if ($category->parent_id == $parentId) {
                $category->level = $level;

                $limitlessCategories[] = $category;

                $this->limitlessLevelCategory($categories, $category->id, $level + 1);
            }
        }
        return $limitlessCategories;
    }*/
}
