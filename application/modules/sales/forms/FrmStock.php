<?php 
class Sales_Form_FrmStock extends Zend_Form
{
	public function init()
    {
    	
	}
	public function showSaleAgentForm($data=null, $stockID=null) {

		$db=new Application_Model_DbTable_DbGlobal();
	
		$nameElement = new Zend_Form_Element_Text('name');
		$nameElement->setAttribs(array('class'=>'validate[required] form-control','placeholder'=>'Enter Agent Name'));
    	$this->addElement($nameElement);
    	
    	$phoneElement = new Zend_Form_Element_Text('phone');
    	$phoneElement->setAttribs(array('class'=>'validate[required] form-control','placeholder'=>'Enter Phone Number'));
    	$this->addElement($phoneElement);
    	
    	$emailElement = new Zend_Form_Element_Text('email');
    	$emailElement->setAttribs(array('class'=>'validate[custom[email]] form-control','placeholder'=>'Enter Email Address'));
    	$this->addElement($emailElement);
    	
    	$addressElement = new Zend_Form_Element_Text('address');
    	$addressElement->setAttribs(array('placeholder'=>'Enter Current Address',"class"=>"form-control"));
    	$this->addElement($addressElement);
    	
    	$jobTitleElement = new Zend_Form_Element_Text('job_title');
    	$jobTitleElement->setAttribs(array('placeholder'=>'Enter Position',"class"=>"form-control"));
    	$this->addElement($jobTitleElement);
    	
		$descriptionElement = new Zend_Form_Element_Textarea('description');
		$descriptionElement->setAttribs(array('placeholder'=>'Descrtion Here...',"class"=>"form-control","rows"=>3));
    	$this->addElement($descriptionElement);
    	
    	$rowsStock = $db->getGlobalDb('SELECT id,name FROM tb_sublocation WHERE name!=""  ORDER BY id DESC ');
    	$optionsStock = array('1'=>'Default Location','-1'=>'Add New Location');
    	if(count($rowsStock) > 0) {
    		foreach($rowsStock as $readStock) $optionsStock[$readStock['id']]=$readStock['name'];
    	}
    	$mainStockElement = new Zend_Form_Element_Select('branch_id');
    	$mainStockElement->setAttribs(array('OnChange'=>'AddLocation()','class'=>'form-control select2me'));
    	$mainStockElement->setMultiOptions($optionsStock);
    	$this->addElement($mainStockElement);
    	
    	$user_name = new Zend_Form_Element_Text('user_name');
    	$user_name->setAttribs(array('placeholder'=>'Enter Position',"class"=>"form-control"));
    	$this->addElement($user_name);
    	
    	$password = new Zend_Form_Element_Password('password');
    	$password->setAttribs(array('placeholder'=>'Enter Position',"class"=>"form-control"));
    	$this->addElement($password);
    	
    	$pob= new Zend_Form_Element_Text('pob');
    	$pob->setAttribs(array('placeholder'=>'Enter Position',"class"=>"form-control"));
    	$this->addElement($pob);
    	
    	$dob= new Zend_Form_Element_Text('dob');
    	$dob->setAttribs(array('placeholder'=>'Enter Position',"class"=>"form-control"));
    	$this->addElement($dob);
    	
    	//set value when edit
    	if($data != null) {
    		$idElement = new Zend_Form_Element_Hidden('id');
    	    $this->addElement($idElement);
    	    $idElement->setValue($data['id']);
    		$nameElement->setValue($data['name']);
    		$phoneElement->setValue($data['phone']);
    		$emailElement->setValue($data['email']);
    		$addressElement->setValue($data['address']);
    		$jobTitleElement->setValue($data['job_title']);
    		$mainStockElement->setValue($data["branch_id"]);
    		$descriptionElement->setValue($data['description']);
    	}
    	return $this;
	}
}