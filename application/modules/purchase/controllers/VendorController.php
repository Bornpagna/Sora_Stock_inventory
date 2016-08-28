<?php
class purchase_vendorController extends Zend_Controller_Action
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
		$this->view->formFilter = $formFilter;
		Application_Model_Decorator::removeAllDecorator($formFilter);
		$list = new Application_Form_Frmlist();
		$db = new Application_Model_DbTable_DbGlobal();
		$vendorSql = "SELECT v.vendor_id, v.v_name,v.contact_name, v.phone, v.email, v.website,v.add_name
		FROM tb_vendor AS v WHERE 1 ";
		/*$vendorSql = "SELECT v.vendor_id, v.v_name,v.contact_name, v.phone, v.email, v.website,v.add_name,
		CONCAT(cur.Description, ' ', cur.Symbol) AS currency FROM tb_vendor AS v
		INNER JOIN tb_currency AS cur ON cur.CurrencyId=v.CurrencyId ";*/
		if($this->getRequest()->isPost()){
			$post = $this->getRequest()->getPost();
			//echo $post["order"];
			if($post['name'] !=''){
				$vendorSql .= " AND v.v_name LIKE '%".trim($post['name'])."%'";
			}
			if($post['phone'] !=''){
				$vendorSql .= " AND v.phone LIKE '%".trim($post['phone'])."%'";
				$vendorSql .= " OR v.contact_name LIKE '%".trim($post['phone'])."%'";
			}
			if($post['email'] !=''){
				$vendorSql .= " AND v.email LIKE '%".trim($post['email'])."%'";
				$vendorSql .= " OR v.website LIKE '%".trim($post['email'])."%'";
			}
			if($post['address'] !=''){
				$vendorSql .= " AND v.add_name LIKE '%".trim($post['address'])."%'";
			}
		}
		$vendorSql.=" ORDER BY v.vendor_id DESC";	
		$rows=$db->getGlobalDb($vendorSql);
		$columns=array("VENDOR_NAME_CAP","CON_NAME_CAP","CONTACT_NUM_CAP","EMAIL_CAP","WEBSITE_CAP","ADDRESS_CAP");
		$link=array(
				'module'=>'purchase','controller'=>'vendor','action'=>'update-vendor',
		);
		$urlEdit = BASE_URL . "/purchase/vendor/update-vendor";
		$this->view->list=$list->getCheckList(1, $columns, $rows, array('v_name'=>$link,'contact_name'=>$link),$urlEdit);
	}
	
	public function updateVendorAction() {
		$session_stock=new Zend_Session_Namespace('stock');
		if($this->getRequest()->isPost())
		{
			$post = $this->getRequest()->getPost();
			$vendor = new purchase_Model_DbTable_DbAddVendor();
			$vendor->updateVendor($post);
			$this->_redirect("purchase/vendor/index");
		}
		$id = ($this->getRequest()->getParam('id'))? $this->getRequest()->getParam('id'): '0';
		// show form with value
		$sql = "SELECT * FROM tb_vendor WHERE vendor_id=".$id;
		$db = new Application_Model_DbTable_DbGlobal();
		$row = $db->getGlobalDbRow($sql);
		// lost item info
		$formStock= new purchase_Form_FrmVendor($row);
		$session_stock->stockID;
		$formStockEdit = $formStock->AddVendorForm($row, $session_stock->stockID);
		Application_Model_Decorator::removeAllDecorator($formStockEdit);// omit default zend html tag
		$this->view->orderForm = $formStockEdit;
	
		//control action
		$formControl = new Application_Form_FrmAction(null);
		$formViewControl = $formControl->AllAction(null);
		Application_Model_Decorator::removeAllDecorator($formViewControl);
		$this->view->control = $formViewControl;
	}
	public function addAction()
	{
		if($this->getRequest()->isPost())
		{
			$post = $this->getRequest()->getPost();
			try{
				if(@$post['Save']!="")
				{
					$vendor = new purchase_Model_DbTable_DbAddVendor();
					$vendor->addVendor($post);
					$this->_redirect("purchase/vendor/index");
				}
				if(@$post['SaveNew']!="")
				{
					$vendor = new purchase_Model_DbTable_DbAddVendor();
					$vendor->addVendor($post);
					$this->_redirect("purchase/vendor/add");
				}
				elseif(@$post['btn_new']=='New')
				{
					$this->_redirect('/purchase/vendor/add');
				}
			}catch(Exception $e){
				Application_Form_FrmMessage::message("You have Error".$e->getMessage());
			}
		}
		/////////////////for veiw form
		$formStock = new purchase_Form_FrmVendor(null);
		$formStockAdd = $formStock->AddVendorForm(null);
		Application_Model_Decorator::removeAllDecorator($formStockAdd);
		$this->view->form = $formStockAdd;
		//.end controller
		$formControl = new Application_Form_FrmAction(null);
		$formViewControl = $formControl->AllAction(null);
		Application_Model_Decorator::removeAllDecorator($formViewControl);
		$this->view->control = $formViewControl;
	}
	
	public function deleteVendorAction() {
		$id = ($this->getRequest()->getParam('id'));
		$sql = "DELETE FROM tb_vendor WHERE vendor_id IN ($id)";
		$deleteObj = new Application_Model_DbTable_DbGlobal();
		$deleteObj->deleteRecords($sql);
		$this->_redirect('/purchase/vendor/index');
	}
	//for add vendor from purchase
	final function addVendorAction(){
		$post=$this->getRequest()->getPost();
		$add_vendor = new purchase_Model_DbTable_DbAddVendor();
		$vid = $add_vendor->addNewVenor($post);
		$result = array('vid'=>$vid);
		echo Zend_Json::encode($result);
		exit();
	}
	
}