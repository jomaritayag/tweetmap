<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twitter;

class TweetController extends Controller {

    /**
     * Search all tweets based on location / coordinates
     *
     * @param  Request $request
     * @return Response
     */
    public function search(Request $request) {
        $location    = $request->get('location');
        $coordinates = $request->get('coordinates');
        $radius      = config('ttwitter.radius');

        $results = $this->getTweets($location, $coordinates, $radius);

        return response()->json($results, 200);
    }

    /**
     * Get search results in twitter
     *
     * @param  String $location
     * @param  String $coordinates
     * @param  String $radius
     * @return Array
     */
    public function getTweets($location, $coordinates, $radius = '50km') {
        
        $results = $this->parseTweets(
            Twitter::getSearch([
                'q'       => $location,
                'geocode' => $coordinates . ',' . $radius,
                'count'   => config('ttwitter.size')
            ])
        );

        return $results;
    }

    /**
     * Parse Tweets
     *
     * @param  $tweets
     * @return Array
     */
    public function parseTweets($tweets) {

        $allTweets = [];

        foreach ($tweets->statuses as $tweet) {
            array_push($allTweets, [
                'id'                      => $tweet->id,
                'profile_image_url_https' => $tweet->user->profile_image_url_https,
                'text'                    => $tweet->text,
                'created_at'              => date('Y-m-d H:i:s', strtotime($tweet->created_at)),
                'geo'                     => $tweet->geo,
            ]);
        }

        return $allTweets;
    }
}
