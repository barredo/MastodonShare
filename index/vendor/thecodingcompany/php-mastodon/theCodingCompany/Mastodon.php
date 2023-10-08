<?php
/**
 * Intellectual Property of #Mastodon
 * 
 * @copyright (c) 2017, #Mastodon
 * @author V.A. (Victor) Angelier <victor@thecodingcompany.se>
 * @version 1.0
 * @license http://www.apache.org/licenses/GPL-compatibility.html GPL
 * 
 */
namespace theCodingCompany;

use \theCodingCompany\HttpRequest;

/**
 * Mastodon main class
 */
class Mastodon
{
    //Mastodon oAuth
    use \theCodingCompany\oAuth;
    
    /**
     * Holds our current user_id for :id in API calls
     * @var string
     */
    private $mastodon_user_id = null;
    
    /**
     * Holds our current userinfo
     * @var array
     */
    private $mastodon_userinfo = null;
    
    /**
     * Construct new Mastodon class
     */
    public function __construct($domainname = "") {
        
        //Set the domain name to use
        $this->setMastodonDomain($domainname);
        // p("CONSTRUCT".$domainname);
    }

    /**
     * Create an App and get client_id and client_secret
     * @param string $name
     * @param string $website_url
     * @return array|bool
     */
    public function createApp($name, $website_url){
        if(!empty($name) && !empty($website_url)){
            
            //Set our info
            $this->app_config["client_name"] = $name;
            $this->app_config["website"]     = $website_url;
            
            return $this->getAppConfig();
        }
        return false;
    }

    /**
     * Authenticate the user
     * @param string $username
     * @param string $password
     * @return $this
     */
    public function authenticate($username = null, $password = null) {
        $this->authUser($username, $password);
        
        //Set current working userid
        $this->mastodon_userinfo = $this->getUser();
        
        return $this; //For method chaining
    }

    /**
     * Post a new status to your {visibility} timeline
     * @param string $text
     * @param string $visibility
     * @return HttpRequest | bool
     */
    public function postStatus($text = "", $visibility = "public", $in_reply_to_id = null){
        if(!empty($this->getCredentials())){
            
            $headers = $this->getHeaders();

            // p("!!!");
            // p($headers);
            // p("!!!");
            
            //Create our object
            $http   = HttpRequest::Instance($this->getApiURL());
            $status = $http::Post(
                "api/v1/statuses",
                array(
                    "status"         => $text,
                    "visibility"     => $visibility,
                    "in_reply_to_id" => $in_reply_to_id,
                    // "media_ids" => [],
                ),
                $headers
            );

            // p("!!!!");
            // p($status);
            // p("!!!!");
            return $status;
        }
        return false;
    }

    public function actionAccount($action,$account_id){

        $valid_actions = [
            'follow'    => 'follow'
        ];

        $check_actions = [
            'follow'    => 'following'
        ];

        if(!isset($valid_actions[$action])) {
            return 'Not valid action.';
        } 

        if(!empty($this->getCredentials())){
            $headers = $this->getHeaders();
            //Create our object
            $http    = HttpRequest::Instance($this->getApiURL());
            $account = $http::Post(
                "api/v1/accounts/".$account_id."/".$valid_actions[$action],
                false,
                $headers
            );

            // p($account);

            return isset($account[$check_actions[$valid_actions[$action]]]) ? 
                $account[$check_actions[$valid_actions[$action]]] : false;
        }
        return false;
    }

    public function actionStatus($action,$status_id){

        $valid_actions = [
            'favourite' => 'favourite',
            'like'      => 'favourite',
            'reblog'    => 'reblog',
            'boost'     => 'reblog',
            'bookmark'  => 'bookmark'
        ];

        $check_actions = [
            'favourite' => 'favourited',
            'reblog'    => 'reblogged',
            'bookmark'  => 'bookmarked'
        ];

        if(!isset($valid_actions[$action])) {
            return 'Not valid action';
        } 

        if(!empty($this->getCredentials())){
            
            $headers = $this->getHeaders();

            // p("!!!");
            // p($headers);
            // p("!!!");
            
            //Create our object
            $http   = HttpRequest::Instance($this->getApiURL());
            $status = $http::Post(
                "api/v1/statuses/".$status_id."/".$valid_actions[$action],
                false,
                $headers
            );

            // p("!!!!");
            // // p($status);
            // p($action);
            // p($valid_actions[$action]);
            // p($check_actions[$valid_actions[$action]]);
            // p($status[$check_actions[$valid_actions[$action]]]);
            // p($status['favourited']);
            // p("!!!!");
            // exit;
            return isset($status[$check_actions[$valid_actions[$action]]]) ? 
                $status[$check_actions[$valid_actions[$action]]] : false;
        }
        return false;
    }
    /**
     * Get mastodon user
     */
    public function getLists(){        
        if(empty($this->mastodon_userinfo)){

            $http    = HttpRequest::Instance($this->getApiURL());
            $results = $http::Get(
                "api/v1/lists",
                null,
                $this->getHeaders()
            );
            
            if(is_array($results) && count($results) > 0){
                return $results;
            }
            return [];
        }
        return [];
    }
    public function getUser(){        
        if(empty($this->mastodon_userinfo)){
            //Create our object
            $http = HttpRequest::Instance($this->getApiURL());
            $user_info = $http::Get(
                "api/v1/accounts/verify_credentials",
                null,
                $this->getHeaders()
            );
            if(is_array($user_info) && isset($user_info["username"])){
                $this->mastodon_user_id = (int) $user_info["id"];
                return $user_info;
            }else{
                echo "Authentication or authorization failed\r\n";
            }
        }
        return $this->mastodon_userinfo;
    }
    
