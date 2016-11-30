<?php

namespace App\Http\Controllers;

use App\Helpers\TweetHelper;
use Illuminate\Http\Request;
use Twitter;

class TweetController extends Controller {

    /**
     * @desc Tweet helper class with helper functions
     *
     * @var  TweetHelper
     */
    protected $tweetHelper;

    public function __construct(TweetHelper $tweetHelper) {
        $this->tweetHelper = $tweetHelper;
    }

    /**
     * Find all tweets in a location within 50KM radius
     *
     * @param  Request $request
     * @return Response
     */
    public function find(Request $request) {
        $location    = $request->get('location');
        $coordinates = $request->get('coordinates');
        $radius      = config('result.radius');

        $cache   = $this->tweetHelper->getCache($location);
        $results = $this->parse($location, $coordinates, $radius);
        
        // Verify if cached
        if ($cache) {
            $results = $cache;
        }

        return response()->json($results, 200);
    }

    /**
     * Parse twitter search results
     *
     * @param  String $location - The location name
     * @param  String $coordinates - The latitude & longitude of the location
     * @param  String $radius - The search range (in KM)
     * @return Array
     */
    public function parse(string $location, string $coordinates, string $radius = '50km') {
        // Find tweets & parse tweet results
        $results = $this->parseTweets(
            Twitter::getSearch([
                'q'       => $location,
                'geocode' => $coordinates . ',' . $radius,
                'count'   => config('result.size')
            ])
        );

        // Cache results
        $this->tweetHelper->setCache($location, $results);

        return $results;
    }

    /**
     * Parse tweets
     *
     * @param  stdClass $tweets - Tweets result from Twitter API
     * @return Array
     */
    public function parseTweets($tweets) {
        $parsedTweets = [];

        foreach ($tweets->statuses as $tweet) {
            array_push($parsedTweets, [
                'id'                      => $tweet->id,
                'profile_image_url_https' => $tweet->user->profile_image_url_https,
                'text'                    => $tweet->text,
                'created_at'              => $tweet->created_at,
                'geo'                     => $tweet->geo,
            ]);
        }

        return $parsedTweets;
    }
}
