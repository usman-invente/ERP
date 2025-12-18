@extends('cms::frontend.layouts.app')
@section('title', 'Home')

@section('meta')
    <meta name="description" content="Professional ERP Solutions for Your Business">
@endsection

@section('content')
<style>
    :root {
        --primary-color: #0066cc;
        --secondary-color: #ff6b00;
        --dark-color: #1a1a1a;
        --light-gray: #f8f9fa;
        --border-color: #e0e0e0;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        line-height: 1.6;
        color: #333;
    }

    /* Modern Navbar */
    .modern-navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .modern-navbar.scrolled {
        box-shadow: 0 2px 15px rgba(0,0,0,0.12);
    }

    .navbar-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 70px;
    }

    .navbar-logo img {
        height: 45px;
        width: auto;
    }

    .navbar-menu {
        display: flex;
        list-style: none;
        gap: 35px;
        align-items: center;
        margin: 0;
    }

    .navbar-menu > li {
        position: relative;
    }

    .navbar-link {
        text-decoration: none;
        color: #1a1a1a;
        font-weight: 500;
        font-size: 0.95rem;
        transition: color 0.2s ease;
        padding: 8px 0;
        display: inline-block;
    }

    .navbar-link:hover {
        color: #7c3aed;
    }

    .navbar-dropdown {
        position: relative;
    }

    .navbar-dropdown-toggle {
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        color: #1a1a1a;
        font-weight: 500;
        font-size: 0.95rem;
        transition: color 0.2s ease;
    }

    .navbar-dropdown-toggle:hover {
        color: #7c3aed;
    }

    .navbar-dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        background: white;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        border-radius: 8px;
        min-width: 220px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        margin-top: 15px;
        padding: 8px 0;
    }

    .navbar-dropdown:hover .navbar-dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .navbar-dropdown-menu a {
        display: block;
        padding: 12px 20px;
        color: #1a1a1a;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .navbar-dropdown-menu a:hover {
        background: #f8f9fa;
        color: #7c3aed;
        padding-left: 25px;
    }

    .navbar-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .navbar-btn-outline {
        padding: 10px 24px;
        background: white;
        color: #1a1a1a;
        text-decoration: none;
        border: 1.5px solid #e0e0e0;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        display: inline-block;
    }

    .navbar-btn-outline:hover {
        border-color: #7c3aed;
        color: #7c3aed;
        background: #f8f4ff;
    }

    .navbar-btn {
        padding: 10px 24px;
        background: #7c3aed;
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        display: inline-block;
        border: none;
    }

    .navbar-btn:hover {
        background: #6d28d9;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        color: white;
    }

    /* Mobile Menu */
    .mobile-menu-toggle {
        display: none;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #1a1a1a;
    }

    .mobile-menu {
        display: none;
        position: fixed;
        top: 70px;
        left: 0;
        right: 0;
        background: white;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        max-height: calc(100vh - 70px);
        overflow-y: auto;
        padding: 20px;
        z-index: 999;
    }

    .mobile-menu.active {
        display: block;
    }

    .mobile-menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .mobile-menu li {
        margin-bottom: 10px;
    }

    .mobile-menu a {
        display: block;
        padding: 15px;
        color: #1a1a1a;
        text-decoration: none;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .mobile-menu a:hover {
        background: #f8f9fa;
        color: #7c3aed;
    }

    .mobile-dropdown-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        cursor: pointer;
        font-weight: 500;
        border-radius: 8px;
    }

    .mobile-dropdown-toggle:hover {
        background: #f8f9fa;
    }

    .mobile-dropdown-menu {
        display: none;
        padding-left: 20px;
        margin-top: 5px;
    }

    .mobile-dropdown-menu.active {
        display: block;
    }

    .mobile-navbar-actions {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    @media (max-width: 992px) {
        .navbar-menu,
        .navbar-actions {
            display: none;
        }

        .mobile-menu-toggle {
            display: block;
        }

        .navbar-container {
            height: 70px;
            padding: 0 20px;
        }

        .navbar-logo img {
            height: 40px;
        }
    }

    /* Hero Section */
    .modern-hero {
        background: #f8f9fa;
        padding-top: 70px;
        position: relative;
        overflow: hidden;
        min-height: 90vh;
    }

    .hero-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 80px 40px 60px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: center;
        min-height: calc(90vh - 70px);
    }

    /* Multistep Form */
    .multistep-form-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #4A90E2 0%, #7B68EE 100%);
    }

    .multistep-form-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .form-header {
        text-align: center;
        color: white;
        margin-bottom: 40px;
    }

    .form-header h2 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .form-header p {
        font-size: 1.2rem;
        opacity: 0.95;
    }

    .multistep-form-wrapper {
        background: white;
        border-radius: 20px;
        padding: 50px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    .progress-bar {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 50px;
        position: relative;
        padding: 0;
        gap: 0;
    }

    .progress-bar::before {
        display: none;
    }

    .progress-bar-fill {
        display: none;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .step-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #999;
        margin-bottom: 10px;
        transition: all 0.3s ease;
        font-size: 1.2rem;
        border: 3px solid #E8E8E8;
    }

    .progress-step.active .step-circle {
        background: linear-gradient(135deg, #9B59B6 0%, #8E44AD 100%);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 4px 20px rgba(155, 89, 182, 0.5);
        border: none;
    }

    .progress-step.completed .step-circle {
        background: #0088FF;
        color: white;
        border: none;
    }

    .step-connector {
        width: 80px;
        height: 3px;
        background: #E8E8E8;
        margin: 0 -10px;
        align-self: flex-start;
        margin-top: 23px;
    }

    .step-connector.active {
        background: #0088FF;
    }

    .step-label {
        font-size: 0.9rem;
        color: #999;
        font-weight: 600;
        text-align: center;
        margin-top: 5px;
    }

    .progress-step.active .step-label {
        color: #333;
        font-weight: 700;
    }

    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
        animation: fadeInStep 0.4s ease;
    }

    @keyframes fadeInStep {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .step-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 30px;
        color: #1a1a1a;
    }

    .device-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .device-option {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 25px 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .device-option:hover {
        border-color: #0088FF;
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 136, 255, 0.2);
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
        font-weight: 600;
        color: #1a1a1a;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .brand-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }

    .brand-option {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        color: #1a1a1a;
    }

    .brand-option:hover {
        border-color: #0088FF;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 136, 255, 0.2);
    }

    .brand-option.selected {
        border-color: #0088FF;
        background: #E3F2FD;
    }

    .issue-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 30px;
    }

    .issue-option {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .issue-option:hover {
        border-color: #0088FF;
        box-shadow: 0 3px 10px rgba(0, 136, 255, 0.15);
    }

    .issue-option.selected {
        border-color: #0088FF;
        background: #E3F2FD;
    }

    .issue-icon {
        font-size: 1.5rem;
        color: #0088FF;
    }

    .issue-text {
        flex: 1;
    }

    .issue-title {
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 5px;
    }

    .issue-desc {
        font-size: 0.9rem;
        color: #666;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 10px;
        font-size: 1rem;
    }

    .form-input {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #0088FF;
        box-shadow: 0 0 0 3px rgba(0, 136, 255, 0.1);
    }

    .search-input-wrapper {
        position: relative;
        width: 100%;
    }

    .search-input-wrapper .form-input {
        padding-right: 50px;
    }

    .search-input-wrapper .fa-search {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #0088FF;
        font-size: 1.2rem;
        pointer-events: none;
    }

    .form-textarea {
        min-height: 120px;
        resize: vertical;
    }

    .form-buttons {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-top: 40px;
    }

    .form-btn {
        padding: 15px 40px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-prev {
        background: #f0f0f0;
        color: #666;
    }

    .btn-prev:hover {
        background: #e0e0e0;
        transform: translateY(-2px);
    }

    .btn-next {
        background: linear-gradient(135deg, #0088FF 0%, #0066CC 100%);
        color: white;
        flex: 1;
    }

    .btn-next:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 136, 255, 0.4);
    }

    .btn-next:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .summary-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-label {
        font-weight: 600;
        color: #666;
    }

    .summary-value {
        font-weight: 700;
        color: #1a1a1a;
    }

    @media (max-width: 768px) {
        .multistep-form-wrapper {
            padding: 30px 20px;
        }

        .form-header h2 {
            font-size: 2rem;
        }

        .device-grid {
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: 15px;
        }

        .brand-grid {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        }

        .step-label {
            font-size: 0.75rem;
        }

        .progress-step {
            flex: 1;
        }

        .form-buttons {
            flex-direction: column;
        }

        .btn-prev {
            order: 2;
        }

        .btn-next {
            order: 1;
        }
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 24px;
        line-height: 1.2;
        color: #1a1a1a;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        margin-bottom: 40px;
        color: #555;
        font-weight: 400;
        line-height: 1.6;
    }

    .hero-buttons {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .btn-modern {
        padding: 16px 40px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        border: 2px solid transparent;
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-secondary-modern {
        background: white;
        color: #667eea;
        border-color: #667eea;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .btn-secondary-modern:hover {
        background: #667eea;
        color: white;
    }

    .hero-image-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero-image {
        width: 100%;
        height: auto;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        object-fit: cover;
        max-height: 600px;
    }

    .hero-image-placeholder {
        width: 100%;
        height: 500px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        position: relative;
        overflow: hidden;
    }

    .hero-image-placeholder::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100" fill="none"/><circle cx="50" cy="50" r="40" fill="white" opacity="0.1"/></svg>');
        background-size: 100px 100px;
    }

    .hero-image-placeholder i {
        font-size: 8rem;
        color: white;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }

    /* Decorative elements */
    .hero-decoration {
        position: absolute;
        border-radius: 50%;
        opacity: 0.1;
        z-index: 0;
    }

    .hero-decoration-1 {
        width: 300px;
        height: 300px;
        background: #667eea;
        top: 10%;
        right: -100px;
    }

    .hero-decoration-2 {
        width: 200px;
        height: 200px;
        background: #764ba2;
        bottom: 10%;
        left: -50px;
    }

    @media (max-width: 768px) {
        .modern-hero {
            min-height: auto;
        }

        .hero-container {
            grid-template-columns: 1fr;
            gap: 40px;
            padding: 40px 20px;
            min-height: auto;
        }

        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .hero-image-placeholder {
            height: 350px;
        }

        .hero-image-placeholder i {
            font-size: 5rem;
        }
    }

    /* Features Section */
    .features-section {
        padding: 100px 0;
        background: white;
    }

    /* Info Cards Section */
    .info-cards-section {
        padding: 100px 0;
        background: white;
    }

    .info-cards-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 40px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: center;
    }

    .info-cards-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .info-card {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        height: 280px;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .info-card-large {
        grid-row: span 2;
        height: 580px;
    }

    .info-card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .info-card-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 30px;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 50%, transparent 100%);
        color: white;
    }

    .info-card-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.3;
    }

    .info-content h2 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 24px;
        color: #1a1a1a;
        line-height: 1.2;
    }

    .info-content p {
        font-size: 1.1rem;
        color: #555;
        line-height: 1.8;
        margin-bottom: 30px;
    }

    .info-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .info-btn {
        padding: 14px 32px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .info-btn-primary {
        background: #7c3aed;
        color: white;
        border: 2px solid #7c3aed;
    }

    .info-btn-primary:hover {
        background: #6d28d9;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(124, 58, 237, 0.3);
        color: white;
    }

    .info-btn-secondary {
        background: #f3e8ff;
        color: #7c3aed;
        border: 2px solid #f3e8ff;
    }

    .info-btn-secondary:hover {
        background: #e9d5ff;
        transform: translateY(-2px);
        color: #6d28d9;
    }

    @media (max-width: 992px) {
        .info-cards-container {
            grid-template-columns: 1fr;
            gap: 50px;
            padding: 0 20px;
        }

        .info-cards-grid {
            grid-template-columns: 1fr;
        }

        .info-card-large {
            grid-row: span 1;
            height: 280px;
        }

        .info-content h2 {
            font-size: 2rem;
        }
    }

    .section-title {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 60px;
        color: var(--dark-color);
    }

    .features-grid {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
    }

    .feature-card {
        background: white;
        padding: 40px 30px;
        border-radius: 16px;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        border-color: #667eea;
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 24px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: white;
    }

    .feature-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 16px;
        color: var(--dark-color);
    }

    .feature-description {
        color: #666;
        line-height: 1.8;
    }

    /* Services Section */
    .services-section {
        padding: 100px 0;
        background: var(--light-gray);
    }

    .services-grid {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }

    .service-item {
        background: white;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .service-item:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .service-icon {
        font-size: 3rem;
        margin-bottom: 20px;
        color: #667eea;
    }

    .service-name {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--dark-color);
    }

    /* Why Choose Us Section */
    .why-choose-section {
        padding: 100px 0;
        background: white;
    }

    .why-choose-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
    }

    .why-choose-image {
        width: 100%;
        height: 500px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: white;
    }

    .why-choose-list {
        list-style: none;
    }

    .why-choose-item {
        margin-bottom: 30px;
        display: flex;
        align-items: start;
        gap: 20px;
    }

    .check-icon {
        width: 30px;
        height: 30px;
        background: #667eea;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
        margin-top: 5px;
    }

    .why-choose-text h3 {
        font-size: 1.3rem;
        margin-bottom: 8px;
        color: var(--dark-color);
    }

    .why-choose-text p {
        color: #666;
        line-height: 1.6;
    }

    /* Testimonials Section */
    .testimonials-section {
        padding: 100px 0;
        background: var(--light-gray);
    }

    .testimonials-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
    }

    .testimonial-card {
        background: white;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .testimonial-rating {
        color: #ffa500;
        font-size: 1.2rem;
        margin-bottom: 20px;
    }

    .testimonial-text {
        font-style: italic;
        color: #555;
        margin-bottom: 24px;
        line-height: 1.8;
    }

    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .author-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
    }

    .author-info h4 {
        font-size: 1rem;
        margin-bottom: 4px;
        color: var(--dark-color);
    }

    .author-info p {
        font-size: 0.9rem;
        color: #888;
    }

    /* CTA Section */
    .cta-section {
        padding: 100px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-align: center;
    }

    /* Tech Repair Section */
    .tech-repair-section {
        padding: 80px 0;
        background: white;
    }

    .tech-repair-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .tech-repair-header h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .tech-repair-banner {
        max-width: 1000px;
        margin: 0 auto 60px;
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }

    .tech-repair-image {
        width: 100%;
        height: auto;
        display: block;
    }

    .tech-repair-content {
        text-align: center;
        max-width: 900px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .tech-repair-content h3 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
    }

    .tech-repair-content p {
        font-size: 1.1rem;
        color: #555;
        line-height: 1.8;
        margin-bottom: 0;
    }

    .tech-repair-content a {
        color: #7c3aed;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .tech-repair-content a:hover {
        color: #6d28d9;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .tech-repair-header h2 {
            font-size: 1.5rem;
        }

        .tech-repair-content h3 {
            font-size: 1.5rem;
        }

        .tech-repair-content p {
            font-size: 1rem;
        }

        .tech-repair-section {
            padding: 60px 0;
        }
    }

    .cta-content {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .cta-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 24px;
    }

    .cta-description {
        font-size: 1.3rem;
        margin-bottom: 40px;
        opacity: 0.95;
    }

    /* Stats Section */
    .stats-section {
        padding: 80px 0;
        background: white;
    }

    .stats-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 40px;
        text-align: center;
    }

    .stat-item {
        padding: 20px;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        color: #667eea;
        margin-bottom: 10px;
    }

    .stat-label {
        font-size: 1.1rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .why-choose-content {
            grid-template-columns: 1fr;
        }

        .cta-title {
            font-size: 2rem;
        }

        .features-grid,
        .services-grid,
        .testimonials-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Modern Navbar -->
<nav class="modern-navbar">
    <div class="navbar-container">
        <div class="navbar-logo">
            <a href="{{url('/')}}">
                <img src="{{$__logo_url ?? asset('images/logo.png')}}" alt="Logo">
            </a>
        </div>

        <ul class="navbar-menu">
            @if(count($__seperate_menu_pages ?? []) > 0)
                @foreach($__seperate_menu_pages as $s_page)
                    <li>
                        <a href="{{ action([\Modules\Cms\Http\Controllers\CmsPageController::class, 'showPage'], ['page' => $s_page->slug]) }}" class="navbar-link">
                            {{$s_page->title}}
                        </a>
                    </li>
                @endforeach
            @endif

            @if(count($__pages ?? []) > 0)
                <li class="navbar-dropdown">
                    <div class="navbar-dropdown-toggle">
                        <span>{{$navbar_btn['drop_down_text'] ?? 'Pages'}}</span>
                        <svg width="10" height="10" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </div>
                    <div class="navbar-dropdown-menu">
                        @foreach($__pages as $page)
                            <a href="{{ action([\Modules\Cms\Http\Controllers\CmsPageController::class, 'showPage'], ['page' => $page->slug]) }}">
                                {{$page->title}}
                            </a>
                        @endforeach
                    </div>
                </li>
            @endif

            @if(($__blog_count ?? 0) >= 1)
                <li>
                    <a href="{{action([\Modules\Cms\Http\Controllers\CmsController::class, 'getBlogList'])}}" class="navbar-link">
                        {{__('cms::lang.blogs')}}
                    </a>
                </li>
            @endif

            @if(Route::has('pricing') && config('app.env') != 'demo')
                <li>
                    <a href="{{ action([\Modules\Superadmin\Http\Controllers\PricingController::class, 'index']) }}" class="navbar-link">
                        @lang('superadmin::lang.pricing')
                    </a>
                </li>
            @endif

            <li>
                <a href="{{route('cms.contact.us')}}" class="navbar-link">Contact Us</a>
            </li>
        </ul>

        <div class="navbar-actions">
            @if (Route::has('login'))
                @auth
                    <a href="{{ route('home') }}" class="navbar-link">
                        @lang('cms::lang.dashboard')
                    </a>
                @else
                    <a href="{{ route('login') }}" class="navbar-link">
                        @lang('lang_v1.login')
                    </a>
                @endauth
            @endif

            <a href="{{url('/')}}" class="navbar-btn-outline">
                Find Your Store
            </a>

            <a href="{{ $navbar_btn['link'] ?? route('business.getRegister') }}" class="navbar-btn">
                {{$navbar_btn['text'] ?? 'Start a Repair'}}
            </a>
        </div>

        <button class="mobile-menu-toggle" id="mobileMenuToggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
    <ul>
        @if(count($__seperate_menu_pages ?? []) > 0)
            @foreach($__seperate_menu_pages as $s_page)
                <li>
                    <a href="{{ action([\Modules\Cms\Http\Controllers\CmsPageController::class, 'showPage'], ['page' => $s_page->slug]) }}">
                        {{$s_page->title}}
                    </a>
                </li>
            @endforeach
        @endif

        @if(count($__pages ?? []) > 0)
            <li>
                <div class="mobile-dropdown-toggle" onclick="toggleMobileDropdown(this)">
                    <span>{{$navbar_btn['drop_down_text'] ?? 'Pages'}}</span>
                    <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </div>
                <div class="mobile-dropdown-menu">
                    @foreach($__pages as $page)
                        <a href="{{ action([\Modules\Cms\Http\Controllers\CmsPageController::class, 'showPage'], ['page' => $page->slug]) }}">
                            {{$page->title}}
                        </a>
                    @endforeach
                </div>
            </li>
        @endif

        @if(($__blog_count ?? 0) >= 1)
            <li>
                <a href="{{action([\Modules\Cms\Http\Controllers\CmsController::class, 'getBlogList'])}}">
                    {{__('cms::lang.blogs')}}
                </a>
            </li>
        @endif

        @if(Route::has('pricing') && config('app.env') != 'demo')
            <li>
                <a href="{{ action([\Modules\Superadmin\Http\Controllers\PricingController::class, 'index']) }}">
                    @lang('superadmin::lang.pricing')
                </a>
            </li>
        @endif

        <li>
            <a href="{{route('cms.contact.us')}}">Contact Us</a>
        </li>

        @if (Route::has('login'))
            @auth
                <li>
                    <a href="{{ route('home') }}">
                        @lang('cms::lang.dashboard')
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('login') }}">
                        @lang('lang_v1.login')
                    </a>
                </li>
            @endauth
        @endif
    </ul>

    <div class="mobile-navbar-actions">
        <a href="{{url('/')}}" class="navbar-btn-outline" style="text-align: center;">
            Find Your Store
        </a>
        <a href="{{ $navbar_btn['link'] ?? route('business.getRegister') }}" class="navbar-btn" style="text-align: center;">
            {{$navbar_btn['text'] ?? 'Start a Repair'}}
        </a>
    </div>
