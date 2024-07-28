function modal_example(){
    var content =
    '<div class="modal modal-xl" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">\n\
        <div class="modal-dialog">\n\
          <div class="modal-content">\n\
            <div class="modal-header">\n\
              <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>\n\
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>\n\
            </div>\n\
            <div class="modal-body">\n\
                <div class="row mt-2">\n\
                      <div class="col-lg-12">\n\
                      <table id="example" class="table table-striped" style="width:100%">\n\
                          <thead>\n\
                              <tr>\n\
                                  <th>ID</th>\n\
                                  <th>Name</th>\n\
                                  <th>Email</th>\n\
                              </tr>\n\
                          </thead>\n\
                          <tbody>\n\
                              <tr>\n\
                                  <td>1</td>\n\
                                  <td>John Doe</td>\n\
                                  <td>john@example.com</td>\n\
                              </tr>\n\
                              <tr>\n\
                                  <td>2</td>\n\
                                  <td>Jane Doe</td>\n\
                                  <td>jane@example.com</td>\n\
                              </tr>\n\
                          </tbody>\n\
                      </table>\n\
                  </div>\n\
              </div>\n\
          </div>\n\
        </div>\n\
      </div>';
    $("#exampleModal").modal('hide');
    $("body").append(content);
    $('#exampleModal').on('show.bs.modal', function (e) {

    });

    $('#exampleModal').on('shown.bs.modal', function (e) { 

        $('#sales_modal_table').DataTable({
                 ajax: {
                    url: "?r=reportsacc&f=get_all_sales&p0=0&p1=0&p2=0",
                    type: 'POST',
                    error:function(xhr,status,error) {
                        
                    },
                },
                "pagingType": "full_numbers", // Add pagination styles
                "scrollY": "300px", // Set the maximum height for the body
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], // Define page length menu
                "order": [[0, "asc"]], // Set default sorting column and order
                "language": {
                  "search": "Search records:",
                  "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                  }
                }
            });
    });

    $('#exampleModal').on('hide.bs.modal', function (e) {
        $("#exampleModal").remove();
    });
    $('#exampleModal').modal('show');
}