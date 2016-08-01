<?php

namespace App\Controller\Backend;

use App\Model\CommentModel;
use Vendor\Pager;

class CommentController extends \Core\Controller
{
	public function __construct()
		{
			parent::__construct();
			$this->loginAccess();

		}
	public function select()
	{   

		$where = "2 > 1";
		$search = empty($_REQUEST['search']) ? "" :$_REQUEST['search'];
		if ($search) {
            $where .= " AND `comment`.`content` LIKE '%{$search}%' "; 
		}
        $count = CommentModel::create()->getCount($where);
        $pageSize = 3;
        $page = empty($_GET['page']) ? 1 : $_GET['page']; 
		$pager = new Pager($count, $pageSize, $page,'Index.php', array(
         	'p' => 'Backend',
         	'c' => 'Comment',
         	'a' => 'select',
         	'search' => $search,
			));
		$start = ($page-1)*$pageSize;
		$pagerHtml = $pager->showPage();
		$comment = CommentModel::create()->commentJoin($where, $start, $pageSize);
		$arr = array(
			'comment' => $comment,
			'search' => $search,
			'pagerHtml' => $pagerHtml,
			);
		/*echo "<pre>";
		print_r($comment);
        echo "</pre>";*/
        $this->loadHtml('Comment/select', $arr);
	}

	

	public function update(){}

	public function delete()
	{
		if (!empty($_POST['del'])) {
			$id = implode(',', $_POST['del']);
		} else{
			$id = $_GET['id'];
		}
				
		if (CommentModel::create()->delete($id)>=1) {
            $this->getLocation('Index.php?p=Backend&c=Comment&a=select', "删除成功");
		} else {
			$this->getLocation('Index.php?p=Backend&c=Comment&a=select', "删除失败");
		}
	}
}