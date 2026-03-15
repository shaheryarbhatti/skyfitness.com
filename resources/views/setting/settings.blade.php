<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sky Fitness Gym – premium gym portal.">
    <meta name="author" content="skyfitnessgym.com">
    <link rel="icon" href="{{ asset('public/assets/images/favicon.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('public/assets/images/favicon.png')}}" type="image/x-icon">

    <title>{{ __('system_settings') }}</title>

@extends('layouts.app')
@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card" style="margin-top: 20px;">
                    <div class="card-header pb-0">
                        <h4>{{ __('system_settings') }}</h4>
                    </div>
                    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 mb-4">
                                    <label class="form-label fw-bold">{{ __('footer_text') }}</label>
                                    <input type="text" name="footer_text" class="form-control"
                                           value="{{ \App\Models\Setting::get('footer_text') }}"
                                           placeholder="e.g. Copyright 2026 © Sky Fitness Gym">
                                </div>

                                <div class="col-xl-4 col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ __('login_logo') }}</label>
                                    <input type="file" name="login_logo" class="form-control mb-2">
                                    <div class="preview-box border p-2 text-center bg-light">
                                        <img src="{{ asset('public/' .\App\Models\Setting::get('login_logo', 'public/assets/images/logo/logo.png')) }}"
                                             style="max-height: 80px; width: auto;">
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ __('admin_logo') }}</label>
                                    <input type="file" name="admin_logo" class="form-control mb-2">
                                    <div class="preview-box border p-2 text-center bg-light">
                                        <img src="{{ asset('public/' .\App\Models\Setting::get('admin_logo', 'public/assets/images/logo/logo_dark.png')) }}"
                                             style="max-height: 80px; width: auto;">
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ __('login_background_image') }}</label>
                                    <input type="file" name="login_bg_image" class="form-control mb-2">
                                    <div class="preview-box border p-2 text-center bg-light">
                                        <img src="{{ asset('public/' .\App\Models\Setting::get('login_bg_image', 'public/assets/images/login/bg.jpg')) }}"
                                             style="max-height: 80px; width: 100%; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn btn-primary" type="submit">{{ __('save_settings') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
