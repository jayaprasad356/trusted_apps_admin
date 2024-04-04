<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_POST['btnAdd'])) {
    $name = $db->escapeString($_POST['name']);
    $plan_details = $db->escapeString($_POST['plan_details']);
    $refer_link = $db->escapeString($_POST['refer_link']);

    if (empty($name)) {
        $error['name'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($plan_details)) {
        $error['plan_details'] = " <span class='label label-danger'>Required!</span>";
    }

    // Validate and process the image uploads
    if ($_FILES['logo']['size'] != 0 && $_FILES['logo']['error'] == 0 && !empty($_FILES['logo']) &&
        $_FILES['screenshot']['size'] != 0 && $_FILES['screenshot']['error'] == 0 && !empty($_FILES['screenshot'])) {

        $logo_extension = pathinfo($_FILES["logo"]["name"])['extension'];
        $screenshot_extension = pathinfo($_FILES["screenshot"]["name"])['extension'];

        // Validate logo image
        $logo_result = $fn->validate_image($_FILES["logo"]);
        $target_path = 'upload/images/';
        $logo_filename = microtime(true) . '_logo.' . strtolower($logo_extension);
        $logo_full_path = $target_path . "" . $logo_filename;

        // Validate screenshot image
        $screenshot_result = $fn->validate_image($_FILES["screenshot"]);
        $screenshot_filename = microtime(true) . '_screenshot.' . strtolower($screenshot_extension);
        $screenshot_full_path = $target_path . "" . $screenshot_filename;

        // Move uploaded logo image
        if (!move_uploaded_file($_FILES["logo"]["tmp_name"], $logo_full_path)) {
            echo '<p class="alert alert-danger">Can not upload logo image.</p>';
            return false;
            exit();
        }

        // Move uploaded screenshot image
        if (!move_uploaded_file($_FILES["screenshot"]["tmp_name"], $screenshot_full_path)) {
            echo '<p class="alert alert-danger">Can not upload screenshot image.</p>';
            return false;
            exit();
        }

        // Insert data into database
        $upload_logo = 'upload/images/' . $logo_filename;
        $upload_screenshot = 'upload/images/' . $screenshot_filename;

        $sql = "INSERT INTO apps (name, logo, screenshot, plan_details,refer_link) VALUES ('$name', '$upload_logo', '$upload_screenshot', '$plan_details','$refer_link')";
        $db->sql($sql);
    } else {
        // Image(s) not uploaded or empty, insert only the name
        $sql = "INSERT INTO apps (name, plan_details,refer_link) VALUES ('$name', '$plan_details','$refer_link')";
        $db->sql($sql);
    }

    $result = $db->getResult();
    if (!empty($result)) {
        $result = 0;
    } else {
        $result = 1;
    }

    if ($result == 1) {
        $error['add_slide'] = "<section class='content-header'><span class='label label-success'>Apps Added Successfully</span></section>";
    } else {
        $error['add_slide'] = " <span class='label label-danger'>Failed</span>";
    }
}
?>


<section class="content-header">
    <h1>Add Apps <small><a href='apps.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Apps</a></small></h1>

    <?php echo isset($error['add_slide']) ? $error['add_slide'] : ''; ?>
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
                <form name="add_slide_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                        <input type="text" class="form-control" name="name" id="name" required>
                                    </div>
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Refer Link</label> <i class="text-danger asterik">*</i><?php echo isset($error['refer_link']) ? $error['refer_link'] : ''; ?>
                                        <input type="text" class="form-control" name="refer_link" id="refer_link" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <label for="exampleInputFile">Logo</label> <i class="text-danger asterik">*</i><?php echo isset($error['logo']) ? $error['logo'] : ''; ?>
                                        <input type="file" name="logo" onchange="readURL(this, 'logo');" accept="image/png,  image/jpeg" id="logo" required/><br>
                                        <img id="logo_preview" src="#" alt="" />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputFile">ScreenShot</label> <i class="text-danger asterik">*</i><?php echo isset($error['screenshot']) ? $error['screenshot'] : ''; ?>
                                        <input type="file" name="screenshot" onchange="readURL(this, 'screenshot');" accept="image/png,  image/jpeg" id="screenshot" required/><br>
                                        <img id="screenshot_preview" src="#" alt="" />
                                    </div>
                                </div>
                            </div>
                            <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                   <label for="plan_details">Plan Details :</label> <i class="text-danger asterik">*</i><?php echo isset($error['plan_details']) ? $error['plan_details'] : ''; ?>
                                    <textarea name="plan_details" id="plan_details" class="form-control" rows="8"></textarea>
                                    <script type="text/javascript" src="css/js/ckeditor/ckeditor.js"></script>
                                    <script type="text/javascript">
                                       CKEDITOR.replace('plan_details');
                                    </script>
                                  </div>
                                </div>
                            </div>  
                        </div>
        
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Add</button>
                        <input type="reset" onClick="refreshPage()" class="btn-warning btn" value="Clear" />
                    </div>

                </form>

            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#add_mahabharatham_form').validate({

        ignore: [],
        debug: false,
        rules: {
            name: "required",
        }
    });
    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
        }
    });

    function readURL(input, image_id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#' + image_id + '_preview')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200)
                    .css('display', 'block'); // Show the image after setting the source
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<!--code for page clear-->
<script>
    function refreshPage(){
    window.location.reload();
} 
</script>
<?php $db->disconnect(); ?>
