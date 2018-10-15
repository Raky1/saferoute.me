<!DOCTYPE html>
<html>
<head>
    <title>Mapa</title>

    <!-- load main CSSs -->
    <?php $this->load->view('includes/main_css'); ?>

    <!-- leaflet css -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
    integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
    crossorigin=""/>

    <!-- map css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/map-style.css'); ?>">
    <!-- map css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/map-search-box.css'); ?>">

    <!--estilo css-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/estilo.css'); ?>">

    <!--Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Titillium+Web|Ubuntu" rel="stylesheet">

    <!-- leaflet js -->
    <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
    integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
    crossorigin=""></script>

    <!-- recaptcha js -->
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>

    <!-- menu div -->
    <?php $this->load->view('includes/menu'); ?>

    
    <div id="map" style="height :500px; margin-top: 50px;">
        <div id=black-screen-report class=map-black-screen >
            <div id=report-display class=popup-report>
                <span id=close-button-report class=popup-report-close-button>&times;</span>
                <br>
                <form id=form-report-occurrence autocomplet=off method=POST>
                    <input id=id_occurrence_report type=hidden value=0>
                    <label for=email >Email:</label><br>
                    <input id=report-email name=email type=email>
                    <br>
                    <label for=note >Note:</label><br>
                    <textarea id=report-note name=note cols=40 rows=5 maxlength=255 ></textarea>
                    <!-- reCaptcha -->
                    <div class="g-recaptcha" data-sitekey="<?php echo $this->config->item('recaptcha')['client_key']; ?>"></div>
                    <button id=report-button-submit type=submit>Enviar</button>
                </form>
            <div>
        <div>
    </div>

    <!-- load main js -->
    <?php $this->load->view('includes/main_js'); ?>

    <!-- message div -->
    <?php $this->load->view('includes/msg'); ?>


    <!-- define base_url in a global var -->
    <script>var base_url = '<?php echo base_url();  ?>';</script>
    <!-- map js -->
    <script type="text/javascript" src="<?php echo base_url('assets/js/map-search-box.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/map.js'); ?>"> require("<?php echo base_url('assets/js/map.js'); ?>"); </script>

</body>
</html>