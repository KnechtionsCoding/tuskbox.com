<?php
require_once('vendor/autoload.php');

/*
 * Application setup, database connection, data sanitization and user  
 * validation routines are here.
 */
$config = Factory::fromFile('config/config.php', true); // Create a Zend Config Object

if ($credentialsAreValid) {

    $tokenId    = base64_encode(mcrypt_create_iv(32));
    $issuedAt   = time();
    $notBefore  = $issuedAt + 10;             //Adding 10 seconds
    $expire     = $notBefore + 60;            // Adding 60 seconds
    $serverName = $config->get('serverName'); // Retrieve the server name from config file
    
    /*
     * Create the token as an array
     */
    $data = [
        'iat'  => $issuedAt,         // Issued at: time when the token was generated
        'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
        'iss'  => $serverName,       // Issuer
        'nbf'  => $notBefore,        // Not before
        'exp'  => $expire,           // Expire
        'data' => [                  // Data related to the signer user
            'userId'   => $rs['id'], // userid from the users table
            'userName' => $username, // User name
        ]
    ];

    
    
    /*
     * Extract the key, which is coming from the config file. 
     * 
     * Best suggestion is the key to be a binary string and 
     * store it in encoded in a config file. 
     *
     * Can be generated with base64_encode(openssl_random_pseudo_bytes(64));
     *
     * keep it secure! You'll need the exact key to verify the 
     * token later.
     */
    
    $secretKey = base64_decode($config->get('jwtKey'));
    
    /*
     * Encode the array to a JWT string.
     * Second parameter is the key to encode the token.
     * 
     * The output string can be validated at http://jwt.io/
     */
    
    $jwt = JWT::encode(
        $data,      //Data to be encoded in the JWT
        $secretKey, // The signing key
        'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        );
        
    $unencodedArray = ['jwt' => $jwt];
    
    echo json_encode($unencodedArray);
}