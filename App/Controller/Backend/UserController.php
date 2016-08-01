<?php

namespace App\Controller\Backend;

use App\Model\UserModel; 

class UserController extends \Core\Controller
{
   public function __construct()
        {
            parent::__construct();
            $this->loginAccess();

        }
    public function select()
    {
 /*$page = empty($_GET['page']) ? 1 : $_GET['page'];
         $pagesize = 5;
         $count = UserModel::create()->countUser();
         $pages = ceil($count/$pagesize);
         $pagerow = ($page-1)*10;
         $pageObj = FenyeModel::getInstance($pages, $page);

         $both = $pageObj->getMethod();
         $user = UserModel::create()->fenye($pagerow, $pagesize);
	 
	 	 $star = $both['star'];
	 	 $end = $both['end'];

	 	if (!empty($_POST['page'])) {

	 	} else { }*/

        $user = UserModel::create()->selectAll();
        $count = UserModel::create()->getCount();
        $arr = array(
             'user' => $user,
             'count' => $count,
            );
        $this->loadHtml('User/userList',$arr);
	  

    }
    public function add()
    {
         if (isset($_POST['ac']) && $_POST['ac'] == $_SESSION['value']) {
            echo $_POST['name'];
            $arr = array(
                'name' => $_POST['name'],
             	'nickname' => $_POST['nickname'],
             	'email' => $_POST['email'],
             	'mobile_number' => $_POST['mobile_number'],
                'register_time' => time(),
                );

        	       UserModel::create()->add($arr);
        	       $this->getLocation('index.php?p=backend&c=User&a=select', '添加成功');
        	        
        	    
        	    } else {
        	   
        			$_SESSION['value'] = uniqid();

                    $this->loadHtml('User/userAdd');
        			
        	    }
    } 
 
    public function update()
    {
	$id = $_GET['id'];
    
        if (!empty($_POST['ac']) && $_POST['ac'] == $_SESSION['valueUpdate']) {
             $arr = array(
                 'name' => $_POST['name'],
                 'nickname' => $_POST['nickname'],
                 'email' => $_POST['email'],
                 'mobile_number' => $_POST['mobile_number'],

              );
        UserModel::create()->update($arr, $id);
        $this->getLocation('index.php?p=backend&c=User&a=select', "修改成功");
        
        } else {
      
         	 $arr1 = UserModel::create()->selectOne("id = {$id}");
             $_SESSION['valueUpdate'] = uniqid();
             $arr = array(
                  'arr1' => $arr1,
                );
             $this->loadHtml('User/updateUser', $arr);
            
        }
    
    }
 
    public  function delete()
   {
    $id = $_GET['id'];

	    if (UserModel::create()->delete($id)) {
	   	    $this->getLocation('index.php?p=backend&c=User&a=select', "删除成功");
	   	}else{
	   	    $this->getLocation('index.php?p=backend&c=User&a=select', "删除失败");
	   	} 
	 
   }
}
