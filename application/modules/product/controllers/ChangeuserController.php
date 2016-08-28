<?php
class Product_changeuserController extends Zend_Controller_Action
{
public function init()
    {
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    }
    public function indexAction()
    {
    	$id=$this->getRequest()->getParam("id");
    	$session_user=new Zend_Session_Namespace('auth');
    	$session_user->location_id=$id;
    	$this->_redirect("/product/index/index");
	}
	
}

