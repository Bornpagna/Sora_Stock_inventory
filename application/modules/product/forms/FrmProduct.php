<?php 
class Product_Form_FrmProduct extends Zend_Form
{
	public function init()
    {
    	$tr = Application_Form_FrmLanguages::getCurrentlanguage();
    	$request=Zend_Controller_Front::getInstance()->getRequest();
	}
	/////////////	Form Product		/////////////////
	public function add($data=null){
		$tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$db = new Product_Model_DbTable_DbProduct();
		$p_code = $db->getProductCode();
		$name = new Zend_Form_Element_Text("name");
		$name->setAttribs(array(
				'class'=>'form-control',
				'required'=>'required'
		));
		
		$pro_code = new Zend_Form_Element_Text("pro_code");
		$pro_code->setAttribs(array(
				'class'=>'form-control',
				//'required'=>'required'
		));
		$pro_code->setValue($p_code);
		 
		$serial = new Zend_Form_Element_Text("serial");
		$serial->setAttribs(array(
				'class'=>'form-control',
				//'required'=>'required'
		));
		 
		$barcode = new Zend_Form_Element_Text("barcode");
		$barcode->setAttribs(array(
				'class'=>'form-control',
				//'required'=>'required'
		));
		 
		$opt = array(''=>$tr->translate("SELECT_BRAND"),-1=>$tr->translate("ADD_NEW_BRAND"));
		$brand = new Zend_Form_Element_Select("brand");
		$brand->setAttribs(array(
				'class'=>'form-control select2me',
				'onChange'=>'getPopupBrand();',
				//'required'=>'required'
		));
		if(!empty($db->getBrand())){
			foreach ($db->getBrand() as $rs){
				$opt[$rs["id"]] = $rs["name"];
			}
		}
		$brand->setMultiOptions($opt);
		 
		$opt = array(''=>$tr->translate("SELECT_MODEL"),-1=>$tr->translate("ADD_NEW_MODEL"));
		$model = new Zend_Form_Element_Select("model");
		$model->setAttribs(array(
				'class'=>'form-control select2me',
				'onChange'=>'getPopupModel()',
				//'required'=>'required'
		));
		if(!empty($db->getModel())){
			foreach ($db->getModel() as $rs){
				$opt[$rs["key_code"]] = $rs["name"];
			}
		}
		$model->setMultiOptions($opt);
		 
		$opt = array(''=>$tr->translate("SELECT_CATEGORY"),-1=>$tr->translate("ADD_NEW_CATEGORY"));
		$category = new Zend_Form_Element_Select("category");
		$category->setAttribs(array(
				'class'=>'form-control select2me',
				'onChange'=>'getPopupCategory()',
				//'required'=>'required'
		));
		if(!empty($db->getCategory())){
			foreach ($db->getCategory() as $rs){
				$opt[$rs["id"]] = $rs["name"];
			}
		}
		$category->setMultiOptions($opt);
		
		$opt = array(''=>$tr->translate("SELECT_COLOR"),-1=>$tr->translate("ADD_NEW_COLOR"));
		$color = new Zend_Form_Element_Select("color");
		$color->setAttribs(array(
				'class'=>'form-control select2me',
				'onChange'=>'getPopupColor()',
				//'required'=>'required'
		));
		if(!empty($db->getColor())){
			foreach ($db->getColor() as $rs){
				$opt[$rs["key_code"]] = $rs["name"];
			}
		}
		$color->setMultiOptions($opt);
		 
		$opt = array(''=>$tr->translate("SELECT_SIZE"),-1=>$tr->translate("ADD_NEW_SIZE"));
		$size = new Zend_Form_Element_Select("size");
		$size->setAttribs(array(
				'class'=>'form-control select2me',
				'onChange'=>'getPopupSize()',
				//'required'=>'required'
		));
		if(!empty($db->getSize())){
			foreach ($db->getSize() as $rs){
				$opt[$rs["key_code"]] = $rs["name"];
			}
		}
		$size->setMultiOptions($opt);
		 
		$unit = new Zend_Form_Element_Text("unit");
		$unit->setAttribs(array(
				'class'=>'form-control',
				//'required'=>'required'
		));
		$unit->setValue(1);
		 
		$qty_per_unit = new Zend_Form_Element_Text("qty_unit");
		$qty_per_unit->setAttribs(array(
				'class'=>'form-control',
				//'required'=>'required'
		));
		 
		$opt = array(''=>$tr->translate("SELECT_MEASURE"),-1=>$tr->translate("ADD_NEW_MEASURE"));
		$measure = new Zend_Form_Element_Select("measure");
		$measure->setAttribs(array(
				'class'=>'form-control select2me',
				//'required'=>'required',
				'Onchange'	=>	'getMeasureLabel();getPopupMeasure();'
		));
		if(!empty($db->getMeasure())){
			foreach ($db->getMeasure() as $rs){
				$opt[$rs["id"]] = $rs["name"];
			}
		}
		$measure->setMultiOptions($opt);
		 
		$label = new Zend_Form_Element_Text("label");
		$label->setAttribs(array(
				'class'=>'form-control',
				//'required'=>'required'
		));
		 
		$description = new Zend_Form_Element_Text("description");
		$description->setAttribs(array(
				'class'=>'form-control',
				//'required'=>'required'
		));
		
		$status = new Zend_Form_Element_Select("status");
		$opt = array('1'=>$tr->translate("ACTIVE"),'2'=>$tr->translate("DEACTIVE"));
		$status->setAttribs(array(
				'class'=>'form-control select2me',
				'required'=>'required',
				//'Onchange'	=>	'getMeasureLabel()'
		));
		$status->setMultiOptions($opt);
		
		$branch = new Zend_Form_Element_Select("branch");
		$opt = array(''=>$tr->translate("SELECT_BRANCH"));
		if(!empty($db->getBranch())){
			foreach ($db->getBranch() as $rs){
				$opt[$rs["id"]] = $rs["name"];
			}
		}
		$branch->setAttribs(array(
				'class'=>'form-control select2me',
				//'required'=>'required',
				'Onchange'	=>	'addNewProLocation()'
		));
		$branch->setMultiOptions($opt);
		
		$price_type = new Zend_Form_Element_Select("price_type");
		$opt = array();
		if(!empty($db->getPriceType())){
			foreach ($db->getPriceType() as $rs){
				$opt[$rs["id"]] = $rs["name"];
			}
		}
		$price_type->setAttribs(array(
				'class'=>'form-control select2me',
				//'required'=>'required',
				'Onchange'	=>	'addNewPriceType()'
		));
		$price_type->setMultiOptions($opt);
		
		if($data!=null){
			$name->setValue($data["item_name"]);
			$pro_code->setValue($data["item_code"]);
			$barcode->setValue($data["barcode"]);
			$serial->setValue($data["serial_number"]);
			$brand->setValue($data["brand_id"]);
			$category->setValue($data["cate_id"]);
			$model->setValue($data["model_id"]);
			$color->setValue($data["color_id"]);
			$size->setValue($data["size_id"]);
			$measure->setValue($data["measure_id"]);
			$label->setValue($data["unit_label"]);
			$description->setValue($data["note"]);
			$qty_per_unit->setValue($data["qty_perunit"]);
			$status->setValue($data["status"]);
		}
		
		$this->addElements(array($price_type,$branch,$status,$pro_code,$name,$serial,$brand,$model,$barcode,$category,$size,$color,$measure,$qty_per_unit,$unit,$label,$description));
		return $this;
	}
	function productFilter(){
		$tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$db = new Product_Model_DbTable_DbProduct();
		$ad_search = new Zend_Form_Element_Text("ad_search");
		$ad_search->setAttribs(array(
				'class'=>'form-control',
		));
		$ad_search->setValue($request->getParam("ad_search"));
		
		$branch = new Zend_Form_Element_Select("branch");
		$opt = array(''=>$tr->translate("SELECT_BRANCH"));
		if(!empty($db->getBranch())){
			foreach ($db->getBranch() as $rs){
				$opt[$rs["id"]] = $rs["name"];
			}
		}
		$branch->setAttribs(array(
				'class'=>'form-control select2me',
		));
		$branch->setMultiOptions($opt);
		$branch->setValue($request->getParam("branch"));
		
		$status = new Zend_Form_Element_Select("status");
		$opt = array('1'=>$tr->translate("ACTIVE"),'2'=>$tr->translate("DEACTIVE"));
		$status->setAttribs(array(
				'class'=>'form-control select2me',
		));
		$status->setMultiOptions($opt);
		$status->setValue($request->getParam("status"));
		
		$opt = array(''=>$tr->translate("SELECT_BRAND"));
		$brand = new Zend_Form_Element_Select("brand");
		$brand->setAttribs(array(
				'class'=>'form-control select2me',
		));
		if(!empty($db->getBrand())){
			foreach ($db->getBrand() as $rs){
				$opt[$rs["id"]] = $rs["name"];
			}
		}
		$brand->setMultiOptions($opt);
		$brand->setValue($request->getParam("brand"));
			
		$opt = array(''=>$tr->translate("SELECT_MODEL"));
		$model = new Zend_Form_Element_Select("model");
		$model->setAttribs(array(
				'class'=>'form-control select2me',
		));
		if(!empty($db->getModel())){
			foreach ($db->getModel() as $rss){
				$opt[$rss["key_code"]] = $rss["name"];
			}
		}
		$model->setMultiOptions($opt);
		$model->setValue($request->getParam("model"));
			
		$opt = array(''=>$tr->translate("SELECT_CATEGORY"));
		$category = new Zend_Form_Element_Select("category");
		$category->setAttribs(array(
				'class'=>'form-control select2me',
		));
		if(!empty($db->getCategory())){
			foreach ($db->getCategory() as $rs){
				$opt[$rs["id"]] = $rs["name"];
			}
		}
		$category->setMultiOptions($opt);
		$category->setValue($request->getParam("category"));
		
		$opt = array(''=>$tr->translate("SELECT_COLOR"));
		$color = new Zend_Form_Element_Select("color");
		$color->setAttribs(array(
				'class'=>'form-control select2me',
		));
		if(!empty($db->getColor())){
			foreach ($db->getColor() as $rs){
				$opt[$rs["key_code"]] = $rs["name"];
			}
		}
		$color->setMultiOptions($opt);
		$color->setValue($request->getParam("color"));
			
		$opt = array(''=>$tr->translate("SELECT_SIZE"));
		$size = new Zend_Form_Element_Select("size");
		$size->setAttribs(array(
				'class'=>'form-control select2me',
		));
		if(!empty($db->getSize())){
			foreach ($db->getSize() as $rs){
				$opt[$rs["key_code"]] = $rs["name"];
			}
		}
		$size->setMultiOptions($opt);
		$size->setValue($request->getParam("size"));
		
		$this->addElements(array($ad_search,$branch,$brand,$model,$category,$color,$size,$status));
		return $this;
	}
}