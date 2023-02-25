@extends('layouts.installer', ['title' => "Login to " . setting('brand.name')])

@section('content')


            <div class="card shadow-sm w-100 p-4 p-md-5" style="max-width: 32rem;">

                @if ($errors->any())
                    @foreach($errors->getMessages() as $error)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {!! $error[0] !!}
                        </div>
                @endforeach
            @endif
                <!-- Form -->
                <form method="post" action="/login" class="row g-3">
                    @method('POST')
                    @csrf
                    <div class="col-12 text-center mb-5">
                        <h1>Login</h1>
                        <span>Login to your account below.</span>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" required class="form-control form-control-lg" placeholder="name@mycompany.com"
                        @if(env('DEMO_MODE'))value="admin@demo.com" @endif>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" required class="form-control form-control-lg"
                               @if(env('DEMO_MODE'))value="demo" @endif>
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-lg btn-block btn-dark lift text-uppercase">Login</button>
                        <p class="mt-3">
                            <a class="text-{{currentMode() == 'dark' ? "white" : "primary"}}" href="/forgot">Forgot Password?</a>
                        </p>
                    </div>
                </form>
                <!-- End Form -->
            </div>
        </div>




@endsection
