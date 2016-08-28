<?php
class Application_Form_Frmsearch extends Zend_Form
{
	public function init()
	{
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$db=new Application_Model_DbTable_DbGlobal();
		
		////////////////////////////////////////////////////////Purchase*****/////////////////////////////////////////////
		$tr=Application_Form_FrmLanguages::getCurrentlanguage();
		//get sales or purchase id text
		$nameValue = $request->getParam('order');
		$nameElement = new Zend_Form_Element_Text('order');
		$nameElement->setValue($nameValue);
		$this->addElement($nameElement);
		
		//get phone text
		$phoneElement = new Zend_Form_Element_Text('phone');
		$this->addElement($phoneElement);
		
		$rs=$db->getGlobalDb('SELECT vendor_id, v_name FROM tb_vendor WHERE v_name!="" AND is_active=1 ');
		$options=array($tr->translate('Please_Select'));
		$vendorValue = $request->getParam('vendor_name');
		if(!empty($rs)) foreach($rs as $read) $options[$read['vendor_id']]=$read['v_name'];
		$vendor_element=new Zend_Form_Element_Select('vendor_name');
		$vendor_element->setMultiOptions($options);
		$vendor_element->setAttribs(array(
				'id'=>'vendor_id',
				//'onchange'=>'this.form.submit()',
				//'class'=>'demo-code-language',
		));
		$vendor_element->setValue($vendorValue);
		$this->addElement($vendor_element);
		

		$rs=$db->getGlobalDb( ('SELECT agent_id, name FROM tb_sale_agent WHERE name!="" ORDER BY agent_id DESC'));
		$options=array($tr->translate('Please_Select'));
		$agentValue = $request->getParam('sale_agent_id');
		if(!empty($rs)) foreach($rs as $read) $options[$read['agent_id']]=$read['name'];
		$sale_agent=new Zend_Form_Element_Select('sale_agent_id');
		$sale_agent->setMultiOptions($options);
		$sale_agent->setAttribs(array(
				'id'=>'sale_agent_id',
				'class'=>'validate[required]',
			//	'onchange'=>'this.form.submit()',
		));
		$sale_agent->setValue($agentValue);
		$this->addElement($sale_agent);
		
		/////////////Date of lost item		/////////////////
		$startDateValue = $request->getParam('search_start_date');
		$endDateValue = $request->getParam('search_end_date');
		if($endDateValue==""){
			$endDateValue=date("Y-m-d");
			$startDateValue=date("Y-m-d");
		}
		
		
		$startDateElement = new Zend_Form_Element_Text('search_start_date');
		$startDateElement->setValue($startDateValue);
		
		$this->addElement($startDateElement);
		$endDateElement = new Zend_Form_Element_Text('search_end_date');
		
		$endDateElement->setValue($endDateValue);
		$this->addElement($endDateElement);
		
		//status of purchase or sales
// 		$statusValue = $request->getParam('status');
// 		$optionsStatus=array(''=>'Please Select',1=>'Quote',2=>'Open',3=>'In Progress',4=>'Invoice',5=>'Paid',6=>"Cancel");
// 		$status=new Zend_Form_Element_Select('status');
// 		$status->setMultiOptions($optionsStatus);
// 		$status->setattribs(array(
// 				'id'=>'status',
// 			//	'onchange'=>'this.form.submit()',
// 		));
// 		$status->setValue($statusValue);
// 		$this->addElement($status);
		
		$rs=$db->getGlobalDb('SELECT DISTINCT Name,LocationId FROM tb_sublocation WHERE Name!="" AND status=1 ');
		$options=array($tr->translate('Please_Select'));
		$locationValue = $request->getParam('LocationId');
		foreach($rs as $read) $options[$read['LocationId']]=$read['Name'];
		$location_id=new Zend_Form_Element_Select('LocationId');
		$location_id->setMultiOptions($options);
		$location_id->setAttribs(array(
				'id'=>'LocationId',
				'onchange'=>'this.form.submit()',
		));
		$location_id->setValue($locationValue);
		$this->addElement($location_id);
	
	   ////////////////////////////////////////////////*******////////////////////////////////
		
		//Customer
		$rowCustomers=$db->getGlobalDb('SELECT customer_id, cust_name FROM tb_customer WHERE cust_name!="" AND is_active=1 ORDER BY customer_id DESC');
		$agentValue = $request->getParam('customer_id');
		$optionsCUS=array($tr->translate('Please_Select_Customer'));
		if(!empty($rowCustomers)) foreach($rowCustomers as $rowCustomer) $optionsCUS[$rowCustomer['customer_id']]=$rowCustomer['cust_name'];
		$customer_id=new Zend_Form_Element_Select('customer_id');
		$customer_id->setMultiOptions($optionsCUS);
		$customer_id->setattribs(array(
				'id'=>'customer_id',
				'class'=>'validate[required]'
		));
		$customer_id->setValue($agentValue);
		$this->addElement($customer_id);
		 
		//status of purchase
		$statusCOValue=4;
		$statusCOValue = $request->getParam('status');
		$optionsCOStatus=array(''=>$tr->translate('ALL'),2=>$tr->translate('OPEN'),3=>$tr->translate('IN_PROGRESS'),4=>$tr->translate('PAID'),5=>$tr->translate('RECEIVED'),6=>$tr->translate('MENU_CANCEL'));
		$statusCO=new Zend_Form_Element_Select('status');
		$statusCO->setMultiOptions($optionsCOStatus);
		$statusCO->setattribs(array(
				'id'=>'status',
			//	'onchange'=>'this.form.submit()',
		));
		if($statusCOValue==""){
			//$statusCOValue=4;
		}
		$statusCO->setValue($statusCOValue);
		$this->addElement($statusCO);
		
		$status_sale=4;
		$status_sale = $request->getParam('status_sale');
		$options_saleStatus=array(''=>$tr->translate('ALL'),2=>$tr->translate('OPEN'),3=>$tr->translate('IN_PROGRESS'),4=>$tr->translate('PAID'),5=>$tr->translate('STATUS_FULL_DELIVER'),6=>$tr->translate('MENU_CANCEL'));
		$status_sales=new Zend_Form_Element_Select('status_sale');
		$status_sales->setMultiOptions($options_saleStatus);
		$status_sales->setattribs(array(
				'id'=>'status',
				//	'onchange'=>'this.form.submit()',
		));
		if($status_sale==""){
			//$statusCOValue=4;
		}
		$status_sales->setValue($status_sale);
		$this->addElement($status_sales);
		 
		Application_Form_DateTimePicker::addDateField(array('search_start_date', 'search_end_date'));
		
		//vendor customer search  //////////////////////////////////////////////////////////
		$getnameValue = $request->getParam('name');
		$nameElement = new Zend_Form_Element_Text('name');
		$nameElement->setValue($getnameValue);
		$this->addElement($nameElement);
		
		$getcontactValue = $request->getParam('Contact');
		$contactElement = new Zend_Form_Element_Text('Contact');
		$contactElement->setValue($getcontactValue);
		$this->addElement($contactElement);
		
		$getphoneValue = $request->getParam('phone');
		$phoneElement = new Zend_Form_Element_Text('phone');
		$phoneElement->setValue($getphoneValue);
		$this->addElement($phoneElement);
		
		$getemailValue = $request->getParam('email');
		$emailElement = new Zend_Form_Element_Text("email");
		$emailElement->setValue($getemailValue);
		$this->addElement($emailElement);
		
		$addValue = $request->getParam('address');
		$addressElement = new Zend_Form_Element_Text("address");
		$addressElement->setValue($addValue);
		$this->addElement($addressElement);
		
// 		$websiteElement = new Zend_Form_Element_Text("website");
// 		$this->addElement($websiteElement);
		
// 		$rowCustomers=$db->getGlobalDb('SELECT customer_id, cust_name FROM tb_customer');
// 		$agentValue = $request->getParam('customer_id');
// 		$options=array(''=>'Please Select Customer');
// 		if(!empty($rowCustomers)) foreach($rowCustomers as $rowCustomer) $options[$rowCustomer['customer_id']]=$rowCustomer['cust_name'];
// 		$customer_id=new Zend_Form_Element_Select('customer_id');
// 		$customer_id->setMultiOptions($options);
// 		$customer_id->setattribs(array(
// 				'id'=>'customer_id',
// 				'class'=>'demo-code-language',
// 				//'onchange'=>'this.form.submit()',
// 		));
// 		$customer_id->setValue($agentValue);
// 		$this->addElement($customer_id);
		
// 		$rs=$db->getGlobalDb('SELECT CurrencyId,Description FROM tb_currency');
// 		$options=array('Please Select');
// 		$currency_value = $request->getParam('currency_id');
// 		foreach($rs as $read) $options[$read['CurrencyId']]=$read['Description'];
// 		$currency_id=new Zend_Form_Element_Select('currency_id');
// 		$currency_id->setMultiOptions($options);
// 		$currency_id->setAttribs(array(
// 				'id'=>'CurrencyId',
// 				//'onchange'=>'this.form.submit()',
// 				'class'=>'demo-code-language',
// 		));
// 		$currency_id->setValue($currency_value);
// 		$this->addElement($currency_id);
		
	}
	public function Search()
	{
		$nameElement = new Zend_Form_Element_Text('Name');
		//$nameElement ->setAttribs(array('Name'=>'Name'));
		$this->addElement($nameElement);
		
		$contactElement = new Zend_Form_Element_Text('Contact');
		//$contactElement->setAdttribs(array('Contact'=>'Contact'));
		$this->addElement($contactElement);
		
		$phoneElement = new Zend_Form_Element_Text('phone');
		//$phoneElement->setAdttribs(array('phone'=>'phone'));
		$this->addElement($phoneElement);
		
		$refreshElement = new Zend_Form_Element_Submit('Refresh');
		$refreshElement->setAttribs(array('Phone'=>'Phone'));
		$this->addElement($refreshElement);
		return $this;
	
	}
	public function customerSearch(){
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$db=new Application_Model_DbTable_DbGlobal();
		
		$getnameValue = $request->getParam('name');
		$nameElement = new Zend_Form_Element_Text('name');
		$nameElement->setValue($getnameValue);
		$this->addElement($nameElement);
		
		$getcontactValue = $request->getParam('Contact');
		$contactElement = new Zend_Form_Element_Text('Contact');
		$contactElement->setValue($getcontactValue);
		$this->addElement($contactElement);
		
		$getphoneValue = $request->getParam('phone');
		$phoneElement = new Zend_Form_Element_Text('phone');
		$phoneElement->setValue($getphoneValue);
		$this->addElement($phoneElement);
		
		$getemailValue = $request->getParam('email');
		$emailElement = new Zend_Form_Element_Text("email");
		$emailElement->setValue($getemailValue);
		$this->addElement($emailElement);
		
		$rs = $db->getGlobalDb('SELECT type_id, price_type_name FROM tb_price_type WHERE public = 1 AND price_type_name!=""');
		if($rs) {
			foreach($rs as $tp) $options[$tp['type_id']]=$tp['price_type_name'];
		}
		$tpElement = new Zend_Form_Element_Select('type_price');
		$tp_value = $request->getParam('type_price');
		$tpElement->setMultiOptions($options);
		$tpElement->setAttribs(array(
				'type_id'=>'type_id',
				'onchange'=>'this.form.submit()',
		));
		$tpElement->setValue($tp_value);
		$this->addElement($tpElement);
		
		return $this;
	}
}

