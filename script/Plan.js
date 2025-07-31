class Plan extends Main{

    constructor(){
        super()
        this.tableDisplayPlan = null;
        this.tableDisplayProcess = null;
        this.tableAddProcess = null;
        this.tableEditProcess = null;
        this.numAddProcess = 0;
    }

    DisplayTablePlanMasterlist(tableElem){
        $.ajax({
            url: "php/controllers/Plan/DisplayPlanRecords.php",
            method: "POST",
            data: {},
            datatype: "json",
            success: function(response){

                // console.log(response);

                this.tableDisplayPlan = new Tabulator(tableElem, {
                    data: response.data,
                    pagination: "local",
                    paginationSize: 10,
                    paginationSizeSelector: [10, 25, 50],
                    page: 1,
                    layout: "fitDataFill",
                    columns: [
                        {title: "ID", field: "RID", headerFilter: "input", visible: false,},
                        {title: "#", formatter: "rownum" },
                        {title: "DATE", field: "DATE", headerFilter: "input"},
                        {title: "CREATED AT", field: "CREATED_AT",},
                        {title: "ACTION", field:"RID", hozAlign: "left", headerSort: false, frozen:true, formatter:function(cell){
                            let id = cell.getValue();
                            let edit = '<button class="btn btn-primary btn-minier btnEditRecord" value="'+id+'">Edit</button>';
                            let remove = '<button class="btn btn-danger btn-minier btnRemoveRecord" value="'+id+'">Remove</button>';
        
                            return edit + " " + remove;
                        }},
                    ],
                });
            },
            error: function(err){
                console.log("Error:"+JSON.stringify(err));
            },
        });
    }

    DisplayPlanListByDate(date, callback){
        $.ajax({
            url: "php/controllers/Plan/DisplayPlanListRecordsByDate.php",
            method: "POST",
            data: {
                date: date,
            },
            datatype: "json",
            success: function(response){
                // console.log(response);
                let list = response.data;

                callback(list);
                
            },
            error: function(err){
                console.log("Error:"+JSON.stringify(err));
            },
        });

    }

    DisplayTableAddProcess(tableElem){
        this.tableAddProcess = new Tabulator(tableElem, {
            data:[],
            layout: "fitDataFill",
            columns: [
                {title: "#", formatter: "rownum" },
                {title: "id", field: "id", formatter: "rownum", visible: false},
                {title: "ITEM", field: "item", headerSort: false, formatter:function(cell){
                    let value = cell.getValue();
                    let element = '<input type="text" class="form-control selectItem">';

                    return element;
                },},
                {title: "PROCESS", field: "process", headerSort: false, formatter:function(cell){
                    let value = cell.getValue();
                    var list = JSON.parse(localStorage.getItem(main.lsProcessList));

                    var select = document.createElement("select");
                    select.classList.add("form-control");
                    select.classList.add("selectProcess");

                    var option = document.createElement("option");
                    option.text = "-Select-";
                    option.value = "";
                    select.appendChild(option);

                    for (var i = 0; i < list.length; i++) {
                        var option = document.createElement("option");
                        option.text = list[i].PROCESS_DESC;
                        option.value = list[i].RID;
                
                        if(list[i].a == cell.getValue()){
                            option.selected = true;
                        }
                
                        select.appendChild(option);
                    }
                
                    // Attach change event listener to the select element
                    select.addEventListener("change", function (event) {
                        // Access the selected value
                        var selectedValue = event.target.value;
                        cell.getRow().update({ process: value });
                    });

                    return select;
                },},
                {title: "QUANTITY", field: "qty", headerSort: false, formatter:function(cell){
                    let value = cell.getValue();
                    var input = document.createElement("input");

                    input.type = "number";
                    input.value = value;
                    input.classList.add("form-control");
                    input.classList.add("txtQty");

                    // Set up an event listener to capture the input value when changed
                    input.addEventListener("input", function (event) {
                        
                        cell.getRow().update({ qty: value });
                    });

                    return input;
                },},
                {title: "ACTION", field:"id", hozAlign: "left", headerSort: false, frozen:true, formatter:function(cell){
                    let id = cell.getValue();
                    let remove = '<button class="btn btn-danger btn-minier btnRemove" value="'+id+'">Remove</button>';

                    return remove;
                }},
            ],
        });
    }
    AddRowTableAddProcess(){
        let newRowData = {
            id: this.numAddProcess,
            item: "",
            process: "",
            qty: 1000,

        }
        this.tableAddProcess.addData(newRowData);
        this.numAddProcess++;

    }
    RemoveRowTableAddProcess(id){

        this.tableAddProcess.deleteRow(id);
    }

    InsertPlanMasterlist(record){
        let self = this;
        $.ajax({
            url: "php/controllers/Plan/InsertPlanMasterlist.php",
            method: "POST",
            data: JSON.stringify(record.sendData),
            success: function(response) {
                console.log(response);
                response = JSON.parse(response);

                if(response.status == "duplicate"){

                    Swal.fire({
                        title: 'Duplicate.',
                        text: 'Please input an unique description.',
                        icon: 'warning'
                    })
                } else if(response.status == "success"){
                    record.modal.modal("hide");

                    Swal.fire({
                        title: 'Record added successfully!',
                        text: '',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Proceed!',
                        timer: 2000,
                        willClose: () => {
                            self.DisplayTablePlanMasterlist(record.table);
                        },
                    })
                }
            }
        });

    }







    DisplayTableEditProcess(tableElem){

    }
    AddRowTableEditProcess(){


    }
    RemoveRowTableEditProcess(){


    }
    
}