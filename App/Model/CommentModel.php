<?php

namespace App\Model;

class CommentModel extends \Core\Model
{
	public static $table = 'comment';

	public function commentJoin($where='2 > 1', $start = null, $pageSize = null)
	{
		$sql = "SELECT `comment`.*, c.`content` as parent_content, `user`.`name` AS user_name,`article`.`title`    FROM `comment` 
		        LEFT JOIN `comment` as c on `comment`.`parent_id` = c.`id` 
		        LEFT JOIN `user` on `comment`.`user_id` = `user`.`id`
		        LEFT JOIN `article` on `comment`.`article_id` = `article`.`id`
		        WHERE {$where}";
		 if (!empty($pageSize)) {
               $sql .= " LIMIt $start, $pageSize";
		 }
	   /* echo "<pre>";
		print_r($sql);
        echo "</pre>";*/
		return $this->getAll($sql);
	}

     public function limitlessLevelComment($comments, $parentId = 0)
    {
        $treeComments = array();
        foreach ($comments as $comment) {
            if ($comment->parent_id == $parentId) {
                // 寻找评论的子评论
                $comment->childrens = $this->limitlessLevelComment($comments, $comment->id);
                $treeComments[] = $comment;
            }
        }
        return $treeComments;
    }

	/*public function limitlessLevelComment($comment)
	{
        $parent_Comment = array();
        $children_Comment = array();
	}*/

}