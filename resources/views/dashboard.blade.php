
@extends('layouts.app')                 

    @section('pagecontent')

    <div class="col-md-2"></div>
    <div class="col-md-8">

        @if(session()->get('msgsuc') != '')
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="height:60px;" >
                {{ session()->get('msgsuc') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php
                session()->forget('msgsuc');
            @endphp
        @endif

        <p>&nbsp;</p>
        <a href="{{ route('manageemployee') }}" >
            <div class="" style="float:left;margin-left:10px;width:20%;height:150px;border:1px solid #d3d3d3;text-align:center;font-weight:bold;padding-top:65px;" >
                Manage Employee
            </div>
        </a>

        <a href="{{ route('managetask') }}" >
            <div class="" style="float:left;margin-left:10px;width:20%;height:150px;border:1px solid #d3d3d3;text-align:center;font-weight:bold;padding-top:65px;" >
                Manage Task
            </div>
        </a>
    </div>
    <div class="col-md-2"></div>

    @endsection

                                     
    @section('pagescripts')
    @endsection        
    
