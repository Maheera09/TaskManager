<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function handle_cors() //this function doesn't run by itself, rather it will be called by codeIgniter's hook system automatically before every request.
{
    $allowed_origin = 'http://localhost:5173'; 

    header("Access-Control-Allow-Origin: $allowed_origin");
    header("Access-Control-Allow-Credentials: true"); //this tells the browser that cookies, sessions, and authentication tokens are allowed.
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); //This tells the browser which request headers the frontend is allowed to send.

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        header('HTTP/1.1 200 OK'); //if true, you may continue the request.
        exit(); //stops the script immediately. There's no need to load controllers or query the database because the browser only wanted permission.
		
		/*Basically, 
		This checks: "Is this request an OPTIONS request?"
		Browsers automatically send OPTIONS before certain requests.
		React sends

		For example, POST /login
		Browser first sends: OPTIONS /login

		asking:
		"Hey server...
		Can I send this POST request?
		Is it allowed?"

		This is called a preflight request.
		*/
    }
}
