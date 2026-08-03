import { find, forEach, isEmpty, uniq, filter } from "lodash";
import { findLivewireComponent } from "../livewire-public.js";
import { dayjs } from "../dayjs";
import { getDarkmode } from "../preferences.js";
import {
  map,
  Map,
  canvas,
  tileLayer,
  divIcon,
  marker,
  polyline,
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
  download_map().then(() => {
    update_map();
    updateAircraftList();
  });
});

window.setInterval(() => updatePredictedPaths(), 1000);

let mymap: Map | null = null;

async function getAerodromeComponent() {
  for (let attempt = 0; attempt < 100; attempt++) {
    const component = findLivewireComponent("aerodrome-page");
    if (component) return component;
    await new Promise((resolve) => window.setTimeout(resolve, 50));
  }

  throw new Error("The aerodrome Livewire component did not initialize.");
}

async function load_map(): Promise<void> {
  const lwc = await getAerodromeComponent();
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

  mymap = map("map", {
    preferCanvas: true,
    renderer: canvas(),
  }).setView([aerodrome_data["latitude"], aerodrome_data["longitude"]], 13);
  const mytilelayer = tileLayer(mapbox_link, {
    attribution: `© <a href="https://www.mapbox.com/about/maps">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> <strong><a href="https://apps.mapbox.com/feedback/" target="_blank">Improve this map</a></strong>`,
    maxZoom: 21,
    updateWhenIdle: true,
    updateWhenZooming: false,
    keepBuffer: 2,
  }).addTo(mymap);

  mymap.on("zoomend", () => update_map());
}

type AerodromeAircraft = {
  callsign: string;
  type?: string;
  departure?: string;
  arrival?: string;
  altitude: number;
  groundspeed: number;
  latitude: number;
  longitude: number;
  heading: number;
  groundstate: string;
  gate?: string | null;
  track: AerodromeTrackPoint[];
};

type AerodromeTrackPoint = {
  latitude: number;
  longitude: number;
  heading: number;
  recorded_at: string;
  predicted: boolean;
};

let standstatus_data: Array<0> = [];
let aircraftstatus_data: AerodromeAircraft[] = [];
let standsUpdatedAt: string | null = null;
let aircraftUpdatedAt: string | null = null;
let mapPollInFlight = false;
const mapPollDelayMs = 4_000;

const aircraftAssetPath = "/images/brand/aircraft";
const predictedPathHorizonMs = 20_000;
const aircraftSimulationDelayMs = 25_000;

function createGateIcon(
  occupied: boolean,
  standId: string,
  showLabel: boolean,
) {
  const asset = occupied ? "plane-gate-occupied.svg" : "plane-gate-empty.svg";
  const markerSize = 16;

  return divIcon({
    className: "aerodrome-gate-icon",
    html: `<span class="aerodrome-gate-marker aerodrome-gate-marker--${occupied ? "occupied" : "empty"}">
      ${showLabel ? `<span class="aerodrome-gate-label">${escapeHtml(standId)}</span>` : ""}
      <img src="${aircraftAssetPath}/${asset}" alt="" aria-hidden="true" style="width: ${markerSize}px !important; height: ${markerSize}px !important;" />
    </span>`,
    iconSize: [markerSize, showLabel ? 30 : markerSize],
    iconAnchor: [markerSize / 2, showLabel ? 15 : markerSize / 2],
  });
}

function createAircraftIcon(heading: number) {
  const aircraftTransform = `translate(-50%, -50%) rotate(${heading - 90}deg)`;
  // Gate symbols are rendered at 16px high. Use that same visible size for
  // moving aircraft so both marker types remain visually consistent.
  const markerSize = 16;

  return divIcon({
    className: "aerodrome-aircraft-icon",
    html: `<span class="aerodrome-aircraft-marker" style="width: ${markerSize}px !important; height: ${markerSize}px !important;">
      <img class="aerodrome-aircraft-plane-image" src="${aircraftAssetPath}/plane-taxi.svg" alt="" aria-hidden="true" style="width: ${markerSize}px !important; height: ${markerSize}px !important; transform: ${aircraftTransform}" />
    </span>`,
    iconSize: [markerSize, markerSize],
    iconAnchor: [markerSize / 2, markerSize / 2],
  });
}

