    @extends('layouts.app')                 
                
        @section('pagecontent')

            <div class="col-md-2"></div>
            <div class="col-md-8">

                <p> <button class="btn btn-secondary btnAddNew" data-bs-toggle="modal" data-bs-target="#exampleModal"  >Add New</button> </p>

                <table id="emplisttable" ></table>

            </div>
            <div class="col-md-2"></div>


            <!-- Modal popup --> 

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="frmAddEmployee" name="frmAddEmployee" method="post" enctype="multipart/form-data" >
                        <div class="modal-body">
                            
                                <input type="hidden" name="empid" id="empid" value="0" />

                                <div class="mb-3">
                                    <label for="recipient-name" class="col-form-label">Name:</label>
                                    <input type="text" class="form-control " name="empname" id="empname" />                                        

                                </div>
                                <div class="mb-3">
                                    <label for="message-text" class="col-form-label">Photo:</label>
                                    <input type="file" class="form-control" name="photo" id="photo" />
                                </div>
                                <div class="mb-3">
                                    <label for="message-text" class="col-form-label">Email:</label>
                                    <input type="email" class="form-control" name="empemail" id="empemail" />
                                </div>
                                <div class="mb-3">
                                    <label for="message-text" class="col-form-label">Birth-Date:</label>
                                    <input type="text" class="form-control" name="dob" id="dob" />
                                </div>                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="btnSubmit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>

        @endsection
                

        @section('pagescripts')    

        <!--  Page jQuery code -->
        <script>
            $(document).ready(function(){

                //  Datatable list of employees
                dt = $("#emplisttable").DataTable({
                            serverSide: true,
                            order: [[0, "desc"]],
                            pageLength: 25,
                            serverSide: !0,
                            responsive: true,
                            lengthChange: true,
                            searchDelay: 500,
                            processing: true,
                            searchable: false,
                            lengthMenu: [25 , 50, 100, 150, 200],
                            paging: true,                                                                                                                                                                 
                            ajax : {
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: "{{ route('getemployeelist') }}",
                                type: 'POST',
                                data:{}
                            },
                            columns: [                                
                                {data: "id", title: 'ID',visible:false},
                                {data: 'photo', title: 'Photo',sortable:false,searchable:false},
                                {data: 'empname', title: 'Name'},
                                {data: 'email', title: 'Email'},
                                {data: 'dob', title: 'Birth-Date'},
                                {data: 'created_at', title: 'Date'},
                                {data: 'action', title: 'Action',sortable:false,searchable:false},
                                //{data: 'UserName', title: 'UserName'},
                            ],
                            "drawCallback": function (settings) {
                                $('.paginate_button.previous').html("<a href='#' aria-controls='emplisttable' data-dt-idx='0' tabindex='0' class='page-link'><i class='fa fa-angle-left fs-6'></i></a>");
                                $('.paginate_button.next').html("<a href='#' aria-controls='emplisttable' data-dt-idx='0' tabindex='0' class='page-link'><i class='fa fa-angle-right fs-6'></i></a>");
                            },
                            initComplete: function () {
                                $('[data-toggle="m-popover"]').tooltip();
                                                                              

                            }
                        });

                $( "#dob" ).datepicker({
                    'dateFormat':'yy-mm-dd'
                });        

                $(".btnAddNew").click(function(){
                    $("#empid").val('0');                             
                    $("#empname").val('');
                    $("#empemail").val('');
                    $("#dob").val('');

                });
                
                $("#frmAddEmployee").submit(function(e){
                    e.preventDefault();
                    var formData = new FormData();
                
                    let empid = $("input[name=empid]").val();
                    let empname = $("input[name=empname]").val();
                    let empemail = $("input[name=empemail]").val();
                    let dob = $("input[name=dob]").val();

                    if(empname == ''){
                        alert("PLease enter name of the employee");
                        return false;
                    }

                    if(empemail == ''){
                        alert("PLease enter email of the employee");
                        return false;
                    }

                    if(dob == ''){
                        alert("PLease select birth-date of the employee");
                        return false;
                    }

                    var photo = $('#photo').prop('files')[0];   
                    
                    formData.append('empid', empid);
                    formData.append('photo', photo);
                    formData.append('empname', empname);
                    formData.append('empemail', empemail);
                    formData.append('dob', dob);
                    // console.log(formData);
                    $.ajax({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('createemployee') }}",
                        type: 'POST',
                        contentType: 'multipart/form-data',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: (response) => {
                            // success
                            alert(response.message);
                            $("#emplisttable").DataTable().ajax.reload();
                            $('#exampleModal').modal('toggle');
                        },
                        error: (response) => {
                            alert("Please enter all required fields");
                        }
                    });
                });

                                  
            });

            $(document).on('click',".btndelete",function(){

                if(!confirm("Delete this employee?")){
                    return false;
                }

                var empid = $(this).attr('data-empid');
                $("#empid").val(empid);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{ route('removeemployee') }}",
                    type:'POST',
                    data:{'empid':empid},
                    success:function(response){                            
                        alert(response.message);            
                        $("#emplisttable").DataTable().ajax.reload();            
                        
                    }
                });

            });


            $(document).on('click',".btnedit",function(){
                var empid = $(this).attr('data-empid');
                $("#empid").val(empid);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{ route('getemployee') }}",
                    type:'POST',
                    data:{'empid':empid},
                    success:function(response){                            
                        
                        $("#empid").val(response.data.id);
                        $("#empname").val(response.data.empname);
                        $("#empemail").val(response.data.email);
                        $("#dob").val(response.data.dob);

                    }
                });

            });

        </script>            
        
        @endsection
    
