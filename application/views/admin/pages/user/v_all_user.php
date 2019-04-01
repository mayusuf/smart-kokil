<?php
if ($all_user) {
    $data = array();

    $no = 1;
    foreach ($all_user as $value) {

        $id = $value['user_id'];

        $row = array();
        $row[] = "<tr><td>" . $no++ . "</td>";
        $row[] = " <td>" . $value['user_name'] . "</td>";
        $row[] = "<td>" . $value['user_email'] . "</td>";
        $row[] = "<td>" . $value['roll_name'] . "</td>";
        $row[] = "<td style='text-align:center;'><a data-toggle='tooltip' class='btn btn-primary updateUser'   id='" . $id . "' title='Edit'> <i class='fa fa-pencil-square-o'></i> </a>
				  <a data-toggle='tooltip' class='btn btn-success assignRole'   id='" . $id . "' title='Assign User Role'> <i class='fa fa-sign-in'></i> </a>
				  <a data-toggle='tooltip' class='btn btn-danger deleteinformation'  id='" . $id . "' title='Delete'> <i class='fa fa-trash-o'></i> </a></td></tr>";

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