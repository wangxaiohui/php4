<?php

namespace App\Controller\Backend;

use App\Model\LoginModel;
use Vendor\Captcha;


class LoginController extends \Core\Controller
{
	public function login()
	{
		if (empty($_POST)) {
			$this->loadHtml('Login/login');
		} else {
			if (strtolower($_POST['edtCaptcha'])!== $_SESSION['captchaValue'] ) {
                 $this->getLocation('Index.php?p=Backend&c=Login&a=login', '验证码错误，请从新输入');
                 die();
			}
			//$name = str_replace('<sctipt>', '', str_replace('</script>', '', $_POST['username']));
			$name = addslashes($_POST['username']);
			$password = $_POST['password']; 

            $sussec = LoginModel::create()->selectOne("name = '{$name}' AND password = '{$password}'");

            if ($sussec == FALSE) {
            	$_SESSION['success'] = false;
            	$this->getLocation('Index.php?p=Backend&c=Login&a=login', '登录失败');
            } else {
                $_SESSION['success'] = true;
                $_SESSION['user'] = array(
                     'id' =>$sussec->id,
                     'name'=>$sussec->name,
                     'nickname'=>$sussec->nickname,
                     'loginTime'=>time(),
                     'ip'=>$_SERVER['REMOTE_ADDR']
                	);
                $this->getLocation('Index.php?p=Backend&c=Frame&a=frame', '登录成功');
            }

		}
		
       
	}
	public function logout()
	{
		unset($_SESSION);
		session_destroy();
		$this->getLocation('Index.php?p=Backend&c=Login&a=login', '成功退出');

	}
	public function captcha()
	{
		//什么时候用return
		$obj = new Captcha();
	    $obj->generateCode();
		$_SESSION['captchaValue'] = $obj->getCode();

	}
}