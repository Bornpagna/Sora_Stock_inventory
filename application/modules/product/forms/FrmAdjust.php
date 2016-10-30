<?php 
class Product_Form_FrmAdjust extends Zend_Form
{
	public function init()
    {

	}
	function add(){
		$tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$db = new Product_Model_DbTable_DbAdjustStock();
		$pro_name =new Zend_Form_Element_Select("pro_name");
		$pro_name->setAttribs(array(
				'class'=>'form-control select2me',
				'onChange'=>'addNew();'
		));
		$opt= array(''=>$tr->translate("SELECT PRODUCT"));
		if(!empty($db->getProductName())){
			foreach ($db->getProductName() as $rs){
				$opt[$rs["id"]] = $rs["item_name"]." ".$rs["model"]." ".$rs["size"]." ".$rs["color"];
			}
		}
		
		$pro_name->setMultiOptions($opt);
		
		$this->addElements(array($pro_name));
		return $this;
	}
	
	function filter(){
		$tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$db = new Product_Model_DbTable_DbAdjustStock();
		$pro_name =new Zend_Form_Element_Text("ad_search");
		$pro_name->setAttribs(array(
				'class'=>'form-control',
		));
		
		$start_date = New Zend_Form_Element_Text("start_date");
		$start_date->setAttribs(array(
				'class'=>'form-control',
		));
		
		$end_date = New Zend_Form_Element_Text("end_date");
		$end_date->setAttribs(array(
				'class'=>'form-control',
		));
		
		$this->addElements(array($pro_name,$end_date,$start_date));
		return $this;
	}
	
	
}