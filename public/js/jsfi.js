function checkNum(x)
	{	 
	  var s_len=x.value.length ;
	  var s_charcode = 0;
	    for (var s_i=0;s_i<s_len;s_i++)
	    {
	     s_charcode = x.value.charCodeAt(s_i);
	     if(!((s_charcode>=48 && s_charcode<=57)))
	      {
	         alert("Only Numeric Values Allowed");
	          x.value='';
	         x.focus();
	        return false;
	      }
	    }
	    return true;
	}
function totalAge0_17()
{	
	var total_t = 0;
	var total_f = 0;
	for(var i=2; i<=3; i++){
		var val_t = document.getElementById('0_17_T['+i+']').value;
		if(isNaN(val_t)) val_t=0;										    									
		total_t=(total_t-0)+(val_t-0);
		
		var val_f = document.getElementById('0_17_F['+i+']').value;
		if(isNaN(val_f)) val_f=0;										    									
		total_f=(total_f-0)+(val_f-0);
	}
	var dis_t  = document.getElementById('0_17_T[4]').value;
	total_t = total_t - dis_t;
	document.getElementById('0_17_T[5]').value = total_t;
	
	var dis_f  = document.getElementById('0_17_F[4]').value;
	total_f = total_f - dis_f;
	document.getElementById('0_17_F[5]').value = total_f;
	
}
function totalAge18_24(){
	var total_t = 0;
	var total_f = 0;
	for(var i=2; i<=3; i++){
		var val_t = document.getElementById('18_24_T['+i+']').value;
		if(isNaN(val_t)) val_t=0;										    									
		total_t=(total_t-0)+(val_t-0);
		
		var val_f = document.getElementById('18_24_F['+i+']').value;
		if(isNaN(val_f)) val_f=0;										    									
		total_f=(total_f-0)+(val_f-0);
	}
	var dis_t  = document.getElementById('18_24_T[4]').value;
	total_t = total_t - dis_t;
	document.getElementById('18_24_T[5]').value = total_t;
	
	var dis_f  = document.getElementById('18_24_F[4]').value;
	total_f = total_f - dis_f;
	document.getElementById('18_24_F[5]').value = total_f;
}
function families(){
	var total_t = 0;
	for(var i=2; i<=3; i++){
		var val_t = document.getElementById('families['+i+']').value;
		if(isNaN(val_t)) val_t=0;										    									
		total_t=(total_t-0)+(val_t-0);
		
	}
	var dis_t  = document.getElementById('families[4]').value;
	total_t = total_t - dis_t;
	document.getElementById('families[5]').value = total_t;
	

}
function other(){
	var total_t = 0;
	for(var i=2; i<=3; i++){
		var val_t = document.getElementById('other['+i+']').value;
		if(isNaN(val_t)) val_t=0;
		total_t=(total_t-0)+(val_t-0);
		
	}
	var dis_t  = document.getElementById('other[4]').value;
	total_t = total_t - dis_t;
	document.getElementById('other[5]').value = total_t;
}

function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;	
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
} 

function format_percent($number, $decimals) {
	return $number.toFixed($decimals) + " %";
}
function print(){	
	document.body.innerHTML = document.getElementById("printreport").innerHTML;
	drawChart();
	window.print();
}

/* @author: May Dara
 * @desciption: delete multiple records with using checkbox and alert box
 * @param url which is redirected after delete successfully
 * */
function deleteRecord(url) {
	var cbs = $("input:checkbox"); //find all checkboxes
	var nbCbs = cbs.size(); //the number of checkboxes
	var checked = $("input[@type=checkbox]:checked"); //find all checked checkboxes + radio buttons
	var nbChecked = checked.size();
	if(nbChecked < 1) {
		alert("Please select record to delete!");
	} else {
		var answer = confirm("Are you sure you want to delete it?")
	    if (answer){
			id = new Array();
			var i = 0;
			checked.each(function() {
				id[i] = $(this).val();
				i++;
			});
			window.location.href = url+'/id/'+id;
	    } else {
	    	alert("Sorrty can't delete it!");
	    }
	}
}
//check status value if status = 1 alert message
function checkStatusApprove(elementID,value,message) {
	if($(elementID).val() == value) {
		var answer=confirm(message);
		if (answer==true)
		{
			return true;
		} else {
			return false;
		}
	}
}
//check status value if status = 1 alert message
function statusValidate(elementID,value,message) {
	if($(elementID).val() >= value) {
		var answer=confirm(message);
		if (answer==true)
		{
			return true;
		} else {
			return false;
		}
	}
}