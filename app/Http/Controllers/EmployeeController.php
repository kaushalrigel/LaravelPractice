<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index(){
        return view('employeemanage');
    }
    
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

            $querylog = DB::getQueryLog();     
            //$querylog = end($querylog);
            //dd($query);
                        

            return response()->json([
                'recordsTotal' => $totalEmployees,
                'recordsFiltered' => $totalEmployees,
                'data' => $employees->map(function ($employee){

                    $actionButtons='<button class="btn btn-primary btn-sm" >Edit</button> &nbsp; ';
                    $actionButtons.='<button class="btn btn-danger btn-sm" >Delete</button>';
                    
                    return [
                        'id'          => $employee->id,
                        'photo'       => $employee->photo,
                        'empname'     => $employee->empname,
                        'email'       => $employee->email,                        
                        'dob'         => $employee->dob,                        
                        'created_at' => date('Y-m-d  H:i',strtotime($employee->created_at)),
                        'action' => $actionButtons,
                        //'UserName' => $dispatcher->username                        
                    ];
                })
            ]);
        }
    }

}
