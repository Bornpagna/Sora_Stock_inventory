<?php

class Sales_Model_DbTable_Dbquoteapprov extends Zend_Db_Table_Abstract
{
	//use for add purchase order 29-13
	protected $_name="tb_quoatation";
	function getAllSaleOrder($search){
			$db= $this->getAdapter();
			$sql=" SELECT id,
			(SELECT name FROM `tb_sublocation` WHERE tb_sublocation.id = branch_id AND status=1 AND name!='' LIMIT 1) AS branch_name,
			(SELECT cust_name FROM `tb_customer` WHERE tb_customer.id=tb_quoatation.customer_id LIMIT 1 ) AS customer_name,
			(SELECT name FROM `tb_sale_agent` WHERE tb_sale_agent.id =tb_quoatation.saleagent_id  LIMIT 1 ) AS staff_name,
			quoat_number,date_order,
			(SELECT symbal FROM `tb_currency` WHERE id= currency_id limit 1) As curr_name,
			all_total,discount_value,net_total,
			(SELECT name_en FROM `tb_view` WHERE type=7 AND key_code=is_approved LIMIT 1),
			(SELECT name_en FROM `tb_view` WHERE type=8 AND key_code=pending_status LIMIT 1),
			(SELECT name_en FROM `tb_view` WHERE type=9 AND key_code=is_tosale LIMIT 1),
			(SELECT u.fullname FROM tb_acl_user AS u WHERE u.user_id = user_mod) AS user_name
			FROM `tb_quoatation` ";
			
			$from_date =(empty($search['start_date']))? '1': " date_order >= '".$search['start_date']." 00:00:00'";
			$to_date = (empty($search['end_date']))? '1': " date_order <= '".$search['end_date']." 23:59:59'";
			$where = " WHERE ".$from_date." AND ".$to_date;
			if(!empty($search['text_search'])){
				$s_where = array();
				$s_search = trim(addslashes($search['text_search']));
				$s_where[] = " quoat_number LIKE '%{$s_search}%'";
				$s_where[] = " net_total LIKE '%{$s_search}%'";
				$s_where[] = " balance LIKE '%{$s_search}%'";
				$where .=' AND ('.implode(' OR ',$s_where).')';
			}
			if($search['branch_id']>0){
				$where .= " AND branch_id = ".$search['branch_id'];
			}
			if($search['customer_id']>0){
				$where .= " AND customer_id =".$search['customer_id'];
			}
			$dbg = new Application_Model_DbTable_DbGlobal();
			$where.=$dbg->getAccessPermission();
			$order=" ORDER BY id DESC ";
			return $db->fetchAll($sql.$where.$order);
	}
	public function addQuoateOrderApproved($data)
	{
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$db_global = new Application_Model_DbTable_DbGlobal();
			$session_user=new Zend_Session_Namespace('auth');
			$userName=$session_user->user_name;
			$GetUserId= $session_user->user_id;
			$dbc=new Application_Model_DbTable_DbGlobal();
			$pending=2;
			if($data['approved_name']==2){$pending=1;}
			$arr=array(		
// 					'is_toinvocie'=>1,		
					'is_approved'	=> $data['approved_name'],
					'approved_userid'=> $GetUserId,
					'approval_note'	=> $data['app_remark'],
					'approved_date'	=> date("Y-m-d",strtotime($data['app_date'])),
					'pending_status'=>$pending,					
			);
			$this->_name="tb_quoatation";
			$where = " id = ".$data["id"];
			$sale_id = $this->update($arr, $where);
			unset($info_purchase_order);
			 
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			Application_Form_FrmMessage::message('INSERT_FAIL');
			$err =$e->getMessage();
			Application_Model_DbTable_DbUserLog::writeMessageError($err);
		}
	}
	function getProductSaleById($id){//5
		$db = $this->getAdapter();
		$sql=" SELECT
		s.id,
		(SELECT NAME FROM `tb_sublocation` WHERE id=s.branch_id LIMIT 1) AS branch_name,
		s.is_approved,s.quoat_number,s.discount_real,s.all_total,s.date_order,s.remark,s.approval_note,s.approved_date,
		(SELECT NAME FROM `tb_sale_agent` WHERE tb_sale_agent.id =s.saleagent_id  LIMIT 1 ) AS staff_name,
		(SELECT item_name FROM `tb_product` WHERE id= so.pro_id LIMIT 1) AS item_name,
		(SELECT item_code FROM `tb_product` WHERE id=so.pro_id LIMIT 1 ) AS item_code,
		(SELECT symbal FROM `tb_currency` WHERE id=s.currency_id LIMIT 1) AS curr_name,
		(SELECT cust_name FROM `tb_customer` WHERE tb_customer.id=s.customer_id LIMIT 1 ) AS customer_name,
		(SELECT phone FROM `tb_customer` WHERE tb_customer.id=s.customer_id LIMIT 1 ) AS phone,
		(SELECT contact_name FROM `tb_customer` WHERE tb_customer.id=s.customer_id LIMIT 1 ) AS contact_name,
		(SELECT email FROM `tb_customer` WHERE tb_customer.id=s.customer_id LIMIT 1 ) AS email,
		(SELECT address FROM `tb_customer` WHERE tb_customer.id=s.customer_id LIMIT 1 ) AS add_name,
		(SELECT u.fullname FROM tb_acl_user AS u WHERE u.user_id = s.user_mod LIMIT 1 ) AS user_name,
		(SELECT u.fullname FROM tb_acl_user AS u WHERE u.user_id = s.approved_userid LIMIT 1 ) AS approved_by,
		(SELECT name_en FROM `tb_view` WHERE TYPE=7 AND key_code=is_approved LIMIT 1) approval_status,
		(SELECT name_en FROM `tb_view` WHERE TYPE=8 AND key_code=pending_status LIMIT 1) processing,
		so.qty_order,so.price,so.old_price,so.sub_total,s.net_total
		
		FROM `tb_quoatation` AS s,
		`tb_quoatation_item` AS so WHERE s.id=so.quoat_id
		AND s.status=1 AND s.id = $id ";
		return $db->fetchAll($sql);
	} 
}