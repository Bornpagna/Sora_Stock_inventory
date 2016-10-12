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
		$db =$this->getAdapter();
		$db->beginTransaction();
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
		
		try{
		// photo image
			$adapter = new Zend_File_Transfer_Adapter_Http();
			$part= PUBLIC_PATH.'/images/stuffdocument/';
			
			$adapter->setDestination($part);
			$photo = $adapter->getFileInfo();
			$adapter->addFilter(new Zend_Filter_File_Rename( array('target' => $part."photo".$data["code"].".jpg", 'overwrite' => true)));
			$adapter->receive();
			if(!empty($photo['photo']['name'])){
				$data['photo']="photo".$data["code"].".jpg";
			}else{
				$data['photo']='';
			}
			
			// document image
			
			$document = $adapter->getFileInfo();
			$adapter->addFilter('Rename', array('target' => $part."document".$data["code"].".jpg", 'overwrite' => true));
			//$adapter->addFilter('Rename', "document".$data["code"].".jpg",$document);
			$adapter->receive();
			if(!empty($document['document']['name'])){
				$data['document']="document".$data["code"].".jpg";
			}else{
				$data['document']='';
			}
			
			//signature image
			
			
			$signature = $adapter->getFileInfo();
			$adapter->addFilter('Rename', array('target' => $part."signature".$data["code"].".jpg", 'overwrite' => true));
			//$adapter->addFilter('Rename', "signature".$data["code"].".jpg",$signature);
			$adapter->receive();
			if(!empty($signature['signature']['name'])){
				$data['signature']="signature".$data["code"].".jpg";
			}else{
				$data['signature']='';
			}
			$datainfo=array(
					"code"					=>	$data["code"],
					"name"		 			=>	$data['name'],
					"user_name"  			=>	$data['user_name'],
					"password"   			=> 	md5($data['password']),
					"phone"      			=>	$data['phone'],
					"email"      			=>	$data['email'],
					"address"    			=>	$data['address'],
					"pob"		 			=>	$data['pob'],
					"dob"		 			=>	$data['dob'],
					"job_title"  			=>	$data['job_title'],
					"branch_id"   			=>	$data['branch_id'],
					"user_type"				=>	$data["user_type"],
					"manage_by"				=>	$data["manage_by"],
					"bank_acc"				=>	$data["bank_acc"],
					"start_working_date"	=>	$data["start_working_date"],
					"refer_name"			=>	$data["refer_name"],
					"refer_phone"			=>	$data["refer_phone"],
					"refer_add"				=>	$data["refer_address"],
					"photo"					=>	$data["photo"],
					"document"				=>	$data['document'],
					"signature"				=>	$data['signature'],
					"description"			=>	$data['description'],	
					'user_id'				=>	$GetUserId,
					"date"					=>	date("Y-m-d")
			);
			if(!empty($data['id'])){
				$where=$this->getAdapter()->quoteInto('agent_id=?',$data['id']);
				$this->update($datainfo,$where);
			}else{
				$this->insert($datainfo);
			}
			
			$arr=array(
					"username"  		=>	$data['user_name'],
					"password"   		=> 	md5($data['password']),
					"email"      		=>	$data['email'],
					"LocationId"   		=>	$data['branch_id'],
					"user_type"			=>	$data["user_type"],
					"fullname"			=>	$data['name'],
					'created_date'		=>	date("Y-m-d"),
					"modified_date"		=>	date("Y-m-d"),
					"status"			=>	1
			);
			$this->_name = "tb_acl_user";
			if(!empty($data['id'])){
				$where=$this->getAdapter()->quoteInto('user_id=?',$data['id']);
				$this->update($arr,$where);
			}else{
				$this->insert($arr);
			}
		$db->commit();
		}catch (Exception $e){
			$db->rollBack();
			$err = $e->getMessage();
			Application_Model_DbTable_DbUserLog::writeMessageError($err);
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