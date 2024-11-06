@extends('layout')
<title>Login - {{$general_settings['company_title']}}</title>
@section('content')
<style>
    #submit_btn {
        background: linear-gradient(45deg, #007bff, #00aaff);
    }

    #submit_btn:hover {
        background: linear-gradient(45deg, #0056b3, #007bff);
        transform: scale(1.05);
    }

    #submit_btn:active {
        background: linear-gradient(45deg, #004085, #0066cc);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }

</style>


<!-- Content -->
<div class="container-fluid">
    @if (config('constants.ALLOW_MODIFICATION') === 0)
    <div class="col-12 text-center mt-4">
        <div class="alert alert-warning mb-0">
            <b>Note:</b> If you cannot log in here, please close the codecanyon frame by clicking on <b>x Remove Frame</b> button from the top right corner of the page or <a href="{{ url('/') }}" target="_blank">&gt;&gt; Click here &lt;&lt;</a>
        </div>
    </div>
    @endif
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Register -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <a href="/" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">
                                <img src="{{asset($general_settings['full_logo'])}}" width="300px" alt="" />
                            </span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">Welcome to HARMORA! 👋</h4>
                    <p class="mb-4">Sign into your account</p>
                    <form id="formAuthentication" class="mb-3 form-submit-event" action="/users/authenticate" method="POST">
                        <input type="hidden" name="redirect_url" value="/home">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="asterisk">*</span></label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="<?= get_label('please_enter_email', 'Please enter email') ?>" value="<?= config('constants.ALLOW_MODIFICATION') === 0 ? 'admin@gmail.com' : '' ?>" autofocus />
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password <span class="asterisk">*</span></label>
                                <a href="{{url('/forgot-password')}}">
                                    <small>Forgot Password?</small>
                                </a>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password" placeholder="<?= get_label('please_enter_password', 'Please enter password') ?>" value="<?= config('constants.ALLOW_MODIFICATION') === 0 ? '123456' : '' ?>" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            <span class="text-danger text-xs mt-1 error-message"></span>
                        </div>
                        <div class="mb-4">
                            <button class="btn btn-primary  d-grid w-100 border-0  text-white position-relative" id="submit_btn" type="submit">
                                Login
                                <div class="button-ripple"></div>
                            </button>
                        </div>
                        @if (!isset($general_settings['allowSignup']) || $general_settings['allowSignup'] == 1)
                        <div class="text-center">
                            <p class="mb-0">Don't have an account? <a href="/">Sign Up</a></p>
                        </div>
                        @endif

                    </form>
                </div>
            </div>
            <!-- /Register -->
        </div>
    </div>
</div>
<!-- / Content -->
@endsection
