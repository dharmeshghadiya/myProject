@if($app_local=='en')
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;700&display=swap" rel="stylesheet">

</head>
<body
        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: #718096; height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important;">
<style>
    @media only screen and (max-width: 600px) {
        .inner-body {
            width: 100% !important;
        }

        .footer {
            width: 100% !important;
        }
    }

    @media only screen and (max-width: 500px) {
        .button {
            width: 100% !important;
        }
    }

    ul li {
        display: inline;
    }
</style>
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation"
       style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
    <tr>
        <td align="center"
            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative;">
            <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation"
                   style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 0; padding: 0; width: 100%;">
                <tr>
                    <td class="header"
                        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; padding: 25px 0 0 0; text-align: center;">
                        <a href="#"
                           style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; color: #3d4852; font-size: 19px; font-weight: bold; text-decoration: none; display: block;margin-bottom:-8px;">
                            <img src="{{ asset('assets/email/email_header.png') }}" alt="Logo"
                                 style="width: 570px;margin-bottom: -7px;"/>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td class="body" width="100%" cellpadding="0"
                        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0"
                               role="presentation"
                               style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; background-color: #80e613; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015); margin: 0 auto; padding: 0; width: 570px;">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell"
                                    style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; max-width: 100vw;text-align:center">
                                    <h2 style="font-size: 21pt;color: #242525;letter-spacing:2.1pt;line-height: 26pt;
									font-weight: bold;font-style: italic">
                                        {{ $main_title_text }}
                                    </h2>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @else
                    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml" dir="rtl">
                    <head>
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                        <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;700&display=swap"
                              rel="stylesheet">

                    </head>
                    <body dir="rtl"
                          style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: #718096; height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important;">
                    <style>
                        @media only screen and (max-width: 600px) {
                            .inner-body {
                                width: 100% !important;
                            }

                            .footer {
                                width: 100% !important;
                            }
                        }

                        @media only screen and (max-width: 500px) {
                            .button {
                                width: 100% !important;
                            }
                        }

                        ul li {
                            display: inline;
                        }
                    </style>
                    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation"
                           style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
                        <tr>
                            <td align="center"
                                style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative;">
                                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                       style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 0; padding: 0; width: 100%;">
                                    <tr>
                                        <td class="header"
                                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; padding: 25px 0 0 0; text-align: center;">
                                            <a href="#"
                                               style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; color: #3d4852; font-size: 19px; font-weight: bold; text-decoration: none; display: block;margin-bottom:-8px;">
                                                <img src="{{ asset('assets/email/email_header.png') }}" alt="Logo"
                                                     style="width: 570px;margin-bottom: -7px;"/>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="body" width="100%" cellpadding="0"
                                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
                                            <table class="inner-body" align="center" width="570" cellpadding="0"
                                                   cellspacing="0"
                                                   role="presentation"
                                                   style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; background-color: #80e613; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015); margin: 0 auto; padding: 0; width: 570px;">
                                                <!-- Body content -->
                                                <tr>
                                                    <td dir="rtl" class="content-cell"
                                                        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; max-width: 100vw;text-align:center">
                                                        <h2 style="font-size: 21pt;color: #242525;letter-spacing:2.1pt;line-height: 26pt;font-weight: bold;font-style: italic">
                                                            {{ $main_title_text }}
                                                        </h2>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
@endif