type TimestampedResponse<T> = {
  updated_at: string | null;
  unchanged: boolean;
  data?: T;
};

async function download_map(): Promise<boolean> {
  if (mapPollInFlight) return false;

  mapPollInFlight = true;
  let changed = false;

  try {
    const lwc = await getAerodromeComponent();
    const standsResponse = (await lwc.$wire.load_stands(
      standsUpdatedAt,
    )) as TimestampedResponse<Array<0>>;

    standsUpdatedAt = standsResponse.updated_at;
    if (!standsResponse.unchanged && standsResponse.data) {
      standstatus_data = standsResponse.data;
      changed = true;
    }

    const aircraftResponse = (await lwc.$wire.load_aircraft(
      aircraftUpdatedAt,
    )) as TimestampedResponse<AerodromeAircraft[]>;

    aircraftUpdatedAt = aircraftResponse.updated_at;
    if (!aircraftResponse.unchanged && aircraftResponse.data) {
      aircraftstatus_data = aircraftResponse.data;
      changed = true;
    }
  } finally {
    mapPollInFlight = false;
    window.setTimeout(() => {
      download_map().then((mapChanged) => {
        if (mapChanged) {
          update_map();
          updateAircraftList();
        }
      });
    }, mapPollDelayMs);
  }

  return changed;
}

function escapeHtml(value: string): string {
  return value.replace(/[&<>"']/g, (character) => {
    const entities: Record<string, string> = {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#039;",
    };

    return entities[character];
  });
}

function formatGroundstate(groundstate: string): string {
  return groundstate
    .split("_")
    .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
    .join(" ");
}

function interpolateHeading(
  from: number,
  to: number,
  progress: number,
): number {
  const delta = ((to - from + 540) % 360) - 180;

  return (from + delta * progress + 360) % 360;
}

function smoothTrack(points: AerodromeTrackPoint[]): [number, number][] {
  if (points.length < 3) {
    return points.map((point) => [point.latitude, point.longitude]);
  }

  const smoothed: [number, number][] = [];
  const subdivisions = 8;

  for (let index = 0; index < points.length - 1; index++) {
    const p0 = points[Math.max(0, index - 1)];
    const p1 = points[index];
    const p2 = points[index + 1];
    const p3 = points[Math.min(points.length - 1, index + 2)];

    for (let step = 0; step < subdivisions; step++) {
      const t = step / subdivisions;
      const t2 = t * t;
      const t3 = t2 * t;
      const interpolate = (a: number, b: number, c: number, d: number) =>
        0.5 *
        (2 * b +
          (-a + c) * t +
          (2 * a - 5 * b + 4 * c - d) * t2 +
          (-a + 3 * b - 3 * c + d) * t3);

      smoothed.push([
        interpolate(p0.latitude, p1.latitude, p2.latitude, p3.latitude),
        interpolate(p0.longitude, p1.longitude, p2.longitude, p3.longitude),
      ]);
    }
  }

  const last = points[points.length - 1];
  smoothed.push([last.latitude, last.longitude]);

  return smoothed;
}

function interpolateTrackPoint(
  track: AerodromeTrackPoint[],
  timestamp: number,
): AerodromeTrackPoint | null {
  const timedTrack = track
    .map((point) => ({ point, time: Date.parse(point.recorded_at) }))
    .filter(({ time }) => Number.isFinite(time))
    .sort((first, second) => first.time - second.time);

  if (timedTrack.length === 0) return null;

  let previous = timedTrack[0];
  let next = timedTrack[timedTrack.length - 1];

  for (let index = 1; index < timedTrack.length; index++) {
    if (timedTrack[index].time >= timestamp) {
      next = timedTrack[index];
      previous = timedTrack[index - 1];
      break;
    }
  }

  const duration = Math.max(1, next.time - previous.time);
  const progress = Math.min(
    1,
    Math.max(0, (timestamp - previous.time) / duration),
  );

  return {
    latitude:
      previous.point.latitude +
      (next.point.latitude - previous.point.latitude) * progress,
    longitude:
      previous.point.longitude +
      (next.point.longitude - previous.point.longitude) * progress,
    heading: interpolateHeading(
      previous.point.heading,
      next.point.heading,
      progress,
    ),
    recorded_at: new Date(timestamp).toISOString(),
    predicted: timestamp > previous.time,
  };
}