</div>

<!-- Hero Section -->
<section class="modern-hero">
    <div class="hero-decoration hero-decoration-1"></div>
    <div class="hero-decoration hero-decoration-2"></div>
    
    <div class="hero-container">
        <div class="hero-content">
            <h1 class="hero-title">Transform Your Business with Smart ERP Solutions</h1>
            <p class="hero-subtitle">Streamline operations, boost productivity, and grow faster with our all-in-one business management platform designed for modern businesses.</p>
            <div class="hero-buttons">
                <a href="{{ route('business.getRegister') }}" class="btn-modern btn-primary-modern">Start Free Trial</a>
                <a href="#features" class="btn-modern btn-secondary-modern">Learn More</a>
            </div>
        </div>
        
        <div class="hero-image-wrapper">
            <!-- Replace the src below with your actual image URL -->
            <img src="https://images.ctfassets.net/d9ybqgejqp0w/3pkJ055RnTXxptyAhp4oZy/c1dc10d10094b8214af1d943634bd6b5/ubif-homepage-hero-desktop.png?w=1024&h=669&q=50&fm=webp" 
                 alt="Business Management" 
                 class="hero-image"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="hero-image-placeholder" style="display: none;">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
</section>

<!-- Multistep Form Section -->
<section class="multistep-form-section">
    <div class="multistep-form-container">
        <div class="form-header">
            <h2>Book Your Repair in 4 Easy Steps</h2>
            <p>Fast, reliable repairs for all your devices</p>
        </div>

        <div class="multistep-form-wrapper">
            <!-- Progress Bar -->
            <div class="progress-bar">
                <div class="progress-step active" data-step="1">
                    <div class="step-circle">1</div>
                    <span class="step-label">Select device</span>
                </div>
                <div class="step-connector"></div>
                <div class="progress-step" data-step="2">
                    <div class="step-circle">2</div>
                    <span class="step-label">Select repair</span>
                </div>
                <div class="step-connector"></div>
                <div class="progress-step" data-step="3">
                    <div class="step-circle">3</div>
                    <span class="step-label">Finalize order</span>
                </div>
            </div>

            <!-- Form Steps -->
            <form id="repairForm" action="{{ route('cms.contact.us') }}" method="GET">
                <!-- Step 1: Device Selection -->
                <div class="form-step active" data-step="1">
                    <h3 class="step-title">Which model do you have?</h3>
                    <p style="color: #666; margin-bottom: 25px;">Type in your <strong>brand, model</strong> or <strong>model code</strong>.</p>
                    
                    <div style="position: relative; margin-bottom: 20px;">
                        <div class="search-input-wrapper" style="margin-bottom: 10px;">
                            <input type="text" 
                                   class="form-input" 
                                   id="modelSearch" 
                                   placeholder="iPhone 16 Pro Max"
                                   style="padding-right: 50px;">
                            <i class="fas fa-search" style="position: absolute; right: 20px; top: 18px; color: #0088FF; font-size: 1.2rem;"></i>
                        </div>
                        <div style="text-align: right;">
                            <button type="button" style="background: none; border: none; color: #0088FF; font-size: 0.9rem; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; padding: 5px;">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Find my model</span>
                            </button>
                        </div>
                    </div>

                    <div style="text-align: center; margin-bottom: 25px;">
                        <span style="color: #999; font-size: 0.95rem;">Or select your type</span>
                    </div>

                    <div class="device-grid">
                        <div class="device-option" data-device="smartphone">
                            <div class="device-icon">📱</div>
                            <div class="device-name">SMARTPHONE</div>
                        </div>
                        <div class="device-option" data-device="tablet">
                            <div class="device-icon">📱</div>
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
                            <div class="device-icon">🎮</div>
                            <div class="device-name">CONSOLE</div>
                        </div>
                    </div>
                    <div class="form-buttons">
                        <button type="button" class="form-btn btn-next" id="step1Next" disabled>Next</button>
                    </div>
                </div>

                <!-- Step 2: Brand & Issue Selection -->
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
                                <div class="issue-title">Speaker/Audio</div>
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
                    
                    <div class="summary-card">
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

                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" class="form-input" id="fullName" name="name" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" class="form-input" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number *</label>
                        <input type="tel" class="form-input" id="phone" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Additional Details (Optional)</label>
                        <textarea class="form-input form-textarea" id="details" name="message" placeholder="Tell us more about the issue..."></textarea>
                    </div>

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
    </div>
