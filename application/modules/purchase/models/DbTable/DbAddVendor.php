<?php

class purchase_Model_DbTable_DbAddVendor extends Zend_Db_Table_Abstract
{
	protected $_name = "tb_vendor";
	public function setName($name)
	{
		$this->_name=$name;
	}
	final public function addVendor($post){
		$session_user=new Zend_Session_Namespace('auth');
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
		$db=$this->getAdapter();
		$db->beginTransaction();
		try{
			$data=array(
					'v_name'		=> $post['txt_name'],
					'add_name'		=> $post['txt_address'],
					'contact_name'	=> $post['txt_contact_name'],//test
					'phone'			=> $post['txt_phone'],
					'fax'			=> $post['txt_fax'],
					'email'			=> $post['txt_mail'],
					'website'		=> $post['txt_website'],//test
					'vendor_remark'	=> $post['remark'],
					'last_usermod'	=> $GetUserId,
					'last_mod_date' => new Zend_Date(),
					/*'PaymentTermsId'=> $post['pay_term'],
					 'CurrencyId'	=> $post['currency'],
			'CarrierId'		=> $post['carrier'],
			'version'		=> 1*/
			);
			$db->insert("tb_vendor", $data);
			return $db->rollBack();	
		}
		catch(Exception $e){
			return $db->rollBack();
		}
	}
	public function updateVendor($post){
		$session_user=new Zend_Session_Namespace('auth');
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
		$data=array(
				'v_name'		=> $post['txt_name'],
				'add_name'		=> $post['txt_address'],
				'contact_name'	=> $post['txt_contact_name'],//test
				'phone'			=> $post['txt_phone'],
				'fax'			=> $post['txt_fax'],
				'email'			=> $post['txt_mail'],
				'website'		=> $post['txt_website'],//test
				'vendor_remark'	=> $post['remark'],
				'last_usermod'	=> $GetUserId,
				'last_mod_date' => new Zend_Date(),
				/*'PaymentTermsId'=> $post['pay_term'],
				'CurrencyId'	=> $post['currency'],
				'CarrierId'		=> $post['carrier'],
				'version'		=> 1*/
		);
		$where=$this->getAdapter()->quoteInto('vendor_id=?',$post['id']);
		$this->update($data,$where);
		
	}
	//add new from add purchase order
	final public function addNewVenor($post){
		$db_global = new Application_Model_DbTable_DbGlobal();
		$session_user=new Zend_Session_Namespace('auth');
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
		$db=$this->getAdapter();
		$datavendor=array(
				"v_name"	   => $post["v_name"],
				"contact_name" => $post["contact"],
				"phone"		   => $post["phone"],
				"add_name"	   => $post["address"],
				"email"		   => $post["txt_mail"],
				"last_usermod" => $GetUserId,
				"last_mod_date"=>new Zend_Date(),
				"CurrencyId"   => 1				
		);
		$result =$db_global->addRecord($datavendor,"tb_vendor");
		return $result;
	}
}