function updateAircraftList() {
  const container = document.getElementById("aircraft-container");
  const count = document.getElementById("aircraft-count");
  if (!container || !count) return;

  count.textContent = String(aircraftstatus_data.length);

  if (isEmpty(aircraftstatus_data)) {
    container.innerHTML = `<p class="p-5 text-sm text-secondary-500 dark:text-secondary-300">${container.dataset.emptyText ?? "No aircraft are currently moving around this aerodrome."}</p>`;
    return;
  }

  container.innerHTML = aircraftstatus_data
    .map(
      (aircraft) => `
        <div class="aerodrome-aircraft-row">
          <div class="min-w-0">
            <p class="truncate font-semibold text-primary-900 dark:text-secondary-50">${escapeHtml(aircraft.callsign)}</p>
            <p class="truncate text-sm text-secondary-500 dark:text-secondary-300">${escapeHtml(aircraft.departure || "—")} → ${escapeHtml(aircraft.arrival || "—")}</p>
            <p class="mt-0.5 text-xs text-secondary-400 dark:text-secondary-400">${escapeHtml(aircraft.type || "—")} · ${Math.round(aircraft.groundspeed)} kt · ${Math.round(aircraft.altitude)} ft</p>
            <div class="mt-1 flex flex-wrap gap-1.5">
              <span class="badge">${escapeHtml(formatGroundstate(aircraft.groundstate))} ${aircraft.gate ? escapeHtml(aircraft.gate) : ""}</span>
            </div>
          </div>
        </div>
      `,
    )
    .join("");
}

let mymarker: LayerGroup | null = null;
let actualPathLayer: LayerGroup | null = null;
let predictedPathLayer: LayerGroup | null = null;
let actualPathLines: Record<string, ReturnType<typeof polyline>> = {};
let predictedPathLines: Record<string, ReturnType<typeof polyline>> = {};
let aircraftAnimationToken = 0;
const aircraftDisplayState: Record<
  string,
  { latitude: number; longitude: number; heading: number }
> = {};
const aircraftDataTransitionMs = 5_000;

