<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Exo:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css">
<link rel="stylesheet" href="{{ asset('admin/css/jquery.toast.css') }}">
<link rel="stylesheet" href="{{ asset('front/css/filtercms.css') }}">

<style>
  body{
    font-family: "Exo", sans-serif;
  }
    h1.page-titles {
    font-size: 33px;
    padding: 15px 0;
    color: #000000;
}

.region-card {
        display: inline-flex;
    align-items: center;
    border-radius: 7px;
    gap: 1.25rem;
    width: -webkit-fill-available;
    box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 15px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px;
padding: 10px;
}

.deal-column {
    border: 1px solid #ededed;
    border-radius: 10px;
    width: 45%;
    margin: 20px;
}

.region-card img {
    border-radius: 9px;
    width: 86px;
}

h2.sub-headings {
    font-size: 22px;
    padding: 15px 0;
    color: #000000;
}
    .navbar-brand img {
        max-height: 50px;
        width: auto;
    }
    
    .navbar {
        padding: 0.5rem 0;
    }
    
    .navbar-nav .nav-link {
        font-weight: 500;
        padding: 0.5rem 1rem;
        color: rgba(255, 255, 255, 0.75) !important;
    }
    
    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
        color: white !important;
    }
    
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

     .dropdown-menu[data-bs-popper]{
          left: -46px !important;
    }

    ul.dropdown-menu.show li a {
    font-size: 13px;
    margin: -1px;
}

.offer-card-buttons{
    width: 140px;
    color: #fff;
    background: transparent;
    border: none;
    text-align: center;
    font-weight: 600;
    clip-path: polygon(0 0, 97% 0, 83% 100%, 0 100%);       
    
}
.button-base{
border: 2px dashed #ee7b42;
    border-radius: 10px;
padding: 14px 20px;
background: #002b61;

}
.button-base:hover{
    background: #ee7b42;
    color: #000;
    border: 2px dashed #002b61;

}
.modal-content{
    min-width: 600px;
}
.modal-dialog-centered{
    justify-content: center;
}
.buttons-tab:hover{
    background: #002b61;
    color: #fff !important;
    border-radius: 10px;
    border: 1px dashed #ff4700;
}
.active-tab-style{
    background: #002b61;
    color: #fff;
    border: 1px dashed #ff4700;
}
    /* Marquee animation */
    .marquee-content {
        display: inline-block;
        white-space: nowrap;
        animation: marquee-scroll 20s linear infinite;
    }

    .widget p{
        font-size: 13px;
        margin-top:5px;
        color: #000;
    }

    /* trending store */
        .trending-logo img{
            max-height: 120px;
    max-width: 100%;
    object-fit: contain;
        }

        .trending-box {
    border: 1px dashed #ff4700;
    border-radius: 10px;
    transition: 0.5s;
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
}
.trending-box:hover {
border: 1px dashed #002d61;
}

.box-trending-store{
    justify-content: space-around;
}

.categories-title-text{
    font-size: 16px;
    font-weight: 600;
    color: #000;
}

.trending-cat-box{
border: 3px solid #ff4700;
    border-radius: 10px;
    transition: 0.5s;
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
}
    .comission-btn {
    background: #002b61;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    padding: 5px;
    transition: 0.5s;
    text-align: center;
    border-radius: 5px;
}
.comission-btn:hover{
    background-color: #ff4700;
}

.col-lg-3.col-md-4.sidebar {
    background: #f8f9fa;
    border-radius: 11px;
    padding: 21px;
}

.more-stores-widget ul li{
margin-bottom: 6px;
}

.widget-coupon ul{
    padding: 0;
}

.widget-coupon ul li{
color: #000;
font-size: 14px;
list-style: circle;
}
.more-stores-widget ul li a{
color: #000;
font-size: 14px;
list-style: circle;
}



