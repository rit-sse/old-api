<!DOCTYPE html>
<!-- FIXME: Design a better oops view. -->
<html>
    <head>
        <title>Error</title>

        <link href="//fonts.googleapis.com/css?family=Lato:300" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 300;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }

            .body {
                font-size: 54px;
                padding-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">That's an error.</div>
                <div class="body">We were unable to find the go link '{{ $link }}'.</div>
            </div>
        </div>
    </body>
</html>
