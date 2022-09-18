@extends('main')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">User Profile</h3>
                </div>

                <form action="{{ url('/user-profile/update-profile') }}" method="post">
                    <div class="card-body">
                        @if (session()->has('successUpdateProfile'))
                            <div class="alert alert-success" role="alert">
                                {{ session('successUpdateProfile') }}
                            </div>
                        @endif
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                placeholder="Enter email" value="{{ $data['name'] }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" value="{{ $data['email'] }}" disabled>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Change Password</h3>
                </div>

                <form action="{{ url('/user-profile/update-password') }}" method="post">
                    <div class="card-body">
                        @if (session()->has('successUpdatePassword'))
                            <div class="alert alert-success" role="alert">
                                {{ session('successUpdatePassword') }}
                            </div>
                        @endif
                        @if (session()->has('savePasswordError'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('savePasswordError') }}
                            </div>
                        @endif
                        @csrf
                        <div class="form-group">
                            <label for="old_password">Old Password</label>
                            <input type="password" class="form-control @error('old_password') is-invalid @enderror"
                                name="old_password" placeholder="Password">
                            @error('old_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="new_password">Password</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                name="new_password" placeholder="Password">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="new_password_confirmation">Repeat Password</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                name="new_password_confirmation" placeholder="Password">
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
