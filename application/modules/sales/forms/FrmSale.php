<?php

class Sales_Form_FrmSale extends Zend_Form
{
    protected function GetuserInfo(){
    	$user_info = new Application_Model_DbTable_DbGetUserInfo();
    	$result = $user_info->getUserInfo();
    	return $result;
    }
    public function SaleOrder($data=null)
    {
    	$user_info = new Application_Model_DbTable_DbGetUserInfo();
    	$result = $user_info->getUserInfo();
    	
    	$tr = Application_Form_FrmLanguages::getCurrentlanguage();
    	$request=Zend_Controller_Front::getInstance()->getRequest();
    	$db=new Application_Model_DbTable_DbGlobal();

    	$rs=$db->getGlobalDb('SELECT id, cust_name FROM tb_customer WHERE cust_name!="" AND status=1 ORDER BY id DESC');
    	$options=array(''=>$tr->translate('Please_Select'),'-1'=>$tr->translate('Add_New_Vendor'));
    	if(!empty($rs)) foreach($rs as $read) $options[$read['id']]=$read['cust_name'];
    	$vendor_id=new Zend_Form_Element_Select('customer_id');
    	$vendor_id ->setAttribs(array(
    			'class' => 'validate[required] form-control select2me',
    			'Onchange'=>'getCustomerInfo()'
    			));
    	$vendor_id->setMultiOptions($options);
    	$this->addElement($vendor_id);
    	
    	$roder_element= new Zend_Form_Element_Text("txt_order");
    	$roder_element->setAttribs(array('placeholder' => 'Optional','class'=>'form-control',"readonly"=>true,
    			"onblur"=>"CheckPOInvoice();"));
    	$this->addElement($roder_element);
    	$qo = $db->getSalesNumber(1);
    	$roder_element->setValue($qo);
    	$this->addElement($roder_element);
    	
    	$user= $this->GetuserInfo();
    	$options="";
    	$sql = "SELECT id, name FROM tb_sublocation WHERE name!='' ";
    	if($user["level"]==1 OR $user["level"]== 2){
    		$options=array("1"=>$tr->translate("Please_Select"),"-1"=>$tr->translate("ADD_NEW_LOCATION"));
    	}
    	else{
    		$sql.=" AND id = ".$user["location_id"];
    	}
    	$sql.=" ORDER BY id DESC";
    	$rs=$db->getGlobalDb($sql);
    	if(!empty($rs)) foreach($rs as $read) $options[$read['id']]=$read['name'];
    	$locationID = new Zend_Form_Element_Select('branch_id');
    	$locationID ->setAttribs(array('class'=>'validate[required] form-control select2me'));
    	$locationID->setMultiOptions($options);
    	$locationID->setattribs(array(
    			'Onchange'=>'AddLocation()',));
    	$this->addElement($locationID);
    	    	
    	$rowspayment= $db->getGlobalDb('SELECT * FROM tb_paymentmethod');
    	if($rowspayment) {
    		foreach($rowspayment as $readCategory) $options_cg[$readCategory['payment_typeId']]=$readCategory['payment_name'];
    	}
    	$paymentmethodElement = new Zend_Form_Element_Select('payment_name');
    	$paymentmethodElement->setMultiOptions($options_cg);
    	$this->addElement($paymentmethodElement);
    	$paymentmethodElement->setAttribs(array("class"=>"form-control select2me"));
    	$rowsPayment = $db->getGlobalDb('SELECT id, description,symbal FROM tb_currency WHERE status = 1 ');
    	if($rowsPayment) {
    		foreach($rowsPayment as $readPayment) $options_cur[$readPayment['id']]=$readPayment['description'].$readPayment['symbal'];
    	}	 
    	$currencyElement = new Zend_Form_Element_Select('currency');
    	$currencyElement->setAttribs(array('class'=>'demo-code-language form-control select2me'));
    	$currencyElement->setMultiOptions($options_cur);
    	$this->addElement($currencyElement);
    	
    	$descriptionElement = new Zend_Form_Element_Textarea('remark');
    	$descriptionElement->setAttribs(array("class"=>'form-control',"rows"=>3));
    	$this->addElement($descriptionElement);
    	
    	$allTotalElement = new Zend_Form_Element_Text('all_total');
    	$allTotalElement->setAttribs(array("class"=>"form-control",'readonly'=>'readonly','style'=>'text-align:right'));
    	$this->addElement($allTotalElement);
    	
    	$netTotalElement = new Zend_Form_Element_Text('net_total');
    	$netTotalElement->setAttribs(array('readonly'=>'readonly',));
    	$this->addElement($netTotalElement);
    	
    	$opt=array();
    	$rows = $db->getGlobalDb('SELECT id ,name FROM `tb_sale_agent` WHERE name!="" AND status=1');
    	if(!empty($rows)) {
    		foreach($rows as $rs) $opt[$rs['id']]=$rs['name'];
    	}
    	$saleagent_id = new Zend_Form_Element_Select('saleagent_id');
    	$saleagent_id->setAttribs(array('class'=>'demo-code-language form-control select2me'));
    	$saleagent_id->setMultiOptions($opt);
    	$this->addElement($saleagent_id);
    	
    	
    	$discountValueElement = new Zend_Form_Element_Text('discount_value');
    	$discountValueElement->setAttribs(array('class'=>'input100px form-control','onblur'=>'doTotal()',));
    	$this->addElement($discountValueElement);
    	
    	$discountRealElement = new Zend_Form_Element_Text('discount_real');
    	$discountRealElement->setAttribs(array('readonly'=>'readonly','class'=>'input100px form-control',));
    	$this->addElement($discountRealElement);
    	
    	$globalRealElement = new Zend_Form_Element_Hidden('global_disc');
    	$globalRealElement->setAttribs(array("class"=>"form-control"));
    	$this->addElement($globalRealElement);
    	
    	$discountValueElement = new Zend_Form_Element_Text('discount_value');
    	$discountValueElement->setAttribs(array('class'=>'input100px','onblur'=>'doTotal();','style'=>'text-align:right'));
    	$this->addElement($discountValueElement);
    	
    	$dis_valueElement = new Zend_Form_Element_Text('dis_value');
    	$dis_valueElement->setAttribs(array("required"=>1,'placeholder' => 'Discount Value','style'=>'text-align:right'));
    	$dis_valueElement->setValue(0);
    	$dis_valueElement->setAttribs(array("onkeyup"=>"calculateDiscount();","class"=>"form-control"));
    	$this->addElement($dis_valueElement);
    	
    	$totalAmountElement = new Zend_Form_Element_Text('totalAmoun');
    	$totalAmountElement->setAttribs(array('readonly'=>'readonly','style'=>'text-align:right',"class"=>"form-control"
    	));
    	$this->addElement($totalAmountElement);
    	
    	$remainlElement = new Zend_Form_Element_Text('remain');
    	$remainlElement->setAttribs(array('readonly'=>'readonly','style'=>'text-align:right',"class"=>"red form-control"));
    	$this->addElement($remainlElement);
    	
    	$balancelElement = new Zend_Form_Element_Text('balance');
    	$balancelElement->setAttribs(array('readonly'=>'readonly','style'=>'text-align:right',"class"=>"form-control"));
    	$this->addElement($balancelElement);
    	
    	$date_inElement = new Zend_Form_Element_Text('date_in');
    	$date =new Zend_Date();
    	$date_inElement ->setAttribs(array('class'=>'validate[required] form-control form-control-inline date-picker'));
    	$date_inElement ->setValue($date->get('MM/d/Y'));
    	$this->addElement($date_inElement);
    	
    	$dateOrderElement = new Zend_Form_Element_Text('order_date');
    	$dateOrderElement ->setAttribs(array('class'=>'col-md-3 validate[required] form-control form-control-inline date-picker','placeholder' => 'Click to Choose Date'));
    	$dateOrderElement ->setValue($date->get('MM/d/Y'));
    	$this->addElement($dateOrderElement);
    	
    	$dateElement = new Zend_Form_Element_Text('date');
    	$this->addElement($dateElement);
    	 
    	$totalElement = new Zend_Form_Element_Text('total');
    	$this->addElement($totalElement);
    	
    	$totaTaxElement = new Zend_Form_Element_Text('total_tax');
    	$totaTaxElement->setAttribs(array('class'=>'custom[number] form-control','style'=>'text-align:right'));
    	$this->addElement($totaTaxElement);
    	
    	$paidElement = new Zend_Form_Element_Text('paid');
    	$paidElement->setAttribs(array('class'=>'custom[number] form-control','onkeyup'=>'doRemain();','style'=>'text-align:right'));
    	$this->addElement($paidElement);
    	
    	Application_Form_DateTimePicker::addDateField(array('order_date','date_in'));
    		if($data != null) {
    			$idElement = new Zend_Form_Element_Text('id');
    			$this->addElement($idElement);
    			
    			$recieve_id = new Zend_Form_Element_Hidden("recieve_id");
    			$this->addElement($recieve_id);
    			$recieve_id->setValue($data["recieve_id"]);
    			
    			$oldlocationIdElement = new Zend_Form_Element_Text('old_location');
    			$this->addElement($oldlocationIdElement);
    			
    			$idElement ->setValue($data["order_id"]);
    			$date_inElement->setValue($data["date_in"]);
    			$oldStatusElement = new Zend_Form_Element_Hidden('oldStatus');
    			$this->addElement($oldStatusElement);
    			$vendor_id->setValue($data["vendor_id"]);

    			$oldStatusElement->setValue($data['status']);
    			$locationID->setvalue($data['LocationId']);
    			$oldlocationIdElement->setvalue($data['LocationId']);
    			$dateOrderElement->setValue($data["date_order"]);
    			$roder_element->setValue($data['order']);
    			$roder_element->setAttribs(array('readonly'=>'readonly'));
    			$paymentmethodElement->setValue($data['payment_method']);
    			$currencyElement->setValue($data['currency_id']);
    			$paidElement->setValue($data['paid']);
    			$totalAmountElement->setValue($data["all_total"]);
    			//$remainlElement->setvalue($data['balance']);
    			$allTotalElement->setValue($data['all_total']);
    			$discountValueElement->setValue($data['discount_value']);
    			$netTotalElement->setValue($data['net_total']);   
    			$balancelElement->setValue($data["balance"]);
    			$globalRealElement->setValue($data["discount_real"]);
    		
    		} else {
    	}
     	return $this;
    }

}

