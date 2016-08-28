<?php

class sales_Model_DbTable_DbSalesOrder extends Zend_Db_Table_Abstract
{
	//for click payment
	public function CustomerAddOrderPayment($data)
	{
		try{
			$db = $this->getAdapter();
			$db->beginTransaction();
			$db_global= new Application_Model_DbTable_DbGlobal();
			$session_user=new Zend_Session_Namespace('auth');
			$userName = $session_user->user_name;
			$GetUserId= $session_user->user_id;
		//command below not yet use
	// 		$idrecord=$data['customer_id'];
	// 		$datainfo=array(
	// 				"contact_name"=>$data['contact'],
	// 				"phone"       =>$data['txt_phone'],
	// 		);
	// 		//updage customer info
	// 		$itemid=$db_global->updateRecord($datainfo, $idrecord, "customer_id","tb_customer");
	// 		unset($datainfo);
			if($data['order']==""){
				$date= new Zend_Date();
				$order_add="SO".$date->get('hh-mm-ss');
			}
			else{
				$order_add=$data['order'];
					
			}
			$info_order=array(
					"customer_id"    => $data['customer_id'],
					"LocationId"     => $data['LocationId'],
					"order"          => $order_add,
					"sales_ref"      => $data['sales_ref'],
					"date_order"     => $data['order_date'],
					"status"         => $data['status'],
				//	"payment_method" => $data['payment_name'],
				//	"currency_id"    => $data['currency'],
					"remark"         => $data['remark'],
					"user_mod"       => $GetUserId,
					"timestamp"      => new Zend_Date(),
					//"net_total"      => $data['net_total'],
					//"discount_type"	 => $data['discount_type'],
					//"discount_value" => $data['discount_value'],
					//"discount_real"  => $data["discount_real"],
					"paid"			 => $data['remain'],
					"all_total"      => $data['remain'],
					"balance"        => 0
			);
			//and info of order
			$id_order=$db_global->addRecord($info_order,"tb_sales_order");
			unset($info_order);
		
			$ids=explode(',',$data['identity']);
			foreach ($ids as $i)
			{
				//add history order
				$data_history = array(
						'pro_id'	 => $data['item_id_'.$i],
						'type'		 => 2,
						'order'		 => $id_order,//$data['order']
						'customer_id'=> $data['customer_id'],
						'date'		 => $data['order_date'],
						'status'	 => 4,
						'order_total'=> $data['remain'],
						'qty'		 => $data['qty'.$i],
						'unit_price' => $data['price'.$i],
						'sub_total'  => $data['total'.$i],
				);
				$db->insert("tb_order_history", $data_history);
				unset($data_history);
				
				$data_item[$i]= array(
						'order_id'	  => $id_order,
						'pro_id'	  => $data['item_id_'.$i],
						'qty_order'	  => $data['qty'.$i],
						'price'		  => $data['price'.$i],
						'total_befor' => $data['total'.$i],
	// 					'disc_type'	  => $data['dis-type-'.$i],
	// 					'disc_value'  => $data['dis-value'.$i],
						'sub_total'	  => $data['total'.$i]
				);
			   $db->insert("tb_sales_order_item", $data_item[$i]);
				
				unset($data_item[$i]);
		
				//check stock product location
				$locationid=$data['LocationId'];
			    $itemId=$data['item_id_'.$i];
				$qtyrecord=$data['qty'.$i];//qty on 1 record
				
				$rows=$db_global -> productLocationInventory($itemId, $locationid);//to check product location
				if($rows)
				{
					$qtyold       = $rows['qty'];
					$getrecord_id = $rows["ProLocationID"];
					$itemOnHand = array(
							'qty_onsold'   => $rows["qty_onsold"]+$qtyrecord,
							'qty_onsold'=> $rows["qty_onsold"]+$qtyrecord
					);
					//update total stock
					$db_global->updateRecord($itemOnHand,$itemId,"pro_id","tb_product");
					unset($itemOnHand);
					//update stock dork
					//$newqty       = $rows['qty']-$qtyrecord;
					$updatedata=array(
							'qty_onsold' => $rows['qty_onsold']+$qtyrecord
					);
					//update stock product location
					$db_global->updateRecord($updatedata,$getrecord_id,"ProLocationID","tb_prolocation");
					unset($updatedata);
					//update stock record
				}
				else
				{
					//insert stock ;
					$rows_pro_exit= $db_global->productLocation($itemId, $locationid);
					if($rows_pro_exit){
						$updatedata=array(
								'qty_onsold' => $rows_pro_exit['qty_onsold']+$qtyrecord
						);
						//update stock product location
						$itemid=$db_global->updateRecord($updatedata,$rows_pro_exit['ProLocationID'], "ProLocationID", "tb_prolocation");
						unset($updatedata);
					}
					else{
						$insertdata=array(
								'pro_id'       => $itemId,
								'LocationId'   => $locationid,
								'last_usermod' => $GetUserId,
								'qty'          => -$qtyrecord,
								'last_mod_date'=>new Zend_Date()
						);
						//update stock product location
						$db->insert("tb_prolocation", $insertdata);
						unset($insertdata);
					}
					
					$rowitem=$db_global->InventoryExist($itemId);//to check product location
					if($rowitem)
					{
						$itemOnHand   = array(
								'QuantityOnHand'=>$rowitem["QuantityOnHand"]-$qtyrecord,
								'QuantityAvailable'=>$rowitem["QuantityAvailable"]-$qtyrecord,
		
						);
						//update total stock
						$itemid=$db_global->updateRecord($itemOnHand,$itemId,"ProdId","tb_inventorytotal");
						unset($itemOnHand);
					}
					else
					{
						$dataInventory= array(
								'ProdId'            => $itemId,
								'QuantityOnHand'    => -$data['qty'.$i],
								'QuantityAvailable' => -$data['qty'.$i],
								'Timestamp'         => new Zend_date()
						);
						$db->insert("tb_inventorytotal", $dataInventory);
						unset($dataInventory);
						//update stock product location
					}
				}
			}
			$db->commit();
	    }catch(Exception $e){
			$db->rollBack();
		}
	}
	
