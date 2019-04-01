<?php
if ($single_template) {

    foreach ($single_template as $value) {

        $id = $value['id'];
        }
    ?>

    <div class = "tab-content">
        <div id = "tech_details" class = "tab-pane fade in active">
            <div class="col-md-3"><strong>Template Name  </strong></div><div class="col-md-9"> :   <?php echo $value['temp_name']; ?> </div>
            <div class="col-md-3"><strong>Template Type  </strong></div><div class="col-md-9"> :   <?php echo $value['temp_type']; ?> </div>
            <div class="col-md-3"><strong>Message Body  </strong></div><div class="col-md-9"> : <?php echo $value['temp_message']; ?></div>
        </div>
    </div>
    <div class="clearfix"></div>
    <style>
        .tab-content{
            padding: 15px;
            line-height: 30px;
        }
        #tech_details img{
            max-width: 150px;
            max-height: 100px;
            border: 5px solid #003bb3;
        }

    </style>

<?php

} else {
    echo '<i class="icon fa fa-times"></i><strong> Sorry ! </strong>  No records have found .';
}
?>
