<?php

class Application_Model_GlobalClass  extends Zend_Db_Table_Abstract
{

	/**
	 * replace value of statu by image
	 * @param array $rows
	 * @param string $base_url
	 * @param bool  $case 
	 * @return array
	 * @uses must have status field in $rows
	 */
	public function getImgStatus($rows,$base_url, $case=''){
		if($rows){			
			$imgnone='<img src="'.$base_url.'/images/icon/cross.png"/>';
			$imgtick='<img src="'.$base_url.'/images/icon/tick.png"/>';
			 
			foreach ($rows as $i =>$row){
				if($row['status'] == 1){
					$rows[$i]['status'] = $imgtick;
				}
				else{
					$rows[$i]['status'] = $imgnone;
				}
			}
		}
		return $rows;
	}
	
	public function getImgActive($rows,$base_url, $case=''){
		if($rows){
			$imgnone='<img src="'.$base_url.'/images/icon/cross.png"/>';
			$imgtick='<img src="'.$base_url.'/images/icon/apply2.png"/>';
	
			foreach ($rows as $i =>$row){
				if($row['is_avaliable'] == 1){
					$rows[$i]['is_avaliable'] = $imgtick;
				}
				else{
					$rows[$i]['is_avaliable'] = $imgnone;
				}
			}
		}
		return $rows;
	}
	//////get stock type
	public function getStockType($rows,$base_url, $case=''){
		if($rows){
			$stockable = "Stockable";
			$nonestock = "None Stock";
			$service = "Service";
	
			foreach ($rows as $i =>$row){
				if($row['type'] == 1){
					$rows[$i]['type'] = $stockable;
				}
				elseif($row['type'] == 2){
					$rows[$i]['type'] = $nonestock;
				}
				else{
					$rows[$i]['type'] = $service;
				}
			}
		}
		return $rows;
	}
	
	public function getActive($rows,$base_url, $case=''){
		if($rows){
			$imgnone='<img src="'.$base_url.'/images/icon/cross.png"/>';
			$imgtick='<img src="'.$base_url.'/images/icon/tick.png"/>';
	
			foreach ($rows as $i =>$row){
				if($row['IsActive'] == 1){
					$rows[$i]['IsActive'] = $imgtick;
				}
				else{
					$rows[$i]['IsActive'] = $imgnone;
				}
			}
		}
		return $rows;
	}
	public function getpublic($rows,$base_url, $case=''){
		if($rows){
			$imgnone='<img src="'.$base_url.'/images/icon/cross.png"/>';
			$imgtick='<img src="'.$base_url.'/images/icon/tick.png"/>';
	
			foreach ($rows as $i =>$row){
				if($row['public'] == 1){
					$rows[$i]['public'] = $imgtick;
				}
				else{
					$rows[$i]['public'] = $imgnone;
				}
			}
		}
		return $rows;
	}
	//get transaction type
	public function getTransactionType($rows,$base_url, $case=''){
		if($rows){
			//$adjust    = "Stock Adjustment";
			//$transfer_stock = "Stock Transfer";
			//$received  = "Received";
			//$return  = "Return Stock Out(V)";
			foreach ($rows as $i =>$row){
				if($row['transaction_type'] == 1){
					$rows[$i]['transaction_type'] = "Stock Adjustment";
				}
				elseif($row['transaction_type'] == 2){
					$rows[$i]['transaction_type'] = "Stock Transfer";
				}
				elseif($row['transaction_type'] == 3){
					$rows[$i]['transaction_type'] = "Received";
				}
				elseif($row['transaction_type'] == 4){
					$rows[$i]['transaction_type'] = "Return Stock Out(V)";
				}
				elseif($row['transaction_type'] == 5){
					$rows[$i]['transaction_type'] = "Return Stock In(V)";
				}
				elseif($row['transaction_type'] == 6){
					$rows[$i]['transaction_type'] = "Return Stock Out(C)";
				}
				else{
					$rows[$i]['transaction_type'] = "Return Stock In(C)";
				}
				
			}
		}
		return $rows;
	}
	
