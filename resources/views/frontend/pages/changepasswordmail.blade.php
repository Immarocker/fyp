@extends('frontend.layouts.master')

@section('title', 'Kitabbhandaar || Register Page')

@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ route('home') }}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0);">Change Password</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->
    <!-- Shop Login -->
    <section class="shop login section">
        <div class="container">
            <div class="row"> 
                <div class="col-lg-6 offset-lg-3 col-12">
                    <div class="login-form">
                        <h2>Change Password</h2>
                        <p>Please enter the email to change the password?</p>
                        <!-- Form -->
                        <form class="form" method="post" action="{{ route('update.forgetpassword') }}">
                            @csrf
                            <div class="row">
                                
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>New Password<span>*</span></label>
                                        <input type="password" name="password" placeholder="" required="required">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Confirm Password<span>*</span></label>
                                        <input type="confirmpassword" name="password" placeholder="" required="required">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group login-btn">
                                        <button class="btn" type="submit">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--/ End Form -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ End Login -->
@endsection

@push('styles')
<style>
    .shop.login .form .btn {
        margin-right: 0;
    }
    .btn-facebook {
        background: #39579A;
    }
    .btn-facebook:hover {
        background: #073088 !important;
    }
    .btn-github {
        background: #444444;
        color: white;
    }
    .btn-github:hover {
        background: black !important;
    }
    .btn-google {
        background: #ea4335;
        color: white;
    }
    .btn-google:hover {
        background: rgb(243, 26, 26) !important;
    }
</style>
@endpush