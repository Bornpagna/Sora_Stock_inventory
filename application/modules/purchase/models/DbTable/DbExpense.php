<?php
class Purchase_Model_DbTable_DbExpense extends Zend_Db_Table_Abstract
{
	protected $_name = 'tb_income_expense';
	public function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;
	}
	function addexpense($data){
		$data = array(
				
				'title'=>$data['title'],
				'invoice'=>$data['invoice'],
				'branch_id'=>$data['branch_id'],
				'curr_type'=>$data['currency_type'],
				'total_amount'=>$data['total_amount'],
				'desc'=>$data['Description'],
				'for_date'=>$data['Date'],
				'status'=>$data['Stutas'],
				'user_id'=>$this->getUserId(),
				'create_date'=>date('Y-m-d'),
				
		);
		$this->insert($data);

 }
 function updateExpense($data){
	$arr = array(
				
				'title'=>$data['title'],
				'invoice'=>$data['invoice'],
				'curr_type'=>$data['currency_type'],
			    'branch_id'=>$data['branch_id'],
				'total_amount'=>$data['total_amount'],
				'desc'=>$data['Description'],
				'for_date'=>$data['Date'],
				'status'=>$data['Stutas'],
				'user_id'=>$this->getUserId(),
				'create_date'=>date('Y-m-d'),
				
		);
	$where=" id = ".$data['id'];
	$this->update($arr, $where);
}
function getexpensebyid($id){
	$db = $this->getAdapter();
	$sql=" SELECT * FROM $this->_name where id=$id ";
	return $db->fetchRow($sql);
}

function getAllExpense($search=null){
	$db = $this->getAdapter();
	$session_user=new Zend_Session_Namespace('auth');
	$from_date =(empty($search['start_date']))? '1': " create_date >= '".$search['start_date']." 00:00:00'";
	$to_date = (empty($search['end_date']))? '1': " create_date <= '".$search['end_date']." 23:59:59'";
	$where = " WHERE ".$from_date." AND ".$to_date;
	
	$sql=" SELECT id,
	(SELECT name FROM `tb_sublocation` WHERE id=branch_id) AS branch_name,
	invoice,title,
	(SELECT description FROM tb_currency WHERE tb_currency.id = curr_type LIMIT 1) as currency_type,
	total_amount,`desc`,for_date,(SELECT name_en FROM `tb_view` WHERE TYPE=5 AND key_code=status LIMIT 1) FROM $this->_name ";
	
	if (!empty($search['adv_search'])){
			$s_where = array();
			$s_search = trim(addslashes($search['adv_search']));
			$s_where[] = " title LIKE '%{$s_search}%'";
			$s_where[] = " total_amount LIKE '%{$s_search}%'";
			$s_where[] = " invoice LIKE '%{$s_search}%'";
			$where .=' AND ('.implode(' OR ',$s_where).')';
		}
		if($search['branch_id']>-1){
			$where.= " AND branch_id = ".$search['branch_id'];
		}
// 		if($search['currency_type']>-1){
// 			$where.= " AND curr_type = ".$search['currency_type'];
// 		}
       $order=" order by id desc ";
		return $db->fetchAll($sql.$where.$order);
}
function getAllExpenseReport($search=null){
	$db = $this->getAdapter();
	$session_user=new Zend_Session_Namespace('auth');
	$from_date =(empty($search['start_date']))? '1': " date >= '".$search['start_date']." 00:00:00'";
	$to_date = (empty($search['end_date']))? '1': " date <= '".$search['end_date']." 23:59:59'";
	$where = " WHERE ".$from_date." AND ".$to_date;

	$sql=" SELECT id,
	account_id,
	(SELECT symbol FROM `ln_currency` WHERE ln_currency.id =curr_type) AS currency_type,invoice,
	curr_type,
	total_amount,disc,date,status FROM $this->_name ";

	if (!empty($search['adv_search'])){
		$s_where = array();
		$s_search = trim(addslashes($search['adv_search']));
		$s_where[] = " account_id LIKE '%{$s_search}%'";
		$s_where[] = " title LIKE '%{$s_search}%'";
		$s_where[] = " total_amount LIKE '%{$s_search}%'";
		$s_where[] = " invoice LIKE '%{$s_search}%'";
		
		$where .=' AND ('.implode(' OR ',$s_where).')';
	}
	if($search['status']>-1){
		$where.= " AND status = ".$search['status'];
	}
	if($search['currency_type']>-1){
		$where.= " AND curr_type = ".$search['currency_type'];
	}
	$order=" order by id desc ";
	return $db->fetchAll($sql.$where.$order);
}



}