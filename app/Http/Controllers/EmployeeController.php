<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    // main page view
    public function index(){
        return view('employeemanage');
    }


    //  Get single employee record
    public function getEmployeeInfo(Request $request){
        $response = array('status'=>0,'message'=>'','data'=>[]);

        $empid =  $request->input('empid');
        $employee = Employee::find($empid);

        $response = array('status'=>1,'message'=>'','data'=>$employee);

        return response()->json($response);
        exit;
    }


    //    Delete Employee
    public function deleteEmployee(Request $request){
        $response = array('status'=>0,'message'=>'','data'=>[]);
        $empid =  $request->input('empid');
        $employee = Employee::find($empid);
        $employee->delete();

        $response = array('status'=>1,'message'=>'Deleted successfully','data'=>[]);

        return response()->json($response);
        exit;

    }

    //    Add new & update Employee
    public function createNewEmployee(Request $request){
            
        $validatedData = $request->validate([
            'empname' => ['required'],
            'empemail' => ['required','email'],
            'dob' => ['required'],
        ]);
        

        $response = array('status'=>0,'message'=>'','data'=>[]);

        $empid =  $request->input('empid');
        $empname =  $request->input('empname');
        $empemail =  $request->input('empemail');
        $dob     =  $request->input('dob');        
        
        if($empid > 0){
            $employee = Employee::find($empid);
            $employee->empname = $empname; 
            $employee->email = $empemail; 
            $employee->dob = $dob; 

            /* Save Photo */

            if($request->hasFile('photo')){
                $filename = $request->file('photo')->getClientOriginalName();
                $extension = $request->file('photo')->getClientOriginalExtension();
                $newfilename = rand().time().".".$extension;
                $path = $request->file('photo')->storeAs("public/employeephotos/",$newfilename);
                $employee->photo = $newfilename; 
            }                    
            /* --- End save photo  --- */

            $saved = $employee->save();

            if($saved){
                $response = array('status'=>1,'message'=>'Updated successfully!... ','data'=>[]);        
                return response()->json($response);
                exit;
            }
        }

        $curEmp = Employee::where('email', '=', $empemail)->first();        

        if (!empty($curEmp)) {
            $response = array('status'=>0,'message'=>'Email already exist!... ','data'=>[]);        
            return response()->json($response);
        }

        $employee = new Employee;
        $employee->empname = $empname; 
        $employee->email = $empemail; 
        $employee->dob = $dob; 
        $employee->photo = '';

        /* Save Photo */

        if($request->hasFile('photo')){
            $filename = $request->file('photo')->getClientOriginalName();
            $extension = $request->file('photo')->getClientOriginalExtension();
            $newfilename = rand().time().".".$extension;
            $path = $request->file('photo')->storeAs("public/employeephotos/",$newfilename);
            $employee->photo = $newfilename; 
        }                    
        /* --- End save photo  --- */

        $saved = $employee->save();

        if($saved){
            $response = array('status'=>1,'message'=>'Employee Added successfully!... ','data'=>[]);        
        }

        return response()->json($response);

    }
    

    //  Datatable Employees listing  
    public function getEmployeeList(Request $request)
    {
        //if request is ajax
        if ($request->ajax()) {
            // Search keyword and sorting directions
            $keyword    = $request->search['value'];
            $column     = $request->order[0]['column'];
            $direction  = $request->order[0]['dir'];
            $start      = $request->start;
            $length     = $request->length;
            $fromdate   = $request->fromdate;
            $todate     = $request->todate;                                    

            $query = Employee::select('*');                        

            if($keyword != ''){
                $query->Where('empname', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('email', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('dob', '=',  $keyword );
            }
            

            // sorting grid base on the default column index
            switch ($column) {                
                case 0:
                    $query->orderBy('id', $direction);
                    break;
                case 1:
                    $query->orderBy('photo', $direction);
                    break;
                case 2:
                    $query->orderBy('empname', $direction);
                    break;
                case 3:
                    $query->orderBy('email', $direction);
                    break;    
                case 4:
                    $query->orderBy('dob', $direction);    
                    break;
                case 5:
                    $query->orderBy('created_at', $direction);
                    break;
                default:
                    break;
            }

            // To count total domains
            $totalEmployees = $query->count();                 
            $employees = $query->limit($length)->offset($start)->get();       

            //$querylog = DB::getQueryLog();     
            //$querylog = end($querylog);
            //dd($query);
                        

            return response()->json([
                'recordsTotal' => $totalEmployees,
                'recordsFiltered' => $totalEmployees,
                'data' => $employees->map(function ($employee){

                    $empphoto='';                    
                    if($employee->photo != ''){
                        $picurl = asset('storage/employeephotos/'.$employee->photo);
                        $empphoto='<img src="'.$picurl.'"  width="50" />';
                    }else{
                        $empphoto=' ';
                    }
                    
                    $actionButtons='<button data-empid="'.$employee->id.'" class="btn btn-primary btn-sm btnedit"  data-bs-toggle="modal" data-bs-target="#exampleModal" >Edit</button> &nbsp; ';
                    $actionButtons.='<button data-empid="'.$employee->id.'" class="btn btn-danger btn-sm btndelete" >Delete</button>';
                    
                    return [
                        'id'          => $employee->id,
                        'photo'       => $empphoto,
                        'empname'     => $employee->empname,
                        'email'       => $employee->email,                        
                        'dob'         => $employee->dob,                        
                        'created_at' => date('Y-m-d  H:i',strtotime($employee->created_at)),
                        'action' => $actionButtons,                        
                    ];
                })
            ]);
        }
    }

}
