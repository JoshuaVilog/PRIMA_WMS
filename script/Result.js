class Result extends Main{

    constructor(){
        super()
    }

    PopulateStatus(selectElem){
        let list = this.GetStatusList()
        let options = '';

        for(let index = 0; index < list.length; index++){
            options += '<option value="'+list[index].a+'">'+list[index].b+'</option>';

        }

        selectElem.html(options);
    }

    InsertResultMasterlist(record){
        let self = this;
        let qty = record.qty;
        let status = record.status;
        let id = record.id;
        
        if(qty.val() == "" || status.val() == "" || id.val() == ""){
            Swal.fire({
                title: 'Incomplete Form.',
                text: 'Please complete the form.',
                icon: 'warning'
            })
        } else {
            $.ajax({
                url: "php/controllers/Result/InsertResultMasterlist.php",
                method: "POST",
                data: {
                    qty: qty.val(),
                    status: status.val(),
                    id: id.val(),
                },
                success: function(response){
                    response = JSON.parse(response);

                    if(response.status == "duplicate"){

                        Swal.fire({
                            title: 'Duplicate.',
                            text: 'Please input an unique description.',
                            icon: 'warning'
                        })
                    } else if(response.status == "success"){
                        qty.val(""),
                        self.PopulateStatus(status),
                        id.val(""),
    
                        Swal.fire({
                            title: 'Record added successfully!',
                            text: '',
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Proceed!',
                            timer: 2000,
                            willClose: () => {
                                
                            },
                        })
                    }
                    
                },
                error: function(err){
                    console.log("Error:"+JSON.stringify(err));
                },
            });
        }
    }

    DisplayResultListByID(id, callback){
        $.ajax({
            url: "php/controllers/Result/DisplayResultListRecordsByID.php",
            method: "POST",
            data: {
                id: id,
            },
            datatype: "json",
            success: function(response){
                console.log(response);
                let list = response.data;

                callback(list);
                
            },
            error: function(err){
                console.log("Error:"+JSON.stringify(err));
            },
        });

    }

}