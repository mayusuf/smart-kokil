<?php
if ($all_client_task) {
    $data = array();

    $no = 1;
    foreach ($all_client_task as $value) {

        $id = $value['id'];

        $row = array();
        $row[] = "<td>" . $no++ . "</td>";
        $row[] = "<tr><td>" . $value['accountName'] . "</td>";
        $row[] = "<tr><td>" . $value['accountNo'] . "</td>";
        $row[] = "<tr><td>" . $value['client_status'] . "</td>";
        $row[] = "<td style='text-align:center;'><a data-toggle='tooltip' class='btn btn-primary updateClientTask'  id='" . $id . "' title='Edit'> <i class='fa fa-pencil-square-o'></i> </a>
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