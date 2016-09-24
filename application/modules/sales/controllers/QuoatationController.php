<?php
class Sales_QuoatationController extends Zend_Controller_Action
{	
	
    public function init()
    {
        /* Initialize action controller here */
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    	$tr = Application_Form_FrmLanguages::getCurrentlanguage();
    }
    protected function GetuserInfoAction(){
    	$user_info = new Application_Model_DbTable_DbGetUserInfo();
    	$result = $user_info->getUserInfo();
    	return $result;
    }
    
   	public function indexAction()
	{
		if($this->getRequest()->isPost()){
			$search = $this->getRequest()->getPost();
		}
		else{
			$search = array();
		}
		$db = new Sales_Model_DbTable_Dbquoatation();
		$rows = $db->getAllQuoatation($search);
		$list = new Application_Form_Frmlist();
		$columns=array("QUOAT_NO","ORDER_DATE","DATE_IN", "BRNACH",
				"CUSTOMER_NAME","CURRNECY_TYPE","TOTAL_AMOUNT","PAID","BALANCE","BY_USER_CAP");
		$link=array(
				'module'=>'purchase','controller'=>'index','action'=>'detail-purchase-order',
		);
		// url link to update purchase order
		
		$urlEdit = BASE_URL . "/purchase/index/edit";
		$this->view->list=$list->getCheckList(0, $columns, $rows, array('order'=>$link));
		
// 		$formFilter = new Application_Form_Frmsearch();
// 		$this->view->formFilter = $formFilter;
// 		Application_Model_Decorator::removeAllDecorator($formFilter);
		
	}
	function addAction(){
		$db = new Application_Model_DbTable_DbGlobal();
		if($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			try {
				$dbq = new Sales_Model_DbTable_Dbquoatation();
				$dbq->addQuoatationOrder($data);
				Application_Form_FrmMessage::message("INSERT_SUCESS");
				if(!empty($data['btnsavenew'])){
					Application_Form_FrmMessage::redirectUrl("/sales/quoatation");
				}
			}catch (Exception $e){
				Application_Form_FrmMessage::message('INSERT_FAIL');
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		///link left not yet get from DbpurchaseOrder
		$frm_purchase = new Sales_Form_FrmQuoatation(null);
		$form_sale = $frm_purchase->SaleOrder(null);
		Application_Model_Decorator::removeAllDecorator($form_sale);
		$this->view->form_sale = $form_sale;
		 
		// item option in select
		$items = new Application_Model_GlobalClass();
		$this->view->items = $items->getProductOption();;
		 
	}		
}