import mapboxgl from 'mapbox-gl';
import $ from 'jquery';

$(document).ready(map);

function map() {
    let styleUrl = 'mapbox://styles/nikki2048/ckyg6998m2ec515o86wkmkjnn';

    const aerodrome_data = JSON.parse(document.getElementById('mapboxdata-aerodrome')?.innerHTML ?? '');

    const standstatus_data = JSON.parse(document.getElementById('mapboxdata-standstatus')?.innerHTML ?? '');

    mapboxgl.accessToken = 'pk.eyJ1Ijoibmlra2kyMDQ4IiwiYSI6ImNrOXpibmR5bTA1MTIzZnJ0aXh1cG4yNjYifQ.b-1gEcULFsxkvP2s9BCXQg';
    const map = new mapboxgl.Map({
        container: 'map', // container ID
        style: styleUrl, // style URL
        center: [aerodrome_data['longitude'], aerodrome_data['latitude']], // starting position [lng, lat]
        zoom: 12, // starting zoom
    });
    console.log([aerodrome_data['longitude'], aerodrome_data['latitude']]);
    map.on('zoom', () => {
        $('.marker-occupied').css('width', 12 * ((map.getZoom() - 3) / 10));
        $('.marker-occupied').css('height', 12 * ((map.getZoom() - 3) / 10));
        $('.marker-free').css('width', 12 * ((map.getZoom() - 3) / 10));
        $('.marker-free').css('height', 12 * ((map.getZoom() - 3) / 10));
    });

    $.each(standstatus_data, (key, stand) => {
        const el = document.createElement('div');
        if (stand['occupier'] == null) el.className = 'marker-free';
        else el.className = 'marker-occupied';

        let callsign = '';
        if (stand['occupier'] != null) {
            callsign = `<p class="pb-0 mb-0">${stand['occupier']}</p>`;
        }
        console.log([stand['longitude'], stand['latitude']]);
        let marker = new mapboxgl.Marker(el)
            .setLngLat([stand['longitude'], stand['latitude']])
            .setPopup(
                new mapboxgl.Popup({
                    offset: 8,
                }) // add popups
                    .setHTML(`<p class="pb-0 mb-0" style="font-size: 15px"><strong>${stand['id']}</strong></p>` + callsign)
            )
            .addTo(map);
    });

    /*

*/
}
