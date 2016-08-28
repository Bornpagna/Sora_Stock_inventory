 /**
 * generate elemenets form to html element to use in javascript
 * @param string $id
 * @param int $type
 *    	  1:text, 2:date, 3:select, 4:get id from table Provice;5:textarea;6:datetime;7 yes/no option
 * @return string    	 
 */
function generateElements(id, type, value){
	html = "";	
	switch (type) {
		case 1:
		case 2:
		case 6:
			html = '<input type="text" id="'+id+'" name="'+id+'" value="' + value + '"/>';
			break;
		case 3:		
		case 4:
		case 7:			
			html = '<select  id="'+id+'" name="'+id+'"></select>';
			break;
		case 5:
			html = '<textarea id="'+id+'" name="'+id+'">' + value + '</textarea>';
			break;
	}	    	
	return html;
}