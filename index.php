<?php
// déterminer l'instance utilisée
switch($_SERVER['HTTP_HOST']) {
	case 'www.nek.ovh':$inst='NekoVH';break;
	default:$inst='Kitsu.eu';
}

// traitement du flux ATOM
if(isset($_GET['feed'])) {
	if(!file_exists('./feed.json')) header('Location: ./');
	$feed=json_decode(file_get_contents('./feed.json'), TRUE);
	header('content-type: application/atom+xml; charset=UTF-8');
	echo '<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:thr="http://purl.org/syndication/thread/1.0" xml:lang="en-US">
	<title type="text">Kitsu.eu ~ news</title>
	<id>https://www.kitsu.eu/</id>
	<updated>'.date(DATE_ATOM, $feed['0']['timestamp']).'</updated>
	<link rel="self" href="https://www.kitsu.eu/?feed" />
	';

	foreach($feed as $entry) {
		echo '<entry><author><name>Mitsu</name><uri>https://www.kitsu.eu/</uri></author>'."\n";
		echo '<title type="text">'.$entry['title'].'</title>'."\n";
		echo '<link rel="alternate" type="text/html" href="https://www.kitsu.eu/#'.$entry['timestamp'].'" />'."\n";
		echo '<id>https://www.kitsu.eu/#'.$entry['timestamp'].'</id>'."\n";
		echo '<updated>'.date(DATE_ATOM, $entry['timestamp']).'</updated>'."\n";
		echo '<content type="text">'.$entry['content'].'</content>'."\n";
		echo '</entry>'."\n\n";
	}

	die('</feed>');
}

// redirection pages legacy
if(!isset($_GET['p'])) {
	$pagetitle='';
}
else {
	switch($_GET['p']) {
		case 'access': header('Location: ./#access');break;
                case 'tools':header('Location: ./#portfolio');break;
                case 'repertory':header('Location: ./#repertory');break;
		default:$pagetitle='';
	}
}

// traitement formulaire envoi email
$messageenvoi = '';
if(isset($_POST['xmess']) and isset($_POST['xemiss']) ) {
	$email_from = htmlentities(strip_tags($_POST['xemiss']));
	$comments = htmlentities(strip_tags(stripslashes($_POST['xmess'])));
	$subject = htmlentities(strip_tags(stripslashes($_POST['xsuj'])));

	function clean_string($string) {
		$bad = array("content-type","bcc:","to:","cc:","href");
		return str_replace($bad,"",$string);
	}
	$email_message = "".clean_string($comments)."\n";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	$headers .= "From: $email_from\r\n";
	$headers .= 'Reply-To: '.$email_from."\r\n" .
	 'X-Mailer: PHP/' . phpversion();
 
	if(strpos($email_message, '---BEGIN PGP MESSAGE---') !== FALSE) {
		@mail('adressemail@localhost', '[contact kitsu.eu] '.clean_string($subject), $email_message, $headers); 
		$messageenvoi = '<span style="color:white;background-color:green;padding:0.3em;"> ✔ Message envoyé avec succès ✔ </span>';
	}
	else {
		$messageenvoi = '<span style="color:red;background-color:yellow;font-weight:bold;padding:0.3em;"> ✖ Envoi rejeté: le message n\'est pas chiffré. Pensez à activer javascript !</span>';
	}


}


?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

	<title>
	<?php echo $inst; ?> ~ hébergement libre
	</title>

    <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/freelancer.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<link href="favicon-<?php echo $inst; ?>.png" rel="icon" type="image/png">
<link rel="alternate" type="application/rss+xml" title="Kitsu.eu ~ news" href="https://www.kitsu.eu/?feed"/>
</head>

<body id="page-top" class="index">
<?php
	if($inst=='NekoVH')
		echo '<div style="background-color:#fff99b;text-align:center;position:absolute;bottom:0;font-size:x-large;"><b>⚠ NekoVH utilise CloudFlare, entreprise américaine, pour contourner les blocages IP de certains régimes politiques ⚠<br>Si possible, <a href="//www.kitsu.eu/">basculez sur Kitsu</a> pour une connexion directe.</b></div>';
