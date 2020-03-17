<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>{sitetitle}</title>
        <style>
        /* -------------------------------------
            GLOBAL
        ------------------------------------- */
        * {
            margin: 0;
            padding: 0;
            font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            font-size: 100%;
            line-height: 1.3;
        }

        img {
            max-width: 100%;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100%!important;
            height: 100%;
            background-color: #f6f6f6;
        }

        /* -------------------------------------
            ELEMENTS
        ------------------------------------- */
        a {
            color: #348eda;
        }

        .text-muted {
            color: #777;
        }

        .small {
            font-size: 75%;
        }

        /* -------------------------------------
            BODY
        ------------------------------------- */
        table.body-wrap {
            width: 100%;
            padding: 20px;
            background-color: #f6f6f6;
        }

        table.body-wrap .container {
            background-color: #FFF;
            border: 1px solid #f0f0f0;
        }

        /* -------------------------------------
            TYPOGRAPHY
        ------------------------------------- */
        h1, h2, h3 {
            font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            color: #000;
            margin: 40px 0 10px;
            line-height: 1.2;
            font-weight: 200;
        }

        h1 {
            font-size: 36px;
        }
        h2 {
            font-size: 28px;
        }
        h3 {
            font-size: 22px;
        }

        p, ul, ol {
            margin-bottom: 10px;
            font-weight: normal;
            font-size: 14px;
        }

        ul li, ol li {
            margin-left: 5px;
            list-style-position: inside;
        }

        /* ---------------------------------------------------
            RESPONSIVENESS
            Nuke it from orbit. It's the only way to be sure.
        ------------------------------------------------------ */

        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
            display: block!important;
            max-width: 600px!important;
            margin: 0 auto!important; /* makes it centered */
            clear: both!important;
        }

        /* Set the padding on the td rather than the div for Outlook compatibility */
        .body-wrap .container {
            padding: 20px;
        }

        /* This should also be a block element, so that it will fill 100% of the .container */
        .content {
            max-width: 600px;
            margin: 0 auto;
            display: block;
        }

        /* Let's make sure tables in the content area are 100% wide */
        .content table {
            width: 100%;
        }
        </style>
    </head>

    <body>
        <!-- body -->
        <table class="body-wrap">
            <tr>
                <td></td>
                <td class="container">
                    <!-- content -->
                    <div class="content">
                        <table>
                            <tr>
                                <td>
                                    <p class="small text-muted">{date}</p>
                                    <p>&nbsp;</p>
                                    <p><b>{subject}</b></p>
                                    <p>{from} {senderName} ({senderMail})</p>
                                    <p>&nbsp;</p>
                                    <p>{senderName} {writes}:</p>
                                    <p>{content}</p>
                                    <p>&nbsp;</p>
                                    <p>{writeBackLink}: <a href="mailto:{senderMail}?subject=Re: {subject}&body={encodedContent}" target="_top">{reply}</a></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- /content -->
                </td>
                <td></td>
            </tr>
        </table>
        <!-- /body -->
    </body>
</html>
