<?php

require "../vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;
    
class TwitterSearch {
    private $twitter;

    public function __construct() {
        define('CONSUMER_SECRET', getenv('CONSUMER_SECRET'));
        define('ACCESS_TOKEN', getenv('ACCESS_TOKEN'));
        define('ACCESS_TOKEN_SECRET', getenv('ACCESS_TOKEN_SECRET'));
        define('CONSUMER_KEY', 'CNUBibY8JtHK5vOb8SJaEIgG1');
        
        $this->twitter = new TwitterOAuth(CONSUMER_KEY, 
               CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
    }
    
    public function simpleSearch($query, $since_id) {
        if ( $since_id > 0 ) {
            $parameters =  array('q' => $query, 'since_id' => $since_id, 'count'=>10);
        } else {
            $parameters =  array('q' => $query, 'count' => 100);         
        }
        $result = $this->twitter->get('search/tweets',$parameters);
        if ($this->twitter->getLastHttpCode() == 200) {
            $array = $result->statuses;
            if (is_array($array)) {
                foreach ($array as $tweet) {
                    echo $tweet->text . '<br>';
                }
            }
        }
    }
        
    public function testGetMentionsTimeline($screen_name, $count=10){
        $parameters = array('screen_name' => $screen_name, 'count'=>$count);
        $result = $this->twitter->get('statuses/mentions_timeline', $parameters);
        if ($this->twitter->getLastHttpCode() == 200) {
            
            if (is_array($result)) {
                foreach ($result as $tweet) {
                    echo $tweet->created_at . '<br>';
                    echo $tweet->text . '<br>';
                    echo $tweet->entities->user_mentions[0]->screen_name . '<br>';
                }
            }
        }
    }
    
    public function getUserTimeline($screen_name, $since_id='655418087945170945') {
        $parameters = array(
            'screen_name' => $screen_name, 
            'count'=>10, 
            'since_id' =>$since_id);
        
        $result = $this->twitter->get('statuses/user_timeline', $parameters);
        if ($this->twitter->getLastHttpCode() == 200) {
            if (is_array($result)) {

                echo '<table border=1>';
                echo '<th>Tweet ID</th><th>Date</th><th>Text</th>';
                foreach($result as $tweet) {
                    $item = array();
                    $item[] = $tweet->id;
                    $item[] = $tweet->created_at;
                    $item[] = $tweet->text;
                    echo '<tr><td>'. implode('</td><td>', $item) . '</td></tr>';
                }
                echo '</table>';
            }
        }
    }
    
    public function getMentionsTimeline($screen_name, $count)
    {
        $result = $this->twitter->get('statuses/mentions_timeline', 
                array('screen_name' => $screen_name, 
                    'count'=>$count));
        if ($this->twitter->getLastHttpCode() == 200) {
            
            if (is_array($result)) {

                echo '<table border=1>';
                echo '<th>Date</th><th>Screen Name</th><th>Text</th>';
                foreach($result as $tweet) {
                    $item = array();
                    $item[] = $tweet->created_at;
                    $item[] = $tweet->user->screen_name;
                    $item[] = $tweet->text;
                    echo '<tr><td>'. implode('</td><td>', $item) . '</td></tr>';
                }
                echo '</table>';
            }
        }
    }

    public function search($query, $since_id) {
        if ( $since_id > 0 ) {
            $parameters =  array('q' => $query, 'since_id' => $since_id, 'count'=>10);
        } else {
            $parameters =  array('q' => $query, 'count' => 100);         
        }
        $result = $this->twitter->get('search/tweets',$parameters);
        if ($this->twitter->getLastHttpCode() == 200) {
            $array = $result->statuses;
            if (is_array($array)) {

                echo '<table border=1>';
                echo '<th>ID</th><th>Date</th><th>Screen Name</th><th>Image</th><th>Text</th><th>Link</th>';
                foreach($array as $tweet) {
                    $item = array();
                    $item[] = $tweet->id;
                    $item[] = $tweet->created_at;
                    $item[] = $tweet->user->screen_name;
                    $item[] = '<div align=center><img src='. $tweet->user->profile_image_url . '></div>';
                    $item[] = $tweet->text;
                    if (strlen($tweet->text) > 140) {
                        continue;
                    }
                    /**
                     *  extract http link from the tweet
                     */
                    preg_match_all('!https?://\S+!', $tweet->text, $matches);
                    
                    $matched_links = $matches[0];
                    $links = '<a href=' . implode('>link</a> <a href=', $matched_links) . '>link</a>';
                    
                    $item[] = $links;
                    echo '<tr><td>'. implode('</td><td>', $item) . '</td></tr>';
                }
                echo '</table>';
            } else {
                var_dump($result);
            }
        }
    }
    
    public function search2array($query, $since_id) {
        if ( $since_id > 0 ) {
            $parameters =  array('q' => $query, 'since_id' => $since_id, 'count'=>100);
        } else {
            $parameters =  array('q' => $query, 'count' => 100);         
        }
                
        $result = $this->twitter->get('search/tweets',$parameters);
        
        if ($this->twitter->getLastHttpCode() == 200) {

            $array = $result->statuses;
            
            if (is_array($array)) {

               return $array;
            }
        }
    }
    
    public function postFavorites($id){
        $result = $this->twitter->post('favorites/create', array('id'=>$id));
        if ( $this->twitter->getLastHttpCode() == 200) {
            echo 'Favoriated<br>';
        } else {
            echo $this->twitter->getLastHttpCode() . '<br>';
        }
    }    
} // end of class
