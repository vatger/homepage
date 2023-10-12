import mapboxgl from 'mapbox-gl';
import $ from 'jquery';
import { isEmpty } from 'lodash';
import { zroute } from '@/ts/myziggy';
import { findLivewireComponent } from '@/ts/livewire';

$(map);
$(metar);
$(atis);
$(indicator);

async function map() {
    let lwc = findLivewireComponent('aerodrome-page');
    const aerodrome_data: Object = await lwc.$wire.load_aerodrome();
    const standstatus_data: Array<0> = await lwc.$wire.load_stands();

    let styleUrl = 'mapbox://styles/nikki2048/ckyg6998m2ec515o86wkmkjnn';
    mapboxgl.accessToken = 'pk.eyJ1Ijoibmlra2kyMDQ4IiwiYSI6ImNrOXpibmR5bTA1MTIzZnJ0aXh1cG4yNjYifQ.b-1gEcULFsxkvP2s9BCXQg';

    let map_el = document.getElementById('map');
    if (map_el) map_el.innerHTML = '';

    const map = new mapboxgl.Map({
        container: 'map', // container ID
        style: styleUrl, // style URL
        center: [aerodrome_data['longitude'], aerodrome_data['latitude']], // starting position [lng, lat]
        zoom: 12, // starting zoom
    });

    map.on('zoom', () => {
        let marker_occupied = $('.marker-occupied');
        let marker_free = $('.marker-free');
        marker_occupied.css('width', 12 * ((map.getZoom() - 3) / 10));
        marker_occupied.css('height', 12 * ((map.getZoom() - 3) / 10));
        marker_free.css('width', 12 * ((map.getZoom() - 3) / 10));
        marker_free.css('height', 12 * ((map.getZoom() - 3) / 10));
    });

    $.each(standstatus_data, (key, stand) => {
        const el = document.createElement('div');
        if (stand['occupier'] == null) el.className = 'marker-free';
        else el.className = 'marker-occupied';

        let callsign = '';
        if (!isEmpty(stand['occupier'])) {
            callsign = `<p class="pb-0 mb-0">${stand['occupier']}</p>`;
        }
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
}

async function metar() {
    let lwc = findLivewireComponent('aerodrome-page');
    const metar_data: string = await lwc.$wire.load_metar();
    let metar_el = document.getElementById('metar-container');
    if (metar_el) metar_el.innerHTML = metar_data;
}

async function atis() {
    let lwc = findLivewireComponent('aerodrome-page');
    const atis_data: string = await lwc.$wire.load_atis();
    if (!atis_data) return;
    let atis_el = document.getElementById('atis-container');
    let atis_wid = document.getElementById('atis-widget');
    if (!atis_el || !atis_wid) return;
    atis_wid.setAttribute('style', 'visibility=visible');
    let string = '';
    atis_data['text_atis'].forEach((line) => {
        string += line + ' ';
    });
    atis_el.innerHTML = string;
}

async function indicator() {
    let lwc = findLivewireComponent('aerodrome-page');
    const data: Array<0> = await lwc.$wire.load_indicators();

    function checkindicator(ending: string, element_id: string) {
        if (
            data.find((value) => {
                let ident: string = value['station']['ident'];
                return ident.endsWith(ending);
            })
        ) {
            document.getElementById(element_id)?.setAttribute('class', 'badge rounded bg-soft-success p-2');
        }
    }

    checkindicator('_DEL', 'del_indicator');
    checkindicator('_GND', 'gnd_indicator');
    checkindicator('_TWR', 'twr_indicator');
    checkindicator('_APP', 'app_indicator');
    checkindicator('_CTR', 'ctr_indicator');
    let table = document.getElementById('loading-text-atc');
    if (!table) return;

    let html = '';
    data.forEach((station) => {
        html +=
            '<tr>' +
            '<td><small>' +
            station['station']['name'] +
            '</small></td>' +
            '<td>' +
            station['callsign'] +
            '</td>' +
            '<td><small>' +
            station['frequency'] +
            '</small></td>' +
            '</tr>';
    });

    if (isEmpty(data)) {
        html += '<tr><td>No ATC online.</td></tr>';
    }
    table.innerHTML = html;
}
