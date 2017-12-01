<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width" />
        <style type="text/css">
            .wrapper {
                width: 60%;
                box-shadow: 0 0 2px #aaa;
                font-family: Hind;
                border: 1px solid rgba(255, 94, 58, 0.95);
            }
            .logo_header {
                height: 50px;
                padding: 5px;
                background-color: rgba(255, 94, 58, 0.95);
            }
            .logo_header a {
                color: white;
                text-decoration: unset;
            }
            .logo_header a img {
                height: 100%;
            }
            .logo_header a span {
                position: absolute !important;
                font-size: 40px;
            }
            .email_body {
                padding: 0 15px;
            }

            .email_body p{
                font-size: 15px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="wrapper">
                    <div class="logo_header">
                        <a href="{{ route('home_page') }}">
                            <img src="{{ config('settings.email.logo') }}"/>
                            <span> @lang('emails.app')</span>
                        </a>
                    </div>
                    <div class="email_body">
                        <h1 class="text-center"> @lang('emails.title', ['object' => $object])</h1>
                        <p>
                            @lang('emails.content')
                        </p>
                        <p>
                            <a href="{{ $linkActive }}">{{ $linkActive }}</a>
                        </p>
                        <p> @lang('emails.password_reset.ending')</p>
                    </div>
                </div>

            </div>
        </div>
    </body>
</html>
