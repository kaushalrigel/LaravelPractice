<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Task;
use App\Models\UserTask;
use App\Models\TaskNotification;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mail;

class TaskController extends Controller
{
    /**  
     * main page view
     * 
     * 
     */
    public function index(){

        $allEmployees = Employee::all();               

        return view('taskmanage',['allEmployees'=>$allEmployees]);
    }

    /**  
     * For Employees select box values
     * 
     * 
     */
    public function getAllEmployees(){
    
        $response = array('status'=>0,'message'=>'','data'=>[]);
        

    }

    //  Get single task record
    public function getTaskInfo(Request $request){
        $response = array('status'=>0,'message'=>'','data'=>[]);

        $taskid =  $request->input('taskid');
        $task = Task::find($taskid);

        $response = array('status'=>1,'message'=>'','data'=>$task);

        return response()->json($response);
        exit;
    }


    //    Delete Task
    public function deleteTask(Request $request){
        $response = array('status'=>0,'message'=>'','data'=>[]);
        $taskid =  $request->input('taskid');
        $task = Task::find($taskid);
        $task->delete();

        $response = array('status'=>1,'message'=>'Deleted successfully','data'=>[]);

        return response()->json($response);
        exit;

    }

     //    Assign Task to Employee
     public function assignTaskToEmp(Request $request){
            
        $validatedData = $request->validate([
            'empid' => ['required'],            
        ]);        

        $response = array('status'=>0,'message'=>'','data'=>[]);

        $taskid =  $request->input('taskid');
        $empid =  $request->input('empid');    
        $taskname =  $request->input('taskname');       
                   
        $userTask = new UserTask;
        $userTask->taskid = $taskid; 
        $userTask->empid = $empid;                
        $saved = $userTask->save();

        if($saved){

            $taskNotification = new TaskNotification;
            $taskNotification->empid = $empid; 
            $taskNotification->detail = 'You have been assigned a task '.$taskname.'  by the Admin';                
            $saved = $taskNotification->save();

            $employee = Employee::find($empid);
            $receiveremail = $employee->email;
            $fromemail = env('MAIL_FROM_ADDRESS');

            /**  
             *    Send Notification Email to User
             */
            $data = array('taskname'=>$taskname,'username'=>$employee->empname);
            Mail::send('emails.notification', $data, function($message) use($receiveremail,$fromemail){
                $message->to($receiveremail, 'Laravel - ABZ')->subject
                    ('Task Allocation');
                $message->from($fromemail,'ABZ');
            });
            /* ---------------------- */


            $response = array('status'=>1,'message'=>'Task Assigned successfully!... ','data'=>[]);        
        }

        return response()->json($response);

    }

    //    Add new & update Task
    public function createNewTask(Request $request){
            
        $validatedData = $request->validate([
            'taskname' => ['required'],
            'description' => ['required'],            
        ]);        

        $response = array('status'=>0,'message'=>'','data'=>[]);

        $taskid =  $request->input('taskid');
        $taskname =  $request->input('taskname');
        $description =  $request->input('description');        
        
        if($taskid > 0){
            $task = Task::find($taskid);
            $task->taskname = $taskname; 
            $task->description = $description;                         

            $saved = $task->save();

            if($saved){
                $response = array('status'=>1,'message'=>'Updated successfully!... ','data'=>[]);        
                return response()->json($response);
                exit;
            }
        }       

        $task = new Task;
        $task->taskname = $taskname; 
        $task->description = $description;                 

        /* Save Photo */

        if($request->hasFile('photo')){
            $filename = $request->file('photo')->getClientOriginalName();
            $extension = $request->file('photo')->getClientOriginalExtension();
            $newfilename = rand().time().".".$extension;
            $path = $request->file('photo')->storeAs("public/taskphotos/",$newfilename);
            $task->photo = $newfilename; 
        }                    
        /* --- End save photo  --- */

        $saved = $task->save();

        if($saved){
            $response = array('status'=>1,'message'=>'Task Added successfully!... ','data'=>[]);        
        }

        return response()->json($response);

    }
    

    //  Datatable Tasks listing  
    public function getTaskList(Request $request)
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

            $query = Task::select('*');                        

            if($keyword != ''){
                $query->Where('taskname', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('description', 'LIKE', '%' . $keyword . '%');                
            }
            

            // sorting grid base on the default column index
            switch ($column) {                
                case 0:
                    $query->orderBy('id', $direction);
                    break;
                case 1:
                    $query->orderBy('taskname', $direction);
                    break;
                case 2:
                    $query->orderBy('description', $direction);
                    break;                
                case 3:
                    $query->orderBy('created_at', $direction);
                    break;
                default:
                    break;
            }

            // To count total domains
            $totalTasks = $query->count();                 
            $tasks = $query->limit($length)->offset($start)->get();       

            $querylog = DB::getQueryLog();     
            //$querylog = end($querylog);
            //dd($query);
                        

            return response()->json([
                'recordsTotal' => $totalTasks,
                'recordsFiltered' => $totalTasks,
                'data' => $tasks->map(function ($task){

                   
                    $actionButtons='<button data-taskid="'.$task->id.'" class="btn btn-primary btn-sm btnedit"  data-bs-toggle="modal" data-bs-target="#exampleModal" >Edit</button> &nbsp; ';
                    $actionButtons.='<button data-taskid="'.$task->id.'" class="btn btn-danger btn-sm btndelete" >Delete</button> &nbsp; ';                    
                    $actionButtons.='<button data-taskid="'.$task->id.'" class="btn btn-dark btn-sm btnassign" data-bs-toggle="modal" data-bs-target="#assignTaskModal" >Assign Task</button>';

                    return [
                        'id'           => $task->id,                        
                        'taskname'     => $task->taskname,
                        'description'  => $task->description,                                                
                        'created_at'   => date('Y-m-d  H:i',strtotime($task->created_at)),
                        'action' => $actionButtons,                        
                    ];
                })
            ]);
        }
    }

}