.badge{
    color: #000;
    font-weight: 600;
}
.widget-content ul li a{
    font-size: 14px;
    color: #000;
    text-decoration: none;

}

    .trending-store-title {
    font-size: 14px;
    font-weight: 600;
    color: #000;
}
.trending-store-data {
    font-size: 13px;
    font-weight: 500;
    color: #000;
}

    /* FOOTER */
     footer{
    margin: 0;
            padding: 0;
            box-sizing: border-box;
}
        /* Footer Container */
        .footer-container {
            background-color: #002D5B;
            /* Dark blue background */
            color: white;
            width: 100%;
            
        }

        /* Header Section */
        .footer-header {
            background-color: #FF6600;
            /* Bright orange background */
            padding: 20px 0;
            text-align: center;
            position: relative;
        }

        .footer-header h2 {
            color: white;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        /* Triangle separator */
        .triangle-separator {
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-top: 10px solid #FF6600;
        }

        /* Main Footer Content */
        .footer-content {
            padding: 40px 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Footer Column */
        .footer-column {
            flex: 1;
            min-width: 250px;
            padding: 0 15px;
            margin-bottom: 30px;
        }

        .footer-column h3 {
            color: white;
            font-size: 1.1rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column li {
            margin-bottom: 8px;
        }

        .footer-column a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s ease;
        }

        .footer-column a:hover {
            opacity: 0.8;
        }

        /* Logo Section */
        .logo-section {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .logo-tag {
            width: 24px;
            height: 24px;
            background-color: #FF6600;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
        }

        .logo-tag::before {
            content: "x";
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        p.card-text {
    font-size: 13px;
    color: #000;
}

.card-title {
color: #000
    font-weight: 600;
    font-size: 17px;
}

a.read-more-blog-btn {
    background: #002d61;
    padding: 7px 15px;
    border-radius: 7px;
    color: #fff;
    font-size: 14px;
    text-decoration: none;
    transition: 0.5s;
}

a.read-more-blog-btn:hover {
    background: #ff4700;
    transition: 0.5s;
}

.sidebar .widget-title {
    font-size: 1.125rem;
    font-weight: 600;
    border-bottom: 1px solid #F44336;
    padding-bottom: .5rem;
    margin-bottom: 1rem;
}

        .logo-text {
            font-size: 1.5rem;
            font-weight: bold;
            font-family: 'Brush Script MT', cursive;
            color: white;
        }

        .logo-description {
            font-size: 0.9rem;
            line-height: 1.4;
            margin-top: 10px;
        }
.sidebar-blogs {
    background: #efefef;
    border-radius: 10px;
    padding: 10px;
}
        /* Bottom Footer */
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding: 20px 0;
            text-align: center;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            flex-wrap: wrap;
        }

        .copyright {
            font-size: 0.9rem;
            margin: 0 10px;
        }

        .back-to-top {
            background-color: #FF6600;
            width: 40px;
            height: 40px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid white;
            transition: background-color 0.3s ease;
            position: absolute;
            top: 87px;
            right: 22px;
        }

        .back-to-top:hover {
            background-color: #FF7711;
        }

        .back-to-top::before {
            content: "↑";
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .social-icons ul {
            display: flex;
            gap: 8px;
            margin-top: 20px;
        }

        .social-icons ul li {
            display: flex;
            padding: 6px 7px 0px 7px;
            border: 2px solid #fff;
            background: #ff5a00;
            border-radius: 5px;
        }

        .social-icons ul li:hover {
            display: flex;
            padding: 6px 7px 0px 7px;
            border: 2px solid #fff;
            background: #ff590000;
            border-radius: 5px;
            transition: 0.7s;
               }

        /* Responsive Design */
        @media (max-width: 768px) {
            .footer-content {
                padding: 30px 15px;
            }

            .footer-column {
                min-width: 100%;
                padding: 0 10px;
                margin-bottom: 20px;
            }

            .footer-header h2 {
                font-size: 1rem;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 15px;
                padding: 15px 10px;
            }

            .copyright {
                font-size: 0.8rem;
            }

            .back-to-top {
                width: 35px;
                height: 35px;
            }
        }

        @media (max-width: 480px) {
            .footer-header {
                padding: 15px 0;
            }

            .footer-content {
                padding: 25px 10px;
            }

            .footer-column h3 {
                font-size: 1rem;
            }

            .logo-text {
                font-size: 1.2rem;
            }

            .logo-description {
                font-size: 0.8rem;
            }

            .footer-bottom {
                padding: 10px 5px;
            }
        }


     footer{
    margin: 0;
            padding: 0;
            box-sizing: border-box;
}
        /* Footer Container */
        .footer-container {
            background-color: #002D5B;
            /* Dark blue background */
            color: white;
            width: 100%;
            
        }

        /* Header Section */
        .footer-header {
            background-color: #FF6600;
            /* Bright orange background */
            padding: 20px 0;
            text-align: center;
            position: relative;
        }

        .footer-header h2 {
            color: white;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        /* Triangle separator */
        .triangle-separator {
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-top: 10px solid #FF6600;
        }

        /* Main Footer Content */
        .footer-content {
            padding: 40px 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Footer Column */
        .footer-column {
            flex: 1;
            min-width: 250px;
            padding: 0 15px;
            margin-bottom: 30px;
        }

        .footer-column h3 {
            color: white;
            font-size: 1.1rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column li {
            margin-bottom: 8px;
        }

        .footer-column a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s ease;
        }

        .footer-column ul {
        list-style: none;
        padding: 0;
        }

        .footer-column a:hover {
            opacity: 0.8;
        }

        /* Logo Section */
        .logo-section {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .logo-tag {
            width: 24px;
            height: 24px;
            background-color: #FF6600;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
        }

        .logo-tag::before {
            content: "x";
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: bold;
            font-family: 'Brush Script MT', cursive;
            color: white;
        }

        .logo-description {
            font-size: 0.9rem;
            line-height: 1.4;
            margin-top: 10px;
        }

        /* Bottom Footer */
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding: 20px 0;
            text-align: center;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            flex-wrap: wrap;
        }

        .copyright {
            font-size: 0.9rem;
            margin: 0 10px;
        }

        .back-to-top {
            background-color: #FF6600;
            width: 40px;
            height: 40px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid white;
            transition: background-color 0.3s ease;
            position: absolute;
            top: 87px;
            right: 22px;
        }

        .back-to-top:hover {
            background-color: #FF7711;
        }

        .back-to-top::before {
            content: "↑";
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .social-icons ul {
            display: flex;
            gap: 8px;
            margin-top: 20px;
        }

        .social-icons ul li {
            display: flex;
            padding: 4px 7px 4px 7px;
            border: 2px solid #fff;
            background: #ff5a00;
            border-radius: 5px;
        }

        .social-icons ul li:hover {
            display: flex;
            border: 2px solid #fff;
            background: #ff590000;
            border-radius: 5px;
            transition: 0.7s;
               }

        /* Responsive Design */
        @media (max-width: 768px) {
            .footer-content {
                padding: 30px 15px;
            }

            .footer-column {
                min-width: 100%;
                padding: 0 10px;
                margin-bottom: 20px;
            }

            .footer-header h2 {
                font-size: 1rem;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 15px;
                padding: 15px 10px;
            }

            .copyright {
                font-size: 0.8rem;
            }

            .back-to-top {
                width: 35px;
                height: 35px;
            }
        }

        @media (max-width: 480px) {
            .footer-header {
                padding: 15px 0;
            }

            .footer-content {
                padding: 25px 10px;
            }

            .footer-column h3 {
                font-size: 1rem;
            }

            .logo-text {
                font-size: 1.2rem;
            }

            .logo-description {
                font-size: 0.8rem;
            }

            .footer-bottom {
                padding: 10px 5px;
            }
        }


    @keyframes marquee-scroll {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-100%);
        }
    }

    .marquee-text:hover .marquee-content {
        animation-play-state: paused;
    }
    
    /* Responsive adjustments for mobile */
    @media (max-width: 991.98px) {
        .navbar-brand {
            order: 1 !important;
            margin: 0 auto 10px auto !important;
        }
        
        .navbar-collapse {
            text-align: center;
        }
        
        .navbar-nav {
            margin-top: 10px;
        }
    }

    /* FAQS */
     .faq-header {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: #000;
            position: relative;
            padding-bottom: 15px;
        }
        
        /* .faq-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 2px;
            background-color: #000;
        } */
        
        .faq-header .tag-icon {
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #fff;
            padding: 0 10px;
            border-radius: 50%;
            font-size: 16px;
            color: #000;
        }
        
        .faq-item {
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .faq-question {
            background-color: #002855;
            color: white;
            padding: 12px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease;
        }
        
        .faq-question:hover {
            background-color: #001f44;
        }
        
        .faq-question.expanded {
            background-color: #002855;
        }
        
        .faq-question.collapsed {
            background-color: #002855;
        }
        
        .faq-question i {
            font-size: 16px;
            margin-right: 10px;
        }
        
        .faq-content {
            background-color: #f5f5f5;
            padding: 20px;
            border-top: 1px solid #ddd;
            display: none;
        }
        
        .faq-content.show {
            display: block;
        }
        
        .faq-content ul {
            padding-left: 20px;
            margin-bottom: 0;
        }
        
        .faq-content li {
            margin-bottom: 8px;
            line-height: 1.5;
        }

        
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .faq-header {
                font-size: 20px;
                padding-bottom: 10px;
            }
            
            .faq-question {
                padding: 10px 15px;
                font-size: 14px;
            }
            
            .faq-content {
                padding: 15px;
            }
        }
        
        @media (max-width: 576px) {
            .faq-header {
                font-size: 18px;
            }
            
            .faq-question {
                padding: 8px 12px;
                font-size: 13px;
            }
            
            .faq-content {
                padding: 12px;
            }
        }

        /* Trending Offers Carousel */
        .offer-carousel-card {
            border: 1px solid #e0e0e0;
            border-radius: 15px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            text-align: center;
            position: relative;
            padding-bottom: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .offer-carousel-card .card-banner {
            height: 120px;
            background-size: cover;
            background-position: center;
        }
        .offer-carousel-card .store-logo-container {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #fff;
            border: 2px solid #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            position: absolute;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .offer-carousel-card .store-logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .offer-carousel-card .card-content {
            padding: 60px 15px 15px 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .offer-carousel-card .offer-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            min-height: 48px; /* 2 lines of text */
        }
        .offer-carousel-card .get-code-btn {
            background-color: #d92323;
            color: #fff;
            font-weight: bold;
            border-radius: 5px;
            padding: 10px 20px;
            border: none;
            text-transform: uppercase;
        }
</style>

<title>{{ config('app.name') }}</title>
