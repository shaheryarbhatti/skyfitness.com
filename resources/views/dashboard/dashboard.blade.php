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

    <title>{{ __('Sky Fitness Dashboard') }}</title>

    @extends('layouts.app')
    @section('content')
    <style>
        .dashboard-hero {
            background: linear-gradient(120deg, rgba(115, 103, 240, 0.14), rgba(0, 207, 232, 0.16));
            border-radius: 18px;
            padding: 22px 24px;
            box-shadow: 0 16px 40px rgba(18, 38, 63, 0.08);
            position: relative;
            overflow: hidden;
            animation: heroFade 0.6s ease both;
        }

        .dashboard-hero::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            right: -60px;
            top: -70px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.6), transparent 65%);
            opacity: 0.6;
        }

        .stat-card {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            color: #fff;
            box-shadow: 0 18px 36px rgba(18, 38, 63, 0.12);
            transform: translateY(6px);
            animation: fadeUp 0.7s ease both;
        }

        .stat-card .card-body {
            padding: 20px 22px;
            min-height: 150px;
        }

        .stat-card .icon-pill {
            width: 54px;
            height: 54px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.18);
        }

        .stat-card:hover {
            transform: translateY(0);
            box-shadow: 0 22px 44px rgba(18, 38, 63, 0.18);
        }

        .soft-card {
            border-radius: 18px;
            box-shadow: 0 14px 30px rgba(18, 38, 63, 0.08);
        }

        .activity-card,
        .payment-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .activity-card:hover,
        .payment-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(18, 38, 63, 0.12);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes heroFade {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h3>{{ __('Sky Fitness Dashboard') }}</h3>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}"><i data-feather="home"></i></a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            @if (Auth::user()->hasRole(['Admin', 'Super Admin']))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="dashboard-hero d-flex flex-wrap align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bold mb-1 text-dark">{{ __('Sky Fitness Dashboard') }}</h3>
                            <p class="text-muted mb-0">Overview of members, trainers, and recent activity.</p>
                        </div>
                        <div class="text-end mt-3 mt-lg-0">
                            <span class="badge bg-primary text-white px-3 py-2" style="border-radius: 999px;">
                                {{ now()->format('l, d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card stat-card">
                        <div class="card-body" style="background: linear-gradient(135deg, var(--theme-default), #764ba2);">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 text-white">
                                    <h4 class="mb-1 fw-bold text-white">{{ number_format($totalMembers) }}</h4>
                                    <p class="mb-0 fw-medium text-white">{{ __('Total Members') }}</p>
                                    <small class="text-white opacity-75">{{ __('All registered gym members') }}</small>
                                </div>
                                <div class="icon-pill">
                                    <i class="fa fa-users text-white fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card stat-card">
                        <div class="card-body" style="background: linear-gradient(135deg, #ff6a88, #ff99ac);">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 text-white">
                                    <h4 class="mb-1 fw-bold text-white">{{ number_format($activeMembers) }}</h4>
                                    <p class="mb-0 fw-medium text-white">{{ __('Active Members') }}</p>
                                    <small class="text-white opacity-75">{{ __('Members currently active') }}</small>
                                </div>
                                <div class="icon-pill">
                                    <i class="fa-regular fa-circle-check text-white fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card stat-card">
                        <div class="card-body" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 text-white">
                                    <h4 class="mb-1 fw-bold text-white">{{ number_format($totalTrainers) }}</h4>
                                    <p class="mb-0 fw-medium text-white">{{ __('Total Trainers') }}</p>
                                    <small class="text-white opacity-75">{{ __('Sky Fitness professional staff') }}</small>
                                </div>
                                <div class="icon-pill">
                                    <i class="fa fa-id-badge text-white fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-xl-6 mb-4">
                    <div class="card soft-card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom-0 pb-0">
                            <h4 class="fw-bold"><i
                                    class="fa fa-history text-primary me-2"></i>{{ __('Recent User Activity') }}</h4>
                        </div>
                        <div class="card-body pt-2">
                            <div class="user-status table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        @forelse($recentActivity as $activity)
                                        <div class="activity-card d-flex align-items-center p-3 mb-3 shadow-none border"
                                            style="border-radius: 12px; background: #f0f7ff; border-color: #e1effe !important;">

                                            <div class="p-3 bg-light-primary rounded-circle me-3"
                                                style="width: 55px; height: 55px; display: flex; align-items: center; justify-content: center; background-color: rgba(115, 103, 240, 0.1) !important;">
                                                <i class="fa fa-user text-primary fa-lg"></i>
                                            </div>

                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold text-dark">{{ $activity->name }}</h6>
                                                <div class="d-flex align-items-center">
                                                    <small class="text-muted">
                                                        <i class="fa fa-clock-o me-1"></i>
                                                        {{ $activity->last_login_at->format('d M Y - h:i A') }}
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <span class="badge bg-primary text-white px-3 py-2"
                                                    style="border-radius: 8px; font-size: 10px;">
                                                    {{ __('Logged In') }}
                                                </span>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="text-center p-4">
                                            <p class="text-muted mb-0">{{ __('No recent activity found') }}</p>
                                        </div>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 mb-4">
                    <div class="card soft-card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom-0 pb-0">
                            <h4 class="fw-bold"><i class="fa fa-money text-success me-2"></i>{{ __('Recent Payments') }}
                            </h4>
                        </div>
                        <div class="card-body pt-2">
                            @forelse($recentInvoices as $invoice)
                            <div class="payment-card d-flex align-items-center p-3 mb-3 shadow-none border"
                                style="border-radius: 12px; background: #f8fdfa; border-color: #e6f4ea !important;">
                                <div class="p-3 bg-light-success rounded-circle me-3"
                                    style="width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-credit-card text-success fa-lg"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold text-dark">{{ $invoice->invoice_no }}</h6>
                                    <small class="text-muted">{{ $invoice->member->full_name ?? 'N/A' }}</small>
                                </div>
                                <div class="text-end">
                                    <h6 class="mb-1 text-success fw-bold">Rp {{ number_format($invoice->fee, 0, ',') }}
                                    </h6>
                                    <span class="badge bg-success text-white px-3 py-2"
                                        style="border-radius: 8px;">{{ __('Success') }}</span>
                                </div>
                            </div>
                            @empty
                            <p class="text-center p-4">{{ __('No recent payments recorded') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-md-8 col-lg-5 mx-auto py-5 text-center">
                    <div class="card shadow-lg border-0 p-4" style="border-radius: 30px; background: #fff;">
                        <div class="mb-4">
                            <div class="mx-auto bg-light rounded-circle d-flex align-items-center justify-content-center shadow-inner"
                                style="width: 100px; height: 100px;">
                                <i class="fa-solid fa-clock fa-3x text-secondary"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-3 text-dark">{{ __('Member Attendance') }}</h2>
                        <p class="text-muted px-4 mb-4">{{ __('attendance_instruction') }}</p>

                        <form action="{{ route('attendance.store') }}" method="POST">
                            @csrf
                            @if(!$isCheckedIn)
                            <button type="submit" name="action" value="checkin"
                                class="btn btn-lg w-100 shadow-lg text-white"
                                style="background: linear-gradient(to right, #00b09b, #96c93d); border-radius: 20px; padding: 20px; border: none;">
                                <i class="fa fa-sign-in fa-lg me-2"></i> <strong>{{ __('CHECK IN NOW') }}</strong>
                            </button>
                            @else
                            <button type="submit" name="action" value="checkout"
                                class="btn btn-lg w-100 shadow-lg text-white"
                                style="background: linear-gradient(to right, #ff416c, #ff4b2b); border-radius: 20px; padding: 20px; border: none;">
                                <i class="fa fa-sign-out fa-lg me-2"></i> <strong>{{ __('CHECK OUT NOW') }}</strong>
                            </button>
                            <p class="mt-3 text-success"><i class="fa fa-circle me-1"></i> {{ __('You are currently in the gym') }}
                            </p>
                            @endif
                        </form>

                        <div class="mt-4 border-top pt-3">
                            <h6 class="text-muted mb-0">Today: <span class="text-dark">{{ date('l, d M Y') }}</span>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endsection

