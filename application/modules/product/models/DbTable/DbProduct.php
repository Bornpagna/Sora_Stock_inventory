<?php

class Product_Model_DbTable_DbProduct extends Zend_Db_Table_Abstract
{
    protected $_name = 'tb_product';
    
    
    public function setName($name)
    {
    	$this->_name=$name;
    }
    /* @Desc: add Order
     * @Author: May Dara
    * */
   //get categetory 8/22/13
    protected function GetuserInfo(){
    	$user_info = new Application_Model_DbTable_DbGetUserInfo();
    	$result = $user_info->getUserInfo();
    	return $result;
    }
  public function getCategory($id){
    	$db=$this->getAdapter();
    	$sql="SELECT CategoryId, parent_id, Name,IsActive
    	FROM tb_category WHERE  CategoryId =".$id." LIMIT 1";
    	$rows=$db->fetchRow($sql);  
    	return $rows;  	
  }
  public function getBrandName($id){
  	$db=$this->getAdapter();
  	$sql="SELECT branch_id, parent_id, Name,IsActive
  	FROM tb_branch WHERE  branch_id =".$id." LIMIT 1";
  	$rows=$db->fetchRow($sql);
  	return $rows;
  }
     //add catogory 8/22/13
   public function addCategory($data) {
    	$db = $this->getAdapter();
    	//print_r($data); exit();
    	$category= array(
    			"parent_id" =>$data['ParentCategory'],
    			"Name"=>    $data['CategoryName'],
    			"Timestamp"=> new Zend_Date()
    	);
    	$db->insert("tb_category",$category);
      }
      public function addBrand($data) {
      	$db = $this->getAdapter();
      	$_arr= array(
      			"parent_id" => $data['Parentbrand'],
      			"Name"		=> $data['brandName'],
      			"Timestamp"	=> new Zend_Date()
      	);
      	$db->insert("tb_branch",$_arr);
      }
     //get product qty location
    public function getOrderItemDetailByID($id){//get qty all location
    	$db = $this->getAdapter();
    	$user = $this->GetuserInfo();
    	$itemSql = "SELECT pl.ProLocationID, pl.LocationId, pl.qty,pl.`qty_warn`
    	FROM tb_prolocation AS pl
    	WHERE pl.pro_id =".$db->quote($id);
    	if($user["level"]!=1){
			$itemSql.=" AND pl.LocationId = ".$user["location_id"];    		
    	}
    	$rows = $db->fetchAll($itemSql);
    	return $rows;
    }
    