function animateAircraftMarker(
  aircraftMarker: Marker,
  track: AerodromeTrackPoint[],
  token: number,
  callsign: string,
): void {
  if (track.length < 2) return;

  const timedTrack = track
    .map((point) => ({
      point,
      time: Date.parse(point.recorded_at),
    }))
    .filter(({ time }) => Number.isFinite(time))
    .sort((first, second) => first.time - second.time);

  if (timedTrack.length < 2) return;

  const markerElement = aircraftMarker
    .getElement()
    ?.querySelector<HTMLElement>(".aerodrome-aircraft-plane-image");
  const previousDisplayState = aircraftDisplayState[callsign];
  const transitionStartedAt = performance.now();

  const animate = (): void => {
    if (token !== aircraftAnimationToken) return;

    const now = Date.now() - aircraftSimulationDelayMs;
    let previous = timedTrack[0];
    let next = timedTrack[timedTrack.length - 1];

    for (let index = 1; index < timedTrack.length; index++) {
      if (timedTrack[index].time >= now) {
        next = timedTrack[index];
        previous = timedTrack[index - 1];
        break;
      }
    }

    const duration = Math.max(1, next.time - previous.time);
    const progress = Math.min(1, Math.max(0, (now - previous.time) / duration));
    const latitude =
      previous.point.latitude +
      (next.point.latitude - previous.point.latitude) * progress;
    const longitude =
      previous.point.longitude +
      (next.point.longitude - previous.point.longitude) * progress;

    const linearTransitionProgress = previousDisplayState
      ? Math.min(
          1,
          (performance.now() - transitionStartedAt) / aircraftDataTransitionMs,
        )
      : 1;
    const transitionProgress =
      linearTransitionProgress *
      linearTransitionProgress *
      (3 - 2 * linearTransitionProgress);
    const displayedLatitude = previousDisplayState
      ? previousDisplayState.latitude +
        (latitude - previousDisplayState.latitude) * transitionProgress
      : latitude;
    const displayedLongitude = previousDisplayState
      ? previousDisplayState.longitude +
        (longitude - previousDisplayState.longitude) * transitionProgress
      : longitude;
    const targetHeading = interpolateHeading(
      previous.point.heading,
      next.point.heading,
      progress,
    );
    const displayedHeading = previousDisplayState
      ? interpolateHeading(
          previousDisplayState.heading,
          targetHeading,
          transitionProgress,
        )
      : targetHeading;

    aircraftMarker.setLatLng([displayedLatitude, displayedLongitude]);
    aircraftDisplayState[callsign] = {
      latitude: displayedLatitude,
      longitude: displayedLongitude,
      heading: displayedHeading,
    };
    if (markerElement) {
      markerElement.style.transform = `translate(-50%, -50%) rotate(${displayedHeading - 90}deg) translateX(-0.65rem)`;
    }

    if (now < next.time || next !== timedTrack[timedTrack.length - 1]) {
      window.requestAnimationFrame(animate);
    }
  };

  window.requestAnimationFrame(animate);
}

function updateActualPaths(): void {
  if (!mymap) return;

  if (!actualPathLayer) {
    actualPathLayer = layerGroup().addTo(mymap);
  }

  const activeCalls = new Set<string>();
  const simulationNow = Date.now() - aircraftSimulationDelayMs;

  forEach(aircraftstatus_data, (aircraft) => {
    if (aircraft.gate) return;

    const actualTrack = aircraft.track
      .filter((point) => !point.predicted)
      .filter((point) => Date.parse(point.recorded_at) <= simulationNow);
    const simulatedPoint = interpolateTrackPoint(aircraft.track, simulationNow);

    if (actualTrack.length <= 1 && !simulatedPoint) return;

    activeCalls.add(aircraft.callsign);
    const path = smoothTrack(
      simulatedPoint ? [...actualTrack, simulatedPoint] : actualTrack,
    );
    const existingLine = actualPathLines[aircraft.callsign];

    if (existingLine) {
      existingLine.setLatLngs(path);
    } else {
      actualPathLines[aircraft.callsign] = polyline(path, {
        color: "#8faecc",
        weight: 3,
        opacity: 0.72,
        smoothFactor: 1,
      }).addTo(actualPathLayer);
    }
  });

  Object.keys(actualPathLines).forEach((callsign) => {
    if (!activeCalls.has(callsign)) {
      actualPathLines[callsign].remove();
      delete actualPathLines[callsign];
    }
  });
}

function updatePredictedPaths(): void {
  if (!mymap) return;

  updateActualPaths();

  if (!predictedPathLayer) {
    predictedPathLayer = layerGroup().addTo(mymap);
  }

  const now = Date.now() - aircraftSimulationDelayMs;

  forEach(aircraftstatus_data, (aircraft) => {
    if (aircraft.gate) return;

    const predictedTrack = aircraft.track
      .filter((point) => point.predicted)
      .filter((point) => {
        const time = Date.parse(point.recorded_at);

        return (
          Number.isFinite(time) &&
          time > now &&
          time <= now + predictedPathHorizonMs
        );
      })
      .sort(
        (first, second) =>
          Date.parse(first.recorded_at) - Date.parse(second.recorded_at),
      );

    if (predictedTrack.length === 0 || !predictedPathLayer) return;

    const currentPoint = interpolateTrackPoint(aircraft.track, now) ?? {
      latitude: aircraft.latitude,
      longitude: aircraft.longitude,
      heading: aircraft.heading,
      recorded_at: new Date(now).toISOString(),
      predicted: false,
    };

    const path = smoothTrack([currentPoint, ...predictedTrack]);
    const existingLine = predictedPathLines[aircraft.callsign];

    if (existingLine) {
      existingLine.setLatLngs(path);
    } else {
      predictedPathLines[aircraft.callsign] = polyline(path, {
        color: "#f59e0b",
        dashArray: "7 8",
        weight: 3,
        opacity: 0.9,
        smoothFactor: 1,
      }).addTo(predictedPathLayer);
    }
  });
}

