<?php

class Sales_Model_DbTable_Dbquoatation extends Zend_Db_Table_Abstract
{
	//use for add purchase order 29-13
	
	function getAllQuoatation($search){
			$db= $this->getAdapter();
			$sql=" SELECT id,
			quoat_number,date_order,date_in,branch_id,
			(SELECT cust_name FROM `tb_customer` WHERE tb_customer.id=tb_quoatation.customer_id LIMIT 1 ) AS customer_name,
			(SELECT symbal FROM `tb_currency` WHERE id= currency_id limit 1) As curr_name,
			net_total,paid,balance,
			(SELECT u.username FROM tb_acl_user AS u WHERE u.user_id = user_mod) AS user_name
			FROM `tb_quoatation` ";
			$order=" ORDER BY id DESC";
			return $db->fetchAll($sql.$order);
	}
	public function addQuoatationOrder($data)
	{
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$db_global = new Application_Model_DbTable_DbGlobal();
			$session_user=new Zend_Session_Namespace('auth');
			$userName=$session_user->user_name;
			$GetUserId= $session_user->user_id;
// 			if($data['txt_order']==""){
// 				$date= new Zend_Date();
// 				$sql = "SELECT * FROM tb_setting WHERE `code`=3";
// 				$po = $db->fetchOne($sql);
// 				$order_add=$po.$date->get('hh-mm-ss');
// 			}
// 			else{
// 				$order_add=$data['txt_order'];
// 			}
			$info_purchase_order=array(
					"customer_id"      => 	$data['customer_id'],
					'sale_agent_id'=>$data[''],
					"branch_id"      => 	$data["branch_id"],
					"quoat_number"   => 	$data['txt_order'],
					"date_order"     => 	$data['order_date'],
					"saleagent_id"     	 => 	$data['saleagent_id'],
					"payment_method" => $data['payment_name'],
					"currency_id"    => $data['currency'],
					"remark"         => 	$data['remark'],
					"all_total"      => 	$data['totalAmoun'],
					"discount_value" => 	$data['dis_value'],
					"discount_real"  => 	$data['global_disc'],
					"net_total"      => 	$data['all_total'],
					"paid"           => 	$data['paid'],
					"balance"        => 	$data['remain'],
					"tax"			 =>     $data["total_tax"],
					"user_mod"       => 	$GetUserId,
					"date"      => 	date("Y-m-d"),
			);
			 $this->_name="tb_quoatation";
			$qoid = $this->insert($info_purchase_order); 
			unset($info_purchase_order);

			$ids=explode(',',$data['identity']);
			$locationid=$data['branch_id'];
			foreach ($ids as $i)
			{
				$data_item= array(
						'quoat_id'	  => 	$qoid,
						'pro_id'	  => 	$data['item_id_'.$i],
						'qty_order'	  => 	$data['qty'.$i],
						'qty_detail'  => 	$data['qty_per_unit_'.$i],
						'price'		  => 	$data['price'.$i],
						'disc_value'	  => $data['real-value'.$i],
						'sub_total'	  => $data['total'.$i],
				);
				$this->_name='tb_quoatation_item';
				$this->insert($data_item);
			 }
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			Application_Form_FrmMessage::message('INSERT_FAIL');
			$err =$e->getMessage();
			echo $err;exit();
			Application_Model_DbTable_DbUserLog::writeMessageError($err);
		}
	} 
}