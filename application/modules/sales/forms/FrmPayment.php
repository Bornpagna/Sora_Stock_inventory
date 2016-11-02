<?php

class Sales_Form_FrmPayment extends Zend_Form
{
    protected function GetuserInfo(){
    	$user_info = new Application_Model_DbTable_DbGetUserInfo();
    	$result = $user_info->getUserInfo();
    	return $result;
    }
    public function Payment($data=null)
    {
    	$user_info = new Application_Model_DbTable_DbGetUserInfo();
    	$result = $user_info->getUserInfo();
    	
    	$tr = Application_Form_FrmLanguages::getCurrentlanguage();
    	$request=Zend_Controller_Front::getInstance()->getRequest();
    	$db=new Application_Model_DbTable_DbGlobal();

    	$customerid=new Zend_Form_Element_Select('customer_id');
    	$customerid ->setAttribs(array(
    			'class' => 'validate[required] form-control select2me',
    			'Onchange'=>'getInvoice(1)'
    			));
    	
    	$options = $db->getAllCustomer(1);
    	$customerid->setMultiOptions($options);
    	$this->addElement($customerid);
    	
    	$roder_element= new Zend_Form_Element_Text("receipt");
    	$roder_element->setAttribs(array('placeholder' => 'Optional','class'=>'form-control',"readonly"=>true,
    			"onblur"=>"CheckPOInvoice();"));
    	$this->addElement($roder_element);
    	$qo = $db->getSalesNumber(1);
    	$roder_element->setValue($qo);
    	$this->addElement($roder_element);
    	
    	$locationID = new Zend_Form_Element_Select('invoice_id');
    	$locationID ->setAttribs(array('class'=>'validate[required] form-control select2me','readonly'=>true));
    	$options = $db->getAllInvoice(1,1);
    	$locationID->setMultiOptions($options);
    	$locationID->setattribs(array(
    			'Onchange'=>'getInvoice(2)'));
    	$this->addElement($locationID);
    	    	
    	$rowspayment= $db->getGlobalDb('SELECT * FROM tb_paymentmethod');
    	if($rowspayment) {
    		foreach($rowspayment as $readCategory) $options_cg[$readCategory['payment_typeId']]=$readCategory['payment_name'];
    	}
    	$paymentmethodElement = new Zend_Form_Element_Select('payment_name');
    	$paymentmethodElement->setMultiOptions($options_cg);
    	$this->addElement($paymentmethodElement);
    	$paymentmethodElement->setAttribs(array("class"=>"form-control select2me"));
    	
    	
    	$descriptionElement = new Zend_Form_Element_Textarea('remark');
    	$descriptionElement->setAttribs(array("class"=>'form-control',"rows"=>3));
    	$this->addElement($descriptionElement);
    	
    	$allTotalElement = new Zend_Form_Element_Text('all_total');
    	$allTotalElement->setAttribs(array("class"=>"form-control",'readonly'=>'readonly','require'=>true));
    	$this->addElement($allTotalElement);
    	
    	$netTotalElement = new Zend_Form_Element_Text('paid');
    	$netTotalElement->setAttribs(array('readonly'=>'readonly',));
    	$this->addElement($netTotalElement);
    	
    	$remainlElement = new Zend_Form_Element_Text('balance');
    	$remainlElement->setAttribs(array('readonly'=>'readonly',"class"=>"red form-control"));
    	$this->addElement($remainlElement);
    	
    	$date_inElement = new Zend_Form_Element_Text('date_in');
    	$date =new Zend_Date();
    	$date_inElement ->setAttribs(array('class'=>'validate[required] form-control form-control-inline date-picker'));
    	$date_inElement ->setValue($date->get('MM/d/Y'));
    	$this->addElement($date_inElement);
    	
    	$paidElement = new Zend_Form_Element_Text('paid');
    	$paidElement->setAttribs(array('class'=>'custom[number] form-control','onkeyup'=>'doRemain();'));
    	$this->addElement($paidElement);
    	
    	Application_Form_DateTimePicker::addDateField(array('order_date','date_in'));
    		if($data != null) {
    			$idElement = new Zend_Form_Element_Hidden('id');
    			$this->addElement($idElement);
    			$idElement ->setValue($data["id"]);
    			
    			$customerid->setValue($data["customer_id"]);
    			$locationID->setValue($data['branch_id']);
    			
    			$currencyElement->setValue($data['currency_id']);
    			$saleagent_id->setValue($data['saleagent_id']);
    			$descriptionElement->setValue($data['remark']);
    			$roder_element->setValue($data['sale_no']);
    			$totalAmountElement->setValue($data['all_total']);
    			$dis_valueElement->setValue($data['discount_value']);
    			$allTotalElement->setValue($data['net_total']);
    		
    		} else {
    	}
     	return $this;
    }

}

