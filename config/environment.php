<?php

return [
    "env" => [
        "development" => "http://localhost/opensource/nui/snappy", // change to your path
        "staging" => "https://yourstagingdomain.com",
        "production" => "https://yourproductiondomain.com"
    ],
    "development" => [
        "db" => [
            "driver" => "mysql", //mysql | postgresql | sqlserver
            "host" => "localhost",
            "name" => "snappy",
            "user" => "root",
            "pwd" => "",
            "port" => "3306"
        ],
        "email" => [
            "host" => "localhost",
            "displayname" => "Snappy Framework by Nui",
            "replyto" => "snappy@localhost.com",
            "username" => "snappy@localhost.com",
            "email" => "snappy@localhost.com",
            "password" => "snappy",
            "embedimage" => "/src/view/email-template"
        ],
        "view" => "/src/view",
        "assets" => "/assets",
        "showerror" => true,
        "logerror" => true,
    ],
    "staging" => [
        "db" => [
            "driver" => "your_preferred_database", //mysql | postgresql | sqlserver
            "host" => "your_server_name",
            "name" => "your_db_name",
            "user" => "your_db_username",
            "pwd" => "your_db_password",
            "port" => "your_db_port"
        ],
        "email" => [
            "host" => "yourdomain",
            "displayname" => "Your display name",
            "replyto" => "youraccount@yourdomain.com",
            "username" => "yourusername@yourdomain.com",
            "email" => "yourusername@yourdomain.com",
            "password" => "yourpassword",
            "embedimage" => "/src/your/image/path"
        ],
        "view" => "/src/view",
        "assets" => "/assets",
        "showerror" => true,
        "logerror" => true,
    ],
    "production" => [
        "db" => [
            "driver" => "your_preferred_database", //mysql | postgresql | sqlserver
            "host" => "your_server_name",
            "name" => "your_db_name",
            "user" => "your_db_username",
            "pwd" => "your_db_password",
            "port" => "your_db_port"
        ],
        "email" => [
            "host" => "yourdomain",
            "displayname" => "Your display name",
            "replyto" => "youraccount@yourdomain.com",
            "username" => "yourusername@yourdomain.com",
            "email" => "yourusername@yourdomain.com",
            "password" => "yourpassword",
            "embedimage" => "/src/your/image/path"
        ],
        "view" => "/src/view",
        "assets" => "/assets",
        "showerror" => false,
        "logerror" => true,
    ]
];

?>