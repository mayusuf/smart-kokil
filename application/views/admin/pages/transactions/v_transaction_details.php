<?php
if ($all_transaction) {

    ?>
    <h3 style='text-align: center; color: #4a64a6'> Account Number : <?php echo $account_no; ?>  </h3>
    <table id='all_accounts_transactions' class='table table-bordered table-hover'>
        <thead>
        <tr>
            <th>#</th>
            <th> Entry Date</th>
            <th> Receive</th>
            <th> Payment</th>
            <th> Discount </th>
            <th> Balance </th>
            <th> Comments</th>
            <th> Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        $Deposit = 0;
        $Withdraw = 0;
        $Discount = 0;
        $Balance = 0;
        foreach ($all_transaction as $key => $value) {

            $originalDate = $value['entryDate'];
            $Date = date("d-m-Y", strtotime($originalDate));
            $Deposit += $value['deposit'];
            $Withdraw += $value['withDraw'];
            $Discount += $value['discount'];
            $Balance = $value['balance'];

            $id = $value['Id'];
            $account_no = $account_no;
            $edit_url = base_url('admin/account_transactions/update_transaction/' . $id . '/' . $account_no);
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $Date . "</td>";
            echo "<td>" . $value['deposit'] . "</td>";
            echo "<td>" . $value['withDraw'] . "</td>";
            echo "<td>" . $value['discount'] . "</td>";
            echo "<td class=''>" . $value['balance'] . "</td>";
            echo "<td class=''>" . $value['comments'] . "</td>";
            echo "<td style='text-align:center;'><a data-toggle='tooltip' class='btn btn-primary'  href='" . $edit_url . "' title='Edit'> <i class='fa fa-pencil-square-o'></i> </a>";
            echo " <a data-toggle='tooltip' class='btn btn-danger deleteinformation'  id='" . $id . "' acc_no ='" . $account_no . "'  title='Delete'> <i class='fa fa-trash-o'></i> </a></td>";
            echo "</tr>";
        }
        ?>
        </tbody>
        <tfoot>
        <th>#</th>
        <th></th>
        <th> Receive = <?php echo $Deposit; ?></th>
        <th> Payment = <?php echo $Withdraw; ?></th>
        <th> Discount = <?php echo $Discount; ?></th>
        <th> Balance = <?php echo $value['balance'] ; ?></th>
        <th> </th>
        <th></th>
        </tfoot>
    </table>
<?php

} else {
    echo '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i><strong> Sorry ! </strong>  No transaction have found .</div>';
}
?>