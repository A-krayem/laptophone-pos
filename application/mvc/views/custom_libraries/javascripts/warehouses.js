function addWarehouse(action) {
    var content =
        '<div class="modal fade" id="add_new_warehouse" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="add_new_warehouse_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                    <div class="modal-header">\n\
                        <h3 class="modal-title" id="exampleModalLongTitle"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add new warehouse</h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="form-group">\n\
                            <div class="inner-addon left-addon addon_item_icon"><i class="glyphicon glyphicon-home item_icon"></i><input id="warehouse_desc" name="warehouse_desc" type="text" class="form-control" placeholder="Warehouse location" aria-describedby="basic-addon1"></div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <button id="action_btn" type="submit" class="btn btn-primary">Add</button>\n\
                    </div>\n\
                <form/>\n\
            </div>\n\
        </div>\n\
    </div>';

    $('#add_new_warehouse').remove();
    $('body').append(content);
    submitWarehouse(action);
    $('#add_new_warehouse').modal('toggle');
}

function editWarehouse(id){
    var id_int = parseInt(id.split('-')[1]);
    $(".sk-circle-layer").show();
    var location = null;
    $.getJSON("?r=warehouses&f=get_warehouse&p0=" + id_int, function (data) {
        location=data[0].location;
    }).done(function () {
        addWarehouse('up');
         $("#action_btn").text('Update');
        $("#id_to_edit").val(id_int);
        $("#warehouse_desc").val(location);
        $(".sk-circle-layer").hide();
    });
}

function submitWarehouse(action) {
    $("#add_new_warehouse_form").on('submit', (function (e) {
        e.preventDefault();
        if (!emptyInput("warehouse_desc")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=warehouses&f=add_new_warehouse",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    if(action == "otherPage"){
                        //$("#item_cat").append("<option value='"+data.id+"'>"+data.cat_desc+"</option>");
                        //$("#item_cat").selectpicker('refresh');
                        //$("#item_cat").selectpicker('val', data.id);
                        //$('#add_new_cat').modal('hide');
                        //$(".sk-circle-layer").hide();
                    }else{
                        $('#add_new_warehouse').modal('hide');
                        var table = $('#warehouses_table').DataTable();
                        table.ajax.url('?r=warehouses&f=getAllWarehouses').load(function () {
                            if(action == "up"){
                                table.row('.' + pad_wh(data.id), {page: 'current'}).select();
                            }else{
                                table.page('last').draw(false);
                                table.row(':last', {page: 'current'}).select();
                            } 
                            $(".sk-circle-layer").hide();
                        });
                    } 
                }
            });
        }
    }));
}