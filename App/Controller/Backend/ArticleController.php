<?php 

namespace App\Controller\Backend;

use App\Model\CategoryModel;
use App\Model\ArticleModel;
use Vendor\pager;

class ArticleController extends \Core\Controller
{
	public function __construct()
		{
			parent::__construct();
			$this->loginAccess();

		}
	public function select(){

		$where = "2 >1";

		$cate = !empty($_REQUEST['category']) ? $_REQUEST['category'] : 0;
		if ($cate) {
			$where .= " AND `article`.`category_id` = {$cate}";
		}
		
		/*$top = isset($_REQUEST['istop']) ? $_REQUEST['istop']: 0;
		if ($top) {
             $where .= " AND `article`.`top` = 1";
		}*/
		 $top = !empty($_REQUEST['istop']) ? $_REQUEST['istop'] : 0;
		 // 0表示查询出置顶+不置顶的所有数据
        if ($top) {
            $where .= " AND `article`.`top`=1";
        }
       
		$search = !empty($_REQUEST['search']) ? $_REQUEST['search'] : "";
        if ($search) {
        	$where .= " AND `article`.`title` like '%{$search}%'";
        }
		$count = ArticleModel::create()->getCount($where);
		//每页的行数
		$pageCount = 1; 
		$page = !empty($_GET['page'])? $_GET['page'] : 1;
        $pager = new Pager($count, $pageCount, $page, 'Index.php', array(
           'p'=>'Backend',
           'c'=>'Article',
           'a'=>'select',
           'category'=>$cate,
           'istop'=>$top,
           'search'=>$search,
        	));
        $pageHtml = $pager->showPage();
        //从第几行开始取
        $pageStart= ($page-1)*$pageCount;
      
		$category = CategoryModel::create()->selectAll();
		$category = CategoryModel::create()->limitLessLevelCategory($category);
		$selectJoin = ArticleModel::create()->selectJoin($where,$pageStart,$pageCount);
		

		$arr = array(
             'category'=>$category,
             'selectJoin'=>$selectJoin,
             'pageHtml' => $pageHtml, 
             'cate'=>$cate,
             'top'=>$top,
             'search'=>$search,
			);
		$this->loadHtml('Article/select',$arr);
	}

	public function add()
	{
        if (empty($_POST)) {
           $category = CategoryModel::create()->selectAll();
           $category = CategoryModel::create()->limitlessLevelCategory($category);
           $arr = array(
               'category' => $category,
           	);
           $this->loadHtml('Article/add',$arr);
        } else {
             $arr = array(
                   'category_id'=>$_POST['CateID'], 
                   'user_id'=>$_SESSION['user']['id'],
                   'title'=>$_POST['Title'],
                   'content'=>$_POST['Content'],
                   'date'=>strtotime($_POST['PostTime']),
                   'status'=>$_POST['Status'],
                   'top'=>!empty($_POST['isTop'])? 1:2,
             	);
             if (ArticleModel::create()->add($arr) == 1) {
                 $this->getLocation('Index.php?p=Backend&c=Article&a=select', '添加成功');
             } else {
             	$this->getLocation('Index.php?p=Backend&c=Article&a=select', '添加成功');
             }
         
        }
	}

	public function delete(){}

	public function update()
	{   
		
		if (empty($_POST)) {
             $id = $_REQUEST['id'];
			$articleOne = ArticleModel::create()->selectOne("id = {$id}");
			/*echo "<pre>";
			var_dump($articleOne);die;*/
			$category = CategoryModel::create()->selectAll();
			$category = CategoryModel::create()->limitlessLevelCategory($category);
			$arr = array(
				'category'=>$category,
				'articleOne'=>$articleOne,
				);
		
		
			$this->loadHtml('Article/update',$arr);
		} else {
			
                $id = $_REQUEST['id'];
			
			
			/*var_dump($_POST);die;*/
			
			/*echo $id;die;*/

		 /*	js已经做好了各种判断 ，不用再去判断 ，但js 可以在前台被更改，
		 所以服务器端判断必须存在}*/
		
			if (empty($_POST['Content'])) {
				$this->getLocation('Index.php?p=Backend&c=Article&a=update','内容不能修改为空');
				
				return;
			}
			if (empty($_POST['Title'])) {
				$this->getLocation('Index.php?p=Backend&c=Article&a=update','标题不能修改为空');
				
				return;
			}
			if (empty($_POST['CateID'])) {
				$this->getLocation("Index.php?p=Backend&c=Article&a=update&id={$id}",'分类不能修改为空');
				
				return;
			}
			
            $arr = array(
                'category_id'=>$_POST['CateID'], 
                'title'=>$_POST['Title'],
                'content'=>$_POST['Content'],
                'date'=>strtotime($_POST['PostTime']),
                'status'=>$_POST['Status'],
                'top'=>!empty($_POST['isTop'])? 1:0,
             	);
            $selectOne = ArticleModel::create()->selectOne("id = {$id}");
        
       /*    echo "<pre>";
		print_r($arr);
        echo "</pre>";*/
    
          
          
            $PostTime = strtotime($_POST['PostTime']) ;
            
            $isTop = empty($_POST['isTop']) ? 0 :1 ;
            if ($selectOne->category_id == $_POST['CateID'] &&
            	$selectOne->title == $_POST['Title'] &&
            	$selectOne->content == $_POST['Content'] &&
            	$selectOne->date == $PostTime &&
            	$selectOne->status == $_POST['Status'] &&
            	$selectOne->top == $isTop) {
            	$this->getLocation('Index.php?p=Backend&c=Article&a=select','未修改任何选项，需要再次修改');
                return;
            }
           
             if (ArticleModel::create()->update($arr,$id)==1) {
             	
                $this->getLocation('Index.php?p=Backend&c=Article&a=select','修改成功');
                return;
             } else { 
             	$this->getLocation('Index.php?p=Backend&c=Article&a=select','修改失败');
             	return;
             }
		}
		
	}
}