    /**
     * Get current user's followers
     */
    public function getFollowers(){
        if($this->mastodon_user_id > 0){
            
            //Create our object
            $http = HttpRequest::Instance($this->getApiURL());
            $accounts = $http::Get(
                "api/v1/accounts/{$this->mastodon_user_id}/followers",
                null,
                $this->getHeaders()
            );
            if(is_array($accounts) && count($accounts) > 0){
                return $accounts;
            }
            
        }
        return false;
    }
    
    /**
     * Get current user's following
     */
    public function getFollowing(){
        if($this->mastodon_user_id > 0){
            
            //Create our object
            $http = HttpRequest::Instance($this->getApiURL());
            $accounts = $http::Get(
                "api/v1/accounts/{$this->mastodon_user_id}/following",
                null,
                $this->getHeaders()
            );
            if(is_array($accounts) && count($accounts) > 0){
                return $accounts;
            }
            
        }
        return false;
    }
    
    /**
     * Get current user's statuses
     */
    public function getStatuses(){
        if($this->mastodon_user_id > 0){
            
            //Create our object
            $http = HttpRequest::Instance($this->getApiURL());
            $statuses = $http::Get(
                "api/v1/accounts/{$this->mastodon_user_id}/statuses",
                null,
                $this->getHeaders()
            );
            if(is_array($statuses) && count($statuses) > 0){
                return $statuses;
            }
            
        }
        return false;
    }

    public function getProfileId($profile_address,$get_headers = false){

        if(empty($profile_address)) {
            return [];
        }

        $instance = only_instance($profile_address);
        $user     = only_user($profile_address);

        // p([$instance,$user]);

        $headers = $get_headers ? $this->getHeaders() : [];

        // p("mau".$this->mastodon_api_url);
        $http    = HttpRequest::Instance($this->getApiURL());
        $profile = $http::GetUrl(
            $this->getApiURL(),
            "api/v1/accounts/lookup",
            ["acct" => $user.'@'.$instance],
            $headers
        );

        if(is_array($profile) && isset($profile['id'])){
            return $profile['id'];
        }

        return false;
    }

    public function getProfile($profile_address,$get_headers = true){

        $account_id = $this->getProfileId($profile_address,$get_headers);

        if(empty($account_id)) {
            return [];
        }

        $headers = $get_headers ? $this->getHeaders() : [];

        // p("mau".$this->mastodon_api_url);
        $http    = HttpRequest::Instance($this->getApiURL());
        $profile = $http::GetUrl(
            $this->getApiURL(),
            "api/v1/accounts/".($account_id),
            null,
            $headers
        );
        // p($profile);
        // p("principal");
        if(is_array($profile)){
            return $profile;
        }
        return [];
    }

    public function getStatus($status_id,$get_headers = false){

        $headers = $get_headers ? $this->getHeaders() : [];

        // p("mau".$this->mastodon_api_url);
        $http = HttpRequest::Instance($this->getApiURL());
        $status = $http::GetUrl(
            $this->getApiURL(),
            "api/v1/statuses/".($status_id),
            null,
            $headers
        );

        // p("principal");
        if(is_array($status)){
            return $status;
        }
        return [];
    }

    public function getStatusContext($status_id,$get_headers = false){

        $headers = $get_headers ? $this->getHeaders() : [];

        // p("mau".$this->mastodon_api_url);
        $http = HttpRequest::Instance($this->getApiURL());
        $status = $http::GetUrl(
            $this->getApiURL(),
            "api/v1/statuses/".($status_id).'/context',
            null,
            $headers
        );

        // p("principal");
        if(is_array($status)){
            return $status;
        }
        return [];
    }


