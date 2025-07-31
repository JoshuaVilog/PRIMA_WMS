
<body class="no-skin">
    <?php include "partials/navbar.php";?>

    <div class="main-container ace-save-state" id="main-container">
        <script type="text/javascript">
            try{ace.settings.loadState('main-container')}catch(e){}
        </script>

        <?php include "partials/sidebar.php";?>
        <div class="main-content">
            <div class="main-content-inner">
                <div class="page-content">
                    <div class="page-header">
                        <h1>ACTUAL</h1>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-5 pricing-box">
                            <div class="widget-box widget-color-orange">
                                <div class="widget-header">
                                    <h5 class="widget-title bigger lighter">LIST</h5>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="">
                                                        <strong>DATE:</strong>
                                                    </label>
                                                    <input type="date" id="txtDate" class="form-control input-lg">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div id="table-records"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-7 pricing-box">
                            <div class="widget-box widget-color-orange">
                                <div class="widget-header">
                                    <h5 class="widget-title bigger lighter">FORM</h5>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="form-group has-info">
                                            <label for="">
                                                <strong>ITEM:</strong>
                                            </label>
                                            <input type="text" name="" id="txtDisplayItem" class="form-control input-lg disabled">
                                        </div>
                                        <div class="form-group has-info">
                                            <label for="">
                                                <strong>PROCESS:</strong>
                                            </label>
                                            <input type="text" name="" id="txtDisplayProcess" class="form-control input-lg disabled">
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="">
                                                        <strong>QUANTITY:</strong>
                                                    </label>
                                                    <input type="number" name="" id="txtQty" class="form-control input-lg">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="">
                                                        <strong>STATUS:</strong>
                                                    </label>
                                                    <select class="form-control input-lg" id="selectStatus"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="" id="hiddenPlanListID">
                                        <button class="btn btn-primary btn-block" id="btnSubmit">SUBMIT</button>
                                        <hr>
                                        <div id="table-result-records"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include "partials/footer.php";?>
        <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
            <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
        </a>
    </div>
    <!-- JavaScript -->
    <script src="/<?php echo $rootFolder; ?>/script/Plan.js?v=<?php echo $generateRandomNumber; ?>"></script>
    <script src="/<?php echo $rootFolder; ?>/script/Result.js?v=<?php echo $generateRandomNumber; ?>"></script>
    <script>
        $("#menuResult").addClass("active");

        let plan = new Plan();
        let result = new Result();
        let table1;

        $("#txtDate").val(main.GetCurrentDate());
        result.PopulateStatus($("#selectStatus"));

        setTimeout(() => {
            let date = $("#txtDate").val();

            displayPlanList(date);
        }, 1000);

        $("#txtDate").change(function(){
            let value = $(this).val();

            displayPlanList(value);
        });
        $("#table-records").on("click", ".btnSelectRecord", function(){
            let value = $(this).val();

            $("#hiddenPlanListID").val(value);
            displayResultListByID(value);
        })
        $("#btnSubmit").click(function(){
            let qty = $("#txtQty");
            let status = $("#selectStatus");
            let id = $("#hiddenPlanListID");

            result.qty = qty;
            result.status = status;
            result.id = id;

            result.InsertResultMasterlist(result);
        });

        function displayPlanList(date){
            plan.DisplayPlanListByDate(date, function(list){

                table1 = new Tabulator("#table-records", {
                    data: list,
                    pagination: "local",
                    paginationSize: 10,
                    paginationSizeSelector: [10, 25, 50],
                    page: 1,
                    layout: "fitDataFill",
                    columns: [
                        {title: "ID", field: "RID", headerFilter: "input", visible: false,},
                        {title: "#", formatter: "rownum" },
                        {title: "ITEM", field: "ITEM", headerFilter: "input"},
                        {title: "PROCESS", field: "PROCESS", formatter:function(cell){
                            return main.SetProcess(cell.getValue());
                        }},
                        {title: "PLAN QUANTITY", field: "QTY", headerFilter: "input"},
                        {title: "CREATED AT", field: "CREATED_AT",},
                        {title: "ACTION", field:"RID", hozAlign: "left", headerSort: false, frozen:true, formatter:function(cell){
                            let id = cell.getValue();
                            let select = '<button class="btn btn-success btn-minier btnSelectRecord" value="'+id+'">Select</button>';

                            return select;
                        }},
                    ],
                });
            })
        }
        function displayResultListByID(id){
            result.DisplayResultListByID(id, function(list){

                table1 = new Tabulator("#table-result-records", {
                    data: list,
                    pagination: "local",
                    paginationSize: 10,
                    paginationSizeSelector: [10, 25, 50],
                    page: 1,
                    layout: "fitDataFill",
                    columns: [
                        {title: "ID", field: "RID", headerFilter: "input", visible: false,},
                        {title: "#", formatter: "rownum" },
                        {title: "ACTUAL QUANTITY", field: "QTY", },
                        {title: "STATUS", field: "STATUS", formatter: function(cell){
                            return main.SetStatus(cell.getValue());
                        }},
                        {title: "CREATED AT", field: "CREATED_AT",},
                        /* {title: "ACTION", field:"RID", hozAlign: "left", headerSort: false, frozen:true, formatter:function(cell){
                            let id = cell.getValue();
                            let select = '<button class="btn btn-success btn-minier btnSelectRecord" value="'+id+'">Select</button>';

                            return select;
                        }}, */
                    ],
                });
            })
        }


    </script>