</section>

<!-- Info Cards Section -->
<section class="info-cards-section">
    <div class="info-cards-container">
        <!-- Image Cards Grid -->
        <div class="info-cards-grid">
            <!-- Large Card -->
            <div class="info-card info-card-large">
                <img src="https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?w=600&h=800&fit=crop" 
                     alt="Same-day repairs" 
                     class="info-card-image"
                     onerror="this.src='https://via.placeholder.com/600x800/7c3aed/ffffff?text=Same-day+repairs'">
                <div class="info-card-overlay">
                    <h3 class="info-card-title">Same-day<br>repairs</h3>
                </div>
            </div>

            <!-- Small Card 1 -->
            <div class="info-card">
                <img src="https://images.unsplash.com/photo-1556742031-c6961e8560b0?w=400&h=300&fit=crop" 
                     alt="700+ stores nationwide" 
                     class="info-card-image"
                     onerror="this.src='https://via.placeholder.com/400x300/6d28d9/ffffff?text=Stores+Nationwide'">
                <div class="info-card-overlay">
                    <h3 class="info-card-title">700+ stores<br>nationwide</h3>
                </div>
            </div>

            <!-- Small Card 2 -->
            <div class="info-card">
                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=300&fit=crop" 
                     alt="Low price guarantee" 
                     class="info-card-image"
                     onerror="this.src='https://via.placeholder.com/400x300/9333ea/ffffff?text=Low+Price+Guarantee'">
                <div class="info-card-overlay">
                    <h3 class="info-card-title">Low price<br>guarantee</h3>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="info-content">
            <h2>Your tech is in good hands</h2>
            <p>Our local experts have completed 21 million+ repairs, and they can help you too, whether you need a fix, setup, accessories, or even a cleaning for your phone or game console.</p>
            <div class="info-buttons">
                <a href="{{url('/')}}" class="info-btn info-btn-primary">Find a store</a>
                <a href="{{ route('business.getRegister') }}" class="info-btn info-btn-secondary">Start a repair</a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section" id="features">
    <h2 class="section-title">Powerful Features for Your Business</h2>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3 class="feature-title">Advanced Analytics</h3>
            <p class="feature-description">Get real-time insights into your business performance with comprehensive analytics and reporting tools.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-inventory"></i>
            </div>
            <h3 class="feature-title">Inventory Management</h3>
            <p class="feature-description">Track stock levels, manage warehouses, and automate reordering with our intelligent inventory system.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="feature-title">CRM & Sales</h3>
            <p class="feature-description">Manage customer relationships, track leads, and close deals faster with integrated CRM tools.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-calculator"></i>
            </div>
            <h3 class="feature-title">Accounting</h3>
            <p class="feature-description">Comprehensive financial management with invoicing, expense tracking, and automated reporting.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <h3 class="feature-title">Mobile Access</h3>
            <p class="feature-description">Manage your business on-the-go with our fully responsive mobile-friendly interface.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3 class="feature-title">Secure & Reliable</h3>
            <p class="feature-description">Enterprise-grade security with data encryption, backups, and 99.9% uptime guarantee.</p>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services-section">
    <h2 class="section-title">Industry Solutions</h2>
    <div class="services-grid">
        <div class="service-item">
            <div class="service-icon">🏪</div>
            <h3 class="service-name">Retail</h3>
        </div>
        <div class="service-item">
            <div class="service-icon">🏭</div>
            <h3 class="service-name">Manufacturing</h3>
        </div>
        <div class="service-item">
            <div class="service-icon">🍽️</div>
            <h3 class="service-name">Restaurant</h3>
        </div>
        <div class="service-item">
            <div class="service-icon">💼</div>
            <h3 class="service-name">Wholesale</h3>
        </div>
        <div class="service-item">
            <div class="service-icon">🏥</div>
            <h3 class="service-name">Healthcare</h3>
        </div>
        <div class="service-item">
            <div class="service-icon">📦</div>
            <h3 class="service-name">E-commerce</h3>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="why-choose-section">
    <div class="why-choose-content">
        <div class="why-choose-image">
            <i class="fas fa-rocket"></i>
        </div>
        <div>
            <h2 class="section-title" style="text-align: left; margin-bottom: 40px;">Why Choose Our ERP?</h2>
            <ul class="why-choose-list">
                <li class="why-choose-item">
                    <div class="check-icon">✓</div>
                    <div class="why-choose-text">
                        <h3>Easy Implementation</h3>
                        <p>Get started in minutes with our intuitive setup wizard and guided onboarding process.</p>
                    </div>
                </li>
                <li class="why-choose-item">
                    <div class="check-icon">✓</div>
                    <div class="why-choose-text">
                        <h3>Scalable Solution</h3>
                        <p>Grow your business without limits. Our platform scales with your needs from startup to enterprise.</p>
                    </div>
                </li>
                <li class="why-choose-item">
                    <div class="check-icon">✓</div>
                    <div class="why-choose-text">
                        <h3>24/7 Support</h3>
                        <p>Expert support team available around the clock to help you succeed.</p>
                    </div>
                </li>
                <li class="why-choose-item">
                    <div class="check-icon">✓</div>
                    <div class="why-choose-text">
                        <h3>Cost Effective</h3>
                        <p>Affordable pricing plans with no hidden fees. Get enterprise features at small business prices.</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="stats-container">
        <div class="stat-item">
            <div class="stat-number">10K+</div>
            <div class="stat-label">Active Users</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">50+</div>
            <div class="stat-label">Countries</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">99.9%</div>
            <div class="stat-label">Uptime</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">4.9/5</div>
            <div class="stat-label">Rating</div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section">
    <h2 class="section-title">What Our Customers Say</h2>
    <div class="testimonials-container">
        <div class="testimonial-card">
            <div class="testimonial-rating">★★★★★</div>
            <p class="testimonial-text">"This ERP system has completely transformed how we manage our business. The inventory management alone has saved us countless hours and reduced errors significantly."</p>
            <div class="testimonial-author">
                <div class="author-avatar">JD</div>
                <div class="author-info">
                    <h4>John Doe</h4>
                    <p>CEO, Retail Solutions Inc.</p>
                </div>
            </div>
        </div>

        <div class="testimonial-card">
            <div class="testimonial-rating">★★★★★</div>
            <p class="testimonial-text">"Outstanding support team and incredible features. We've seen a 40% increase in productivity since implementing this system. Highly recommended!"</p>
            <div class="testimonial-author">
                <div class="author-avatar">SS</div>
                <div class="author-info">
                    <h4>Sarah Smith</h4>
                    <p>Operations Manager, TechCorp</p>
                </div>
            </div>
        </div>

        <div class="testimonial-card">
            <div class="testimonial-rating">★★★★★</div>
            <p class="testimonial-text">"The best investment we've made for our business. Easy to use, powerful features, and excellent value for money. Our entire team loves it!"</p>
            <div class="testimonial-author">
                <div class="author-avatar">MJ</div>
                <div class="author-info">
                    <h4>Michael Johnson</h4>
                    <p>Founder, StartUp Hub</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tech Repair Section -->
