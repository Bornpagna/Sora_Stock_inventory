<?php
class purchase_ReceiveController extends Zend_Controller_Action
{	
    public function init()
    {
        /* Initialize action controller here */
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
    	$tr = Application_Form_FrmLanguages::getCurrentlanguage();
    }
    protected function GetuserInfoAction(){
    	$user_info = new Application_Model_DbTable_DbGetUserInfo();
    	$result = $user_info->getUserInfo();
    	return $result;
    }
	public function indexAction()
		{
			$formFilter = new Application_Form_Frmsearch();
			$this->view->formFilter = $formFilter;
			Application_Model_Decorator::removeAllDecorator($formFilter);
			
			$list = new Application_Form_Frmlist();
			$db = new Application_Model_DbTable_DbGlobal();
	// 		$vendor_sql = "SELECT p.order_id, p.order, p.date_order, p.status, v.v_name, p.all_total,u.username
	// 						FROM tb_purchase_order AS p ,
	// 						tb_vendor AS v,rsv_acl_user u
	// 		                WHERE v.vendor_id=p.vendor_id AND u.user_id = p.user_mod ";
			$vendor_sql="SELECT 
							recieve_id
							,ro.recieve_no
							,ro.date_recieve
							
							,(SELECT v.v_name FROM tb_vendor AS v WHERE v.vendor_id = ro.vendor_id) AS VendorName
							,ro.all_total
							,(SELECT u.username FROM rsv_acl_user AS u WHERE u.user_id = ro.user_recieve) AS userName
						
						FROM tb_recieve_order AS ro WHERE is_active=1";
			
			$user = $this->GetuserInfoAction();
			$str_condition = " AND p.LocationId" ;
			$vendor_sql .= $db->getAccessPermission($user["level"], $str_condition, $user["location_id"]);
			
			$this->view->level = $user["level"];
			
			if($this->getRequest()->isPost()){
					$post = $this->getRequest()->getPost();
					//echo $post["order"];
					if($post['order'] !=''){
							$vendor_sql .= " AND ro.recieve_no LIKE '%".$post['order']."%'";
					}
					if($post['vendor_name'] !='' AND $post['vendor_name'] !=0){
						$vendor_sql .= " AND ro.user_recieve =".$post['vendor_name'];
									}
	// 				if($post['phone'] !=''){
	// 					$vendor_sql .= " AND v.phone LIKE '%".$post['phone']."%'";
	// 				}
// 					if($post['status'] !=''){
// 						$vendor_sql .= " AND ro.status =".$post['status'];
// 					}
					$start_date = $post['search_start_date'];
					$end_date = $post['search_end_date'];
					
					if($start_date != "" && $end_date != "" && strtotime($end_date) >= strtotime($start_date)) {
						$vendor_sql .= " AND ro.date_recieve BETWEEN '$start_date' AND '$end_date'";
					}
			}
			
			//echo $vendor_sql;exit();
			$vendor_sql.=" ORDER BY ro.recieve_no DESC";
			//**************************************
			$rows=$db->getGlobalDb($vendor_sql);
			//print_r($rows);exit();
			$glClass = new Application_Model_GlobalClass();
			//$rows = $glClass->getStatusType($rows, BASE_URL, true);
			$columns=array("PURCHASE_ORDER_CAP","ORDER_DATE_CAP", "VENDOR_NAME_CAP",
					 "TOTAL_CAP_DOLLAR",strtoupper("BY_USER_CAP"));
			$link=array(
					'module'=>'purchase','controller'=>'receive','action'=>'detail-purchase-order',
			);
			// url link to update purchase order
			
			$urlEdit = BASE_URL . "/purchase/index/update-purchase-order-test";
			$this->view->list=$list->getCheckList(1, $columns, $rows, array('order'=>$link),$urlEdit);
	}
	public function addRecievePurchaseAction(){
		if($this->getRequest()->getPost()){
			$db = new Application_Model_DbTable_DbGlobal();
			
		}
	}
	public function getPurchaseidAction(){		
		if($this->getRequest()->isPost()){
		    $db= new Application_Model_DbTable_DbGlobal();
			$post=$this->getRequest()->getPost();
			$invoice = $post['invoice_id'];
			$sqlinfo ="SELECT * FROM `tb_purchase_order` WHERE order_id = $invoice LIMIT 1";
			$rowinfo=$db->getGlobalDbRow($sqlinfo);
			$sql = "SELECT pui.qty_order,pui.pro_id,pui.price,pui.sub_total
					,(SELECT pur.order FROM tb_purchase_order as pur WHERE pur.order_id = pui.order_id ) as order_no
					,(SELECT pur.all_total FROM tb_purchase_order as pur WHERE pur.order_id = pui.order_id ) as all_total
					,(SELECT pr.qty_perunit FROM tb_product AS pr WHERE pr.pro_id = pui.pro_id LIMIT 1) AS qty_perunit
      				,(SELECT pr.item_name FROM tb_product AS pr WHERE pr.pro_id = pui.pro_id LIMIT 1) AS item_name
					,(SELECT pr.pro_id FROM tb_product AS pr WHERE pr.pro_id = pui.pro_id LIMIT 1) AS pro_id
      				,(SELECT `label` FROM tb_product AS pr WHERE pr.pro_id = pui.pro_id LIMIT 1) AS label
     				 ,(SELECT `measure_name` FROM `tb_measure` AS ms WHERE ms.id=(SELECT measure_id FROM tb_product WHERE pro_id=pui.`pro_id`)) AS measure_name
      			FROM `tb_purchase_order_item` AS pui WHERE pui.order_id = ".$invoice;
			$rows=$db->getGlobalDb($sql);
		    $result = array('poinfo'=>$rowinfo,'item'=>$rows);
			echo Zend_Json::encode($result);
			exit();
		}
		
	}
}