?>
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#page-top"><?php echo $inst; ?> ~ hébergement libre</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li class="page-scroll">
                        <a href="#portfolio">Applications</a>
                    </li>

                    <li class="page-scroll">
                        <a href="#about">À propos</a>
                    </li>
                    <li class="page-scroll">
                        <a href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <img class="img-responsive" src="img/profile.png" alt="">
                    <div class="intro-text">
                        <span class="name"><?php echo $inst; ?></span>
                        <hr class="star-light">
                        <span class="skills">Hébergement de services respectueux de la vie privée<br>⏬ ⏬ ⏬

		</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Portfolio Grid Section -->
    <section id="portfolio">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Applis</h2>

                    <hr class="star-primary">
                </div>
            </div>
            <div class="row">
               <!-- <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal1" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/cabin.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal2" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/cake.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal3" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/circus.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal4" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/game.png" class="img-responsive" alt="">
                    </a>
                </div> -->
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal5" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/safe.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal6" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/submarine.png" class="img-responsive" alt="">
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="success" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>À propos</h2>
                    <hr class="star-light">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-lg-offset-2">
                    <p>Kitsu.eu est un service d'hébergement applicatif personnel (SaaS) respectueux de la vie privée. Les applications proposées sont libres, performantes et simples d'usage.<br>

<a href="https://en.wikipedia.org/wiki/Warrant_canary">
        <img alt="canary" title="Le canari est vivant !" src="warrant-canary-ok.svg" style="height:5em;background-color:white;border:2px solid #000;border-radius:15px;">

<!--        <img alt="canary" title="Le canari est mort !" src="warrant-canary-dead.svg" style="height:7em;background-color:#333;border:2px solid #000;border-radius:15px;"> -->

</a>
			</p>
                </div>
                <div class="col-lg-4">
                    
                </div>



            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Contact</h2>
                    <hr class="star-primary">
            (formulaire chiffré avec PGP)
                </div>
	
            </div>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <!-- To configure the contact form email address, go to mail/contact_me.php and update the email address in the PHP file on line 19. -->
                    <!-- The form should work on most web servers, but if the form is not working you may need to configure your web server differently. -->
                    <form name="contactform" id="contact" method="post" action="./#contact" onsubmit="return encrypt();" novalidate>

                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Email</label>
                                <input type="email" class="form-control" placeholder="Adresse Email" name="xemiss" id="mail" required data-validation-required-message="Please enter your email address.">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Sujet</label>
                                <input type="text" name="xsuj" class="form-control" placeholder="Sujet" id="subject" required data-validation-required-message="Please enter subject.">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Message</label>
                                <textarea rows="5" class="form-control" placeholder="Message" id="message" name="xmess" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <br>
                        <div id="success"></div>
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <button type="submit" name="send" id="send" class="btn btn-success btn-lg">Chiffrer et Envoyer</button><br>
				<?php echo $messageenvoi; ?>
                            </div>
                        </div>
                    </form>
                </div>
			<script src="jquery.min.js" type="text/javascript" charset="utf-8"></script>
			<script src="libencrypt.js" type="text/javascript" charset="utf-8"></script>
			<script type="text/javascript">
			if (! window.crypto.getRandomValues) {
				$('#form').html("Vraiment désolé, votre navigateur n'est pas compatible avec la technologie requise. Utilisez Chrome >= 11, Safari >= 3.1, Firefox >= 21 ou Opera >= 12");
				$('#form').addClass('error');
			}
			</script>
		<pre id="pubkey" style="display:none;">
		<?php echo file_get_contents('mitsu.pub.asc.txt'); ?>
		</pre>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <div class="footer-above">
            <div class="container">
                <div class="row">
                    <div class="footer-col col-md-4">
                        <h3>Prestataire technique</h3>
                        <p><a href="https://www.web4all.fr/">Association Web4all</a><br>137 rue Mouffetard - 75005 PARIS</p>
                    </div>
                    <div class="footer-col col-md-4">
                        <h3>Social</h3>
                        <ul class="list-inline">
                            <li>
                                <a href="http://r.ée.eu" class="btn-social btn-outline"><img alt="" src="sprite_mitsu.png"><i class="fa fa-fw "></i></a>
                            </li>
                            <li>
                                <a href="http://t.ée.eu" class="btn-social btn-outline"><img alt="" src="sprite_twitter.png"><i class="fa fa-fw "></i></a>
                            </li>
                            <li>
                                <a href="http://l.ée.eu" class="btn-social btn-outline"><img alt="" src="sprite_shaarli.png"><i class="fa fa-fw"></i></a>
                            </li>

                        </ul>
                    </div>
                    <div class="footer-col col-md-4">
                        <h3>News</h3>
                        <p><span style="float:right;padding:0.3em;border-bottom:1px dotted #ccc;"><a href="/?feed"><img alt="feed" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUBAMAAAB/pwA+AAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAASUExURfeaQtZNEtBtIPJbB/azdejn5lp1xwsAAAADdFJOU/veNH3oRMsAAAB5SURBVAjXNctLDsMgDATQqSD7VrkB7QHiOtmjEvZIydz/KsV8ZoGeGRvhHAn4TP7gJyN8JmMnFpLose8bc6grHRE7i+rDdosudCrQg0wbL+NWzzQXo9Y6HewUXntjffMtdO3MaqZGZ4VRThWvz1Ww6ojgPflFeI2EPyrLJui5yYgHAAAAAElFTkSuQmCC"></a> 