async function update_map() {
  aircraftAnimationToken++;

  const openPopup = (mymap as
    | (Map & {
        _popup?: { _source?: { popupIdentity?: string } };
      })
    | null)?._popup as
    | { _source?: { popupIdentity?: string } }
    | undefined;
  const openPopupIdentity = openPopup?._source?.popupIdentity;
  let popupToRestore: Marker | null = null;

  if (predictedPathLayer) {
    predictedPathLayer.remove();
    predictedPathLayer = null;
  }
  predictedPathLines = {};

  if (mymarker) {
    mymarker.remove();
  }
  mymarker = layerGroup();
  const map_zoom = mymap?.getZoom();
  if (!map_zoom) return;
  const showGateLabels = map_zoom >= 16;
  forEach(standstatus_data, (stand) => {
    if (mymap == null || mymarker == null) return;
    const stand_lat = stand["latitude"];
    const stand_lon = stand["longitude"];
    const standId = String(stand["id"]);
    const gateAircraft = aircraftstatus_data.find(
      (aircraft) => aircraft.gate === standId,
    );
    const occupied = gateAircraft !== undefined || !isEmpty(stand["occupier"]);
    const aircraftInfo = gateAircraft
      ? `<div class="aerodrome-map-popup-heading">
          <strong>${escapeHtml(gateAircraft.callsign)}</strong>
          <span class="aerodrome-map-popup-status">${escapeHtml(formatGroundstate(gateAircraft.groundstate))}</span>
        </div>
        <span class="aerodrome-map-popup-route">${escapeHtml(`${gateAircraft.departure || "—"} → ${gateAircraft.arrival || "—"}`)}</span>
        <span class="aerodrome-map-popup-meta">${escapeHtml(gateAircraft.type || "Unknown type")} · ${Math.round(gateAircraft.groundspeed)} kt · ${Math.round(gateAircraft.altitude)} ft</span>`
      : "";
    const stand_text = `<div class="aerodrome-map-popup aerodrome-map-popup--stand">
      <span class="aerodrome-map-popup-kicker">Stand</span>
      <strong>${escapeHtml(standId)}</strong>
      ${aircraftInfo || `<span class="aerodrome-map-popup-meta">${occupied ? escapeHtml(String(stand["occupier"])) : "Available"}</span>`}
    </div>`;

    const gateMarker = marker([stand_lat, stand_lon], {
      icon: createGateIcon(occupied, standId, showGateLabels),
    }).addTo(mymarker);
    Object.assign(gateMarker, { popupIdentity: `stand:${standId}` });
    gateMarker.bindPopup(stand_text);
    if (openPopupIdentity === `stand:${standId}`) {
      popupToRestore = gateMarker;
    }
  });

  if (mymap == null) return;
  mymarker.addTo(mymap);

  forEach(aircraftstatus_data, (aircraft) => {
    if (mymap == null || mymarker == null) return;
    if (aircraft.gate) return;
    const aircraft_lat = aircraft["latitude"];
    const aircraft_lon = aircraft["longitude"];
    const aircraft_callsign = aircraft["callsign"];
    const aircraft_heading = aircraft["heading"];
    const displayState = aircraftDisplayState[aircraft_callsign];
    const marker_: Marker = marker(
      [
        displayState?.latitude ?? aircraft_lat,
        displayState?.longitude ?? aircraft_lon,
      ],
      {
        icon: createAircraftIcon(displayState?.heading ?? aircraft_heading),
      },
    ).addTo(mymarker);
    Object.assign(marker_, { popupIdentity: `aircraft:${aircraft_callsign}` });
    const route = `${aircraft.departure || "—"} → ${aircraft.arrival || "—"}`;
    marker_.bindPopup(
      `<div class="aerodrome-map-popup aerodrome-map-popup--aircraft">
      <div class="aerodrome-map-popup-heading">
        <strong>${escapeHtml(aircraft_callsign)}</strong>
        <span class="aerodrome-map-popup-status">${escapeHtml(formatGroundstate(aircraft.groundstate))}</span>
      </div>
      <span class="aerodrome-map-popup-route">${escapeHtml(route)}</span>
      <span class="aerodrome-map-popup-meta">${escapeHtml(aircraft.type || "Unknown type")} · ${Math.round(aircraft.groundspeed)} kt · ${Math.round(aircraft.altitude)} ft</span>
    </div>`,
      {
        className: "aerodrome-map-popup-container",
      },
    );
    if (openPopupIdentity === `aircraft:${aircraft_callsign}`) {
      popupToRestore = marker_;
    }
    animateAircraftMarker(
      marker_,
      aircraft.track,
      aircraftAnimationToken,
      aircraft_callsign,
    );
  });

  updatePredictedPaths();
  popupToRestore?.openPopup();
}

