<?php

class Sales_Model_DbTable_DbCustomer extends Zend_Db_Table_Abstract
{
	protected $_name = "tb_customer";
	public function setName($name)
	{
		$this->_name=$name;
	}
	
	function getAllCustomer($search){
		$db = $this->getAdapter();
		
		$sql=" SELECT id,
		(SELECT name FROM `tb_sublocation` WHERE id=branch_id LIMIT 1) AS branch_name,
		 cust_name,phone,
		(SELECT NAME FROM `tb_price_type` WHERE id=customer_level LIMIT 1) As level,
		 contact_name,contact_phone,address,
		( SELECT name_en FROM `tb_view` WHERE type=2 AND key_code=status LIMIT 1) status,
		( SELECT fullname FROM `tb_acl_user` WHERE tb_acl_user.user_id=user_id LIMIT 1) AS user_name
		 FROM `tb_customer` WHERE cust_name!=''  ";
		
		$from_date =(empty($search['start_date']))? '1': " date >= '".$search['start_date']." 00:00:00'";
		$to_date = (empty($search['end_date']))? '1': " date <= '".$search['end_date']." 23:59:59'";
		$where = " AND ".$from_date." AND ".$to_date;
		if(!empty($search['text_search'])){
			$s_where = array();
			$s_search = trim(addslashes($search['text_search']));
			$s_where[] = " cust_name LIKE '%{$s_search}%'";
			$s_where[] = " phone LIKE '%{$s_search}%'";
			$s_where[] = " contact_name LIKE '%{$s_search}%'";
			$s_where[] = " contact_phone LIKE '%{$s_search}%'";
			$s_where[] = " address LIKE '%{$s_search}%'";
			
			$s_where[] = " email LIKE '%{$s_search}%'";
			$s_where[] = " website LIKE '%{$s_search}%'";
			$s_where[] = " remark LIKE '%{$s_search}%'";
			$where .=' AND ('.implode(' OR ',$s_where).')';
		}
		
		if($search['branch_id']>0){
			$where .= " AND branch_id = ".$search['branch_id'];
		}
		if($search['customer_id']>0){
			$where .= " AND id = ".$search['customer_id'];
		}
		if($search['level']>0){
			$where .= " AND customer_level = ".$search['level'];
		}
		$order=" ORDER BY id DESC ";
// 		echo $sql.$where.$order;
		return $db->fetchAll($sql.$where.$order);
	}
	public function addCustomer($post)
	{
		$session_user=new Zend_Session_Namespace('auth');
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
		$db=$this->getAdapter();
		$data=array(
// 				'type_price'	=> $post['type_price'],
				'cust_name'		=> $post['txt_name'],
				'phone'			=> $post['txt_phone'],
				'contact_name'	=> $post['txt_contact_name'],//test
				'address'		=> $post['txt_address'],
				'fax'			=> $post['txt_fax'],
				'email'			=> $post['txt_mail'],
				'website'		=> $post['txt_website'],//test
				'add_remark'=>$post['remark'],
				'user_id'	=> $GetUserId,
				'date'	=> new Zend_Date(),
				'branch_id'		=> $post['branch_id'],
				'customer_level'=> $post['customer_level'],
		);
		
		$this->insert($data);
	}
	final public function updateCustomer($post){
		$session_user=new Zend_Session_Namespace('auth');
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
		$id=$post["id"];
		$data=array(
				'type_price'	=> $post['type_price'],
				'cust_name'		=> $post['txt_name'],
				'add_name'		=> $post['txt_address'],
				'contact_name'	=> $post['txt_contact_name'],//test
				'phone'			=> $post['txt_phone'],
				'fax'			=> $post['txt_fax'],
				'email'			=> $post['txt_mail'],
				'website'		=> $post['txt_website'],//test
				'customer_remark'=>$post['remark'],
				'last_usermod'	=> $GetUserId,
				'last_mod_date'	=> new Zend_Date(),
				'is_active'		=> $post["status"],
// 				'PaymentTermsId'=> $post['pay_term'],
// 				'discount'		=> $post['txt_discount'],
// 				'CurrencyId'	=> $post['currency'],
				'version'		=> 1
		);
		$where=$this->getAdapter()->quoteInto('customer_id=?',$id);
		$this->update($data,$where);
		
	}
	//for add new customer from sales
	final function addNewCustomer($post){
		$session_user=new Zend_Session_Namespace('auth');
		$db = new Application_Model_DbTable_DbGlobal();
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
			$data=array(
					'type_price'	=> $post['price_type'],
					'cust_name'		=> $post['customer_name'],
					'contact_name'	=> $post['contact'],//test
					'phone'			=> $post['phone'],
					'add_name'		=> $post['address'],
					'email'			=> $post['txt_mail'],
					'last_usermod'	=> $GetUserId,
					'last_mod_date'	=> new Zend_Date(),
					'CurrencyId'	=> 1
			);
		$result=$db->addRecord($data, "tb_customer");
		return $result;	
	}
}