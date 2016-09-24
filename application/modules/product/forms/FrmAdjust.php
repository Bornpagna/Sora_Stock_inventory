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
	/////////////	Form Product		/////////////////

	public function showItemForm($data=null) {
		$db=new Application_Model_DbTable_DbGlobal();
		$tr = Application_Form_FrmLanguages::getCurrentlanguage();
	
		$nameElement = new Zend_Form_Element_Text('name');
		$nameElement->setAttribs(array('class'=>'validate[required]',));
    	$this->addElement($nameElement);
    	
    	$codeElement = new Zend_Form_Element_Text('item_code');
    	$this->addElement($codeElement);
    	
    	$statusElement = new Zend_Form_Element_Select('status');
		$statusElement->setMultiOptions(array(1=>$tr->translate("ACTIVE"), 2=>$tr->translate("INACTIVE")));
    	$this->addElement($statusElement);
    	
    	$saleStartdateElement = new Zend_Form_Element_Text('sale_startdate');
    	$this->addElement($saleStartdateElement);
    	
    	$saleEnddateElement = new Zend_Form_Element_Text('sale_enddate');
    	$this->addElement($saleEnddateElement);

    	$rowsUnit = $db->getGlobalDb('SELECT id, name FROM rsmk_productunit');
    	$optionsUnit=array();
    	if($rowsUnit) {
    		foreach($rowsUnit as $readUnit) $optionsUnit[$readUnit['id']]=$readUnit['name'];
    	}
		$usageUnitElement = new Zend_Form_Element_Select('usage_unit');
		$usageUnitElement->setAttribs(array('class'=>'validate[required]',));
		$usageUnitElement->setMultiOptions($optionsUnit);
    	$this->addElement($usageUnitElement);
    	
    	$qtyPerUnitElement = new Zend_Form_Element_Text('qty_per_unit');
    	$qtyPerUnitElement->setAttribs(array('class'=>'validate[custom[number]]',));
    	$this->addElement($qtyPerUnitElement);
    	
    	$unitPriceElement = new Zend_Form_Element_Text('unit_price');
    	$unitPriceElement->setAttribs(array('class'=>'validate[custom[number]]',));
    	$this->addElement($unitPriceElement);
    	
    	$itemImageElement = new Zend_Form_Element_File('item_image');
    	if($data == null) {
			$itemImageElement->setAttribs(array('class'=>'validate[required]',));
    	}
    	$this->addElement($itemImageElement);
    	
		$descriptionElement = new Zend_Form_Element_Textarea('description');
    	$this->addElement($descriptionElement);
    	
    	// Select Element which get data from other Table
    	$rows = $db->getGlobalDb('SELECT id, name FROM rsmk_product');
    	$options=array();
    	if($rows) {
    		foreach($rows as $read) $options[$read['id']]=$read['name'];
    	}
		$productElement = new Zend_Form_Element_Select('product_id');
		$productElement->setMultiOptions($options);
    	$this->addElement($productElement);
    	
    	// Select Element which get data from table category
    	$rowsCategory = $db->getGlobalDb('SELECT CategoryId, Name FROM tb_category');
    	$optionsCategory=array();
    	if($rowsCategory) {
    		foreach($rowsCategory as $readCategory) $optionsCategory[$readCategory['CategoryId']]=$readCategory['Name'];
    	}
		$categoryElement = new Zend_Form_Element_Select('category');
		$categoryElement->setMultiOptions($optionsCategory);
    	$this->addElement($categoryElement);
    	
    	Application_Form_DateTimePicker::addDateField(array('sale_startdate', 'sale_enddate'));
    	//set value when edit
    	if($data != null) {
    		$idElement = new Zend_Form_Element_Hidden('pr');
    		$this->addElement($idElement);

    		$idElement->setValue($data['pro_id']);
    		$nameElement->setValue($data['name']);
    		$codeElement->setValue($data['item_code']);
    		$statusElement->setValue($data['status']);
    		$saleStartdateElement->setValue($data['sale_startdate']);
    		$saleEnddateElement->setValue($data['sale_enddate']);
    		$usageUnitElement->setValue($data['usage_unit']);
    		$qtyPerUnitElement->setValue($data['qty_per_unit']);
    		$unitPriceElement->setValue($data['unit_price']);
    		$itemImageElement->setValue($data['item_image']);
    		$descriptionElement->setValue($data['description']);
    		$productElement->setValue($data['product_id']);
    		$categoryElement->setValue($data['category_id']);
    		//$stockElement->setValue($data['stock_id']);
    	}
    	return $this;
	}
	/* @Desc: show form for change item qty,and qty demand in stock
	 * @param $data value of both form
	 * */
	public function showStockQtyItem($data=null) {
		$qtyStockElement = new Zend_Form_Element_Text('qty_stock');
		$qtyStockElement->setAttribs(array('class'=>'validate[required]',));
    	$this->addElement($qtyStockElement);
    	
    	$qtyDemandElement = new Zend_Form_Element_Text('qty_demand');
		$qtyDemandElement->setAttribs(array('class'=>'validate[required]',));
    	$this->addElement($qtyDemandElement);
		
		if($data != null) {
			$idElement->setValue($data['id']);
			
			$idElement = new Zend_Form_Element_Hidden('id');
    		$this->addElement($idElement);
    		
			$qtyStockElement->setValue($data['qty_stock']);
	    	$qtyDemandElement->setValue($data['qty_demand']);
		}
	}
	
	/**
	 * @Desc: show form category with add, edit
	 * @param $data
	 * */
	public function showCategoryForm($data=null) {
		$nameElement = new Zend_Form_Element_Text('name');
		$nameElement->setAttribs(array('class'=>'validate[required]',));
    	$this->addElement($nameElement);
    	
    	if($data != null) {
    		$idElement = new Zend_Form_Element_Hidden('id');
    		$this->addElement($idElement);

    		$idElement->setValue($data['id']);
    		$nameElement->setValue($data['name']);
    	}
    	return $this;
	}
	
}