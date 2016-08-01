<?php

namespace App\Controller\Backend;

class FrameController extends \Core\Controller
{    
	public function __construct()
	{
		parent::__construct();
		
			$this->loginAccess();
		
	}

	public function frame()
	{
		 return $this->loadHtml('Frame/frame');
	}
	public function menu()
	{
         return $this->loadHtml('Frame/menu');
	}
	public function top()
	{
         return $this->loadHtml('Frame/top');
	}
	public function content()
	{
		 return $this->loadHtml('Frame/content');
	}
}