	public function getStatusType($rows,$base_url, $case=''){
		if($rows){
			foreach ($rows as $i =>$row){
				if($row['status'] == 1){
					$rows[$i]['status'] = "Quote";
				}
				elseif($row['status'] == 2){
					$rows[$i]['status'] =  "Open";	
				}
				elseif($row['status'] == 3){
					$rows[$i]['status'] = "In Progress";		
				}
				elseif($row['status'] == 4){
					$rows[$i]['status'] = "Paid";
				}
				elseif($row['status'] == 5){
					$rows[$i]['status'] = "Fully Received";
				}
				else{
					$rows[$i]['status'] = "Cancelled";
				}
			}
		}
		return $rows;
	}
	
	public function getSaleStatusType($rows,$base_url, $case=''){
		if($rows){
			foreach ($rows as $i =>$row){
				if($row['status'] == 1){
					$rows[$i]['status'] = "Quote";
				}
				elseif($row['status'] == 2){
					$rows[$i]['status'] =  "Open";
				}
				elseif($row['status'] == 3){
					$rows[$i]['status'] = "In Progress";
				}
				elseif($row['status'] == 4){
					$rows[$i]['status'] = "Paid";
				}
				elseif($row['status'] == 5){
					$rows[$i]['status'] = "Fully Delivery";
				}
				else{
					$rows[$i]['status'] = "Cancelled";
				}
			}
		}
		return $rows;
	}
	
	public function getReturnStatusType($rows,$base_url, $case=''){
		if($rows){
			foreach ($rows as $i =>$row){
				if($row['status'] == 1){
					$rows[$i]['status'] = "Open";
				}
				elseif($row['status'] == 0){
					$rows[$i]['status'] =  "Returned";
				}
				
				else{
					$rows[$i]['status'] = " ";
				}
			}
		}
		return $rows;
	}
	///get type history order
	public function getTypeHistory($rows,$base_url, $case=''){
		if($rows){
			$purchase_order = "Purchase Order";
			$sales_order = "Sales Order";
			$service = "Service";
	
			foreach ($rows as $i =>$row){
				if($row['type'] == 1){
					$rows[$i]['type'] = $purchase_order;
				}
				elseif($row['type'] == 2){
					$rows[$i]['type'] = $sales_order;
				}
				else{
					$rows[$i]['type'] = $service;
				}
			}
		}
		return $rows;
	}
	
	/**
	 * replace value of sex
	 * @param array $rows
	 * @param array $values
	 * @return array
	 */
	public function getStatus($rows, $field, $values){
		if($rows){
			foreach ($rows as $i =>$row){
				for($x=1; $x<= count($values); $x++) {
					if($rows[$i][$field] == $x) {
						$rows[$i][$field] = $values[$x];
					}
				}
			}
		}
		return $rows;
	}
	
	public function interestingCurrentStock($rows,$qtyStock,$qtyDemand) {
		if($rows){
			foreach ($rows as $i =>$row){
				if($rows[$i][$qtyStock] < $rows[$i][$qtyDemand]) {
					$rows[$i][$qtyStock] = '<span style="color:red">'.$rows[$i][$qtyStock].'</span>';
				}
			}
		}
		return $rows;
	}
	
	/**
	 * replace value of sex
	 * @param array $rows
	 * @return array
	 * @uses must have status field in $rows
	 */
	public function getSex($rows){
		if($rows){
			foreach ($rows as $i =>$row){
				if($row['sex'] == 1){
					$rows[$i]['sex'] = 'Male';
				}
				else{
					$rows[$i]['sex'] = 'Female';
				}
			}
		}
		return $rows;
	}
	
	
	/**
	 * add datepicker to text box of date fields
	 * @param array $date_fields
	 */
	public static function addDateField($date_fields)
	{
		$template='$("#template").datepicker({"changeYear":"true","changeMonth":"true","yearRange":"-40:+100","dateFormat":"dd-mm-yy"});';
		$script='<script language="javascript"> $(document).ready(function() {  #template#		});</script>';
		$value='';
		if(is_array($date_fields)){
			foreach($date_fields as $read){
				$value.=str_replace('#template', '#'.$read, $template);
			}
		}
		else{
			$value=str_replace('#template', '#'.$date_fields, $template);
		}
		echo str_replace('#template#', $value, $script);
	}
	
