function openReport(event) {

    $('#black-screen-report').css('display', 'block');
    $('#id_occurrence_report').val($(event).val());

    grecaptcha.reset();

}

//generate popup
function generatePopupHtml(o) {
    let div_clear = $('<div style="clear: both;"></div>');

    let popup_info1 = $('<div class=popup-info1></div>')
        .append($('<div class=alignleft></div>')
            .html(o.occurrence_day.split('-').reverse().join('/'))
        ).append($('<div class=alignright></div>')
            .html(o.occurrence_time.substring(0, 5))
        ).append($('<div class=popup-reports></div>')
            .html(
                (o.reported==1 ? '<img src="' + base_url + 'assets/images/popup_imgs/report.png" title="report">' : '') +
                (o.aggression==1 ? '<img src="' + base_url + 'assets/images/popup_imgs/punch.png" title="aggression" >' : '')
            )
        );


    let popup_info2 = $('<div class=popup-info2></div>')
        .html((o.complement == null ? 'no description' : o.complement));

    let button_report = $('<button class=popup-button-report title="report content" value=' + o.id + ' onclick="openReport(this)">!</button>');

    let popup_conteiner = $('<div class=popup-conteiner></div>');

    popup_conteiner.append(popup_info1);
    popup_conteiner.append(div_clear.clone());
    popup_conteiner.append(popup_info2);
    popup_conteiner.append(button_report);
    popup_conteiner.append(div_clear);

    return popup_conteiner[0];
}
//=====================================================================================================================================

$(document).ready(function() {

    //map config
    var map = L.map('map').setView([-22.0154, -47.8911], 13); //São Carlos
    map.zoomControl.setPosition('topright');

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    //load occurrences
    $.getJSON(base_url + 'map/json', function(response) {

        if (response.result == true) {
            var markersIcon = {};

            //make markers
            response.markers.forEach(marker => {                
                if(marker.shadow == true) {
                    markersIcon[marker.id] = L.icon({
                        iconUrl: base_url+'assets/images/img_markers/' + marker.img_name,
                        iconSize: [Number(marker.width), Number(marker.height)],
                        iconAnchor: [Number(marker.anchorx), Number(marker.anchory)],
                        shadowUrl: base_url+'assets/images/img_markers/' + marker.img_shadow_name,
                        shadowSize: [Number(marker.shadow_width), Number(marker.shadow_height)],
                        shadowAnchor: [Number(marker.shadow_anchorx), Number(marker.shadow_anchory)],
                        popupAnchor: [Number(marker.popupx), Number(marker.popupy)]
                    });
                } else {
                    markersIcon[marker.id] = L.icon({
                        iconUrl: base_url+'assets/images/img_markers/' + marker.img_name,
                        iconSize: [Number(marker.width), Number(marker.height)],
                        iconAnchor: [Number(marker.anchorx), Number(marker.anchory)],
                        popupAnchor: [Number(marker.popupx), Number(marker.popupy)]
                    });                    
                }
            });
            
            //display occurrences
            response.occurrences.forEach(occurrence => {
                L.marker(
                    [occurrence.latitude, occurrence.longitude],
                    {icon: markersIcon[occurrence.marker_id]}
                    ).addTo(map).bindPopup(
                        generatePopupHtml(occurrence)
                    );
            });
        } else {
            for (var k in response.error) {
                console.log(k, response[k]);
            }
        }

    });

    //report window 
    L.DomEvent.disableClickPropagation($('#black-screen-report')[0]);
    $('#close-button-report').click(function(event) {
        $('#black-screen-report').css('display', 'none');
    });

    $('#form-report-occurrence').submit(function(event) {
        if (event.preventDefault) event.preventDefault();

        $('#report-button-submit').prop('disabled', true);

        $.post(base_url + 'occurrence/report', {
            'occurrence_id': $('#id_occurrence_report').val(),
            email: $('#report-email').val(),
            note: $('#report-note').val(),
            'g-recaptcha-response': grecaptcha.getResponse()
        }, function(response) {
            console.log(
                $('#id_occurrence_report').val(),
                $('#report-email').val(),
                $('#report-note').val(),
                grecaptcha.getResponse()
            );
            console.log(response);
            //console.log(grecaptcha.getResponse());
            grecaptcha.reset();
            $('#report-button-submit').prop('disabled', false);
        });
    });



    //add searchboxcontrol
    L.control.searchbox().addTo(map);

});
