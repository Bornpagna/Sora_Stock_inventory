<?php 
class sales_Form_FrmStock extends Zend_Form
{
	public function init()
    {
    	
	}
	public function showSaleAgentForm($data=null, $stockID=null) {

		$db=new Application_Model_DbTable_DbGlobal();
	
		$nameElement = new Zend_Form_Element_Text('name');
		$nameElement->setAttribs(array('class'=>'validate[required]','placeholder'=>'Enter Agent Name'));
    	$this->addElement($nameElement);
    	
    	$phoneElement = new Zend_Form_Element_Text('phone');
    	$phoneElement->setAttribs(array('class'=>'validate[required]','placeholder'=>'Enter Phone Number'));
    	$this->addElement($phoneElement);
    	
    	$emailElement = new Zend_Form_Element_Text('email');
    	$emailElement->setAttribs(array('class'=>'validate[custom[email]]','placeholder'=>'Enter Email Address'));
    	$this->addElement($emailElement);
    	
    	$addressElement = new Zend_Form_Element_Text('address');
    	$addressElement->setAttribs(array('placeholder'=>'Enter Current Address'));
    	$this->addElement($addressElement);
    	
    	$jobTitleElement = new Zend_Form_Element_Text('job_title');
    	$jobTitleElement->setAttribs(array('placeholder'=>'Enter Position'));
    	$this->addElement($jobTitleElement);
    	
		$descriptionElement = new Zend_Form_Element_Textarea('description');
		$descriptionElement->setAttribs(array('placeholder'=>'Descrtion Here...'));
    	$this->addElement($descriptionElement);
    	
    	$rowsStock = $db->getGlobalDb('SELECT LocationId,Name FROM tb_sublocation WHERE Name!=""  ORDER BY LocationId DESC ');
    	$optionsStock = array('1'=>'Default Location','-1'=>'Add New Location');
    	if(count($rowsStock) > 0) {
    		foreach($rowsStock as $readStock) $optionsStock[$readStock['LocationId']]=$readStock['Name'];
    	}
    	$mainStockElement = new Zend_Form_Element_Select('main_stock_id');
    	$mainStockElement->setAttribs(array('OnChange'=>'AddLocation()','class'=>'demo-code-language'));
    	$mainStockElement->setMultiOptions($optionsStock);
    	$this->addElement($mainStockElement);
    	
    	//set value when edit
    	if($data != null) {
    		$idElement = new Zend_Form_Element_Hidden('id');
    	    $this->addElement($idElement);
    	    $idElement->setValue($data['agent_id']);
    		$nameElement->setValue($data['name']);
    		$phoneElement->setValue($data['phone']);
    		$emailElement->setValue($data['email']);
    		$addressElement->setValue($data['address']);
    		$jobTitleElement->setValue($data['job_title']);
    		$mainStockElement->setValue($data["stock_id"]);
    		$descriptionElement->setValue($data['description']);
    	}
    	return $this;
	}
}