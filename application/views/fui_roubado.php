<!DOCTYPE html>
<html>
<head>
    <title>Fui Roubado</title>

    <!-- load main CSSs -->
    <?php $this->load->view('includes/main_css'); ?>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
    integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
    crossorigin=""/>

    <!-- map search box css -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/map-search-box.css'); ?>">

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

    <!-- message div -->
    <?php $this->load->view('includes/msg'); ?>

    <div id="map" style="height : 350px;"></div>
    
    <form autocomplet=off action="occurrence/insert" method="POST">

		Endereço: 
        <input id=input_address type=text name=address value="<?php if(isset($_SESSION['occurrence']['address'])) echo $_SESSION['occurrence']['address']; ?>" readonly required>
        <br>

		Latitude: 
        <input id=input_latitude type=text name=latitude value="<?php if(isset($_SESSION['occurrence']['latitude'])) echo $_SESSION['occurrence']['latitude']; ?>" readonly required>
        <br>

		Longitude: 
        <input id=input_longitude type=text name=longitude value="<?php if(isset($_SESSION['occurrence']['longitude'])) echo $_SESSION['occurrence']['longitude']; ?>" required readonly>
        <br>

		Dia: 
        <input id=input_day type=date name=day value="<?php if(isset($_SESSION['occurrence']['day'])) echo $_SESSION['occurrence']['day']; ?>" required>
        <br>

		Hora: 
        <input id=input_time type=time name=time value="<?php if(isset($_SESSION['occurrence']['time'])) echo $_SESSION['occurrence']['time']; ?>" required>
        <br>

        <!-- list markers -->
        Icone do marcador:
        <?php foreach ($markers as $marker) : ?>
            <label for=marker_id<?php echo $marker['id']; ?> >
                <img src="<?php echo base_url().'assets/images/img_markers/'.$marker['img_name']; ?>" >
                <input id=marker_id<?php echo $marker['id']; ?> 
                    type=radio name=marker_id 
                    value=<?php echo $marker['id']; ?> 
                    required <?php if (isset($_SESSION['occurrence']['marker_id'])) if ($marker['id'] == $_SESSION['occurrence']['marker_id']) echo 'checked' ?>  >
            </label>
        <?php endforeach; ?>
        <br>

		Boletim: 
        <input type=radio name=reported value=1 <?php if(isset($_SESSION['occurrence']['reported'])) if($_SESSION['occurrence']['reported'] == 1) echo 'checked'; ?> required> Sim
        <input type=radio name=reported value=0 <?php if(isset($_SESSION['occurrence']['reported'])) if($_SESSION['occurrence']['reported'] == 0) echo 'checked'; if(!isset($_SESSION['occurrence']['reported'])) echo 'checked' ?>> Não
        <br>

		Agreção: 
        <input type=radio name=aggression value=1 <?php if(isset($_SESSION['occurrence']['aggression'])) if($_SESSION['occurrence']['aggression'] == 1) echo 'checked'; ?> required> Sim
        <input type=radio name=aggression value=0 <?php if(isset($_SESSION['occurrence']['aggression'])) if($_SESSION['occurrence']['aggression'] == 0) echo 'checked'; if(!isset($_SESSION['occurrence']['aggression'])) echo 'checked' ?>> Não
        <br>

        <input type=hidden name=complement value='' />
		Complemento: 
        <input type=text name=complement value="<?php if(isset($_SESSION['occurrence']['complement'])) echo $_SESSION['occurrence']['complement']; ?>">
        <br>

        <!-- email for controller -->
        Email: 
        <input id=email type=email name=email value="<?php if(isset($_SESSION['occurrence']['email'])) echo $_SESSION['occurrence']['email']; ?>" required>
        <br>
        
        <!-- reCaptcha -->
        <div class="g-recaptcha" data-sitekey="<?php echo $this->config->item('recaptcha')['client_key']; ?>"></div>

        <input type=submit value=Enviar>
	</form>


    <!-- load main js -->
    <?php $this->load->view('includes/main_js'); ?>

    <!-- define base_url in a global var -->
    <script>var base_url = '<?php echo base_url();  ?>';</script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/map-search-box.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/map-fui-roubado.js'); ?>"> require("<?php echo base_url('assets/js/map_fui_roubado.js'); ?>"); </script>

</body>
</html>