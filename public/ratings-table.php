
<section class="content-header">
    <h1>Ratings /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
</section>

    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <div class="col-xs-12">
                <div class="box">
                <div class="box-header">
                    <div class="row">
                    <div class="form-group col-md-3">
                    <h4 class="box-title">Filter By Apps</h4>
                            <select id='apps' name="apps" class='form-control'>
                            <option value=''>Select All</option>
                                <?php
                                $sql = "SELECT  name FROM `apps` ORDER BY name"; 
                                $db->sql($sql);
                                $result = $db->getResult();
                                foreach ($result as $value) {
                                    ?>
                                    <option value='<?= $value['name'] ?>'><?= $value['name'] ?></option>
                                <?php } ?>
                            </select>
                          </div>
                          </div>
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=ratings" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "students-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                    
                                    <th  data-field="id" data-sortable="true">ID</th>
                                    <th data-field="user_name" data-sortable="true">User Name</th>
                                    <th data-field="user_mobile" data-sortable="true">User Mobile</th>
                                    <th data-field="app_name" data-sortable="true">App Name</th>
                                    <th data-field="ratings" data-sortable="true">Ratings</th>
                                    <th data-field="comments" data-sortable="true">Comments</th>
                                    <th data-field="datetime" data-sortable="true">Datetime</th>
                                    <th  data-field="operate" data-events="actionEvents">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="separator"> </div>
        </div>
    </section>

<script>
    $('#seller_id').on('change', function() {
        $('#products_table').bootstrapTable('refresh');
    });
    $('#community').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#apps').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });

    function queryParams(p) {
        return {
            "category_id": $('#category_id').val(),
            "seller_id": $('#seller_id').val(),
            "community": $('#community').val(),
            "apps": $('#apps').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
    }
</script>