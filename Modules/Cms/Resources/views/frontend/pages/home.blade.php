@extends('cms::frontend.layouts.app')
@section('title', 'Home')
@php
    $navbar_btn['text'] = 'Try For Free';
    $navbar_btn['link'] = route('business.getRegister');
    $navbar_btn['drop_down_text'] = 'Pages';
    if(isset($__site_details['btns']) && isset($__site_details['btns']['navbar']) && !empty($__site_details['btns']['navbar']['text'])) {
        $navbar_btn['text'] = $__site_details['btns']['navbar']['text'] ?? 'Try For Free';
    }
    if(isset($__site_details['btns']) && isset($__site_details['btns']['navbar']) && !empty($__site_details['btns']['navbar']['link'])) {
        $navbar_btn['link'] = $__site_details['btns']['navbar']['link'] ?? route('business.getRegister');
    }
    if(isset($__site_details['btns']) && isset($__site_details['btns']['navbar']) && !empty($__site_details['btns']['navbar']['drop_down_text'])) {
        $navbar_btn['drop_down_text'] = $__site_details['btns']['navbar']['drop_down_text'] ?? 'Pages';
    }
    $hero_btn['text'] = 'Start your Free Trial';
    $hero_btn['link'] = route('business.getRegister');
    if(isset($__site_details['btns']) && isset($__site_details['btns']['hero']) && !empty($__site_details['btns']['hero']['text'])) {
        $hero_btn['text'] = $__site_details['btns']['hero']['text'] ?? 'Start your Free Trial';
    }
    if(isset($__site_details['btns']) && isset($__site_details['btns']['hero']) && !empty($__site_details['btns']['hero']['link'])) {
        $hero_btn['link'] = $__site_details['btns']['hero']['link'] ?? route('business.getRegister');
    }

    $industry_btn['text'] = 'Get Started';
    $industry_btn['link'] = route('business.getRegister');
    if(isset($__site_details['btns']) && isset($__site_details['btns']['industry']) && !empty($__site_details['btns']['industry']['text'])) {
        $industry_btn['text'] = $__site_details['btns']['industry']['text'] ?? 'Get Started';
    }
    if(isset($__site_details['btns']) && isset($__site_details['btns']['industry']) && !empty($__site_details['btns']['industry']['link'])) {
        $industry_btn['link'] = $__site_details['btns']['industry']['link'] ?? route('business.getRegister');
    }

    $cta_btn['text'] = 'Try Now';
    $cta_btn['link'] = route('business.getRegister');
    if(isset($__site_details['btns']) && isset($__site_details['btns']['cta']) && !empty($__site_details['btns']['cta']['text'])) {
        $cta_btn['text'] = $__site_details['btns']['cta']['text'] ?? 'Try Now';
    }
    if(isset($__site_details['btns']) && isset($__site_details['btns']['cta']) && !empty($__site_details['btns']['cta']['link'])) {
        $cta_btn['link'] = $__site_details['btns']['cta']['link'] ?? route('business.getRegister');
    }
@endphp
@includeIf('cms::frontend.layouts.home_header')
@section('meta')
    <meta name="description" content="{{$page->meta_description}}">
@endsection
@section('content')
@php
    $page_meta = $page->pageMeta->keyBy('meta_key');
@endphp

