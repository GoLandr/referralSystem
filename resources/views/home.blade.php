@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    Referral link: <a href="{{ url('/register', ['referral_key' => $user->referral_key]) }}" target="_blank">{{ url('/register', ['referral_key' => $user->referral_key]) }}</a><br>
                    Balance: {{ number_format($user->balance, 2) }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Balance</div>

                <div class="panel-body">
                    <form class="form-inline" method="POST">
                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                            <label class="sr-only" for="amount">Amount (in dollars)</label>
                            <div class="input-group">
                                <div class="input-group-addon">$</div>
                                <input type="text" class="form-control" name="amount" placeholder="Amount" value="{{ old('amount') }}">
                            </div>

                            @if ($errors->has('amount'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                            @endif
                        </div>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <button type="submit" class="btn btn-primary" style="position: fixed; margin-left: 6px;">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Balance History</div>

                <div class="panel-body">
                    <table class="table table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Amount</th>
                            <th>Paid By</th>
                            <th>Paid On</th>
                            <th>Type</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ $payment->id }}</td>
                                <td>{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->payer_name }}</td>
                                <td>{{ $payment->created_at }}</td>
                                <td>
                                    @if ($payment->type == \App\Managers\PaymentManager::BASE_PAYMENT_TYPE)
                                        Payment
                                    @elseif ($payment->type == \App\Managers\PaymentManager::REFERRAL_PAYMENT_TYPE)
                                        Referral bonus
                                    @else
                                        Other
                                    @endif
                                </td>
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
