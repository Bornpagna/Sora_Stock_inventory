<?php 
Class report_Model_DbQuery extends Zend_Db_Table_Abstract{
	
	protected function GetuserInfo(){
		$user_info = new Application_Model_DbTable_DbGetUserInfo();
		$result = $user_info->getUserInfo();
		return $result;
	}
	
	public function getItem($data){
		$db = $this->getAdapter();
    	$sql = "SELECT p.pro_id, p.item_name,p.item_code FROM tb_product AS p ";
			
    		if($data["LocationId"]!="" OR $data["LocationId"]!=0){
    			$sql.= " INNER JOIN tb_prolocation AS pl ON pl.pro_id = p.pro_id AND pl.`LocationId`= ".trim($data["LocationId"]);
    		}
    		$sql.="WHERE p.item_name!=''";
    		
			if($data["branch_id"]!="" OR $data["branch_id"]!=0){
				$sql.= " AND p.`brand_id`=".trim($data["branch_id"]);
			}
			if($data["category_id"]!="" OR $data["category_id"]!=0){
				$sql.=" AND p.`cate_id`=".trim($data["category_id"]);
			}
			$sql.=" ORDER BY p.item_name";
			echo $sql;
			return $db->fetchAll($sql);
	}
	
	public function getSalesItem($data){
		$db=$this->getAdapter();
		//$start_date = strtotime($start);
		//$end_date =strtotime($end)+86400;//bonus 24h/day
		
// 		echo"after ";
// 		echo $sec=strtotime($star);
// 		echo "time to date".date('Y/m/d H:i:s', $sec);exit();
		
		$start_date = trim($data["start_date"]);
		$end_date = trim($data["end_date"]);
// 		if($start_date=="" OR $end_date=""){
// 			$start_date = $lastMonth;
// 			$end_date = new Zend_Date();
// 		}else{
// 			$start_date = trim($data["start_date"]);
// 			$end_date = trim($data["end_date"]);
// 		}
		
// 		$sql = "SELECT p.item_name, p.item_code, SUM( si.qty_order ) AS qty,br.Name AS Brand,cg.Name AS cate_name,s.`order`,
//   s.`date_order`
// 					FROM tb_product AS p,tb_category As cg,tb_branch AS br, tb_sales_order_item AS si, tb_sales_order AS s
// 					WHERE p.pro_id = si.pro_id
// 					AND cg.CategoryId=p.cate_id
// 					AND br.branch_id=p.brand_id
// 					AND si.order_id = s.order_id AND s.status=4 AND s.date_order BETWEEN '$start_date' AND '$end_date'";
		$sql= "SELECT 
				  p.item_name,
				  p.item_code,
				  p.`qty_onhand`,
				  SUM(si.qty_order) AS qty_order,
				  br.Name AS Brand,
				  cg.Name AS cate_name,
				  s.`order`,
				  s.`date_order` ,
				  (SELECT 
				    NAME 
				  FROM
				    tb_sublocation 
				  WHERE `LocationId` = s.`LocationId`) AS branch,
				  pl.qty 
				FROM
				  tb_product AS p,
				  tb_category AS cg,
				  tb_branch AS br,
				  tb_sales_order_item AS si,
				  tb_sales_order AS s ,
				  tb_prolocation AS pl
				WHERE p.pro_id = si.pro_id 
				  AND cg.CategoryId = p.cate_id 
				  AND br.branch_id = p.brand_id 
				  AND si.order_id = s.order_id 
				  AND pl.`pro_id`=si.`pro_id`
				  AND si.order_id = s.order_id 
				  
				  AND s.date_order BETWEEN '$start_date' 
				  AND '$end_date' ";
			if(($data["item"]!="" AND $data["item"]!=0 )){
				//$sql.=" AND "."(p.item_name LIKE '%".trim($data["item"])."%' OR p.item_code LIKE '%".trim($data["item"]."%')");
				$sql.=" AND p.pro_id = ".trim($data["item"]);
			}
			if($data["category_id"]!="" AND $data["category_id"]!=0){
				$sql.=" AND cg.CategoryId = ".trim($data["category_id"]);
			}
			if($data["branch_id"]!="" AND $data["branch_id"]!=0){
				$sql.=" AND br.branch_id = ".trim($data["branch_id"]);
			}
			if($data["LocationId"]!="" AND $data["LocationId"]!=0){
				$sql.=" AND s.LocationId = ".trim($data["LocationId"]);
			}
			$result =  $this->GetuserInfo();
			if ($result["level"]!=1 AND $result["level"]!=2 ){
				$sql.=" AND s.LocationId = ".trim($result["location_id"]);
			}
			//echo $sql;
			
		$sql.=" GROUP BY si.id ORDER BY s.date_order DESC";
		return $db->fetchAll($sql);
	}
	public function getProductSummary($data){
		$db=$this->getAdapter();
		$start_date = trim($data["start_date"]);
		$end_date = trim($data["end_date"]);
		
		$sql_po=" SELECT po.LocationId, po.date_in, po.status, po.is_active, pi.pro_id, SUM( pi.qty_order ) AS qty_order
							FROM tb_purchase_order po, tb_purchase_order_item pi
							WHERE po.order_id = pi.order_id
							AND po.date_in
							BETWEEN  '$start_date'
							AND  '$end_date' ";
		$sql_so = " SELECT so.LocationId, so.date_order, so.status, so.is_active, si.pro_id, SUM( si.qty_order ) AS qty_sales
							FROM tb_sales_order so, tb_sales_order_item si
							WHERE so.order_id = si.order_id
							AND so.date_order
							BETWEEN  '$start_date'
							AND  '$end_date'  ";
		
		if($data["LocationId"]!="" AND $data["LocationId"]!=0){
			$sql_po.=" AND po.LocationId = ".trim($data["LocationId"]);
			$sql_so.=" AND so.LocationId = ".trim($data["LocationId"]);
		}
		
		$sql_po.=" GROUP BY pi.pro_id ";
		$sql_so.=" GROUP BY si.pro_id";
		
		$sql_vpo = " CREATE OR REPLACE VIEW v_po AS ".$sql_po;
		$sql_vso = " CREATE OR REPLACE VIEW v_so AS ".$sql_so;
						
		$db->query($sql_vpo);
		$db->query($sql_vso);
		
		$sql=" SELECT p.`pro_id` , p.item_name, p.item_code, po.qty_order AS qty_po, so.qty_sales AS qty_so,p.`qty_onhand`,p.`qty_onorder`,p.`qty_onsold`
					FROM tb_product AS p
					LEFT JOIN v_so AS so ON p.pro_id = so.pro_id ";
		if(($data["item"]!="" AND $data["item"]!=0 )){
			$sql.=" INNER JOIN v_po AS po ON p.pro_id = po.pro_id AND p.pro_id = ".trim($data["item"]);
		}
		else{
			$sql.=" LEFT JOIN v_po AS po ON p.pro_id = po.pro_id ";
		}
		if($data["category_id"]!="" AND $data["category_id"]!=0){
			$sql.=" AND p.cate_id = ".trim($data["category_id"]);
		}
		if($data["branch_id"]!="" AND $data["branch_id"]!=0){
			$sql.=" AND p.brand_id = ".trim($data["branch_id"]);
		}
		
			
		$sql.=" GROUP BY p.pro_id ORDER BY p.item_name  ";
		return $db->fetchAll($sql);
	}
	//get location name for report at other location
	public function getLocationName($location_id){
		$db=$this->getAdapter();
		$sql="SELECT Name FROM tb_sublocation WHERE LocationId = ".$location_id;
		$row=$db->fetchRow($sql);
		return $row;
	}
	public function getBrandByUser(){
		$db=$this->getAdapter();
		$user = $this->GetuserInfo();
		if($user["level"]==3 OR $user["level"]==4 ){
			$sql="SELECT Name FROM tb_sublocation WHERE LocationId = ".$user["location_id"];
			$row=$db->fetchRow($sql);
			return $row;
		}
		else{
			return false;
		}
		
	}
	public function getPurchaseItem($data){
		$db=$this->getAdapter();
		$start_date = trim($data["start_date"]);
		$end_date = trim($data["end_date"]);
		
// 		$sql = "SELECT p.item_name, p.item_code, SUM( pi.qty_order ) AS qty,br.Name AS Brand,cg.Name AS cate_name
// 				FROM tb_product AS p,tb_category As cg,tb_branch AS br, tb_purchase_order_item AS pi, tb_purchase_order AS pur
// 				WHERE p.pro_id = pi.pro_id
// 				AND cg.CategoryId=p.cate_id
// 				AND br.branch_id=p.brand_id
// 				AND pi.order_id = pur.order_id
				
// 				AND pur.date_in BETWEEN '$start_date' AND '$end_date'";
		$sql= "SELECT 
				  pur.order_id,
				  pur.`order`, 
				  p.item_name,
				  p.item_code,
				  p.qty_onhand,
				  pur.`date_in`,
				  SUM(pio.qty_order) AS qty_order,
				  br.Name AS Brand,
				  cg.Name AS cate_name,
				  (SELECT 
				    NAME 
				  FROM
				    tb_sublocation 
				  WHERE `LocationId` = pur.`LocationId`) AS branch ,
				  pl.qty as qty_location
				FROM
				  tb_product AS p,
				  tb_category AS cg,
				  tb_branch AS br,
				  tb_purchase_order_item AS pio,
				  tb_purchase_order AS pur,
				  tb_prolocation AS pl 
				WHERE p.pro_id = pio.pro_id 
				  AND cg.CategoryId = p.cate_id 
				  AND br.branch_id = p.brand_id 
				  AND pl.`LocationId`=pur.`LocationId`
				  AND pl.`pro_id`=pio.`pro_id`
				  AND pio.order_id = pur.order_id 
				  AND pur.`date_in` BETWEEN '$start_date' AND '$end_date'";
				
		
			if(($data["item"]!="" AND $data["item"]!=0 )){
				$sql.=" AND p.pro_id = ".trim($data["item"]);
			}
			if($data["category_id"]!="" AND $data["category_id"]!=0){
				$sql.=" AND cg.CategoryId = ".trim($data["category_id"]);
			}
			if($data["branch_id"]!="" AND $data["branch_id"]!=0){
				$sql.=" AND br.branch_id = ".trim($data["branch_id"]);
			}
			if($data["LocationId"]!="" AND $data["LocationId"]!=0){
				$sql.=" AND pur.LocationId = ".trim($data["LocationId"]);
			}
			
			//ORDER BY pur.`date_in` DES";
			$sql.=" GROUP BY pio.`id` ";
				$sql.=" ORDER BY pur.`order_id`,pur.`date_in` DESC ";
		return $db->fetchAll($sql);
	}
	public function getQtyTransfer($data){
		$db=$this->getAdapter();
		$start_date = trim($data["start_date"]);
		$end_date = trim($data["end_date"]);
	
// 		$sql = "SELECT p.item_name, p.item_code, SUM( ti.qty) AS qty,br.Name AS Brand,cg.Name AS cate_name
// 		FROM tb_product AS p,tb_category As cg,tb_branch AS br, tb_transfer_item AS ti, tb_stocktransfer AS t
// 			WHERE 
// 	             ti.transfer_id= t.transfer_id
// 	             AND p.pro_id = ti.pro_id
// 				 AND cg.CategoryId=p.cate_id
// 				 AND br.branch_id=p.brand_id
// 		AND t.transfer_date  BETWEEN '$start_date' AND '$end_date'";
		$sql = "SELECT 
					  p.item_name,
					  p.item_code,
					  SUM(ti.qty) AS qty,
					  t.`invoice_num`,
					  br.Name AS Brand,
					  cg.Name AS cate_name,
					  t.`transfer_date`,
					  (SELECT 
					    sl.`Name` 
					  FROM
					    `tb_sublocation` AS sl 
					  WHERE sl.`LocationId` = t.`from_location`) AS From_location,
					  (SELECT 
					    sl.`Name` 
					  FROM
					    `tb_sublocation` AS sl 
					  WHERE sl.`LocationId` = t.`to_location`) AS to_location ,
					  (SELECT 
					    u.title 
					  FROM
					    `rsv_acl_user` AS u 
					  WHERE u.user_id = t.`user_id`) AS title,
					  (SELECT 
					    u.fullname 
					  FROM
					    `rsv_acl_user` AS u 
					  WHERE u.user_id = t.`user_id`) AS user_name 
					FROM
					  tb_product AS p,
					  tb_category AS cg,
					  tb_branch AS br,
					  tb_transfer_item AS ti,
					  tb_stocktransfer AS t,
					  tb_sublocation AS sl 
					WHERE ti.transfer_id = t.transfer_id 
					  AND p.pro_id = ti.pro_id 
					  AND cg.CategoryId = p.cate_id 
					  AND br.branch_id = p.brand_id 
					  AND t.transfer_date BETWEEN '$start_date' 
					  AND '$end_date' ";
		
		if(($data["item"]!="" AND $data["item"]!=0 )){
			$sql.=" AND p.pro_id = ".trim($data["item"]);
		}
		if($data["category_id"]!="" AND $data["category_id"]!=0){
			$sql.=" AND cg.CategoryId = ".trim($data["category_id"]);
		}
		if($data["branch_id"]!="" AND $data["branch_id"]!=0){
			$sql.=" AND br.branch_id = ".trim($data["branch_id"]);
		}
		if($data["LocationId"]!="" AND $data["LocationId"]!=0){
			$sql.=" AND t.From_location = ".trim($data["LocationId"]);
		}
		
		if($data["to_LocationId"]!="" AND $data["to_LocationId"]!=0){
			$sql.=" AND t.to_location = ".trim($data["to_LocationId"]);
		}
		$result =  $this->GetuserInfo();
		if ($result["level"]!=1 AND $result["level"]!=2 ){
			$sql.=" AND t.to_location = ".trim($result["location_id"]);
		}
			
		$sql.=" GROUP BY transfer_no,p.pro_id ";
		return $db->fetchAll($sql);
	}
	
	public function geProducttQty($data){
		$db=$this->getAdapter();
		$start_date = trim($data["start_date"]);
		$end_date = trim($data["end_date"]);
	
		// 		$sql = "SELECT p.item_name, p.item_code, SUM( ti.qty) AS qty,br.Name AS Brand,cg.Name AS cate_name
		// 		FROM tb_product AS p,tb_category As cg,tb_branch AS br, tb_transfer_item AS ti, tb_stocktransfer AS t
		// 			WHERE
		// 	             ti.transfer_id= t.transfer_id
		// 	             AND p.pro_id = ti.pro_id
		// 				 AND cg.CategoryId=p.cate_id
		// 				 AND br.branch_id=p.brand_id
		// 		AND t.transfer_date  BETWEEN '$start_date' AND '$end_date'";
		$sql = "SELECT 
				  pl.pro_id,
				  p.`item_name`,
				  p.`item_code`,
				  pl.qty,
				  pl.`qty_avaliable`,
				  pl.`qty_onorder`,
				  pl.`qty_onsold`,
				  pl.`LocationId` ,
				  (SELECT p.`qty_onhand` FROM tb_product AS p WHERE p.pro_id =pl.pro_id) AS all_qty,
				  (SELECT sl.Name FROM `tb_sublocation` AS sl WHERE sl.LocationId = pl.`LocationId`) AS location,
				  b.`Name` AS branch,
				  c.`Name` AS category
				FROM
				  tb_prolocation  AS pl,
				  `tb_branch` AS b,
				  `tb_category` AS c,
				  tb_product AS p
				  WHERE b.`branch_id`=p.`brand_id`
				  AND c.`CategoryId`=p.`cate_id`
				  AND pl.`pro_id`=p.`pro_id`";	
					
		if(($data["item"]!="" AND $data["item"]!=0 )){
		$sql.="AND p.pro_id = ".trim($data["item"]);
	}
	if($data["category_id"]!="" AND $data["category_id"]!=0){
	$sql.=" AND c.`CategoryId` = ".trim($data["category_id"]);
	}
			if($data["branch_id"]!="" AND $data["branch_id"]!=0){
			$sql.=" AND b.`branch_id` = ".trim($data["branch_id"]);
	}
	if($data["LocationId"]!="" AND $data["LocationId"]!=0){
	$sql.=" AND LocationId = ".trim($data["LocationId"]);
	}
			$result =  $this->GetuserInfo();
			if ($result["level"]!=1 AND $result["level"]!=2 ){
	$sql.=" AND LocationId = ".trim($result["location_id"]);
	}
			
		$sql.=" ORDER BY pl.pro_id ";
		return $db->fetchAll($sql);
	}
	public function getTopTenProductSO(){
		$db = $this->getAdapter();
		$sql = " SELECT  p.item_name, SUM( si.qty_order ) AS qty
					FROM tb_product AS p,tb_sales_order_item AS si, tb_sales_order AS s
					WHERE p.pro_id = si.pro_id
					AND si.order_id = s.order_id AND s.status=4 AND s.date_order  >= DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 MONTH)), INTERVAL 1 DAY)
		 GROUP BY si.pro_id ORDER BY qty DESC LIMIT 10 ";
		$rows = $db->fetchAll($sql);
		return $rows;
	}
	
	
	public function getTopTenProductSOByDate($data){
		$db = $this->getAdapter();
		$start_date = trim($data["start_date"]);
		$end_date = trim($data["end_date"]);
		$sql = " SELECT  p.item_name, SUM( si.qty_order ) AS qty
		FROM tb_product AS p,tb_sales_order_item AS si, tb_sales_order AS s
		WHERE p.pro_id = si.pro_id
		AND si.order_id = s.order_id AND s.status=4 AND s.date_order  BETWEEN '$start_date' AND '$end_date'
		GROUP BY si.pro_id ORDER BY qty DESC LIMIT 10 ";
		$rows = $db->fetchAll($sql);
		return $rows;
	}
	
	public function getTopTenProductPO(){
		$db = $this->getAdapter();
		$sql = " SELECT p.item_name, SUM( pi.qty_order ) AS qty
					FROM tb_product AS p,tb_purchase_order_item AS pi, tb_purchase_order AS pur
				WHERE p.pro_id = pi.pro_id
				AND pi.order_id = pur.order_id
				AND pur.status = 4
				AND pur.date_in >= DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 MONTH)), INTERVAL 1 DAY)
		                GROUP BY pi.pro_id ORDER BY qty DESC LIMIT 10 ";
		$rows = $db->fetchAll($sql);
		return $rows;
	}
	
}

?>