	/**
	 * add dateatimepicker to text box of date fields
	 * @param array $date_fields
	 */
	public static function addDateTimeField($date_fields)
	{
		$template='$("#template").datetimepicker({dateFormat: "yy-mm-dd"} );';
		$script='<script language="javascript"> $(document).ready(function() {  #template#		});</script>';
		$value='';
		if(is_array($date_fields)){
			foreach($date_fields as $read){
				$value.=str_replace('#template', '#'.$read, $template);
			}
		}
		else{
			$value=str_replace('#template', '#'.$date_fields, $template);
		}
		echo str_replace('#template#', $value, $script);
	}

	/**
	 * Get Day name With multiple Languages
	 * @param string $key
	 * @var $key ('mo', 'tu', 'we', 'th', 'fr', 'sa', 'su')
	 */
	public function getDayName($key = ''){
		$tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$day_name = array(
							'su' => $tr->translate('SU'),
							'mo' => $tr->translate('MO'),
							'tu' => $tr->translate('TU'),
							'we' => $tr->translate('WE'),
							'th' => $tr->translate('TH'),
							'fr' => $tr->translate('FR'),
							'sa' => $tr->translate('SA')							
						 );
		if(empty($key)){
			return $day_name;
		}
		return  $day_name[$key];
	}
	
	/**
	 * Get all Hour per day
	 * @param int $key
	 * @return multitype:string |Ambigous <string>
	 * @var $key = [0-23]
	 */
	public function getHours($key = ''){
		$tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$am = $tr->translate('AM');
		$pm = $tr->translate('PM');
		$hours = array(
				'12:00 '. $pm,
				'01:00 '. $am,
				'02:00 '. $am,
				'03:00 '. $am,
				'04:00 '. $am,
				'05:00 '. $am,
				'06:00 '. $am,
				'07:00 '. $am,
				'08:00 '. $am,
				'09:00 '. $am,
				'10:00 '. $am,
				'11:00 '. $am,
				'12:00 '. $am,
				'01:00 '. $pm,
				'02:00 '. $pm,
				'03:00 '. $pm,
				'04:00 '. $pm,
				'05:00 '. $pm,
				'06:00 '. $pm,
				'07:00 '. $pm,
				'08:00 '. $pm,
				'09:00 '. $pm,
				'10:00 '. $pm,
				'11:00 '. $pm				
				); 
		if(empty($key)){
			return $hours;
		}
		return  $hours[$key];
	}

	
	/**
	 * get phone number in format
	 * @param string $str
	 * @return string
	 */
	public static function getPhoneNumber($str)
	{
		$str = str_replace(" ", "", $str);
		$firt = substr($str, 0,3);
		$second = substr($str, 3, strlen($str)-3);
		$phone = $firt." ".$second;
		return $phone;
	}

	public function getMaterialOption(){
		$db = $this->getAdapter();
		$auth = new Zend_Session_Namespace('auth');
		$sql = "SELECT id, `name_en` FROM `fi_cs_items` where cso_id=". $auth->cso_id;
		$option = '<option value="" label="--- Select ---">--- Select ---</option>';
		foreach($db->fetchAll($sql) as $value){
		    $option .= '<option value="'.$value['id'].'" label="'.htmlspecialchars($value['name_en'], ENT_QUOTES).'">'.htmlspecialchars($value['name_en'], ENT_QUOTES).'</option>';
		}
		return $option;
	   }
	//End sophen add
	
