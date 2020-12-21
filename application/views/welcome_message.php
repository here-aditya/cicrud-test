<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to ABC LTD</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

	<style type="text/css">
	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: #aeaeae;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	h4 {
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	form {
		border: 1px solid #D0D0D0;
		padding: 6px;
	}

	#add_form, #show_tbl {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}

	#frm_err {
		font-size: 12px;
		padding: 3px;
		color: red
	}

	#frm_succ {
		font-size: 12px;
		padding: 3px;
		color: green
	}

	table {
		border-collapse: collapse;
	}
	th, td {
		padding: 15px;
		text-align: left;
		border: 1px solid #ddd;
	}
	tr:hover {background-color: #f5f5f5;}
	</style>
</head>
<body>

<div id="container">
	<h1>Welcome to ABC LTD!</h1>
	<h4>CREATE, READ, UPDATE, DELETE Opeartion for Employees :</h4>

	<div id="add_form">
		<div id="frm_err"></div>
		<div id="frm_succ"></div>
		<form id="emp_form">
			<input type ="hidden" id="hid_emp_id">
			<h2><span id="frm_type">ADD</span> Employee</h2><hr>
			<p><label for="emp_f_name">Employee First Name : <input type="text" id="emp_f_name" name="emp_f_name"></label></p>
			<p><label for="emp_l_name">Employee Last Name : <input type="text" id="emp_l_name" name="emp_l_name"></label></p>
			<p>
				<label for="emp_dept">Employee Department : 
					<select id="emp_dept" name="emp_dept">
						<option value=""></option>
						<option value="admin">ADMIN</option>
						<option value="account">ACCOUNTS</option>
						<option value="sales">SALES</option>
						<option value="itsoft">IT SOFTWARE</option>
						<option value="ithard">IT HARDWARE</option>
					</select>
				</label>
			</p>
			<p><label for="emp_age">Employee Age : <input type="number" id="emp_age" size="3" name="emp_age"></label></p>
			<button type="button" onclick="saveData(this)" value="add" id="frm_save">Save</button>
			<button type="button" onclick="cancelForm()">Cancel</button>
		<form>
	</div>

	<div id="show_tbl">
		<h2>Employee LIST</h2>
		<label for="emp_dept">Filter Department : 
			<select onclick="filterDepartment(this)">
				<option value=""></option>
				<option value="admin">ADMIN</option>
				<option value="account">ACCOUNTS</option>
				<option value="sales">SALES</option>
				<option value="itsoft">IT SOFTWARE</option>
				<option value="ithard">IT HARDWARE</option>
			</select>
		</label>
		<hr>
		<div id="tbl_cont"></div>
		<div id="dialog-confirm" title="Empty the recycle bin?" style="display:none">
			<p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
			Are you sure to delete employee code <span id="empcode"></span> ?</p>
		</div>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>


<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type='text/javascript'>
function showEmpTableData() {
	$.ajax({
		url: '<?php echo base_url() ?>welcome/showEmployees/',
		type: 'get',
		dataType: 'html'
	}).done(function(htmlData) {
		$('#tbl_cont').html(htmlData);
	});
}

function editEmployee(empId) {
	$.ajax({
		url: '<?php echo base_url() ?>welcome/getEmployee/'+empId,
		type: 'get',
		dataType: 'json'
	}).done(function(data) {
		$('#hid_emp_id').val(data.id);
		$('#emp_f_name').val(data.f_name);
		$('#emp_l_name').val(data.l_name);
		$('#emp_dept').val(data.dept_code);
		$('#emp_age').val(data.age);
		$('#frm_type').text('UPDATE');
		$('#frm_save').attr('value', 'edit');
	});
}

function cancelForm() {
	$('#frm_type').text('ADD');
	$('#frm_save').attr('value', 'add');
	$('form')[0].reset();
}

function deleteEmployee(empId, empCode) {
	$('#empcode').text(empCode);

	$( "#dialog-confirm" ).dialog({
		resizable: false,
		height: "auto",
		width: 400,
		modal: true,
		buttons: {
			"Delete Employee": function() {
				$.ajax({
					url: '<?php echo base_url() ?>welcome/deleteEmployee/'+empId,
					type: 'get',
					dataType: 'html'
				}).done(function(htmlData) {
					$('#tbl_cont').html(htmlData);
				});
				$( this ).dialog( "close" );
				alert('Employee Deleted Successfullyy !');
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
}

function filterDepartment(curObj) {
	$.ajax({
		url: '<?php echo base_url() ?>welcome/showEmployees/',
		data: {'filter_type' : 'department', 'filter_val' : $(curObj).val()},
		type: 'POST',
		dataType: 'html'
	}).done(function(htmlData) {
		$('#tbl_cont').html(htmlData);
    });
}

function saveData(curObj) {
	var emp_id = $('#hid_emp_id').val();
	var url = '<?php echo base_url() ?>welcome/' + (curObj == 'save' ? 'saveEmployee' : 'saveEmployee/'+emp_id);
	var dataString = $("#emp_form").serialize();
	$.ajax({
		url: url,
		data: dataString,
		type: 'POST',
		dataType: 'json'
	}).done(function(htmlData) {
		if(htmlData.status == 'error') {
			$('#frm_err').html(htmlData.error_details).fadeOut(6000, function() {
				$('#frm_err').html('').show();
			});
		} else {
			$('#tbl_cont').html(htmlData.success_data);
			$('#frm_succ').html('Data Saved Successfully !').fadeOut(6000, function() {
				$('#frm_succ').html('').show();
			});
		}
	});
}

$(document).ready(function() {
	
	showEmpTableData();
});
</script>

</body>
</html>