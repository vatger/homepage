import mapboxgl from "mapbox-gl";
import { find, forEach, isEmpty, uniq, filter } from "lodash";
import { findLivewireComponent } from "../livewire.js";
import { dayjs } from "../dayjs";
import { getDarkmode } from "../template.js";
import {
  map,
  Map,
  tileLayer,
  icon,
  marker,
  circleMarker,
  layerGroup,
  LayerGroup,
  Marker,
} from "leaflet";

document.addEventListener("DOMContentLoaded", () => {
  load_map().then();
  metar().then();
  atis().then();
  indicator().then();
  event().then();
  download_map().then(() => update_map());
});

window.setInterval(() => {
  download_map().then(() => update_map());
}, 10000);

let mymap: Map | null = null;

async function load_map(): Promise<void> {
  const lwc = findLivewireComponent("aerodrome-page");
  const aerodrome_data: Object = await lwc.$wire.load_aerodrome();
  const mapbox_username = "nikki2048";
  const mapbox_style_id_light = "ckyg6998m2ec515o86wkmkjnn";
  const mapbox_style_id_dark = "ckyg12wrq5h6b15pcb4b4dev1";
  const mapbox_style_id = getDarkmode()
    ? mapbox_style_id_dark
    : mapbox_style_id_light;
  const mapbox_access_token =
    "pk.eyJ1Ijoibmlra2kyMDQ4IiwiYSI6ImNrOXpibmR5bTA1MTIzZnJ0aXh1cG4yNjYifQ.b-1gEcULFsxkvP2s9BCXQg";
  const mapbox_link =
    "https://api.mapbox.com/styles/v1/" +
    mapbox_username +
    "/" +
    mapbox_style_id +
    "/tiles/256/{z}/{x}/{y}?access_token=" +
    mapbox_access_token;

  mymap = map("map").setView(
    [aerodrome_data["latitude"], aerodrome_data["longitude"]],
    13,
  );
  const mytilelayer = tileLayer(mapbox_link, {
    attribution: `© <a href="https://www.mapbox.com/about/maps">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> <strong><a href="https://apps.mapbox.com/feedback/" target="_blank">Improve this map</a></strong>`,
    maxZoom: 17,
  }).addTo(mymap);

  mymap.on("zoomend", () => update_map());
}

let standstatus_data: Array<0> = [];
let aircraftstatus_data: Array<0> = [];

async function download_map() {
  let lwc = findLivewireComponent("aerodrome-page");
  standstatus_data = await lwc.$wire.load_stands();
  aircraftstatus_data = await lwc.$wire.load_aircraft();
}

let mymarker: LayerGroup | null = null;

async function update_map() {
  if (mymarker) {
    mymarker.remove();
  }
  mymarker = layerGroup();
  forEach(standstatus_data, (stand) => {
    if (mymap == null || mymarker == null) return;
    const stand_lat = stand["latitude"];
    const stand_lon = stand["longitude"];
    let stand_text =
      "<div style='text-align: center;'>Stand <strong>" +
      stand["id"] +
      "</strong></div>";
    let stand_color = "rgba(0, 128, 0, 0.5)";
    let stand_border = "rgba(0, 128, 0, 0.8)";
    if (!isEmpty(stand["occupier"])) {
      stand_text += `${stand["occupier"]}`;
      stand_color = "rgba(204, 7, 7, 0.5)";
      stand_border = "rgba(204, 7, 7, 0.8)";
    }

    const circle_ = circleMarker([stand_lat, stand_lon], {
      color: stand_border,
      fillColor: stand_color,
      fillOpacity: 0.5,
      radius: 4.5,
    }).addTo(mymarker);
    circle_.bindPopup(stand_text);
  });

  if (mymap == null) return;
  mymarker.addTo(mymap);

  forEach(aircraftstatus_data, (aircraft) => {
    if (mymap == null || mymarker == null) return;
    const aircraft_lat = aircraft["latitude"];
    const aircraft_lon = aircraft["longitude"];
    const aircraft_callsign = aircraft["callsign"];
    const aircraft_heading = aircraft["heading"];
    const icon_ = icon({
      iconUrl: "/images/plane.png",
      iconSize: [16, 16], // size of the icon
      iconAnchor: [8, 8], // point of the icon which will correspond to marker's location
      popupAnchor: [0, 0],
    });
    const marker_: Marker = marker([aircraft_lat, aircraft_lon], {
      icon: icon_,
    }).addTo(mymarker);
    marker_.bindPopup(aircraft_callsign);
    const el = marker_.getElement();
    if (el == null) return;
    el.style.transformOrigin = "50% 50%";
    el.style.transform += ` rotate(${aircraft_heading}deg)`;
  });
}

async function metar() {
  let lwc = findLivewireComponent("aerodrome-page");
  const metar_data: string = await lwc.$wire.load_metar();
  let metar_el = document.getElementById("metar-container");
  if (metar_el) metar_el.innerHTML = metar_data;
}

