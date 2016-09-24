<?php

class Sales_Model_DbTable_DbSaleOrder extends Zend_Db_Table_Abstract
{
	//use for add purchase order 29-13
	
	function getAllSaleOrder($search){
			$db= $this->getAdapter();
			$sql=" SELECT id,
			(SELECT name FROM `tb_sublocation` WHERE tb_sublocation.id = branch_id AND status=1 AND name!='' LIMIT 1) AS branch_name,
			(SELECT cust_name FROM `tb_customer` WHERE tb_customer.id=tb_sales_order.customer_id LIMIT 1 ) AS customer_name,
			(SELECT name FROM `tb_sale_agent` WHERE tb_sale_agent.id =tb_sales_order.saleagent_id  LIMIT 1 ) AS staff_name,
			sale_no,date_sold,
			(SELECT symbal FROM `tb_currency` WHERE id= currency_id limit 1) As curr_name,
			net_total,paid,balance,
			(SELECT u.username FROM tb_acl_user AS u WHERE u.user_id = user_mod) AS user_name
			FROM `tb_sales_order` ";
			
			$from_date =(empty($search['start_date']))? '1': " date_sold >= '".$search['start_date']." 00:00:00'";
			$to_date = (empty($search['end_date']))? '1': " date_sold <= '".$search['end_date']." 23:59:59'";
			$where = " WHERE ".$from_date." AND ".$to_date;
			if(!empty($search['text_search'])){
				$s_where = array();
				$s_search = trim(addslashes($search['text_search']));
				$s_where[] = " sale_no LIKE '%{$s_search}%'";
				$s_where[] = " net_total LIKE '%{$s_search}%'";
				$s_where[] = " paid LIKE '%{$s_search}%'";
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
	public function addSaleOrder($data)
	{
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$db_global = new Application_Model_DbTable_DbGlobal();
			$session_user=new Zend_Session_Namespace('auth');
			$userName=$session_user->user_name;
			$GetUserId= $session_user->user_id;

			$info_purchase_order=array(
					"customer_id"   => 	$data['customer_id'],
					"branch_id"     => 	$data["branch_id"],
					"sale_no"       => 	$data['txt_order'],
					"date_sold"     => 	date("Y-m-d",strtotime($data['order_date'])),
					"saleagent_id"  => 	$data['saleagent_id'],
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
			$this->_name="tb_sales_order";
			$sale_id = $this->insert($info_purchase_order); 
			unset($info_purchase_order);

			$ids=explode(',',$data['identity']);
			$locationid=$data['branch_id'];
			foreach ($ids as $i)
			{
				$data_item= array(
						'saleorder_id'=> $sale_id,
						'pro_id'	  => 	$data['item_id_'.$i],
						'qty_order'	  => 	$data['qty'.$i],
						'qty_detail'  => 	$data['qty_per_unit_'.$i],
						'price'		  => 	$data['price'.$i],
						'disc_value'	  => $data['real-value'.$i],
						'sub_total'	  => $data['total'.$i],
				);
				$this->_name='tb_salesorder_item';
				$this->insert($data_item);
				
				$rows=$db_global ->productLocationInventory($data['item_id_'.$i], $locationid);//check stock product location
				
				if($rows)
				{
					
						$datatostock   = array(
								'qty'   		=> 		$rows["qty"]-$data['qty'.$i],
								'last_mod_date'		=>	date("Y-m-d"),
								'last_mod_userid'=>$GetUserId
						);
						$this->_name="tb_prolocation";
						$where=" id = ".$rows['id'];
						$this->update($datatostock, $where);
					
				}else{
				}
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