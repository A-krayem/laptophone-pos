function addUser() {
    $(".sk-circle").center();
    $(".sk-circle-layer").show();

    var employees_options = "";
    var _data=[];
    $.getJSON("?r=employees&f=getAllEmployeesDetails", function (data) {
        _data=data;
        $.each(data.emp, function (key, val) {
            employees_options += "<option value=" + val.id + ">" + val.first_name+" "+ val.middle_name + " "+ val.last_name + "</option>";
        });
    }).done(function () {
        
        
        
        var omt_operator_is_admin="";
        if(omt_version==1){
            omt_operator_is_admin='\n\
            <div class="col-lg-4 col-md-4 padding-left-4 padding-right-4" >\n\
                <div class="form-group" style="text-align:center">\n\
                    <label for="operator_is_admin">Operator is admin</label><br/>\n\
                    <input id="operator_is_admin" name="operator_is_admin" type="checkbox" style="width:25px;height:25px;" />\n\
                </div>\n\
            </div>\n\
            ';
        
            
        }
        
        var boptions="";
        boptions+="<option value='0'>Main Branch</option>";
        for(var i=0;i<_data.branches.length;i++){
            boptions+="<option value='"+_data.branches[i].id+"'>"+_data.branches[i].branch_name+" - "+_data.branches[i].location_name+"</option>";
        }
        
        var mbranches="";
        if(new_multi_branches_enabled==1){
            mbranches='\n\
            <div class="col-lg-8 col-md-8" >\n\
                <div class="form-group">\n\
                    <label for="branches_ids">Branches</label><br/>\n\
                    <select multiple onchange="user_type_changed()" data-width="100%" id="branches_ids" name="branches_ids[]" class="selectpicker">'+boptions+'</select><br/>\n\
                </div>\n\
            </div>\n\
            ';
        }
        
        var content =
        '<div class="modal" data-backdrop="static" id="add_new_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="add_new_user_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title"><i class="glyphicon glyphicon-user"></i>&nbsp;Add User<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'add_new_user\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                <div class="form-group">\n\
                                    <label for="usr">Username</label>\n\
                                    <div class="inner-addon left-addon addon_item_icon"><input autocomplete="off" id="username" name="username" type="text" class="form-control" placeholder="Username"></div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                <div class="form-group">\n\
                                    <label for="usr">Password</label>\n\
                                    <div class="inner-addon left-addon addon_item_icon"><input autocomplete="off" id="password" name="password" type="password" class="form-control" placeholder="Password"></div>\n\
                                </div>\n\
                            </div>\n\
                            '+omt_operator_is_admin+'\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                <div class="form-group">\n\
                                    <label for="supplier_id">User Type</label>\n\
                                    <select onchange="user_type_changed()" data-width="100%" id="user_type" name="user_type" class="selectpicker"><option value="1">Super Admin</option><option value="2">Vendor</option></select><br/>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                <div class="form-group">\n\
                                    <label for="usr">Employee name</label>\n\
                                    <select data-width="100%" id="user_id" name="user_id" class="selectpicker">'+employees_options+'</select><br/>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" id="pper">\n\
                                <div class="form-group">\n\
                                    <label for="user_critical">Critical Data</label>\n\
                                    <select data-width="100%" id="user_critical" name="user_critical" class="selectpicker"><option value="1">Hide Critical Data</option><option value="0">Full access</option></select><br/>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                <div class="form-group">\n\
                                    <label for="usr">Commission</label>\n\
                                    <div class="inner-addon left-addon addon_item_icon"><input autocomplete="off" id="commission" name="commission" type="number" step="0.01" class="form-control" placeholder="Commission"></div>\n\
                                </div>\n\
                            </div>\n\
                            '+mbranches+'\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <button id="action_btn" type="submit" class="btn btn-primary">Add User</button>\n\
                    </div>\n\
                    <form/>\n\
                </div>\n\
            </div>\n\
        </div>';

        $('#add_new_user').modal('hide');
        $('body').append(content);
        $('.selectpicker').selectpicker();
        submitUser();

        $('#start_date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,

        });
        $('#start_date').datepicker("setDate" , new Date())

        

        $('#add_new_user').on('hidden.bs.modal', function (e) {
            $('#add_new_user').remove();
        });

        $('#add_new_user').on('shown.bs.modal', function (e) {
            $(".sk-circle-layer").hide();
        });
        
        $('#add_new_user').modal('show');
    });
}


function user_type_changed(){
    if($("#user_type").val()==2){
        $("#pper").hide();
    }else{
        $("#pper").show();
    }
}

function closeModal(id){
    $('#'+id).modal('toggle');
}

