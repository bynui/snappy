<?php

/**
 * This is a simple usage example.
 * Your request methods (get, post, put, patch, delete) are defined as functions
 * 
 * Note in this example, each endpoint uses the query string parameter "?format=" just to demonstrate different outputs 
 * to the available formats: JSON, JSONP, XML & HTML template. The default output is JSON if there's no "?format=" in the url.
 * If there's jsonpcallback query string in url ("?jsonpcallback=your_callback_name") then it outputs as JSONP.
 * In the real code choose the desired output format. Default is JSON.
 * 
 * You can create parameter schema by calling Utils::constructSchema() to collect all parameters automatically
 * or you can construct parameter schema manually:
 * $schema = [
 *  ":key1" => "value1",
 *  ":key2" => "value2",
 * ];
 * 
 * All callback values are represented as $callbackvalues on each route.
 * 
 * For complete documentation please refer here: https://bynui.github.com/snappy-docs
 * For dummy data reference please refer to the exported SQL file in the repository
 * 
 */

namespace Controller;

use Core\Controller;
use Core\Utils;
use Core\Config;
use Model\Examples as ExamplesModel;
use Helper\Mailer;

class Examples extends Controller{
    private $model = null;

    function __construct(){
        $this->model = new ExamplesModel();
    }

    public function get(){
        $this->map([
            /**
             * example without parameter
             * url: http://yourdomain/examples
             */
            "/" => function( $callbackvalues ){
                $result = $this->model->getAllExamples();
                $format = $callbackvalues::getData("queries.format");

                if ($format == "xml"){
                    return $this->xml($result);
                } else if ($format == "html"){
                    return $this->html("/web-page/view-examples.php",$result);
                } else {                    
                    $json = $this->json($result);
                    return $json;
                }
            },

            "/try-catch" => function( $callbackvalues ){
                try{
                    // intentionally call undefined function to create error for try-catch
                    $json = $this->undefinedFunction();
                    return $json;
                }catch(\Throwable $e){
                    throw new \Core\Error\ErrorWrapper($e);
                }
            },

            /**
             * example with 1 parameter
             * url: http://yourdomain/examples/10
             */
            "/:id" => function( $callbackvalues ){
                // construct schema manually
                $schema = [
                    ":id" => Utils::validateString( $callbackvalues::getData("keys.id") )
                ];
                $result = $this->model->getExampleById( $schema );
                $format = $callbackvalues::getData("queries.format");
                if ($format == "xml"){
                    return $this->xml($result);
                } else if ($format == "html"){
                    return $this->html("/web-page/view-examples.php",$result);
                } else {
                    $json = $this->json($result);
                    return $json;
                }
            },

            /**
             * example with more specific url with 1 parameter placeholder and query string for pagination
             * url: http://yourdomain/examples/status/the_status?page=1
             */
            "/status/:status" => function( $callbackvalues ){
                $page = empty($callbackvalues::getData("queries")) ? 0 : $callbackvalues::getData("queries.page");
                
                // construct schema automatically
                $schema = Utils::constructSchema($callbackvalues::getData("keys"));
                
                /**
                 * We're calling Utils::constructSchema() to create formated SQL parameter automatically, 
                 * its result will be carried as parameter array to SQL query in the model file.
                 * Since there is no placeholder for ":format" parameter in the SQL query, 
                 * it has to be removed from schema using unset() to avoid PDO "invalid parameter number" error
                 */
                
                unset($schema[":format"]);

                // after removed parameter can be passed to the model
                $result = $this->model->getByStatus( $schema, $page * 10 );
                $format = $callbackvalues::getData("queries.format");
                if ($format == "xml"){
                    return $this->xml($result);
                } else if ($format == "html"){
                    return $this->html("/web-page/view-examples.php",$result);
                } else {
                    return $this->json($result);
                }
            },

            /**
             * example with more specific url and more than 1 parameter placeholders
             * url: http://yourdomain/search/the_keyword/status/the_status
             */
            "/search/:keyword/status/:status" => function( $callbackvalues ){
                // construct schema automatically
                $schema = Utils::constructSchema($callbackvalues::getData("keys"));
                $result = $this->model->searchExample( $schema );
                unset($schema[":format"]);
                $format = $callbackvalues::getData("queries.format");
                if ($format == "xml"){
                    return $this->xml($result);
                } else if ($format == "html"){
                    return $this->html("/web-page/view-examples.php",$result);
                } else {
                    return $this->json($result);
                }
            },

            "/email/:id" => function( $callbackvalues ){
                /**
                 * This endpoint is to send email using PHPMailer library.
                 * If you test this on local mail server please setup an email account on your local mail server.
                 * Change email address from one of the examples data to your local email account.
                 * The email template source can be viewed under /view/email-templaye/theme1
                 */
                try{
                    $schema = [
                        ":id" => Utils::validateString( $callbackvalues::getData("keys.id") )
                    ];
                    $record = $this->model->getExampleById( $schema );
                    $templateString = $this->html("/email-template/theme1/index.php", [
                        "FullName" => $record["result"][0]["FirstName"] . " " . $record["result"][0]["LastName"]
                    ]);
                    $mail = new Mailer(); 
                    $mailResult = $mail->sendMail(
                        $record["result"][0]["Email"], 
                        $record["result"][0]["FirstName"],
                        "Email testing",
                        $templateString,
                        [
                            [
                                "cid" => "mytheme1cid",
                                "image" => Config::getConfig("email")["embedimage"]."/theme1/images/header.jpg"
                            ]
                        ]
                    );
                    if ($mailResult["status"] == 200){
                        $mailResult["message"] = "Email has been sent";
                        $mailResult["result"] = $record["result"];
                        return $this->json($mailResult);
                    }else{
                        return $this->json($mailResult);
                    }
                }catch(\Throwable $e){                    
                    throw new \Core\Error\ErrorWrapper($e);
                }
            }

        ]);
    }

    public function delete(){
        $this->map([
            "/:id" => function( $callbackvalues ){
                // construct schema automatically
                $schema = Utils::constructSchema($callbackvalues::getData("keys"));
                // check if id exists
                $check = $this->model->getExampleById( $schema );
                if (!empty($check["result"])){
                    $result = $this->model->deleteExample( $schema );
                    return $this->json($result);
                }else{
                    die(Utils::response(404) );
                }                
            }
        ]);
    }

    /**
     * the parameters should be the same as in parameter placeholder in SQL model file.
     * There are :first, :last, :email
     */
    public function post(){
        $this->map([
            "/" => function( $callbackvalues ){
                // construct schema automatically
                $schema = Utils::constructSchema($callbackvalues::getData("data"));
                $result = $this->model->newExample( $schema );
                return $this->json($result);
            }
        ]);
    }

    public function put(){
        $this->map([
            "/:id" => function( $callbackvalues ){
                // construct schema automatically
                $id = Utils::constructSchema($callbackvalues::getData("keys"));
                // check if id exists
                $check = $this->model->getExampleById( $id );
                if (!empty($check["result"])){
                    // construct schema manually
                    $schema = [
                        ":first" => Utils::validateString( $callbackvalues::getData("data.first") ),
                        ":last" => Utils::validateString( $callbackvalues::getData("data.last") ),
                        ":email" => Utils::validateString( $callbackvalues::getData("data.email") ),
                        ":id" => Utils::validateString( $callbackvalues::getData("keys.id") )
                    ];
                    $result = $this->model->updateExample( $schema );
                    return $this->json($result);
                }else{
                    die(Utils::response(404) );
                }
            }
        ]);
    }    
}

?>