	 public function getYesNoOption(){
		//Select Public for report
			$myopt = '<option value="" label="---Select----">---Select----</option>';
			$myopt .= '<option value="Yes" label="Yes">Yes</option>';
			$myopt .= '<option value="No" label="No">No</option>';
	    	return $myopt;
	    	
		} 
		protected function GetuserInfoAction(){
			$user_info = new Application_Model_DbTable_DbGetUserInfo();
			$result = $user_info->getUserInfo();
			return $result;
		}
	//get location on select
	public function getLocationOption(){
		$db = $this->getAdapter();
		$user = $this->GetuserInfoAction();
		$sql = "SELECT LocationId,Name FROM tb_sublocation WHERE Name!='' AND status = 1 ";
		
		if($user["level"]!=1 AND $user["level"]!=2){
			$sql .= "AND LocationId = ".$user["location_id"];
			
		}
			$sql.=" ORDER BY LocationId DESC";
		$rows = $db->fetchAll($sql);
		$option ="";
		foreach($rows as $value){
			$option .= '<option label="'.htmlspecialchars($value['Name'], ENT_QUOTES).'" value="'.$value['LocationId'].'">'.htmlspecialchars($value['Name'], ENT_QUOTES).'</option>';
		}
		if($user["level"]==1 OR $user["level"]==2){
			//$option = '<option label="Select Location" value="">Select Location</option>';
			$option.= '<option label="Add New Location" value="-1">Add New Location</option>';
		}
		return $option;
	}
	public function getLocationAssign(){
		$db = $this->getAdapter();
		$user = $this->GetuserInfoAction();
		$sql = "SELECT LocationId,Name FROM tb_sublocation WHERE Name!='' AND status=1 ";
		$sql.=" ORDER BY LocationId DESC";
		$rows = $db->fetchAll($sql);
		$option ="";
		foreach($rows as $value){
			$option .= '<option label="'.htmlspecialchars($value['Name'], ENT_QUOTES).'" value="'.$value['LocationId'].'">'.htmlspecialchars($value['Name'], ENT_QUOTES).'</option>';
		}
		return $option;
	}
	
	public function getLocationSelected($id, $currentItem = null){
		$user = $this->GetuserInfoAction();		
		$db = $this->getAdapter();
		$sql = "SELECT l.LocationId,l.Name FROM tb_sublocation AS l ";
		if($user["level"]!=1){
			$sql .= "WHERE l.LocationId = ".$user["location_id"];
		}		
		$rows = $db->fetchAll($sql);
		$option = '';
		foreach($rows as $value){
			$option .= '<option value="'.$value['LocationId'].'" label="'.htmlspecialchars($value['Name'], ENT_QUOTES).'">'.htmlspecialchars($value['Name'], ENT_QUOTES).'</option>';
		}
		return $option;
	}
	
	public function tolocationOption(){
		$user = $this->GetuserInfoAction();
		$db = $this->getAdapter();
		$sql = "SELECT l.LocationId,l.Name FROM tb_sublocation AS l WHERE l.Name!='' AND l.status = 1";
		$rows = $db->fetchAll($sql);
		$options = '';
		foreach($rows as $value){
			$options .= '<option value="'.$value['LocationId'].'" label="'.htmlspecialchars($value['Name'], ENT_QUOTES).'">'.htmlspecialchars($value['Name'], ENT_QUOTES).'</option>';
		}
		return $options;
	}
	
	public function getProductOption(){
		$db = $this->getAdapter();
		$user_info = new Application_Model_DbTable_DbGetUserInfo();
		$result = $user_info->getUserInfo();
		$sql_cate = 'SELECT `CategoryId`,NAME FROM tb_category WHERE IsActive = 1 AND NAME!=""';
		
		$row_cate = $db->fetchAll($sql_cate);
		$user_info = new Application_Model_DbTable_DbGetUserInfo();
		$result = $user_info->getUserInfo();
		$option="";		
		foreach($row_cate as $cate){
			$option .= '<optgroup  label="'.htmlspecialchars($cate['NAME'], ENT_QUOTES).'">';
			if($result["level"]==1 OR $result["level"]==2){
				$sql = "SELECT pro_id,item_name,item_code FROM tb_product WHERE is_avaliable = 1 AND cate_id = ".$cate['CategoryId']." 
						AND item_name!='' ORDER BY last_mod_date DESC ";
			}else{
				$sql = "SELECT p.pro_id,p.item_name,p.item_code FROM tb_product AS p
				INNER JOIN tb_prolocation  As pl ON p.pro_id = pl.pro_id
				WHERE p.is_avaliable = 1 AND p.is_avaliable= 2 AND p.cate_id = ".$cate['CategoryId']."
				AND p.item_name!='' AND pl.LocationId =".$result['location_id']." ORDER BY p.last_mod_date DESC ";
			}
				$rows = $db->fetchAll($sql);
				if($rows){
					foreach($rows as $value){
						$option .= '<option value="'.$value['pro_id'].'" label="'.htmlspecialchars($value['item_name'], ENT_QUOTES).'">'.
							htmlspecialchars($value['item_name'], ENT_QUOTES)." ".htmlspecialchars($value['item_code'], ENT_QUOTES)
						.'</option>';
					}
				}
			$option.="</optgroup>";
		}
		if($result["level"]==1 OR $result["level"]==2){
			$option .= '<option value="-1" label="Add New Product">Add New Product</option>';
		}
		return $option;
	}
	 
	
	
