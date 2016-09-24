<?php 
class Sales_Form_FrmCustomer extends Zend_Form
{
	public function init()
    {	
	}
	/////////////	Form vendor		/////////////////
public function Formcustomer($data=null) {
		$db=new Application_Model_DbTable_DbGlobal();
		
		$nameElement = new Zend_Form_Element_Text('txt_name');
		$nameElement->setAttribs(array('class'=>'validate[required] form-control','placeholder'=>'Enter Name'));
    	$this->addElement($nameElement);
    	
    	
    	$rowsStock = $db->getGlobalDb('SELECT id,name FROM tb_sublocation WHERE name!=""  ORDER BY id DESC ');
    	$optionsStock = array('1'=>'Default Location','-1'=>'Add New Location');
    	if(count($rowsStock) > 0) {
    		foreach($rowsStock as $readStock) $optionsStock[$readStock['id']]=$readStock['name'];
    	}
    	$mainStockElement = new Zend_Form_Element_Select('branch_id');
    	$mainStockElement->setAttribs(array('OnChange'=>'AddLocation()','class'=>'form-control select2me'));
    	$mainStockElement->setMultiOptions($optionsStock);
    	$this->addElement($mainStockElement);
    	
    	$rows= $db->getGlobalDb('SELECT id,name FROM `tb_price_type` WHERE name!="" AND status=1');
    	$opt= array('1'=>'Default Location','-1'=>'Add New Location');
    	if(count($rows) > 0) {
    		foreach($rows as $readStock) $opt[$readStock['id']]=$readStock['name'];
    	}
    	$customerlevel = new Zend_Form_Element_Select('customer_level');
    	$customerlevel->setAttribs(array('OnChange'=>'AddLocation()','class'=>'form-control select2me'));
    	$customerlevel->setMultiOptions($opt);
    	$this->addElement($customerlevel);
    	 
    	
    	$contactElement = new Zend_Form_Element_Text('txt_contact_name');
    	$contactElement->setAttribs(array('placeholder'=>'Enter Contact Name',"class"=>"form-control"));
    	$this->addElement($contactElement);

    	$phoneElement = new Zend_Form_Element_Text('txt_phone');
    	$phoneElement->setAttribs(array('placeholder'=>'Enter Contact Number',"class"=>"form-control"));
    	$this->addElement($phoneElement);
    	
    	$contact_phone = new Zend_Form_Element_Text('contact_phone');
    	$contact_phone->setAttribs(array('placeholder'=>'Enter Contact Number',"class"=>"form-control"));
    	$this->addElement($contact_phone);
    	
    	$faxElement = new Zend_Form_Element_Text('txt_fax');
    	$faxElement->setAttribs(array('placeholder'=>'Enter Fax Number',"class"=>"form-control"));
    	$this->addElement($faxElement);
    	
    	$emailElement = new Zend_Form_Element_Text('txt_mail');
    	$emailElement->setAttribs(array('class'=>'validate[custom[email]] form-control','placeholder'=>'Enter Email Address'));
    	$this->addElement($emailElement);
    	
    	$websiteElement = new Zend_Form_Element_Text('txt_website');
    	$websiteElement->setAttribs(array('placeholder'=>'Enter Website Address',"class"=>"form-control"));
    	$this->addElement($websiteElement);
    	
    	///update 
    	$remarkElement = new Zend_Form_Element_Textarea('remark');
    	$remarkElement->setAttribs(array('placeholder'=>'Remark Here...',"class"=>"form-control","rows"=>3));
    	$this->addElement($remarkElement);
    	         
    	$addressElement = new Zend_Form_Element_Textarea('txt_address');
    	$addressElement->setAttribs(array('placeholder'=>'Enter Adress',"class"=>"form-control","rows"=>3));
    	$this->addElement($addressElement);
    	
    	$balancelement = new Zend_Form_Element_Text('txt_balance');
    	$balancelement->setValue("0.00");
    	$balancelement->setAttribs(array('readonly'=>'readonly',"class"=>"form-control"));
    	$this->addElement($balancelement); 		
    	
    	if($data != null) {
	       $idElement = new Zend_Form_Element_Hidden('id');
   		   $this->addElement($idElement);
    	   $idElement->setValue($data['vendor_id']);
    		
    	   $nameElement->setValue($data['v_name']);
    		$contactElement->setValue($data['contact_name']);
    		$addressElement->setValue($data["add_name"]);
    		$faxElement->setValue($data['fax']);
    		$emailElement->setValue($data['email']);
    		$websiteElement->setValue($data['website']);
    		$remarkElement->setValue($data['note']);
    		$contact_phone->setValue($data['phone_person']);
    		$balancelement->setValue($data['balance']);
    	}
    	return $this;
	}
}