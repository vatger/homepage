import '../../app';

let g_eventCount = -1;

$(function () {
    let index = 0;

    $.ajax({
        url: config.routes.api.events.loadEvents,
        type: 'GET',
        dataType: 'json',
        success: (data) => {
            g_eventCount = data.length;
            $.each(data, (key, data) => {
                // Load variables for easier access
                let eventLoader = $(`#event-loader-${key}`);
                let eventBanner = $("#event-banner-" + key); // Note: Before replacing, this is simply a size-placeholder
                let eventTitle = $("#event-title-" + key);
                let eventDate = $("#event-date-" + key);
                let eventReadMore = $("#event-readmore-" + key);
                let eventBannerParent = eventBanner.parent();

                // Remove loading-animation and append banner image
                eventBanner.remove();
                eventBannerParent.append(`<img alt="" src="${data.banner}" class="card-img-top overflow-hidden" id="event-banner-${index}" style="min-height: 200px; min-width: 356px">`);

                // Add event-specific context (name, date, etc.)
                eventTitle.text(data.name);
                eventDate.text(formatDate(new Date(data.start_time)) + "Z");
                eventLoader.remove();
                eventReadMore.css("display", "block");
                eventReadMore.attr('href', '/events/view/'+data.id)

                if (data['type'] !== 'Event')
                    $("#event-cpt-banner-" + key).css('display', 'inline-block');

                // Enable the "show more events" button
                $("#show-events-btn").attr('disabled', false);

                index++;
            });

            if (index < 9) {
                for (let i = index; i < 9; i++) {
                    let eventContainer = $("#event-" + i);
                    eventContainer.remove();
                }
            }
        },
        error: () => {
            for (let i = 0; i < 9; i++) {
                let eventContainer = $("#event-" + i);
                eventContainer.remove();
            }

            let errorContainer = $("#danger-alert-event");
            errorContainer.text("@lang('landing.events.loading-error-text')");
            errorContainer.css("display", "block");
        }
    });

    function formatDate(date) {
        let d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear(),
            hour = '' + d.getUTCHours(),
            min = '' + d.getUTCMinutes();


        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;
        if (hour.length < 2)
            hour = '0' + hour;
        if (min.length < 2)
            min = '0' + min;

        return [day, month, year].join('.') + ", " + [hour, min].join(':');
    }
});

$(function () {
    $("#show-events-btn").on('click', function () {
        $(this).remove();
        if (g_eventCount !== -1 && g_eventCount < 7)
        {
            $("#show-events-btn-container").append(`
                        <div class="alert alert-danger mt-3" role="alert">No further events found (lang)</div>
                    `);
            return;
        }

        for (let i = 0; i < 3; i++)
        {
            $(`#event-${i+6}`).removeClass('hide');
        }
    });
});