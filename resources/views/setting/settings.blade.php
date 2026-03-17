<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sky Fitness Gym – premium gym portal.">
    <meta name="author" content="skyfitnessgym.com">
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

                                <div class="col-xl-4 col-md-6 mb-3">
                                    <label class="form-label fw-bold">Favicon</label>
                                    <input type="file" name="favicon" class="form-control mb-2" accept="image/png,image/x-icon,image/vnd.microsoft.icon">
                                    <div class="preview-box border p-2 text-center bg-light">
                                        <img src="{{ asset('public/' .\App\Models\Setting::get('favicon', 'assets/images/favicon.png')) }}"
                                             style="max-height: 48px; width: auto;">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row">
                                <div class="col-12 mb-2">
                                    <h5 class="mb-1">Theme Colors</h5>
                                    <p class="text-muted mb-0">These colors control buttons, pagination, badges, links, and highlights across the project.</p>
                                </div>

                                <div class="col-xl-4 col-md-6 mb-3">
                                    <label class="form-label fw-bold">Primary Color</label>
                                    <div class="input-group">
                                        <input type="color" name="theme_primary" class="form-control form-control-color"
                                               value="{{ \App\Models\Setting::get('theme_primary', '#7367f0') }}">
                                        <input type="text" name="theme_primary_text" class="form-control"
                                               value="{{ \App\Models\Setting::get('theme_primary', '#7367f0') }}"
                                               placeholder="#7367f0">
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-6 mb-3">
                                    <label class="form-label fw-bold">Secondary Color</label>
                                    <div class="input-group">
                                        <input type="color" name="theme_secondary" class="form-control form-control-color"
                                               value="{{ \App\Models\Setting::get('theme_secondary', '#00cfe8') }}">
                                        <input type="text" name="theme_secondary_text" class="form-control"
                                               value="{{ \App\Models\Setting::get('theme_secondary', '#00cfe8') }}"
                                               placeholder="#00cfe8">
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-6 mb-3">
                                    <label class="form-label fw-bold">Accent Color</label>
                                    <div class="input-group">
                                        <input type="color" name="theme_accent" class="form-control form-control-color"
                                               value="{{ \App\Models\Setting::get('theme_accent', '#0f9b8e') }}">
                                        <input type="text" name="theme_accent_text" class="form-control"
                                               value="{{ \App\Models\Setting::get('theme_accent', '#0f9b8e') }}"
                                               placeholder="#0f9b8e">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row">
                                <div class="col-12 mb-2">
                                    <h5 class="mb-1">Login Page Text</h5>
                                    <p class="text-muted mb-0">Control the right-side headline and bullet points on the login page.</p>
                                </div>

                                <div class="col-sm-12 mb-3">
                                    <label class="form-label fw-bold">Login Heading</label>
                                    <input type="text" name="login_heading" class="form-control"
                                           value="{{ \App\Models\Setting::get('login_heading', 'Train Smarter, Track Faster') }}">
                                </div>

                                <div class="col-sm-12 mb-3">
                                    <label class="form-label fw-bold">Login Description</label>
                                    <textarea name="login_description" class="form-control" rows="3">{{ \App\Models\Setting::get('login_description', 'Streamline daily operations with member profiles, smart attendance, and simple billing in one place.') }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Bullet 1</label>
                                    <input type="text" name="login_bullet_1" class="form-control"
                                           value="{{ \App\Models\Setting::get('login_bullet_1', 'Fast member check-ins') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Bullet 2</label>
                                    <input type="text" name="login_bullet_2" class="form-control"
                                           value="{{ \App\Models\Setting::get('login_bullet_2', 'Clean, modern reports') }}">
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
<script>
    (function() {
        const pairs = [
            { color: 'theme_primary', text: 'theme_primary_text' },
            { color: 'theme_secondary', text: 'theme_secondary_text' },
            { color: 'theme_accent', text: 'theme_accent_text' }
        ];

        const isHex = (val) => /^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/.test(val);

        pairs.forEach((pair) => {
            const colorInput = document.querySelector(`input[name="${pair.color}"]`);
            const textInput = document.querySelector(`input[name="${pair.text}"]`);
            if (!colorInput || !textInput) return;

            colorInput.addEventListener('input', () => {
                textInput.value = colorInput.value;
            });

            textInput.addEventListener('input', () => {
                if (isHex(textInput.value)) {
                    colorInput.value = textInput.value;
                }
            });
        });
    })();
</script>
@endsection
