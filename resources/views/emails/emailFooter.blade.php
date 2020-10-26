@if($app_local=='en')

    <tr>
        <td style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative;">
            <table class="footer" width="570" cellpadding="0" cellspacing="0"
                   role="presentation"
                   style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative;  -premailer-cellspacing: 0; -premailer-width: 570px; margin: 0 auto; padding: 0;  width: 570px;background-color:#EFEFEF">
                <tr>
                    <td class="content-cell"
                        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nu0nito Sans', sans-serif; position: relative; line-height: 20pt; margin-top: 0;  font-size: 13pt;color: #070201;text-transform:uppercase ">
                            {{ config('email_string.copyright') }}
                        </p>
                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; line-height: 20pt; margin-top: 0; font-size: 13pt;color: #070201;font-weight: 300;">
                            {{ config('email_string.footer_address_ext') }}<br>
                            {{ config('email_string.footer_address') }}
                        </p>

                        <ul style="list-style: none;padding: 0;display: inline-flex;">
                            <li><img src="{{ asset('assets/email/Linkedin.png') }}"/></li>
                            <li><img src="{{ asset('assets/email/Linkedin.png') }}"/></li>
                            <li><img src="{{ asset('assets/email/Linkedin.png') }}"/></li>
                        </ul>

                    </td>
                </tr>
            </table>
        </td>
    </tr>

    </table>
    </td>
    </tr>
    </table>
    </body>
    </html>
@else
    <tr>
        <td style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative;">
            <table class="footer" width="570" cellpadding="0" cellspacing="0"
                   role="presentation"
                   style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative;  -premailer-cellspacing: 0; -premailer-width: 570px; margin: 0 auto; padding: 0;  width: 570px;background-color:#EFEFEF">
                <tr>
                    <td class="content-cell"
                        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; max-width: 100vw; padding: 32px;">
                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nu0nito Sans', sans-serif; position: relative; line-height: 20pt; margin-top: 0;  font-size: 13pt;color: #070201;text-transform:uppercase ">
                            {{ config('email_string.copyright') }}
                        </p>

                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Nunito Sans', sans-serif; position: relative; line-height: 20pt; margin-top: 0; font-size: 13pt;color: #070201;font-weight: 300;">
                            {{ config('email_string.footer_address_ext') }}<br>
                            {{ config('email_string.footer_address') }}
                        </p>

                        <ul style="list-style: none;padding: 0;display: inline-flex;">
                            <li><img src="{{ asset('assets/email/Linkedin.png') }}"/></li>
                            <li><img src="{{ asset('assets/email/Linkedin.png') }}"/></li>
                            <li><img src="{{ asset('assets/email/Linkedin.png') }}"/></li>
                        </ul>

                    </td>
                </tr>
            </table>
        </td>
    </tr>

    </table>
    </td>
    </tr>
    </table>
    </body>
    </html>
@endif
