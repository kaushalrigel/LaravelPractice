@extends('layouts.app')                 
                                       
        @section('pagecontent')

            <div class="col-md-2"></div>
            <div class="col-md-8">

                <p> <button class="btn btn-secondary btnAddNew" data-bs-toggle="modal" data-bs-target="#exampleModal"  >Add New</button> </p>

                <table id="tasklisttable" ></table>

            </div>
            <div class="col-md-2"></div>


            <!-- Modal popup --> 

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="frmAddTask" name="frmAddTask" method="post" enctype="multipart/form-data" >
                        <div class="modal-body">
                            
                                <input type="hidden" name="taskid" id="taskid" value="0" />

                                <div class="mb-3">
                                    <label for="recipient-name" class="col-form-label">Title:</label>
                                    <input type="text" class="form-control " name="taskname" id="taskname" />                                        

                                </div>
                                <div class="mb-3">
                                    <label for="message-text" class="col-form-label">Description:</label>
                                    <textarea class="form-control" name="description" id="description" >Description</textarea>
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


            <!-- Modal popup for Assign Task -->

            <div class="modal fade" id="assignTaskModal" tabindex="-1" aria-labelledby="assignTaskModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignTaskModalLabel">Assign Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form id="frmAssignTask" name="frmAssignTask" method="post" enctype="multipart/form-data" >

                        <div class="modal-body">                                                                

                                Assign <b> <span id="tasktitle" ></span> </b> to
                                <input type="hidden" name="taskid" id="taskid" />
                                <input type="hidden" name="mytaskname" id="mytaskname" />
                                
                                <div class="mb-3">
                                    <select name="listemployees" id="listemployees" >
                                        <option value="0" >-- Select Employee --</option>
                                        @foreach ($allEmployees as $employee)
                                            <option value="{{ $employee->id }}" >{{ $employee->empname }}</option>
                                        @endforeach
                                    </select>
                                    
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

                //  Datatable list of tasks
                dt = $("#tasklisttable").DataTable({
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
                                url: "{{ route('gettasklist') }}",
                                type: 'POST',
                                data:{}
                            },
                            columns: [                                
                                {data: "id", title: 'ID',visible:false},
                                {data: 'taskname', title: 'Title'},
                                {data: 'description', title: 'Description',sortable:false},                                
                                {data: 'created_at', title: 'Date'},
                                {data: 'action', title: 'Action',sortable:false,searchable:false},
                                //{data: 'UserName', title: 'UserName'},
                            ],
                            "drawCallback": function (settings) {
                                $('.paginate_button.previous').html("<a href='#' aria-controls='tasklisttable' data-dt-idx='0' tabindex='0' class='page-link'><i class='fa fa-angle-left fs-6'></i></a>");
                                $('.paginate_button.next').html("<a href='#' aria-controls='tasklisttable' data-dt-idx='0' tabindex='0' class='page-link'><i class='fa fa-angle-right fs-6'></i></a>");
                            },
                            initComplete: function () {
                                $('[data-toggle="m-popover"]').tooltip();
                                                                              

                            }
                        });
                

                $(".btnAddNew").click(function(){
                    $("#taskid").val('0');                             
                    $("#taskname").val('');
                    $("#description").val('');                    

                });
                
                //  Task Add Update submit
                $("#frmAddTask").submit(function(e){
                    e.preventDefault();                    
                
                    let taskid = $("#taskid").val();
                    let taskname = $("#taskname").val();
                    let description = $("#description").val();                    

                    if(taskname == ''){
                        alert("PLease enter Title of the Task");
                        return false;
                    }

                    if(description == ''){
                        alert("PLease enter Task Descirption ");
                        return false;
                    }                                
                                        
                    // console.log(formData);
                    $.ajax({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('createtask') }}",
                        type: 'POST',                        
                        data: {'taskid':taskid,'description':description,'taskname':taskname},
                        success: (response) => {
                            // success
                            alert(response.message);
                            $("#tasklisttable").DataTable().ajax.reload();
                            $('#exampleModal').modal('toggle');
                        },
                        error: (response) => {
                            alert("Please enter all required fields");
                        }
                    });
                });


                //  Task Assign submit
                $("#frmAssignTask").submit(function(e){
                    e.preventDefault();                    
                
                    let taskid = $("#taskid").val();                    
                    let listemployees = $("#listemployees").val(); 
                    let taskname = $("#mytaskname").val();         

                    if(listemployees == '0'){
                        alert("PLease select employee");
                        return false;
                    }                               
                                        
                    // console.log(formData);
                    $.ajax({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('assigntask') }}",
                        type: 'POST',                        
                        data: {'taskid':taskid,'empid':listemployees,'taskname':taskname},
                        success: (response) => {
                            // success
                            $('#assignTaskModal').modal('toggle');
                            alert(response.message);                                                        
                        },
                        error: (response) => {
                            alert("Please select employee");
                        }
                    });
                });

                                
            });

            $(document).on('click',".btndelete",function(){

                if(!confirm("Delete this Task?")){
                    return false;
                }

                var taskid = $(this).attr('data-taskid');
                $("#taskid").val(taskid);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{ route('removetask') }}",
                    type:'POST',
                    data:{'taskid':taskid},
                    success:function(response){                            
                        alert(response.message);            
                        $("#tasklisttable").DataTable().ajax.reload();            
                        
                    }
                });

            });


            $(document).on('click',".btnedit",function(){
                var taskid = $(this).attr('data-taskid');
                $("#taskid").val(taskid);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{ route('gettask') }}",
                    type:'POST',
                    data:{'taskid':taskid},
                    success:function(response){                            
                        
                        $("#taskid").val(response.data.id);
                        $("#taskname").val(response.data.taskname);
                        $("#description").val(response.data.description);                        

                    }
                });

            });


            $(document).on('click',".btnassign",function(){
                var taskid = $(this).attr('data-taskid');
                $("#taskid").val(taskid);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{ route('gettask') }}",
                    type:'POST',
                    data:{'taskid':taskid},
                    success:function(response){                            
                        
                        $("#taskid").val(response.data.id);
                        $("#tasktitle").html(response.data.taskname);                        
                        $("#mytaskname").val(response.data.taskname);                        

                    }
                });

            });

        </script>            
        
        @endsection
