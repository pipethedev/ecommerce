@extends('layouts.main')
@section('content')
                <div class="col-lg-12" style="padding-bottom: 8%; padding-top: 10%;">
                    <div class="login_form_inner">
                        <h3>Create a new account </h3>
                        <form class="row login_form" method="POST" action="{{ route('register') }}"  id="contactForm" novalidate="novalidate">
                             @csrf
                            <div class="col-md-12 form-group">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"  id="name" name="name" placeholder="Name" onfocus="this.placeholder = ''" 
                                onblur="this.placeholder = 'Name'"  value="{{ old('name') }}" required 
                                autocomplete="name" autofocus/>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-12 form-group">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'" required autocomplete="email" 
                                value="{{ old('email') }}"/>    
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-12 form-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'" required autocomplete="new-password" 
                               />    
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-12 form-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password_confirmation" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'" required autocomplete="new-password" 
                               />    
                            </div>
                            <div class="col-md-12 form-group">
                                <button type="submit" value="submit" class="primary-btn">{{ __('Register') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
@endsection