    public function getOrderItemVeiw($id){
    	$db = $this->getAdapter();
    	$user = $this->GetuserInfo();
    	    	$itemSql = "SELECT 
							  lo.Name,
							  p.pro_id,
							  p.cate_id,
							  p.item_name,
							  p.`qty_onhand`,
							  p.`qty_available`,
							  p.`qty_onorder`,
							  p.`qty_onsold`,
  							  pl.`qty_warn`,
							  pl.ProLocationID,
							  pl.pro_id,
							  pl.LocationId,
							  pl.qty,
							  pl.`qty_avaliable`,
							  pl.`qty_onorder` AS plqty_order,
							  pl.`qty_onsold` AS plqty_onsold
							
							FROM
							  tb_product AS p 
							  INNER JOIN tb_prolocation AS pl 
							    ON pl.pro_id = p.pro_id 
							  INNER JOIN tb_sublocation AS lo 
							    ON lo.LocationId = pl.LocationId 
							WHERE p.pro_id =".$id;
    	    	
//     	    	$itemSql = "SELECT lo.Name, p.pro_id, p.cate_id, p.item_name,pl.ProLocationID, pl.pro_id, pl.LocationId, pl.qty,
//     	    				pt.QuantityOnHand,pt.QuantityOnOrder,pt.QuantitySold
//     						FROM tb_product AS p
//     						INNER JOIN tb_prolocation AS pl ON  pl.pro_id  = p.pro_id
//     						INNER JOIN tb_inventorytotal AS pt ON  pt.ProdId  = p.pro_id
//     						INNER JOIN tb_sublocation AS lo ON  lo.LocationId = pl.LocationId
//     						WHERE p.pro_id =".$id;
    	    	if($user["level"]!=1){
    	    		$itemSql.= " AND pl.LocationId = ".$user["location_id"];
    	    	}
    	$rows = $db->fetchAll($itemSql);
    	return $rows;
    }
    //get product info 22/8/13
    public function getProductInfo($id){
    	$db=$this->getAdapter();
    	$sql = "SELECT 
					  p.pro_id,
					  p.cate_id,
					  p.brand_id,
					  p.stock_type,
					  p.item_name,
					  p.item_code,
					  p.item_size,
					  p.purchase_tax,
					  p.sale_tax,
					  measure_id,
					  label,
					  qty_perunit,
					  p.unit_sale_price,
					  qty_onhand,
					  p.photo,
					  p.is_avaliable,
					  p.remark 
					FROM
					  tb_product AS p 
					WHERE p.pro_id =".$db->quote($id)." LIMIT 1";
    	$rows = $db->fetchRow($sql);
    	return ($rows);
    }
    public function getProductInfoDetail($id){//for view item detail
    	$db=$this->getAdapter();
    	$sql = "SELECT p.pro_id,p.cate_id,p.stock_type,p.item_name,p.item_code,p.price_per_qty,p.brand_id,
    	p.photo,p.is_avaliable,p.remark,c.Name,b.Name As branch_name
    	FROM tb_product AS p
    	INNER JOIN tb_category AS c ON c.CategoryID=p.cate_id
    	INNER JOIN tb_branch AS b ON b.branch_id=p.brand_id
    	WHERE p.pro_id=".$id." LIMIT 1";
    	$rows = $db->fetchRow($sql);
    	return ($rows);
    }
    // for get product info 8/22/13
    public function getProductStock($id){
    	$db= $this->getAdapter();
    	$sql_inventory="SELECT qty_onhand, qty_available,qty_onorder,qty_onsold
    	FROM tb_product WHERE pro_id= ".$id." LIMIT 1";
    	$rows = $db->fetchRow($sql_inventory);
    	return $rows;
    }
    //select before 10-7-13
//     public function getSaleHistory($id){
//     	$db= $this->getAdapter();
//     	$sql_sale="SELECT os.history_id, os.type, so.order, os.customer_id, os.date, os.status, os.order_total, os.qty, os.unit_price, os.sub_total, os.customer_id
//     	FROM tb_order_history AS os
//     	LEFT JOIN tb_purchase_order AS po ON os.order = po.order_id
//     	WHERE pro_id=".$id." ORDER BY os.history_id  DESC ";
//     	$rows = $db->fetchAll($sql_sale);
//     	return $rows;
//     }
    // for get product order history 8/22/13
    public function getSaleHistory($id){
    	$db= $this->getAdapter();
    	$sql_sale="SELECT os.history_id, os.type, r.order, cus.cust_name, os.date, os.status, os.order_total, os.qty, os.unit_price, os.sub_total
		 FROM tb_order_history AS os
		 INNER JOIN tb_customer AS cus ON 
		 	cus.customer_id = os.customer_id
		 LEFT JOIN tb_sales_order AS r ON os.order = r.order_id
    	 WHERE pro_id=".$id." ORDER BY os.history_id  DESC ";
    	$rows = $db->fetchAll($sql_sale);
    	return $rows;
    }
    //for select purchase history
    public function getPurchaseHistory($id){
    	$db= $this->getAdapter();
    	$sql_purchase="SELECT ph.history_id, ph.type, pur.order, v.v_name, ph.date, ph.status, ph.order_total, ph.qty, ph.unit_price, ph.sub_total
					FROM tb_purchase_order_history AS ph
					INNER JOIN tb_vendor AS v ON v.vendor_id = ph.customer_id
					LEFT JOIN tb_purchase_order AS pur ON ph.order = pur.order_id
					WHERE pro_id =".$id."
					ORDER BY ph.history_id DESC ";
    	$rows = $db->fetchAll($sql_purchase);
    	return $rows;
    }
    public function moveproduct($id){
    	$db=$this->getAdapter();
    	$user = $this->GetuserInfo();
    	$sql_move = "SELECT h.history_id, h.transaction_type, h.date, l.Name,
    	h.qty_edit, h.qty_before, h.qty_after,h.Remark, u.username FROM tb_move_history AS h
    	INNER JOIN tb_sublocation AS l ON l.LocationId = h.location_id
    	INNER JOIN rsv_acl_user as u ON u.user_id=h.user_mod
    	WHERE pro_id=".$id;
    	if($user["level"]!=1){
    		$sql_move.=" AND h.location_id = ".$user["location_id"];
    	}
    	$sql_move.=" ORDER BY h.history_id DESC ";
    	$rows=$db->fetchAll($sql_move);
    	return $rows;
    }

