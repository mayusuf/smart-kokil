<?php
if ($all_circle) {
    $data = array();

    $no = 1;
    foreach ($all_circle as $value) {

        $id = $value['id'];

        $row = array();
        $row[] = "<td>" . $no++ . "</td>";
        $row[] = "<tr><td>" . $value['circle_name'] . "</td>";
        $row[] = "<td style='text-align:center;'><a data-toggle='tooltip' class='btn btn-primary updateCircle'  id='" . $id . "' title='Edit'> <i class='fa fa-pencil-square-o'></i> </a>
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