function editUser(id_int){
    $(".sk-circle-layer").show();
    
    var id = null;
    var username = null;
    var password = null;
    var role_id = null;
    var name = null;
    var hide_critical_data = null;
    var operator_is_admin = null;
    var commission=0;
    var branches=0;
    var new_branches_permission=[];
    
    $.getJSON("?r=employees&f=get_user&p0=" + id_int, function (data) {
        id = data[0].id;
        username = data[0].username;
        password = data[0].password;
        role_id = data[0].role_id;
        new_branches_permission = data[0].new_branches_permission;
        
        name = data[0].name;
        hide_critical_data = data[0].hide_critical_data;
        operator_is_admin = data[0].operator_is_admin;
        commission=parseFloat(data[0].commission).toFixed(2);
        
        branches = data[0].branches;
        
    }).done(function () {
        
        var omt_operator_is_admin="";
        if(omt_version==1){
            omt_operator_is_admin='\n\
            <div class="col-lg-4 col-md-4 padding-left-4 padding-right-4" >\n\
                <div class="form-group" style="text-align:center">\n\
                    <label for="operator_is_admin">Operator is admin</label><br/>\n\
                    <input id="operator_is_admin" name="operator_is_admin" type="checkbox" style="width:25px;height:25px;" />\n\
                </div>\n\
            </div>\n\
            ';
        }
        
        
        var boptions="";
        boptions+="<option value='0'>Main Branch</option>";
        for(var i=0;i<branches.length;i++){
            boptions+="<option value='"+branches[i].id+"'>"+branches[i].branch_name+" - "+branches[i].location_name+"</option>";
        }
        
        var mbranches="";
        if(new_multi_branches_enabled==1){
            mbranches='\n\
            <div class="col-lg-8 col-md-8" >\n\
                <div class="form-group">\n\
                    <label for="branches_ids">Branches</label><br/>\n\
                    <select multiple onchange="user_type_changed()" data-width="100%" id="branches_ids" name="branches_ids[]" class="selectpicker">'+boptions+'</select><br/>\n\
                </div>\n\
            </div>\n\
            ';
        }
        
        var content =
            '<div class="modal" data-backdrop="static" id="edit_user_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="add_new_user_form" action="" method="post" enctype="multipart/form-data" >\n\
                        <input id="id_to_edit" name="id_to_edit" type="hidden" value="'+id+'" />\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title" id="exampleModalLongTitle"><i class="glyphicon glyphicon-user"></i>&nbsp;Edit User<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'edit_user_modal\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                <div class="form-group">\n\
                                    <label for="usr">Username</label>\n\
                                    <input readonly id="username" name="username" type="text" class="form-control" placeholder="" value="'+username+'">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                <div class="form-group">\n\
                                    <label for="usr">Password</label>\n\
                                    <input id="password" name="password" type="password" class="form-control" placeholder="Password" value="'+password+'">\n\
                                </div>\n\
                            </div>\n\
                            '+omt_operator_is_admin+'\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                <div class="form-group">\n\
                                    <label for="supplier_id">User Type</label>\n\
                                    <select onchange="user_type_changed()" data-width="100%" id="user_type" name="user_type" class="selectpicker" style="width:100% !important"><option value="1">Super Admin</option><option value="2">Vendor</option></select><br/>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="pper">\n\
                                <div class="form-group">\n\
                                    <label for="user_critical">Critical Data</label>\n\
                                    <select data-width="100%" id="user_critical" name="user_critical" class="selectpicker"><option value="1">Hide Critical Data</option><option value="0">Full access</option></select><br/>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                <div class="form-group">\n\
                                    <label for="usr">Commission</label>\n\
                                    <div class="inner-addon left-addon addon_item_icon"><input autocomplete="off" id="commission" name="commission" type="number" class="form-control" placeholder="Commission"  step="0.01" value="'+commission+'"></div>\n\
                                </div>\n\
                            </div>\n\
                            '+mbranches+'\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <button id="action_btn" type="submit" class="btn btn-primary">Update User</button>\n\
                    </div>\n\
                    <form/>\n\
                </div>\n\
            </div>\n\
        </div>';
    
        $('#edit_user_modal').modal('hide');
        $('body').append(content);

        $('.selectpicker').selectpicker();
        $('#user_type').selectpicker('val', role_id);
        
        $('#user_critical').selectpicker('val', hide_critical_data);
        
        if(operator_is_admin==1){
            $('#operator_is_admin').prop('checked', true);
        }else{
            $('#operator_is_admin').prop('checked', false);
        }
        
        
        if(new_branches_permission!=null){
            var btmp = new_branches_permission.split(',');
            $('#branches_ids').val(btmp);
            $('#branches_ids').selectpicker('refresh');
        }
        
        submitUser();
        

        $('#edit_user_modal').on('hidden.bs.modal', function (e) {
            $('#edit_user_modal').remove();
        });

        $('#edit_user_modal').on('shown.bs.modal', function (e) {

        });
        
        $('#edit_user_modal').modal('show');
    
        $(".sk-circle-layer").hide();
    }).fail(function() {
        
    }).always(function() {
        
    });
}

function submitUser() {
    $("#add_new_user_form").on('submit', (function (e) {
        e.preventDefault();
        if (!emptyInput("first_name")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=employees&f=add_new_user",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    location.reload();

                    /*$('#add_new_user').modal('toggle');
                    $('#edit_user_modal').modal('toggle');
                    
                    
                    
                    var table = $('#employee_table').DataTable();
                    table.ajax.url("?r=employees&f=getAllUsers").load(function () {
                        table.row('.' + pad_sysuser(data.id), {page: 'current'}).select();
                        $(".sk-circle-layer").hide();
                    }, false);*/
                    
                }
            });
        }
    }));
}