<section class="tech-repair-section">
    <div class="tech-repair-header">
        <h2>Tech repair done right</h2>
    </div>

    <div class="tech-repair-banner">
        <img src="https://images.unsplash.com/photo-1556656793-08538906a9f8?w=1200&h=600&fit=crop" 
             alt="Tech repair you can count on" 
             class="tech-repair-image"
             onerror="this.src='https://via.placeholder.com/1200x600/e5e7eb/1a1a1a?text=Tech+repair+you+can+count+on'">
    </div>

    <div class="tech-repair-content">
        <h3>How can we help you today?</h3>
        <p>No matter the device or the issue, our experts at <strong>uBreakiFix® by Asurion</strong> can help get you back up and running fast. Here's more information on our <a href="{{route('cms.contact.us')}}">extensive repair services</a>.</p>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="cta-content">
        <h2 class="cta-title">Ready to Transform Your Business?</h2>
        <p class="cta-description">Join thousands of businesses that trust our ERP solution. Start your free trial today - no credit card required!</p>
        <a href="{{ route('business.getRegister') }}" class="btn-modern btn-primary-modern">Get Started Free</a>
    </div>
</section>

@endsection

@section('javascript')
<script type="text/javascript">
    // Mobile menu toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenu = document.getElementById('mobileMenu');

    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('active');
            const icon = this.querySelector('i');
            if (mobileMenu.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }

    // Mobile dropdown toggle
    function toggleMobileDropdown(element) {
        const dropdownMenu = element.nextElementSibling;
        dropdownMenu.classList.toggle('active');
        const svg = element.querySelector('svg');
        if (dropdownMenu.classList.contains('active')) {
            svg.style.transform = 'rotate(180deg)';
        } else {
            svg.style.transform = 'rotate(0deg)';
        }
    }

    // Navbar scroll effect
    const navbar = document.querySelector('.modern-navbar');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                // Close mobile menu if open
                if (mobileMenu.classList.contains('active')) {
                    mobileMenu.classList.remove('active');
                    const icon = mobileMenuToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
                
                // Scroll to target
                const navbarHeight = navbar.offsetHeight;
                const targetPosition = target.offsetTop - navbarHeight;
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Add animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.feature-card, .service-item, .testimonial-card, .stat-item').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

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
                formData.device = 'search'; // Mark as search input
                document.getElementById('step1Next').disabled = false;
                // Deselect any selected device options
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
            formData.model = ''; // Clear search model if device is selected
            if (modelSearchInput) {
                modelSearchInput.value = ''; // Clear search input
            }
            document.getElementById('step1Next').disabled = false;
        });
    });

    // Brand Selection
    document.querySelectorAll('.brand-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.brand-option').forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            formData.brand = this.dataset.brand;
            checkStep2Complete();
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
        document.querySelectorAll('.step-circle').forEach((circle, index) => {
            const stepNum = index + 1;
            circle.classList.remove('active', 'completed');
            
            if (stepNum === currentStep) {
                circle.classList.add('active');
            } else if (stepNum < currentStep) {
                circle.classList.add('completed');
            }
        });

        // Update connectors
        document.querySelectorAll('.step-connector').forEach((connector, index) => {
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
            
            // Add model to hidden field if searched
            if (formData.model) {
                let modelInput = document.getElementById('hiddenModel');
                if (!modelInput) {
                    modelInput = document.createElement('input');
                    modelInput.type = 'hidden';
                    modelInput.name = 'model';
                    modelInput.id = 'hiddenModel';
                    document.getElementById('repairForm').appendChild(modelInput);
                }
                modelInput.value = formData.model;
            }
        }

        // Scroll to form
        document.querySelector('.multistep-form-wrapper').scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
    }

    function capitalizeFirst(str) {
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

    // Form submission
    document.getElementById('repairForm').addEventListener('submit', function(e) {
        const name = document.getElementById('fullName').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();

        if (!name || !email || !phone) {
            e.preventDefault();
            alert('Please fill in all required fields');
            return false;
        }
    });

</script>
@endsection