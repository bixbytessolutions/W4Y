@extends('layouts.app')

@section('content')
<section class="container content " >
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" >
           
                <div class="card-header"></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
