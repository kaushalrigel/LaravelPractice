<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendEmailNotification;

use App\Models\Employee;
use App\Models\Task;
use App\Models\UserTask;
use App\Models\TaskNotification;
use Illuminate\Support\Facades\DB;

use Mail;



class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        /**  
         *    Send Notification Email to User
         */
        
        $notifications = DB::table('task_notifications')
            ->join('employee', 'task_notifications.empid', '=', 'employee.id')            
            ->select('task_notifications.*', 'employee.empname', 'employee.email')
            ->where('task_notifications.status','')
            ->get();

        foreach($notifications as $notification){

            $data = array('taskname'=>$notification->taskname,'username'=>$notification->empname);            

            $receiveremail=$notification->email;
            $fromemail = env('MAIL_FROM_ADDRESS');

            Mail::send('emails.notification', $data, function($message) use($receiveremail,$fromemail){
                $message->to($receiveremail, 'Laravel - ABZ')->subject
                    ('Task Allocation');
                $message->from($fromemail,'ABZ');
            });
        }
        
        /* ---------------------- */

    }
}
