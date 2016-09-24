<?php
class Sales_CustomerController extends Zend_Controller_Action
{
	

    public function init()
    {
        /* Initialize action controller here */
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    	$tr = Application_Form_FrmLanguages::getCurrentlanguage();
    }
	public function indexAction()
	{
		if($this->getRequest()->isPost()){
			$search = $this->getRequest()->getPost();
			$search['start_date']=date("Y-m-d",strtotime($search['start_date']));
			$search['end_date']=date("Y-m-d",strtotime($search['end_date']));
		}else{
			$search =array(
					'text_search'=>'',
					'branch_id'=>0,
					'customer_id'=>0,
					'level'=>0,
					'start_date'=>date("Y-m-d"),
					'end_date'=>date("Y-m-d"),
			);
		}
		$db = new Sales_Model_DbTable_DbCustomer();
		$rows = $db->getAllCustomer($search);
		$list = new Application_Form_Frmlist();
		$columns=array("BRANCH_NAME","CUSTOMER_NAME","COMPANY_NUMBER","CUSTOMER_LEVEL","CONTACT_NAME","CONTACT_NUMBER","ADDRESS","STATUS","BY_USER");
		$link=array(
				'module'=>'sales','controller'=>'customer','action'=>'edit',
		);
		$this->view->list=$list->getCheckList(0, $columns, $rows, array('branch_name'=>$link,'cust_name'=>$link,'phone'=>$link,'level'=>$link));
		
        $formFilter = new Sales_Form_FrmSearch();
		$this->view->formFilter = $formFilter;
		Application_Model_Decorator::removeAllDecorator($formFilter);
		
	}
	public function addAction()
	{
		if($this->getRequest()->isPost())
		{
			$post = $this->getRequest()->getPost();
			try{
				$db = new Sales_Model_DbTable_DbCustomer();
				$db->addCustomer($post);
				if(!empty($post['saveclose']))
				{
// 					Application_Form_FrmMessage::Sucessfull('INSERT_SUCCESS', sel . '/customer/index');
				}else{
					Application_Form_FrmMessage::message("INSERT_SUCCESS");
				}
			}catch(Exception $e){
				Application_Form_FrmMessage::message('INSERT_FAIL');
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		/////////////////for veiw form
		$formcustomer = new Sales_Form_FrmCustomer(null);
		$formStockAdd = $formcustomer->Formcustomer(null);
		Application_Model_Decorator::removeAllDecorator($formcustomer);
		$this->view->form = $formcustomer;
	
	}	
	public function updateCustomerAction() {
		
		if($this->getRequest()->isPost())
		{
			try{
				$post = $this->getRequest()->getPost();
				$customer= new sales_Model_DbTable_DbCustomer();
				$customer->updateCustomer($post);
				$this->_redirect('/sales/customer/index');
			}catch (Exception $e){
				Application_Form_FrmMessage::message("Update customer failed !");
			}
		}
		$id = ($this->getRequest()->getParam('id'))? $this->getRequest()->getParam('id'): '0';
			$sql = "SELECT c.customer_id,c.type_price,c.cust_name, c.add_remark, c.contact_name,c.add_name, c.phone, 
					c.fax,c.email, c.website,c.customer_remark,c.is_active
					FROM tb_customer AS c,tb_price_type as tp
					WHERE tp.type_id=c.type_price
					AND c.customer_id = ".$id." LIMIT 1";
		$db = new Application_Model_DbTable_DbGlobal();
		$row = $db->getGlobalDbRow($sql);
		// lost item info
		$formStock=new sales_Form_FrmVendor($row);
		$formStockEdit = $formStock->AddCustomerForm($row);
		Application_Model_Decorator::removeAllDecorator($formStockEdit);// omit default zend html tag
		$this->view->customer_frm = $formStockEdit;
	
		//control action
		$formControl = new Application_Form_FrmAction(null);
		$formViewControl = $formControl->AllAction(null);
		Application_Model_Decorator::removeAllDecorator($formViewControl);
		$this->view->control = $formViewControl;
	}	
	public function addCustomerAction(){
		if($this->getRequest()->isPost()){
			try {
			$post=$this->getRequest()->getPost();
			$add_customer = new sales_Model_DbTable_DbCustomer();
			$customer_id = $add_customer->addNewCustomer($post);
			$result = array('cus_id'=>$customer_id);
			echo Zend_Json::encode($result);
			exit();
			}catch (Exception $e){
				$result = array('err'=>$e->getMessage());
				echo Zend_Json::encode($result);
				exit();
			}
		}
	}
	public function deleteCustomerAction() {
		$id = ($this->getRequest()->getParam('id'));
		$sql = "DELETE FROM tb_customer WHERE customer_id IN ($id)";
		$deleteObj = new Application_Model_DbTable_DbGlobal();
		$deleteObj->deleteRecords($sql);
		$this->_redirect('/sales/customer/index');
	}
}