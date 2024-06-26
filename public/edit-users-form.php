<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>

<?php
if (isset($_GET['id'])) {
    $ID = $db->escapeString($_GET['id']);
} else {
    // $ID = "";
    return false;
    exit(0);
}

if (isset($_POST['btnEdit'])) {

    $name = $db->escapeString($_POST['name']);
    $email= $db->escapeString($_POST['email']);
    $min_withdrawal = $db->escapeString($_POST['min_withdrawal']);
    $total_withdrawal= $db->escapeString($_POST['total_withdrawal']);
    $balance = $db->escapeString($_POST['balance']);
    $total_earnings= $db->escapeString($_POST['total_earnings']);
    $account_num = $db->escapeString(($_POST['account_num']));
    $holder_name = $db->escapeString(($_POST['holder_name']));
    $bank = $db->escapeString(($_POST['bank']));
    $branch = $db->escapeString(($_POST['branch']));
    $ifsc = $db->escapeString(($_POST['ifsc']));
    $today_income = $db->escapeString(($_POST['today_income']));
    $total_income = $db->escapeString(($_POST['total_income']));
    $registered_datetime= $db->escapeString($_POST['registered_datetime']);

    $error = array();

    if (empty($name)) {
        $error['name'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($email)) {
        $error['email'] = " <span class='label label-danger'>Required!</span>";
    }
   
    $sql_query = "UPDATE users SET name='$name',email='$email',registered_datetime='$registered_datetime',total_earnings='$total_earnings',balance='$balance',total_withdrawal='$total_withdrawal',min_withdrawal='$min_withdrawal',account_num='$account_num', holder_name='$holder_name', bank='$bank', branch='$branch', ifsc='$ifsc',today_income = '$today_income',total_income = '$total_income' WHERE id =  $ID";
    $db->sql($sql_query);
    $result = $db->getResult();             
    if (!empty($result)) {
        $error['update_users'] = " <span class='label label-danger'>Failed</span>";
    } else {
        $error['update_users'] = " <span class='label label-success'>Users Updated Successfully</span>";
    }
    if ($_FILES['profile']['size'] != 0 && $_FILES['profile']['error'] == 0 && !empty($_FILES['profile'])) {
        //image isn't empty and update the image
        $old_image = $db->escapeString($_POST['old_image']);
        $extension = pathinfo($_FILES["profile"]["name"])['extension'];

        $result = $fn->validate_image($_FILES["profile"]);
        $target_path = 'upload/images/';
        
        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = $target_path . "" . $filename;
        if (!move_uploaded_file($_FILES["profile"]["tmp_name"], $full_path)) {
            echo '<p class="alert alert-danger">Can not upload image.</p>';
            return false;
            exit();
        }
        if (!empty($old_image) && file_exists($old_image)) {
            unlink($old_image);
        }

        $upload_image = 'upload/images/' . $filename;
        $sql = "UPDATE users SET `profile`='$upload_image' WHERE `id`='$ID'";
        $db->sql($sql);

        $update_result = $db->getResult();
        if (!empty($update_result)) {
            $update_result = 0;
        } else {
            $update_result = 1;
        }

        if ($update_result == 1) {
            $error['update_users'] = " <section class='content-header'><span class='label label-success'>Users updated Successfully</span></section>";
        } else {
            $error['update_users'] = " <span class='label label-danger'>Failed to update</span>";
        }
    }
}


// create array variable to store previous data
$data = array();


$sql_query = "SELECT * FROM users WHERE id =" . $ID;
$db->sql($sql_query);
$res = $db->getResult();


if (isset($_POST['btnCancel'])) { ?>
    <script>
        window.location.href = "users.php";
    </script>
<?php } ?>
<section class="content-header">
    <h1>
        Edit Users<small><a href='users.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to users</a></small></h1>
    <small><?php echo isset($error['update_users']) ? $error['update_users'] : ''; ?></small>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
	<!-- Main row -->

	<div class="row">
		<div class="col-md-10">

			<!-- general form elements -->
			<div class="box box-primary">
				<div class="box-header with-border">
				</div><!-- /.box-header -->
				<!-- form start -->
				<form id="edit_languages_form" method="post" enctype="multipart/form-data">
					<div class="box-body">
					<div class="box-body">
                    <input type="hidden" name="old_image" value="<?php echo isset($res[0]['profile']) ? $res[0]['profile'] : ''; ?>">
				    	<div class="row">
					  	  <div class="form-group">
                              <div class="col-md-4">
                                    <label for="exampleInputEmail1">Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                    <input type="text" class="form-control" name="name" value="<?php echo $res[0]['name']; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Email</label> <i class="text-danger asterik">*</i><?php echo isset($error['email']) ? $error['email'] : ''; ?>
                                    <input type="email" class="form-control" name="email" value="<?php echo $res[0]['email']; ?>">
                                </div>
                               </div>
                             </div>
                        <br>
                        <div class="row">
                                <div class="col-md-4">
                                    <label for="exampleInputFile">Profile</label> <i class="text-danger asterik">*</i><?php echo isset($error['profile']) ? $error['profile'] : ''; ?>
                                    <input type="file" name="profile" onchange="readURL(this);" accept="image/png, image/jpeg" id="image" /><br>
                                    <img id="blah" src="<?php echo $res[0]['profile']; ?>" alt="" width="150" height="200" <?php echo empty($res[0]['profile']) ? 'style="display: none;"' : ''; ?> />
                                </div>
                                <div class="col-md-4">
                                <label for="exampleInputEmail1">Registered Datetime</label><i class="text-danger asterik">*</i>
                                    <input type="datetime-local" class="form-control" name="registered_datetime" value="<?php echo $res[0]['registered_datetime']; ?>">
                                </div>
                        </div>
                        <br>
                        <div class="row">
					  	  <div class="form-group">
                               <div class="col-md-3">
                                    <label for="exampleInputEmail1">Min Withdrawal</label> <i class="text-danger asterik">*</i><?php echo isset($error['min_withdrawal']) ? $error['min_withdrawal'] : ''; ?>
                                    <input type="number" class="form-control" name="min_withdrawal" value="<?php echo $res[0]['min_withdrawal']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Total Withdrawal</label> <i class="text-danger asterik">*</i><?php echo isset($error['total_withdrawal']) ? $error['total_withdrawal'] : ''; ?>
                                    <input type="number" class="form-control" name="total_withdrawal" value="<?php echo $res[0]['total_withdrawal']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Balance</label> <i class="text-danger asterik">*</i><?php echo isset($error['balance']) ? $error['balance'] : ''; ?>
                                    <input type="number" class="form-control" name="balance" value="<?php echo $res[0]['balance']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Total Earnings</label> <i class="text-danger asterik">*</i><?php echo isset($error['total_earnings']) ? $error['total_earnings'] : ''; ?>
                                    <input type="number" class="form-control" name="total_earnings" value="<?php echo $res[0]['total_earnings']; ?>">
                                </div>
                            </div>
                         </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                            <div class='col-md-3'>
                                    <label for="exampleInputEmail1">Today Income</label> <i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="today_income" value="<?php echo $res[0]['today_income']; ?>">
                                </div>
                                <div class='col-md-3'>
                                    <label for="exampleInputEmail1">Total Income</label> <i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="total_income" value="<?php echo $res[0]['total_income']; ?>">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                            <div class='col-md-6'>
                                    <label for="exampleInputEmail1">Account Number</label> <i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="account_num" value="<?php echo $res[0]['account_num']; ?>">
                                </div>
                                <div class='col-md-6'>
                                    <label for="exampleInputEmail1">Holder Name</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="holder_name" value="<?php echo $res[0]['holder_name']; ?>">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                            <div class="col-md-4">
                                    <label for="exampleInputEmail1">IFSC</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="ifsc" value="<?php echo $res[0]['ifsc']; ?>">
                                </div>
                                <div class="col-md-4">
                                <label for="exampleInputEmail1">Bank</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="bank" value="<?php echo $res[0]['bank']; ?>">
                                </div>
                                <div class="col-md-4">
                                <label for="exampleInputEmail1">Branch</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="branch" value="<?php echo $res[0]['branch']; ?>">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnEdit">Update</button>
                    </div>
            
                     
                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section>
<div class="separator"> </div>
<?php $db->disconnect(); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
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

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200)
                    .css('display', 'block');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>