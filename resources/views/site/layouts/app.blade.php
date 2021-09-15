<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('site.partials.seo_og')

    <title>{{settingHelper('seo_title')}}</title>

    {{-- CSS --}}
    <link rel="preload" href="{{static_asset('site/css/bootstrap.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link  rel="stylesheet" href="{{static_asset('site/css/bootstrap.min.css') }}"></noscript>
    <link rel="preload" href="{{static_asset('site/css/font-awesome.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link  rel="stylesheet" href="{{static_asset('site/css/font-awesome.min.css') }}"></noscript>
    <link rel="preload" href="{{static_asset('site/css/icon.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link  rel="stylesheet" href="{{static_asset('site/css/icon.min.css') }}"></noscript>
    <link rel="preload" href="{{static_asset('site/css/magnific-popup.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link  rel="stylesheet" href="{{static_asset('site/css/magnific-popup.min.css') }}"></noscript>
    <link rel="preload" href="{{static_asset('site/css/animate.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link  rel="stylesheet" href="{{static_asset('site/css/animate.min.css') }}"></noscript>
    <link rel="preload" href="{{static_asset('site/css/slick.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link  rel="stylesheet" href="{{static_asset('site/css/slick.min.css') }}"></noscript>
    <link rel="preload" href="{{static_asset('site/css/structure.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link  rel="stylesheet" href="{{static_asset('site/css/structure.min.css') }}"></noscript>
    <link rel="preload" href="{{static_asset('site/css/main.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link  rel="stylesheet" href="{{static_asset('site/css/main.css') }}"></noscript>
    @if($language->text_direction == "RTL")
        <link rel="preload" href="{{static_asset('site/css/rtl.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link  rel="stylesheet" href="{{static_asset('site/css/rtl.min.css') }}"></noscript>
    @endif
    <link rel="preload" href="{{static_asset('site/css/custom.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link  rel="stylesheet" href="{{static_asset('site/css/custom.css') }}"></noscript>
    <link rel="preload" href="{{static_asset('site/css/responsive.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link  rel="stylesheet" href="{{static_asset('site/css/responsive.min.css') }}"></noscript>

    @yield('style')

    <link rel="preload" href="https://fonts.googleapis.com/css2?family={{data_get(activeTheme(), 'options.fonts')}}:wght@400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family={{data_get(activeTheme(), 'options.fonts')}}:wght@400;500;600;700&display=swap"
            rel="stylesheet"></noscript>

    {{-- icons --}}
    <link rel="icon" href="{{static_asset(settingHelper('favicon')) }}">
    <link rel="apple-touch-icon" sizes="144x144"
          href="{{static_asset('site/images/ico/apple-touch-icon-precomposed.png') }}">
    <link rel="apple-touch-icon" sizes="114x114"
          href="{{static_asset('site/images/ico/apple-touch-icon-114-precomposed.png') }}">
    <link rel="apple-touch-icon" sizes="72x72"
          href="{{static_asset('site/images/ico/apple-touch-icon-72-precomposed.png') }}">
    <link rel="apple-touch-icon" sizes="57x57"
          href="{{static_asset('site/images/ico/apple-touch-icon-57-precomposed.png') }}">

    @if(settingHelper('predefined_header')!=null)
        {!! base64_decode(settingHelper('predefined_header')) !!}
    @endif
    @if(settingHelper('custom_header_style')!=null)
        {!! base64_decode(settingHelper('custom_header_style')) !!}
    @endif

    @include('feed::links')

    {{-- icons --}}

<!-- Template Developed By  -->
    @stack('style')

    <style type="text/css">
        :root {
            --primary-color: {{data_get(activeTheme(), 'options.primary_color')}};
            --primary-font: {{\Config::get('site.fonts.'.data_get(activeTheme(), 'options.fonts').'')}};
            --plyr-color-main: {{data_get(activeTheme(), 'options.primary_color')}};
        }

        .clicked {
            background-color: rgb(67, 176, 27) !important;
            color:white !important;
        }
    </style>

    <script async src="https://www.googletagmanager.com/gtag/js?id={{ settingHelper('google_analytics_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', '{{ settingHelper('google_analytics_id') }}');
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>

<script type="text/javascript">
$( document ).ready(function() {
    $(document).on("click", ".likebutton", function() {
        let id = $(this).attr("id");
        let split = id.split("like");
        let getID = split[split.length - 1]
        // alert(getID)    
        $.ajax({
        url: "/like-code",
        type:"POST",
        data:{
            "_token": "{{ csrf_token() }}",
            "code_id":parseInt(getID)
        },
        success:function(response){
            id = '#'+id;
            if(response.liked == true){
                $(id).addClass('clicked')
                // $('.changeNumber'+id).text(parseInt($('.changeNumber'+id).text(), 10)+1)
            }else{
                $(id).removeClass('clicked')
            }
        },
        error: function(response) {
        },
        });
    });

    $(document).on("click", ".dislikebutton", function() {
        let id = $(this).attr("id");
        let split = id.split("dislike");
        let getID = split[split.length - 1]
        // alert(getID)    
        $.ajax({
        url: "/dislike-code",
        type:"POST",
        data:{
            "_token": "{{ csrf_token() }}",
            "code_id":parseInt(getID)
        },
        success:function(response){
            id = '#'+id;
            if(response.disliked == true){
                $(id).addClass('clicked')
            }else{
                $(id).removeClass('clicked')
            }
        },
        });
    });
});

  </script>
  
