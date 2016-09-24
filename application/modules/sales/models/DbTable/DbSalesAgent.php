<?php

class Sales_Model_DbTable_DbSalesAgent extends Zend_Db_Table_Abstract
{
	protected $_name = "tb_sale_agent";
	function getAllSaleAgent($search){
		$sql = "SELECT sg.id,l.name AS branch_name, sg.name, sg.phone, sg.email, sg.address, sg.job_title, sg.description
		FROM tb_sale_agent AS sg
		INNER JOIN tb_sublocation As l ON sg.branch_id = l.id WHERE 1 ";
		$order=" ORDER BY sg.id DESC ";
		
		$from_date =(empty($search['start_date']))? '1': " date >= '".$search['start_date']." 00:00:00'";
		$to_date = (empty($search['end_date']))? '1': " date <= '".$search['end_date']." 23:59:59'";
		$where = " AND ".$from_date." AND ".$to_date;
		if(!empty($search['text_search'])){
			$s_where = array();
			$s_search = trim(addslashes($search['text_search']));
			$s_where[] = " l.name LIKE '%{$s_search}%'";
			$s_where[] = " sg.name LIKE '%{$s_search}%'";
			$s_where[] = " sg.phone LIKE '%{$s_search}%'";
			$s_where[] = " sg.email LIKE '%{$s_search}%'";
			$s_where[] = " sg.address LIKE '%{$s_search}%'";
			$s_where[] = " sg.job_title LIKE '%{$s_search}%'";
			$s_where[] = " sg.description LIKE '%{$s_search}%'";
			$where .=' AND ('.implode(' OR ',$s_where).')';
		}
		if($search['branch_id']>0){
			$where .= " AND branch_id = ".$search['branch_id'];
		}
		$order=" ORDER BY id DESC ";
		$db =$this->getAdapter();
		return $db->fetchAll($sql.$where.$order);
	}
	public function addSalesAgent($data)
	{
		$session_user=new Zend_Session_Namespace('auth');
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
		$datainfo=array(
				"name"		 =>$data['name'],
				"user_name"  =>$data['user_name'],
				"password"   => md5($data['password']),
				"phone"      =>$data['phone'],
				"email"      =>$data['email'],
				"address"    =>$data['address'],
				"pob"		 =>$data['pob'],
				"dob"		 =>$data['dob'],
				"job_title"  =>$data['job_title'],
				"branch_id"   =>$data['branch_id'],
				"description"=>$data['description'],	
				'user_id'=>$GetUserId,
				"date"=>date("Y-m-d")
		);
		if(!empty($data['id'])){
			$where=$this->getAdapter()->quoteInto('agent_id=?',$data['id']);
			$this->update($datainfo,$where);
		}else{
			$this->insert($datainfo);
		}
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