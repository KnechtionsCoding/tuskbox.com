<?php
require_once('vendor/autoload.php');
include_once('config.php');

/*
 * Application setup, database connection, data sanitization and user  
 * validation routines are here.
 */



if ($credentialsAreValid) {

    $tokenId    = base64_encode(mcrypt_create_iv(32));
    $issuedAt   = time();
    $notBefore  = $issuedAt + 10;             //Adding 10 seconds
    $expire     = $notBefore + 60;            // Adding 60 seconds
    // Retrieves the server name from config file
    
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
        $secretKey, // The signing key //Make sure to have this in a config file instead 
        'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        );
        
    $unencodedArray = ['jwt' => $jwt];
    
    echo json_encode($unencodedArray);
}


?>


<!-- This is where the actual Webpage starts -->


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>TuskBox.com</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">

    <!-- Theme CSS -->
    <link href="css/grayscale.min.css" rel="stylesheet">

    

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

    <!-- Navigation -->
    <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                    Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">
                    <i class="fa fa-play-circle"></i> <span class="light">Tusk</span> Box
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
                <ul class="nav navbar-nav">
                    <!-- Hidden li included to remove active class from about link when scrolled up past about section -->
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#about">About</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#order">Order Now</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Intro Header -->
    <header class="intro">
        <div class="intro-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h1 class="brand-heading"TuskBox.com</h1>
                        <p class="intro-text">A Way to Make Caring for Your Teeth Cheaper and Easier.
                            <br>Partnered With Your Dentist</p>
                        <a href="#about" class="btn btn-circle page-scroll">
                            <i class="fa fa-angle-double-down animated"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- About Section -->
    <section id="about" class="container content-section text-center">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <h2>About TuskBox</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam faucibus imperdiet volutpat. Mauris lectus mi, efficitur nec luctus et, sollicitudin nec lorem. Sed tincidunt enim vitae congue convallis. Aliquam id ipsum metus. Nulla lectus ante, feugiat ac aliquam sed, euismod malesuada purus. Suspendisse in dui leo. Nam a mi vulputate, condimentum massa nec, egestas urna. Cras quis mauris in orci tempus aliquam sed ut massa. Ut in orci ac leo sollicitudin pellentesque in sed purus. Nulla in eros vitae purus cursus sodales. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Suspendisse potenti.</p>
                <p>Phasellus blandit quam at purus condimentum tempor. Morbi consectetur, eros sed aliquam consequat, dolor ipsum rhoncus nunc, nec iaculis dolor ante accumsan libero. Aenean ullamcorper consectetur est, interdum bibendum quam. Praesent consectetur blandit lectus, quis scelerisque neque sodales eu. Nulla auctor ullamcorper sem, nec pharetra mi efficitur sit amet. Duis hendrerit nisi non odio semper eleifend. Donec odio ligula, convallis nec dolor viverra, hendrerit blandit mi. In euismod quam sit amet suscipit pretium. Suspendisse ac est ut nibh imperdiet eleifend. Proin dignissim enim justo, vel accumsan massa cursus eget. Morbi mauris justo, interdum a laoreet sit amet, laoreet id nulla.</p>
                
            </div>
        </div>
    </section>

    <!-- Order Now Section -->
    <section id="order" class="content-section text-center">
        <div class="order-section">
            <div class="container">
                <div class="col-lg-8 col-lg-offset-2">
                    <h2>Order a Box Now</h2>
                    <p>Ut ultricies lacus at mauris vestibulum, eget aliquam est rhoncus. Nulla ac nisi mauris. Sed pellentesque est eu porta mattis. Duis egestas dictum lacus.</p>
                    
                    <script>$("#testLoad").load("ttps://www.payfacile.com/tuskboxcom/s/standard-box");        </script>
<div id="testLoad"></div>
                    <a href="https://www.payfacile.com/tuskboxcom/s/standard-box" class="btn btn-default btn-lg">Buy Now!</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="container content-section text-center">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <h2>Contact Start Bootstrap</h2>
                <p>Feel free to email us to provide some feedback on our templates, give us suggestions for new templates and themes, or to just say hello!</p>
                <p><a href="mailto:feedback@startbootstrap.com">feedback@startbootstrap.com</a>
                </p>
                <ul class="list-inline banner-social-buttons">
                    <li>
                        <a href="https://twitter.com/SBootstrap" class="btn btn-default btn-lg"><i class="fa fa-twitter fa-fw"></i> <span class="network-name">Twitter</span></a>
                    </li>
                    <li>
                        <a href="https://github.com/IronSummitMedia/startbootstrap" class="btn btn-default btn-lg"><i class="fa fa-github fa-fw"></i> <span class="network-name">Github</span></a>
                    </li>
                    <li>
                        <a href="https://plus.google.com/+Startbootstrap/posts" class="btn btn-default btn-lg"><i class="fa fa-google-plus fa-fw"></i> <span class="network-name">Google+</span></a>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <div id="map"></div>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p>Copyright &copy; Your Website 2016</p>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

    <!-- Google Maps API Key - Use your own API key to enable the map feature. More information on the Google Maps API can be found at https://developers.google.com/maps/ -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRngKslUGJTlibkQ3FkfTxj3Xss1UlZDA&sensor=false"></script>

    <!-- Theme JavaScript -->
    <script src="js/grayscale.min.js"></script>
    

</body>

</html>