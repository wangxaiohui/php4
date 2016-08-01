<?php

namespace App\Controller\Frontend;

use App\Model\ArticleModel;
use App\Model\CommentModel;
use core\Controller;


class CommentController extends Controller
{
	public function __construct()
		{
			parent::__construct();
			$this->loginAccess();

		}
	public function add()
	{
		

		$id = $_GET['id'];
		if (empty($_POST['txaArticle'])) {
             return $this->getLocation("Index.php?p=Frontend&c=Article&a=content&id={$id}",'评论内容不能为空');
		} else {
			$arr = array(
				'content' => $_POST['txaArticle'],
				'time' => time(),
				'user_id' => $_SESSION['user']['id'],
                'article_id' => $id,
                'parent_id' => $_POST['inpRevID'],
                );
              if (CommentModel::create()->add($arr)) {
         
                   $this->getLocation("Index.php?p=Frontend&c=Article&a=content&id={$id}", '评论成功');
             } else {
          
               $this->getLocation("Index.php?p=Frontend&c=Article&a=content&id={$id}", "评论失败，请稍后再试。");
              }
    			
	 }
   }

}