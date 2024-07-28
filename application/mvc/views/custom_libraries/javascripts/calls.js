function showCalls(type){
    var title = "";
    var icon = "";
    var custom = 0;
    if(type=="int"){ /* Internationnal calls */
        title = "Internationnal calls";
        icon = "international-call";
        custom = 2;
    }else if(type=="loc"){/* Local calls */
        title = "Local calls";
        icon = "local-call";
        custom = 3;
    }
    
    var content =
        '<div class="modal fade" data-keyboard="false" id="callsModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title"><i class="icon-'+icon+'"></i>&nbsp;'+title+'<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'callsModal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="form-group">\n\
                        <div class="inner-addon"><input autocomplete="off" disabled id="custom_item_name" name="custom_item_name" value="'+title+'" data-provide="typeahead" type="hidden" class="form-control" placeholder="Item description" aria-describedby="basic-addon1"></div>\n\
                    </div>\n\
                    <div class="form-group" style="width:100px">\n\
                        <div class="inner-addon"><input autocomplete="off" id="custom_item_cost" name="custom_item_cost" data-provide="typeahead" type="text" value="0" class="form-control only_numeric" placeholder="" aria-describedby="basic-addon1"></div>\n\
                    </div>\n\
                    <div class="form-group" style="width:150px">\n\
                        <div class="inner-addon"><button id="addCustomItemBtn" onclick="addCustomItemCalls('+custom+')" type="button" class="btn btn-default" style="width: 100%; color: #000; font-size: 20px; font-weight: bold;">Add to invoice</button></div>\n\
                    </div>\n\
                </div>\n\
                <div class="modal-footer">\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    
    
    $("#callsModal").remove();
    $("body").append(content);
    $("#callsModal").centerWH();
    $(".only_numeric").numeric();
    
    $('#callsModal').modal('toggle');

    $('#callsModal').on('shown.bs.modal', function (e) {
    });

    $('#callsModal').on('hidden.bs.modal', function (e) {
        $("#callsModal").remove();
    }); 
}

function addCustomItemCalls(custom){
    var infCust = [];
    infCust["description"] = $("#custom_item_name").val();
    infCust["price"] = $("#custom_item_cost").val();
    infCust["custom_item"] = custom;
    inv.addCallsItem(infCust);
    $('#callsModal').modal('toggle');
}

/*
function submitCalls(){
    $("#add_calls").on('submit', (function (e) {
        e.preventDefault();
        $.ajax({
            url: "?r=pos&f=add_calls",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data)
            {
            }
        });
    }));
}
*/
