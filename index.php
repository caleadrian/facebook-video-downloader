<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Facebook Video Downloader</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/animate.css">
    <style>
        .alert{
            max-width: 15rem;
            padding: 5px;
        }
        .card-header{
            background: linear-gradient(to right, #c6ea8d, #fe90af);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container p-0">
        <div class="card border-0">
            <div class="card-header text-center">
                <h6 class="mb-0">Facebook Video Downloader</h6>
            </div>
            <div class="card-body py-5 border-0">
                <div class="form-container mb-2">
                    <input type="text" id="url" class="form-control" placeholder="Enter your fb video link here ...">
                </div>
                <button id="checkbtn" class="btn btn-secondary w-100">Check link</button>
            </div>
            <div class="card-footer text-center">
                <p id="videoTitle" class="card-text text-muted">Waiting for your video</p>
                <div class="row">
                    <div class="col-6 col-md-3 col-lg-2">
                        <button disabled name="sdbtn" class="btn btn-outline-success btn-lg">SD Download</button>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <button disabled name="hdbtn" class="btn btn-success btn-lg">HD Download</button>                    
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- all script -->
    <script src="./js/jquery.min.js"></script>
    <script src="./js/bootstrap-notify.js"></script>
    <script>
       // $('body').html('<p>Loading</p>');
        $(function(){

            function notify(msg, style){
                return $.notify( // snackbar notification
                    {
                    message : msg
                    },
                    {
                    animate: {
                        enter: "animated fadeInRight",
                        exit: "animated fadeOutRight"
                    },
                    delay : 8000,
                    type: style,
                    placement: {
                        from: 'top',
                        align: 'center'
                    }
                });
            }

            $('button#checkbtn').click(function(){
                var noti = notify('<small>Checking link ...</small>', 'secondary');
                var url = $('#url').val();
                $.ajax({
                    type: 'POST',
                    url: './ajax/getVideo.php',
                    data: {url:url},
                    dataType:"json",
                    success: function(result){
                        noti.close();
                        if(result.type === 'success'){
                            $('p#videoTitle').html(result.title);
                            if(result.sd_download_url){
                                $("button[name='sdbtn']").attr('disabled', false);
                                $("button[name='sdbtn']").attr('id', result.sd_download_url);
                            }
                            if(result.hd_download_url){
                                $("button[name='hdbtn']").attr('disabled', false);
                                $("button[name='hdbtn']").attr('id', result.hd_download_url);
                            } 
                        }else{
                            $('p#videoTitle').html(result.message);
                            $("button[name='sdbtn']").attr('disabled', true);
                            $("button[name='sdbtn']").attr('id', '');
                            $("button[name='hdbtn']").attr('disabled', true);
                            $("button[name='hdbtn']").attr('id', '');
                        }
                    }
                });
            });

            $("button[name='sdbtn']").click(function(){
                var noti = notify('<small>Downloading ...</small>', 'secondary');
                var title = $('p#videoTitle').html();
                var url = $(this).attr('id');
                $.ajax({
                    type: 'POST',
                    url: './ajax/downloadVideo.php',
                    data: {url:url, title:title},
                    success: function(result){
                        noti.close();
                        if(result){
                            notify('<small>Downloaded</small>', 'success');
                        }else{
                            notify('<small>Error</small>', 'danger');
                        }
                    }
                });                
            });

            $("button[name='hdbtn']").click(function(){
                var noti = notify('<small>Downloading ...</small>', 'success');
                var title = $('p#videoTitle').html();
                var url = $(this).attr('id');
                $.ajax({
                    type: 'POST',
                    url: './ajax/downloadVideo.php',
                    data: {url:url, title:title},
                    success: function(result){
                        noti.close();
                        if(result){
                            notify('<small>Downloaded</small>', 'success');
                        }else{
                            notify('<small>Error</small>', 'danger');
                        }
                    }
                });                
            });

        });

    </script>                                                                 
</body>

</html>