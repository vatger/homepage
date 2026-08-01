import axios, { AxiosResponse } from "axios";
import { dayjs } from "@/ts/dayjs";
import { zroute } from "@/ts/myziggy";

const EVENT_QUERY_COUNT = 9;
const API_URI = zroute("api.loadEvents", { count: EVENT_QUERY_COUNT });

type Event = {
  id: number;
  name: string;
  start_time: string;
  banner: string;
  type: string;
};

async function getEvents(): Promise<Event[]> {
  return axios.get(API_URI).then((response: AxiosResponse) => {
    const events: Event[] = response.data;
    if (!events?.length) throw new Error("No events found");
    return events;
  });
}

function clear(element: HTMLElement) {
  element.replaceChildren();
}

function eventMarkup(event: Event, index: number): string {
  const cpt =
    event.type === "CPT"
      ? '<span class="badge mb-3">Controller Practical Test</span>'
      : "";

  return `
    <article class="${index > 5 ? "hide" : ""}" id="event-${index}">
      <a class="block h-full" href="${window.location.origin}/events/view/${event.id}">
        <div class="card landing-event-card">
          <div class="card-img-top" style="background-image: url('${event.banner}')"></div>
          <div class="card-body">
            ${cpt}
            <h3 class="text-lg font-semibold text-primary-900 dark:text-secondary-50">${event.name}</h3>
            <p class="mt-3 text-sm text-secondary-500 dark:text-secondary-300">
              ${dayjs.utc(event.start_time).format("DD.MM.YYYY HH:mm")}z
            </p>
          </div>
        </div>
      </a>
    </article>
  `;
}

const container = document.getElementById("event-container");

if (container) {
  getEvents()
    .then((events) => {
      clear(container);
      events.forEach((event, index) =>
        container.insertAdjacentHTML("beforeend", eventMarkup(event, index)),
      );

      container.insertAdjacentHTML(
        "beforeend",
        `<div class="col-span-full mt-2 text-center" id="show-events-btn-container">
          <button type="button" class="btn btn-primary" id="show-events-btn">
            ${container.dataset.showMore ?? "Show more events"}
          </button>
        </div>`,
      );

      document
        .getElementById("show-events-btn")
        ?.addEventListener("click", () => {
          container
            .querySelectorAll(".hide")
            .forEach((event) => event.classList.remove("hide"));
          document.getElementById("show-events-btn-container")?.remove();
        });
    })
    .catch((error) => {
      console.error("Failed to load events: ", error.message);
      clear(container);
      container.insertAdjacentHTML(
        "beforeend",
        `<div class="alert alert-info col-span-full text-center" role="status">
          ${container.dataset.empty ?? "There are currently no upcoming events."}
        </div>`,
      );
    });
}
