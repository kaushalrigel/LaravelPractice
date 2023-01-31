<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ url('css/style.css') }}">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>


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
                    <h2> <center> Dashboard </center></h2>            
                </div>                  

                <div class="col-md-12 text-start">
                    <span><b>&nbsp; <a href="javascript:void(0);" >Dashboard</a> </b></span> 
                </div>

                <div class="col-md-12 text-end">
                    <a href="{{ url('logout') }}" >Log Out</a>
                </div>                 

            </div>        

            <div id="pagecontent" class="row">                        
                
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <p>&nbsp;</p>
                    <a href="{{ route('manageemployee') }}" >
                        <div class="" style="float:left;margin-left:10px;width:20%;height:150px;border:1px solid #d3d3d3;text-align:center;font-weight:bold;padding-top:65px;" >
                            Manage Employee
                        </div>
                    </a>

                    <a href="#" >
                        <div class="" style="float:left;margin-left:10px;width:20%;height:150px;border:1px solid #d3d3d3;text-align:center;font-weight:bold;padding-top:65px;" >
                            Manage Task
                        </div>
                    </a>
                </div>
                <div class="col-md-2"></div>

            </div>    
        
            <div id="pagefooter" class="row">                        
                <div class="col-md-12"  >
                    <center><small> &#169;Abcdef Pvt Ltd </small></center>           
                </div>
            </div>        
            
        </div>        

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <script>
            $(document).ready(function(){
                $("#frmLogin").submit(function(){

                    var uemail = $("#uemail").val();
                    var upassword = $("#upassword").val();

                    if(uemail == ''){
                        alert("PLease enter email");
                        return false;
                    }

                    if(upassword == ''){
                        alert("PLease enter password");
                        return false;
                    }
                })                        
            });
        </script>            
                

    </body>
</html>
