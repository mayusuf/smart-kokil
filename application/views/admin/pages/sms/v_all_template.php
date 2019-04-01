<?php
$total = count($all_template);

if ($total > 0) {
    $data = array();

    $no = 1;
    foreach ($all_template as $value) {

        $id = $value['id'];
        $des = $value['temp_message'];
        $ShortDes = implode(' ', array_slice(explode(' ', $des), 0, 7));

        $row = array();
        $row[] = "<td>" . $no++ . "</td>";
        $row[] = "<tr><td>" . $value['temp_name'] . "</td>";
        $row[] = "<tr><td>" . $value['temp_type'] . "</td>";
        $row[] = "<td>" . $ShortDes . "</td>";
        $row[] = "<td style='text-align:center;'><a data-toggle='tooltip' class='btn btn-primary updateTemplate'  id='" . $id . "' title='Edit'> <i class='fa fa-pencil-square-o'></i> </a>
				  <a data-toggle='tooltip' class='btn btn-danger deleteinformation'  id='" . $id . "' title='Delete'> <i class='fa fa-trash-o'></i> </a>
				  <a data-toggle='tooltip' class='btn btn-info viewDetails'  id='" . $id . "' title='View Details'> <i class='fa fa-eye'></i> </a></td></tr>";
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