<?php

class sales_Model_DbTable_DbCustomer extends Zend_Db_Table_Abstract
{
	protected $_name = "tb_customer";
	public function setName($name)
	{
		$this->_name=$name;
	}
	
	//for add customer
	final public function addCustomer($post)
	{
		$session_user=new Zend_Session_Namespace('auth');
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
		
		$db=$this->getAdapter();
		$data=array(
				'type_price'	=> $post['type_price'],
				'cust_name'		=> $post['txt_name'],
				'contact_name'	=> $post['txt_contact_name'],//test
				'add_name'		=> $post['txt_address'],
				'phone'			=> $post['txt_phone'],
				'fax'			=> $post['txt_fax'],
				'email'			=> $post['txt_mail'],
				'website'		=> $post['txt_website'],//test
				'customer_remark'=>$post['remark'],
				'last_usermod'	=> $GetUserId,
				'last_mod_date'	=> new Zend_Date(),
				//'PaymentTermsId'=> $post['pay_term'],
				//'discount'		=> $post['txt_discount'],
				//'CurrencyId'	=> $post['currency'],
		);
		//$db->insert("tb_customer", $data);
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