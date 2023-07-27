@extends('layouts.app')

@section('content')

<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        <div>
            <img alt="image" class="img-main-logo" src="{{url('login_css/img/logo.png')}}" />
        </div>
        
        <div>
            <h1 class="logo-name">{{ config('app.name', 'Laravel') }}</h1>

        </div>
        <p></p>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            @if($errors->any())
                <div class="form-group alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <strong>{{$errors->first()}}</strong>
                </div>
            @endif
            <div class="form-group">
                <input type="email" placeholder="Email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required >
            </div>
            <div class="form-group">
                <input type="password" id="password" placeholder="Password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

            <a href="{{ route('password.request') }}"><small>Forgot password?</small></a>
          
        </form>
    </div>
</div>
@endsection
