<?php

class product_Model_DbTable_DbAddStock extends Zend_Db_Table_Abstract
{

    //protected $_name = 'tb_sublocation';
    
	/* @Desc: add lost item
	 * @Author: May Dara
	 * */
    //add sub stock name 8/22/13
	public function SaveItems($data) {
		$db_rs = $this->getAdapter();
		$session_user=new Zend_Session_Namespace('auth');
		$GetUserId= $session_user->user_id;
		
		$dataInfo = array(
	    		'Name' 			=> $data['name'],
	    		'last_usermod'  => $GetUserId,
	    		'last_mod_date' => new Zend_Date(),
	    		'contact' 		=> $data['description'],
				'phone' 		=> $data['assign_contact'],
				'stock_add' 	=> $data['order_date'],
				'remark' 		=> $data['status']
	    		);
    	$itemid = $db->insert("tb_sublocation", $dataInfo);
	}
	
	public function SelectItem(){
		$db=$this->getAdapter();
		 $user_info = new Application_Model_DbTable_DbGetUserInfo();
        $result = $user_info->getUserInfo();
        $db = new Application_Model_DbTable_DbGlobal();
		
		$productSql = "SELECT
				p.pro_id
				,p.item_name
				,p.item_code
				,(SELECT g.Name FROM tb_category AS g WHERE g.CategoryId = (SELECT cate_id FROM tb_product WHERE pro_id = pl.`pro_id` LIMIT 1)) AS Cate_name
				,(SELECT b.Name FROM tb_branch AS b WHERE b.branch_id = (SELECT brand_id FROM tb_product WHERE pro_id = pl.`pro_id` LIMIT 1 )) AS Branch
				,(SELECT lo.Name FROM tb_sublocation AS lo WHERE lo.LocationId = pl.LocationId LIMIT 1) AS LocationName
				,pl.qty
			FROM tb_prolocation AS pl,tb_product AS p WHERE pl.`pro_id`=p.pro_id ";
		 
		$str_condition = " AND pl.LocationId" ;
		$productSql .= $db->getAccessPermission($result["level"], $str_condition, $result["location_id"]);
		 
		if($this->getRequest()->isPost()){
			$post = $this->getRequest()->getPost();
			$productName = $this->getRequest()->getParam('s_name');
			if($post['LocationId'] !=='' AND $post['LocationId'] !=0){
				$productSql .= " AND pl.LocationId = ".trim($post['LocationId']);
			}
			if($post['p_name'] !=''){
				$productSql .= " AND p.item_name LIKE '%".trim($post['p_name'])."%'";
				$productSql .= " OR p.item_code LIKE '%".trim($post['p_name'])."%'";
			}
			if($post['category_id']!=='' AND $post['category_id']!=0){
				//echo $post['category_id']; exit();
				$productSql .= " AND p.cate_id =".trim($post['category_id']);}
					
				if($post['branch_id']!=='' AND $post['branch_id']!=0){
					$productSql .= " AND p.brand_id =".trim($post['branch_id']);}
		}//echo $productSql;exit();
		$productSql.= " ORDER BY p.item_name,p.cate_id DESC";
		return  $rows=$db_rs->fetchAll($productSql);
	}
}

