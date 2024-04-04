<?php
include_once('includes/functions.php');
date_default_timezone_set('Asia/Kolkata');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_GET['id'])) {
    $ID = $db->escapeString($fn->xss_clean($_GET['id']));
} else {
    return false;
    exit(0);
}

if (isset($_POST['btnUpdate'])) {
    $name = $db->escapeString($fn->xss_clean($_POST['name']));
    $plan_details = $db->escapeString($fn->xss_clean($_POST['plan_details']));
    $refer_link = $db->escapeString($fn->xss_clean($_POST['refer_link']));

    $sql = "UPDATE apps SET name='$name', plan_details='$plan_details', refer_link='$refer_link' WHERE id = '$ID'";
    $db->sql($sql);
    $result = $db->getResult();
    if (!empty($result)) {
        $error['update_slide'] = " <span class='label label-danger'>Failed</span>";
    } else {
        $error['update_slide'] = " <span class='label label-success'>Apps Updated Successfully</span>";
    }

    // Handle logo update
    if ($_FILES['logo']['size'] != 0 && $_FILES['logo']['error'] == 0 && !empty($_FILES['logo'])) {
       
        $logo_extension = pathinfo($_FILES["logo"]["name"])['extension'];
        $logo_target_path = 'upload/images/';
        $logo_filename = microtime(true) . '.' . strtolower($logo_extension);
        $logo_full_path = $logo_target_path . $logo_filename;

        if (!move_uploaded_file($_FILES["logo"]["tmp_name"], $logo_full_path)) {
            echo '<p class="alert alert-danger">Can not upload logo image.</p>';
            return false;
            exit();
        }

        if (!empty($old_logo) && file_exists($old_logo)) {
            unlink($old_logo);
        }

        $sql = "UPDATE apps SET logo='$logo_full_path' WHERE id='$ID'";
        $db->sql($sql);
    }

    // Handle screenshot update
    if ($_FILES['screenshot']['size'] != 0 && $_FILES['screenshot']['error'] == 0 && !empty($_FILES['screenshot'])) {
        
        $screenshot_extension = pathinfo($_FILES["screenshot"]["name"])['extension'];
        $screenshot_target_path = 'upload/images/';
        $screenshot_filename = microtime(true) . '_screenshot.' . strtolower($screenshot_extension);
        $screenshot_full_path = $screenshot_target_path . $screenshot_filename;

        if (!move_uploaded_file($_FILES["screenshot"]["tmp_name"], $screenshot_full_path)) {
            echo '<p class="alert alert-danger">Can not upload screenshot image.</p>';
            return false;
            exit();
        }

        if (!empty($old_screenshot) && file_exists($old_screenshot)) {
            unlink($old_screenshot);
        }

        $sql = "UPDATE apps SET screenshot='$screenshot_full_path' WHERE id='$ID'";
        $db->sql($sql);
    }
}

$data = array();

$sql_query = "SELECT * FROM `apps` WHERE id = '$ID'";
$db->sql($sql_query);
$res = $db->getResult();
?>

<section class="content-header">
    <h1>Edit Apps <small><a href='apps.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Apps</a></small></h1>
    <?php echo isset($error['update_slide']) ? $error['update_slide'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                </div>
                <div class="box-header">
                    <?php echo isset($error['cancelable']) ? '<span class="label label-danger">Till status is required.</span>' : ''; ?>
                </div>

                <!-- /.box-header -->
                <!-- form start -->
                <form id='edit_slide_form' method="post" enctype="multipart/form-data">
                    <div class="box-body">
                    <input type="hidden" name="old_image" value="<?php echo isset($res[0]['image']) ? $res[0]['image'] : ''; ?>">
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-6'>
                                    <label for="exampleInputEmail1"> Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                    <input type="text" class="form-control" name="name" value="<?php echo $res[0]['name']?>">
                                </div>
                                <div class='col-md-6'>
                                    <label for="exampleInputEmail1">Refer Link</label> <i class="text-danger asterik">*</i><?php echo isset($error['refer_link']) ? $error['refer_link'] : ''; ?>
                                    <input type="text" class="form-control" name="refer_link" value="<?php echo $res[0]['refer_link']?>">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="exampleInputFile">Logo</label> <i class="text-danger asterik">*</i><?php echo isset($error['logo']) ? $error['logo'] : ''; ?>
                                    <input type="file" name="logo" onchange="readURL(this, 'logo');" accept="image/png,  image/jpeg" id="logo"/><br>
                                    <img id="logo_preview" src="<?php echo $res[0]['logo']; ?>" alt="" width="150" height="200" <?php echo empty($res[0]['logo']) ? 'style="display: none;"' : ''; ?> />
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputFile">Screenshot</label> <i class="text-danger asterik">*</i><?php echo isset($error['screenshot']) ? $error['screenshot'] : ''; ?>
                                    <input type="file" name="screenshot" onchange="readURL(this, 'screenshot');" accept="image/png,  image/jpeg" id="screenshot"/><br>
                                    <img id="screenshot_preview" src="<?php echo $res[0]['screenshot']; ?>" alt="" width="150" height="200" <?php echo empty($res[0]['screenshot']) ? 'style="display: none;"' : ''; ?> />
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                   <label for="plan_details">Plan Details :</label> <i class="text-danger asterik">*</i><?php echo isset($error['plan_details']) ? $error['plan_details'] : ''; ?>
                                    <textarea name="plan_details" id="plan_details" class="form-control" rows="8"><?php echo $res[0]['plan_details']; ?></textarea>
                                    <script type="text/javascript" src="css/js/ckeditor/ckeditor.js"></script>
                                    <script type="text/javascript">
                                       CKEDITOR.replace('plan_details');
                                    </script>
                                </div>
                                </div>  
                            </div>
                    </div>

                    <div class="box-footer">
                        <input type="submit" class="btn-primary btn" value="Update" name="btnUpdate" />
                    </div>
                </form>
            </div>
            <!-- /.box -->
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
