<?php 
Class Purchase_Form_Frmexpense extends Zend_Form {
	protected $tr;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function FrmAddExpense($data=null){
		
		$title = new Zend_Form_Element_Text('title');
		$title->setAttribs(array(
				'class'=>' form-control',
				));
		
		$for_date = new Zend_Form_Element_Select('for_date');
		$for_date->setAttribs(array(
				'class'=>' form-control'
		));
		$options= array(1=>"1",2=>"2",3=>"3",4=>"4",5=>"5",6=>"6",7=>"7",8=>"8",9=>"9",10=>"10",11=>"11",12=>"12");
		$for_date->setMultiOptions($options);
		
		$_Date = new Zend_Form_Element_Text('Date');
		$_Date->setAttribs(array(
				'class'=>'date-picker form-control',
				'constraints'=>"{datePattern:'dd/MM/yyyy'}"
				
		));
		$_Date->setValue(date('Y-m-d'));
		
		$_branch_id = new Zend_Form_Element_Select('branch_id');
		$_branch_id->setAttribs(array(
				'class'=>' form-control',
				'onchange'=>'filterClient();'
		));
		
		$db = new Application_Model_DbTable_DbGlobal();
		$opt = $db->getAllLocation(1);
		$_branch_id->setMultiOptions($opt);
		
		$_stutas = new Zend_Form_Element_Select('Stutas');
		$_stutas ->setAttribs(array(
				'class'=>' form-control',			
		));
		$options= array(1=>"ប្រើប្រាស់",2=>"មិនប្រើប្រាស់");
		$_stutas->setMultiOptions($options);
		
		$_Description = new Zend_Form_Element_Textarea('Description');
		$_Description ->setAttribs(array(
				'class'=>' form-control',
				'rows'=>"3",
				//'cols'=>"80"
		));
		$total_amount=new Zend_Form_Element_Text('total_amount');
		$total_amount->setAttribs(array(
				'class'=>' form-control',
				//'onkeyup'=>'convertToDollar();',
		));
		
		$convert_to_dollar=new Zend_Form_Element_Text('convert_to_dollar');
		$convert_to_dollar->setAttribs(array(
				'class'=>' form-control',
				//'required'=>true
		));
		
		$invoice=new Zend_Form_Element_Text('invoice');
		$invoice->setAttribs(array(
				'class'=>' form-control',
		));
		
		$id = new Zend_Form_Element_Hidden("id");
		$_currency_type = new Zend_Form_Element_Select('currency_type');
		$_currency_type->setAttribs(array(
				'class'=>' form-control',
				//'onchange'=>'convertToDollar();',
		));
		$opt = $db->getAllCurrency(1);
		//$opt = $db->getViewById(8,1);
		$_currency_type->setMultiOptions($opt);
		
		if($data!=null){
			$_currency_type->setValue($data['curr_type']);
			//$_branch_id->setValue($data['branch_id']);
			$title->setValue($data['title']);
			$total_amount->setValue($data['total_amount']);
			//$convert_to_dollar->setValue($data['amount_in_dollar']);
			$for_date->setValue($data['for_date']);
			$_Description->setValue($data['desc']);
			$_Date->setValue($data['create_date']);
			$_stutas->setValue($data['status']);
			$invoice->setValue($data['invoice']);
			$id->setValue($data['id']);
		}
		
		$this->addElements(array($_branch_id,$invoice,$_currency_type,$title,$_Date ,$_stutas,$_Description,
				$total_amount,$convert_to_dollar,$for_date,$id,));
		return $this;
		
	}	
}