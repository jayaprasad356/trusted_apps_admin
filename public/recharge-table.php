
<?php
if (isset($_POST['btnPaid'])  && isset($_POST['enable'])) {
    for ($i = 0; $i < count($_POST['enable']); $i++) {
        
        $enable = $db->escapeString($fn->xss_clean($_POST['enable'][$i]));
        $recharge = 299;
        $sql = "SELECT user_id FROM recharge WHERE id = $enable";
        $db->sql($sql);
        $res= $db->getResult();
        $user_id = $res[0]['user_id'];
        $sql = "SELECT id FROM users WHERE id = $user_id";
        $db->sql($sql);
        $res= $db->getResult();
        $num = $db->numRows($res);
        if ($num == 1) {
            $sql = "UPDATE recharge SET recharge_amount = $recharge,status=1 WHERE id = $enable";
            $db->sql($sql);
            $datetime = date('Y-m-d H:i:s');
            $type = 'recharge';
            $sql = "INSERT INTO transactions (`user_id`,`amount`,`datetime`,`type`)VALUES('$user_id','$recharge','$datetime','$type')";
            $db->sql($sql);
            $sql_query = "UPDATE users SET recharge = recharge + $recharge ,total_recharge = total_recharge + $recharge  WHERE id = $user_id";
            $db->sql($sql_query);

        }


    }
}
if (isset($_POST['btnCancel'])  && isset($_POST['enable'])) {
    for ($i = 0; $i < count($_POST['enable']); $i++) {
        
    
        $enable = $db->escapeString($fn->xss_clean($_POST['enable'][$i]));
        $sql = "UPDATE recharge SET status=2,recharge_amount = 0 WHERE id = $enable";
        $db->sql($sql);
        $result = $db->getResult();
    }
}

?>
<section class="content-header">
    <h1>Recharge /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
</section>

<section class="content">
<form name="recharge_form" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="col-md-3">
                        <h4 class="box-title">Filter by Status</h4>
                        <select id="status" name="status" class="form-control">
                            <option value="">All</option>
                            <option value="0">Pending</option>
                            <option value="1">Verified</option>
                            <option value="2">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <h4 class="box-title">Filter by Date </h4>
                        <input type="date" class="form-control" id="date" name="date" value="<?php echo (isset($_GET['date'])) ? $_GET['date'] : "" ?>">
                    </div>
                    <div class="col-md-2">
                        <input type="checkbox" onchange="checkAll(this)" name="chk[]" > Select All</input>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success" name="btnPaid">verifed</button>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-danger" name="btnCancel">Cancel</button>
                    </div>
                </div>
             
             
                <div class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=recharge" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{"fileName": "students-list-<?= date('d-m-Y') ?>","ignoreColumn": ["operate"] }'>
                        <thead>
                            <tr>
                            <th data-field="column"> All</th>
                                <th data-field="id" data-sortable="true">ID</th>
                                <th data-field="name" data-sortable="true">Name</th>
                                <th data-field="mobile" data-sortable="true">Mobile</th>
                                <th data-field="image">Image</th>
                                <th data-field="recharge_amount" data-sortable="true">Recharge Amount</th>
                                <th data-field="status" data-sortable="true">Status</th>
                                <th data-field="datetime" data-sortable="true">Date Time</th>
                                <th data-field="operate" data-events="actionEvents">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
              </div>
            <div class="separator"></div>
        </div>
    </div>
    </form>
</section>

<script>
    $('#status').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });

    $('#date').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });

    function queryParams(p) {
        return {
            "category_id": $('#category_id').val(),
            "seller_id": $('#seller_id').val(),
            "community": $('#community').val(),
            "status": $('#status').val(),
            "date": $('#date').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
    }

</script>
<script>
 function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
 }
    
</script>
