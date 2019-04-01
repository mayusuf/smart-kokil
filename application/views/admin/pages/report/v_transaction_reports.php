<?php
$total = count($amount_inf);

if ($total > 0) {
    foreach ($details_inf as $key => $baby_details) {

        $id = $baby_details['accountName'];

    }

    ?>

    <!--    <input type='button' class='btn btn-default btn-icon icon-left hidden-print pull-right' onClick='PrintElem();' value='Print Invoice'/>-->
    <input type='button' id='btn' class='btn btn-success pull-right' value='Print The Invoice' style='margin-top: 20px;' onClick='printContent();'>
    <div class='col-md-12 table-responsive panel-body' id='invoice_print'>
        <div class="col-md-6" style="text-align: left"><strong>Name : <?php echo $baby_details['accountName']; ?> <br/>
                Account Number : <?php echo $baby_details['accountNo']; ?>  </strong></div>
        <div class="col-md-6" style="text-align: right"><strong><?php echo $baby_details['contactNo']; ?> <br/>
                <?php echo $baby_details['accountAddress']; ?> </strong>
        </div>
        <table id='Accounts_invoice' class='ui celled table table-bordered table-hover'>
            <thead>
            <tr>
                <th> #</th>
                <th> Entry Date</th>
                <th> Receive</th>
                <th> Payment</th>
                <th> Discount</th>
                <th> Balance</th>
                <th> Comments</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            $Deposit = 0;
            $Withdraw = 0;
            $Discount = 0;
            $Balance = 0;
            foreach ($amount_inf as $key => $value) {

                $originalDate = $value['entryDate'];
                $Date = date("d-m-Y", strtotime($originalDate));
                $Deposit += $value['deposit'];
                $Withdraw += $value['withDraw'];
                $Discount += $value['discount'];
                $Balance = $value['balance'];

                $id = $value['Id'];
                $account_no = $baby_details['accountNo'];;
                $edit_url = base_url('admin/account_transactions/update_transaction/' . $id . '/' . $account_no);
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . $Date . "</td>";
                echo "<td>" . $value['deposit'] . "</td>";
                echo "<td>" . $value['withDraw'] . "</td>";
                echo "<td>" . $value['discount'] . "</td>";
                echo "<td class=''>" . $value['balance'] . "</td>";
                echo "<td class=''>" . $value['comments'] . "</td>";
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
            <th> Balance = <?php echo $value['balance']; ?></th>
            <th></th>
            </tfoot>
        </table>
    </div>
<?php
} else {
    echo '
<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i><strong> Sorry ! </strong> No records have found .</div>';
}
?>