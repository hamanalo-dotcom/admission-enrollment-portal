@if(isset($isPublic) && $isPublic)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Admission Form - San Roque High School</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            background: #f4f7fb;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #0f1c2e;
            margin: 0;
            padding: 0;
        }

        /* ── PUBLIC HEADER ── */
        .pub-header {
            background: #ffffff;
            border-bottom: 1px solid rgba(0,0,0,0.08);
            padding: 0 2rem;
            height: 62px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 10;
        }
        .pub-logo {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }
        .pub-logo-image {
            width: 34px;
            height: 34px;
            object-fit: contain;
            flex-shrink: 0;
        }
        .pub-logo-text { font-size: 14px; font-weight: 600; color: #0f1c2e; line-height: 1.2; }
        .pub-logo-sub { font-size: 11px; color: #5a6a7e; }
        .pub-back {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px; font-size: 13px; font-weight: 500;
            color: #5a6a7e; text-decoration: none;
            border: 1px solid rgba(0,0,0,0.12);
            border-radius: 8px;
            transition: background 0.15s, color 0.15s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .pub-back:hover { background: #f4f7fb; color: #0f1c2e; }

        @media (max-width: 768px) {
            .pub-header { padding: 0 1rem; }
        }

        @media (max-width: 576px) {
            .pub-header {
                height: auto;
                min-height: 62px;
                padding: 10px 12px;
                gap: 10px;
                align-items: flex-start;
            }

            .pub-logo {
                flex: 1;
                min-width: 0;
            }

            .pub-logo > div:last-child {
                min-width: 0;
            }

            .pub-logo-text,
            .pub-logo-sub {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .pub-logo-text { font-size: 13px; }
            .pub-logo-sub { font-size: 10px; }

            .pub-back {
                padding: 6px 10px;
                font-size: 12px;
                white-space: nowrap;
            }
        }

        @media (max-width: 420px) {
            .pub-header {
                padding: 8px 10px;
                gap: 8px;
            }

            .pub-logo-image {
                width: 30px;
                height: 30px;
            }

            .pub-logo-sub { display: none; }

            .pub-back {
                padding: 6px 8px;
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <!-- Public Header -->
    <header class="pub-header">
        <a href="{{ route('home') }}" class="pub-logo">
            <img src="{{ asset('img/logo.png') }}" alt="San Roque High School logo" class="pub-logo-image">
            <div>
                <div class="pub-logo-text">San Roque High School</div>
                <div class="pub-logo-sub">Admission &amp; Enrollment Portal</div>
            </div>
        </a>
        <a href="{{ route('home') }}" class="pub-back">
            <i class="fas fa-arrow-left" style="font-size:11px;"></i> Back to Home
        </a>
    </header>
@else
@extends('layout.app')
@section('content')
@endif

<style>
    :root {
        --blue: #1a56b0;
        --blue-dark: #143f87;
        --blue-light: #e8f0fc;
        --text-primary: #0f1c2e;
        --text-secondary: #5a6a7e;
        --border: rgba(0,0,0,0.08);
        --border-med: rgba(0,0,0,0.12);
        --bg-page: #f4f7fb;
        --bg-white: #ffffff;
        --radius-md: 8px;
        --radius-lg: 12px;
        --radius-xl: 16px;
        --green: #16a34a;
        --green-light: #dcfce7;
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── PAGE WRAPPER ── */
    .adm-wrapper {
        max-width: 900px;
        margin: 0 auto;
        padding: 32px 20px 48px;
    }

    @media (max-width: 576px) {
        .adm-wrapper { padding: 20px 12px 36px; }
    }

    @media (max-width: 420px) {
        .adm-wrapper { padding: 16px 10px 30px; }
    }

    @media (max-width: 360px) {
        .adm-wrapper { padding: 14px 8px 26px; }
    }

    /* ── PAGE HEADER ── */
    .adm-page-header {
        margin-bottom: 24px;
    }
    .adm-page-header .adm-label {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--blue-light); color: var(--blue);
        font-size: 11px; font-weight: 600; letter-spacing: 0.07em;
        text-transform: uppercase; padding: 4px 10px;
        border-radius: 999px; margin-bottom: 8px;
    }
    .adm-page-header h1 {
        font-size: 22px; font-weight: 700; color: var(--text-primary);
        margin-bottom: 4px;
    }
    .adm-page-header p {
        font-size: 13px; color: var(--text-secondary); margin: 0;
    }
    .adm-page-header-row {
        display: flex; align-items: flex-start;
        justify-content: space-between; gap: 12px; flex-wrap: wrap;
    }

    @media (max-width: 576px) {
        .adm-page-header { margin-bottom: 18px; }
        .adm-page-header-row {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }
        .adm-page-header h1 { font-size: 20px; }
        .adm-page-header p {
            font-size: 12px;
            line-height: 1.45;
        }
        .adm-page-header .pub-back {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 360px) {
        .adm-page-header h1 { font-size: 18px; }
    }

    /* ── WIZARD PROGRESS ── */
    .wizard-track {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 20px 28px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        position: relative;
    }

    .wizard-track-line {
        position: absolute;
        top: 50%; left: 0; right: 0;
        height: 1px;
        background: var(--border);
        z-index: 0;
        margin: 0 28px;
    }

    .wizard-step-item {
        flex: 1; display: flex; flex-direction: column;
        align-items: center; position: relative; z-index: 1;
        min-width: 0;
    }

    .ws-circle {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700;
        background: var(--bg-page);
        border: 2px solid var(--border-med);
        color: var(--text-secondary);
        transition: all 0.25s;
        margin-bottom: 8px;
    }

    .wizard-step-item.active .ws-circle {
        background: var(--blue);
        border-color: var(--blue);
        color: white;
        box-shadow: 0 0 0 4px rgba(26,86,176,0.15);
    }

    .wizard-step-item.completed .ws-circle {
        background: var(--green);
        border-color: var(--green);
        color: white;
    }

    .ws-label {
        font-size: 11px; font-weight: 600;
        color: var(--text-secondary); text-align: center;
        white-space: nowrap;
    }
    .wizard-step-item.active .ws-label { color: var(--blue); }
    .wizard-step-item.completed .ws-label { color: var(--green); }

    @media (max-width: 640px) {
        .wizard-track {
            padding: 14px;
            margin-bottom: 16px;
        }

        .wizard-track-line { margin: 0 14px; }

        .ws-circle {
            width: 32px;
            height: 32px;
            margin-bottom: 6px;
        }

        .ws-label {
            font-size: 10px;
            line-height: 1.25;
            white-space: normal;
        }
    }

    @media (max-width: 480px) {
        .ws-label { display: none; }
        .wizard-track { padding: 16px 20px; }
    }

    @media (max-width: 420px) {
        .wizard-track {
            padding: 12px 10px;
            margin-bottom: 14px;
        }

        .wizard-track-line { margin: 0 10px; }

        .ws-circle {
            width: 30px;
            height: 30px;
            font-size: 12px;
            margin-bottom: 0;
            border-width: 1.5px;
        }

        .wizard-step-item.active .ws-circle {
            box-shadow: 0 0 0 3px rgba(26,86,176,0.15);
        }
    }

    /* ── CARD ── */
    .adm-card {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        overflow: hidden;
    }

    .adm-card-section {
        padding: 28px 28px 0;
    }

    @media (max-width: 576px) {
        .adm-card-section { padding: 20px 16px 0; }
    }

    /* ── SECTION HEADERS ── */
    .form-section {
        margin-bottom: 28px;
    }

    .form-section-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 16px; padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }

    .form-section-title {
        display: flex; align-items: center; gap: 8px;
        font-size: 14px; font-weight: 700; color: var(--blue);
        margin: 0;
    }

    .form-section-title i {
        font-size: 13px;
        width: 28px; height: 28px;
        background: var(--blue-light);
        border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        color: var(--blue);
    }

    .form-section-note {
        font-size: 11px; color: var(--text-secondary);
    }

    /* ── FORM FIELDS ── */
    .adm-label {
        display: block;
        font-size: 12px; font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 5px;
        letter-spacing: 0.01em;
    }

    .adm-label .req { color: #e03131; margin-left: 2px; }

    .form-control, .form-select {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13px;
        padding: 8px 12px;
        border: 1px solid var(--border-med);
        border-radius: var(--radius-md);
        color: var(--text-primary);
        background: var(--bg-white);
        height: auto;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    @media (max-width: 576px) {
        .form-control, .form-select {
            min-height: 40px;
            font-size: 14px;
        }
    }

    @media (max-width: 360px) {
        .form-control, .form-select {
            font-size: 13px;
            padding: 8px 10px;
        }
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(26,86,176,0.12);
        outline: none;
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #e03131;
    }

    .form-control[readonly] {
        background: var(--bg-page);
        cursor: not-allowed;
        color: var(--text-secondary);
    }

    .field-hint {
        font-size: 11px; color: var(--text-secondary);
        margin-top: 4px; display: block;
    }

    /* ── STEP PANELS ── */
    .wizard-panel {
        display: none;
        padding: 28px;
    }

    @media (max-width: 576px) {
        .wizard-panel { padding: 20px 16px; }
    }

    .wizard-panel.active {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        height: auto !important;
        overflow: visible !important;
    }

    .wizard-panel-title {
        font-size: 16px; font-weight: 700; color: var(--text-primary);
        margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
    }

    .wizard-panel-title .step-badge {
        width: 24px; height: 24px; border-radius: 50%;
        background: var(--blue); color: white;
        font-size: 11px; font-weight: 700;
        display: inline-flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    @media (max-width: 576px) {
        .form-section {
            margin-bottom: 22px;
        }

        .form-section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
            margin-bottom: 12px;
            padding-bottom: 10px;
        }

        .wizard-panel-title {
            font-size: 15px;
            margin-bottom: 16px;
            line-height: 1.3;
        }
    }

    @media (max-width: 420px) {
        .adm-card { border-radius: 12px; }
        .wizard-panel { padding: 16px 12px; }
    }

    @media (max-width: 360px) {
        .wizard-panel { padding: 14px 10px; }
    }

    /* ── DOCUMENT UPLOAD ── */
    .doc-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }

    @media (max-width: 576px) {
        .doc-grid { grid-template-columns: 1fr; }
    }

    .doc-item {
        border: 1.5px dashed var(--border-med);
        border-radius: var(--radius-lg);
        padding: 16px;
        background: var(--bg-page);
        transition: border-color 0.2s, background 0.2s;
    }

    @media (max-width: 420px) {
        .doc-item { padding: 12px; }
        .doc-item-label {
            flex-wrap: wrap;
            gap: 4px;
        }
    }

    .doc-item.uploaded {
        border-color: var(--green);
        border-style: solid;
        background: #f0fdf4;
    }

    .doc-item-label {
        font-size: 12px; font-weight: 700; color: var(--text-primary);
        margin-bottom: 10px; display: flex; align-items: center; gap: 6px;
    }

    .doc-badge {
        font-size: 10px; font-weight: 600; padding: 2px 7px;
        border-radius: 999px; text-transform: uppercase;
    }

    .doc-badge.required { background: #fee2e2; color: #b91c1c; }
    .doc-badge.optional { background: var(--bg-page); color: var(--text-secondary); border: 1px solid var(--border-med); }

    .doc-item .form-control {
        font-size: 12px; padding: 6px 10px;
    }

    .doc-status {
        display: none;
        margin-top: 8px;
        font-size: 11px; font-weight: 600;
        align-items: center; gap: 5px;
    }

    .doc-status.show { display: flex; }
    .doc-status.uploaded { color: var(--green); }
    .doc-status.pending { color: #b45309; }

    .doc-existing {
        margin-bottom: 8px;
    }

    .doc-existing a {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 12px; font-weight: 500; color: var(--blue);
        text-decoration: none; padding: 4px 10px;
        border: 1px solid rgba(26,86,176,0.3);
        border-radius: 6px; background: var(--blue-light);
    }

    .doc-existing a:hover { background: #d0e3fc; }

    /* ── REVIEW SECTIONS ── */
    .review-block {
        background: var(--bg-page);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 20px;
        margin-bottom: 14px;
    }

    @media (max-width: 576px) {
        .review-block { padding: 14px; }
        .review-block-header {
            flex-wrap: wrap;
            gap: 8px;
            align-items: flex-start;
        }
    }

    @media (max-width: 420px) {
        .review-edit-btn {
            width: 100%;
            justify-content: center;
        }
    }

    .review-block-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 14px; padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
    }

    .review-block-title {
        font-size: 13px; font-weight: 700; color: var(--text-primary);
        display: flex; align-items: center; gap: 7px; margin: 0;
    }

    .review-block-title i {
        font-size: 11px; color: var(--blue);
    }

    .review-edit-btn {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 12px; font-weight: 500; color: var(--blue);
        text-decoration: none; padding: 4px 10px;
        border: 1px solid rgba(26,86,176,0.3);
        border-radius: 6px;
        background: var(--blue-light);
        cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: background 0.15s;
    }

    .review-edit-btn:hover { background: #d0e3fc; }

    .review-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }

    @media (max-width: 480px) {
        .review-grid { grid-template-columns: 1fr; }
    }

    .review-field { font-size: 13px; }
    .review-field strong { color: var(--text-secondary); font-weight: 500; font-size: 11px; display: block; margin-bottom: 2px; text-transform: uppercase; letter-spacing: 0.04em; }
    .review-field span { color: var(--text-primary); font-weight: 600; }

    /* ── WIZARD NAV ── */
    .wizard-nav {
        display: flex; align-items: center; justify-content: space-between;
        padding: 20px 28px;
        border-top: 1px solid var(--border);
        margin-top: 4px;
    }

    @media (max-width: 576px) {
        .wizard-nav {
            padding: 14px 12px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .wizard-nav > div:empty {
            display: none;
        }

        .btn-wiz-back,
        .btn-wiz-next,
        .btn-wiz-submit {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 360px) {
        .btn-wiz-back,
        .btn-wiz-next,
        .btn-wiz-submit {
            font-size: 12px;
            padding: 8px 12px;
        }
    }

    .btn-wiz-back {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px; font-size: 13px; font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-secondary);
        background: var(--bg-white);
        border: 1px solid var(--border-med);
        border-radius: var(--radius-md);
        cursor: pointer;
        text-decoration: none;
        transition: background 0.15s, color 0.15s;
    }

    .btn-wiz-back:hover { background: var(--bg-page); color: var(--text-primary); }

    .btn-wiz-next {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 22px; font-size: 13px; font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--blue); color: white;
        border: none; border-radius: var(--radius-md);
        cursor: pointer;
        transition: background 0.15s, transform 0.15s;
    }

    .btn-wiz-next:hover { background: var(--blue-dark); transform: translateY(-1px); }

    .btn-wiz-submit {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 22px; font-size: 13px; font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--green); color: white;
        border: none; border-radius: var(--radius-md);
        cursor: pointer;
        transition: background 0.15s, transform 0.15s;
    }

    .btn-wiz-submit:hover:not(:disabled) { background: #15803d; transform: translateY(-1px); }
    .btn-wiz-submit:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

    /* ── ALERT STYLES ── */
    .adm-alert {
        padding: 12px 16px;
        border-radius: var(--radius-md);
        font-size: 13px;
        margin-bottom: 20px;
        display: flex; gap: 10px; align-items: flex-start;
    }

    @media (max-width: 576px) {
        .adm-alert {
            padding: 10px 12px;
            font-size: 12px;
            margin-bottom: 16px;
        }
    }

    .adm-alert-info {
        background: var(--blue-light);
        border: 1px solid rgba(26,86,176,0.2);
        color: #1e3a8a;
    }

    .adm-alert-warning {
        background: #fffbeb;
        border: 1px solid rgba(180,144,9,0.25);
        color: #78350f;
    }

    .adm-alert-danger {
        background: #fef2f2;
        border: 1px solid rgba(220,38,38,0.2);
        color: #7f1d1d;
    }

    /* ── CONFIRM CHECKBOX ── */
    .confirm-block {
        background: var(--blue-light);
        border: 1px solid rgba(26,86,176,0.2);
        border-radius: var(--radius-lg);
        padding: 16px 20px;
        margin-bottom: 6px;
        display: flex; gap: 12px; align-items: flex-start;
    }

    .confirm-block input[type="checkbox"] {
        width: 16px; height: 16px; flex-shrink: 0;
        accent-color: var(--blue); margin-top: 2px;
    }

    .confirm-block label {
        font-size: 13px; font-weight: 500; color: #1e3a8a; cursor: pointer;
    }

    @media (max-width: 576px) {
        .confirm-block {
            padding: 12px 14px;
            gap: 10px;
        }

        .confirm-block label {
            font-size: 12px;
            line-height: 1.45;
        }
    }

    /* ── MISC ── */
    .invalid-feedback { font-size: 11px; }
    .alert { font-size: 13px; border-radius: var(--radius-md); }

    /* Step 3 visibility overrides */
    #step3.active,
    #step3.wizard-panel.active {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        height: auto !important;
        overflow: visible !important;
    }

    #step3.active .review-block,
    #step3.active #review_student_info,
    #step3.active #review_address_info,
    #step3.active #review_family_info,
    #step3.active #review_academic_info,
    #step3.active #review_documents {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        height: auto !important;
        overflow: visible !important;
    }
</style>

@php
    $editing = isset($admission);
    $pf = $profile ?? null;
@endphp

<div class="adm-wrapper">

    {{-- Page Header --}}
    <div class="adm-page-header">
        <div class="adm-page-header-row">
            <div>
                <div class="adm-label">
                    <i class="fas fa-file-signature" style="font-size:10px;"></i>
                    {{ $editing ? 'Edit Application' : 'Admission Form' }}
                </div>
                <h1>{{ $editing ? 'Edit Admission Application' : 'Apply for Admission' }}</h1>
                <p>Fields marked with <span style="color:#e03131;">*</span> are required. Please fill out all information accurately.</p>
            </div>
            <div>
                @if($editing)
                    <a href="{{ route('student.admissions.show', $admission->id) }}" class="pub-back">
                        <i class="fas fa-arrow-left" style="font-size:11px;"></i> Back to View
                    </a>
                @elseif(!isset($isPublic) || !$isPublic)
                    {{-- back button handled by layout --}}
                @endif
            </div>
        </div>
    </div>

    {{-- Wizard Progress --}}
    <div class="wizard-track">
        <div class="wizard-track-line"></div>
        <div class="wizard-step-item active" data-step="1">
            <div class="ws-circle">1</div>
            <div class="ws-label">Student Info</div>
        </div>
        <div class="wizard-step-item" data-step="2">
            <div class="ws-circle">2</div>
            <div class="ws-label">Upload Documents</div>
        </div>
        <div class="wizard-step-item" data-step="3">
            <div class="ws-circle">3</div>
            <div class="ws-label">Review &amp; Confirm</div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="adm-alert adm-alert-danger mb-3">
            <i class="fas fa-exclamation-circle" style="margin-top:2px;flex-shrink:0;"></i>
            <div>
                <strong>Please fix the following errors:</strong>
                <ul style="margin:6px 0 0; padding-left:16px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="adm-card">
        <form
            action="{{ $editing ? route('student.admissions.update', $admission->id) : (Auth::check() ? route('student.admissions.store') : route('admission.store.public')) }}"
            method="POST"
            enctype="multipart/form-data"
            id="admissionForm"
        >
            @csrf
            @if($editing) @method('PUT') @endif

            {{-- ═══════════════════════════════ STEP 1 ═══════════════════════════════ --}}
            <div class="wizard-panel active" id="step1">

                <div class="wizard-panel-title">
                    <span class="step-badge">1</span>
                    Student Information
                </div>

                {{-- Student Type --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <h5 class="form-section-title">
                            <i class="fas fa-id-badge"></i> Student Type
                        </h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="adm-label">Student Type <span class="req">*</span></label>
                            <select name="student_type" id="student_type" class="form-select" required>
                                @php $stype = old('student_type', $editing ? ($admission->student_type ?? '') : ''); @endphp
                                <option value="">-- Select --</option>
                                <option value="new" {{ $stype=='new' ? 'selected' : '' }}>New</option>
                                <option value="transferee" {{ $stype=='transferee' ? 'selected' : '' }}>Transferee</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Personal Information --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <h5 class="form-section-title">
                            <i class="fas fa-user-graduate"></i> Personal Information
                        </h5>
                        <span class="form-section-note d-none d-md-block">Name and birthdate must match official documents</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="adm-label">First Name <span class="req">*</span></label>
                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $editing ? ($admission->first_name ?? '') : ($pf->first_name ?? ($user->firstname ?? ''))) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="adm-label">Middle Name</label>
                            <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $editing ? ($admission->middle_name ?? '') : ($pf->middle_name ?? ($user->middlename ?? ''))) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="adm-label">Last Name <span class="req">*</span></label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $editing ? ($admission->last_name ?? '') : ($pf->last_name ?? ($user->lastname ?? ''))) }}" required>
                        </div>
                        <div class="col-md-1">
                            <label class="adm-label">Suffix</label>
                            <input type="text" name="suffix" class="form-control" maxlength="10">
                        </div>
                    </div>
                    <div class="row g-3 mt-0">
                        <div class="col-md-3">
                            <label class="adm-label">Date of Birth <span class="req">*</span></label>
                            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $editing ? ($admission->date_of_birth ?? '') : ($pf->date_of_birth ?? (isset($user->birthdate) ? $user->birthdate->format('Y-m-d') : ''))) }}" max="{{ date('Y-m-d', strtotime('-11 years')) }}" required>
                            <span class="field-hint">Must be at least 11 years old.</span>
                        </div>
                        <div class="col-md-5">
                            <label class="adm-label">Place of Birth <span class="req">*</span></label>
                            <input type="text" name="place_of_birth" class="form-control" value="{{ old('place_of_birth', $editing ? ($admission->place_of_birth ?? '') : ($pf->place_of_birth ?? '')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="adm-label">Gender <span class="req">*</span></label>
                            @php $g = old('gender', $editing ? ($admission->gender ?? '') : ($pf->gender ?? ucfirst($user->sex ?? ''))); @endphp
                            <select name="gender" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Male" {{ $g=='Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $g=='Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-0">
                        <div class="col-md-6">
                            <label class="adm-label">Nationality <span class="req">*</span></label>
                            <input type="text" name="nationality" class="form-control" value="{{ old('nationality', $editing ? ($admission->nationality ?? '') : ($pf->nationality ?? '')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="adm-label">Religion</label>
                            <input type="text" name="religion" class="form-control" value="{{ old('religion', $editing ? ($admission->religion ?? '') : ($pf->religion ?? '')) }}">
                        </div>
                    </div>
                </div>

                {{-- Address & Contact --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <h5 class="form-section-title">
                            <i class="fas fa-map-marker-alt"></i> Address &amp; Contact
                        </h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="adm-label">Region <span class="req">*</span></label>
                            <select name="region_code" id="region" class="form-select" required>
                                <option value="">-- Select Region --</option>
                                @foreach ($regions as $region)
                                    <option value="{{ is_array($region) ? ($region['code'] ?? '') : ($region->regCode ?? '') }}">
                                        {{ is_array($region) ? ($region['desc'] ?? '') : ($region->regDesc ?? '') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="adm-label">Province</label>
                            <select name="province_code" id="province" class="form-select">
                                <option value="">-- Select Province --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="adm-label">City / Municipality</label>
                            <select name="city_code" id="city" class="form-select">
                                <option value="">-- Select City/Municipality --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="adm-label">Barangay</label>
                            <select name="barangay_code" id="barangay" class="form-select">
                                <option value="">-- Select Barangay --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-0">
                        <div class="col-md-5">
                            <label class="adm-label">Street / House No.</label>
                            <input type="text" name="street" class="form-control" value="{{ old('street', $editing ? ($admission->street ?? '') : ($pf->street ?? ($user->address ?? ''))) }}" placeholder="Enter street address">
                        </div>
                        <div class="col-md-3">
                            <label class="adm-label">Contact No. <span class="req">*</span></label>
                            <input type="text" name="contact_no" class="form-control" value="{{ old('contact_no', $editing ? ($admission->contact_no ?? '') : ($pf->contact_no ?? ($user->contact_no ?? ''))) }}" placeholder="e.g. 09XXXXXXXXX" required>
                        </div>
                        <div class="col-md-4">
                            <label class="adm-label">Email <span class="req">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $editing ? ($admission->email ?? ($user->email ?? '')) : ($user->email ?? '')) }}" {{ $user ? 'readonly' : '' }} required>
                            <div class="invalid-feedback @error('email') d-block @enderror" id="email_error">@error('email'){{ $message }}@enderror</div>
                            @if($user)
                                <span class="field-hint">Must match your account email.</span>
                            @else
                                <span class="field-hint">You will use this to register after approval.</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Family Information --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <h5 class="form-section-title">
                            <i class="fas fa-users"></i> Family Information
                        </h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="adm-label">Father's Name <span class="req">*</span></label>
                            <input type="text" name="father_name" class="form-control" value="{{ old('father_name', $editing ? ($admission->father_name ?? '') : ($pf->father_name ?? '')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="adm-label">Occupation</label>
                            <input type="text" name="father_occupation" class="form-control" value="{{ old('father_occupation', $editing ? ($admission->father_occupation ?? '') : ($pf->father_occupation ?? '')) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="adm-label">Contact No.</label>
                            <input type="text" name="father_contact" class="form-control" value="{{ old('father_contact', $editing ? ($admission->father_contact ?? '') : ($pf->father_contact ?? '')) }}">
                        </div>
                    </div>
                    <div class="row g-3 mt-0">
                        <div class="col-md-4">
                            <label class="adm-label">Mother's Name <span class="req">*</span></label>
                            <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name', $editing ? ($admission->mother_name ?? '') : ($pf->mother_name ?? '')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="adm-label">Occupation</label>
                            <input type="text" name="mother_occupation" class="form-control" value="{{ old('mother_occupation', $editing ? ($admission->mother_occupation ?? '') : ($pf->mother_occupation ?? '')) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="adm-label">Contact No.</label>
                            <input type="text" name="mother_contact" class="form-control" value="{{ old('mother_contact', $editing ? ($admission->mother_contact ?? '') : ($pf->mother_contact ?? '')) }}">
                        </div>
                    </div>
                    <div class="row g-3 mt-0">
                        <div class="col-md-6">
                            <label class="adm-label">Guardian's Name</label>
                            <input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name', $editing ? ($admission->guardian_name ?? '') : ($pf->guardian_name ?? '')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="adm-label">Guardian's Contact</label>
                            <input type="text" name="guardian_contact" class="form-control" value="{{ old('guardian_contact', $editing ? ($admission->guardian_contact ?? '') : ($pf->guardian_contact ?? '')) }}">
                        </div>
                    </div>
                </div>

                {{-- Academic Background --}}
                <div class="form-section">
                    <div class="form-section-header">
                        <h5 class="form-section-title">
                            <i class="fas fa-graduation-cap"></i> Academic Background
                        </h5>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="adm-label">
                                Year Level <span class="req">*</span>
                                <span id="year_level_note" style="font-weight:400; font-size:10px; color:var(--text-secondary);">(Grade 7 &amp; 11 only)</span>
                            </label>
                            @php $yl = old('year_level', $editing ? ($admission->year_level ?? '') : ($pf->year_level ?? '')); @endphp
                            <select name="year_level" id="year_level" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Grade 7" {{ $yl=='Grade 7' ? 'selected' : '' }}>Grade 7</option>
                                <option value="Grade 8" {{ $yl=='Grade 8' ? 'selected' : '' }}>Grade 8</option>
                                <option value="Grade 9" {{ $yl=='Grade 9' ? 'selected' : '' }}>Grade 9</option>
                                <option value="Grade 10" {{ $yl=='Grade 10' ? 'selected' : '' }}>Grade 10</option>
                                <option value="Grade 11" {{ $yl=='Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                <option value="Grade 12" {{ $yl=='Grade 12' ? 'selected' : '' }}>Grade 12</option>
                            </select>
                            @if(!$editing)
                                <span class="field-hint" id="year_level_info">Grade 8–10 &amp; 12 should go to Enrollment directly.</span>
                            @endif
                        </div>

                        @php
                            $isShs = false;
                            if ($editing && isset($admission)) {
                                $isShs = in_array($admission->year_level ?? '', ['Grade 11', 'Grade 12']);
                            } elseif (isset($yl)) {
                                $isShs = in_array($yl, ['Grade 11', 'Grade 12']);
                            }
                            $st = old('strand', $editing && isset($admission) ? ($admission->strand ?? '') : ($pf->strand ?? ''));
                        @endphp
                        <div class="col-md-4" id="strand_container" style="display: {{ $isShs ? 'block' : 'none' }};">
                            <label class="adm-label">Strand <span id="strand_asterisk" style="display:{{ $isShs ? 'inline' : 'none' }};" class="req">*</span> <span style="font-weight:400;font-size:10px;color:var(--text-secondary);">(SHS only)</span></label>
                            <select name="strand" id="strand" class="form-select" {{ $isShs ? 'required' : '' }}>
                                <option value="">-- Select --</option>
                                <option value="GAS" {{ $st=='GAS' ? 'selected' : '' }}>GAS</option>
                                <option value="TVL" {{ $st=='TVL' ? 'selected' : '' }}>TVL</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="tvl_branch_container" style="display:none;">
                            <label class="adm-label">TVL Branch <span id="tvl_branch_asterisk" style="display:none;" class="req">*</span></label>
                            @php $tb = old('tvl_branch', $editing && isset($admission) ? ($admission->tvl_branch ?? '') : ($pf->tvl_branch ?? '')); @endphp
                            <select name="tvl_branch" id="tvl_branch" class="form-select">
                                <option value="">-- Select --</option>
                                <option value="CSS" {{ $tb=='CSS' ? 'selected' : '' }}>CSS</option>
                                <option value="EIM" {{ $tb=='EIM' ? 'selected' : '' }}>EIM</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="adm-label">LRN <span class="req">*</span></label>
                            <input type="text" name="lrn" class="form-control" value="{{ old('lrn', $editing ? ($admission->lrn ?? '') : ($pf->lrn ?? '')) }}" required>
                        </div>
                    </div>
                    <div class="row g-3 mt-0">
                        <div class="col-md-5">
                            <label class="adm-label">Previous School <span class="req">*</span></label>
                            <input type="text" name="previous_school" class="form-control" value="{{ old('previous_school', $editing ? ($admission->previous_school ?? '') : ($pf->previous_school ?? '')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="adm-label">Previous School Address</label>
                            <input type="text" name="previous_school_address" class="form-control" value="{{ old('previous_school_address', $editing ? ($admission->previous_school_address ?? '') : ($pf->previous_school_address ?? '')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="adm-label">GWA</label>
                            <input type="number" name="gwa" class="form-control" step="0.01" min="60" max="100" value="{{ old('gwa', $editing ? ($admission->gwa ?? '') : '') }}" placeholder="e.g. 89.75">
                            <span class="field-hint">Used for sectioning (60–100).</span>
                        </div>
                    </div>
                </div>

                <div class="wizard-nav">
                    <div></div>
                    <button type="button" class="btn-wiz-next" onclick="nextStep(2)">
                        Next: Upload Documents <i class="fas fa-arrow-right" style="font-size:11px;"></i>
                    </button>
                </div>
            </div>

            {{-- ═══════════════════════════════ STEP 2 ═══════════════════════════════ --}}
            <div class="wizard-panel" id="step2">

                <div class="wizard-panel-title">
                    <span class="step-badge">2</span>
                    Upload Documents
                </div>

                <div class="adm-alert adm-alert-info">
                    <i class="fas fa-info-circle" style="flex-shrink:0;margin-top:1px;"></i>
                    <div>Upload all required documents. Accepted formats: <strong>PDF, JPG, JPEG, PNG</strong> — max <strong>2MB</strong> per file.</div>
                </div>

                @php
                    $optionalFiles = ['medical_certificate', 'id_picture'];
                    $stype = old('student_type', $editing ? ($admission->student_type ?? '') : '');
                    $isNewOrTransferee = in_array(strtolower($stype), ['new', 'transferee']);
                    $requiredFields = $isNewOrTransferee ? ['form_137', 'report_card', 'birth_certificate'] : [];
                @endphp

                <div class="doc-grid">
                    @foreach ([['form_137','Form 137'],['report_card','Report Card'],['birth_certificate','Birth Certificate'],['good_moral','Good Moral'],['medical_certificate','Medical Certificate'],['id_picture','ID Picture']] as [$field,$label])
                        @php
                            $fileExists = $editing && $admission->$field && \Storage::disk('public')->exists($admission->$field);
                            $isOptional = in_array($field, $optionalFiles);
                            $isRequired = !$isOptional && in_array($field, $requiredFields) && !$fileExists;
                        @endphp
                        <div class="doc-item {{ $fileExists ? 'uploaded' : '' }}" id="doc_{{ $field }}">
                            <div class="doc-item-label">
                                {{ $label }}
                                @if($isOptional)
                                    <span class="doc-badge optional">Optional</span>
                                @elseif($isRequired)
                                    <span class="doc-badge required">Required</span>
                                @endif
                            </div>
                            @if($editing && $fileExists)
                                <div class="doc-existing">
                                    <a href="{{ Storage::url($admission->$field) }}" target="_blank" download>
                                        <i class="fas fa-download" style="font-size:11px;"></i> View Current File
                                    </a>
                                </div>
                            @endif
                            <input type="file"
                                   name="{{ $field }}"
                                   id="{{ $field }}"
                                   class="form-control @error($field) is-invalid @enderror"
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   data-doc-name="{{ $label }}"
                                   onchange="handleFileUpload(this, '{{ $field }}')"
                                   @if($editing && $isRequired && !$isOptional) required @endif>

                            <div class="doc-status uploaded {{ $fileExists ? 'show' : '' }}" id="status_{{ $field }}">
                                <i class="fas fa-check-circle"></i> Uploaded
                            </div>
                            <div class="doc-status pending" id="status_pending_{{ $field }}">
                                <i class="fas fa-clock"></i> Ready to upload
                            </div>
                            <div id="file_name_{{ $field }}" style="display:none; margin-top:6px;">
                                <span style="font-size:11px; color:var(--green);"><i class="fas fa-file"></i> <span id="file_name_text_{{ $field }}"></span></span>
                            </div>
                            @error($field)
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <span class="field-hint">PDF, JPG, JPEG, PNG — max 2MB</span>
                        </div>
                    @endforeach
                </div>

                <div class="wizard-nav">
                    <button type="button" class="btn-wiz-back" onclick="prevStep(1)">
                        <i class="fas fa-arrow-left" style="font-size:11px;"></i> Back
                    </button>
                    <button type="button" class="btn-wiz-next" onclick="nextStep(3)">
                        Next: Review &amp; Confirm <i class="fas fa-arrow-right" style="font-size:11px;"></i>
                    </button>
                </div>
            </div>

            {{-- ═══════════════════════════════ STEP 3 ═══════════════════════════════ --}}
            <div class="wizard-panel" id="step3">

                <div class="wizard-panel-title">
                    <span class="step-badge" style="background:var(--green);">3</span>
                    Review &amp; Confirm
                </div>

                <div class="adm-alert adm-alert-warning">
                    <i class="fas fa-exclamation-triangle" style="flex-shrink:0;margin-top:1px;"></i>
                    <div>Please review all information carefully before submitting. Click <strong>Edit</strong> on any section to make changes.</div>
                </div>

                {{-- Student Info Review --}}
                <div class="review-block">
                    <div class="review-block-header">
                        <h5 class="review-block-title"><i class="fas fa-user"></i> Student Information</h5>
                        <button type="button" class="review-edit-btn" onclick="goToStep(1)"><i class="fas fa-pen" style="font-size:10px;"></i> Edit</button>
                    </div>
                    <div class="review-grid" id="review_student_info">
                        <div style="color:var(--text-secondary);font-size:13px;grid-column:span 2;text-align:center;padding:12px 0;">
                            <i class="fas fa-spinner fa-spin"></i> Loading...
                        </div>
                    </div>
                </div>

                {{-- Address Review --}}
                <div class="review-block">
                    <div class="review-block-header">
                        <h5 class="review-block-title"><i class="fas fa-map-marker-alt"></i> Address &amp; Contact</h5>
                        <button type="button" class="review-edit-btn" onclick="goToStep(1)"><i class="fas fa-pen" style="font-size:10px;"></i> Edit</button>
                    </div>
                    <div class="review-grid" id="review_address_info">
                        <div style="color:var(--text-secondary);font-size:13px;grid-column:span 2;text-align:center;padding:12px 0;">
                            <i class="fas fa-spinner fa-spin"></i> Loading...
                        </div>
                    </div>
                </div>

                {{-- Family Review --}}
                <div class="review-block">
                    <div class="review-block-header">
                        <h5 class="review-block-title"><i class="fas fa-users"></i> Parent / Guardian Details</h5>
                        <button type="button" class="review-edit-btn" onclick="goToStep(1)"><i class="fas fa-pen" style="font-size:10px;"></i> Edit</button>
                    </div>
                    <div class="review-grid" id="review_family_info">
                        <div style="color:var(--text-secondary);font-size:13px;grid-column:span 2;text-align:center;padding:12px 0;">
                            <i class="fas fa-spinner fa-spin"></i> Loading...
                        </div>
                    </div>
                </div>

                {{-- Academic Review --}}
                <div class="review-block">
                    <div class="review-block-header">
                        <h5 class="review-block-title"><i class="fas fa-graduation-cap"></i> Academic Background</h5>
                        <button type="button" class="review-edit-btn" onclick="goToStep(1)"><i class="fas fa-pen" style="font-size:10px;"></i> Edit</button>
                    </div>
                    <div class="review-grid" id="review_academic_info">
                        <div style="color:var(--text-secondary);font-size:13px;grid-column:span 2;text-align:center;padding:12px 0;">
                            <i class="fas fa-spinner fa-spin"></i> Loading...
                        </div>
                    </div>
                </div>

                {{-- Documents Review --}}
                <div class="review-block">
                    <div class="review-block-header">
                        <h5 class="review-block-title"><i class="fas fa-folder-open"></i> Uploaded Documents</h5>
                        <button type="button" class="review-edit-btn" onclick="goToStep(2)"><i class="fas fa-pen" style="font-size:10px;"></i> Edit</button>
                    </div>
                    <div class="review-grid" id="review_documents">
                        <div style="color:var(--text-secondary);font-size:13px;grid-column:span 2;text-align:center;padding:12px 0;">
                            <i class="fas fa-spinner fa-spin"></i> Loading...
                        </div>
                    </div>
                </div>

               {{-- Confirmation and Data Privacy Consent --}}
<div class="confirmation-section">

    {{-- Information Confirmation --}}
    <div class="confirm-block mb-3">
        <input
            type="checkbox"
            id="confirmCheckbox"
            name="information_confirmation"
            value="1"
            onchange="toggleSubmitButton()"
        >

        <label for="confirmCheckbox">
            I confirm that all information provided above is
            <strong>true, accurate, and complete</strong>.
            I understand that any false information may result in the
            rejection or cancellation of my application.
        </label>
    </div>

    {{-- Data Privacy Act Consent --}}
    <div class="confirm-block privacy-block">
        <input
            type="checkbox"
            id="privacyCheckbox"
            name="data_privacy_consent"
            value="1"
            onchange="toggleSubmitButton()"
        >

        <label for="privacyCheckbox">
            I have read and understood the
            <strong>Data Privacy Notice</strong> and hereby give my
            consent to San Roque High School to collect, record, store,
            process, and use my personal information and submitted
            documents for purposes related to admission, enrollment,
            student records, and other legitimate school-related
            activities, in accordance with the
            <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong>
            and applicable regulations.
        </label>
    </div>

</div>

                <div class="wizard-nav">
                    <button type="button" class="btn-wiz-back" onclick="prevStep(2)">
                        <i class="fas fa-arrow-left" style="font-size:11px;"></i> Back
                    </button>
                    <button type="submit" name="submit_admission" value="1" class="btn-wiz-submit" id="submitApplicationBtn" disabled>
                        <i class="fas fa-check" style="font-size:11px;"></i> Submit Application
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

@if(isset($isPublic) && $isPublic)
</body>
</html>
@else
@endsection
@endif

<script>
    let currentStep = 1;
    const totalSteps = 3;

    function goToStep(step) {
        if (step < 1 || step > totalSteps) return;

        document.querySelectorAll('.wizard-panel').forEach(panel => {
            const s = panel.id.replace('step', '');
            if (s !== String(step)) {
                panel.classList.remove('active');
                panel.style.display = 'none';
            }
        });

        const target = document.getElementById('step' + step);
        if (target) {
            target.classList.add('active');
            target.style.display = 'block';
            target.style.visibility = 'visible';
            target.style.opacity = '1';
            target.style.height = 'auto';
            target.style.overflow = 'visible';
            void target.offsetHeight;
        }

        document.querySelectorAll('.wizard-step-item').forEach((el, i) => {
            el.classList.remove('active', 'completed');
            const num = el.querySelector('.ws-circle');
            if (i + 1 < step) {
                el.classList.add('completed');
                if (num) num.innerHTML = '<i class="fas fa-check" style="font-size:12px;"></i>';
            } else if (i + 1 === step) {
                el.classList.add('active');
                if (num) num.textContent = i + 1;
            } else {
                if (num) num.textContent = i + 1;
            }
        });

        currentStep = step;

        if (step === 3) {
            requestAnimationFrame(() => {
                setTimeout(() => {
                    if (typeof populateReview === 'function') populateReview();
                }, 150);
            });
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function nextStep(step) {
        if (currentStep === 1 && !validateStep1()) return;
        if (currentStep === 2 && !validateStep2()) return;
        goToStep(step);
    }

    function prevStep(step) { goToStep(step); }

    const EMAIL_REGEX = /^[^\s@]+@(?:[^\s@.]+\.)+[^\s@.]{2,}$/;

    function validateEmailField() {
        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('email_error');
        if (!emailInput) return { valid: true, reason: null, message: '' };

        const value = emailInput.value.trim();
        let reason = null;
        let message = '';

        if (!value) {
            reason = 'empty';
            message = 'Email address is required.';
        } else if (emailInput.validity?.typeMismatch || !EMAIL_REGEX.test(value)) {
            reason = 'format';
            message = 'Please enter a valid email address (e.g., student@gmail.com).';
        }

        if (reason) {
            emailInput.classList.add('is-invalid');
            if (emailError) {
                emailError.textContent = message;
                emailError.classList.add('d-block');
            }
            return { valid: false, reason, message };
        }

        emailInput.classList.remove('is-invalid');
        if (emailError) {
            emailError.textContent = '';
            emailError.classList.remove('d-block');
        }
        return { valid: true, reason: null, message: '' };
    }

    function validateStep1() {
        const required = document.querySelectorAll('#step1 [required]');
        let hasMissing = false;
        required.forEach(f => {
            if (f.id === 'email') return;
            if (!f.value.trim()) { f.classList.add('is-invalid'); hasMissing = true; }
            else f.classList.remove('is-invalid');
        });

        const emailResult = validateEmailField();
        if (!emailResult.valid && emailResult.reason === 'empty') {
            hasMissing = true;
        }

        if (hasMissing || !emailResult.valid) {
            let alertText = 'Please fill in all required fields in Step 1.';
            if (!emailResult.valid && emailResult.reason === 'format') {
                alertText = hasMissing
                    ? 'Please fill in all required fields and enter a valid email address.'
                    : emailResult.message;
            }
            Swal.fire({ icon: 'error', title: 'Validation Error', text: alertText });
            if (!hasMissing && !emailResult.valid) {
                document.getElementById('email')?.focus();
            }
            return false;
        }
        return true;
    }

    function validateStep2() {
        const studentType = document.getElementById('student_type')?.value;
        let requiredDocs = [];
        if (['new', 'transferee'].includes(studentType?.toLowerCase())) {
            requiredDocs = ['form_137', 'report_card', 'birth_certificate'];
        }
        let allOk = true;
        const missing = [];
        requiredDocs.forEach(f => {
            const input = document.getElementById(f);
            const uploaded = input?.files.length > 0 || document.getElementById('status_' + f)?.classList.contains('show');
            if (!uploaded) { allOk = false; missing.push(input?.getAttribute('data-doc-name') || f); }
        });
        if (!allOk) {
            Swal.fire({ icon: 'error', title: 'Missing Documents', text: 'Please upload: ' + missing.join(', ') });
        }
        return allOk;
    }

    function handleFileUpload(input, fieldName) {
        const file = input.files[0];
        if (!file) return;
        const allowed = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        if (!allowed.includes(file.type)) {
            Swal.fire({ icon: 'error', title: 'Invalid File Type', text: 'Please upload PDF, JPG, JPEG, or PNG.' });
            input.value = ''; return;
        }
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({ icon: 'error', title: 'File Too Large', text: 'Max file size is 2MB.' });
            input.value = ''; return;
        }
        const item = document.getElementById('doc_' + fieldName);
        const nameDisplay = document.getElementById('file_name_' + fieldName);
        const nameText = document.getElementById('file_name_text_' + fieldName);
        const statusEl = document.getElementById('status_' + fieldName);
        if (item) item.classList.add('uploaded');
        if (nameText) nameText.textContent = file.name;
        if (nameDisplay) nameDisplay.style.display = 'block';
        if (statusEl) statusEl.classList.add('show');
    }

    function toggleSubmitButton() {
        const cb = document.getElementById('confirmCheckbox');
        const btn = document.getElementById('submitApplicationBtn');
        if (cb && btn) btn.disabled = !cb.checked;
    }

    function ensureVisible(el) {
        if (!el) return;
        let cur = el;
        while (cur && cur !== document.body) {
            if (window.getComputedStyle(cur).display === 'none') cur.style.display = 'block';
            cur.style.visibility = 'visible';
            cur.style.opacity = '1';
            if (cur.style.height === '0' || cur.style.height === '0px') cur.style.height = 'auto';
            cur = cur.parentElement;
        }
    }

    function buildReviewField(label, value) {
        if (!value || !value.trim() || value.includes('-- Select')) return '';
        return `<div class="review-field"><strong>${label}</strong><span>${value}</span></div>`;
    }

    function populateReview() {
        const step3 = document.getElementById('step3');
        if (step3) {
            step3.classList.add('active');
            step3.style.display = 'block';
            step3.style.visibility = 'visible';
            step3.style.opacity = '1';
            step3.style.height = 'auto';
            step3.style.overflow = 'visible';
            ensureVisible(step3);
        }

        function getVal(name) {
            const f = document.querySelector(`[name="${name}"]`);
            if (!f) return '';
            if (f.tagName === 'SELECT') {
                const opt = f.options[f.selectedIndex];
                if (!opt || !opt.value) return '';
                const txt = (opt.text || '').replace(/^--\s*Select.*--\s*$/i, '').trim();
                return txt.startsWith('--') ? '' : txt;
            }
            return f.value || '';
        }

        // Student info
        const studentFields = [
            ['Student Type', getVal('student_type')],
            ['First Name', getVal('first_name')],
            ['Middle Name', getVal('middle_name')],
            ['Last Name', getVal('last_name')],
            ['Date of Birth', getVal('date_of_birth')],
            ['Place of Birth', getVal('place_of_birth')],
            ['Gender', getVal('gender')],
            ['Nationality', getVal('nationality')],
            ['Religion', getVal('religion')],
        ];
        const siEl = document.getElementById('review_student_info');
        if (siEl) {
            const html = studentFields.map(([l,v]) => buildReviewField(l,v)).join('');
            siEl.innerHTML = html || '<div style="color:var(--text-secondary);font-size:13px;grid-column:span 2;">No information provided.</div>';
            ensureVisible(siEl);
        }

        // Address info
        const addrFields = [
            ['Region', getVal('region_code')],
            ['Province', getVal('province_code')],
            ['City/Municipality', getVal('city_code')],
            ['Barangay', getVal('barangay_code')],
            ['Street', getVal('street')],
            ['Contact No.', getVal('contact_no')],
            ['Email', getVal('email')],
        ];
        const aiEl = document.getElementById('review_address_info');
        if (aiEl) {
            const html = addrFields.map(([l,v]) => buildReviewField(l,v)).join('');
            aiEl.innerHTML = html || '<div style="color:var(--text-secondary);font-size:13px;grid-column:span 2;">No address provided.</div>';
            ensureVisible(aiEl);
        }

        // Family info
        const famFields = [
            ["Father's Name", getVal('father_name')],
            ["Father's Occupation", getVal('father_occupation')],
            ["Father's Contact", getVal('father_contact')],
            ["Mother's Name", getVal('mother_name')],
            ["Mother's Occupation", getVal('mother_occupation')],
            ["Mother's Contact", getVal('mother_contact')],
            ["Guardian's Name", getVal('guardian_name')],
            ["Guardian's Contact", getVal('guardian_contact')],
        ];
        const fiEl = document.getElementById('review_family_info');
        if (fiEl) {
            const html = famFields.map(([l,v]) => buildReviewField(l,v)).join('');
            fiEl.innerHTML = html || '<div style="color:var(--text-secondary);font-size:13px;grid-column:span 2;">No family info provided.</div>';
            ensureVisible(fiEl);
        }

        // Academic info
        const acadFields = [
            ['Year Level', getVal('year_level')],
            ['Strand', getVal('strand')],
            ['TVL Branch', getVal('tvl_branch')],
            ['LRN', getVal('lrn')],
            ['Previous School', getVal('previous_school')],
            ['Previous School Address', getVal('previous_school_address')],
            ['GWA', getVal('gwa')],
        ];
        const acadEl = document.getElementById('review_academic_info');
        if (acadEl) {
            const html = acadFields.map(([l,v]) => buildReviewField(l,v)).join('');
            acadEl.innerHTML = html || '<div style="color:var(--text-secondary);font-size:13px;grid-column:span 2;">No academic info provided.</div>';
            ensureVisible(acadEl);
        }

        // Documents
        const docs = [
            ['form_137','Form 137'],['report_card','Report Card'],
            ['birth_certificate','Birth Certificate'],['good_moral','Good Moral'],
            ['medical_certificate','Medical Certificate'],['id_picture','ID Picture']
        ];
        const docEl = document.getElementById('review_documents');
        if (docEl) {
            let html = '';
            docs.forEach(([field, label]) => {
                const input = document.querySelector(`[name="${field}"]`);
                const file = input?.files?.[0];
                const statusEl = document.getElementById('status_' + field);
                const isUploaded = file || statusEl?.classList.contains('show');
                const fileName = file ? file.name : (isUploaded ? 'Previously uploaded' : null);
                if (fileName) {
                    html += `<div class="review-field"><strong>${label}</strong><span style="color:var(--green);"><i class="fas fa-check-circle" style="font-size:11px;"></i> ${fileName}</span></div>`;
                } else {
                    html += `<div class="review-field"><strong>${label}</strong><span style="color:var(--text-secondary);">Not uploaded</span></div>`;
                }
            });
            docEl.innerHTML = html;
            ensureVisible(docEl);
        }
    }

    window.goToStep = goToStep;
    window.nextStep = nextStep;
    window.prevStep = prevStep;
    window.populateReview = populateReview;
    window.toggleSubmitButton = toggleSubmitButton;
    window.handleFileUpload = handleFileUpload;

    document.addEventListener('DOMContentLoaded', function () {
        const yearLevel = document.getElementById('year_level');
        const strandContainer = document.getElementById('strand_container');
        const studentType = document.getElementById('student_type');
        const regionSelect = document.getElementById('region');
        const provinceSelect = document.getElementById('province');
        const citySelect = document.getElementById('city');
        const barangaySelect = document.getElementById('barangay');

        let PROVINCES_ALL = [], CITIES_ALL = [], BARANGAYS_ALL = [];

        const oldRegion   = @json(old('region_code', $editing ? ($admission->region_code ?? null) : ($pf->region_code ?? null)));
        const oldProvince = @json(old('province_code', $editing ? ($admission->province_code ?? null) : ($pf->province_code ?? null)));
        const oldCity     = @json(old('city_code', $editing ? ($admission->city_code ?? null) : ($pf->city_code ?? null)));
        const oldBarangay = @json(old('barangay_code', $editing ? ($admission->barangay_code ?? null) : ($pf->barangay_code ?? null)));
        const isEditing = {{ $editing ? 'true' : 'false' }};

        provinceSelect.disabled = true;
        citySelect.disabled = true;
        barangaySelect.disabled = true;

        function triggerCascade() {
            if (!oldRegion) return;
            const regCode = oldRegion;
            provinceSelect.innerHTML = '<option value="">Loading...</option>';
            citySelect.innerHTML = '<option value="">-- Select City/Municipality --</option>';
            barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';
            provinceSelect.disabled = true; citySelect.disabled = true; barangaySelect.disabled = true;

            const cached = PROVINCES_ALL.filter(p => String(p.region_code) === String(regCode));

            function loadProvinces(list) {
                provinceSelect.innerHTML = '<option value="">-- Select Province --</option>';
                list.forEach(p => { const o = document.createElement('option'); o.value = p.code || p.provCode; o.textContent = p.name || p.provDesc; provinceSelect.appendChild(o); });
                provinceSelect.disabled = false;
                setTimeout(() => {
                    if (oldProvince && Array.from(provinceSelect.options).some(o => o.value === String(oldProvince))) {
                        provinceSelect.value = oldProvince;
                        const provCode = oldProvince;
                        citySelect.innerHTML = '<option value="">Loading...</option>'; barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';
                        citySelect.disabled = true; barangaySelect.disabled = true;
                        const cachedC = CITIES_ALL.filter(c => String(c.province_code) === String(provCode));
                        function loadCities(cl) {
                            citySelect.innerHTML = '<option value="">-- Select City/Municipality --</option>';
                            cl.forEach(c => { const o = document.createElement('option'); o.value = c.code || c.citymunCode || c.psgcCode; o.textContent = c.name || c.citymunDesc; citySelect.appendChild(o); });
                            citySelect.disabled = false;
                            setTimeout(() => {
                                if (oldCity && Array.from(citySelect.options).some(o => o.value === String(oldCity))) {
                                    citySelect.value = oldCity;
                                    const citymunCode = oldCity;
                                    barangaySelect.innerHTML = '<option value="">Loading...</option>'; barangaySelect.disabled = true;
                                    const cachedB = BARANGAYS_ALL.filter(b => String(b.city_code) === String(citymunCode));
                                    function loadBarangays(bl) {
                                        barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';
                                        bl.forEach(b => { const o = document.createElement('option'); o.value = b.code || b.brgyCode; o.textContent = b.name || b.brgyDesc; barangaySelect.appendChild(o); });
                                        barangaySelect.disabled = false;
                                        setTimeout(() => { if (oldBarangay) barangaySelect.value = oldBarangay; }, 50);
                                    }
                                    if (cachedB.length > 0) loadBarangays(cachedB);
                                    else fetch(window.location.origin + `/api/barangays?city_code=${encodeURIComponent(citymunCode)}`).then(r=>r.json()).then(r=>(r.data||r)).then(loadBarangays).catch(()=>{barangaySelect.innerHTML='<option value="">Failed</option>';barangaySelect.disabled=false;});
                                }
                            }, 50);
                        }
                        if (cachedC.length > 0) loadCities(cachedC);
                        else fetch(window.location.origin + `/api/cities?province_code=${encodeURIComponent(provCode)}`).then(r=>r.json()).then(r=>(r.data||r)).then(loadCities).catch(()=>{citySelect.innerHTML='<option value="">Failed</option>';citySelect.disabled=false;});
                    }
                }, 100);
            }
            if (cached.length > 0) loadProvinces(cached);
            else fetch(window.location.origin + `/api/provinces?region_code=${encodeURIComponent(regCode)}`).then(r=>r.json()).then(r=>(r.data||r)).then(loadProvinces).catch(()=>{provinceSelect.innerHTML='<option value="">Failed</option>';provinceSelect.disabled=false;});
        }

        if (regionSelect) {
            const base = window.location.origin;
            regionSelect.innerHTML = '<option value="">-- Select Region --</option>';
            Promise.all([
                fetch(base+'/api/regions/all').then(r=>r.json()).then(r=>(r.data||r)),
                fetch(base+'/api/provinces/all').then(r=>r.json()).then(r=>{PROVINCES_ALL=r.data||[];return r.data||[];}),
                fetch(base+'/api/cities/all').then(r=>r.json()).then(r=>{CITIES_ALL=r.data||[];return r.data||[];}),
                fetch(base+'/api/barangays/all').then(r=>r.json()).then(r=>{BARANGAYS_ALL=r.data||[];return r.data||[];})
            ]).then(([regions]) => {
                regions.forEach(rg => { const o = document.createElement('option'); o.value = rg.code; o.textContent = rg.name; regionSelect.appendChild(o); });
                if (oldRegion) { regionSelect.value = oldRegion; setTimeout(triggerCascade, 200); }
                else if (regionSelect.options.length > 1) { regionSelect.selectedIndex = 1; regionSelect.dispatchEvent(new Event('change')); }
            }).catch(e => console.error('Failed loading address data', e));
        }

        regionSelect.addEventListener('change', function() {
            const regCode = this.value;
            provinceSelect.innerHTML = '<option value="">Loading...</option>';
            citySelect.innerHTML = '<option value="">-- Select City/Municipality --</option>';
            barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';
            if (!regCode) return;
            provinceSelect.disabled = true; citySelect.disabled = true; barangaySelect.disabled = true;
            const cached = PROVINCES_ALL.filter(p => String(p.region_code) === String(regCode));
            function fill(list) {
                provinceSelect.innerHTML = '<option value="">-- Select Province --</option>';
                list.forEach(p => { const o = document.createElement('option'); o.value = p.code||p.provCode; o.textContent = p.name||p.provDesc; provinceSelect.appendChild(o); });
                provinceSelect.disabled = false;
                if (oldProvince) { provinceSelect.value = oldProvince; provinceSelect.dispatchEvent(new Event('change')); }
            }
            if (cached.length > 0) fill(cached);
            else fetch(window.location.origin+`/api/provinces?region_code=${encodeURIComponent(regCode)}`).then(r=>r.json()).then(r=>(r.data||r)).then(fill).catch(()=>{provinceSelect.innerHTML='<option value="">Failed</option>';provinceSelect.disabled=false;});
        });

        provinceSelect.addEventListener('change', function() {
            const provCode = this.value;
            citySelect.innerHTML = '<option value="">Loading...</option>';
            barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';
            if (!provCode) return;
            citySelect.disabled = true; barangaySelect.disabled = true;
            const cached = CITIES_ALL.filter(c => String(c.province_code) === String(provCode));
            function fill(list) {
                citySelect.innerHTML = '<option value="">-- Select City/Municipality --</option>';
                list.forEach(c => { const o = document.createElement('option'); o.value = c.code||c.citymunCode||c.psgcCode; o.textContent = c.name||c.citymunDesc; citySelect.appendChild(o); });
                citySelect.disabled = false;
                if (oldCity) { citySelect.value = oldCity; citySelect.dispatchEvent(new Event('change')); }
            }
            if (cached.length > 0) fill(cached);
            else fetch(window.location.origin+`/api/cities?province_code=${encodeURIComponent(provCode)}`).then(r=>r.json()).then(r=>(r.data||r)).then(fill).catch(()=>{citySelect.innerHTML='<option value="">Failed</option>';citySelect.disabled=false;});
        });

        citySelect.addEventListener('change', function() {
            const citymunCode = this.value;
            barangaySelect.innerHTML = '<option value="">Loading...</option>';
            if (!citymunCode) return;
            barangaySelect.disabled = true;
            const cached = BARANGAYS_ALL.filter(b => String(b.city_code) === String(citymunCode));
            function fill(list) {
                barangaySelect.innerHTML = '<option value="">-- Select Barangay --</option>';
                list.forEach(b => { const o = document.createElement('option'); o.value = b.code||b.brgyCode; o.textContent = b.name||b.brgyDesc; barangaySelect.appendChild(o); });
                barangaySelect.disabled = false;
                if (oldBarangay) barangaySelect.value = oldBarangay;
            }
            if (cached.length > 0) fill(cached);
            else fetch(window.location.origin+`/api/barangays?city_code=${encodeURIComponent(citymunCode)}`).then(r=>r.json()).then(r=>(r.data||r)).then(fill).catch(()=>{barangaySelect.innerHTML='<option value="">Failed</option>';barangaySelect.disabled=false;});
        });

        function updateYearLevelOptions() {
            if (!yearLevel || !studentType) return;
            const isTransferee = (studentType.value || '').toLowerCase() === 'transferee';
            const note = document.getElementById('year_level_note');
            const info = document.getElementById('year_level_info');
            const cur = yearLevel.value;
            yearLevel.innerHTML = '<option value="">-- Select --</option>';
            const grades = isTransferee ? ['Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'] : ['Grade 7','Grade 11'];
            grades.forEach(g => { const o = document.createElement('option'); o.value = g; o.textContent = g; if (cur === g) o.selected = true; yearLevel.appendChild(o); });
            if (note) note.textContent = isTransferee ? '(All grade levels available)' : '(Grade 7 & 11 only)';
            if (info) info.textContent = isTransferee ? 'Transferees can select any grade level.' : 'Grade 8–10 & 12 should go to Enrollment directly.';
            toggleStrand();
        }

        function toggleStrand() {
            if (!yearLevel || !strandContainer) return;
            const strandSel = document.getElementById('strand');
            const tvlCont = document.getElementById('tvl_branch_container');
            const tvlSel = document.getElementById('tvl_branch');
            const sAst = document.getElementById('strand_asterisk');
            const tAst = document.getElementById('tvl_branch_asterisk');
            const isSHS = ['Grade 11','Grade 12'].includes(yearLevel.value);
            strandContainer.style.display = isSHS ? 'block' : 'none';
            if (strandSel) { isSHS ? strandSel.setAttribute('required','required') : strandSel.removeAttribute('required'); if (!isSHS) strandSel.value = ''; }
            if (sAst) sAst.style.display = isSHS ? 'inline' : 'none';
            const isTVL = isSHS && strandSel?.value === 'TVL';
            if (tvlCont) tvlCont.style.display = isTVL ? 'block' : 'none';
            if (tvlSel) { isTVL ? tvlSel.setAttribute('required','required') : (tvlSel.removeAttribute('required'), tvlSel.value = ''); }
            if (tAst) tAst.style.display = isTVL ? 'inline' : 'none';
        }

        function removeFileRequirements() {
            document.querySelectorAll('input[type="file"]').forEach(i => { i.removeAttribute('required'); i.required = false; });
        }

        removeFileRequirements();
        if (studentType) updateYearLevelOptions();
        if (yearLevel && strandContainer) toggleStrand();

        if (studentType) studentType.addEventListener('change', updateYearLevelOptions);
        if (yearLevel) yearLevel.addEventListener('change', toggleStrand);
        const strandSel = document.getElementById('strand');
        if (strandSel) strandSel.addEventListener('change', toggleStrand);

        const emailInputEl = document.getElementById('email');
        if (emailInputEl && !emailInputEl.readOnly) {
            emailInputEl.addEventListener('blur', function () {
                if (emailInputEl.value.trim() !== '' || emailInputEl.classList.contains('is-invalid')) {
                    validateEmailField();
                }
            });
            emailInputEl.addEventListener('input', function () {
                if (emailInputEl.classList.contains('is-invalid')) {
                    validateEmailField();
                }
            });
        }

        const admForm = document.getElementById('admissionForm');
        if (admForm) {
            let clickedButton = null;
            const draftBtn = admForm.querySelector('button[name="save_as_draft"]');
            const submitBtn = admForm.querySelector('button[name="submit_admission"]');
            if (draftBtn) draftBtn.addEventListener('click', () => clickedButton = 'draft');
            if (submitBtn) submitBtn.addEventListener('click', () => clickedButton = 'submit');
            admForm.addEventListener('submit', function(e) {
                removeFileRequirements();
                const isDraft = clickedButton === 'draft';
                if (!isDraft) {
                    const emailResult = validateEmailField();
                    if (!emailResult.valid) {
                        e.preventDefault();
                        goToStep(1);
                        Swal.fire({ icon: 'error', title: 'Validation Error', text: emailResult.message });
                        document.getElementById('email')?.focus();
                        clickedButton = null;
                        return false;
                    }
                }
                if (!isDraft && !isEditing) {
                    const type = (studentType?.value || '').toLowerCase();
                    const optFiled = ['medical_certificate','id_picture'];
                    let req = [];
                    if (['new','transferee'].includes(type)) req = ['form_137','report_card','birth_certificate'];
                    const errs = [];
                    req.forEach(f => {
                        const inp = document.querySelector(`input[name="${f}"]`);
                        if (!inp?.files?.length) errs.push(inp?.getAttribute('data-doc-name') || f);
                    });
                    if (errs.length) {
                        e.preventDefault();
                        Swal.fire({ icon: 'warning', title: 'Required Files Missing', html: 'Please upload:<br>' + errs.join('<br>'), confirmButtonColor: '#1a56b0' });
                        clickedButton = null; return false;
                    }
                }
                clickedButton = null;
            });
        }

        if (document.getElementById('step3')?.classList.contains('active')) {
            populateReview();
        }
    });
</script>