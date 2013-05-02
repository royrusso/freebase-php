<!--Copyright 2013 Roy Russo

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

Latest Builds: https://github.com/royrusso/freebase-php
-->
<!DOCTYPE html>
<html>
<head>
    <title>FreeBase PHP - People Finder</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#personField").focus();

            /* attach a submit handler to the form */
            $("#theform").submit(function (event) {

                /* stop form from submitting normally */
                event.preventDefault();

                /* get some values from elements on the page: */
                var $form = $(this);

                var loading;
                $("#loader").hide();
                $.ajax({
                    type:$form.attr('method'),
                    url:$form.attr('action'),
                    data:$form.serialize(),
                    beforeSend:function () {
                        $('#loading-indicator').show();
                    },
                    complete:function () {
                        $('#loading-indicator').hide();
                    },
                    success:function (data) {
                        $('#loading-indicator').show();
                        $("#theresult").empty().append(data);
                    }
                });

                /* Send the data using post */
//                var posting = $.post(url, { s:term });

                /* Put the results in a div */
                /*
                                posting.done(function (data) {
                                    var content = $(data);
                                    $("#theresult").empty().append(content);
                                });*/
            });
        });
    </script>
    <style type="text/css">/*
        #loading-indicator {
            position: fixed;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .11);
            display: none;
            z-index: 999;
            *//*            position: absolute;*//*
            left: 0;
            top: 0;
            margin-left: -50px;
            margin-top: -50px;
        }
        */
    #loading-indicator {
        display: none;
        width: 100%;
        height: 500px;
        position: fixed;
        background: url(img/ajax-loader.gif) no-repeat center rgba(0, 0, 0, .2);
        text-align: center;
        padding: 10px;
        font: normal 16px Tahoma, Geneva, sans-serif;
        border: 0;
        z-index: 999;
        overflow: hidden;
    }

    ​
    </style>
</head>
<body>
<div id="loading-indicator" style="display:none">

</div>
<div class="container">
    <form id="theform" action="query.php" method="POST">
        <fieldset>
            <legend>FreeBase PHP - People Finder</legend>
            <p>Enter a person's name, and this script will query the FreeBase API for information on that person.<br/>Documentation
                and code can be found on the <a href="https://github.com/royrusso/freebase-php" target="_blank">Project
                    Page</a></p>
            <label>Enter a Name</label>
            <input type="text" id="personField" placeholder="Type a name" name="q">
            <span class="help-block">ie. Albert Einstein, Ronald Reagan</span>
            <button type="submit" class="btn">Submit</button>
        </fieldset>
    </form>

    <!-- the result of the search will be rendered inside this div -->
    <div class="lead">Result:</div>
    <div id="theresult"></div>
</div>
</body>
</html>