    public function getProductVendor($id){
    	$db=$this->getAdapter();
    	$sql_move = "SELECT pro_id FROM tb_purchase_order_item
        WHERE pro_id =".$id;
    	$rows=$db->fetchAll($sql_move);
    	return $rows;
    }
    /**
    * Update Order item
    * @param array $itemsData
    * @author May Dara
    */
//     public function UpdateOrderItem($itemsData){
// 	    $db = $this->getAdapter();
// 	    $dataInfo = array(
// 	    'name' => $itemsData['name'],
// 	    'purchase_code' => $itemsData['purchase_code'],
// 	    'status' => $itemsData['status'],
// 	    'description' => $itemsData['description'],
// 	    'stock_id' => $itemsData['stock_id'],
// 	    'vendor_id' => $itemsData['vendor_id'],
// 	    'discount_type' => $itemsData['discount_type'],
// 	    'discount_value' => $itemsData['discount_value'],
// 	    'shipping_id' => $itemsData['shipping_id'],
// 	    'shipping_charge' => $itemsData['shipping_charge'],
// 	    'assign_contact' => $itemsData['assign_contact'],
// 	    'order_date' => $itemsData['order_date'],
// 	    'net_total' => $itemsData['net_total'],
// 	    'all_total' => $itemsData['all_total']
// 	    );
// 	    $where=$this->getAdapter()->quoteInto('id=?',$itemsData['id']);
// 	    $this->update($dataInfo,$where);
	    
// 	    		$this->_name = "rsmk_purchase_item";
// 	    		$this->delete("purchase_id = " . $itemsData['id']);
// 	    		$identitys = explode(',',$itemsData['identity']);
// 	    		foreach($identitys as $i){
// 	    		$data = array(
// 		    		'purchase_id'  => $itemsData['id'],
// 		    		'item_id' 	   => $itemsData['item_id_'.$i],
// 		    		'qty_purchase' => $itemsData['qty'.$i],
// 		   			'price'        => $itemsData['price'.$i],
// 			    	'dis-type'     => $itemsData['dis-type-'.$i],
// 			    	'dis-value'    => $itemsData['dis-value'.$i]
// 	    	);
// 	    	$this->insert($data);
// 	    }
//     }
	public function getTransferInfo($id){
		$db=$this->getAdapter();
		$sql = "SELECT * FROM tb_stocktransfer WHERE transfer_id = ".$id." LIMIT 1";
		$row = $db->fetchRow($sql);
		return $row;
	}
	public function getTransferItem($id){
		$db=$this->getAdapter();
		$sql = "SELECT * FROM tb_transfer_item WHERE transfer_id = ".$id;
		$rows = $db->fetchAll($sql);
		return $rows;
	}
    
}