<?php

class Vendor_Model_DbTable_DbVendor extends Zend_Db_Table_Abstract
{

	final public function addVendor($post){
		$session_user=new Zend_Session_Namespace('auth');
		$userName=$session_user->user_name;
		$GetUserId= $session_user->user_id;
		$db=$this->getAdapter();
		$data=array(
				'v_name'		=>$post['txt_name'],
				'add_name'		=>$post['txt_addName'],
				'street'		=>$post['text_street'],
				'city'			=>$post['ddl_city'],
				'state'			=>$post['ddl_state'],//test
				'country'		=>$post['ddl_counrty'],
				'postalcode'	=>$post['txt_zip'],//test
				'add_remark'	=>$post['txt_addremark'],
				'contact_name'	=>$post['txt_contact_name'],//test
				'phone'			=>$post['txt_phone'],
				'fax'			=>$post['txt_fax'],
				'email'			=>$post['txt_mail'],
				'website'		=>$post['txt_website'],//test
				'vendor_remark'	=>$post['txt_add_remark'],
				'last_usermod'	=>$GetUserId,
				'last_mod_date' => new Zend_Date(),
				'PaymentTermsId'=>$post['pay_term'],
				'CurrencyId'	=>$post['currency'],
				'CarrierId'		=>$post['carrier'],
				'version'		=>1
		);
		$db->insert("tb_vendor", $data);
	}
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
				"last_mod_date"=>new Zend_Date()
				);
		$result =$db_global->addRecord($datavendor,"tb_vendor");	
		return $result;	
	}
}