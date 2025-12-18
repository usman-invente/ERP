<!------------------------------>
<!--Footer---------------->
<!------------------------------>
<style>
    .modern-footer {
        background: #2d2d2d;
        color: #e0e0e0;
        padding: 60px 0 30px;
    }

    .modern-footer a {
        color: #e0e0e0;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .modern-footer a:hover {
        color: #7c3aed;
    }

    .footer-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 40px;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 40px;
        margin-bottom: 40px;
    }

    .footer-column h3 {
        font-size: 1rem;
        font-weight: 700;
        color: white;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .footer-column ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-column ul li {
        margin-bottom: 12px;
    }

    .footer-column ul li a {
        font-size: 0.95rem;
        display: inline-block;
    }

    .footer-contact {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .footer-contact-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        border: 2px solid #e0e0e0;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        justify-content: center;
    }

    .footer-contact-btn:hover {
        background: white;
        color: #2d2d2d;
        border-color: white;
    }

    .footer-bottom {
        border-top: 1px solid #444;
        padding-top: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .footer-links {
        display: flex;
        gap: 25px;
        flex-wrap: wrap;
    }

    .footer-links a {
        font-size: 0.9rem;
        text-decoration: underline;
    }

    .footer-social {
        display: flex;
        gap: 20px;
        align-items: center;
    }

    .footer-trust-badges {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .trust-badge {
        background: white;
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 700;
        color: #2d2d2d;
    }

    .footer-social a {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #e0e0e0;
        border-radius: 50%;
        font-size: 1.2rem;
        transition: all 0.2s ease;
    }

    .footer-social a:hover {
        background: white;
        color: #2d2d2d;
        border-color: white;
    }

    .footer-copyright {
        width: 100%;
        text-align: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #444;
        font-size: 0.85rem;
        color: #999;
        line-height: 1.6;
    }

    @media (max-width: 1200px) {
        .footer-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .footer-container {
            padding: 0 20px;
        }

        .footer-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        .footer-bottom {
            flex-direction: column;
            text-align: center;
        }

        .footer-links {
            justify-content: center;
        }

        .footer-social {
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .footer-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<footer class="modern-footer">
    <div class="footer-container">
        <div class="footer-grid">
            <!-- Repairs Column -->
            <div class="footer-column">
                <h3>Repairs</h3>
                <ul>
                    <li><a href="#">iPhone repair</a></li>
                    <li><a href="#">Samsung repair</a></li>
                    <li><a href="#">Google repair</a></li>
                    <li><a href="#">Cell phone repair</a></li>
                    <li><a href="#">Tablet repair</a></li>
                    <li><a href="#">Computer repair</a></li>
                    <li><a href="#">Game console repair</a></li>
                    <li><a href="#">All repairs</a></li>
                </ul>
            </div>

            <!-- About Column -->
            <div class="footer-column">
                <h3>About {{config('app.name', 'uBreakiFix')}}</h3>
                <ul>
                    @if(Route::has('pricing') && config('app.env') != 'demo')
                        <li><a href="{{ action([\Modules\Superadmin\Http\Controllers\PricingController::class, 'index']) }}">Pricing</a></li>
                    @endif
                    @if(count($__pages ?? []) > 0)
                        @foreach($__pages as $page)
                            <li><a href="{{ action([\Modules\Cms\Http\Controllers\CmsPageController::class, 'showPage'], ['page' => $page->slug]) }}">{{$page->title}}</a></li>
                        @endforeach
                    @endif
                    @if(($__blog_count ?? 0) >= 1)
                        <li><a href="{{action([\Modules\Cms\Http\Controllers\CmsController::class, 'getBlogList'])}}">{{__('cms::lang.blogs')}}</a></li>
                    @endif
                </ul>
            </div>

            <!-- Support Column -->
            <div class="footer-column">
                <h3>Support</h3>
                <ul>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Track repair</a></li>
                    <li><a href="{{route('cms.contact.us')}}">Contact Us</a></li>
                    @if (Route::has('login'))
                        @auth
                            <li><a href="{{ route('home') }}">@lang('cms::lang.dashboard')</a></li>
                        @else
                            <li><a href="{{ route('login') }}">@lang('lang_v1.login')</a></li>
                        @endauth
                    @endif
                </ul>
            </div>

            <!-- Shop Column -->
            <div class="footer-column">
                <h3>Shop</h3>
                <ul>
                    <li><a href="#">Cases</a></li>
                    <li><a href="#">Screen protectors</a></li>
                    <li><a href="#">Power</a></li>
                    <li><a href="#">Audio</a></li>
                    <li><a href="#">iPhone accessories</a></li>
                    <li><a href="#">Samsung accessories</a></li>
                    <li><a href="#">Google accessories</a></li>
                    <li><a href="#">All accessories</a></li>
                </ul>
            </div>

            <!-- Contact Column -->
            <div class="footer-column">
                <h3>Contact Information</h3>
                <div class="footer-contact">
                    <a href="tel:877.320.2237" class="footer-contact-btn">
                        <i class="fas fa-phone"></i>
                        877.320.2237
                    </a>
                    <a href="{{route('cms.contact.us')}}" class="footer-contact-btn">
                        <i class="fas fa-envelope"></i>
                        Contact us
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-links">
                <a href="#">Repair Terms of Service</a>
                <a href="#">Website Terms of Use</a>
                <a href="#">Privacy Notice</a>
                <a href="#">Cookie Preferences</a>
                <a href="#">Sitemap</a>
            </div>

            <div class="footer-trust-badges">
                <div class="trust-badge">
                    <i class="fas fa-shield-alt"></i> TRUST
                </div>
                <div class="trust-badge">
                    <i class="fas fa-shield-alt"></i> TRUST
                </div>
            </div>

            @if(isset($__site_details['follow_us']) && !empty($__site_details['follow_us']))
                <div class="footer-social">
                    @foreach($__site_details['follow_us'] as $key => $follow_us)
                        @if($key == 'instagram' && !empty($follow_us))
                            <a href="{{$follow_us}}" target="_blank" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                        @if($key == 'twitter' && !empty($follow_us))
                            <a href="{{$follow_us}}" target="_blank" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                        @endif
                        @if($key == 'facebook' && !empty($follow_us))
                            <a href="{{$follow_us}}" target="_blank" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif
                        @if($key == 'youtube' && !empty($follow_us))
                            <a href="{{$follow_us}}" target="_blank" title="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                        @endif
                        @if($key == 'linkedin' && !empty($follow_us))
                            <a href="{{$follow_us}}" target="_blank" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Copyright -->
        <div class="footer-copyright">
            <p>The Asurion® and uBreakiFix® trademarks and logos are the property of Asurion, LLC and uBreakiFix Co. respectively. All rights reserved. All other trademarks are the property of their respective owners.</p>
            <p>&copy; {{ date('Y')}} {{config('app.name', 'ultimatePOS')}}. All Rights Reserved.</p>
        </div>
    </div>
</footer>
