<footer class="footer-container">
    @php
        $regionCode = request()->route('region') ?? 'us';
    @endphp
    <a href="#header" class="scroll-to-top"><i class="fa fa-angle-up" aria-label="Scroll to top"></i>
    </a>
        <!-- Header Section -->
        <div class="footer-header">
            <h2>{{ __('Get Best Deals and Coupons With Only PocketThrift') }}</h2>
            <div class="triangle-separator"></div>
        </div>

        <!-- Main Footer Content -->
        <div class="footer-content">
            <!-- Column 1: Logo and Description -->
            <div class="footer-column">
                <div class="logo-section">
                     <a class="navbar-brand text-white" href="{{ ($regionCode === 'us') ? route('home') : route('region.home', $regionCode) }}">
            <img src="{{ asset('uploads/logo.png') }}" alt="{{ config('app.name') }}" class="img-fluid" style="max-height: 50px; max-width: 180px; object-fit: contain;" width="180" height="50" loading="lazy">
        </a>
                </div>
                <p class="logo-description">{{ __('Get Top Deals and Exclusive Discounts on your favorite brands in the') }}
                     @if(request()->route('region'))
        {{ optional(\App\Models\Region::where('code', request()->route('region'))->where('active', true)->first())->country ?? strtoupper(request()->route('region')) }}
      @else
        USA
      @endif
                    {{ __('Save more with our curated offers and promo codes for a smarter shopping experience.') }}</p>
                {{-- Social icons (commented out in original) --}}
                <div class="mt-3">
                    @include('layouts.components.region-dropdown', ['usUrl' => route('home')])
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="footer-column">
                <h3>{{ __('Quick Links') }}</h3>
                <ul>
                    <li><a href="{{ ($regionCode === 'us') ? route('aboutUs') : route('region.aboutUs', $regionCode) }}">{{ __('About Us') }}</a></li>
                    <li><a href="{{ ($regionCode === 'us') ? route('contactUs') : route('region.contactUs', $regionCode) }}">{{ __('Contact Us') }}</a></li>
                    <li><a href="{{ ($regionCode === 'us') ? route('privacyPolicy') : route('region.privacyPolicy', $regionCode) }}">{{ __('Privacy Policy') }}</a></li>
                    <li><a href="{{ ($regionCode === 'us') ? route('affiliateDisclaimer') : route('region.affiliateDisclaimer', $regionCode) }}">{{ __('Affiliate Disclaimer') }}</a></li>
                    <li><a href="{{ ($regionCode === 'us') ? route('imprint') : route('region.imprint', $regionCode) }}">{{ __('Imprint') }}</a></li>
                    <li><a href="{{ ($regionCode === 'us') ? route('termsofUse') : route('region.termsofUse', $regionCode) }}">{{ __('Terms of Use') }}</a></li>
                    <li><a href="{{ ($regionCode === 'us') ? route('allRegions') : route('region.allRegions', $regionCode) }}">{{ __('All Regions') }}</a></li>
                </ul>
            </div>

            <!-- Column 3: Explore Our Site -->
            <div class="footer-column">
                <h3>{{ __('Explore Our Site') }}</h3>
                <ul>
                    <li><a href="{{ ($regionCode === 'us') ? route('categories') : route('region.categories', $regionCode) }}">{{ __('Categories') }}</a></li>
                    <li><a href="{{ ($regionCode === 'us') ? route('stores') : route('region.stores', $regionCode) }}">{{ __('Stores') }}</a></li>
                    <li><a href="{{ ($regionCode === 'us') ? route('blogs') : route('region.blogs', $regionCode) }}">{{ __('Blogs') }}</a></li>
                </ul>
            </div>

            <!-- Column 4: Latest Discount -->
            <div class="footer-column">
                <h3>{{ __('Contact Details') }}</h3>
                <ul>
                    <li><a href="mailto:contact@pocketthrift.com ">{{ __('Email') }}: contact@pocketthrift.com </a></li>
                </ul>
                <h3 class="pt-5">{{ __('Follow Us on Social Media') }}</h3>
                <div class="social-icons">

                    <ul class="">
                        <li><a href="https://www.facebook.com/people/Pocket-Thrift/61562541071877/?mibextid=ZbWKwL" rel="noopener nofollow" target="_blank" title="Facebook">
                                <div class="">
                                    <svg fill="rgba(255,255,255,1)" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14 13.5H16.5L17.5 9.5H14V7.5C14 6.47062 14 5.5 16 5.5H17.5V2.1401C17.1743 2.09685 15.943 2 14.6429 2C11.9284 2 10 3.65686 10 6.69971V9.5H7V13.5H10V22H14V13.5Z">
                                        </path>
                                    </svg>
                                </div>
                            </a></li>
                        <li><a href="https://www.instagram.com/pocketthrift1/" rel="noopener nofollow" target="_blank" title="Instagram">
                                <div class="">
                                    <svg fill="rgba(255,255,255,1)" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.001 9C10.3436 9 9.00098 10.3431 9.00098 12C9.00098 13.6573 10.3441 15 12.001 15C13.6583 15 15.001 13.6569 15.001 12C15.001 10.3427 13.6579 9 12.001 9ZM12.001 7C14.7614 7 17.001 9.2371 17.001 12C17.001 14.7605 14.7639 17 12.001 17C9.24051 17 7.00098 14.7629 7.00098 12C7.00098 9.23953 9.23808 7 12.001 7ZM18.501 6.74915C18.501 7.43926 17.9402 7.99917 17.251 7.99917C16.5609 7.99917 16.001 7.4384 16.001 6.74915C16.001 6.0599 16.5617 5.5 17.251 5.5C17.9393 5.49913 18.501 6.0599 18.501 6.74915ZM12.001 4C9.5265 4 9.12318 4.00655 7.97227 4.0578C7.18815 4.09461 6.66253 4.20007 6.17416 4.38967C5.74016 4.55799 5.42709 4.75898 5.09352 5.09255C4.75867 5.4274 4.55804 5.73963 4.3904 6.17383C4.20036 6.66332 4.09493 7.18811 4.05878 7.97115C4.00703 9.0752 4.00098 9.46105 4.00098 12C4.00098 14.4745 4.00753 14.8778 4.05877 16.0286C4.0956 16.8124 4.2012 17.3388 4.39034 17.826C4.5591 18.2606 4.7605 18.5744 5.09246 18.9064C5.42863 19.2421 5.74179 19.4434 6.17187 19.6094C6.66619 19.8005 7.19148 19.9061 7.97212 19.9422C9.07618 19.9939 9.46203 20 12.001 20C14.4755 20 14.8788 19.9934 16.0296 19.9422C16.8117 19.9055 17.3385 19.7996 17.827 19.6106C18.2604 19.4423 18.5752 19.2402 18.9074 18.9085C19.2436 18.5718 19.4445 18.2594 19.6107 17.8283C19.8013 17.3358 19.9071 16.8098 19.9432 16.0289C19.9949 14.9248 20.001 14.5389 20.001 12C20.001 9.52552 19.9944 9.12221 19.9432 7.97137C19.9064 7.18906 19.8005 6.66149 19.6113 6.17318C19.4434 5.74038 19.2417 5.42635 18.9084 5.09255C18.573 4.75715 18.2616 4.55693 17.8271 4.38942C17.338 4.19954 16.8124 4.09396 16.0298 4.05781C14.9258 4.00605 14.5399 4 12.001 4ZM12.001 2C14.7176 2 15.0568 2.01 16.1235 2.06C17.1876 2.10917 17.9135 2.2775 18.551 2.525C19.2101 2.77917 19.7668 3.1225 20.3226 3.67833C20.8776 4.23417 21.221 4.7925 21.476 5.45C21.7226 6.08667 21.891 6.81333 21.941 7.8775C21.9885 8.94417 22.001 9.28333 22.001 12C22.001 14.7167 21.991 15.0558 21.941 16.1225C21.8918 17.1867 21.7226 17.9125 21.476 18.55C21.2218 19.2092 20.8776 19.7658 20.3226 20.3217C19.7668 20.8767 19.2076 21.22 18.551 21.475C17.9135 21.7217 17.1876 21.89 16.1235 21.94C15.0568 21.9875 14.7176 22 12.001 22C9.28431 22 8.94514 21.99 7.87848 21.94C6.81431 21.8908 6.08931 21.7217 5.45098 21.475C4.79264 21.2208 4.23514 20.8767 3.67931 20.3217C3.12348 19.7658 2.78098 19.2067 2.52598 18.55C2.27848 17.9125 2.11098 17.1867 2.06098 16.1225C2.01348 15.0558 2.00098 14.7167 2.00098 12C2.00098 9.28333 2.01098 8.94417 2.06098 7.8775C2.11014 6.8125 2.27848 6.0875 2.52598 5.45C2.78014 4.79167 3.12348 4.23417 3.67931 3.67833C4.23514 3.1225 4.79348 2.78 5.45098 2.525C6.08848 2.2775 6.81348 2.11 7.87848 2.06C8.94514 2.0125 9.28431 2 12.001 2Z">
                                        </path>
                                    </svg></div>
                            </a></li>
                        <li><a href="https://x.com/Pocketthrift" rel="noopener nofollow" target="_blank" title="Twitter-x">
                                <div class="">
                                    <svg fill="#fff" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 2H1L9.26086 13.0145L1.44995 21.9999H4.09998L10.4883 14.651L16 22H23L14.3917 10.5223L21.8001 2H19.1501L13.1643 8.88578L8 2ZM17 20L5 4H7L19 20H17Z">
                                        </path>
                                    </svg></div>
                            </a></li>
                        <li><a href="https://www.linkedin.com/in/pocket-thrift-40336131b" rel="noopener nofollow" target="_blank" title="Linkedin">
                                <div class="">
                                    <svg fill="rgba(255,255,255,1)" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.94048 4.99993C6.94011 5.81424 6.44608 6.54702 5.69134 6.85273C4.9366 7.15845 4.07187 6.97605 3.5049 6.39155C2.93793 5.80704 2.78195 4.93715 3.1105 4.19207C3.43906 3.44699 4.18654 2.9755 5.00048 2.99993C6.08155 3.03238 6.94097 3.91837 6.94048 4.99993ZM7.00048 8.47993H3.00048V20.9999H7.00048V8.47993ZM13.3205 8.47993H9.34048V20.9999H13.2805V14.4299C13.2805 10.7699 18.0505 10.4299 18.0505 14.4299V20.9999H22.0005V13.0699C22.0005 6.89993 14.9405 7.12993 13.2805 10.1599L13.3205 8.47993Z">
                                        </path>
                                    </svg></div>
                            </a></li>
                        <li><a href="https://www.pinterest.com/pocketthrift1/" rel="noopener nofollow" target="_blank" title="Pinterest">
                                <div class="">
                                    <svg fill="rgba(255,255,255,1)" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.3717 2.09442C8.42512 1.41268 3.73383 4.48505 2.38064 9.29256C1.02745 14.1001 3.42711 19.1692 8.00271 21.1689C7.94264 20.4008 7.99735 19.628 8.16502 18.8761C8.34964 18.0374 9.46121 13.4132 9.46121 13.4132C9.23971 12.9173 9.12893 12.379 9.13659 11.8359C9.13659 10.3509 9.99353 9.24295 11.0597 9.24295C11.4472 9.23718 11.8181 9.40028 12.0758 9.68981C12.3335 9.97934 12.4526 10.3667 12.402 10.751C12.402 11.6512 11.8236 13.0131 11.5228 14.2903C11.4014 14.7656 11.5131 15.2703 11.8237 15.65C12.1343 16.0296 12.6069 16.2389 13.0967 16.2139C14.9944 16.2139 16.2675 13.7825 16.2675 10.9126C16.2675 8.71205 14.8098 7.0655 12.1243 7.0655C10.826 7.01531 9.56388 7.4996 8.63223 8.40543C7.70057 9.31126 7.18084 10.5595 7.19423 11.859C7.16563 12.5722 7.39566 13.2717 7.84194 13.8287C8.01361 13.9564 8.07985 14.1825 8.00425 14.3827C7.9581 14.5673 7.84194 15.0059 7.79578 15.1675C7.77632 15.278 7.70559 15.3728 7.60516 15.4228C7.50473 15.4729 7.38651 15.4724 7.28654 15.4214C5.9019 14.8674 5.24957 13.3439 5.24957 11.6051C5.24957 8.75822 7.63424 5.3497 12.4036 5.3497C16.1998 5.3497 18.723 8.1273 18.723 11.0972C18.723 15.0059 16.5468 17.9451 13.3298 17.9451C12.3526 17.9761 11.4273 17.5061 10.8759 16.6986C10.8759 16.6986 10.2974 19.0146 10.1835 19.4531C9.95101 20.2099 9.60779 20.9281 9.16505 21.5844C10.0877 21.8643 11.0471 22.0044 12.0113 22C14.6636 22.0017 17.2078 20.9484 19.0829 19.072C20.958 17.1957 22.0099 14.6504 22.0069 11.9975C22.004 7.00306 18.3183 2.77616 13.3717 2.09442Z">
                                        </path>
                                    </svg></div>
                            </a></li>
                    </ul>

                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="footer-bottom">
            <div class="copyright">{!! __('PocketThrift © - :year All Rights Reserved', ['year' => '<span id="current-year">2026</span>']) !!}</div>
            {{-- <div class="back-to-top"></div> --}}
        </div>
    
    <script>
    const scrollBtn = document.querySelector('.scroll-to-top');

window.addEventListener('scroll', () => {
    if (window.scrollY > window.innerHeight) {
        // user has scrolled past first fold
        scrollBtn.style.opacity = '1';
        scrollBtn.style.pointerEvents = 'auto';
    } else {
        scrollBtn.style.opacity = '0';
        scrollBtn.style.pointerEvents = 'none';
    }
});    

// Update Footer Year
document.getElementById('current-year').textContent = new Date().getFullYear();
    </script>

    </footer>
