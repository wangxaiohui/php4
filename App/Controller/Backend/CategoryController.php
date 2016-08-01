<?php

namespace App\Controller\Backend;

use App\Model\CategoryModel;

class CategoryController extends \Core\Controller
{
  public function __construct()
    {
      parent::__construct();
      $this->loginAccess();

    }
	public function select()
	{
        $category = CategoryModel::create()->selectAll();
        $category = CategoryModel::create()->limitlessLevelCategory($category);
        $arr = array(
                'category'=>$category,
        	);

        $this->loadHtml('Category/Select',$arr);
	}

	public function add()
	{
          if (empty($_POST)) {
          	$category = CategoryModel::create()->selectAll();
          	$categort = CategoryMOdel::create()->limitlessLevelCategory($category);
          	$arr = array(
                 'category'=>$category,
          		);
              $this->loadHtml('Category/Add', $arr);
          } else {
              $arr = array(
              	'name' =>$_POST['Name'],
                'nickname'=>$_POST['Alias'],
                'sort'=>$_POST['Order'],
                'parent_id'=>$_POST['ParentID']
              	);
              if (CategoryModel::create()->add($arr)) {
              	   $this->getLocation('Index.php?p=Backend&c=Category&a=select', '分类成功'); 
                } else {
                   $this->getLocation('Index.php?p=Backend&c=Category&a=add', '分类失败，请从新分类'); 
                }
          }
	}

	public function delete()
	{
         $id = $_GET['id'];
         //父类ID出现的次数大于0时
         if (CategoryModel::create()->getCount("parent_id={$id}")>0) {
               $this->getLocation('Index.php?p=Backend&c=Category&a=select', '属于父类，禁止删除');
         } else {
               if (CategoryModel::create()->delete($id) == 1) {
               	 $this->getLocation('Index.php?p=Backend&c=Category&a=select', '删除成功');

               } else {
               	 $this->getLocation('Index.php?p=Backend&c=Category&a=select', '删除失败');
               }
           }             
       
	}

	public function update()
	{
        $id = $_GET['id'];
        
        if (empty($_POST)) {
        	$categoryOne = CategoryModel::create()->selectOne("id = {$id}");
        	$category = CategoryModel::create()->selectAll();
        	$category = CategoryModel::create()->limitlessLevelCategory($category);
            $arr = array(
                   
                    'categoryOne'=>$categoryOne, 
                    'category' => $category,
            	);
           
            $this->loadHtml('Category/Update', $arr);
        } else {
            $arr = array(
                'name' =>$_POST['Name'],
                'nickname'=>$_POST['Alias'],
                'sort'=>$_POST['Order'],
                'parent_id'=>$_POST['ParentID']
            	);
            $selectOne = CategoryModel::create()->selectOne("id = {$id}");
            if (($selectOne->name == $_POST['Name']) && 
                ($selectOne->nickname == $_POST['Alias']) &&
                ($selectOne->sort == $_POST['Order']) && 
                ($selectOne->parent_id ==$_POST['ParentID'])
              ) {
                    $this->getLocation('Index.php?p=Backend&c=Category&a=select','未做任何修改，是否从新修改？');
                  return;

            }
            if (CategoryModel::create()->update($arr,$id )== 1) {
                 return  $this->getLocation('Index.php?p=Backend&c=Category&a=select','修改成功');
            } else {
            	   return $this->getLocation('Index.php?p=Backend&c=Category&a=select','修改失败，从新修改');
            }

        }
	}
}
