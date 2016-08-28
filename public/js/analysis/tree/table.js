function createNode(){
  var root = {
    "id" : "0",
    "text" : "PORTFOLIO",
    "value" : "86",
    "showcheck" : true,
    complete : true,
    "isexpand" : true,
    "checkstate" : 0,
    "hasChildren" : true
  };
  var portfolio=[];
  //---------------------project info--------------------
  	  var project_info = [];    	      
      project_info.push( {
         "id" : 'pdbs_analysis_project',
         "text" : 'Project/Program',
         "value" : 'pdbs_analysis_project',
         "showcheck" : true,
         complete : true,
         "isexpand" : false,
         "checkstate" : 0,
         "hasChildren" : false
      });    
      project_info.push( {
          "id" : 'pdbs_analysis_allocation',
          "text" : 'Allocation',
          "value" : 'pdbs_view_analysis_allocation',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
      project_info.push( {
          "id" : 'pdbs_analysis_dp_team',
          "text" : 'Dp Project Team',
          "value" : 'pdbs_analysis_dp_team',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
      project_info.push( {
          "id" : 'pdbs_analysis_component',
          "text" : 'Component/Part/Output',
          "value" : 'pdbs_analysis_component',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
      project_info.push( {
          "id" : 'pdbs_analysis_agency',
          "text" : 'EA/IA',
          "value" : 'pdbs_analysis_agency',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });   
      portfolio.push( {
      	      "id" : "project_info",
      	      "text" : "Project/Program Info",
      	      "value" : "project_info",
      	      "showcheck" : true,
      	      complete : true,
      	      "isexpand" : false,
      	      "checkstate" : 0,
      	      "hasChildren" : true,
      	      "ChildNodes" : project_info
      	    });  		
   //---------------------budget--------------------
      var budget = [];        
      budget.push( {
          "id" : 'pdbs_analysis_project_disbursement',
          "text" : 'Project of Disbursement',
          "value" : 'pdbs_analysis_project_disbursement',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });   
	   budget.push( {
          "id" : 'pdbs_analysis_actual_disbursement',
          "text" : 'Actual of Disbursement',
          "value" : 'pdbs_analysis_actual_disbursement',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       }); 
	   budget.push( {
          "id" : 'pdbs_analysis_payment_order',
          "text" : 'Payment Order',
          "value" : 'pdbs_analysis_payment_order',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   budget.push( {
          "id" : 'pdbs_analysis_tracking_wa',
          "text" : 'Tracking W/A',
          "value" : 'pdbs_analysis_tracking_wa',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   budget.push( {
          "id" : 'pdbs_analysis_payment_order',
          "text" : 'Payment Order',
          "value" : 'pdbs_analysis_payment_order',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   budget.push( {
          "id" : 'pdbs_analysis_budget_support',
          "text" : 'Budget Support',
          "value" : 'pdbs_analysis_budget_support',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   budget.push( {
          "id" : 'pdbs_analysis_sale_bidding',
          "text" : 'Sale of Bidding',
          "value" : 'pdbs_analysis_sale_bidding',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
		budget.push( {
          "id" : 'pdbs_analysis_capitalized_amount',
          "text" : 'Capitalized Amount',
          "value" : 'pdbs_analysis_capitalized_amount',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   portfolio.push( {
  	      "id" : "dudget",
  	      "text" : "Budget",
  	      "value" : "budget",
  	      "showcheck" : true,
  	      complete : true,
  	      "isexpand" : false,
  	      "checkstate" : 0,
  	      "hasChildren" : true,
  	      "ChildNodes" : budget
  	    });  
//-----------------------------------------------------procurement---------------------------------------	   
		var procurement=[];
		procurement.push( {
          "id" : 'pdbs_analysis_contract_ledger',
          "text" : 'Contract Ledger',
          "value" : 'pdbs_analysis_contract_ledger',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   procurement.push( {
          "id" : 'pdbs_analysis_tracking_procurement',
          "text" : 'Tracking of Procurement',
          "value" : 'pdbs_analysis_tracking_procurement',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   procurement.push( {
          "id" : 'pdbs_analysis_project_contract_award',
          "text" : 'Projection of Contract Award',
          "value" : 'pdbs_analysis_project_contract_award',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   procurement.push( {
          "id" : 'pdbs_analysis_procurement_plan',
          "text" : 'Procurment Plan',
          "value" : 'pdbs_analysis_procurement_plan',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   procurement.push( {
          "id" : 'pdbs_analysis_list_prc',
          "text" : 'List of PRC',
          "value" : 'pdbs_analysis_list_prc',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
		portfolio.push( {
  	      "id" : "procurement",
  	      "text" : "Procruement",
  	      "value" : "procurement",
  	      "showcheck" : true,
  	      complete : true,
  	      "isexpand" : false,
  	      "checkstate" : 0,
  	      "hasChildren" : true,
  	      "ChildNodes" : procurement
  	    });  
//---------------------inventory------------------------------------------------------------------------------
      var inventory = [];        
      inventory.push( {
          "id" : 'pdbs_analysis_tax_exemption',
          "text" : 'Tax Exmeption',
          "value" : 'pdbs_analysis_tax_exemption',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });   
	   inventory.push( {
          "id" : 'pdbs_motor_vehicle',
          "text" : 'Motor and Vehicle',
          "value" : 'pdbs_analysis_motor_vehicle',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       }); 
	   inventory.push( {
          "id" : 'pdbs_analysis_bank_accounts',
          "text" : 'Bank Accounts',
          "value" : 'pdbs_analysis_bank_accounts',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   inventory.push( {
          "id" : 'pdbs_analysis_business_firm',
          "text" : 'List of Business Firm',
          "value" : 'pdbs_analysis_business_firm',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   inventory.push( {
          "id" : 'pdbs_analysis_list_ea_ia',
          "text" : 'List of EA/IA',
          "value" : 'pdbs_analysis_list_ea_ia',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   inventory.push( {
          "id" : 'pdbs_analysis_management_staff',
          "text" : 'Management Staff',
          "value" : 'pdbs_analysis_management_staff',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   inventory.push( {
          "id" : 'pdbs_analysis_dp_project_team',
          "text" : 'DP Project Team',
          "value" : 'pdbs_analysis_dp_project_team',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   inventory.push( {
          "id" : 'pdbs_analysis_uxo_mine',
          "text" : 'UXO & Mine',
          "value" : 'pdbs_analysis_uxo_mine',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   inventory.push( {
          "id" : 'pdbs_analysis_resettlement',
          "text" : 'Resettlement',
          "value" : 'pdbs_analysis_resettlement',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   portfolio.push( {
  	      "id" : "inventory",
  	      "text" : "Inventory",
  	      "value" : "inventory",
  	      "showcheck" : true,
  	      complete : true,
  	      "isexpand" : false,
  	      "checkstate" : 0,
  	      "hasChildren" : true,
  	      "ChildNodes" : inventory
  	    });  
//---------------------------------reference------------------------------------------------------------------
      var reference = [];        
      reference.push( {
          "id" : 'pdbs_analysis_poc',
          "text" : 'POC',
          "value" : 'pdbs_analysis_poc',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });   
	   reference.push( {
          "id" : 'pdbs_analysis_rmf_pad',
          "text" : 'RMF/PAD',
          "value" : 'pdbs_analysis_rmf_pad',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       }); 
	   reference.push( {
          "id" : 'pdbs_analysis_pam_pim',
          "text" : 'PAM/PIM',
          "value" : 'pdbs_analysis_pam_pim',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   reference.push( {
          "id" : 'pdbs_analysis_prc',
          "text" : 'PRC',
          "value" : 'pdbs_analysis_prc',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   reference.push( {
          "id" : 'pdbs_analysis_budget_regulation',
          "text" : 'Budget Regulation',
          "value" : 'pdbs_analysis_budget_regulation',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   reference.push( {
          "id" : 'pdbs_analysis_reference_support',
          "text" : 'reference Support',
          "value" : 'pdbs_analysis_reference_support',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   reference.push( {
          "id" : 'pdbs_analysis_sops',
          "text" : 'SOPs',
          "value" : 'pdbs_analysis_sops',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });		
	   portfolio.push( {
  	      "id" : "reference",
  	      "text" : "Reference",
  	      "value" : "reference",
  	      "showcheck" : true,
  	      complete : true,
  	      "isexpand" : false,
  	      "checkstate" : 0,
  	      "hasChildren" : true,
  	      "ChildNodes" : reference
  	    });  
//---------------------poc--------------------
      var poc = [];        
      poc.push( {
          "id" : 'pdbs_analysis_poc_director',
          "text" : 'POC Direcor',
          "value" : 'pdbs_analysis_poc_director',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });   
	   poc.push( {
          "id" : 'pdbs_analysis_poc_recipient',
          "text" : 'POC Recipient',
          "value" : 'pdbs_analysis_poc_recipient',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       }); 
	   poc.push( {
          "id" : 'pdbs_analysis_tracking_poc',
          "text" : 'Tracking of POC',
          "value" : 'pdbs_analysis_tracking_poc',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   portfolio.push( {
  	      "id" : "poc",
  	      "text" : "POC",
  	      "value" : "poc",
  	      "showcheck" : true,
  	      complete : true,
  	      "isexpand" : false,
  	      "checkstate" : 0,
  	      "hasChildren" : true,
  	      "ChildNodes" : poc
  	    });  
//---------------------prp----------------------------------------------------------------------------------
      var prp = [];        
      prp.push( {
          "id" : 'pdbs_analysis_personel',
          "text" : 'Personel',
          "value" : 'pdbs_analysis_personel',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });   
	   prp.push( {
          "id" : 'pdbs_analysis_rmf',
          "text" : 'RMF',
          "value" : 'pdbs_analysis_rmf',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       }); 
	   prp.push( {
          "id" : 'pdbs_analysis_ppp',
          "text" : 'PPP',
          "value" : 'pdbs_analysis_ppp',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });	
	   portfolio.push( {
  	      "id" : "prp",
  	      "text" : "Personel/RMF/PPP",
  	      "value" : "prp",
  	      "showcheck" : true,
  	      complete : true,
  	      "isexpand" : false,
  	      "checkstate" : 0,
  	      "hasChildren" : true,
  	      "ChildNodes" : prp
  	    }); 	   
	//---------------------share--------------------
      var share = [];        
      share.push( {
          "id" : 'pdbs_analysis_share',
          "text" : 'Share',
          "value" : 'pdbs_analysis_share',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });   
	   share.push( {
          "id" : 'pdbs_analysis_promissory_note',
          "text" : 'Promissory Note',
          "value" : 'pdbs_analysis_promissory_note',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       }); 
	   share.push( {
          "id" : 'pdbs_analysis_encashment_promissory',
          "text" : 'Encashment of Promissory',
          "value" : 'pdbs_analysis_encashment_promissory',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   portfolio.push( {
  	      "id" : "share",
  	      "text" : "Share",
  	      "value" : "share",
  	      "showcheck" : true,
  	      complete : true,
  	      "isexpand" : false,
  	      "checkstate" : 0,
  	      "hasChildren" : true,
  	      "ChildNodes" : share
  	    });
//---------------------more---------------------------------------------------------------------------------
      var more = [];        
      more.push( {
          "id" : 'pdbs_analysis_development_partner',
          "text" : 'Developement Partner',
          "value" : 'pdbs_analysis_developement_partner',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });   
	   more.push( {
          "id" : 'pdbs_analysis_division',
          "text" : 'Division',
          "value" : 'pdbs_analysis_division',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       }); 
	   more.push( {
          "id" : 'pdbs_analysis_currency',
          "text" : 'Currency',
          "value" : 'pdbs_analysis_currency',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   more.push( {
          "id" : 'pdbs_analysis_category_allocation',
          "text" : 'Category Allocation',
          "value" : 'pdbs_analysis_category_allocation',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   more.push( {
          "id" : 'pdbs_analysis_sub_category_allocation',
          "text" : 'Sub Category Allocation',
          "value" : 'pdbs_analysis_sub_category_allocation',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   more.push( {
          "id" : 'pdbs_analysis_nature_contract',
          "text" : 'Nature of Contract',
          "value" : 'pdbs_analysis_nature_contract',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   more.push( {
          "id" : 'pdbs_analysis_sub_nature_contract',
          "text" : 'Sub Nature of Contract',
          "value" : 'pdbs_analysis_sub_nature_contract',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
		more.push( {
          "id" : 'pdbs_analysis_chapter',
          "text" : 'Chapter',
          "value" : 'pdbs_analysis_chapter',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   more.push( {
          "id" : 'pdbs_analysis_account',
          "text" : 'Account',
          "value" : 'pdbs_analysis_account',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   more.push( {
          "id" : 'pdbs_analysis_sub_account',
          "text" : 'Sub Account',
          "value" : 'pdbs_analysis_sub_account',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   more.push( {
          "id" : 'pdbs_analysis_type_of_payment',
          "text" : 'Type of Payment',
          "value" : 'pdbs_analysis_type_of_payment',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });
	   more.push( {
          "id" : 'pdbs_analysis_bank_account',
          "text" : 'Bank Account',
          "value" : 'pdbs_analysis_bank_account',
          "showcheck" : true,
          complete : true,
          "isexpand" : false,
          "checkstate" : 0,
          "hasChildren" : false
       });	   
	   portfolio.push( {
  	      "id" : "mote",
  	      "text" : "More",
  	      "value" : "more",
  	      "showcheck" : true,
  	      complete : true,
  	      "isexpand" : false,
  	      "checkstate" : 0,
  	      "hasChildren" : true,
  	      "ChildNodes" : more
  	    });  
  root["ChildNodes"] = portfolio;   
  return root; 
}

treedata = [createNode()];
