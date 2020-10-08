//enable debug console logs
var debug = false;

//ajax calls
var myRequest = null;
var categoryObj = {};
var userObj = {};

function CreateXmlHttpReq(handler) {
var xmlhttp = null;
xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = handler;
return xmlhttp;
}

function myHandler() {
  if (myRequest.readyState == 4 && myRequest.status == 200) {
	  if(debug){console.log(myRequest.responseText);}
  }
}

//return first letter capitalized
function ucfirst(string){
	return string.charAt(0).toUpperCase() + string.slice(1);
};

//category retrive
function getCategory(){
	$.getJSON('action/data.php?categories=true', function(json) {
		for (x in json) {
			categoryObj[json[x].cat_id]=json[x].cat_name;
		}
	});
	
	categoryObj[9999]='Undefined';
	
	if(debug){console.log(categoryObj);}
}

getCategory();

function printCategory(){
	return categoryObj;
}

//user retrive
function getUser(){
	$.getJSON('action/data.php?users=true', function(json) {
		for (x in json) {
			userObj[json[x].usr_id]=ucfirst(json[x].usr_id);
		}
	});
	
	if(debug){console.log(userObj);}
}

getUser();

function printUser(){
	return userObj;
}

//date editor
var dateEditor = function(cell, onRendered, success, cancel){
    var cellValue = moment(cell.getValue(), "YYYY-MM-DD").format('YYYY-MM-DD'),
    input = document.createElement("input");
    input.setAttribute("type", "date");
    input.style.padding = "4px";
    input.style.width = "100%";
    input.style.boxSizing = "border-box";
    input.value = cellValue;

    onRendered(function(){
        input.focus();
        input.style.height = "100%";
    });

    function onChange(){
        if(input.value != cellValue){
            success(moment(input.value, "YYYY-MM-DD").format('YYYY-MM-DD'));
        }else{
            cancel();
        }
    }

    //submit new value on blur or change
    input.addEventListener("blur", onChange);

    //submit new value on enter
    input.addEventListener("keydown", function(e){
        if(e.keyCode == 13){
            onChange();
        }

        if(e.keyCode == 27){
            cancel();
        }
    });

    return input;
};

//avoid editing of existing usr_id
var checkIfBlank = function(cell){

    //get row data
    var data = cell.getRow().getData();
	if(debug){console.log(data);}
	
	if (data.usr_id == null){
		return true;
	}else{
		return false;
	}
}

//tabulator users
const usersTable = new Tabulator("#users-table", {
	maxHeight:"300px",
	layout:"fitColumns",
	selectable:true,
 	ajaxURL:"./action/data.php?users=true",
	columns:[
		{formatter:"rowSelection", titleFormatter:"rowSelection", width:50, hozAlign:"center", headerSort:false},
		{title:"Id", field:"usr_id", validator:"required", editor:"input", editable:checkIfBlank},
		{title:"Mail", field:"email", validator:"required", editor:"input"},
		{title:"Password", field:"password", validator:"required", editor:"input"},
		{title:"Last login", field:"tms_upd", width:200, hozAlign:"center", sorter:"date", formatter:"datetime", formatterParams:{
			inputFormat:"YYYY-MM-DD",
			outputFormat:"DD/MM/YYYY",
			invalidPlaceholder:"(invalid date)"}
		},
		{title:"Admin", field:"admin", width:100, hozAlign:"center", formatter:"tickCross", editor:true, formatterParams:{
			allowEmpty:true,
			allowTruthy:true,
			tickElement:"<i class='fa fa-check'></i>",
			crossElement:"<i class='fa fa-times'></i>"}
		},
		{title:"Valid", field:"valid", width:100, hozAlign:"center", formatter:"tickCross", editor:true, formatterParams:{
			allowEmpty:true,
			allowTruthy:true,
			tickElement:"<i class='fa fa-check'></i>",
			crossElement:"<i class='fa fa-times'></i>"}
		},
		{title:"Color", field:"color", width:80, hozAlign:"center", validator:"required", editor:"input", formatter:"color"}
		],
    placeholder:"No Data Set",
	cellEdited:function(cell){
		if(debug){console.log(cell);}
		var id = cell._cell.row.data.usr_id;
		var col = cell._cell.column.field;
		var value = cell._cell.value;
		if(Object.values(userObj).indexOf(id) > -1){
			if(debug){console.log(col, value, id);}
			myRequest = CreateXmlHttpReq(myHandler);
			myRequest.open("POST","action/editor.php");
			myRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			myRequest.send("pk="+id+"&col="+col+"&value="+value+"&action=usr-edit");
			usersTable.setData();
		}else{
			var usr_id = cell._cell.row.data.usr_id;
			var email = cell._cell.row.data.email;
			if (cell._cell.row.data.admin){
				var admin = cell._cell.row.data.admin;
			}else{
				var admin = false;
			}
			if (cell._cell.row.data.valid){
				var valid = cell._cell.row.data.valid;
			}else{
				var valid = false;
			}
			var color = cell._cell.row.data.color;
			if(usr_id && email && color){
				if(debug){console.log(usr_id, email, admin, valid, color);}
				myRequest = CreateXmlHttpReq(myHandler);
				myRequest.open("POST","action/editor.php");
				myRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				myRequest.send("usr_id="+usr_id+"&email="+email+"&admin="+admin+"&valid="+valid+"&color="+color+"&action=usr-save");
				usersTable.setData();
				getUser();
			}else{
				if(debug){console.log('no complete data');}
			}
		}
    }
});