async function atis() {
  let lwc = findLivewireComponent("aerodrome-page");
  const atis_data: string = await lwc.$wire.load_atis();
  let atis_el = document.getElementById("atis-container");
  let atis_wid = document.getElementById("atis-widget");
  if (!atis_el || !atis_wid || !atis_data) {
    if (atis_wid) atis_wid.style.display = "none";
    return;
  }
  atis_wid.style.display = "block";
  let string = "";
  forEach(atis_data, (atis_obj) => {
    string +=
      '<h6 class="text-center">' +
      atis_obj["callsign"] +
      " " +
      atis_obj["frequency"] +
      " MHz</h6> ";
    atis_obj["text_atis"].forEach((line) => {
      string += line + " ";
    });
    string += "<hr>";
  });
  string = string.slice(0, -4);

  atis_el.innerHTML = string;
}

async function indicator() {
  let lwc = findLivewireComponent("aerodrome-page");
  const data: Array<0> = await lwc.$wire.load_indicators();

  function checkindicator(ending: string, element_id: string) {
    if (
      find(data, (value) => {
        let ident: string = value["station"]["ident"];
        return ident.endsWith(ending);
      })
    ) {
      document
        .getElementById(element_id)
        ?.setAttribute("class", "badge rounded bg-soft-success p-2");
    }
  }

  checkindicator("_DEL", "del_indicator");
  checkindicator("_GND", "gnd_indicator");
  checkindicator("_TWR", "twr_indicator");
  checkindicator("_APP", "app_indicator");
  checkindicator("_CTR", "ctr_indicator");

  let table = document.getElementById("loading-text-atc");
  let tableContainer = document.getElementById("table-atc-container");
  if (!table || !tableContainer) return;
  const monitoringText = tableContainer.dataset.monitoringText ?? "monitoring";
  const emptyText =
    tableContainer.dataset.emptyText ?? "No ATC is currently online.";

  let html = "";

  forEach(data, (station) => {
    const primary_name = station["station"]["name"];
    const primary_callsign = station["callsign"];
    const primary_frequency = station["frequency"];
    const secondary_frequencies = filter(
      uniq(station["transceivers"].map((obj) => obj.frequencyString)),
      (str) => str != primary_frequency,
    );

    html +=
      "<tr><td><small><b>" +
      primary_name +
      "</b></small></td><td>" +
      primary_callsign +
      " <small><b>" +
      primary_frequency +
      "</b> MHz</small>";

    if (!isEmpty(secondary_frequencies)) {
      html += `<br><small>${monitoringText} `;
      forEach(secondary_frequencies, (frequency) => {
        html += frequency + ", ";
      });
      html = html.slice(0, -2);
      html += "</small>";
    }
    html += "</td>" + "</tr>";
  });

  if (isEmpty(data)) {
    tableContainer.innerHTML = `<p style="text-align: center">${emptyText}</p>`;
    return;
  }

  table.innerHTML = html;
}

type Event = {
  id: number;
  name: string;
  description: string;
  short_description: string;
  start_time: string;
  end_time: string;
  banner: string;
  link: string;
  type: string;
  airports: Array<{ icao: string }>;
  organisers: Array<{
    region?: string;
    division?: string;
    subdivision?: string;
    organised_by_vatsim: boolean;
  }>;
  vso_name?: string;
};

async function event() {
  let lwc = findLivewireComponent("aerodrome-page");
  const data: Array<Event> = await lwc.$wire.load_events();

  const event_container = document.getElementById("event-container");
  while (event_container?.lastChild) {
    event_container.removeChild(event_container.lastChild);
  }

  forEach(data, (e: Event, i: number) => {
    event_container?.insertAdjacentHTML(
      "beforeend",
      `
                <div class="col-12 mt-4 pb-2 ${
                  i > 5 ? "hide" : ""
                }" id="event-${i}">
                    <a href="${window.location.origin}/events/view/${e.id}">
                        <div class="card blog rounded border-0 shadow overflow-hidden">
                            <div class="position-relative">
                                <div class="overlay rounded-top"></div>
                                <div class="card-img-top loader-show overflow-hidden" id="event-banner-1" style="min-height: 200px; min-width: 356px; background: url('${
                                  e.banner
                                }') center; background-size: cover;"></div>
                            </div>
                            <div class="card-body content">
                                <span class="badge rounded-pill bg-soft-primary mb-2 ${
                                  e.type == "CPT" ? "" : "hide"
                                }">
                                    Controller Practical Test
                                </span>
                                <h5>
                                    <span class="card-title title text-dark" id="event-title-1">${
                                      e.name
                                    }</span>
                                </h5>
                                <div class="post-meta d-flex justify-content-between mt-3">
                                    <ul class="list-unstyled mb-0">
                                        <li class="list-inline-item me-2 mb-0">
                                            <span href="javascript:void(0)" class="text-muted" id="event-date-1">
                                                ${
                                                  dayjs(e.start_time).format(
                                                    "DD.MM.YYYY HH:mm",
                                                  ) + "z"
                                                }
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            `,
    );
  });
}
