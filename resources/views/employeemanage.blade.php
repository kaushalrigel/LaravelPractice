<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>Laravel</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

        <!--  jQuery Ui Datepicker css -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ url('css/style.css') }}">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
                
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <!-- Datatable CSS & JS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">        
        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

        <!-- jQeruy UI JS -->
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

        <!-- Styles -->        

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="">
       
        <div class="container-fluid" >   

            <div id="pageheader" class="row">                        
                <div class="col-md-12">
                    <h2> <center> Manage Employees </center></h2>            
                </div>                                      

                <div class="col-md-12 text-start">
                    <span><b>&nbsp; <a href="{{ url('dashboard') }}" >Dashboard</a> </b></span> 
                </div>

                <div class="col-md-12 text-end">
                    <a href="{{ url('logout') }}" >Log Out</a>
                </div>                 

            </div>        

            <div id="pagecontent" class="row mt-5 pt-5">                        
                
                <div class="col-md-2"></div>
                <div class="col-md-8">

                    <p> <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal"  >Add New</button> </p>

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
                        <div class="modal-body">
                            <form id="frmAddEmployee" name="frmAddEmployee" >

                                <input type="hidden" name="empid" value="0" />

                                <div class="mb-3">
                                    <label for="recipient-name" class="col-form-label">Name:</label>
                                    <input type="text" class="form-control" name="empname" id="empname" />
                                </div>
                                <div class="mb-3">
                                    <label for="message-text" class="col-form-label">Photo:</label>
                                    <input type="file" class="form-control" name="photo" id="photo" />
                                </div>
                                <div class="mb-3">
                                    <label for="message-text" class="col-form-label">Email:</label>
                                    <input type="text" class="form-control" name="empemail" id="empemail" />
                                </div>
                                <div class="mb-3">
                                    <label for="message-text" class="col-form-label">Birth-Date:</label>
                                    <input type="text" class="form-control" name="dob" id="dob" />
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Submit</button>
                        </div>
                        </div>
                    </div>
                </div>

            </div>    
        
            <div id="pagefooter" class="row">                        
                <div class="col-md-12">
                    <center><small> &#169;Abcdef Pvt Ltd </small></center>           
                </div>
            </div>        
        </div>    

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>    

        <!--  Page jQuery code -->
        <script>
            $(document).ready(function(){

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

                $("#frmAddEmployee").submit(function(){

                    var empname = $("#empname").val();
                    var empemail = $("#empemail").val();
                    var dob = $("#dob").val();

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
                })                        
            });
        </script>            
        
        

    </body>
</html>
