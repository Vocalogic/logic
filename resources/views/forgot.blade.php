@extends('layouts.installer', ['title' => "Reset your " . setting('brand.name') . " Password"])

@section('content')

            <div class="card shadow-sm w-100 p-4 p-md-5" style="max-width: 32rem;">

                @if ($errors->any())
                    @foreach($errors->getMessages() as $error)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {!! $error[0] !!}
                        </div>
                    @endforeach
                @endif
                @if(session('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {!! session('message') !!}
                        </div>
                @endif

                <!-- Form -->
                <form method="post" action="/forgot" class="row g-3">
                    @method('POST')
                    @csrf
                    <div class="col-12 text-center mb-5">
                        <h1>Reset Password</h1>
                        <span>Enter your email below to reset your password.</span>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" required class="form-control form-control-lg" placeholder="name@mycompany.com">
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-lg btn-block btn-dark lift text-uppercase">Send Reset Link</button>
                        <p class="mt-3">
                            <a class="text-{{currentMode() == 'dark' ? "white" : "primary"}}" href="/login">I remember my password</a>
                        </p>
                    </div>
                </form>
                <!-- End Form -->
            </div>
        </div>




@endsection