<style>
    /* Multistep Form Section */
    .multistep-form-section {
        padding: 80px 20px;
        background: #FFFFFF;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-container {
        background: white;
        border-radius: 20px;
        padding: 50px;
        max-width: 900px;
        width: 100%;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }

    .form-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .form-header h2 {
        font-size: 2rem;
        color: #333;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .form-header p {
        color: #666;
        font-size: 1rem;
    }

    /* Progress Bar */
    .multistep-progress-bar {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 50px;
        position: relative;
        gap: 0;
    }

    .multistep-progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        position: relative;
    }

    .multistep-step-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: white;
        border: 3px solid #E8E8E8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.3rem;
        color: #D0D0D0;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .multistep-step-circle.active {
        background: linear-gradient(135deg, #FF1B8D 0%, #D946EF 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 20px rgba(255, 27, 141, 0.4);
    }

    .multistep-step-circle.completed {
        background: white;
        color: #D0D0D0;
        border: 3px solid #E8E8E8;
    }

    .multistep-step-connector {
        width: 80px;
        height: 3px;
        background: #E8E8E8;
    }
    
    .multistep-step-connector.active {
        background: #E8E8E8;
    }

    .multistep-step-label {
        font-size: 0.95rem;
        color: #A0A0A0;
        font-weight: 600;
        white-space: nowrap;
    }

    .multistep-progress-step .multistep-step-circle.active ~ .multistep-step-label {
        color: #FF1B8D;
    }

    .multistep-progress-step .multistep-step-circle.completed ~ .multistep-step-label {
        color: #A0A0A0;
    }

    /* Form Steps */
    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
        animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .step-title {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 30px;
        font-weight: 600;
    }

    /* Search Input */
    .search-container {
        margin-bottom: 30px;
    }

    .search-info {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
        color: #0088FF;
        font-size: 0.95rem;
    }

    .search-input-wrapper {
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 15px 50px 15px 20px;
        border: 2px solid #E3F2FD;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #0088FF;
        box-shadow: 0 0 0 3px rgba(0, 136, 255, 0.1);
    }

    .search-btn {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        background: linear-gradient(135deg, #0088FF 0%, #0066CC 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .search-btn:hover {
        box-shadow: 0 4px 12px rgba(0, 136, 255, 0.3);
    }

    .find-model-link {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 5px;
        margin-top: 10px;
        color: #0088FF;
        font-size: 0.9rem;
        cursor: pointer;
        text-decoration: none;
    }

    .find-model-link:hover {
        text-decoration: underline;
    }

    /* Device Grid */
    .device-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .device-option {
        background: white;
        border: 2px solid #E8E8E8;
        border-radius: 12px;
        padding: 30px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .device-option:hover {
        border-color: #0088FF;
        background: #F8FCFF;
        transform: translateY(-2px);
    }

    .device-option.selected {
        border-color: #0088FF;
        background: #E3F2FD;
    }

    .device-icon {
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .device-name {
        font-size: 0.95rem;
        font-weight: 600;
        color: #333;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Back Button */
    .back-button {
        background: none;
        border: none;
        color: inherit;
        font-size: inherit;
        font-weight: inherit;
        cursor: pointer;
        padding: 0;
        margin: 0;
        display: inline-flex;
        align-items: center;
        vertical-align: middle;
        transition: all 0.3s ease;
    }

    .back-button:hover {
        opacity: 0.8;
    }

    .back-button .arrow-icon {
        width: 32px;
        height: 32px;
        background: #0088FF;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .back-button:hover .arrow-icon {
        background: #0066CC;
        transform: translateX(-3px);
    }

    .back-button .arrow-icon svg {
        width: 20px;
        height: 20px;
    }

    .step-title .back-button {
        margin-right: 5px;
    }

    /* Brand Grid */
    .brand-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 40px;
    }

    .brand-option {
        background: white;
        border: 2px solid #E8E8E8;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        font-weight: 600;
        color: #333;
        transition: all 0.3s ease;
    }

    .brand-option:hover {
        border-color: #0088FF;
        background: #F8FCFF;
    }

    .brand-option.selected {
        border-color: #0088FF;
        background: #E3F2FD;
        color: #0088FF;
    }

    /* Issue List */
    .issue-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 30px;
    }

    .issue-option {
        background: white;
        border: 2px solid #E8E8E8;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .issue-option:hover {
        border-color: #0088FF;
        background: #F8FCFF;
    }

    .issue-option.selected {
        border-color: #0088FF;
        background: #E3F2FD;
    }

    .issue-icon {
        font-size: 2rem;
        flex-shrink: 0;
    }

    .issue-text {
        flex: 1;
    }

    .issue-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .issue-desc {
        font-size: 0.85rem;
        color: #666;
    }

    /* Form Buttons */
    .form-buttons {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-top: 40px;
    }

    .form-btn {
        padding: 15px 40px;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-prev {
        background: #F5F5F5;
        color: #666;
    }

    .btn-prev:hover {
        background: #E8E8E8;
    }

    .btn-next {
        background: linear-gradient(135deg, #0088FF 0%, #0066CC 100%);
        color: white;
        flex: 1;
    }

    .btn-next:hover:not(:disabled) {
        box-shadow: 0 4px 15px rgba(0, 136, 255, 0.3);
        transform: translateY(-2px);
    }

    .btn-next:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Contact Form */
    .contact-form {
        display: grid;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .form-group input,
    .form-group textarea {
        padding: 12px 15px;
        border: 2px solid #E8E8E8;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #0088FF;
        box-shadow: 0 0 0 3px rgba(0, 136, 255, 0.1);
    }

    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }

    .summary-box {
        background: #F8FCFF;
        border: 2px solid #E3F2FD;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .summary-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.95rem;
    }

    .summary-label {
        color: #666;
    }

    .summary-value {
        font-weight: 600;
        color: #333;
    }

    /* Language Selector */
    .language-selector {
        display: flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        font-size: 0.9rem;
        color: #666;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-container {
            padding: 30px 20px;
        }

        .device-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .brand-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .issue-list {
            grid-template-columns: 1fr;
        }

        .multistep-step-connector {
            width: 60px;
        }

        .multistep-step-label {
            font-size: 0.75rem;
        }
    }
</style>

<!-- Multistep Form Section -->
<section class="multistep-form-section">
    <div class="form-container">
        <!-- Form Header -->
        <div class="form-header">
            <h2>Ihr Gerät. Unsere Expertise.</h2>
            <p>Egal ob iPhone, iPad, Samsung, Google Pixel, MacBook oder Laptop – mit repairNstore<br>
            erhalten Sie professionelle Reparaturen, Originalteile und Garantie. Wählen Sie jetzt Ihr<br>
            Gerät aus und starten Sie Ihre Reparatur.</p>
        </div>

        <!-- Progress Bar -->
        <div class="multistep-progress-bar">
            <div class="multistep-progress-step">
                <div class="multistep-step-circle active" data-step="1">1</div>
                <div class="multistep-step-label">Select device</div>
            </div>
            <div class="multistep-step-connector"></div>
            <div class="multistep-progress-step">
                <div class="multistep-step-circle" data-step="2">2</div>
                <div class="multistep-step-label">Select repair</div>
            </div>
            <div class="multistep-step-connector"></div>
            <div class="multistep-progress-step">
                <div class="multistep-step-circle" data-step="3">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                    </svg>
                </div>
                <div class="multistep-step-label">Finalize order</div>
            </div>
        </div>

        <!-- Form -->
        <form id="multistepForm" action="{{ route('cms.contact.us') }}" method="POST">
            @csrf
            
            <!-- Step 1: Device Selection -->
            <div class="form-step active" data-step="1">
               
                <h3 class="step-title">
                    <button type="button" class="back-button" id="backToDeviceType">
                        <span class="arrow-icon">
                            <svg viewBox="0 0 24 24" fill="white">
                                <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                            </svg>
                        </span>
                    </button>
                    Which <strong>model</strong> do you have?
                </h3>
                
                <!-- Device Type Section -->
                <div id="deviceTypeSection">
                    <!-- Search Input -->
                    <div class="search-container">
                        <div class="search-info">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm1 12H7V7h2v5zm0-6H7V4h2v2z"/>
                            </svg>
                            <span>Type in your <strong>brand</strong>, <strong>model</strong> or <strong>model code</strong>.</span>
                        </div>
                        <div class="search-input-wrapper">
                            <input type="text" class="search-input" id="modelSearch" placeholder="iPhone 15 Pro Max">
                            <button type="button" class="search-btn">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="white" style="display: inline-block; vertical-align: middle;">
                                    <path d="M11.7 10.3l3.6 3.6-1.4 1.4-3.6-3.6C9.5 12.5 8.3 13 7 13c-3.3 0-6-2.7-6-6s2.7-6 6-6 6 2.7 6 6c0 1.3-0.5 2.5-1.3 3.4zM7 3C4.8 3 3 4.8 3 7s1.8 4 4 4 4-1.8 4-4S9.2 3 7 3z"/>
                                </svg>
                            </button>
                        </div>
                        <a href="#" class="find-model-link">
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm1 12H7V7h2v5zm0-6H7V4h2v2z"/>
                            </svg>
                            Find my model
                        </a>
                    </div>

                    <div class="search-info" style="margin-bottom: 15px;">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <circle cx="8" cy="8" r="2"/>
                        </svg>
                        <span>Or select your <strong>type</strong></span>
                    </div>

                    <!-- Device Type Grid -->
                    <div class="device-grid">
                        <div class="device-option" data-device="smartphone">
                            <div class="device-icon">📱</div>
                            <div class="device-name">SMARTPHONE</div>
                        </div>
                        <div class="device-option" data-device="tablet">
                            <div class="device-icon">📲</div>
                            <div class="device-name">TABLET</div>
                        </div>
                        <div class="device-option" data-device="laptop">
                            <div class="device-icon">💻</div>
                            <div class="device-name">LAPTOP</div>
                        </div>
                        <div class="device-option" data-device="desktop">
                            <div class="device-icon">🖥️</div>
                            <div class="device-name">DESKTOP</div>
                        </div>
                        <div class="device-option" data-device="watch">
                            <div class="device-icon">⌚</div>
                            <div class="device-name">WATCH</div>
                        </div>
                        <div class="device-option" data-device="console">
                            <div class="device-option" data-device="console">
                            <div class="device-icon">🎮</div>
                            <div class="device-name">CONSOLE</div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Brand Selection - Shows after device type is selected -->
                <div id="brandSection" style="display: none;">
                   
                    
                    <div class="search-info" style="margin-bottom: 15px; margin-top: 20px;">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <circle cx="8" cy="8" r="2"/>
                        </svg>
                        <span>Or select your <strong>brand</strong></span>
                    </div>
                    
                    <div class="brand-grid" id="brandGridStep1">
                        <div class="brand-option" data-brand="apple">Apple</div>
                        <div class="brand-option" data-brand="samsung">Samsung</div>
                        <div class="brand-option" data-brand="google">Google</div>
                        <div class="brand-option" data-brand="huawei">Huawei</div>
                        <div class="brand-option" data-brand="xiaomi">Xiaomi</div>
                        <div class="brand-option" data-brand="oneplus">OnePlus</div>
                        <div class="brand-option" data-brand="sony">Sony</div>
                        <div class="brand-option" data-brand="lg">LG</div>
                        <div class="brand-option" data-brand="dell">Dell</div>
                        <div class="brand-option" data-brand="hp">HP</div>
                        <div class="brand-option" data-brand="lenovo">Lenovo</div>
                        <div class="brand-option" data-brand="other">Other</div>
                    </div>
                </div>

                <div class="form-buttons">
                    <button type="button" class="form-btn btn-next" id="step1Next" disabled>Next</button>
                </div>
            </div>

            <!-- Step 2: Issue Selection -->
            <div class="form-step" data-step="2">
                <h3 class="step-title">Select your brand</h3>
                <div class="brand-grid">
                    <div class="brand-option" data-brand="apple">Apple</div>
                    <div class="brand-option" data-brand="samsung">Samsung</div>
                    <div class="brand-option" data-brand="google">Google</div>
                    <div class="brand-option" data-brand="huawei">Huawei</div>
                    <div class="brand-option" data-brand="xiaomi">Xiaomi</div>
                    <div class="brand-option" data-brand="oneplus">OnePlus</div>
                    <div class="brand-option" data-brand="sony">Sony</div>
                    <div class="brand-option" data-brand="lg">LG</div>
                    <div class="brand-option" data-brand="dell">Dell</div>
                    <div class="brand-option" data-brand="hp">HP</div>
                    <div class="brand-option" data-brand="lenovo">Lenovo</div>
                    <div class="brand-option" data-brand="other">Other</div>
                </div>

                <h3 class="step-title" style="margin-top: 40px;">What's the issue?</h3>
                <div class="issue-list">
                    <div class="issue-option" data-issue="screen">
                        <div class="issue-icon">🔨</div>
                        <div class="issue-text">
                            <div class="issue-title">Screen Repair</div>
                            <div class="issue-desc">Cracked, broken or unresponsive screen</div>
                        </div>
                    </div>
                    <div class="issue-option" data-issue="battery">
                        <div class="issue-icon">🔋</div>
                        <div class="issue-text">
                            <div class="issue-title">Battery Replacement</div>
                            <div class="issue-desc">Poor battery life or won't charge</div>
                        </div>
                    </div>
                    <div class="issue-option" data-issue="water">
                        <div class="issue-icon">💧</div>
                        <div class="issue-text">
                            <div class="issue-title">Water Damage</div>
                            <div class="issue-desc">Device exposed to liquid</div>
                        </div>
                    </div>
                    <div class="issue-option" data-issue="camera">
                        <div class="issue-icon">📷</div>
                        <div class="issue-text">
                            <div class="issue-title">Camera Issues</div>
                            <div class="issue-desc">Camera not working properly</div>
                        </div>
                    </div>
                    <div class="issue-option" data-issue="speaker">
                        <div class="issue-icon">🔊</div>
                        <div class="issue-text">
                            <div class="-title">Speaker/Audio</div>
                            <div class="issue-desc">No sound or distorted audio</div>
                        </div>
                    </div>
                    <div class="issue-option" data-issue="other">
                        <div class="issue-icon">🔧</div>
                        <div class="issue-text">
                            <div class="issue-title">Other Issue</div>
                            <div class="issue-desc">Something else needs fixing</div>
                        </div>
                    </div>
                </div>
                <div class="form-buttons">
                    <button type="button" class="form-btn btn-prev">Previous</button>
                    <button type="button" class="form-btn btn-next" id="step2Next" disabled>Next</button>
                </div>
            </div>

            <!-- Step 3: Contact Information -->
            <div class="form-step" data-step="3">
                <h3 class="step-title">Your Contact Information</h3>
                
                <div class="summary-box">
                    <div class="summary-title">Repair Summary</div>
                    <div class="summary-item">
                        <span class="summary-label">Device:</span>
                        <span class="summary-value" id="summaryDevice">-</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Brand:</span>
                        <span class="summary-value" id="summaryBrand">-</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Issue:</span>
                        <span class="summary-value" id="summaryIssue">-</span>
                    </div>
                </div>

                <div class="contact-form">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Additional Notes (Optional)</label>
                        <textarea id="message" name="message" placeholder="Any additional information about your device or issue..."></textarea>
                    </div>
                </div>

                <!-- Hidden fields for form data -->
                <input type="hidden" name="device" id="hiddenDevice">
                <input type="hidden" name="brand" id="hiddenBrand">
                <input type="hidden" name="issue" id="hiddenIssue">

                <div class="form-buttons">
                    <button type="button" class="form-btn btn-prev">Previous</button>
                    <button type="submit" class="form-btn btn-next">Submit Request</button>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    // Multistep Form Logic
    let currentStep = 1;
    const totalSteps = 3;
    let formData = {
        device: '',
        brand: '',
        issue: '',
        model: ''
    };

    // Model Search Input
    const modelSearchInput = document.getElementById('modelSearch');
    if (modelSearchInput) {
        modelSearchInput.addEventListener('input', function() {
            if (this.value.trim().length > 0) {
                formData.model = this.value.trim();
                formData.device = 'search';
                document.getElementById('step1Next').disabled = false;
                document.querySelectorAll('.device-option').forEach(opt => opt.classList.remove('selected'));
            } else {
                if (formData.device === 'search') {
                    formData.model = '';
                    formData.device = '';
                    document.getElementById('step1Next').disabled = true;
                }
            }
        });
    }

    // Device Selection
    document.querySelectorAll('.device-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.device-option').forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            formData.device = this.dataset.device;
            formData.model = '';
            if (modelSearchInput) {
                modelSearchInput.value = '';
            }
            
            // Hide device type section and show brand section
            document.getElementById('deviceTypeSection').style.display = 'none';
            document.getElementById('brandSection').style.display = 'block';
            
            // Filter brands based on device type
            if (this.dataset.device === 'tablet') {
                filterBrandsByDevice('tablet');
            } else if (this.dataset.device === 'smartphone') {
                filterBrandsByDevice('smartphone');
            } else if (this.dataset.device === 'laptop') {
                filterBrandsByDevice('laptop');
            } else if (this.dataset.device === 'desktop') {
                filterBrandsByDevice('desktop');
            } else if (this.dataset.device === 'watch') {
                filterBrandsByDevice('watch');
            } else if (this.dataset.device === 'console') {
                filterBrandsByDevice('console');
            } else {
                showAllBrands();
            }
        });
    });
    
    // Back to device type button
    const backToDeviceBtn = document.getElementById('backToDeviceType');
    if (backToDeviceBtn) {
        backToDeviceBtn.addEventListener('click', function() {
            // Hide brand section and show device type section
            document.getElementById('brandSection').style.display = 'none';
            document.getElementById('deviceTypeSection').style.display = 'block';
            
            // Clear brand selection
            document.querySelectorAll('#brandGridStep1 .brand-option').forEach(opt => opt.classList.remove('selected'));
            formData.brand = '';
            document.getElementById('step1Next').disabled = true;
        });
    }
    
    // Filter brands based on device type
    function filterBrandsByDevice(deviceType) {
        const brandFilters = {
            'tablet': ['apple', 'samsung', 'huawei', 'lenovo', 'xiaomi', 'other'],
            'smartphone': ['apple', 'samsung', 'google', 'huawei', 'xiaomi', 'oneplus', 'sony', 'lg', 'other'],
            'laptop': ['apple', 'dell', 'hp', 'lenovo', 'other'],
            'desktop': ['dell', 'hp', 'lenovo', 'other'],
            'watch': ['apple', 'samsung', 'huawei', 'xiaomi', 'other'],
            'console': ['sony', 'other']
        };
        
        const allowedBrands = brandFilters[deviceType] || [];
        const allBrands = document.querySelectorAll('#brandGridStep1 .brand-option');
        
        allBrands.forEach(brand => {
            const brandValue = brand.dataset.brand;
            if (allowedBrands.includes(brandValue)) {
                brand.style.display = 'block';
            } else {
                brand.style.display = 'none';
            }
        });
    }
    
    // Show all brands
    function showAllBrands() {
        document.querySelectorAll('#brandGridStep1 .brand-option').forEach(brand => {
            brand.style.display = 'block';
        });
    }

    // Brand Selection (for both step 1 and step 2)
    document.querySelectorAll('.brand-option').forEach(option => {
        option.addEventListener('click', function() {
            // Get the parent grid to only affect brands in the same section
            const parentGrid = this.closest('.brand-grid');
            parentGrid.querySelectorAll('.brand-option').forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            formData.brand = this.dataset.brand;
            
            // If we're on step 1, enable next button
            if (currentStep === 1) {
                document.getElementById('step1Next').disabled = false;
            } else {
                checkStep2Complete();
            }
        });
    });

    // Issue Selection
    document.querySelectorAll('.issue-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.issue-option').forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            formData.issue = this.dataset.issue;
            checkStep2Complete();
        });
    });
    
    function checkStep2Complete() {
        if (formData.brand && formData.issue) {
            document.getElementById('step2Next').disabled = false;
        }
    }

    // Next Button
    document.querySelectorAll('.btn-next').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (this.type !== 'submit' && currentStep < totalSteps) {
                e.preventDefault();
                currentStep++;
                updateStep();
            }
        });
    });

    // Previous Button
    document.querySelectorAll('.btn-prev').forEach(btn => {
        btn.addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                updateStep();
            }
        });
    });

    function updateStep() {
        // Update form steps
        document.querySelectorAll('.form-step').forEach(step => {
            step.classList.remove('active');
        });
        document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.add('active');

        // Update progress step circles
        document.querySelectorAll('.multistep-progress-step').forEach((step, index) => {
            const stepNum = index + 1;
            const circle = step.querySelector('.multistep-step-circle');
            
            circle.classList.remove('active', 'completed');
            
            if (stepNum === currentStep) {
                circle.classList.add('active');
            } else if (stepNum < currentStep) {
                circle.classList.add('completed');
            }
        });

        // Update connectors
        const connectors = document.querySelectorAll('.multistep-step-connector');
        connectors.forEach((connector, index) => {
            connector.classList.remove('active');
            if (index < currentStep - 1) {
                connector.classList.add('active');
            }
        });

        // Update summary on last step
        if (currentStep === 3) {
            const deviceDisplay = formData.model ? formData.model : capitalizeFirst(formData.device);
            document.getElementById('summaryDevice').textContent = deviceDisplay;
            document.getElementById('summaryBrand').textContent = capitalizeFirst(formData.brand);
            document.getElementById('summaryIssue').textContent = getIssueLabel(formData.issue);
            
            document.getElementById('hiddenDevice').value = formData.device;
            document.getElementById('hiddenBrand').value = formData.brand;
            document.getElementById('hiddenIssue').value = formData.issue;
            
            if (formData.model) {
                let modelInput = document.getElementById('hiddenModel');
                if (!modelInput) {
                    modelInput = document.createElement('input');
                    modelInput.type = 'hidden';
                    modelInput.name = 'model';
                    modelInput.id = 'hiddenModel';
                    document.getElementById('multistepForm').appendChild(modelInput);
                }
                modelInput.value = formData.model;
            }
        }
    }

    function capitalizeFirst(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function getIssueLabel(issue) {
        const labels = {
            'screen': 'Screen Repair',
            'battery': 'Battery Replacement',
            'water': 'Water Damage',
            'camera': 'Camera Issues',
            'speaker': 'Speaker/Audio',
            'other': 'Other Issue'
        };
        return labels[issue] || issue;
    }
</script>

<!------------------------------>
<!--Features---------------->
<!------------------------------>
@includeIf('cms::frontend.pages.partials.features', ['page_meta' => $page_meta])

<!------------------------------>
<!--Industries---------------->
<!------------------------------>
@includeIf('cms::frontend.pages.partials.industries', ['page_meta' => $page_meta])

<!------------------------------>
<!--Stats---------------->
<!------------------------------>
@includeIf('cms::frontend.pages.partials.statistics', ['statistics' => $statistics ?? []])

<!------------------------------>
<!--Testimonial---------------->
<!------------------------------>
@includeIf('cms::frontend.pages.partials.testimonial', ['testimonials' => $testimonials ?? []])

<!------------------------------>
<!--CTA---------------->
<!------------------------------>
@includeIf('cms::frontend.pages.partials.cta')

<!------------------------------>
<!--FAQ---------------->
<!------------------------------>
@includeIf('cms::frontend.pages.partials.faq', ['faqs' => $faqs ?? []])
@endsection
@section('javascript')
<script type="text/javascript">
    new Sticky("[sticky]");
</script>
@endsection