<?php
	$feed=json_decode(file_get_contents('feed.json'), TRUE);
	echo date('Y-m-d', $feed[0]['timestamp']).' ~ <b>'.$feed[0]['title'].'</b> <br><i>'.$feed[0]['content'].'</i>';
?> 
 </span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-below">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        Copyleft <s>&copy;</s> Kitsu.eu
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
    <div class="scroll-top page-scroll visible-xs visible-sm">
        <a class="btn btn-primary" href="#page-top">
            <i class="fa fa-chevron-up"></i>
        </a>
    </div>

    <!-- Portfolio Modals -->
    <div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>Shaarli</h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/cabin.png" class="img-responsive img-centered" alt="">
                            <p>Partagez vos découvertes sur le web et rejoignez une formidable communauté !</p>
                            <ul class="list-inline item-details">
                                <li>Site:
                                    <strong><a href="https://github.com/shaarli/Shaarli">Github</a>
                                    </strong>
                                </li>
                                <li>Type:
                                    <strong>PHP 5.1, serial</strong>
                                </li>
                                <li>Service:
                                    <strong>abonné·e·s</strong>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>Respawn</h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/cake.png" class="img-responsive img-centered" alt="">
                            <p>Gardez une copie des pages web qui vous intéressent !</p>
                            <ul class="list-inline item-details">
                                <li>Site:
                                    <strong><a href="https://github.com/broncowdd/respawn">Github</a>
                                    </strong>
                                </li>
                                <li>Type:
                                    <strong>PHP 5.x, serial
                                    </strong>
                                </li>
                                <li>Service:
                                    <strong>abonné·e·s
                                    </strong>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal3" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>KrISS feed</h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/circus.png" class="img-responsive img-centered" alt="">
                            <p>Lecteur de flux RSS/ATOM pour ne rien rater du web !</p>
                            <ul class="list-inline item-details">
                                <li>Site:
                                    <strong><a href="http://tontof.net/kriss/feed/">KrISS Feed</a>
                                    </strong>
                                </li>
                                <li>Type:
                                    <strong>PHP 5.2 
                                    </strong>
                                </li>
                                <li>Service:
                                    <strong>abonné·e·s
                                    </strong>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal4" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>BlogoText</h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/game.png" class="img-responsive img-centered" alt="">
                            <p>Votre propre blog, rapide et complet !</p>
                            <ul class="list-inline item-details">
                                <li>Site:
                                    <strong><a href="http://lehollandaisvolant.net/blogotext/fr/">BlogoText</a>
                                    </strong>
                                </li>
                                <li>Type:
                                    <strong>PHP 5.3, SQLite
                                    </strong>
                                </li>
                                <li>Service:
                                    <strong>abonné·e·s
                                    </strong>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal5" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>PrivateBin</h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/safe.png" class="img-responsive img-centered" alt="">
                            <p>Stockage de texte et fichiers chiffré côté client</p>
                            <ul class="list-inline item-details">
                                <li>Site:
                                    <strong><a href="https://github.com/PrivateBin/PrivateBin/">PrivateBin</a>
                                    </strong>
                                </li>
                                <li>Type:
                                    <strong>PHP 5.3, serial
                                    </strong>
                                </li>
                                <li>Service:
                                    <strong>public: <a href="./privatebin/">PrivateBin</a> (max 1 mois)
                                    </strong>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal6" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>MyCryptoChat</h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/submarine.png" class="img-responsive img-centered" alt="">
                            <p>Chat chiffré dans le navigateur, seul·e ou à plusieurs !</p>
                            <ul class="list-inline item-details">
                                <li>Site:
                                    <strong><a href="https://github.com/HowTommy/mycryptochat">Github</a>
                                    </strong>
                                </li>
                                <li>Type:
                                    <strong>PHP 5.4, SQLite
                                    </strong>
                                </li>
                                <li>Service:
                                    <strong>public: <a href="./cryptochat/">MyCryptoChat</a> (max 1 mois)
                                    </strong>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/classie.js"></script>
    <script src="js/cbpAnimatedHeader.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>
    <!--<script src="js/contact_me.js"></script>-->

    <!-- Custom Theme JavaScript -->
    <script src="js/freelancer.js"></script>

</body>

</html>
