@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif


                        <div class="row ">
                            <div class="col">
                                <form method="POST" action="" id="paymentForm">
                                    @csrf
                                    <div class="form-group col-6">
                                        <label for="exampleInputPassword1">Make A Payment</label>
                                        <input type="number" min="5" step="0.01" name="value"
                                            class="form-control" id="value">

                                        <small class="form-text">
                                            Use value with two decimal position, the default is 2dps
                                        </small>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="inputState">Select Currency</label>
                                            <select id="inputState" class="form-control">
                                                <option selected>Choose...</option>
                                                @foreach ($currencies as $currency)
                                                    <option value="{{ $currency->iso }}">{{ strtoupper($currency->iso) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col">
                                            <label for="">Select Payment Platform</label>
                                            <div class="form-group" id="toggler">
                                                <div class="button-group btn-group-toggle" data-toggle="buttons">

                                                    @foreach ($paymentPlatforms as $payment)
                                                        <label class="btn btn-outline-secondary rounded m-2 p-1" data-target="#{{$payment->name}}collapse" data-toggle="collapse">
                                                            <input type="radio" name="paymentplatform"
                                                                value="{{ $payment->id }}" required>
                                                            <img class="img-thumbnail" src="{{ asset($payment->image) }}">
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @foreach ($paymentPlatforms as $payment)
                                                    <div id="{{$payment->name}}collapse" class="collapse" data-target="#toggler">
                                                        @includeif(
                                                            'components.'.strtolower($payment->name).'-collapse')
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary mt-3">Submit</button>

                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
