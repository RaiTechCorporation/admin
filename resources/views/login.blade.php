<!DOCTYPE html>
<html lang="en" dir="ltr" class="light">

<head>
    <meta charset="utf-8" />
    <title>{!! Session::get('app_name') !!}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="RetryTech" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">
    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/hyper-config.js') }}"></script>
    <!-- Vendor css -->
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Toast css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-toast-plugin/jquery.toast.min.css') }}">
    <!-- App css -->
    <link href="{{ asset('assets/css/app-saas.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <!-- Icons css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        :root {
            --ct-primary: #4f46e5;
            --ct-primary-rgb: 79, 70, 229;
        }

        .authentication-bg {
            background-color: #f8fafc !important;
            background-image: radial-gradient(circle at 10% 20%, rgba(79, 70, 229, 0.05) 0%, transparent 40%),
                              radial-gradient(circle at 90% 80%, rgba(79, 70, 229, 0.05) 0%, transparent 40%) !important;
        }

        .card {
            border: none !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            border-radius: 1rem !important;
            overflow: hidden;
        }

        .card-header.bg-primary {
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%) !important;
            border-bottom: none !important;
        }

        .btn-primary {
            background-color: #4f46e5 !important;
            border-color: #4f46e5 !important;
            font-weight: 600 !important;
            padding: 0.6rem 1rem !important;
            border-radius: 0.5rem !important;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: #4338ca !important;
            border-color: #4338ca !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2) !important;
        }

        .form-control:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
        }
    </style>
</head>

<body class="authentication-bg position-relative">
    <div class="position-absolute top-0 start-0 end-0 bottom-0 w-100 h-100" style="pointer-events: none;">
        <svg xmlns='http://www.w3.org/2000/svg' width='100%' height='100%' viewBox='0 0 800 800'>
            <g fill-opacity='0.22'>
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.1);" cx='400' cy='400' r='600' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.2);" cx='400' cy='400' r='500' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.3);" cx='400' cy='400' r='300' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.4);" cx='400' cy='400' r='200' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.5);" cx='400' cy='400' r='100' />
            </g>
        </svg>
    </div>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">
                        <div class="card-header py-4 text-center bg-primary">
                            <a href="index.html">
                                <span><img src="{{ asset('assets/img/logo-dark.png') }}" alt="logo"
                                        height="120"></span>
                            </a>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center pb-0 fw-bold">{{ __('Login') }}</h4>
                                <p class="text-muted mb-4">{{ __('Enter Username & Password to Login') }}</p>
                            </div>
                            <form id="loginForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="username" class="form-label">{{ __('Username') }}</label>
                                    <input class="form-control" type="text" id="username" name="username"
                                        required="" placeholder="Enter your username">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" name="password" class="form-control"
                                            placeholder="Enter your password">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 mb-0 text-center">
                                    <button class="btn btn-primary w-100" type="submit"> {{ __('Login') }} </button>
                                </div>

                                <hr>
                                <div class="text-center">
                                    <a class="text-danger" href="javascript:;" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">{{__('Forget Password?')}}</a>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<div id="forgotPasswordModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">{{ __('Forgot Password') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="forgotPasswordForm" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="database_username" class="form-label">{{ __('Database Username') }}</label>
                        <input class="form-control" type="text" id="database_username" name="database_username"
                            placeholder="Enter your database username" required="">
                    </div>
                    <div class="mb-3">
                        <label for="database_password" class="form-label">{{ __('Database Password') }}</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="database_password" name="database_password"
                                class="form-control" placeholder="Enter your database password" required="">
                            <div class="input-group-text" data-password="false">
                                <span class="password-eye"></span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">{{ __('New Password') }}</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="new_password" name="new_password" class="form-control"
                                placeholder="New Password" required="">
                            <div class="input-group-text" data-password="false">
                                <span class="password-eye"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">{{ __('Confirm Password') }}</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="confirm_password" name="confirm_password"
                                class="form-control" placeholder="Confirm Password" required="">
                            <div class="input-group-text" data-password="false">
                                <span class="password-eye"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm me-1 spinner hide" role="status"
                            aria-hidden="true"></span>
                        {{ __('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- scripts -->
<input type="hidden" value="{{ url('/') }}/" id="appUrl">
<!-- Vendor js -->
<script src="{{ asset('assets/js/vendor.min.js') }}"></script>
<!-- App js -->
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
<!-- Toast Plugin js -->
<script src="{{ asset('assets/vendor/jquery-toast-plugin/jquery.toast.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/demo.toastr.js') }}"></script>
<!-- Login js -->
<script src="{{ asset('assets/script/login.js') }}"></script>
</body>

</html>
