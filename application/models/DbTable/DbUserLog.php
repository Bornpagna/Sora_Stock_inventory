<?php

class Application_Model_DbTable_DbUserLog extends Zend_Db_Table_Abstract
{

    protected $_name = 'fi_user_log';

	public function insertLogin($user_id){
    	$data=array(
    					'log_date'=>date("Y/m/d , H:i:s"),
    					'log_type'=>'in',
    					'user_id'=>$user_id	
    	           );
    	 $this->insert($data);
    }
    public function insertLogout($user_id)
    {
    	$data=array(
    					'log_date'=>date("Y/m/d , H:i:s"),
    					'log_type'=>'out',
    					'user_id'=>$user_id	
    	           );    	 
    	 $this->insert($data);
    }
}