</head>
{{-- dark class="sg-dark" --}}
<body class="{{defaultModeCheck()}}">
<div id="switch-mode" class="{{defaultModeCheck() == 'sg-dark'? 'active':''}}">
    <div class="sm-text">{{__('dark_mode')}}</div>
    <div class="sm-button">
        <input type="hidden" id="url" value="{{url('/')}}">
        <span></span>
    </div>
</div>
@if(settingHelper('preloader_option')==1)
    <div id="preloader">
        <img src="{{static_asset('site/images/')}}/preloader-2.gif" alt="Image" class="tr-preloader img-fluid">
    </div>
@endif
@include('site.layouts.header')
{{-- /.sg-header --}}

@yield('content')

<div class="scrollToTop" id="display-nothing">
    <a href="#"><i class="fa fa-angle-up"></i></a>
</div>
@include('site.layouts.footer')
{{-- /.footer --}}

{{-- JS --}}

<script src="{{static_asset('site/js/jquery.min.js') }}"></script>
<script defer src="{{static_asset('site/js/popper.min.js') }}"></script>
<script defer src="{{static_asset('site/js/bootstrap.min.js') }}"></script>
<script defer src="{{static_asset('site/js/slick.min.js') }}"></script>
<script defer src="{{static_asset('site/js/theia-sticky-sidebar.min.js') }}"></script>
<script defer src="{{static_asset('site/js/magnific-popup.min.js') }}"></script>
<script src="{{static_asset('site/js/carouFredSel.min.js') }}"></script>
@stack('script')
<script src="{{static_asset('site/js/main.min.js') }}"></script>
<script src="{{static_asset('js/custom.js') }}"></script>

<script async type="text/javascript" src="{{static_asset('site/js') }}/jquery.cookie.min.js"></script>
<script defer src="{{static_asset('site/js/lazyload.min.js')}}"></script>
@php
    if(settingHelper('notification_status') == '1'){
        $onesignal_appid                    =   settingHelper('onesignal_app_id');
        $onesignal_actionmessage            =   settingHelper('onesignal_action_message');
        $onesignal_acceptbuttontext         =   settingHelper('onesignal_accept_button');
        $onesignal_cancelbuttontext         =   settingHelper('onesignal_cancel_button');
    }
@endphp
<script src="{{static_asset('site/js') }}/bootstrap-tagsinput.min.js" async></script>

@if(settingHelper('notification_status') == '1')
    <!-- oneSignal -->
    <script src="{{static_asset('site/js') }}/OneSignalSDK.js" async=""></script>

    <script>
        var OneSignal = window.OneSignal || [];
        OneSignal.push(["init", {
            appId: "{{ $onesignal_appid ?? '' }}",
            subdomainName: 'push',
            autoRegister: false,
            promptOptions: {
                /* These prompt options values configure both the HTTP prompt and the HTTP popup. */
                /* actionMessage limited to 90 characters */
                actionMessage: "{{ $onesignal_actionmessage ?? '' }}",
                /* acceptButtonText limited to 15 characters */
                acceptButtonText: "{{ $onesignal_acceptbuttontext ?? '' }}",
                /* cancelButtonText limited to 15 characters */
                cancelButtonText: "{{ $onesignal_cancelbuttontext ?? '' }}"
            }
        }]);
    </script>

    <script src="{{static_asset('site/js/onesignal_notification.js')}}"></script>

@endif


@if(!blank(\Request::route()))
    @if(\Request::route()->getName() == "article.detail")
        @if(settingHelper('adthis_option')==1 and settingHelper('addthis_public_id')!=null)
            {!! base64_decode(settingHelper('addthis_public_id')) !!}
        @endif

        @if(settingHelper('facebook_comment')==1)
            <div id="fb-root"></div>
            <script async defer crossorigin="anonymous"
                    src="https://connect.facebook.net/{{ settingHelper('default_language') }}/sdk.js#xfbml=1&version=v8.0&appId={{ settingHelper('facebook_app_id') }}&autoLogAppEvents=1"
                    nonce="JOvaLAFF"></script>
        @endif
    @endif
@endif

@yield('script')
@yield('player')
@yield('audio')

@isset($post)
    @if(!blank(\Request::route()))
        @if(settingHelper('adthis_option')==1 and settingHelper('addthis_public_id')!=null and \Request::route()->getName() == "article.detail")
            <script type="text/javascript">
                (function ($) {
                    "use strict";
                    var addthis_share = {
                        url: "{{ url()->current() }}",
                        title: "{{ $post->meta_title }}",
                        description: "{{ strip_tags($post->meta_description) }}",
                        media: "{{basePath(@$post->image)}}/{{ @$post->image->og_image }}"
                    }
                })(jQuery);
            </script>
        @endif
    @endif
@endisset


@if(settingHelper('custom_footer_js')!=null)
    {!! base64_decode(settingHelper('custom_footer_js')) !!}
@endif

<script type="text/javascript" src="{{ static_asset('site/js/webp-support.js') }}"></script>
<script type="text/javascript" src="{{ static_asset('site/js/custom.min.js')}}" type="text/javascript"></script>
@yield('quiz')
</body>
</html>
