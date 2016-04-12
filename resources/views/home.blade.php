@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    Referral link: <a href="{{ url('/register', ['referral_key' => $user->referral_key]) }}" target="_blank">{{ url('/register', ['referral_key' => $user->referral_key]) }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Balance</div>

                <div class="panel-body">
                    Form with button
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Balance History</div>

                <div class="panel-body">
                    Table
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
