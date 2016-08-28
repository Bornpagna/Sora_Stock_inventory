<?php

class sales_Model_DbTable_DbSalesAgent extends Zend_Db_Table_Abstract
{
	protected $_name = "tb_sale_agent";
	public function setName($name)
	{
		$this->_name=$name;
	}
	
	//for click add new sales agent
	public function addSalesAgent($data)
	{
		$db = $this->getAdapter();	
		$datainfo=array(
				"name"		 =>$data['name'],
				"phone"      =>$data['phone'],
				"email"      =>$data['email'],
				"address"    =>$data['address'],
				"job_title"  =>$data['job_title'],
				"stock_id"   =>$data['main_stock_id'],
				"description"=>$data['description'],	
		);
		$db->insert("tb_sale_agent", $datainfo);
	}
	public function updateSalesAgent($data){
		$sale_agent=array(
				"name"		 =>$data['name'],
				"phone"      =>$data['phone'],
				"email"      =>$data['email'],
				"address"    =>$data['address'],
				"job_title"  =>$data['job_title'],
				"stock_id"   =>$data['main_stock_id'],
				"description"=>$data['description'],
		);

		$where=$this->getAdapter()->quoteInto('agent_id=?',$data['id']);
		$this->update($sale_agent,$where);
	}
	public function addNewAgent($data){
		$db = new Application_Model_DbTable_DbGlobal();
		$datainfo=array(
				"name"		 =>$data['agent_name'],
				"phone"      =>$data['phone'],
				"job_title"  =>$data['position'],
				"stock_id"   =>$data['location'],
				"description"=>$data['desc'],
		);
		$agent_id=$db->addRecord($datainfo,"tb_sale_agent");
		return $agent_id; 
	}
}