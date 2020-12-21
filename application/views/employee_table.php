<style type="text/css">
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 15px;
    text-align: left;
    border: 1px solid black;
}
th {
    background-color: #dedede;
}
tr:hover {background-color: #f5f5f5;}
</style>

<table>
    <tr>
        <th>EMP CODE</th>
        <th>EMP FIRST NAME</th>
        <th>EMP LAST NAME</th>
        <th>EMP DEPARTMENT</th>
        <th>EMP AGE</th>
        <th>ACTION</th>
    </tr>
    <?php foreach ($emp_data as $key => $emp) { ?>
    <tr>
        <td>ABC-<?php echo $emp->id?></td>
        <td><?php echo $emp->f_name?></td>
        <td><?php echo $emp->l_name?></td>
        <td><?php echo $emp->dept_code?></td>
        <td><?php echo $emp->age?></td>
        <td>
            <button onclick="editEmployee(<?php echo $emp->id?>)">EDIT</button> | 
            <button onclick="deleteEmployee(<?php echo $emp->id?>, 'ABC-<?php echo $emp->id?>');">DELETE</button>
        </td>
    </tr>
    <?php } ?>
</table>

