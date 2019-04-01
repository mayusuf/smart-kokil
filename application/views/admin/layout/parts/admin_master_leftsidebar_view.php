<div class = "navbar-default sidebar" role = "navigation">
    <div class = "sidebar-nav navbar-collapse">
        <ul class = "nav" id = "side-menu">
            <li>
                <a href = "<?php echo site_url('admin/admin_dashboard/index'); ?>"><i class = "fa fa-dashboard fa-fw"></i>
                    <?php echo $this->lang->line('dashboard'); ?></a>
            </li>
            <li>
                <a href = "#"> <i class = "fa fa-cog fa-fw"> </i> Settings <span class = "fa arrow"></span></a>
                <ul class = "nav nav-second-level">
                    <li><a href="<?php echo site_url('admin/roll/'); ?>"> <i
                                class="fa fa-edit fa-fw"></i> Roll </a></li>
                    <li><a href = "<?php echo site_url('admin/tax/'); ?>"><i
                                class = "fa fa-circle-o fa-fw "></i> Types </a></li>
                    <li><a href = "<?php echo site_url('admin/circle/'); ?>"><i
                                class = "fa fa-circle-o fa-fw"></i> Circle </a></li>
                    <li><a href = "<?php echo site_url('admin/client_status/'); ?>"><i
                                class = "fa fa-circle-o fa-fw"></i> Client Status </a></li>
                    <li><a href = "<?php echo site_url('admin/client_task/'); ?>"><i
                                class = "fa fa-circle-o fa-fw"></i> Client Task </a></li>
                </ul>
            </li>
            <li>
                <a href = "#"> <i class = "fa fa-list fa-fw"> </i> <?php echo $this->lang->line('account_settings'); ?> <span class = "fa arrow"></span></a>
                <ul class = "nav nav-second-level">
                    <li><a href = "<?php echo site_url('admin/account_settings/create_new_account'); ?>"><i
                                class = "fa fa-circle-o fa-fw "></i> <?php echo $this->lang->line('new_account'); ?> </a></li>
                    <li><a href = "<?php echo site_url('admin/account_settings/manage_accounts_settings'); ?>"><i
                                class = "fa fa-circle-o fa-fw"></i> <?php echo $this->lang->line('manage_accounts'); ?> </a></li>
                </ul>
            </li>
            <li>
                <a href = "#"> <i class = "fa fa-dollar fa-fw"> </i> <?php echo $this->lang->line('transaction'); ?> <span class = "fa arrow"></span></a>
                <ul class = "nav nav-second-level">
                    <li><a href = "<?php echo site_url('admin/account_transactions/create_new_transactions'); ?>"><i
                                class = "fa fa-circle-o fa-fw"></i>
                            <?php echo $this->lang->line('new_transaction'); ?> </a></li>
                    <li><a href = "<?php echo site_url('admin/account_transactions/manage_all_transaction'); ?>"><i
                                class = "fa fa-circle-o fa-fw"></i> <?php echo $this->lang->line('manage_transaction'); ?> </a></li>
                    <li><a href = "<?php echo site_url('admin/account_transactions/account_transactions_reports'); ?>"><i
                                class = "fa fa-circle-o fa-fw"></i> <?php echo $this->lang->line('reports'); ?> </a></li>
                </ul>
            </li>
            <li>
                <a href = "<?php echo site_url('admin/sms_template/index'); ?>"><i class = "fa fa-envelope fa-fw"></i>
                    <?php echo $this->lang->line('sms_temp'); ?></a>
            </li>
            <li>
                <a href = "#"> <i class = "fa fa-envelope-o fa-fw"> </i> Send Message  <span class = "fa arrow"></span></a>
                <ul class = "nav nav-second-level">
                    <li><a href = "<?php echo site_url('admin/send_sms/single_sms'); ?>"><i
                                class = "fa fa-circle-o fa-fw "></i> Single SMS </a></li>
                    <li><a href = "<?php echo site_url('admin/send_sms/'); ?>"><i
                                class = "fa fa-circle-o fa-fw"></i> Group SMS </a></li>
                </ul>
            </li>
            <li>
                <a href="<?php echo site_url('admin/user/'); ?>"> <i class="fa fa-users fa-fw" aria-hidden="true"></i>
                    User Management</a></li>
            <li><a href = "<?php echo site_url('admin/auth/admin_login/logout'); ?>"><i
                        class = "fa fa-sign-out text-aqua fa-fw"></i>
                    <span> <?php echo $this->lang->line('log_out'); ?> </span></a></li>

        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.sidebar -->
<script type = "text/javascript">
    $(document).ready(function () {

        $('.sidebar ul li').each(function () {
            if (window.location.href.indexOf($(this).find('a:first').attr('href')) > -1) {
                $(this).closest('ul').closest('li').attr('class', 'active');
                $(this).addClass('active').siblings().removeClass('active');
            }
        });

    });
</script>