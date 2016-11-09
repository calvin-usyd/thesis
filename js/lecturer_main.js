"use strict"

var urls = {
	bAddEdit : "/groupEditAdd",
	markEdit : "/professor/markEdit",
	gUAdd : "/admin/groupConfigure",
	
	gDelete : "/groupDelete",
	gUDelete : "/groupUserDelete",
	
	setGuiTheme : "/setGuiTheme"
};

$(document).ready(function(){
	
	 $("#btnExportExcel").click(function () {
		$("#studentMarkReport").battatech_excelexport({
			containerid: "studentMarkReport"
		   , datatype: "Table"
		});
	});
	
	$('#btnGroupUserAdd').bind('click', function(){
		var formId = "#groupUserForm";

		$.post(
			urls.gUAdd,
			$( formId ).serialize(),
			addEditResponse
		)
	});
	
	$('#btnGroupAddEdit').bind('click', function(){
		var formId = "#groupForm";
		var $elem = $( formId + " input[name=name]");
		
		if (!hasError($elem)){			
			$.post(
				urls.bAddEdit,
				$( formId ).serialize(),
				addEditResponse
			)
		}
	});
	
	$('#btnMarkEdit').bind('click', function(){		
		var formId = "#markForm";
		var $elem = $( formId + " input[name=mark]");
		
		if (!hasError($elem)){
			$.post(
				urls.markEdit,
				$( formId ).serialize(),
				addEditResponse
			)
		}
	});
	
	function hasError(elem){
		if (elem.val() === ''){
			elem.focus();
			elem.closest(".form-group").addClass('has-error');
			return true;
		}
		
		return false;
	}
	
	function addEditResponse(json){
		var d = $.parseJSON(json);
		
		if (d[0] == 'success'){
			location.reload();
			
		}else{
			alert(d[1]);
		}
	}
});

function changeGuiTheme(event, theme){
	event.preventDefault(); 
	
	$("#guiTheme").attr('href', '//bootswatch.com/'+theme+'/bootstrap.min.css');
	
	$.post(
		urls.setGuiTheme,
		{'guiTheme':theme},
		successChange
	)
}
function successChange(data){
	//console.log(data);
	//console.log('success');
}
function populateMark(idPart){
	var formN = "#markForm";
	
	$('.progress').removeClass('hide');
	$(formN).addClass('hide');
	
	$(formN + ' .subTitleAction').html('Edit').removeClass('label-danger').addClass('label-info');
	$(formN + ' input[name=studentId]').val($('#studentId'+idPart).attr('data'));
	$(formN + ' input[name=mark]').val($('#mark'+idPart).attr('data'));
	
	setTimeout(function(){
		$('.progress').addClass('hide');
		$(formN).removeClass('hide');
	}, 500);
}
function populateGroup(idPart){
	var formN = "#groupForm";
	
	$('.progress').removeClass('hide');
	$(formN).addClass('hide');
	
	$(formN + ' .subTitleAction').html('Edit').removeClass('label-danger').addClass('label-info');
	$(formN + ' input[name=name]').val($('#groupName'+idPart).attr('data'));
	$(formN + ' input[name=groupLongId]').val($('#groupLongId'+idPart).attr('data'))
	
	setTimeout(function(){
		$('.progress').addClass('hide');
		$(formN).removeClass('hide');
	}, 500);
}
function reset2Add(formN){
	$('#'+formN+' .subTitleAction').html('Add New').removeClass('label-info').addClass('label-danger');
}
function deleteGroup(id){
	if (confirm("Are you to delete this data permanently")){
		$.post(
			urls.gDelete,
			{'groupLongId':id},
			deleteResponse
		)
	}
}
function deleteGroupUser(id, groupLongId){
	if (confirm("Are you to delete this data permanently")){
		$.post(
			urls.gUDelete,
			{'studentId':id, 'groupLongId':groupLongId},
			deleteResponse
		)
	}
}
function deleteResponse(json){
	var d = $.parseJSON(json);
	
	if (d[0] == 'success'){
		location.reload();
		
	}else{
		alert(d[1]);
	}
}