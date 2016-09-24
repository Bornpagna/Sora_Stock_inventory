<?php

class Product_Model_DbTable_DbOther extends Zend_Db_Table_Abstract
{
    
    
    
    public function setName($name)
    {
    	//$this->_name=$name;
    }
    
    
    function getAllView(){
    	$db = $this->getAdapter();
    	$sql = "SELECT v.`id`,v.`name_en`,v.`status`,v.`key_code`,`type` FROM `tb_view` AS v WHERE v.`type` IN(2,3,4)";
    	return $db->fetchAll($sql);
    }
    function getViewById($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT v.`id`,v.`name_en`,v.`status`,v.`key_code`,`type` FROM `tb_view` AS v WHERE v.`id`=$id";
    	return $db->fetchRow($sql);
    }
    
    function add($data){
    	$db = $this->getAdapter();
    	$key_code = $this->getLastKeycodeByType($data['type']);
    	$arr = array(
    		'name_en'	=>	$data["title_en"],
    		'key_code'	=>	$key_code,
    		'type'		=>	$data["type"],
    		'status'	=>	$data["status"],
    	);
    	$this->_name = "tb_view";
    	$this->insert($arr);
    }
    function edit($data){
    	$db = $this->getAdapter();
    	$key_code = $this->getLastKeycodeByType($data['type']);
    	$arr = array(
    			'name_en'	=>	$data["title_en"],
    			'key_code'	=>	$key_code,
    			'type'		=>	$data["type"],
    			'status'	=>	$data["status"],
    	);
    	$this->_name = "tb_view";
    	$where = $db->quoteInto("id=?", $data["id"]);
    	$this->update($arr, $where);
    }
    
    function getLastKeycodeByType($type){
    	$sql = "SELECT key_code FROM `tb_view` WHERE type=$type ORDER BY key_code DESC LIMIT 1 ";
    	$db =$this->getAdapter();
    	$number = $db->fetchOne($sql);
    	return $number+1;
    }
}