//add trigger
document.getElementById("users-add").addEventListener("click", function(){
    usersTable.addRow({}, true);
});

//delete trigger
document.getElementById("users-delete").addEventListener("click", function(){
	selectedRows = usersTable.getSelectedRows();
	for (x in selectedRows) {
		if(debug){console.log(selectedRows[x]._row.data.mov_id);}
		var id = selectedRows[x]._row.data.usr_id;
		myRequest = CreateXmlHttpReq(myHandler);
	    myRequest.open("POST","action/editor.php");
	    myRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    myRequest.send("pk="+id+"&action=usr-delete");
	}
	usersTable.setData();
});


//tabulator categories
const categoriesTable = new Tabulator("#categories-table", {
	maxHeight:"500px",
	layout:"fitColumns",
	selectable:true,
 	ajaxURL:"./action/data.php?categories=true",
	columns:[
		{formatter:"rowSelection", titleFormatter:"rowSelection", width:50, hozAlign:"center", headerSort:false},
		{title:"Id", field:"cat_id", width:50, visible:false},
		{title:"Nome", field:"cat_name", width:150, hozAlign:"center", validator: "required", editor:"input"},
		{title:"Padre", field:"parent_id", width:150, hozAlign:"center", formatter: "lookup", formatterParams: printCategory, editor: "select", editorParams: {values: printCategory}},
		{title:"Income", field:"income", width:100, hozAlign:"center", formatter:"tickCross", editor:true, formatterParams:{
			allowEmpty:true,
			allowTruthy:true,
			tickElement:"<i class='fa fa-check'></i>",
			crossElement:"<i class='fa fa-times'></i>"}
		},
		{title:"Entry", field:"num_mov", width:80, hozAlign:"center"},
		{title:"Keyword", field:"keyword", formatter:"textarea", editor:"input"},
		{title:"Color", field:"color", width:80, hozAlign:"left", formatter:"color", validator:"required", editor:"input"}
		],
    placeholder:"No Data Set",
	cellEdited:function(cell){
		if(debug){console.log(cell);}
		var id = cell._cell.row.data.cat_id;
		var col = cell._cell.column.field;
		var value = cell._cell.value;
		if(col == 'parent_id' && value == 9999){
			value = id;
		}
		if(id){
			if(debug){console.log(col, value, id);}
			myRequest = CreateXmlHttpReq(myHandler);
			myRequest.open("POST","action/editor.php");
			myRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			myRequest.send("pk="+id+"&col="+col+"&value="+value+"&action=cat-edit");
			categoriesTable.setData();
		}else{
			if (cell._cell.row.data.parent_id){
				var parent_id = cell._cell.row.data.parent_id;
			}else{
				var parent_id = 9999;
			}
			var cat_name = cell._cell.row.data.cat_name;
			var color = cell._cell.row.data.color;
			if (cell._cell.row.data.income){
				var income = cell._cell.row.data.income;
			}else{
				var income = false;
			}
			if (cell._cell.row.data.keyword){
				var keyword = cell._cell.row.data.keyword;
			}else{
				var keyword = ' ';
			}
			if(cat_name && color){
				if(debug){console.log(parent_id, cat_name, color, income, keyword);}
				myRequest = CreateXmlHttpReq(myHandler);
				myRequest.open("POST","action/editor.php");
				myRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				myRequest.send("parent_id="+parent_id+"&cat_name="+cat_name+"&color="+color+"&income="+income+"&keyword="+keyword+"&action=cat-save");
				categoriesTable.setData();
			}else{
				if(debug){console.log('no complete data');}
			}
		}
    }
});

//add trigger
document.getElementById("categories-add").addEventListener("click", function(){
    categoriesTable.addRow({}, true);
});

//delete trigger
document.getElementById("categories-delete").addEventListener("click", function(){
	selectedRows = categoriesTable.getSelectedRows();
	for (x in selectedRows) {
		if(debug){console.log(selectedRows[x]._row.data.cat_id);}
		var id = selectedRows[x]._row.data.cat_id;
		myRequest = CreateXmlHttpReq(myHandler);
	    myRequest.open("POST","action/editor.php");
	    myRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    myRequest.send("pk="+id+"&action=cat-delete");
	}
	categoriesTable.setData();
});