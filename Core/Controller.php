<?php

namespace Core;

use Vendor\Smarty;

class Controller
{
	protected  $s;
	public function __construct()
	{
		$this->createSmartyObject();
	}
	protected function createSmartyObject()
	{
		$s = new Smarty();
		$s->setCompileDir(sys_get_temp_dir().'/templates_c');
		$s->setTemplateDir(VIEW_PATH);
		$s->setConfigDir('./app/config');
		$s->left_delimiter = '<{';
		$s->right_delimiter = '}>';
		$this->s = $s;

	}

	protected function getLocation($dress = null, $massage = null, $time = 3, $type = 1){

      if ($type == 2) {
      	header("refresh:{$time}; url = {$dress}");
		echo $massage;
      } else {  
        require VIEW_PATH.'/tips.html';   
	  }
    }
	protected function loadHtml($htmlname, $arr = array() )
	{
		foreach ($arr as $key => $value) {
			$$key = $value;
		}
		require VIEW_PATH."/".PLAT."/{$htmlname}.html";
	}

	

	protected function loginAccess()
	{
		if (!empty($_SESSION['success']) && $_SESSION['success']) {
             
		} else {
            $this->getLocation('Index.php?p=Backend&c=Login&a=login','禁止仿问');
            die;
		}
	}
}

