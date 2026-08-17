# Event Booking API

API endpoints to block (book) stations as a **vatger event booking**.

They exist so external event tooling – namely the [vatger event manager](https://github.com/vatger/event-manager) – can reserve the stations of an event for the assigned controllers without anybody having to click them together by hand on the homepage.

A booking created through these endpoints is always

- `event = true`
- `vatger_event = true`
- `training = false`, `exam = false`, `voice = true`

which means it behaves exactly like an event booking made by a member of the event team on the homepage: regular bookings of the same station that stand in the way are removed and the affected controllers get a notification.

## Authentication

All endpoints use the regular API token authentication of this application:

```
Authorization: Bearer <token>
```

The token needs the following route ids:

| Route id               | Endpoint                    |
| ---------------------- | --------------------------- |
| `booking.event.index`  | `GET /api/booking/event`    |
| `booking.event.create` | `POST /api/booking/event`   |
| `booking.event.delete` | `DELETE /api/booking/event` |

A token including these route ids can be created with `php artisan vatger:add-api-token`.

## References

Every booking may carry an `event_reference`, a free form string of at most 191 characters that identifies the creator of the booking. The event manager uses `eventmanager:event:<id>` and `eventmanager:weekly:<occurrenceId>`.

The reference is what makes a synchronisation possible: the caller can list its own bookings again and remove exactly those that no longer belong to the current planning – without ever touching bookings of somebody else.

## `GET /api/booking/event`

Lists vatger event bookings.

| Parameter   | Type   | Description                                                  |
| ----------- | ------ | ------------------------------------------------------------ |
| `reference` | string | optional, only bookings with this reference                  |
| `start`     | date   | optional, only bookings that end after this point in time    |
| `end`       | date   | optional, only bookings that start before this point in time |

Without any parameter all bookings that have not ended yet are returned.

```json
[
  {
    "booking_id": 4711,
    "callsign": "EDDF_TWR",
    "cid": 1234567,
    "start": "2026-09-02T18:00:00+00:00",
    "end": "2026-09-02T21:00:00+00:00",
    "reference": "eventmanager:event:42"
  }
]
```

## `POST /api/booking/event`

Books up to 100 stations in one request.

```json
{
  "reference": "eventmanager:event:42",
  "bookings": [
    {
      "callsign": "EDDF_TWR",
      "cid": 1234567,
      "start": "2026-09-02T18:00:00Z",
      "end": "2026-09-02T21:00:00Z"
    }
  ]
}
```

| Field                  | Type    | Description                                                       |
| ---------------------- | ------- | ----------------------------------------------------------------- |
| `reference`            | string  | optional, default reference for all bookings of the request       |
| `bookings[].callsign`  | string  | the station ident, e.g. `EDDF_TWR`                                |
| `bookings[].cid`       | integer | the vatsim id the station is booked for                           |
| `bookings[].start`     | date    | start of the booking, interpreted as UTC                          |
| `bookings[].end`       | date    | end of the booking, interpreted as UTC                            |
| `bookings[].reference` | string  | optional, overrides the reference of the request for this booking |

Every entry is handled on its own, a single failing booking does not abort the rest of the request:

```json
{
  "reference": "eventmanager:event:42",
  "created": 1,
  "existing": 0,
  "conflict": 0,
  "failed": 0,
  "results": [
    {
      "callsign": "EDDF_TWR",
      "cid": 1234567,
      "reference": "eventmanager:event:42",
      "status": "created",
      "message": "Booked.",
      "booking_id": 4711,
      "removed_bookings": 2
    }
  ]
}
```

`status` is one of

| Status     | Meaning                                                              |
| ---------- | -------------------------------------------------------------------- |
| `created`  | the booking was created and synchronised with the vatsim booking api |
| `existing` | the exact same booking already existed, nothing was changed          |
| `conflict` | another event booking already blocks the station in that time frame  |
| `failed`   | the booking could not be created, `message` explains why             |

`removed_bookings` is the number of regular bookings that were removed to free the station.

An entry ends up as `failed` if the end is not after the start, if the booking is longer than 24 hours, if it has already ended, if the station is unknown or not bookable, if the vatsim id is unknown to the homepage, if the controller has no ATC rating or if the vatsim booking api rejected the booking. A malformed payload – a missing field or more than 100 bookings – is rejected as a whole with `422`.

## `DELETE /api/booking/event`

Removes event bookings, either all bookings of a reference or a given list of booking ids. Bookings that are not a vatger event booking are never touched.

```json
{
  "reference": "eventmanager:event:42"
}
```

```json
{
  "booking_ids": [4711, 4712]
}
```

```json
{
  "reference": "eventmanager:event:42",
  "deleted": 2,
  "failed": 0,
  "results": [
    {
      "booking_id": 4711,
      "callsign": "EDDF_TWR",
      "cid": 1234567,
      "start": "2026-09-02T18:00:00+00:00",
      "end": "2026-09-02T21:00:00+00:00",
      "reference": "eventmanager:event:42",
      "status": "deleted",
      "message": "Booking deleted!"
    }
  ]
}
```

Deleting a booking that has not ended yet notifies the controller, exactly like a deletion through the homepage does.
