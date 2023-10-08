<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of settings
 *
 * @author JPenagos
 */
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
         // Database connection settings           
          "db" => [
            "host" => "localhost",
            "dbname" => "avalon7m_master",
            "user" => "root",
            "pass" => ""
        ],
        "wp" => [
            "host" => "localhost",
            "dbname" => "avalon7m_wrdp2",
            "user" => "root",
            "pass" => ""
        ],
    ],
];
