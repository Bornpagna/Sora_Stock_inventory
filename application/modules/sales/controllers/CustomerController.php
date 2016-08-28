<?php
class sales_CustomerController extends Zend_Controller_Action
{
	
    public function init()
    {
        /* Initialize action controller here */
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    	$tr = Application_Form_FrmLanguages::getCurrentlanguage();
    }
	public function indexAction()
	{
		$formFilter = new Application_Form_Frmsearch();
		$frmfillter = $formFilter->customerSearch();
		Application_Model_Decorator::removeAllDecorator($frmfillter);
		$this->view->formFilter = $frmfillter;
		$list = new Application_Form_Frmlist();
		Application_Model_Decorator::removeAllDecorator($formFilter);
		$db = new Application_Model_DbTable_DbGlobal();
		$vendorSql = "SELECT c.customer_id, c.cust_name,c.contact_name, c.phone, c.email, c.website,
		tp.price_type_name ,c.is_active
		FROM tb_customer AS c,tb_price_type as tp
		WHERE tp.type_id=c.type_price AND c.cust_name!='' ";
		if($this->getRequest()->isPost()){
			$post = $this->getRequest()->getPost();			
			if($post['name'] !=''){
				$vendorSql .= " AND c.cust_name LIKE '%".trim($post['name'])."%'";
			}
			if($post['phone'] !=''){
				$vendorSql .= " AND ( c.contact_name LIKE '%".trim($post['phone'])."%'";
				$vendorSql .= " OR c.phone LIKE '%".trim($post['phone'])."%')";
			}
			if($post['email'] !=''){
				$vendorSql .= " AND c.email LIKE '%".trim($post['email'])."%'";
				$vendorSql .= " OR c.website LIKE '%".trim($post['email'])."%'";
			}
			if($post['type_price'] !='' AND $post['type_price'] !=0){
				$vendorSql .= " AND tp.type_id = ".trim($post['type_price']);
			}
		}
		$vendorSql.=" ORDER BY c.cust_name,c.is_active ";
		$rows=$db->getGlobalDb($vendorSql);
		$glClass = new Application_Model_GlobalClass();
		$rows = $glClass->getImgActive($rows, BASE_URL, true);
		
		$columns=array("CUSTOMER_NAME_CAP","CON_NAME_CAP","CONTACT_NUM_CAP","EMAIL_CAP","WEBSITE_CAP","TYPE_PRICE","STATUS_CAP");
		$link=array(
				'module'=>'sales','controller'=>'customer','action'=>'update-customer',
		);
		$urlEdit = BASE_URL . "/sales/customer/update-customer";
		$this->view->list=$list->getCheckList(1, $columns, $rows, array('c.v_name'=>$link,'c.cust_name'=>$link),$urlEdit);
		
	}
	public function addAction()
	{	
		
		if($this->getRequest()->isPost())
		{
			$post = $this->getRequest()->getPost();
			//print_r($post); exit();
			if(@$post['Save']!="")
			{
				$customer= new sales_Model_DbTable_DbCustomer();
				$customer->addCustomer($post);
				$this->_redirect('/sales/customer/index');
			}
			elseif(@$post['SaveNew']!="")
			{
				$customer= new sales_Model_DbTable_DbCustomer();
				$customer->addCustomer($post);
				Application_Form_FrmMessage::message("Customer has been saved !");
			}
			elseif(@$post['btn_new']=='New')
			{
				$this->_redirect('/sales/customer/add');
				
			}
		}
		$formStock = new sales_Form_FrmVendor(null);
		$formStockAdd = $formStock->AddCustomerForm(null);
		Application_Model_Decorator::removeAllDecorator($formStockAdd);
		$this->view->form = $formStockAdd;
		//.end controller
		$formControl = new Application_Form_FrmAction(null);
		$formViewControl = $formControl->AllAction(null);
		Application_Model_Decorator::removeAllDecorator($formViewControl);
		$this->view->control = $formViewControl;
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