<?php
class Quote{
    static private $CATEGORIES = array("inspire", "management", "sports", "life", "funny");
    static private $ENDPOINT = "http://quotes.rest/qod.json?category=%s";

    private $db;
    private $category;

    private $testResponse = '{ "success": { "total": 1 }, "contents": { "quotes": [ { "quote": "If you like what you do, and you’re lucky enough to be good at it, do it for that reason.", "length": "96", "author": "Phil Grimshaw", "tags": [ "inspire", "luck", "reason", "tso-life" ], "category": "inspire", "date": "2016-10-09", "title": "Inspiring Quote of the day", "background": "https://theysaidso.com/img/bgs/man_on_the_mountain.jpg", "id": "j1sPwFauvgEBPe9xEzmT3weF" } ] } }';

    public function __construct($category=null, $db){
        $this->category = $this->validateCategory($category);
        $this->db = $db;
    }

    public function fetchQuote(){
        $quoteFromDB = $this->fetchFromDatabase();

        // If already in DB then return that
        if($quoteFromDB != null){
            $quote = json_decode($quoteFromDB['json_blurb'], true);
            $quote['likes'] = $quoteFromDB['likes'];
            $quote['views'] = $quoteFromDB['views'];
            return $quote;
        }

        // fetch from API
        $quote = $this->callApi();

        // store in the DB:
        $stmt = $this->db->prepare("INSERT INTO `my_quotes` (`quote_id`, `created`, `category`, `json_blurb`, `likes`, `views`) VALUES(?, NOW(), ?, ?, ?, ?)");
        $stmt->execute(array($quote['id'], $quote['category'], json_encode($quote), 0, 1));

        return $quote;
    }

    private function fetchFromDatabase(){
        $stmt = $this->db->prepare("SELECT * FROM `my_quotes` WHERE DATE(`created`) = DATE(NOW()) AND `category` = ?");
        $stmt->execute(array($this->category));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($rows)){
            $this->db->exec("UPDATE `my_quotes` SET `views` = `views`+1 WHERE `quote_id`='".$rows[0]['quote_id']."' LIMIT 1");
            return $rows[0];
        }
        return null;
    }

    private function callApi(){
        // prepare the URL to call
        $endpoint = sprintf( self::$ENDPOINT, $this->category );

        // call URL and get contents
        $content = file_get_contents($endpoint);

        // Check if Backend API has failed to return a successul response
        if($content===FALSE){
            throw new Exception("Backend service failed to return a response. Possibly throttling our request.");
        }

        // Parse JSON response
        $response = json_decode($content, true);

        // Return just the quote
        $quote = $response['contents']['quotes'][0];
        $quote['requested_category'] = $this->category;
        if(!$quote['id']){
            $quote['id'] = substr( md5($str), 0, 32); // just a unique id if missing
        }

        return $quote;
    }

    private function validateCategory($category){
        if($category==null){
            return $this->getRandomCategory(); // if category not specific then pick a random one
        }

        $categoryName = strtolower($category);

        // Is it a valid category?
        if( !in_array($categoryName, self::$CATEGORIES) ){
            throw new Exception("Category: $categoryName is invalid.");
        }

        return $categoryName;
    }

    public function getCategories(){
        return self::$CATEGORIES;
    }

     public function getRandomCategory(){
        return self::$CATEGORIES[array_rand(self::$CATEGORIES, 1)];
    }
}