<?php 
class Product_Form_FrmTransfer extends Zend_Form
{
	public function init()
    {

	}
	protected function GetuserInfo(){
		$user_info = new Application_Model_DbTable_DbGetUserInfo();
		$result = $user_info->getUserInfo();
		return $result;
	}
	
	public function transferItem($data=null) {
		$db=new Application_Model_DbTable_DbGlobal();
		// $tr = Application_Form_FrmLanguages::getCurrentlanguage();
	
		$invoiceElement = new Zend_Form_Element_Text('invoce_num');
		$invoiceElement->setAttribs(array('class'=>'validate[required]',));
    	$this->addElement($invoiceElement);
    	
    	
    	$date =new Zend_Date();
    	$transfer_dateElement = new Zend_Form_Element_Text('transfer_date');
    	$transfer_dateElement->setValue($date->get('YYYY-MM-dd'));
    	$transfer_dateElement->setAttribs(array('class'=>'validate[required]',));
    	$this->addElement($transfer_dateElement);
    	
    	$remark_element = new Zend_Form_Element_Textarea("remark_transfer");
    	$this->addElement($remark_element);
    	
    	$user= $this->GetuserInfo();
    	$options="";
    	$sql = "SELECT LocationId, Name FROM tb_sublocation WHERE Name!=''  ";
    	if($user["level"]==1 OR $user["level"]== 2){
    		$options=array("1"=>"Defaul Location","-1"=>"Add New Location");
    	}
    	else{
    		$sql.=" AND LocationId = ".$user["location_id"];
    	}
    	$sql.=" ORDER BY LocationId DESC";
    	$rs=$db->getGlobalDb($sql);
    	if(!empty($rs)) foreach($rs as $read) $options[$read['LocationId']]=$read['Name'];
    	$locationID = new Zend_Form_Element_Select('from_location');
    	$locationID ->setAttribs(array('class'=>'validate[required]'));
    	$locationID->setMultiOptions($options);
    	$locationID->setattribs(array(
    			'id'=>'from_location',
    			'Onchange'=>'AddLocation()',
    			));
    	$this->addElement($locationID);
    	////////////////////////////////////
    	$options="";
    	$sql = "SELECT LocationId, Name FROM tb_sublocation WHERE Name!='' ";
    	$sql.=" ORDER BY LocationId DESC";
    	$rs=$db->getGlobalDb($sql);
    	if(!empty($rs)) foreach($rs as $read) $options[$read['LocationId']]=$read['Name'];
    	$to_locationID = new Zend_Form_Element_Select('to_location');
    	$to_locationID ->setAttribs(array('class'=>'validate[required]'));
    	$to_locationID->setMultiOptions($options);
    	$to_locationID->setattribs(array(
    			'id'=>'to_location',));
    	$this->addElement($to_locationID);
    	
    	
    	Application_Form_DateTimePicker::addDateField(array('transfer_date',));
    	//set value when edit
    	if($data != null) {
    		$idElement = new Zend_Form_Element_Hidden('transfer_id');
    		$idElement->setValue($data["transfer_id"]);
    		$this->addElement($idElement);
    		
    		$fromElement = new Zend_Form_Element_Hidden('old_from_location');
    		$fromElement->setValue($data["from_location"]);
    		$this->addElement($fromElement);
    		
    		$toElement = new Zend_Form_Element_Hidden('old_to_location');
    		$toElement->setValue($data["to_location"]);
    		$this->addElement($toElement);
    		
    		$invoiceElement->setValue($data["invoice_num"]);
    		$invoiceElement->setAttribs(array("readonly"=>"readonly"));
    		$transfer_dateElement->setValue($data["transfer_date"]);
    		$remark_element->setValue($data["remark"]);
    		$locationID->setValue($data["from_location"]);
    		$to_locationID->setValue($data["to_location"]);
    		
    	}
    	return $this;
	}
}