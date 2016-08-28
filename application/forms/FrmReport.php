<?php

class Application_Form_FrmReport extends Zend_Form
{
    public function init()
    {
   
    }
    public function GetuserInfo(){
    	$user_info = new Application_Model_DbTable_DbGetUserInfo();
    	$result = $user_info->getUserInfo();
    	return $result;
    }
    public function salseReport($data=null)
    {
    	$db=new Application_Model_DbTable_DbGlobal();
    	$request = Zend_Controller_Front::getInstance()->getRequest();
    	$tr = Application_Form_FrmLanguages::getCurrentlanguage();
//     	$itemElement = new Zend_Form_Element_Text("item");
//     	$itemvalue = $request->getParam("item");
//     	$itemElement->setValue($itemvalue);
//     	$itemElement->setAttribs(array('placeholder'=>'Item Name,Code...'));
		
    	
    	$rs=$db->getGlobalDb('SELECT pro_id, item_name,item_code FROM tb_product WHERE item_name!="" ORDER BY item_name ');
    	$options=array(''=>$tr->translate('Select_Products'));
    	$proValue = $request->getParam('item');
    	foreach($rs as $read) $options[$read['pro_id']]=$read['item_code']." ".$read['item_name'];
    	$pro_id=new Zend_Form_Element_Select('item');
    	$pro_id->setMultiOptions($options);
    	$pro_id->setAttribs(array(
    			'id'=>'item',
    	//		'onchange'=>'this.form.submit()'
    	));
    	$pro_id->setValue($proValue);
    	$this->addElement($pro_id);
    	
    	$sql='SELECT DISTINCT Name,LocationId FROM tb_sublocation WHERE Name!="" AND status=1 ';
    	$user = $this->GetuserInfo();
    	if($user["level"]!=1 AND $user["level"]!=2){
    		$sql .= " AND LocationId= ".$user["location_id"];
    			
    	}
    	$rs=$db->getGlobalDb($sql);
    	$options=array(''=>$tr->translate('Please_Select_Location'));
    	$locationValue = $request->getParam('LocationId');
    	foreach($rs as $read) $options[$read['LocationId']]=$read['Name'];
    	$location_id=new Zend_Form_Element_Select('LocationId');
    	$location_id->setMultiOptions($options);
    	$location_id->setAttribs(array(
    			'id'=>'LocationId',
    			'onchange'=>'getProductFilter()',
    	));
    	$location_id->setValue($locationValue);
    	
    	$rs=$db->getGlobalDb('SELECT CategoryId, Name FROM tb_category WHERE Name!="" ');
    	$options=array(''=>$tr->translate('Please_Select'));
    	$cateValue = $request->getParam('category_id');
    	foreach($rs as $read) $options[$read['CategoryId']]=$read['Name'];
    	$cate_element=new Zend_Form_Element_Select('category_id');
    	$cate_element->setMultiOptions($options);
    	$cate_element->setAttribs(array(
    			'id'=>'category_id',
    			'onchange'=>'getProductFilter()',
    	));
    	$cate_element->setValue($cateValue);
    	$this->addElement($cate_element);
    	 
    	$rs=$db->getGlobalDb('SELECT branch_id, Name FROM tb_branch WHERE Name!="" ORDER BY Name');
    	$options=array(''=>$tr->translate('Please_Select'));
    	$branchValue = $request->getParam('branch_id');
    	foreach($rs as $read) $options[$read['branch_id']]=$read['Name'];
    	$branch_element=new Zend_Form_Element_Select('branch_id');
    	$branch_element->setMultiOptions($options);
    	$branch_element->setAttribs(array(
    			'id'=>'branch_id',
    			'onchange'=>'getProductFilter();',
    	));
    	$branch_element->setValue($branchValue);
    	$this->addElement($branch_element);
    	
    	$date = new Zend_Date();
    	$startDate = new Zend_Form_Element_Text("start_date");
    	$startDatevalue = $request->getParam("start_date");
    	//$startDate->setAttribs(array("class"=>"validate[required]"));
    	$startDate->setValue($startDatevalue);
    	
    	$endDate = new Zend_Form_Element_Text("end_date");
    	//$endDate->setValue($date->get("DD-MM-YY"));
    	$endDatevalue = $request->getParam("end_date");
    	//$endDate->setAttribs(array("class"=>"validate[required]"));
    	$endDate->setValue($endDatevalue);

    	$this->addElements(array($startDate,$endDate,$location_id));
    	Application_Form_DateTimePicker::addDateField(array('start_date','end_date'));
     	return $this;
    
    }
    
    
    public function productDetailReport($data=null)
    {
    	$db=new Application_Model_DbTable_DbGlobal();
    	$request = Zend_Controller_Front::getInstance()->getRequest();
    	$tr = Application_Form_FrmLanguages::getCurrentlanguage();
    	$location = $data["LocationId"];
    	$brand= $data["branch_id"];
    	$category = $data["category_id"];
     	$item = new report_Model_DbQuery();
     	
    	$sql="SELECT p.pro_id, p.item_name,p.item_code FROM tb_product AS p ";
			
    		if($data["LocationId"]!="" OR $data["LocationId"]!=0){
    			$sql.= " INNER JOIN tb_prolocation AS pl ON pl.pro_id = p.pro_id AND pl.`LocationId`= ".trim($data["LocationId"]);
    		}
    		$sql.=" WHERE p.item_name!=''";
    		
			if(@$data["branch_id"]!="" OR @$data["branch_id"]!=0){
				$sql.= " AND p.`brand_id`=".trim($data["branch_id"]);
			}
			if(@$data["category_id"]!="" OR @$data["category_id"]!=0){
				$sql.=" AND p.`cate_id`=".trim($data["category_id"]);
			}
			$sql.=" ORDER BY p.item_name" ;
		//echo $sql;//exit();
		$rs=$db->getGlobalDb($sql);
		if($rs){
    	$options=array(''=>$tr->translate('Select_Products'));
    	foreach($rs as $read) $options[$read['pro_id']]=$read['item_code']." ".$read['item_name'];
		}else{
			$options=array(''=>'No Items Results');
		}
    	$pro_id=new Zend_Form_Element_Select('item');
    	$pro_id->setMultiOptions($options);
    	$proValue = $request->getParam('item');
    	$pro_id->setAttribs(array(
    			'id'=>'item',
    			//'onchange'=>'getItemById()'
    	));
    	$pro_id->setValue($proValue);
    	$this->addElement($pro_id);
    	
    	$reportTypeElement = new Zend_Form_Element_Select("report_type");
    	$opt = array(""=>$tr->translate('REPORT_TYPE'),1=>"All Report",2=>"Purchase Report",3=>"Sales Report",4=>"Transfer Report",5=>"Poduct Information Report",6=>"Customize Report");
    	$reportTypeElement->setMultiOptions($opt);
    	$reportTypeElement->setAttribs(array('onChange'=>'report();getTransfer();'));
    	$reportTypeValue = $request->getParam('report_type');
    	$reportTypeElement->setValue($reportTypeValue);
    	$this->addElement($reportTypeElement);
    	
    	$check = new Zend_Form_Element_MultiCheckbox("report_num");
    	$check->setAttribs(array("class"=>"validate[required]"));
    	$opt_check = array(1=>'Purchase Report',2=>'Sales Report',3=>'Transfer Report',4=>'Poduct Information Report');
    	$check->setMultiOptions($opt_check);
    	$check_value = $request->getParam("report_num");
    	$check->setValue($check_value);
    	$this->addElement($check);
    	 
    	$sql='SELECT DISTINCT Name,LocationId FROM tb_sublocation WHERE Name!="" AND status=1 ';
    	$user = $this->GetuserInfo();
    	if($user["level"]!=1 AND $user["level"]!=2){
    		$sql .= " AND LocationId= ".$user["location_id"];
    		 
    	}
    	$rs=$db->getGlobalDb($sql);
    	$options=array(''=>$tr->translate('Please_Select_Location'));
    	$locationValue = $request->getParam('LocationId');
    	foreach($rs as $read) $options[$read['LocationId']]=$read['Name'];
    	$location_id=new Zend_Form_Element_Select('LocationId');
    	$location_id->setMultiOptions($options);
    	$location_id->setAttribs(array(
    			'id'=>'LocationId',
    			'onchange'=>'getProductFilter();',
    	));
    	$location_id->setValue($locationValue);
    	
//     	$sql='SELECT DISTINCT Name,LocationId FROM tb_sublocation WHERE Name!="" AND status=1 ';
//     	$user = $this->GetuserInfo();
//     	if($user["level"]!=1 AND $user["level"]!=2){
//     		$sql .= " AND LocationId= ".$user["location_id"];
    		 
//     	}
//     	$rs=$db->getGlobalDb($sql);
    	$options=array(''=>$tr->translate('Please_Select_Location'));
    	$locationValues = $request->getParam('to_LocationId');
    	foreach($rs as $read) $options[$read['LocationId']]=$read['Name'];
    	$to_location_id=new Zend_Form_Element_Select('to_LocationId');
    	$to_location_id->setMultiOptions($options);
    	$to_location_id->setAttribs(array(
    			'id'=>'to_LocationId',
    			//'onchange'=>'getTransfer()'
    	));
    	$to_location_id->setValue($locationValues);
    	$this->addElement($to_location_id);
    	 
    	$rs=$db->getGlobalDb('SELECT CategoryId, Name FROM tb_category WHERE Name!="" ORDER BY CategoryId');
    	$options=array(''=>$tr->translate('Please_Select'));
    	$cateValue = $request->getParam('category_id');
    	foreach($rs as $read) $options[$read['CategoryId']]=$read['Name'];
    	$cate_element=new Zend_Form_Element_Select('category_id');
    	$cate_element->setMultiOptions($options);
    	$cate_element->setAttribs(array(
    			'id'=>'category_id',
    			'onchange'=>'getProductFilter()',
    	));
    	$cate_element->setValue($cateValue);
    	$this->addElement($cate_element);
    
    	$rs=$db->getGlobalDb('SELECT branch_id, Name FROM tb_branch WHERE Name!="" ORDER BY branch_id ');
    	$options=array(''=>$tr->translate('Please_Select'));
    	$branchValue = $request->getParam('branch_id');
    	foreach($rs as $read) $options[$read['branch_id']]=$read['Name'];
    	$branch_element=new Zend_Form_Element_Select('branch_id');
    	$branch_element->setMultiOptions($options);
    	$branch_element->setAttribs(array(
    			'id'=>'branch_id',
    			'onchange'=>'getProductFilter()',
    	));
    	$branch_element->setValue($branchValue);
    	$this->addElement($branch_element);
    	 
    	$date = new Zend_Date();
    	$startDate = new Zend_Form_Element_Text("start_date");//echo date("Y-m-d");
    	$startDatevalue = $request->getParam("start_date");
    	//$startDate->setAttribs(array("class"=>"validate[required]"));
    	$startDate->setValue($startDatevalue);
    	 
    	$endDate = new Zend_Form_Element_Text("end_date");
    	//$endDate->setValue($date->get("DD-MM-YY"));
    	$endDatevalue = $request->getParam("end_date");
    	//$endDate->setAttribs(array("class"=>"validate[required]"));
    	$endDate->setValue($endDatevalue);
    
    	$this->addElements(array($startDate,$endDate,$location_id));
    	Application_Form_DateTimePicker::addDateField(array('start_date','end_date'));
    	return $this;
    
    }
	
}

