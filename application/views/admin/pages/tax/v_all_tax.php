<?php
$total = count($all_tax);

if ($total > 0) {
    $data = array();

    $no = 1;
    foreach ($all_tax as $value) {

        $id = $value['id'];

        $row = array();
        $row[] = "<td>" . $no++ . "</td>";
        $row[] = "<tr><td>" . $value['tax_name'] . "</td>";
        $row[] = "<td style='text-align:center;'><a data-toggle='tooltip' class='btn btn-primary updateTax'  id='" . $id . "' title='Edit'> <i class='fa fa-pencil-square-o'></i> </a>
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