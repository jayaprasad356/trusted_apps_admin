<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;


if (isset($_POST['btnUpdate'])) {
    
    $whatsapp_group = $db->escapeString(($_POST['whatsapp_group']));
    $telegram_channel = $db->escapeString(($_POST['telegram_channel']));
    $min_withdrawal = $db->escapeString(($_POST['min_withdrawal']));
    $max_withdrawal = $db->escapeString(($_POST['max_withdrawal']));
    $pay_video = $db->escapeString(($_POST['pay_video']));
    

            $error = array();
            $sql_query = "UPDATE settings SET whatsapp_group='$whatsapp_group',telegram_channel='$telegram_channel',min_withdrawal='$min_withdrawal',max_withdrawal='$max_withdrawal',pay_video='$pay_video' WHERE id=1";
            $db->sql($sql_query);
            $result = $db->getResult();
            if (!empty($result)) {
                $result = 0;
            } else {
                $result = 1;
            }

            if ($result == 1) {
                
                $error['update'] = "<section class='content-header'>
                                                <span class='label label-success'>Settings Updated Successfully</span> </section>";
            } else {
                $error['update'] = " <span class='label label-danger'>Failed</span>";
            }
        }
  

// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM settings WHERE id = 1";
$db->sql($sql_query);
$res = $db->getResult();
?>
<section class="content-header">
    <h1>Settings</h1>
    <?php echo isset($error['update']) ? $error['update'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
           
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">

                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form name="delivery_charge" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                            <div class="row">
                            <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Whatsapp Group</label><br>
                                        <input type="text" class="form-control" name="whatsapp_group" value="<?= $res[0]['whatsapp_group'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Telegram Channel</label><br>
                                        <input type="text" class="form-control" name="telegram_channel" value="<?= $res[0]['telegram_channel'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Min Withdrawal</label><br>
                                        <input type="number" class="form-control" name="min_withdrawal" value="<?= $res[0]['min_withdrawal'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Max Withdrawal</label><br>
                                        <input type="number" class="form-control" name="max_withdrawal" value="<?= $res[0]['max_withdrawal'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Pay Video</label><br>
                                        <input type="text" class="form-control" name="pay_video" value="<?= $res[0]['pay_video'] ?>">
                                    </div>
                                </div>
								
                            </div>
                           <br>
                    </div>
                  
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnUpdate">Update</button>
                    </div>

                </form>

            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>

<?php $db->disconnect(); ?>

<script>
    var changeCheckbox = document.querySelector('#challenge_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#challenge_status').val(1);

        } else {
            $('#challenge_status').val(0);
        }
    };
</script>

<script>
    var changeCheckbox = document.querySelector('#withdrawal_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#withdrawal_status').val(1);

        } else {
            $('#withdrawal_status').val(0);
        }
    };
</script>
