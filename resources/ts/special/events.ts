import axios, { AxiosResponse } from 'axios';
import { dayjs } from '@/ts/dayjs';
import { zroute } from '@/ts/myziggy';

const EVENT_QUERY_COUNT = 9;
const API_URI = zroute('api.loadEvents', { count: EVENT_QUERY_COUNT }); //window.location.origin + "/web_api/queryevents/" + EVENT_QUERY_COUNT;

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
    organisers: Array<{ region?: string; division?: string; subdivision?: string; organised_by_vatsim: boolean }>;
    vso_name?: string;
};

async function getEvents(): Promise<Event[]> {
    return axios.get(API_URI).then((res: AxiosResponse) => {
        const events: Array<Event> = res.data;

        if (events == null || events.length == 0) {
            throw new Error('No events found (len 0)');
        }

        return events;
    });
}

function removeChildrenFromElement(el: HTMLElement | null) {
    if (el == null) {
        return;
    }

    while (el.lastChild) {
        el.removeChild(el.lastChild);
    }
}

getEvents()
    .then(async (events: Array<Event>) => {
        const event_container = document.getElementById('event-container');
        removeChildrenFromElement(event_container);

        events.forEach((e: Event, i: number) => {
            event_container?.insertAdjacentHTML(
                'beforeend',
                `
                <div class="col-lg-4 col-md-6 mb-4 pb-2 ${i > 5 ? 'hide' : ''}" id="event-${i}">
                    <a href="${window.location.origin}/events/view/${e.id}">
                        <div class="card blog rounded border-0 shadow overflow-hidden">
                            <div class="position-relative">
                                <div class="overlay rounded-top"></div>
                                <div class="card-img-top loader-show overflow-hidden" id="event-banner-1" style="min-height: 200px; min-width: 356px; background: url('${
                                    e.banner
                                }') center; background-size: cover;"></div>
                            </div>
                            <div class="card-body content">
                                <span class="badge rounded-pill bg-soft-primary mb-2 ${e.type == 'CPT' ? '' : 'hide'}">
                                    Controller Practical Test
                                </span>
                                <h5>
                                    <span class="card-title title text-dark" id="event-title-1">${e.name}</span>
                                </h5>
                                <div class="post-meta d-flex justify-content-between mt-3">
                                    <ul class="list-unstyled mb-0">
                                        <li class="list-inline-item me-2 mb-0">
                                            <span href="javascript:void(0)" class="text-muted" id="event-date-1">
                                                ${dayjs.utc(e.start_time).format('DD.MM.YYYY HH:mm') + 'z'}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            `
            );
        });

        event_container?.insertAdjacentHTML(
            'beforeend',
            `
            <div style="text-align: center" class="mt-4 mb-0 pb-0" id="show-events-btn-container">
                <button type="button" class="btn btn-pills btn-soft-primary" id="show-events-btn"> Show More</button>
            </div>
        `
        );

        document.getElementById('show-events-btn')?.addEventListener('click', () => {
            for (let i = 0; i < events.length; i++) {
                document.getElementById(`event-${i}`)?.classList.remove('hide');
            }

            document.getElementById('show-events-btn')?.remove();
        });
    })
    .catch((err) => {
        console.error('Failed to load events: ', err.message);

        const event_container = document.getElementById('event-container');
        removeChildrenFromElement(event_container);

        event_container?.insertAdjacentHTML(
            'beforeend',
            `
            <div class="alert alert-danger mt-2 text-center" role="alert">
                Es gibt derzeit keine Events die geladen werden können. Schaue zu einem späteren Zeitpunkt noch mal vorbei.
            </div>
        `
        );
    });
