<?php
$user_module_permission_action = $this->m_permission->check_module_action_permission('Roll', 'Edit');
if ($user_module_permission_action) {
    $e_visible = '';
} else {
    $e_visible = "style='display:none'";
}

$user_module_permission_action = $this->m_permission->check_module_action_permission('Roll', 'Delete');
if ($user_module_permission_action) {
    $d_visible = '';
} else {
    $d_visible = "style='display:none'";
}


if ($all_roll) {
    $data = array();

    $no = 1;
    foreach ($all_roll as $value) {

        $id = $value['roll_id'];
        $row = array();
        $row[] = "<td>" . $no++ . "</td>";
        $row[] = "<tr><td>" . $value['roll_name'] . "</td>";
        $row[] = "<td style='text-align:center;'><a data-toggle='tooltip' class='btn btn-primary updateRoll' $e_visible id='" . $id . "' title='Edit'> <i class='fa fa-pencil-square-o'></i> </a>
				  <a data-toggle='tooltip' class='btn btn-danger deleteinformation' $d_visible  id='" . $id . "' title='Delete'> <i class='fa fa-trash-o'></i> </a></td></tr>";

        $data[] = $row;
    }

} else {
    $data = "";
}
//output to json format
$output = array(
    "data" => $data,
);
echo json_encode($output);