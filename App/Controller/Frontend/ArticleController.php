<?php

namespace App\Controller\Frontend;

use App\Model\ArticleModel;
use App\Model\CategoryModel;
use Vendor\pager;
use App\Model\CommentModel;

class ArticleController extends \Core\Controller
{
	public function select()
	{
		$where = ' 2 > 1 ';

		$search = empty($_REQUEST['search'])? "" : $_REQUEST['search'];
		
		if ($search) {
			$where .= " AND `article`.`title` like '%{$search}%' ";
		}
		$category_id= empty($_REQUEST['category_id']) ? 0: $_REQUEST['category_id'];
		if (!empty($category_id)) {
			$where .= " AND `article`.`category_id` = '{$category_id}' ";
		}
		/*echo "<pre>";
		print_r($where);
        echo "</pre>";*/
		$count = ArticleModel::create()->getCount($where);

		$pageSize = 2;
		$page = empty($_GET['page']) ? 1 :$_GET['page'];
		$pageStart = ($page-1)*$pageSize ;
		$article = ArticleModel::create()->selectJoin($where, $pageStart, $pageSize);
		$category = CategoryModel::create()->selectAll();
		$category = CategoryModel::create()->limitlessLevelCategory($category);
        $pager = new Pager($count, $pageSize, $page, 'Index.php', array(
             'p' => 'Frontend',
             'c' => 'Article',
             'a' => 'select',
             'search' => $search,
             'category_id' => $category_id,
        	));
        $pageHtml = $pager->showPage();

	    $this->s->assign(array(
           'article' => $article,
           'category' => $category,
           'pageHtml' => $pageHtml,
           'search' => $search,
           'category_id' =>$category_id,

	   	));
	   $this->s->display('Frontend/Article/select.html');
	}
	public function content()
	{
		$id = $_GET['id'];
		ArticleModel::create()->addRead($id);		
		$articleOne = ArticleModel::create()->selectJoin("`article`.`id` = {$id}");

        $comments = CommentModel::create()->commentJoin("`comment`.`article_id`='{$id}'");   
        $comments = CommentModel::create()->limitlessLevelComment($comments);
		
		$this->s->assign(array(
           'articleOne'=>$articleOne,
           'comments' => $comments,
			));
		$this->s->display('Frontend/Article/content.html');
	}
	public function onclickGood()
	{
		$id = $_GET['id'];
		$url = "Index.php?p=Frontend&c=Article&a=content&id={$id}";
		$time = time();
		$goodtime = ArticleModel::create()->selecttime($id);
		$nowTime = date('h小时i分s秒',36000-($time-$goodtime));
		/*echo $time."</br>";
		echo $goodtime."</br>";
		echo $nowTime ;die;*/

		if (!empty($_SESSION['success']) && $_SESSION['success']) {
           if ($time-36000>$goodtime) {
           	  if (ArticleModel::create()->addgood($id,$time)) {
           	  	 return  $this->getLocation($url, '恭喜，点赞成功');
           	  	}else {
           	  	 return  $this->getLocation($url, '抱歉，点赞失败');
           	  	}
           	
           } else {
           	 return $this->getLocation($url, '抱歉，必须'.$nowTime.'之后才能再次点赞');
           }
		} else {
			return $this->getLocation('Index.php?p=Backend&c=Login&a=login', '抱歉，请登录后在进行点赞');
		}
	}

}