	public function getsale($order_id){
		$db = $this->getAdapter();
		$sql_delivery = "SELECT
		sod.`delivery_no` ,
		sod.`delivery_id`,
		sod.`sale_order_id`,
		sod.`delivery_date`
		FROM
		tb_sale_order_delivery AS sod,
		tb_sales_order AS s
		WHERE sod.`sale_order_id`=s.`order_id`
		AND s.`order_id`=$order_id";
		
		$row_delivery = $db->fetchAll($sql_delivery);
		return $row_delivery;
	}
	
	public function getdelivery($delivery){
		$db = $this->getAdapter();
		$sql="SELECT
				sod.`delivery_no`,
				sdi.`pro_id`,
				sdi.`qty_deliver`,
				sdi.`qty_order`,
				sdi.`qty_remian`,
				sod.`delivery_date`,
				sdo.Sum_qty_order,
				sdo.Sum_qty_deliver,
				sdo.Sum_qty_remian,
				(SELECT
				p.item_name
				FROM
				tb_product AS p
				WHERE p.pro_id = sdi.`pro_id`) AS Item_name
				FROM
				tb_sale_order_delivery AS sod,
				tb_sale_order_delivery_item AS sdi
				INNER JOIN sum_delivery AS sdo
				WHERE sod.`delivery_id` = sdi.`delivery_id`
				AND sod.`delivery_id` = sdo.delivery_id
				AND sod.`delivery_id` =".$delivery;
		$delivery= $db->fetchAll($sql);
		return  $delivery;
	}
	public function getDeliveryOption($order_id){
		$db = $this->getAdapter();
		$user_info = new Application_Model_DbTable_DbGetUserInfo();
		$result = $user_info->getUserInfo();
		$sql_delivery = "SELECT 
					  sod.`delivery_no` ,
					  sod.`delivery_id`,
					  sod.`sale_order_id`,
					  sod.`delivery_date`
					FROM
					  tb_sale_order_delivery AS sod,
					  tb_sales_order AS s
					WHERE sod.`sale_order_id`=s.`order_id`
					  AND s.`order_id`=$order_id";
	
		$row_delivery = $db->fetchAll($sql_delivery);
// 		$a = count($row_delivery);
// 		echo $a;exit();
		//print_r($row_delivery);
		$user_info = new Application_Model_DbTable_DbGetUserInfo();
		$result = $user_info->getUserInfo();
		$row_data="";
		
		foreach($row_delivery as $j=>$delivery){
			$inx = $j+1;
			$a = count($delivery);
			echo $a;//exit();
// 			$row_data.="<table>
// 					<tr bgcolor='#E0F1CF' style='border: none;bgcolor:#E0F1CF;'>
// 							<td align='left' colspan='3'>".$delivery['delivery_no']."</td>
// 							<td>".$delivery['delivery_date']."</td>
// 						</tr>	
					
// 					</table>";
			$row_data.="<table class='collape' width='94%' style='margin:20px 15px;font-family: arial,sans-serif; font-size:12px;'>";
			
			$row_data.="
					<tr style='font-size:16px;color:#396F33;border: none;bgcolor:#E0F1CF;'>
							<th align='left' style='border:none' >".$delivery['delivery_no']."</th>
							<th colspan='2' style='border:none'></th>
							<th style='border:none'>".$delivery['delivery_date']."</th>
						</tr>
			
					";
			$row_data.='
						<tr  height="45" bgcolor="#BFBFBF" style="color: #000;padding:10px; font-size:12px;">
							<td align="center"> ឈ្មោះមុខទំនិញ <br /> Item Name</td>
							<td align="center">ចំនួនបញ្ជាទិញ  <br />​ Qty Order</td>
							<td align="center">ចំនួនដឹកជិញ្ជូន<br />Qty Deliver</td>
							<td align="center">ចំនួននៅសល់<br />Remain</td>
						</tr>
					';
			$sql = "SELECT 
					sod.`delivery_id`,
					  sod.`delivery_no`,
					  sdi.`pro_id`,
					  sdi.`qty_deliver`,
					  sdi.`qty_order`,
					  sdi.`qty_remian`,
					  sod.`delivery_date`,
					  sdo.Sum_qty_order,
					  sdo.Sum_qty_deliver,
					  sdo.Sum_qty_remian,
					  (SELECT 
					    p.item_name 
					  FROM
					    tb_product AS p 
					  WHERE p.pro_id = sdi.`pro_id`) AS Item_name 
					FROM
					  tb_sale_order_delivery AS sod,
					  tb_sale_order_delivery_item AS sdi 
					  INNER JOIN sum_delivery AS sdo 
					WHERE sod.`delivery_id` = sdi.`delivery_id` 
					  AND sod.`delivery_id` = sdo.delivery_id 
					  AND sod.`delivery_id` =".$delivery['delivery_id'];
			$rows = $db->fetchAll($sql);
					//print_r($rows);
				if($rows){
					$num=1;
					foreach ($rows as $i=>$rs){
						$i = $i+1;
						$sum_qty_order = $rs["Sum_qty_order"];
						$sum_qty_deliver =$rs["Sum_qty_deliver"];
						$sum_qty_remain = $rs["Sum_qty_remian"];
						$row_data.="<tr  class='row-format".$num."' style='height: 35px'>
										<td style='border: none'>".$rs["Item_name"]."</td>
										<td style='border: none' align='center'>".$rs['qty_order']."</td>
										<td style='border: none' align='center'>".$rs['qty_deliver']."</td>
										<td style='border: none' align='center'>".$rs['qty_remian']."</td>
									</tr>";	
						$num=1-$num;
					}
				}
				
				$row_data .="
							
							<tr style='padding-right:10px;' style='height: 35px'>
								<td style='border: none'></td>
								<td style='border-top:1px solid #000;border-left:none;border-right:none;border-bottom:none;' align='center'>".$sum_qty_order."</td>
								<td style='border-top:1px solid #000;border-left:none;border-right:none;border-bottom:none;' align='center'>".$sum_qty_deliver."</td>
								<td style='border-top:1px solid #000;border-left:none;border-right:none;border-bottom:none;' align='center'>".$sum_qty_remain."</td>
							</tr>
						";
				$row_data.="</table><br /><br /> ";
				
			//echo "<input type='hidden' id='identity_".$inx."' />";
		}
		
		return $row_data;
	}
	
	
	public function getDeliveryOptions($order_id){
		$db = $this->getAdapter();
		$user_info = new Application_Model_DbTable_DbGetUserInfo();
		$result = $user_info->getUserInfo();
		$sql_delivery = "SELECT
		sod.`delivery_no` ,
		sod.`delivery_id`,
		sod.`sale_order_id`,
		sod.`delivery_date`
		FROM
		tb_sale_order_delivery AS sod,
		tb_sales_order AS s
		WHERE sod.`sale_order_id`=s.`order_id`
		AND s.`order_id`=$order_id";
	
		$row_delivery = $db->fetchAll($sql_delivery);
		// 		$a = count($row_delivery);
		// 		echo $a;exit();
		//print_r($row_delivery);
		$user_info = new Application_Model_DbTable_DbGetUserInfo();
		$result = $user_info->getUserInfo();
		$row_data="";
	
		foreach($row_delivery as $j=>$delivery){
		$inx = $j+1;
		$a = count($delivery);
		echo $a;//exit();
			// 			$row_data.="<table>
			// 					<tr bgcolor='#E0F1CF' style='border: none;bgcolor:#E0F1CF;'>
			// 							<td align='left' colspan='3'>".$delivery['delivery_no']."</td>
			// 							<td>".$delivery['delivery_date']."</td>
			// 						</tr>
				
			// 					</table>";
			$row_data.="<table class='collape' width='94%' style='margin:20px 15px;font-family: arial,sans-serif; font-size:12px;'>";
		
			$row_data.="
					<tr style='font-size:16px;color:#396F33;border: none;bgcolor:#E0F1CF;'>
							<th align='left' style='border:none' >".$delivery['delivery_no']."</th>
							<th colspan='2' style='border:none'></th>
							<th style='border:none'>".$delivery['delivery_date']."</th>
						</tr>
		
										";
										$row_data.='
						<tr  height="45" bgcolor="#BFBFBF" style="color: #000;padding:10px; font-size:12px;">
							<td align="center"> ឈ្មោះមុខទំនិញ <br /> Item Name</td>
							<td align="center">ចំនួនបញ្ជាទិញ  <br />​ Qty Order</td>
							<td align="center">ចំនួនដឹកជិញ្ជូន<br />Qty Deliver</td>
							<td align="center">ចំនួននៅសល់<br />Remain</td>
						</tr>
					';
			$sql = "SELECT
					sod.`delivery_id`,
						sod.`delivery_no`,
					  sdi.`pro_id`,
					  sdi.`qty_deliver`,
					  sdi.`qty_order`,
					  sdi.`qty_remian`,
					  sod.`delivery_date`,
					  sdo.Sum_qty_order,
					  sdo.Sum_qty_deliver,
					  sdo.Sum_qty_remian,
					  (SELECT
					    p.item_name
					  FROM
					    tb_product AS p
					  WHERE p.pro_id = sdi.`pro_id`) AS Item_name
					FROM
					  tb_sale_order_delivery AS sod,
					  tb_sale_order_delivery_item AS sdi
					  INNER JOIN sum_delivery AS sdo
					WHERE sod.`delivery_id` = sdi.`delivery_id`
					  AND sod.`delivery_id` = sdo.delivery_id
					  AND sod.`delivery_id` =".$delivery['delivery_id'];
			$rows = $db->fetchAll($sql);
				print_r($rows);
						if($rows){
						$num=1;
						foreach ($rows as $i=>$rs){
						//print_r($rs);
						$a = $rs["Item_name"];
						$sum_qty_order = $rs["Sum_qty_order"];
						$sum_qty_deliver =$rs["Sum_qty_deliver"];
						$sum_qty_remain = $rs["Sum_qty_remian"];
						$row_data.="<tr  class='row-format".$num."' style='height: 35px'>
							<td style='border: none'>".$rs["Item_name"]."</td>
										<td style='border: none' align='center'>".$rs['qty_order']."</td>
										<td style='border: none' align='center'>".$rs['qty_deliver']."</td>
										<td style='border: none' align='center'>".$rs['qty_remian']."</td>
									</tr>";
						$num=1-$num;
						}
				}
	
								$row_data .="
				
								<tr style='padding-right:10px;' style='height: 35px'>
								<td style='border: none'></td>
								<td style='border-top:1px solid #000;border-left:none;border-right:none;border-bottom:none;' align='center'>".$sum_qty_order."</td>
								<td style='border-top:1px solid #000;border-left:none;border-right:none;border-bottom:none;' align='center'>".$sum_qty_deliver."</td>
								<td style='border-top:1px solid #000;border-left:none;border-right:none;border-bottom:none;' align='center'>".$sum_qty_remain."</td>
							</tr>
						";
// 				$row_data .="
// 							<tr style='padding-right:10px;'>
// 								<td colspan='2' style='border: none'></td>
								// 								<td style='border: none'>Total</td>
								// 								<td align='center'>".$rs['Sum_qty_order']."</td>
								// 							</tr>
								// 							<tr style='padding-right:10px;'>
								// 								<td colspan='2' style='border: none'></td>
								// 								<td style='border: none'>Deliver</td>
								// 								<td align='center'>".$rs['Sum_qty_deliver']."</td>
								// 							</tr>
								// 							<tr style='padding-right:10px;'>
								// 								<td colspan='2' style='border: none'></td>
								// 								<td style='border: none;'>Remain</td>
								// 								<td align='center'>".$rs['Sum_qty_remian']."</td>
								// 							</tr>
								// 						";
				$row_data.="</table><br /><br /> ";
	
			echo "<input type='text' id='identity_".$inx."' />";
	}
	
	return $row_data;
			echo "<hr />";
		}
	public function selectProductOption(){//not add item to this select box
		$db = $this->getAdapter();
		$user_info = new Application_Model_DbTable_DbGetUserInfo();
		$result = $user_info->getUserInfo();
		$sql_cate = 'SELECT `CategoryId`,NAME FROM tb_category WHERE IsActive = 1 AND NAME!=""';
	
		$row_cate = $db->fetchAll($sql_cate);
		$option="";
		foreach($row_cate as $cate){
			$option .= '<optgroup  label="'.htmlspecialchars($cate['NAME'], ENT_QUOTES).'">';
			if($result["level"]==1 OR $result["level"]==2){
				$sql = "SELECT pro_id,item_name,item_code FROM tb_product WHERE is_avaliable = 1 AND cate_id = ".$cate['CategoryId']."
				AND item_name!='' ORDER BY last_mod_date DESC ";
			}else{
				$sql = "SELECT p.pro_id,p.item_name,p.item_code FROM tb_product AS p
						INNER JOIN tb_prolocation  As pl ON p.pro_id = pl.pro_id
				 WHERE p.is_avaliable = 1 AND p.cate_id = ".$cate['CategoryId']."
				AND p.item_name!='' AND pl.LocationId =".$result['location_id']." ORDER BY p.last_mod_date DESC ";
			}
			
			
			$rows = $db->fetchAll($sql);
			if($rows){
				foreach($rows as $value){
					$option .= '<option value="'.$value['pro_id'].'" label="'.htmlspecialchars($value['item_name'], ENT_QUOTES).'">'.
							htmlspecialchars($value['item_name'], ENT_QUOTES)." ".htmlspecialchars($value['item_code'], ENT_QUOTES)
							.'</option>';
				}
			}
			$option.="</optgroup>";
		}
		return $option;
	}
	