async function metar() {
  const lwc = await getAerodromeComponent();
  const metarResponse = (await lwc.$wire.load_metar()) as TimestampedResponse<
    string | null
  >;
  const metar_data = metarResponse.data ?? null;
  let metar_el = document.getElementById("metar-container");
  if (metar_el) metar_el.innerHTML = metar_data;
}

async function atis() {
  const lwc = await getAerodromeComponent();
  const atisResponse = (await lwc.$wire.load_atis()) as TimestampedResponse<
    Array<0>
  >;
  const atis_data = atisResponse.data ?? [];
  let atis_el = document.getElementById("atis-container");
  let atis_wid = document.getElementById("atis-widget");
  if (!atis_el || !atis_wid) {
    return;
  }

  if (isEmpty(atis_data)) {
    atis_el.textContent =
      atis_el.dataset.emptyText ??
      "No ATIS is currently available for this aerodrome.";
    return;
  }

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
  const lwc = await getAerodromeComponent();
  const indicatorsResponse =
    (await lwc.$wire.load_indicators()) as TimestampedResponse<Array<0>>;
  const data = indicatorsResponse.data ?? [];

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
    tableContainer.innerHTML = `<p class="p-6 text-center text-sm text-secondary-500 dark:text-secondary-300">${emptyText}</p>`;
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
  const lwc = await getAerodromeComponent();
  const data: Array<Event> = await lwc.$wire.load_events();

  const event_container = document.getElementById("event-container");
  while (event_container?.lastChild) {
    event_container.removeChild(event_container.lastChild);
  }

  forEach(data, (e: Event, i: number) => {
    event_container?.insertAdjacentHTML(
      "beforeend",
      `
        <article class="${i > 5 ? "hide" : ""}" id="event-${i}">
          <a class="block" href="${window.location.origin}/events/view/${e.id}">
            <div class="card">
              <div class="card-img-top" style="background-image: url('${e.banner}')"></div>
              <div class="card-body">
                ${
                  e.type === "CPT"
                    ? '<span class="badge mb-2">Controller Practical Test</span>'
                    : ""
                }
                <h3 class="font-semibold text-primary-900 dark:text-secondary-50">${e.name}</h3>
                <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-300">${dayjs(
                  e.start_time,
                ).format("DD.MM.YYYY HH:mm")}z</p>
              </div>
            </div>
          </a>
        </article>
            `,
    );
  });
}
