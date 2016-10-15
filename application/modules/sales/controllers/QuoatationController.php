<?php
class Sales_quoatationController extends Zend_Controller_Action
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
			$search['start_date']=date("Y-m-d",strtotime($search['start_date']));
			$search['end_date']=date("Y-m-d",strtotime($search['end_date']));
		}
		else{
			$search =array(
					'text_search'=>'',
					'start_date'=>date("Y-m-d"),
					'end_date'=>date("Y-m-d"),
					'branch_id'=>-1,
					'customer_id'=>-1,
					);
		}
		$db = new Sales_Model_DbTable_Dbquoatation();
		$rows = $db->getAllQuoatation($search);
		$list = new Application_Form_Frmlist();
		$columns=array("BRANCH_NAME","CUSTOMER_NAME","SALE_AGENT","QUOTATION_NO", "ORDER_DATE",
				"CURRNECY_TYPE","TOTAL","DISCOUNT","TOTAL_AMOUNT","APPROVED_STATUS","PENDING_STATUS","BY_USER");
		$link=array(
				'module'=>'sales','controller'=>'quoatation','action'=>'edit',
		);
		$this->view->list=$list->getCheckList(0, $columns, $rows, array('branch_name'=>$link,'customer_name'=>$link,'staff_name'=>$link,'quoat_number'=>$link));
		
		$formFilter = new Sales_Form_FrmSearch();
		$this->view->formFilter = $formFilter;
	    Application_Model_Decorator::removeAllDecorator($formFilter);
		
	}
	function addAction(){
		$db = new Application_Model_DbTable_DbGlobal();
		if($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			try {
				$dbq = new Sales_Model_DbTable_Dbquoatation();
				if(!empty($data['identity'])){
					$dbq->addQuoatationOrder($data);
				}
				Application_Form_FrmMessage::message("INSERT_SUCESS");
				if(empty($data['btnsavenew'])){
					Application_Form_FrmMessage::redirectUrl("/sales/quoatation");
				}
			}catch (Exception $e){
				Application_Form_FrmMessage::message('INSERT_FAIL');
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		///link left not yet get from DbpurchaseOrder
		$frm_purchase = new Sales_Form_FrmQuoatation(null);
		$form_sale = $frm_purchase->SaleOrder(null);
		Application_Model_Decorator::removeAllDecorator($form_sale);
		$this->view->form_sale = $form_sale;
		 
		$items = new Application_Model_GlobalClass();
		$this->view->items = $items->getProductOption();
		$this->view->term_opt = $db->getAllTermCondition(1);
	}
	function editAction(){
		$dbq = new Sales_Model_DbTable_Dbquoatation();
		$db = new Application_Model_DbTable_DbGlobal();
		$id = ($this->getRequest()->getParam('id'))? $this->getRequest()->getParam('id'): '0';
		if($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			try {
				if(!empty($data['identity'])){
					$dbq->updateQoutation($data);
				}
				Application_Form_FrmMessage::Sucessfull("UPDATE_SUCESS","/sales/quoatation");
			}catch (Exception $e){
				Application_Form_FrmMessage::message('UPDATE_FAIL');
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$row = $dbq->getQuotationItemById($id);
		if(empty($row)){
			Application_Form_FrmMessage::Sucessfull("NO_DATA","/sales/quoatation");
		}		
		$this->view->rs = $dbq->getQuotationItemDetailid($id);
		$this->view->rsterm = $dbq->getTermconditionByid($id);
		
		$frm_purchase = new Sales_Form_FrmQuoatation();
		$form_sale = $frm_purchase->SaleOrder($row);
		Application_Model_Decorator::removeAllDecorator($form_sale);
		$this->view->form_sale = $form_sale;
		$this->view->rsq = $row;
		$items = new Application_Model_GlobalClass();
		$this->view->items = $items->getProductOption();
		$this->view->term_opt = $db->getAllTermCondition(1);
	}	
	function getquotenoAction(){
		if($this->getRequest()->isPost()){
			$post=$this->getRequest()->getPost();
			$db = new Application_Model_DbTable_DbGlobal();
			$qo = $db->getQuoationNumber($post['branch_id']);
			echo Zend_Json::encode($qo);
			exit();
		}
	}
}