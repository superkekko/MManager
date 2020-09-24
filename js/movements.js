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

//tabulator movements
const table = new Tabulator("#movements-table", {
	height:"500px",
	layout:"fitColumns",
	selectable:true,
 	ajaxURL:"./action/data.php?transaction=true",
	columns:[
		{formatter:"rowSelection", titleFormatter:"rowSelection", width:50, hozAlign:"center", headerSort:false},
		{title:"Id", field:"mov_id", width:50, visible:false, print:false},
		{title:"Value", field:"val", width:100, hozAlign:"center", formatter:"money", validator:["required","numeric"], formatterParams:{
			decimal:",",
			thousand:".",
			symbol:"€ ",
			precision:false
		},
		editor:"number"},
		{title:"Date", field:"dat_mov", width:100, hozAlign:"center", sorter:"date", formatter:"datetime", validator:"required", formatterParams:{
			inputFormat:"YYYY-MM-DD",
			outputFormat:"DD/MM/YYYY",
			invalidPlaceholder:"(invalid date)"
		},
		editor:dateEditor},
		{title:"Utente", field:"usr_mov", width:150, hozAlign:"center", validator:"required", formatter: "lookup", formatterParams: printUser, editor: "select", editorParams: {values: printUser}},
		{title:"Categoria", field:"cat_id", width:150, hozAlign:"center", validator:"required", formatter: "lookup", formatterParams: printCategory, editor: "select", editorParams: {values: printCategory}},
		{title:"Note", field:"note", formatter:"textarea", editor:"input"}
		],
    placeholder:"No Data Set",
	cellEdited:function(cell){
		if(debug){console.log(cell);}
		var id = cell._cell.row.data.mov_id;
		var col = cell._cell.column.field;
		var value = cell._cell.value;
		if(id){
			if(debug){console.log(col, value, id);}
			myRequest = CreateXmlHttpReq(myHandler);
			myRequest.open("POST","action/editor.php");
			myRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			myRequest.send("pk="+id+"&col="+col+"&value="+value+"&action=mov-edit");
			table.setData();
		}else{
			var cat_id = cell._cell.row.data.cat_id;
			var dat_mov = cell._cell.row.data.dat_mov;
			var usr_mov = cell._cell.row.data.usr_mov;
			if (cell._cell.row.data.note){
				var note = cell._cell.row.data.note;
			}else{
				var note = '';
			}
			var val = cell._cell.row.data.val;
			if(cat_id && dat_mov && dat_mov != 'Invalid date' && usr_mov && val){
				if(debug){console.log(cat_id, dat_mov, usr_mov, val);}
				myRequest = CreateXmlHttpReq(myHandler);
				myRequest.open("POST","action/editor.php");
				myRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				myRequest.send("cat="+cat_id+"&value="+val+"&usr="+usr_mov+"&note="+note+"&date="+dat_mov+"&action=mov-save");
				table.setData();
			}else{
				if(debug){console.log('no complete data');}
			}
		}
    }
});

//download trigger
document.getElementById("download").addEventListener("click", function(){
    table.download("xlsx", "movements.xlsx", {sheetName:"Movements"});
});

//add trigger
document.getElementById("add").addEventListener("click", function(){
    table.addRow({}, true);
});

//delete trigger
document.getElementById("delete").addEventListener("click", function(){
	selectedRows = table.getSelectedRows();
	for (x in selectedRows) {
		if(debug){console.log(selectedRows[x]._row.data.mov_id);}
		var id = selectedRows[x]._row.data.mov_id;
		myRequest = CreateXmlHttpReq(myHandler);
	    myRequest.open("POST","action/editor.php");
	    myRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    myRequest.send("pk="+id+"&action=mov-delete");
	}
	table.setData();
});

//category retrive
function getCategory(){
	myRequest = CreateXmlHttpReq(myHandler);
	myRequest.open("GET","action/data.php?categories=true", false);
	myRequest.send();
	tempObj = JSON.parse(myRequest.responseText);
	
	for (x in tempObj) {
		categoryObj[tempObj[x].cat_id]=tempObj[x].cat_name;
	}
	
	if(debug){console.log(categoryObj);}
	
	table.redraw();
}

getCategory();

function printCategory(){
	return categoryObj;
}

//user retrive
function getUser(){
	myRequest = CreateXmlHttpReq(myHandler);
	myRequest.open("GET","action/data.php?users=true", false);
	myRequest.send();
	tempObj = JSON.parse(myRequest.responseText);
	
	for (x in tempObj) {
		userObj[tempObj[x].usr_id]=tempObj[x].usr_id;
	}
	
	if(debug){console.log(userObj);}
	
	table.redraw();
}

getUser();

function printUser(){
	return userObj;
}