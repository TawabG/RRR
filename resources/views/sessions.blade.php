@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Sessions</div>

                    <div class="panel-body">
                        View Sessions
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>View</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>No. of Players</th>
                            </tr>
                            </thead>
                            <tbody>
                        @foreach($sessions as $session)
                           <tr>
                               <td><a href="/session/{{$session->id}}">View</a></td>
                               <td>{{$session->id}}-{{$session->game->name}}</td>
                               <td>{{$session->status}}</td>
                               <td>{{$session->players->count()}}</td>
                           </tr>
                        @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