	public function getTypePriceOption(){
		$db = $this->getAdapter();
		$sql = 'SELECT type_id, price_type_name FROM tb_price_type WHERE public = 1 AND price_type_name!=""';
		$rows = $db->fetchAll($sql);
		$user_info = new Application_Model_DbTable_DbGetUserInfo();
		$result = $user_info->getUserInfo();
		$option="";
		if(!empty($rows))foreach($rows as $price){
					$option .= '<option value="'.$price['type_id'].'" label="'.htmlspecialchars($price['price_type_name'], ENT_QUOTES).'">'.htmlspecialchars($price['price_type_name'], ENT_QUOTES).'</option>';
		}
		if($result["level"]==1 OR $result["level"]==2){
			$option .= '<option value="-1" label="Add Price Type">Add Price Type</option>';
		}
		return $option;
	}
	
	
	public function getProductOptionBYId($stock, $currentItem = null){
		$db = $this->getAdapter();
		$sql = "SELECT pro_id,item_name FROM tb_product WHERE is_avaliable = 1 AND item_name!='' ";
		$rows = $db->fetchAll($sql);
		$option = '';
		foreach($rows as $value){
			$option .= '<option value="'.$value['pro_id'].'" label="'.htmlspecialchars($value['item_name'], ENT_QUOTES).'">'.htmlspecialchars($value['item_name'], ENT_QUOTES).'</option>';
		}
		return $option;
	}
	
	/**
	 * Generate Options for select option in html code
	 * @author Sovann
	 * @param string $sql
	 * @param string $display
	 * @param string $value
	 * @return string
	 */
	public function getOptonsHtml($sql, $display, $value){
		$db = $this->getAdapter();		
		$option = '<option value="" label="--- Select ---">--- Select ---</option>';
		foreach($db->fetchAll($sql) as $r){
			
			$option .= '<option value="'.$r[$value].'" label="'.htmlspecialchars($r[$display], ENT_QUOTES).'">'.htmlspecialchars($r[$display], ENT_QUOTES).'</option>';
		}
		return $option;
	}
	
}

