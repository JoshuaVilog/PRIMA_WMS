
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
                        <h1>PLAN</h1>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 pricing-box">
                            <div class="widget-box widget-color-orange">
                                <div class="widget-header">
                                    <h5 class="widget-title bigger lighter">List of Plan</h5>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <button class="btn btn-primary btn-block" id="btnOpenAddModal">NEW +</button>
                                                <hr>
                                                <div id="table-records"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-8 pricing-box">
                            <div class="widget-box widget-color-orange">
                                <div class="widget-header">
                                    <h5 class="widget-title bigger lighter">List of Process</h5>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group has-info">
                                                    <label for="">
                                                        <strong>DATE:</strong>
                                                    </label>
                                                    <input type="text" id="txtDisplayDate" class="form-control disabled">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div id="table-process"></div>
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
    <!-- MODAL -->
    <?php 
    include "partials/modals/modalAddPlanProcess.php";
    ?>
    <!-- JavaScript -->
    <script src="/<?php echo $rootFolder; ?>/script/Plan.js?v=<?php echo $generateRandomNumber; ?>"></script>
    <script>
        let plan = new Plan();

        plan.DisplayTablePlanMasterlist("#table-records");

        $("#btnOpenAddModal").click(function(){

            $("#modalAddPlanProcess").modal("show");
            plan.DisplayTableAddProcess("#table-add-process");
            $("#txtDate").val(main.GetCurrentDate());
        });
        $("#btnAddRowAddProcess").click(function(){

            plan.AddRowTableAddProcess()
        })
        $("#table-add-process").on("click", ".btnRemove", function(){
            let value = $(this).val();

            plan.RemoveRowTableAddProcess(value);
        })
        $("#btnSubmit").click(function(){
            let date = $("#txtDate").val();
            let dataArray = [];
            let container = document.getElementById("table-add-process");
            let rows = container.getElementsByClassName("tabulator-row");
            let isNull = false;

            for(let i = 0; i < rows.length; i++){
                let item = rows[i].querySelector(".selectItem").value;
                let process = rows[i].querySelector(".selectProcess").value;
                let qty = rows[i].querySelector(".txtQty").value;

                dataArray.push({
                    item: item,
                    process: process,
                    qty: qty,
                })

                if(item == "" || process == "" || qty == ""){
                    isNull = true;
                }
            }

            if(isNull == true || date == ""){
                Swal.fire({
                    title: 'Incomplete Form',
                    text: 'Please fill up all the information',
                    icon: 'warning'
                })
            } else {
                let sendData = {
                    date: date,
                    processList: dataArray,
                }

                plan.sendData = sendData;
                plan.modal = $("#modalAddPlanProcess");
                plan.table = "#table-records"
                plan.InsertPlanMasterlist(plan);

            }
            

        })
    </script>

