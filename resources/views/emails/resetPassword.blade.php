@include('emails.emailHeader')
@if($app_local=='en')
    <tr>
        <td class="body" width="100%" cellpadding="0"
            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; border-bottom: 1px solid #edf2f7; border-top: 1px solid #edf2f7; margin: 0; padding: 0; width: 100%;">
            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0"
                   role="presentation"
                   style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; background-color: #ffffff; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015); margin: 0 auto; padding: 0; width: 570px;">
                <!-- Body content -->
                <tr>
                    <td class="content-cell"
                        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; font-weight:bold;position: relative; font-size: 16pt; line-height: 1.5em; margin-top: 0; text-align: left;color:#070201">
                            {{ config('email_string.hey') }} {{ $name }}
                        </p>
                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; font-size: 13pt; line-height: 1.5em; margin-top: 0; text-align: left;color:#070201">
                            {{ config('email_string.forgot_password_body') }}
                        </p>


                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin:35px 0 35px 0; text-align: center;">
                            <a href="{{ $actionUrl }}"
                               style="background-image: linear-gradient(to right, #80e613 0%, #02b80e 100%);text-decoration: none;color: #242525;font-size: 21pt;line-height: 26pt;width:100%;padding: 10px 30px 10px 20px;border-radius:50px;text-transform: uppercase;font-weight: bold;">STAY
                                {{ config('email_string.forgot_password_button_text') }}
                                <img src='{{ asset('assets/email/right-arrow.png') }}'
                                     style="width:4%;margin-right:20px;"/>
                            </a>
                        </p>
                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; font-size: 13pt; line-height: 1.5em; margin-top: 0; text-align: left;color:#070201">
                            {{ config('email_string.forgot_password_other_body') }}</p>


                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative;color:#070201;text-align: left; margin-left: -19%;">
                            {{ config('email_string.global_footer_team_title') }}</p>
                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative;color:#070201;text-align: left;margin-top:-10px; ">
                            <span style="font-weight:700;color:#070201">{{ config('email_string.global_footer_team_name') }}</span>
                        </p>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
@else
    <tr>
        <td class="body" width="100%" cellpadding="0"
            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; border-bottom: 1px solid #edf2f7; border-top: 1px solid #edf2f7; margin: 0; padding: 0; width: 100%;">
            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0"
                   role="presentation"
                   style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; background-color: #ffffff; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015); margin: 0 auto; padding: 0; width: 570px;">
                <!-- Body content -->
                <tr>
                    <td class="content-cell"
                        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; font-weight:bold;position: relative; font-size: 16pt; line-height: 1.5em; margin-top: 0; text-align: right;color:#070201;direction:rtl"
                           dir="rtl">
                            {{ config('email_string.hey') }} {{ $name }}
                        </p>
                        <p dir="rtl" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; font-size: 13pt; line-height: 1.5em; margin-top: 0; color:#070201">
                            {{ config('email_string.forgot_password_body') }}
                        </p>


                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; font-size: 16px; line-height: 1.5em; margin:35px 0 35px 0; text-align: center;">
                            <a href="{{ $actionUrl }}"
                               style="background-image: linear-gradient(to right, #80e613 0%, #02b80e 100%);text-decoration: none;color: #242525;font-size: 21pt;line-height: 26pt;width:100%;padding: 10px 30px 10px 20px;border-radius:50px;text-transform: uppercase;font-weight: bold;">STAY
                                {{ config('email_string.forgot_password_button_text') }}
                                <img src='{{ asset('assets/email/left-arrow.png') }}'
                                     style="width:4%;margin-right:20px;"/>

                            </a>
                        </p>
                        <p dir="rtl" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; font-size: 13pt; line-height: 1.5em; margin-top: 0; color:#070201">
                            {{ config('email_string.forgot_password_other_body') }}
                        </p>


                        <p dir="rtl"
                           style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative;color:#070201;text-align: right;">
                            {{ config('email_string.global_footer_team_title') }}
                        </p>

                        <p dir="rtl"
                           style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative;color:#070201;text-align: right;margin-top:-10px; ">
                            <span style="font-weight:700;color:#070201">{{ config('email_string.global_footer_team_name') }}</span>
                        </p>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endif
@include('emails.emailFooter')