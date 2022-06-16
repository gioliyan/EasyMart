@extends('layouts.app')
@section('content')
<link href="{{ URL::asset('admin/assets/plugins/nprogress/nprogress.css') }}" rel="stylesheet" />
<link id="sleek-css" rel="stylesheet" href="{{ URL::asset('admin/assets/css/sleek.css') }}" />
<link href="{{ URL::asset('admin/assets/img/favicon.png') }}" rel="shortcut icon" />

<body class="" id="body">
    <div class="container d-flex flex-column justify-content-between vh-100">
        <div class="row justify-content-center mt-5">
            <div class="col-xl-5 col-lg-6 col-md-10">
                <div class="card">
                    <div class="card-header bg-primary">
                        <div class="app-brand">
                            <a href="/index.html">
                                <svg class="brand-icon" xmlns="http://www.w3.org/2000/svg"
                                    preserveAspectRatio="xMidYMid" width="30" height="33" viewBox="0 0 30 33">
                                    <g fill="none" fill-rule="evenodd">
                                        <path class="logo-fill-blue" fill="#7DBCFF" d="M0 4v25l8 4V0zM22 4v25l8 4V0z" />
                                        <path class="logo-fill-white" fill="#FFF" d="M11 4v25l8 4V0z" />
                                    </g>
                                </svg>
                                <span class="brand-name">EasyMart</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-5">
                        <h4 class="text-dark mb-5">Sign In</h4>
                        <form action="{{ route('login') }}" method="POST" novalidate="novalidate">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-12 mb-4">
                                    <input type="email"
                                        class="form-control input-lg @error('email') is-invalid @enderror" id="email"
                                        aria-describedby="emailHelp" name="email" value="{{ old('email') }}"
                                        placeholder="Email" required autocomplete="email" autofocus>
                                </div>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="form-group col-md-12 ">
                                    <input type="password"
                                        class="form-control input-lg @error('password') is-invalid @enderror"
                                        id="password" placeholder="Password" name="password" required
                                        autocomplete="current-password">
                                </div>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="col-md-12">
                                    <div class="d-flex my-2 justify-content-between">
                                        <div class="d-inline-block mr-3">
                                            <label class="control control-checkbox">Remember me
                                                <input type="checkbox" name="remember" id="remember"
                                                    {{ old('remember') ? 'checked' : '' }} />
                                                <div class="control-indicator"></div>
                                            </label>

                                        </div>
                                        <p>
                                            @if (Route::has('password.request'))
                                            <a class="text-blue" href="{{ route('password.request') }}">Forgot Your
                                                Password?</a>
                                            @endif
                                        </p>

                                    </div>
                                    <button type="submit" class="btn btn-lg btn-primary btn-block mb-4">Sign In</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright pl-0">
            <p class="text-center">&copy; 2018 Copyright Sleek Dashboard Bootstrap Template by
                <a class="text-primary" href="http://www.iamabdus.com/" target="_blank">Abdus</a>.
            </p>
        </div>
    </div>
</body>
@endsection