    public function getStatusExternal($status_id,$get_headers = false){

        $headers = $get_headers ? $this->getHeaders() : [];
        // p("externo");
        // $status_id = 109575164029976898;
        // $http = HttpRequest::Instance("https://social.lansky.name");
        // $http   = HttpRequest::Instance($this->getApiURL());
        // $http = new HttpRequest();
        // $http_request = new HttpRequest($this->getApiURL());
        // p("mau".$this->mastodon_api_url);
        $http = HttpRequest::Instance($this->getApiURL());
        $status = $http::GetUrl(
            $this->getApiURL(),
            "api/v1/statuses/".($status_id),
            null,
            $headers
        );
        if(is_array($status)){
            return $status;
        }

        return [];
    }


    public function getStatusExternalContext($status_id,$get_headers = false){

        $headers = $get_headers ? $this->getHeaders() : [];
        // p("externo");
        // $status_id = 109575164029976898;
        // $http = HttpRequest::Instance("https://social.lansky.name");
        // $http   = HttpRequest::Instance($this->getApiURL());
        // $http = new HttpRequest();
        // $http_request = new HttpRequest($this->getApiURL());
        // p("mau".$this->mastodon_api_url);
        $http = HttpRequest::Instance($this->getApiURL());
        $status = $http::GetUrl(
            $this->getApiURL(),
            "api/v1/statuses/".($status_id).'/context',
            null,
            $headers
        );
        if(is_array($status)){
            return $status;
        }

        return [];
    }

    public function getResults($endpoint,$args = []){

        // if($this->mastodon_user_id > 0){
            
            //Create our object
            $http = HttpRequest::Instance($this->getApiURL());

            // if(count($args) > 0) {
            //     foreach($args as $arg => $value) {
            //         $valid_args = ['max_id','since_id','min_id','limit'];
            //         if(!in_array($arg, $valid_args)) {
            //             unset($args[$arg]);
            //         }
            //     }
            // }
            
            $results = $http::Get(
                $endpoint,
                $args,
                $this->getHeaders()
            );
            
            if(is_array($results) && count($results) > 0){
                return $results;
            }
        // }
        return [];
    }

    public function getSearchResults($endpoint,$args = []){

        // if($type !== 'statuses') {
        //     return false;
        // }

        // if($this->mastodon_user_id > 0){
        $args['type'] = 'statuses';
            
            //Create our object
        $http = HttpRequest::Instance($this->getApiURL());

        // if(count($args) > 0) {
        //     foreach($args as $arg => $value) {
        //         $valid_args = ['q','type','max_id','since_id','min_id','limit'];
        //         if(!in_array($arg, $valid_args)) {
        //             unset($args[$arg]);
        //         }
        //     }
        // }
        
        $results = $http::Get(
            $endpoint,
            $args,
            $this->getHeaders()
        );

        $results = $results[$type];
        
        if(is_array($results) && count($results) > 0){
            return $results;
        }
        // }
        return [];
    }

    public function getTimeline($endpoint,$args = []){

        // if($endpoint !== '/api/v1/timelines/home') {
        //     return false;
        // }

        // if($this->mastodon_user_id > 0){
            
            //Create our object
        $http = HttpRequest::Instance($this->getApiURL());

        // if(count($args) > 0) {
        //     foreach($args as $arg => $value) {
        //         $valid_args = ['max_id','since_id','min_id','limit'];
        //         if(!in_array($arg, $valid_args)) {
        //             unset($args[$arg]);
        //         }
        //     }
        // }
        
        $results = $http::Get(
            $endpoint,
            $args,
            $this->getHeaders()
        );
        
        if(is_array($results) && count($results) > 0){
            return $results;
        }
        // }
        return [];
    }

    /**
     * Get current user's notifications. If $since_id is provided, will only get the items
     * after since_id.
     * 
     */
    public function getNotifications($args = []){
        $http = HttpRequest::Instance($this->getApiURL());
        
        $notifications = $http::Get(
            "api/v1/notifications",
            $args,
            $this->getHeaders()
        );
        
        if(is_array($notifications) && count($notifications) > 0){
            return $notifications;
        }
        return [];
    }

    /**
     * Clears the user's notifications. Returns true if successful.
     * 
     */
    public function clearNotifications(){
        if($this->mastodon_user_id > 0){
            
            //Create our object
            $http = HttpRequest::Instance($this->getApiURL());
            
            $clear_result = $http::Post(
                "api/v1/notifications/clear",
                null,
                $this->getHeaders()
            );
            
            if(is_array($clear_result)) {
                return true;
            }
            else {
                return false;
            }
        }
        return false;
    }
    
}
