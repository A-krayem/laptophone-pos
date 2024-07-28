function gaz_station(is_admin){
    
    var admin_display = "";
    if(is_admin==0){
        admin_display = "display:none;";
        
        $("#mobile_section_modal").modal("hide");
    }
    
    var modal_name = "modal_gaz_station_modal__";
    var modal_title = "Oil Dispensing Guns";
    var content =
    '<div class="modal medium-plus" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <input id="id_to_edit" name="id_to_edit" value="0" type="hidden" />\n\
                <input id="counter_type" name="counter_type" value="2" type="hidden" />\n\
                <div class="modal-header">\n\
                    <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row" style="'+admin_display+'">\n\
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                            <button onclick="add_dispensing_gun_station()" style="width:100%" type="button" class="btn btn-primary">New Dispensing Gun</button>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table style="width:100%" id="dispensing_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width:60px;">ID</th>\n\
                                        <th>Dispensing Gun</th>\n\
                                        <th style="width:100px;">Starting Date</th>\n\
                                        <th style="width:120px;">Starting Counter</th>\n\
                                        <th>Connected Item</th>\n\
                                        <th>Stock (L)</th>\n\
                                        <th>Capacity (L)</th>\n\
                                        <th></th>\n\
                                        <th></th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tbody></tbody>\n\
                            </table>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row" id="oil_charts">\n\
                        \n\
                    </div>\n\
                </div>\n\
                <div class="modal-footer" style=" text-align: left;">\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#"+modal_name).remove();
    $("body").append(content);
    //submitGenerateInvoice(modal_name,2);
            
            
    $('#'+modal_name).on('show.bs.modal', function (e) {

    });
    
    $('#'+modal_name).on('shown.bs.modal', function (e) {
        
        
        
        
        var table_name = "dispensing_table";
        var _cards_table__var =null;

     

        _cards_table__var = $('#'+table_name).DataTable({
            ajax: {
                url: "?r=gaz_station&f=get_all_dispensing_guns",
                type: 'POST',
                error:function(xhr,status,error) {
                },
                dataSrc: function (json) {
                    //$("#w_total").html("Total Wasting: "+json.total);
                    return json.data;
                }
            },
            //order: [[1, 'asc']],
            responsive: true,
            orderCellsTop: true,
            scrollX: true,
            scrollY: "55vh",
            iDisplayLength: 10,
            aoColumnDefs: [
                { "targets": [0], "searchable": false, "orderable": true,"visible": false },
                { "targets": [1], "searchable": false, "orderable": true,"visible": true },
                { "targets": [2], "searchable": false, "orderable": true,"visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                { "targets": [5], "searchable": true, "orderable": true, "visible": true },
                { "targets": [6], "searchable": true, "orderable": false, "visible": true },
                { "targets": [7], "searchable": true, "orderable": false, "visible": true },
                { "targets": [8], "searchable": true, "orderable": false, "visible": true },
            ],
            scrollCollapse: true,
            paging: true,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bAutoWidth: true,
            dom: '<"toolbardisgun">frtip',
            initComplete: function(settings, json) {  

                $(".sk-circle-layer").hide();
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
            },
             fnDrawCallback: function(){

                var table = $('#'+table_name).DataTable();
                var p = table.rows({ page: 'current' }).nodes();
                for (var k = 0; k < p.length; k++){
                    var index = table.row(p[k]).index();
                    //if(table.cell(index,10).data()==1)
                        //table.cell(index,9).data('<i class="glyphicon glyphicon-trash" onclick="delete_waste(\''+parseInt(table.cell(index, 0).data())+'\')" style="font-size:18px;cursor:pointer" ></i>');
                }
             },
        });

        $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
        });


        $('#'+table_name).on('click', 'td', function () {
            if ($(this).index() == 4 || $(this).index() == 5) {
                //return false;
            }
        });
        
        prepare_to_render();
        
 
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}


function prepare_to_render(){
    var result = [];
    $.getJSON("?r=gaz_station&f=prepare_to_render", function (data) {
        result = data;
    }).done(function () {
        $("#oil_charts").empty();
        for(var i=0;i<result.length;i++){
            $("#oil_charts").append('\
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" >\n\
                    <div id="oil_level_'+result[i].id+'" style="width: 100%;height:400px;">\n\
                    </div>\n\
                </div>');
            render_graph(result[i].min,result[i].max,result[i].stock,result[i].name,result[i].id);
        }
        
    });
    //render_graph(0,100000,65000,"Station Name");
}

function render_graph(min,max,current_stock,station_name,id){
    var myChart = echarts.init(document.getElementById('oil_level_'+id));
    var app = {};
    /*
    option = {
        title: {
            text: station_name,  // The text of the title
            subtext: '',  // Optional subtitle
            left: 'center',  // Position of the title (left, right, center)
            textStyle: {
                fontSize: 16,  // Font size of the title
                fontWeight: 'bold',  // Font weight
                color: '#333'  // Color of the title text
            },
            subtextStyle: {
                fontSize: 12,  // Font size of the subtitle
                color: '#999'  // Color of the subtitle text
            }
        },
    series: [
      {
      type: 'gauge',
      "min": min,
    "max": max, 
    "splitNumber": 8,
      axisLine: {
        lineStyle: {
          width: 30,
          color: [
            [0.3, '#fd666d'],
            [0.7, '#67e0e3'],
            [1, '#37a2da']
          ]
        }
      },
      pointer: {
        itemStyle: {
          color: 'auto'
        }
      },
      axisTick: {
        distance: -30,
        length: 8,
        lineStyle: {
          color: '#fff',
          width: 2
        }
      },
      splitLine: {
        distance: -30,
        length: 30,
        lineStyle: {
          color: '#fff',
          width: 4
        }
      },
      axisLabel: {
        color: 'inherit',
        distance: 40,
        fontSize: 14
      },
      detail: {
        valueAnimation: true,
        formatter: '{value} L',
        color: 'inherit'
      },
      data: [
        {
          value: current_stock
        }
      ]
    }
  ]
};*/

    var option = {
        title: {
            text: station_name,  // The text of the title
            subtext: '',  // Optional subtitle
            left: 'center',  // Position of the title (left, right, center)
            textStyle: {
                fontSize: 16,  // Font size of the title
                fontWeight: 'bold',  // Font weight
                color: '#333'  // Color of the title text
            },
            subtextStyle: {
                fontSize: 12,  // Font size of the subtitle
                color: '#999'  // Color of the subtitle text
            }
        },
        series: [
          {
            type: 'gauge',
            "min": min,
            "max": max, 
            "splitNumber": 8,
            progress: {
              show: false,
              width: 18
            },
            axisLine: {
              lineStyle: {
                width: 10,
                color: [
                    [0.2, '#d9534f'],
                    [0.7, 'rgb(252 223 83)'],
                    [1, '#5cb85c ']
                  ]
              },
              
            },
            axisTick: {
              show: false
            },
            splitLine: {
              length: 7,
              lineStyle: {
                width: 2,
                color: '#999'
              }
            },
            axisLabel: {
              distance: 20,
              color: '#999',
              fontSize: 10,
              formatter: function(value) {
                    const formatter = new Intl.NumberFormat('en-US');
                    return formatter.format(value);  // Formatting the numbers as integers
              }
            },
            anchor: {
              show: true,
              showAbove: true,
              size: 25,
              itemStyle: {
                borderWidth: 10
              }
            },
            title: {
              show: true
            },
            detail: {
              valueAnimation: true,
              fontSize: 15,
              offsetCenter: [0, '70%'],
              formatter: function(value) {
                    const formatter = new Intl.NumberFormat('en-US');
                    return formatter.format(value)+" Litre ";  // Formatting the numbers as integers
              }
              //formatter: '{value} Litre',
              
            },
            data: [
              {
                value: current_stock
              }
            ]
          }
        ]
      };

    
    myChart.setOption(option);
}

function add_dispensing_gun_station(id){
    var modal_name = "modal_new_disp_gun_modal__";
    var modal_title = "New Oil Dispensing Gun";
    var content =
    '<div class="modal small" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <input id="id_to_edit" name="id_to_edit" value="'+id+'" type="hidden" />\n\
                <div class="modal-header">\n\
                    <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                            <div class="form-group">\n\
                                <label for="gun_name">Name</label>\n\
                                <input  autocomplete="off" id="gun_name" name="gun_name" value="" type="text" class="form-control med_input" placeholder="">\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                            <div class="form-group">\n\
                                <label for="gun_st_counter">Starting Counter</label>\n\
                                <input autocomplete="off" id="gun_st_counter" name="gun_st_counter" value="" type="number" class="form-control med_input" placeholder="">\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                            <div class="form-group">\n\
                                <label for="gun_st_counter">Maximum Capacity</label>\n\
                                <input autocomplete="off" id="max_stock" name="max_stock" value="" type="number" class="form-control med_input" placeholder="">\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <div class="form-group">\n\
                                <label for="search_item">Connected Item</label>\n\
                                <input autocomplete="off" id="search_item" name="search_item" value="" type="text" class="form-control med_input" placeholder="">\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                <div class="modal-footer" style=" text-align: left;">\n\
                    <button id="gaz_station_btn" onclick="submit_dispensing_gun_station()" style="width:100%" type="button" class="btn btn-primary">Submit</button>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#"+modal_name).remove();
    $("body").append(content);
    //submitGenerateInvoice(modal_name,2);
            
            
    $('#'+modal_name).on('show.bs.modal', function (e) {

    });
    
    $('#'+modal_name).on('shown.bs.modal', function (e) {
        $("#gun_name").focus();
        
        $("#search_item").select2({
            ajax: {
                url: '?r=items&f=search', // Change this to your AJAX endpoint
                dataType: "json",
                delay: 250, // Delay in milliseconds to prevent sending too many requests
                data: function(params) {
                    var query = {
                        p0: params.term || "",
                        p1: params.page || 1
                    }

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                processResults: function(data, params) {
                    // Process the data returned from the server
                    params.page = params.page || 1;

                    return {
                        results: data.results, // The items to display in the dropdown
                    };
                },
                cache: true // Cache the results to improve performance
            },
            dropdownParent: $(`#${modal_name}`),
            minimumInputLength: 0, // Minimum characters before AJAX request is triggered
            placeholder: "Search by Id, barcode, SKU and description",
            allowClear: true // Allow clearing the selection
        }).on('select2:select', function (e) {
            var data = e.params.data;
            
            var newOption = new Option(data.text, data.id, true, true);
            $('#search_item').append(newOption).trigger('change');
            
            
    
        });;
    

    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}


function submit_dispensing_gun_station(){
    var formData = new FormData();
    
    if($("#gun_name").val()==""){
        $("#gun_name").addClass("error");
        return;
    }else{
        $("#gun_name").removeClass("error");
    }
    
    if($("#gun_st_counter").val()==""){
        $("#gun_st_counter").addClass("error");
        return;
    }else{
        $("#gun_st_counter").removeClass("error");
    }
    
    if($("#max_stock").val()==""){
        $("#max_stock").addClass("error");
        return;
    }else{
        $("#max_stock").removeClass("error");
    }
    
    

    if(!$("#search_item").val()){
        $(".select2-container--default .select2-selection--single").addClass("error_select2");
        return;
    }else{
        $(".select2-container--default .select2-selection--single").removeClass("error_select2");
    }
    
    formData.append("gun_name", $("#gun_name").val());
    formData.append("gun_st_counter", $("#gun_st_counter").val());
    formData.append("search_item", $("#search_item").val());
    formData.append("max_stock", $("#max_stock").val());
    
    
    $("#gaz_station_btn").prop("disabled", true);
    
    $.ajax({
        url: "?r=gaz_station&f=submit_dispensing_gun_station",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        success: function (data)
        {
            var table = $('#dispensing_table').DataTable();
            table.ajax.url("?r=gaz_station&f=get_all_dispensing_guns").load(function () {
                prepare_to_render();
            }, false);
                        
            $('#modal_new_disp_gun_modal__').modal('hide');
        }
    });
}

function delete_counter(id,gaz_station_id){
    $.confirm({
        title: 'Delete Counter!',
        content: 'Are you sure?',
        buttons: {
            DELETE: {
                btnClass: 'btn-danger',
                action: function(){
                    var result = [];
                    $.getJSON("?r=gaz_station&f=delete_counter&p0="+id, function (data) {
                        result = data;
                    }).done(function () {
                        var table = $('#counters_table').DataTable();
                        table.ajax.url("?r=gaz_station&f=get_all_counters&p0="+gaz_station_id).load(function () {
                            var table = $('#dispensing_table').DataTable();
                            table.ajax.url("?r=gaz_station&f=get_all_dispensing_guns").load(function () {

                            }, false);
                        }, false);
                        
                    });
                }
            },
            CANCEL: {
                btnClass: 'btn-default any-other-class', // multiple classes.
                action: function(){
                    
                }
            },
        }
    });
}

function delete_disp_gun(id){
    $.confirm({
        title: 'Delete!',
        content: 'Are you sure?',
        buttons: {
            DELETE: {
                btnClass: 'btn-danger',
                action: function(){
                    var result = [];
                    $.getJSON("?r=gaz_station&f=delete_disp_gun&p0="+id, function (data) {
                        result = data;
                    }).done(function () {
                        var table = $('#dispensing_table').DataTable();
                        table.ajax.url("?r=gaz_station&f=get_all_dispensing_guns").load(function () {
                            prepare_to_render();
                        }, false);
                    });
                }
            },
            CANCEL: {
                btnClass: 'btn-default any-other-class', // multiple classes.
                action: function(){
                    
                }
            },
        }
    });
     
}

function set_new_counter(id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    var result = [];
    $.getJSON("?r=gaz_station&f=get_gun_details&p0="+id, function (data) {
        result = data;
    }).done(function () {
        $(".sk-circle-layer").hide(); 
        
        var modal_name = "modal_set_counter_gun_modal__";
        var modal_title = "Set New Counter For: <b>"+result[0].name+"</b>";
        var content =
        '<div class="modal large" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <input id="gun_id" name="gun_id" value="'+id+'" type="hidden" />\n\
                    <div class="modal-header">\n\
                        <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="new_counter_n">New Counter</label>\n\
                                    <input  autocomplete="off" id="new_counter_n" name="new_counter_n" value="" type="number" class="form-control med_input" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="new_counter_btn">&nbsp;</label>\n\
                                    <button id="new_counter_btn" onclick="set_currenct_counter('+id+',0)" style="width:100%" type="button" class="btn btn-primary">Set Counter for '+result[0].name+'</button>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <table style="width:100%" id="counters_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th style="width:60px;">ID</th>\n\
                                            <th style="width:70px">By</th>\n\
                                            <th style="width:120px">Date</th>\n\
                                            <th style="width:100px">Old Counter</th>\n\
                                            <th style="width:100px">New Counter</th>\n\
                                            <th style="width:100px">Difference (L)</th>\n\
                                            <th style="width:110px">Total Debt (L)</th>\n\
                                            <th style="width:110px">Total Cash (L)</th>\n\
                                            <th style="width:70px">Invoice ID</th>\n\
                                            <th>New Stock (L)</th>\n\
                                            <th style="width:50px">&nbsp;</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody></tbody>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer" style=" text-align: left;">\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';
        $("#"+modal_name).remove();
        $("body").append(content);
        //submitGenerateInvoice(modal_name,2);


        $('#'+modal_name).on('show.bs.modal', function (e) {

        });

        $('#'+modal_name).on('shown.bs.modal', function (e) {
            //$("#gun_name").focus();

            var table_name = "counters_table";
            var _cards_table__var =null;



            _cards_table__var = $('#'+table_name).DataTable({
                ajax: {
                    url: "?r=gaz_station&f=get_all_counters&p0="+id,
                    type: 'POST',
                    error:function(xhr,status,error) {
                    },
                    dataSrc: function (json) {
                        //$("#w_total").html("Total Wasting: "+json.total);
                        return json.data;
                    }
                },
                //order: [[1, 'asc']],
                responsive: true,
                orderCellsTop: true,
                scrollX: true,
                scrollY: "55vh",
                iDisplayLength: 10,
                aoColumnDefs: [
                    { "targets": [0], "searchable": false, "orderable": false,"visible": false },
                    { "targets": [1], "searchable": false, "orderable": false,"visible": true },
                    { "targets": [2], "searchable": false, "orderable": false,"visible": true },
                    { "targets": [3], "searchable": false, "orderable": false, "visible": true },
                    { "targets": [4], "searchable": false, "orderable": false, "visible": true },
                    { "targets": [5], "searchable": false, "orderable": false, "visible": true },
                    { "targets": [6], "searchable": false, "orderable": false, "visible": true },
                    { "targets": [7], "searchable": false, "orderable": false, "visible": true },
                    { "targets": [8], "searchable": false, "orderable": false, "visible": true },
                    { "targets": [9], "searchable": false, "orderable": false, "visible": true },
                    { "targets": [10], "searchable": false, "orderable": false, "visible": true },
                ],
                scrollCollapse: true,
                paging: true,
                bPaginate: false,
                bLengthChange: false,
                bFilter: true,
                bInfo: false,
                bAutoWidth: true,
                dom: '<"toolbardisgun_counter">frtip',
                initComplete: function(settings, json) {  

                },
                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).addClass(aData[0]);
                },
                 fnDrawCallback: function(){

                    var table = $('#'+table_name).DataTable();
                    var p = table.rows({ page: 'current' }).nodes();
                    for (var k = 0; k < p.length; k++){
                        var index = table.row(p[k]).index();
                        //if(table.cell(index,10).data()==1)
                            //table.cell(index,9).data('<i class="glyphicon glyphicon-trash" onclick="delete_waste(\''+parseInt(table.cell(index, 0).data())+'\')" style="font-size:18px;cursor:pointer" ></i>');
                    }
                 },
            });

            $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
                $('#counters_table .selected').removeClass("selected");
                $(this).addClass('selected');
            });


            $('#'+table_name).on('click', 'td', function () {
                if ($(this).index() == 4 || $(this).index() == 5) {
                    //return false;
                }
            });

        });
        $('#'+modal_name).on('hide.bs.modal', function (e) {
            $("#"+modal_name).remove();
        });
        $('#'+modal_name).modal('show');
    
    });
                    
    
}

