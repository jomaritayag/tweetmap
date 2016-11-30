<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TweetMap</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}" media="screen" title="no title">

        <script>
            window.app_settings = { csrfToken: "{{ csrf_token() }}" }
        </script>
    </head>
    <body>
        
        <div class="container-fluid">
            <div class="row">
                <div class="map-panel">
                    <div id="tweet-header">
                        <h1>Tweets About 
                            <span id="location">Bangkok<span>
                        </h1>
                    </div>
                    <div id="map-content"></div>
                </div>

                <div class="search-panel">
                    <form action="={{ route('tweet.search') }}" method="POST" id="frm-search" class="form-inline">
                        <input type="search" id="txt-search" name="search" placeholder="City name" class="form-control form-control-search">
                        <input type="submit" value="SEARCH" class="btn btn-info btn-search">
                    </form>
                </div>
            </div>
        </div>
        
        <script src="{{ asset('js/app.js') }}"></script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.map.key') }}&callback=initMap"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
        
    </body>
</html>