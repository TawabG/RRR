@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Session: {{$session->id}}-{{$session->game->name}}</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Players</div>
                    <div class="panel-body">
                        <ul>
                            @if($session->players()->count() >= 1)
                        @foreach($session->players as $player)
                            <li>{{$player->name}}</li>
                        @endforeach
                                @else
                                Geen players in deze session
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Score</div>

                    <div class="panel-body">

                        <ul>
                            @if($session->players()->count() >= 1)

                        @foreach($session->scores->sortByDesc('points') as $score)
                            <li>{{$session->players()->where('player_id', $score->player_id)->firstOrFail()->name}} : {{$score->points}}</li>
                        @endforeach
                                @else
                                Geen scores in deze session
                            @endif
                        </ul>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Streak</div>

                    <div class="panel-body">

                        <ul>
                            @if($session->players()->count() >= 1)
                        @foreach($session->scores->sortByDesc('streak') as $score)
                            <li>{{$session->players()->where('player_id', $score->player_id)->firstOrFail()->name}} : {{$score->streak}}</li>
                        @endforeach
                                @else
                                Geen streak in deze session
                            @endif
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
