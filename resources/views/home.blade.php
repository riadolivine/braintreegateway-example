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

                        <div class="py-12">
                            @csrf
                            <div id="dropin-container" style="display: flex;justify-content: center;align-items: center;"></div>
                            <div style="display: flex;justify-content: center;align-items: center; color: white">
                                <a id="submit-button" class="btn btn-sm btn-success">Submit payment</a>
                            </div>

                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
