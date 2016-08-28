<?php 
class sales_Form_FrmSearch extends Zend_Form
{
	public function init()
	{
		
	}
	//affter not use 
	public function FrmSearchFromCustomer(){
		$tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$db=new Application_Model_DbTable_DbGlobal();
		
		$nameValue = $request->getParam('order');
		$nameElement = new Zend_Form_Element_Text('order');
		$nameElement->setValue($nameValue);
		$this->addElement($nameElement);
		
		$returninValue = $request->getParam('return_in');
		$returninElement = new Zend_Form_Element_Text('return_in');
		$returninElement->setValue($returninValue);
		$this->addElement($returninElement);
		
		$startDateValue = $request->getParam('search_start_date');
		$startDateElement = new Zend_Form_Element_Text('search_start_date');
		$startDateElement->setValue($startDateValue);
		$this->addElement($startDateElement);
			
		$endDateValue = $request->getParam('search_end_date');
		$endDateElement = new Zend_Form_Element_Text('search_end_date');
		$endDateElement->setValue($endDateValue);
		$this->addElement($endDateElement);
		
		$rowCustomers=$db->getGlobalDb('SELECT customer_id, cust_name FROM tb_customer WHERE cust_name!="" AND is_active=1 ORDER BY customer_id DESC');
		$agentValue = $request->getParam('customer_id');
		$options=array(''=>$tr->translate('Please_Select_Customer'));
		if(!empty($rowCustomers)) foreach($rowCustomers as $rowCustomer) $options[$rowCustomer['customer_id']]=$rowCustomer['cust_name'];
		$customer_id=new Zend_Form_Element_Select('customer_id');
		$customer_id->setMultiOptions($options);
		$customer_id->setattribs(array(
				'id'=>'customer_id',
				'class'=>'validate[required]'
		));
		$customer_id->setValue($agentValue);
		$this->addElement($customer_id);
		
		Application_Form_DateTimePicker::addDateField(array('search_start_date', 'search_end_date'));
		
		return $this;
	}
    		
}