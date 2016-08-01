<?php

namespace App\Model;

class UserModel extends \Core\Model
{
	public static $table = "user";
 	//查询所有，结果二位数组
 	
 	//删除
 	/*public function deleteUserByid($id)
 	{
 		 
 		$sql="delete from user where id='{$id}'";
 		return $this->obj->exec($sql);

 	}
 	//天添加信息
 	public function addUser($name=null,$nickname=null,$email=null,$mobile=null,$mobile_number=null)
 	{
 		
 
 	     $time = time();
 		 $sql="insert into user values(null,'{$name}','{$nickname}','{$email}','{$mobile_number      }',$time)";
 		
 		 return $this->obj->exec($sql);
 	}
 	//查询当前行信息，结果一位数组
 	public function  selectRowUser($id)
 	{
 		
 		 $sql="select * from user where id=$id";

 		 return $this->obj->GetOneRow($sql);
 	}
 	//删除
 	public function updateUser($id,$name=null,$nickname=null,$email=null,$mobile=null,$mobile_number=null)
 	{
        
        $time=time();
        $sql="update user set name='{$name}',nickname='{$nickname}',email='{$email}',mobile_number='{$mobile_number}',register_time=$time where id={$id}";
        return $this->obj->exec($sql);
 	}
    //查询总数，结果一位数组
 	public function countUser()
 	{
 		
 		$sql="select count(*) from user";
 		return $this->obj->GetOneData($sql);


 	}
 	//查询结果，分页显示的当前页
 	public function fenye($pagerow,$pagesize)
 	{
      $sql="select * from user limit $pagerow,$pagesize";
      return $this->obj->getRows($sql);
 	} */
 }