function set_currenct_counter(id,save){
    var formData = new FormData();
    
    if($("#new_counter_n").val()==""){
        $("#new_counter_n").addClass("error");
        return;
    }else{
        $("#new_counter_n").removeClass("error");
    }
    
    formData.append("new_counter_n", $("#new_counter_n").val());
    formData.append("gaz_station_id", id);
    formData.append("save", save);
    
    $("#new_counter_btn").prop("disabled", true);
    
    
    $.ajax({
        url: "?r=gaz_station&f=submit_new_counter",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        success: function (data)
        {
            if(data.error==1){
         
                $.dialog({
                    title: 'Error!',
                    content: 'New counter is smaller than previous counter.',
                });
                
                
            }else{
                
                if(save==0){
                    $.confirm({
                        title: 'Set Counter!',
                        content: '' +
                        '<div class="form-group">' +
                            '<span>Old Counter:</span> <b>'+data.old_counter+'</b><br/>' +
                            '<span>New Counter:</span> <b>'+data.new_counter+'</b><br/>' +
                            '<span>Difference:</span> <b>'+data.difference+' (liters)</b><br/>' +
                            '<span>Total Debt:</span> <b>'+data.total_debt+' (liters)</b><br/>' +
                            '<span>Total Cash:</span> <b>'+data.total_cash+' (liters)</b><br/>' +
                            '<span>The system will generate a cash invoice for '+data.total_cash+' liters. </span>' +
                        '</div>' ,
                        buttons: {
                            CANCEL: {
                                btnClass: 'btn-default any-other-class', // multiple classes.
                                action: function(){

                                }
                            },
                            YES: {
                                btnClass: 'btn-success',
                                action: function(){
                                    set_currenct_counter(id,1);
                                }
                            }
                        }
                    });
                }else{
                    $("#new_counter_n").val("");
                    
                    var table = $('#counters_table').DataTable();
                    table.ajax.url("?r=gaz_station&f=get_all_counters&p0="+id).load(function () {

                    }, false);
                    
                    var table = $('#dispensing_table').DataTable();
                    table.ajax.url("?r=gaz_station&f=get_all_dispensing_guns").load(function () {

                    }, false);
                    
                    prepare_to_render();
            
                }
            }
            $("#new_counter_btn").prop("disabled", false);
                        
        }
    });
}



