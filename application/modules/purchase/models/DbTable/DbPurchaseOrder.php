<?php

class Purchase_Model_DbTable_DbPurchaseOrder extends Zend_Db_Table_Abstract
{	
	function getAllPurchaseOrder($search){//new
		$db= $this->getAdapter();
		$sql=" SELECT id,
		(SELECT name FROM `tb_sublocation` WHERE tb_sublocation.id = branch_id AND status=1 AND name!='' LIMIT 1) AS branch_name,
		(SELECT v_name FROM `tb_vendor` WHERE tb_vendor.vendor_id=tb_purchase_order.vendor_id LIMIT 1 ) AS vendor_name,
		order_number,date_order,date_in,
		(SELECT symbal FROM `tb_currency` WHERE id= currency_id limit 1) As curr_name,
		net_total,paid,balance,
		(SELECT name_en FROM `tb_view` WHERE key_code = purchase_status AND `type`=1) As purchase_status,
		(SELECT name_en FROM `tb_view` WHERE key_code =tb_purchase_order.status AND type=5 LIMIT 1),
		(SELECT u.username FROM tb_acl_user AS u WHERE u.user_id = user_mod LIMIT 1 ) AS user_name
		FROM `tb_purchase_order` ";
		$from_date =(empty($search['start_date']))? '1': " date_order >= '".$search['start_date']." 00:00:00'";
		$to_date = (empty($search['end_date']))? '1': " date_order <= '".$search['end_date']." 23:59:59'";
		$where = " WHERE ".$from_date." AND ".$to_date;
		if(!empty($search['text_search'])){
			$s_where = array();
			$s_search = trim(addslashes($search['text_search']));
			$s_where[] = " order_number LIKE '%{$s_search}%'";
			$s_where[] = " net_total LIKE '%{$s_search}%'";
			$s_where[] = " paid LIKE '%{$s_search}%'";
			$s_where[] = " balance LIKE '%{$s_search}%'";
			$where .=' AND ('.implode(' OR ',$s_where).')';
		}
		if($search['suppliyer_id']>0){
			$where .= " AND vendor_id = ".$search['suppliyer_id'];
		}
		if($search['purchase_status']>0){
			$where .= " AND purchase_status =".$search['purchase_status'];
		}
		$dbg = new Application_Model_DbTable_DbGlobal();
		$where.=$dbg->getAccessPermission();
		$order=" ORDER BY id DESC ";
		return $db->fetchAll($sql.$where.$order);
	}
	public function addPurchaseOrder($data)
	{
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$db_global = new Application_Model_DbTable_DbGlobal();
				
			$session_user=new Zend_Session_Namespace('auth');
			$userName=$session_user->user_name;
			$GetUserId= $session_user->user_id;
			$idrecord=$data['v_name'];
			if($data['txt_order']==""){
				$date= new Zend_Date();
				$sql = "SELECT * FROM tb_setting WHERE `code`=8 ";
				$po = $db_global->getGlobalDbRow($sql);
				$PO = $po["key_value"];
				$order_add=$PO.$date->get('hh-mm-ss');
			}
			else{
				$order_add=$data['txt_order'];
			}
			$info_purchase_order=array(
					"vendor_id"      => 	$data['v_name'],
					"branch_id"      => 	$data["LocationId"],
					"order_number"   => 	$order_add,
					"date_order"     => 	date("Y-m-d",strtotime($data['order_date'])),
					"date_in"     	 => 	date("Y-m-d",strtotime($data['date_in'])),
					"purchase_status"=> 	$data['status'],
					"payment_method" => $data['payment_name'],
					"currency_id"    => $data['currency'],
					"remark"         => 	$data['remark'],
					"all_total"      => 	$data['totalAmoun'],
					//"discount_type"	 => $data['discount_type'],
					"discount_value" => 	$data['dis_value'],
					"discount_real"  => 	$data['global_disc'],
					"net_total"      => 	$data['all_total'],
					"paid"           => 	$data['paid'],
					"balance"        => 	$data['remain'],
					//"tax"			 =>     $data["total_tax"],
					"user_mod"       => 	$GetUserId,
					"date"      => 	new Zend_Date(),
			);
			$this->_name="tb_purchase_order";
			$purchase_id = $this->insert($info_purchase_order);
			unset($info_purchase_order);
	
			if($data["status"]==5 OR $data["status"]==4){
				$sqls = "SELECT * FROM tb_setting WHERE `code`=16 ";
				$ro = $db_global->getGlobalDbRow($sqls);
				$RO = $ro["key_value"];
				$date= new Zend_Date();
				$recieve_no=$RO.$date->get('hh-mm-ss');
				$orderdata = array(
						'purchase_id'=>$purchase_id,
						"vendor_id"      => 	$data['v_name'],
						"LocationId"     => 	$data["LocationId"],
						"recieve_number" => 	$recieve_no,
						"date_order"     => 	$data['order_date'],
						"date_in"     	 => 	$data['date_in'],
						"purchase_status"         => 	$data['status'],
						"payment_method" => $data['payment_name'],
						"currency_id"    => $data['currency'],
						"remark"         => 	$data['remark'],
						"all_total"      => 	$data['totalAmoun'],
						//"tax"=>$data["total_tax"],
						"discount_value" => 	$data['dis_value'],
						"discount_real"  => 	$data['global_disc'],
						"net_total"      => 	$data['all_total'],
						"paid"           => 	$data['paid'],
						"balance"        => 	$data['remain'],
						"user_mod"       => 	$GetUserId,
						"date"      => 	new Zend_Date(),
				);
				$this->_name='tb_recieve_order';
				$recieved_order = $this->insert($orderdata);
				unset($orderdata);
			}
				
			$ids=explode(',',$data['identity']);
			$locationid=$data['LocationId'];
			foreach ($ids as $i)
			{
				$data_item= array(
						'purchase_id'	  => 	$purchase_id,
						'pro_id'	  => 	$data['item_id_'.$i],
						'qty_order'	  => 	$data['qty'.$i],
						'qty_detail'  => 	$data['qty_per_unit_'.$i],
						'price'		  => 	$data['price'.$i],
						//'total_befor' => 	$data['total'.$i],
						'disc_value'	  => $data['real-value'.$i],
						'sub_total'	  => $data['total'.$i],
						//'remark'	  => $data['remark_'.$i]
				);
				$this->_name='tb_purchase_order_item';
				$this->insert($data_item);
	
				if($data["status"]==5 OR $data["status"]==4){
					$recieved_item = array(
							'recieve_id'	  => 	$recieved_order,
							'pro_id'	  => 	$data['item_id_'.$i],
							'qty_order'	  => 	$data['qty'.$i],
							'qty_detail'  => 	$data['qty_per_unit_'.$i],
							'price'		  => 	$data['price'.$i],
							'disc_value'	  => $data['real-value'.$i],
							'sub_total'	  => $data['total'.$i],
					);
					$db->insert("tb_recieve_order_item", $recieved_item);
						
					unset($recieved_item);
					$rows=$db_global ->productLocationInventory($data['item_id_'.$i], $locationid);//check stock product location
					if($rows)
					{
						if($data["status"]==4 OR $data["status"]==5){
							$datatostock   = array(
									'qty'   		=> 		$rows["qty"]+$data['qty'.$i],
									'last_mod_date'		=>	date("Y-m-d"),
									'last_mod_userid'=>$GetUserId
							);
							$this->_name="tb_prolocation";
							$where=" id = ".$rows['id'];
							$this->update($datatostock, $where);
						}
					}
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
	public function getPurchaseID($id){
		$db = $this->getAdapter();
		$sql = "SELECT CONCAT(p.item_name,'(',p.item_code,' )') AS item_name , p.qty_perunit,od.order_id, od.pro_id, od.qty_order,
		od.price, od.total_befor, od.disc_type,	od.disc_value, od.sub_total, od.remark 
		FROM tb_purchase_order_item AS od
		INNER JOIN tb_product AS p ON p.pro_id=od.pro_id WHERE od.order_id=".$id;
		$row = $db->fetchAll($sql);
		return $row;
	}
	public function getPurchaseById($id){//just new 
		$db=$this->getAdapter();
		$sql = "SELECT * FROM `tb_purchase_order` WHERE id=$id LIMIT 1 ";
		$rows=$db->fetchRow($sql);
		return $rows;
	}
	public function getPurchaseDetailById($id){//just new
		$db=$this->getAdapter();
		$sql = "SELECT * FROM `tb_purchase_order_item` WHERE purchase_id=$id ";
		$rows=$db->fetchAll($sql);
		return $rows;
	}
	public function recieved_info($order_id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM tb_recieve_order WHERE order_id=".$order_id." LIMIT 1";		
		$row =$db->fetchRow($sql);
		return $row;
	}
	//for get left order address change form showsaleorder to below
	public function showPurchaseOrder(){
		$db= $this->getAdapter();
		$sql = "SELECT p.order_id, p.order, p.date_order, p.status, v.v_name, p.all_total,p.paid,p.balance
		FROM tb_purchase_order AS p  INNER JOIN tb_vendor AS v ON v.vendor_id=p.vendor_id";
		$row=$db->fetchAll($sql);
		return $row;
		
	}
	public function getVendorInfo($post){
		$db=$this->getAdapter();
		$sql="SELECT contact_name,phone, add_name AS address 
		FROM tb_vendor WHERE vendor_id = ".$post['vendor_id']." LIMIT 1";
		$row=$db->fetchRow($sql);
		return $row;
	}
	
}