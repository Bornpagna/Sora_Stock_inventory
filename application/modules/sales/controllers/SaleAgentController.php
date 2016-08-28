<?php

class sales_SaleAgentController extends Zend_Controller_Action
{

public function init()
    {
        /* Initialize action controller here */
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    }

    public function indexAction()
    {
    	$formFilter = new sales_Form_FrmStockFilter();
    	$this->view->formFilter = $formFilter;
    	Application_Model_Decorator::removeAllDecorator($formFilter);
        $list = new Application_Form_Frmlist();
    	$db = new Application_Model_DbTable_DbGlobal();
        $sales_agent_sql = "SELECT sg.agent_id, sg.name, sg.phone, sg.email, sg.address, sg.job_title, l.Name, sg.description
        FROM tb_sale_agent AS sg 
        INNER JOIN tb_sublocation As l ON l.LocationId = sg.stock_id WHERE 1 ";
    	if($this->getRequest()->isPost()){
			$search = $this->getRequest()->getPost();
			if($search["s_name"] !=''){
				 $sales_agent_sql.=" AND sg.name LIKE '%".$search['s_name']."%'";
				 $sales_agent_sql.=" OR sg.phone LIKE '%".$search['s_name']."%'";
			}
			if($search["stock_location"] !='' AND $search["stock_location"] !=0){
				$sales_agent_sql.=" AND sg.stock_id = ".$search['stock_location'];
			}
			
		}
		$sales_agent_sql.=" ORDER BY sg.agent_id DESC";

        $rows=$db->getGlobalDb($sales_agent_sql);
    	$columns=array("SALE-AGENT","CONTACT_NUM_CAP","EMAIL_CAP","ADDRESS_CAP","POSTION_CAP","LOCATION_NAME_CAP","DESC_CAP");
    	$link=array(
    		'module'=>'sales','controller'=>'sale-agent','action'=>'update-sale-agent',
    	);
    	$urlEdit = BASE_URL . "/sales/sale-agent/update-sale-agent";
    	$glClass = new Application_Model_GlobalClass();

    	$this->view->list=$list->getCheckList(1, $columns, $rows, array('name'=>$link), $urlEdit);
    	
	}

	public function addSaleAgentAction() {
		if($this->getRequest()->isPost()) {
			$post = $this->getRequest()->getPost();
			if(@$post['submit_add']){
				$add_agent = new sales_Model_DbTable_DbSalesAgent();
				$add_agent ->addSalesAgent($post);
				Application_Form_FrmMessage::message("Agent Has Been Inserted !");
			}
			elseif(@$post['submit_add_close']){
				$add_agent = new sales_Model_DbTable_DbSalesAgent();
				$add_agent ->addSalesAgent($post);
				$this->_redirect("sales/sale-agent/index");
			}
			else{
				$this->_redirect("sales/sale-agent/index");				
			}
		}
		$formAgent = new sales_Form_FrmStock(null);
		$formShowAgent = $formAgent->showSaleAgentForm(null);
		Application_Model_Decorator::removeAllDecorator($formShowAgent);
		$this->view->form_agent = $formShowAgent;
		
		
		$formpopup = new Application_Form_FrmPopup(null);
		//for add location
		$formAdd = $formpopup->popuLocation(null);
		Application_Model_Decorator::removeAllDecorator($formAdd);
		$this->view->form_addstock = $formAdd;
	}
	public function updateSaleAgentAction() {
		$session_stock=new Zend_Session_Namespace('stock');
		$id = ($this->getRequest()->getParam('id'))? $this->getRequest()->getParam('id'): '0';
    	$db = new Application_Model_DbTable_DbGlobal();
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		if($data["submit_update"]){
    			$update_agent = new sales_Model_DbTable_DbSalesAgent();
    			$update_agent ->updateSalesAgent($data);
    			$this->_redirect("sales/sale-agent/index");
    		}
    		else{
    			$this->_redirect("sales/sale-agent/index");    		}
    	}
    	// show form with value
    	$sql="SELECT * FROM tb_sale_agent where agent_id=".$id;
    	$rows= $db->getGlobalDbRow($sql);
    	$formAgent = new sales_Form_FrmStock(null);
		$formShowAgent = $formAgent->showSaleAgentForm($rows, $session_stock->stockID);
		Application_Model_Decorator::removeAllDecorator($formShowAgent);
		$this->view->form_agent = $formShowAgent;
		
		
		$formpopup = new Application_Form_FrmPopup(null);
		//for add location
		$formAdd = $formpopup->popuLocation(null);
		Application_Model_Decorator::removeAllDecorator($formAdd);
		$this->view->form_addstock = $formAdd;
	}
	
	private function formSaleAgent($formElement) {
		$elements = array();
		$elements[''] = $formElement->getElement('stock_id');
		$elements['NAME'] = $formElement->getElement('name');
		$elements['PHONE'] = $formElement->getElement('phone');
		$elements['EMAIL'] = $formElement->getElement('email');
		$elements['ADDRESS'] = $formElement->getElement('address');
		$elements['TITLE'] = $formElement->getElement('job_title');
		$elements['DESCRIPTION'] = $formElement->getElement('description');
		return $elements;
	}
	//for get current price getCurrentPrice
	public function addAgentAction(){
		if($this->getRequest()->isPost()){
			$post=$this->getRequest()->getPost();
			$update_agent = new sales_Model_DbTable_DbSalesAgent();
			$agent_id = $update_agent ->addNewAgent($post);
			$result = array("agent_id"=>$agent_id);
			echo Zend_Json::encode($result);
			exit();
		}
		
	}
}

