<?php 
class sales_Form_FrmVendor extends Zend_Form
{
	public function init()
    {	
	}
	/////////////	Form vendor		/////////////////
public function AddVendorForm($data=null) {
		$db=new Application_Model_DbTable_DbGlobal();
		
// 		$vendorIDElement = new Zend_Form_Element_Hidden('stock_id');
// 		$this->addElement($vendorIDElement);
// 		$vendorIDElement->setValue($db);
		
		$nameElement = new Zend_Form_Element_Text('txt_name');
		$nameElement->setAttribs(array('class'=>'validate[required]',));
		//$nameElement->setValue('txt_name');
    	$this->addElement($nameElement);
    	
    	$contactElement = new Zend_Form_Element_Text('txt_contact_name');
    	$contactElement->setAttribs(array('class'=>'validate[required]',));
    	$this->addElement($contactElement);
    	
    	$phoneElement = new Zend_Form_Element_Text('txt_phone');
    	$this->addElement($phoneElement);
    	
    	$faxElement = new Zend_Form_Element_Text('txt_fax');
    	$this->addElement($faxElement);
    	
    	$emailElement = new Zend_Form_Element_Text('txt_mail');
    	//$emailElement->setAttribs(array('class'=>'validate[custom[email]]',));
    	$this->addElement($emailElement);
    	
    	$websiteElement = new Zend_Form_Element_Text('txt_website');
    	$this->addElement($websiteElement);
    	
    	$remarkElement = new Zend_Form_Element_Textarea('txt_addremark');
    	$this->addElement($remarkElement);
    	
    	$rowsPayment = $db->getGlobalDb('SELECT PaymentTermsId, Name FROM tb_paymentterm');
    	$options=array(''=>'Please select');
    	if($rowsPayment) {
    		foreach($rowsPayment as $readPayment) $options[$readPayment['PaymentTermsId']]=$readPayment['Name'];
    	}
    	$paytermElement = new Zend_Form_Element_Select('pay_term');
    	$paytermElement->setMultiOptions($options);
    	$this->addElement($paytermElement);
    	
    	$rowsCarrier = $db->getGlobalDb('SELECT CarrierId, carrier_name FROM tb_carrier');
    	$options=array(''=>'Please select');
    	if($rowsCarrier) {
    		foreach($rowsCarrier as $readContact) $options[$readContact['CarrierId']]=$readContact['carrier_name'];
    	}
    	$carrierElement = new Zend_Form_Element_Select('carrier');
    	$carrierElement->setMultiOptions($options);
    	$this->addElement($carrierElement);
    	
    	$rowsPayment = $db->getGlobalDb('SELECT CurrencyId, Description,Symbol FROM tb_currency');
    	$options=array(''=>'Please select');
    	if($rowsPayment) {
    		foreach($rowsPayment as $readPayment) $options[$readPayment['CurrencyId']]=$readPayment['Description'].$readPayment['Symbol'];
    	}
    	
    	$currencyElement = new Zend_Form_Element_Select('currency');
    	$currencyElement->setMultiOptions($options);
    	$this->addElement($currencyElement);
    	
    	$paymentElement = new Zend_Form_Element_Text('ddl_payment_term');
    	$paymentElement->setAttribs(array('class'=>'validate[required]',));
    	$this->addElement($paymentElement);
    	    	
    	///update 
    	         
    	$addressElement = new Zend_Form_Element_Textarea('txt_address');
    	$this->addElement($addressElement);
    	
    	$balancelement = new Zend_Form_Element_Text('txt_balance');
    	$this->addElement($balancelement); 		
    	
    	if($data != null) {
    		
	       $idElement = new Zend_Form_Element_Hidden('id');
   		   $this->addElement($idElement);
    	   $idElement->setValue($data['vendor_id']);
   
    		$nameElement->setValue($data['v_name']);
    		$contactElement->setValue($data['contact_name']);
    		$phoneElement->setValue($data['phone']);
    		$faxElement->setValue($data['fax']);
    		$emailElement->setValue($data['email']);
    		$websiteElement->setValue($data['website']);
    		$remarkElement->setValue($data['vendor_remark']);
    		$paymentElement->setValue($data['PaymentTermsId']);
    		$carrierElement->setValue($data['CarrierId']);
    		$currencyElement->setValue($data['CurrencyId']);
    		
    		$balancelement = new Zend_Form_Element_Text('txt_balance');
    		$this->addElement($balancelement);
    	}
    	
    	return $this;
	}
public function AddCustomerForm($data=null) {
		
		$db=new Application_Model_DbTable_DbGlobal();
	
		$nameElement = new Zend_Form_Element_Text('txt_name');
		$nameElement->setAttribs(array('class'=>'validate[required]','placeholder'=>"Enter Customer Name"));
		//$nameElement->setValue('txt_name');
		$this->addElement($nameElement);
		 
		$contactElement = new Zend_Form_Element_Text('txt_contact_name');
		$contactElement->setAttribs(array('placeholder'=>"Enter Contact Name"));
		$this->addElement($contactElement);
		 
		$phoneElement = new Zend_Form_Element_Text('txt_phone');
		$phoneElement->setAttribs(array('placeholder'=>"Enter Phone Number"));
		$this->addElement($phoneElement);
		 
		$faxElement = new Zend_Form_Element_Text('txt_fax');
		$faxElement->setAttribs(array('placeholder'=>"Enter Fax Number"));
		$this->addElement($faxElement);
		 
		$emailElement = new Zend_Form_Element_Text('txt_mail');
		$emailElement->setAttribs(array(
			//	'class'=>'validate[custom[email]]',
				'placeholder'=>"Enter Email Address"));
		$this->addElement($emailElement);
		 
		$websiteElement = new Zend_Form_Element_Text('txt_website');
		$websiteElement->setAttribs(array('placeholder'=>"Enter Email Website"));
		$this->addElement($websiteElement);
		
		$rs = $db->getGlobalDb('SELECT type_id, price_type_name FROM tb_price_type WHERE public = 1 AND price_type_name!=""');
		if($rs) {
			foreach($rs as $tp) $options[$tp['type_id']]=$tp['price_type_name'];
		}
		$tpElement = new Zend_Form_Element_Select('type_price');
		//$tpElement->setAttribs(array('class'=>'demo-code-language'));
		$tpElement->setMultiOptions($options);
		$this->addElement($tpElement);
		
		 
// 		$rowsPayment = $db->getGlobalDb('SELECT PaymentTermsId, Name FROM tb_paymentterm');
// 		$options=array(''=>'Please select');
// 		if($rowsPayment) {
// 			foreach($rowsPayment as $readPayment) $options[$readPayment['PaymentTermsId']]=$readPayment['Name'];
// 		}
// 		$paytermElement = new Zend_Form_Element_Select('pay_term');
// 		$paytermElement->setAttribs(array('class'=>'demo-code-language'));
// 		$paytermElement->setMultiOptions($options);
// 		$this->addElement($paytermElement);
		
// 		$discountElement = new Zend_Form_Element_Text('txt_discount');
// 		$this->addElement($discountElement);
		 
// 		$rowsPayment = $db->getGlobalDb('SELECT CurrencyId, Description,Symbol FROM tb_currency');
// 		$options=array(''=>'Please select');
// 		if($rowsPayment) {
// 			foreach($rowsPayment as $readPayment) $options[$readPayment['CurrencyId']]=$readPayment['Description'].$readPayment['Symbol'];
// 		}
		 
// 		$currencyElement = new Zend_Form_Element_Select('currency');
// 		$currencyElement->setAttribs(array('class'=>'demo-code-language'));
// 		$currencyElement->setMultiOptions($options);
// 		$this->addElement($currencyElement);
		$_arroption = array(1=>"Active",0=>"Deactive");
		$status = new Zend_Form_Element_Select("status");
		$status->setMultiOptions($_arroption);
		$this->addElement($status);
	
		$addressElement = new Zend_Form_Element_Textarea('txt_address');
		$addressElement->setAttribs(array('placeholder'=>"Enter Customer Address Here..."));
		$this->addElement($addressElement);
		
		$addremarkElement = new Zend_Form_Element_Textarea('txt_addremark');
		$addremarkElement->setAttribs(array('placeholder'=>"Enter Customer Address Here..."));
		$this->addElement($addremarkElement);
		
		$remarkElement = new Zend_Form_Element_Textarea('remark');
		$remarkElement->setAttribs(array('placeholder'=>"Description Here..."));
		$this->addElement($remarkElement);

		$balancelement = new Zend_Form_Element_Text('txt_balance');
		$balancelement->setAttribs(array('readonly'=>'readonly'));
		$balancelement->setValue("0.00");
		$this->addElement($balancelement);
		
		 
		if($data != null) {
	
			$idElement = new Zend_Form_Element_Hidden('id');
			$this->addElement($idElement);
			$idElement->setValue($data['customer_id']);
			$addressElement->setValue($data['add_name']);
			$nameElement->setValue($data['cust_name']);
			//$balancelement->setValue($data['bac']);
			$contactElement->setValue($data['contact_name']);
			$phoneElement->setValue($data['phone']);
			$faxElement->setValue($data['fax']);
			$emailElement->setValue($data['email']);
			$websiteElement->setValue($data['website']);
			$remarkElement->setValue($data['customer_remark']);
			$tpElement->setValue($data['type_price']);
			$status->setValue($data['is_active']);
// 			$paytermElement->setValue($data['PaymentTermsId']);
// 			$discountElement->setValue($data['discount']);
// 			$currencyElement->setValue($data['CurrencyId']);
		
			//$balancelement = new Zend_Form_Element_Text('txt_balance');
			//$this->addElement($balancelement);
	
		}
		 
		return $this;
	}
}