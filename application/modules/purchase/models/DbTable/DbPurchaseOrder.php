<?php

class purchase_Model_DbTable_DbPurchaseOrder extends Zend_Db_Table_Abstract
{	
	//get update order but not well
	public function getPurchaseID($id){
		$db = $this->getAdapter();
		$sql = "SELECT CONCAT(p.item_name,'(',p.item_code,' )') AS item_name , p.qty_perunit,od.order_id, od.pro_id, od.qty_order,
				
		od.price, od.total_befor, od.disc_type,	od.disc_value, od.sub_total, od.remark 
				
		FROM tb_purchase_order_item AS od
				
		INNER JOIN tb_product AS p ON p.pro_id=od.pro_id WHERE od.order_id=".$id;
		$row = $db->fetchAll($sql);
		return $row;
	}
	//get purchase info //23/8/13
	public function purchaseInfo($id){
		$db=$this->getAdapter();
		$sql = "SELECT poh.history_id,poh.date,v.vendor_id,v.v_name,v.phone,v.add_name,v.contact_name,v.add_remark,ro.recieve_id,
		p.order_id,p.LocationId, p.order, p.date_order,p.date_in,p.status,p.payment_method,p.currency_id,
		p.remark,p.version,p.net_total,p.discount_type,p.discount_value,p.discount_real,p.paid,p.all_total,p.balance, SUM(poi.sub_total) as sub_total
		FROM 
				tb_purchase_order_item as poi,
				tb_purchase_order AS p 
		INNER JOIN 
				tb_vendor AS v ON v.vendor_id= p.vendor_id
		INNER JOIN tb_purchase_order_history as poh ON poh.order = p.order_id
		INNER JOIN tb_recieve_order as ro ON ro.order_id = p.order_id
		WHERE poi.order_id = p.order_id and p.order_id=".$id." LIMIT 1";
		$rows=$db->fetchRow($sql);
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