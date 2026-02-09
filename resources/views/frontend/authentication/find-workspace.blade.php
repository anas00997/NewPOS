<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Find Workspace | {{ readConfig('site_name') }}
    </title>
    <!-- FAVICON ICON -->
    <link rel="shortcut icon" href="{{ assetImage(readconfig('site_logo')) }}" type="image/svg+xml">
    <!-- BACK-TOP CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/back-top/backToTop.css') }}">
    <!-- BOOTSTRAP CSS (5.3) -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.min.css') }}">
    <!-- APP-CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.min.css') }}">
</head>

<body>
    <x-simple-alert />

    <!-- AUTHENTICATION-START (LOGIN) -->
    <section class="authentications">
        <div class="left-content">
            <figure class="">
                <img src="{{ asset('assets/images/authentication/register.svg') }}" alt="register image ">
            </figure>
        </div>
        <div class="right-content">
            <form action="{{ route('login') }}" method="post" class="authentication-form px-lg-5 needs-validation"
                novalidate>
                @csrf
                <div class="authentication-form-header">
                    <a href="{{ route('frontend.home') }}" class="logo">
                        <img src="{{ assetImage(readconfig('site_logo')) }}" width="200px" alt="brand-logo">
                    </a>
                    <h3 class="form-title">Find Your Workspace</h3>
                    <p class="form-des">Enter your company's domain/subdomain to continue.</p>
                </div>
                <div class="authentication-form-content">
                    <div class="row g-4">

                        <div class="col-sm-6 col-lg-12 col-xl-6">
                            <div class="form-group">
                                <label for="company_domain" class="form-label">Company Domain</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="company_domain" placeholder="your-company"
                                        autocomplete="off" name="company_domain" required>
                                    @php
                                        $host = request()->getHost();
                                        if ($host === 'localhost' || $host === '127.0.0.1') {
                                            $suffix = '.localhost';
                                        } elseif (filter_var($host, FILTER_VALIDATE_IP)) {
                                            $suffix = '.' . $host . '.nip.io';
                                        } else {
                                            $suffix = '.' . $host;
                                        }
                                    @endphp
                                    <span class="input-group-text">{{ $suffix }}</span>
                                </div>
                                <div class="invalid-feedback">
                                    Please enter your company domain.
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <div class="form-group">
                                <button type="submit" class="create-account-btn w-100">
                                    Continue
                                </button>
                            </div>
                        </div>

                        <div class="col-12">
                            <p class="form-des text-center">
                                Don't have an account? <a href="{{ route('signup') }}" class="text-primary">Sign up</a>
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- AUTHENTICATION-END -->

    <!-- JQUERY (3.6.0) -->
    <script src="{{ asset('assets/js/jquery/jquery-3.6.0.min.js') }}"></script>
    <!-- BOOTSTRAP (5.3) -->
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- MAIN-JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        // Simple validation script
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>

</html>