	//page add when click save
	//////////////order but not yet
	public function CustomerOrderSave($data)
	{
		$db=$this->getAdapter();
		$db_global = new Application_Model_DbTable_DbGlobal();
		
		$session_user=new Zend_Session_Namespace('auth');
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
	
		$idrecord=$data['customer_id'];
		$datainfo=array(
				"contact_name"=>$data['contact'],
				"phone"       =>$data['txt_phone'],
				"add_name"  =>$data['customer_add']
		);
		$itemid=$db_global->updateRecord($datainfo,$idrecord,"customer_id","tb_customer");
		
		//updage customer info //not clear cos write wrong method
// 		$id = $data["id"];
// 		$sql_del= "DELETE FROM tb_sales_order_item WHERE order_id IN ($id)";
// 		$db_global->deleteRecords($sql_del);
// 		$sql_sale= "DELETE FROM tb_sales_order WHERE order_id IN ($id)";
//		$db_global->deleteRecords($sql_sale);
		if($data['order']==""){
			$date= new Zend_Date();
			$order_add="SO".$date->get('hh-mm-ss');
		}
		else{
			$order_add=$data['order'];
				
		}
		$info_order=array(
				"customer_id"    => $data['customer_id'],
				"LocationId"     => $data['LocationId'],
				"order"          => $order_add,
				"sales_ref"      => $data['sales_ref'],
				"date_order"     => $data['order_date'],
				"status"         => 2,
				"payment_method" => $data['payment_name'],
				"currency_id"    => $data['currency'],
				"remark"         => $data['remark'],
				"user_mod"       => $GetUserId,
				"timestamp"      => new Zend_Date(),
				"version"        => 1,
				"net_total"      => $data['net_total'],
				"discount_type"	 => $data['discount_type'],
				"discount_value" => $data['discount_value'],
				"discount_real"  => $data["discount_real"],
				"paid"			 => $data['paid'],
				"all_total"      => $data['all_total'],
				"balance"        => $data['all_total']-$data['paid']
		);
		//and info of order
		$id_order=$db_global->addRecord($info_order,"tb_sales_order");
		  
		unset($info_order);
		$ids=explode(',',$data['identity']);
		$qtyonhand=0;
		foreach ($ids as $i)
		{
			//add history order
			$data_history[$i] = array(
					'pro_id'	 => $data['item_id_'.$i],
					'type'		 => 2,
					'order'		 => $id_order,
					'customer_id'=> $data['customer_id'],
					'date'		 => new Zend_Date(),
					'status'	 => 2,
					'order_total'=> $data['all_total'],
					'qty'		 => $data['qty'.$i],
					'unit_price' => $data['price'.$i],
					'sub_total'  => $data['after_discount'.$i],
			);
			$order_history = $db->insert("tb_order_history", $data_history[$i]);
			unset($data_history[$i]);
				
			$data_item[$i]= array(
					'order_id'	  => $id_order,
					'pro_id'	  => $data['item_id_'.$i],
					'qty_order'	  => $data['qty'.$i],
					'price'		  => $data['price'.$i],
					'total_befor' => $data['total'.$i],
					'disc_type'	  => $data['dis-type-'.$i],
					'disc_value'  => $data['dis-value'.$i],
					'sub_total'	  => $data['after_discount'.$i]
			);
			$db->insert("tb_sales_order_item",$data_item[$i]);
			unset($data_item[$i]);
				
			//update stock total inventory (QTY Reserved)
			$locationid=$data['LocationId'];
			$itemId=$data['item_id_'.$i];
			$qtyrecord=$data['qty'.$i];//qty on 1 record
			$rows=$db_global->InventoryExist($itemId);
			if($rows)
			{
				$qty_on_reserved = array(
						'QuantitySold'      => $rows["QuantitySold"]+$qtyrecord,
						'QuantityAvailable' => $rows["QuantityAvailable"] - $qtyrecord
				);
				//update total stock
				$db_global->updateRecord($qty_on_reserved,$itemId,"ProdId","tb_inventorytotal");
			}
			else{
					$addInventory= array(
							'ProdId'            => $itemId,
							'QuantityOnOrder'    => $data['qty'.$i],
							'Timestamp'         => new Zend_date()
					);
					$db_global->addRecord($addInventory,"tb_inventorytotal");
					unset($addInventory);
				}
		}
	} 
	/**
	 * for page update
	 * 29-13
	 * 
	 * 
	 * 
	 */
	///when click payment on page upload
	public function updateCustomerOrderPayment($data){
	
		$db_global= new Application_Model_DbTable_DbGlobal();
		$db=$this->getAdapter();
	
		$session_user=new Zend_Session_Namespace('auth');
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
	
		$idrecord=$data['customer_id'];
		$datainfo=array(
				"contact_name"=> $data['contact'],
				"phone"       => $data['txt_phone']
		);
		//updage customer info
		$db_global->updateRecord($datainfo,$idrecord,"customer_id","tb_customer");
		$id_order_update=$data['id'];
		$info_order=array(
				"customer_id"    => $data['customer_id'],
				"LocationId"     => $data['LocationId'],
				"order"          => $data['order'],
				"sales_ref"      => $data['sales_ref'],
				"date_order"     => $data['order_date'],
				"status"         => 4,
				"payment_method" => $data['payment_name'],
				"currency_id"    => $data['currency'],
				"remark"         => $data['remark'],
				"user_mod"       => $GetUserId,
				"timestamp"      => new Zend_Date(),
				"version"        => 1,
				"net_total"      => $data['net_total'],
				"discount_type"	 => $data['discount_type'],
				"discount_value" => $data['discount_value'],
				"discount_real"  => $data["discount_real"],
				"paid"			 => $data['all_total'],
				"all_total"      => $data['all_total'],
				"balance"        => 0
		);
		//update info of order
		$db_global->updateRecord($info_order,$id_order_update,"order_id","tb_sales_order");
		unset($info_order);
		$rows_exist=$db_global->salesOrderHistoryExitAll($id_order_update);
		if($rows_exist){
			foreach ($rows_exist as $id_history){
				$data_status=array(
						'status'=> 4
				);
					
				$db_global->updateRecord($data_status, $id_history['history_id'], "history_id", "tb_order_history");
				unset($data_status);
			}
		
		}
		//and info of order
		$sql_item="SELECT iv.ProdId, iv.QuantitySold, iv.QuantityAvailable, sum(so.qty_order) AS qtysold FROM tb_sales_order_item AS so
		INNER JOIN tb_inventorytotal AS iv ON iv.ProdId = so.pro_id WHERE so.order_id =$id_order_update GROUP BY so.pro_id";
		$rows_sold=$db_global->getGlobalDb($sql_item);
		if($rows_sold){
			foreach ($rows_sold as $row_sold){
				$qty_on_sold = array(
						"QuantitySold"      => $row_sold["QuantitySold"]-$row_sold["qtysold"],
						"QuantityAvailable" => $row_sold["QuantityAvailable"]+$row_sold["qtysold"],
				);
				//update total stock
				$db_global->updateRecord($qty_on_sold,$row_sold["ProdId"],"ProdId","tb_inventorytotal");
			}
	
		}
		unset($qty_on_sold);
		unset($rows_order);
		$sql= "DELETE FROM tb_sales_order_item WHERE order_id IN ($id_order_update)";
		$db_global->deleteRecords($sql);
	
		$ids=explode(',',$data['identity']);
		$qtyonhand=0;
		foreach ($ids as $i)
		{
			$data_item[$i]= array(
					'order_id'	  => $id_order_update,
					'pro_id'	  => $data['item_id_'.$i],
					'qty_order'	  => $data['qty'.$i],
					'price'		  => $data['price'.$i],
					'total_befor' => $data['total'.$i],
					'disc_type'	  => $data['dis-type-'.$i],
					'disc_value'  => $data['dis-value'.$i],
					'sub_total'	  => $data['after_discount'.$i]
			);
			$db->insert("tb_sales_order_item", $data_item[$i]);
			unset($data_item[$i]);
				
			$locationid=$data['LocationId'];
			$itemId=$data['item_id_'.$i];
			$qtyrecord=$data['qty'.$i];//qty on 1 record
// 			$sql="SELECT pl.ProLocationID, pl.qty, iv.QuantityOnHand, iv.QuantityAvailable
// 			FROM tb_prolocation AS pl 
// 			INNER JOIN tb_inventorytotal AS iv ON iv.ProdId = pl.pro_id
// 			WHERE pl.LocationId =".$locationid." AND pl.pro_id=".$itemId;
			$rows=$db_global->inventoryLocation($locationid, $itemId);
			if($rows)
			{
				$qty_on_order = array(
						"QuantityAvailable" => $rows["QuantityAvailable"] - $data['qty'.$i] ,
						"QuantityOnHand"    => $rows["QuantityOnHand"] - $data['qty'.$i]
				);
				//update total stock
				$db_global->updateRecord($qty_on_order,$itemId,"ProdId","tb_inventorytotal");
				unset($qty_on_order);
				//update stock dork
				$updatedata=array(
						'qty' => $rows["qty"]-$qtyrecord
				);
				//update stock product location
				$db_global->updateRecord($updatedata,$rows["ProLocationID"],"ProLocationID","tb_prolocation");
				unset($updatedata);
				//update stock record
			}
			else{
				//insert stock
				$insertdata=array(
						'pro_id'     => $itemId,
						'LocationId' => $locationid,
						'qty'        => -$qtyrecord
				);
				//update stock product location
				$db->insert("tb_prolocation", $insertdata);
				unset($insertdata);
				$rows_stock=$db_global->InventoryExist($itemId);
				if($rows_stock){
					$dataInventory= array(
							'ProdId'            => $itemId,
							'QuantityOnHand'    => $rows_stock["QuantityOnHand"]- $data['qty'.$i],
							'Timestamp'         => new Zend_date()
					);
					$db_global->updateRecord($dataInventory,$rows_stock["ProdId"],"ProdId","tb_inventorytotal");
					unset($dataInventory);
				}
				else{
					$addInventory= array(
							'ProdId'            => $itemId,
							'QuantityOnHand'    => -$qtyrecord,
							'QuantityAvailable' => -$qtyrecord,
							'Timestamp'         => new Zend_date()
					);
					$db->insert("tb_inventorytotal", $addInventory);
					unset($addInventory);
				}
			}
				
		}
	
	}
	/*
	 * for update then save 29-13
	 * 
	 * */
	public function updateCustomerOrder($data)
	{
		$db_global= new Application_Model_DbTable_DbGlobal();
		$db = $this->getAdapter();
	
		$session_user=new Zend_Session_Namespace('auth');
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
	
		$idrecord=$data['customer_id'];
		$datainfo=array(
				"contact_name"=> $data['contact'],
				"phone"       => $data['txt_phone']
		);
		//updage customer info
	
		$itemid=$db_global->updateRecord($datainfo,$idrecord,"customer_id","tb_customer");
		//for update order by id\
		$id_order_update=$data['id'];
		$info_order=array(
				"customer_id"    => $data['customer_id'],
				"LocationId"     => $data['LocationId'],
				"order"          => $data['order'],
				"sales_ref"      => $data['sales_ref'],
				"date_order"     => $data['order_date'],
				"status"         => 2,
				"payment_method" => $data['payment_name'],
				"currency_id"    => $data['currency'],
				"remark"         => $data['remark'],
				"user_mod"       => $GetUserId,
				"timestamp"      => new Zend_Date(),
				"version"        => 1,
				"net_total"      => $data['net_total'],
				"discount_type"	 => $data['discount_type'],
				"discount_value" => $data['discount_value'],
				"discount_real"  => $data["discount_real"],
				"paid"			 => $data['paid'],
				"all_total"      => $data['all_total'],
				"balance"        => $data['all_total']-$data['paid']
		);
		//update info of order
		$db_global->updateRecord($info_order,$id_order_update,"order_id","tb_sales_order");
		unset($info_order);
		$sql_item="SELECT iv.ProdId, iv.QuantitySold, iv.QuantityAvailable, sum(so.qty_order) AS qtysold FROM tb_sales_order_item AS so
		INNER JOIN tb_inventorytotal AS iv ON iv.ProdId = so.pro_id WHERE so.order_id =$id_order_update GROUP BY so.pro_id";
		$rows_sold=$db_global->getGlobalDb($sql_item);
		if($rows_sold){
			foreach ($rows_sold as $row_sold){
				$qty_on_order = array(
						"QuantitySold"=>$row_sold["QuantitySold"]-$row_sold["qtysold"],
						"QuantityAvailable"=>$row_sold["QuantityAvailable"]+$row_sold["qtysold"],
				);
				//update total stock
				$db_global->updateRecord($qty_on_order,$row_sold["ProdId"],"ProdId","tb_inventorytotal");
			}
	
		}
		unset($qty_on_order);
		//delete old sale order
		unset($rows_sold);
		$sql= "DELETE FROM tb_sales_order_item WHERE order_id IN ($id_order_update)";
		$db_global->deleteRecords($sql);
	
		$ids=explode(',',$data['identity']);
		$qtyonhand=0;
		//$db->DeleteData("tb_sales_order_item"," WHERE order_id = ".$id_order_update);
		foreach ($ids as $i)
		{
			$data_item[$i]= array(
					'order_id'	  => $id_order_update,
					'pro_id'	  => $data['item_id_'.$i],
					'qty_order'	  => $data['qty'.$i],
					'price'		  => $data['price'.$i],
					'total_befor' => $data['total'.$i],
					'disc_type'	  => $data['dis-type-'.$i],
					'disc_value'  => $data['dis-value'.$i],
					'sub_total'	  => $data['after_discount'.$i]
			);
			$db->insert("tb_sales_order_item", $data_item[$i]);
			unset($data_item[$i]);
			$rows_add=$db_global->InventoryExist($data['item_id_'.$i]);
			if($rows_add)
			{
				$qty_on_order = array(
						"QuantitySold"=>$rows_add["QuantitySold"]+$data['qty'.$i] ,
						"QuantityAvailable"=>$rows_add["QuantityAvailable"]-$data['qty'.$i]
				);
				//update total stock
				$db_global->updateRecord($qty_on_order,$rows_add["ProdId"],"ProdId","tb_inventorytotal");
				unset($qty_on_order);
			}
		}
	}
	//page add when click save
	//////////////order but not yet
	public function addQuote($data)
	{
		$db=$this->getAdapter();
		$db_global = new Application_Model_DbTable_DbGlobal();
	
		$session_user=new Zend_Session_Namespace('auth');
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
	
		$idrecord=$data['customer_id'];
		$datainfo=array(
				"contact_name"=>$data['contact'],
				"phone"       =>$data['txt_phone'],
				"add_name"  =>$data['customer_add']
		);
		$itemid=$db_global->updateRecord($datainfo,$idrecord,"customer_id","tb_customer");
		//updage customer info
		if($data['order']==""){
			$date= new Zend_Date();
			$order_add="SQ-".$date->get('mm-ss');
		}
		else{
			$order_add=$data['order'];
		}
		$info_order=array(
				"customer_id"    => $data['customer_id'],
				"LocationId"     => $data['LocationId'],
				"order"          => $order_add,
				"sales_ref"      => $data['sales_ref'],
				"date_order"     => $data['order_date'],
				"status"         => 1,
				"payment_method" => $data['payment_name'],
				"currency_id"    => $data['currency'],
				"remark"         => $data['remark'],
				"user_mod"       => $GetUserId,
				"timestamp"      => new Zend_Date(),
				"net_total"      => $data['net_total'],
				"discount_type"	 => $data['discount_type'],
				"discount_value" => $data['discount_value'],
				"discount_real"  => $data["discount_real"],
				"paid"			 => $data['paid'],
				"all_total"      => $data['all_total'],
				"balance"        => $data['all_total']-$data['paid']
		);
		//and info of order
		$id_order=$db_global->addRecord($info_order,"tb_sales_order");
		unset($info_order);
		
		$ids=explode(',',$data['identity']);
		foreach ($ids as $i)
		{
			$data_item[$i]= array(
					'order_id'	  => $id_order,
					'pro_id'	  => $data['item_id_'.$i],
					'qty_order'	  => $data['qty_order_'.$i],
					'price'		  => $data['price_'.$i],
					'total_befor' => $data['total_'.$i],
					'disc_type'	  => $data['dis-type-'.$i],
					'disc_value'  => $data['dis-value'.$i],
					'sub_total'	  => $data['after_discount'.$i]
			);
			$db->insert("tb_sales_order_item",$data_item[$i]);
			unset($data_item[$i]);
			//add history order
			$data_history[$i] = array(
					'pro_id'	 => $data['item_id_'.$i],
					'type'		 => 2,
					'order'		 => $id_order,
					'customer_id'=> $data['customer_id'],
					'date'		 => new Zend_Date(),
					'status'	 => 1,
					'order_total'=> $data['all_total'],
					'qty'		 => $data['qty_order_'.$i],
					'unit_price' => $data['price_'.$i],
					'sub_total'  => $data['after_discount'.$i],
			);
			$order_history = $db->insert("tb_order_history", $data_history[$i]);
			unset($data_history[$i]);	
			//update stock total inventory (QTY Reserved)
		}
	}
	public function convertQuote($data)
	{
		$db=$this->getAdapter();
		$db_global = new Application_Model_DbTable_DbGlobal();
	
		$session_user=new Zend_Session_Namespace('auth');
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
	
		$idrecord=$data['customer_id'];
		$datainfo=array(
				"contact_name"=>$data['contact'],
				"phone"       =>$data['txt_phone'],
				"add_name"  =>$data['customer_add']
		);
		//updage customer info
		$itemid=$db_global->updateRecord($datainfo,$idrecord,"customer_id","tb_customer");		
		if($data['order']==""){
			$date= new Zend_Date();
			$order_add="SO".$date->get('hh-mm-ss');
		}
		else{
			$order_add=$data['order'];
		
		}
		$info_order=array(
				"customer_id"    => $data['customer_id'],
				"LocationId"     => $data['LocationId'],
				"order"          => $order_add,
				"sales_ref"      => $data['sales_ref'],
				"date_order"     => $data['order_date'],
				"status"         => 2,
				"payment_method" => $data['payment_name'],
				"currency_id"    => $data['currency'],
				"remark"         => $data['remark'],
				"user_mod"       => $GetUserId,
				"timestamp"      => new Zend_Date(),
				"net_total"      => $data['net_total'],
				"discount_type"	 => $data['discount_type'],
				"discount_value" => $data['discount_value'],
				"discount_real"  => $data["discount_real"],
				"paid"			 => $data['paid'],
				"all_total"      => $data['all_total'],
				"balance"        => $data['all_total']-$data['paid']
		);
		//and info of order
		$id_order=$db_global->addRecord($info_order,"tb_sales_order");
		unset($info_order);
		$ids=explode(',',$data['identity']);
		$qtyonhand=0;
		foreach ($ids as $i)
		{
			//add history order
			$data_history[$i] = array(
					'pro_id'	 => $data['item_id_'.$i],
					'type'		 => 2,
					'order'		 => $id_order,
					'customer_id'=> $data['customer_id'],
					'date'		 => new Zend_Date(),
					'status'	 => 1,
					'order_total'=> $data['all_total'],
					'qty'		 => $data['qty_order_'.$i],
					'unit_price' => $data['price_'.$i],
					'sub_total'  => $data['after_discount'.$i],
			);
			$order_history = $db->insert("tb_order_history", $data_history[$i]);
			unset($data_history[$i]);
	
			$data_item[$i]= array(
					'order_id'	  => $id_order,
					'pro_id'	  => $data['item_id_'.$i],
					'qty_order'	  => $data['qty_order_'.$i],
					'price'		  => $data['price_'.$i],
					'total_befor' => $data['total_'.$i],
					'disc_type'	  => $data['dis-type-'.$i],
					'disc_value'  => $data['dis-value'.$i],
					'sub_total'	  => $data['after_discount'.$i]
			);
			$db->insert("tb_sales_order_item",$data_item[$i]);
			unset($data_item[$i]);
			//update stock total inventory (QTY Reserved)
			$itemId = $data['item_id_'.$i];
			$qtyrecord = $data['qty'.$i];//qty on 1 record
			$rows=$db_global->InventoryExist($itemId);
			if($rows)
			{
				$qty_on_reserved = array(
						'QuantitySold'      => $rows["QuantitySold"] + $data['qty_order_'.$i],
						'QuantityAvailable' => $rows["QuantityAvailable"] - $data['qty_order_'.$i]
				);
				//update total stock
				$db_global->updateRecord($qty_on_reserved,$itemId,"ProdId","tb_inventorytotal");
			}
			else{
				$addInventory= array(
						'ProdId'            => $itemId,
						'QuantityOnOrder'    => $data['qty'.$i],
						'Timestamp'         => new Zend_date()
				);
				$db_global->addRecord($addInventory,"tb_inventorytotal");
				unset($addInventory);
			}
		}
	}
	public function updateCustomerQuote($data){
		$db=$this->getAdapter();
		$db_global = new Application_Model_DbTable_DbGlobal();
		
		$session_user=new Zend_Session_Namespace('auth');
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
		
		$idrecord=$data['customer_id'];
		$datainfo=array(
				"contact_name"=> $data['contact'],
				"phone"       => $data['txt_phone'],
			    "add_name"    => $data['remark_add']
		);
		//updage customer info
		$itemid=$db_global->updateRecord($datainfo,$idrecord,"customer_id","tb_customer");
		
		$id = $data["id"];
		$sql_del= "DELETE FROM tb_sales_order_item WHERE order_id IN ($id)";
		$db_global->deleteRecords($sql_del);
		$sql_sale= "DELETE FROM tb_sales_order WHERE order_id IN ($id)";
		$db_global->deleteRecords($sql_sale);
		$new_order=str_replace("SQ","SO",$data["order"]);
		
		$info_order=array(
				"order_id"       => $id,
				"customer_id"    => $data['customer_id'],
				"LocationId"     => $data['LocationId'],
				"order"          => $new_order,
				"sales_ref"      => $data['sales_ref'],
				"date_order"     => $data['order_date'],
				"status"         => 2,
				"payment_method" => $data['payment_name'],
				"currency_id"    => $data['currency'],
				"remark"         => $data['remark'],
				"user_mod"       => $GetUserId,
				"timestamp"      => new Zend_Date(),
				"version"        => 1,
				"net_total"      => $data['net_total'],
				"discount_type"	 => $data['discount_type'],
				"discount_value" => $data['discount_value'],
				"discount_real"  => $data["discount_real"],
				"paid"			 => $data['paid'],
				"all_total"      => $data['all_total'],
				"balance"        => $data['all_total']-$data['paid']
		);
		//and info of order
		$id_order=$db_global->addRecord($info_order,"tb_sales_order");
		
		unset($info_order);
		$ids=explode(',',$data['identity']);
		$qtyonhand=0;
		foreach ($ids as $i)
		{
			//add history order
			$data_history[$i] = array(
					'pro_id'	 => $data['item_id_'.$i],
					'type'		 => 2,
					'order'		 => $id_order,
					'customer_id'=> $data['customer_id'],
					'date'		 => new Zend_Date(),
					'status'	 => 2,
					'order_total'=> $data['all_total'],
					'qty'		 => $data['qty'.$i],
					'unit_price' => $data['price'.$i],
					'sub_total'  => $data['after_discount'.$i],
			);
			$order_history = $db->insert("tb_order_history", $data_history[$i]);
			unset($data_history[$i]);
		
			$data_item[$i]= array(
					'order_id'	  => $id_order,
					'pro_id'	  => $data['item_id_'.$i],
					'qty_order'	  => $data['qty'.$i],
					'price'		  => $data['price'.$i],
					'total_befor' => $data['total'.$i],
					'disc_type'	  => $data['dis-type-'.$i],
					'disc_value'  => $data['dis-value'.$i],
					'sub_total'	  => $data['after_discount'.$i]
			);
			$db->insert("tb_sales_order_item",$data_item[$i]);
			unset($data_item[$i]);
		
			//update stock total inventory (QTY Reserved)
			$locationid=$data['LocationId'];
			$itemId=$data['item_id_'.$i];
			$qtyrecord=$data['qty'.$i];//qty on 1 record
			$rows=$db_global->InventoryExist($itemId);
			if($rows)
			{
				$qty_on_reserved = array(
						'QuantitySold'      => $rows["QuantitySold"]+$qtyrecord,
						'QuantityAvailable' => $rows["QuantityAvailable"] - $qtyrecord
				);
				//update total stock
				$db_global->updateRecord($qty_on_reserved,$itemId,"ProdId","tb_inventorytotal");
			}
			else{
				$addInventory= array(
						'ProdId'            => $itemId,
						'QuantityOnOrder'    => $data['qty'.$i],
						'Timestamp'         => new Zend_date()
				);
				$db_global->addRecord($addInventory,"tb_inventorytotal");
				unset($addInventory);
			}
		}
		
	}
	public function updateSaleQuote($data){
		
		$db=$this->getAdapter();
		$db_global = new Application_Model_DbTable_DbGlobal();
		
		$session_user=new Zend_Session_Namespace('auth');
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
		$idrecord=$data['customer_id'];
		$datainfo=array(
				"contact_name"=>$data['contact'],
				"phone"       =>$data['txt_phone'],
				"add_name"  =>$data['customer_add']
		);
		$itemid=$db_global->updateRecord($datainfo,$idrecord,"customer_id","tb_customer");
		
		$id = $data["id"];
		$sql_del= "DELETE FROM tb_sales_order_item WHERE order_id IN ($id)";
		$db_global->deleteRecords($sql_del);
		$sql_sale= "DELETE FROM tb_sales_order WHERE order_id IN ($id)";
		
		$info_order=array(
				"customer_id"    => $data['customer_id'],
				"LocationId"     => $data['LocationId'],
				"order"          => $order_add,
				"sales_ref"      => $data['sales_ref'],
				"date_order"     => $data['order_date'],
				"status"         => 1,
				"payment_method" => $data['payment_name'],
				"currency_id"    => $data['currency'],
				"remark"         => $data['remark'],
				"user_mod"       => $GetUserId,
				"timestamp"      => new Zend_Date(),
				"net_total"      => $data['net_total'],
				"discount_type"	 => $data['discount_type'],
				"discount_value" => $data['discount_value'],
				"discount_real"  => $data["discount_real"],
				"paid"			 => $data['paid'],
				"all_total"      => $data['all_total'],
				"balance"        => $data['all_total']-$data['paid']
		);
		//and info of order
		$id_order=$db_global->addRecord($info_order,"tb_sales_order");
		unset($info_order);
		$qtyonhand=0;
		foreach ($ids as $i)
		{
			$data_item[$i]= array(
					'order_id'	  => $id_order,
					'pro_id'	  => $data['item_id_'.$i],
					'qty_order'	  => $data['qty_order_'.$i],
					'price'		  => $data['price_'.$i],
					'total_befor' => $data['total_'.$i],
					'disc_type'	  => $data['dis-type-'.$i],
					'disc_value'  => $data['dis-value'.$i],
					'sub_total'	  => $data['after_discount'.$i]
			);
			$db->insert("tb_sales_order_item",$data_item[$i]);
			unset($data_item[$i]);
			//add history order
			$data_history[$i] = array(
					'pro_id'	 => $data['item_id_'.$i],
					'type'		 => 2,
					'order'		 => $id_order,
					'customer_id'=> $data['customer_id'],
					'date'		 => new Zend_Date(),
					'status'	 => 1,
					'order_total'=> $data['all_total'],
					'qty'		 => $data['qty_order_'.$i],
					'unit_price' => $data['price_'.$i],
					'sub_total'  => $data['after_discount'.$i],
			);
			$order_history = $db->insert("tb_order_history", $data_history[$i]);
			unset($data_history[$i]);
			//update stock total inventory (QTY Reserved)
		}
		
	}
	public function quoteUpdate($data){
		$db=$this->getAdapter();
		$db_global = new Application_Model_DbTable_DbGlobal();
		
		$session_user=new Zend_Session_Namespace('auth');
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
		$idrecord=$data['customer_id'];
		$datainfo=array(
				"contact_name"=> $data['contact'],
				"phone"       => $data['txt_phone'],
				"add_name"    => $data['remark_add']
		);
		$itemid=$db_global->updateRecord($datainfo,$idrecord,"customer_id","tb_customer");
		
		$id = $data["id"];
		$sql_del= "DELETE FROM tb_sales_order_item WHERE order_id IN ($id)";
		$db_global->deleteRecords($sql_del);
		$sql_sale= "DELETE FROM tb_sales_order WHERE order_id IN ($id)";
		$sql_del_history = "DELETE FROM tb_order_history WHERE type = 2 AND order_id IN ($id)";
		
		$info_order=array(
				"customer_id"    => $data['customer_id'],
				"LocationId"     => $data['LocationId'],
				"sales_ref"      => $data['sales_ref'],
				"date_order"     => $data['order_date'],
				"payment_method" => $data['payment_name'],
				"currency_id"    => $data['currency'],
				"remark"         => $data['remark'],
				"user_mod"       => $GetUserId,
				"timestamp"      => new Zend_Date(),
				"net_total"      => $data['net_total'],
				"discount_type"	 => $data['discount_type'],
				"discount_value" => $data['discount_value'],
				"discount_real"  => $data["discount_real"],
				"paid"			 => $data['paid'],
				"all_total"      => $data['all_total'],
				"balance"        => $data['all_total']-$data['paid']
		);
		//and info of order
		$id_order=$db_global->addRecord($info_order,"tb_sales_order");
		unset($info_order);
		
		$ids=explode(',',$data['identity']);
		foreach ($ids as $i)
		{
			$data_item[$i]= array(
					'order_id'	  => $id_order,
					'pro_id'	  => $data['item_id_'.$i],
					'qty_order'	  => $data['qty'.$i],
					'price'		  => $data['price'.$i],
					'total_befor' => $data['total'.$i],
					'disc_type'	  => $data['dis-type-'.$i],
					'disc_value'  => $data['dis-value'.$i],
					'sub_total'	  => $data['after_discount'.$i]
			);
			$db->insert("tb_sales_order_item",$data_item[$i]);
			unset($data_item[$i]);
			//add history order
			$data_history[$i] = array(
					'pro_id'	 => $data['item_id_'.$i],
					'type'		 => 2,
					'order'		 => $id_order,
					'customer_id'=> $data['customer_id'],
					'date'		 => new Zend_Date(),
					'status'	 => 1,
					'order_total'=> $data['all_total'],
					'qty'		 => $data['qty'.$i],
					'unit_price' => $data['price'.$i],
					'sub_total'  => $data['after_discount'.$i],
			);
			$order_history = $db->insert("tb_order_history", $data_history[$i]);
			unset($data_history[$i]);
			//update stock total inventory (QTY Reserved)
		}
		
	}
}