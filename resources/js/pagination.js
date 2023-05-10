export class Pagination {
    // Saved Parameters for storing loaded pages
    storedPageData = [];
    storedPages = [];
    buttonsDisabled = false;

    currentPage = -1;
    maxPage = -1;
    ajaxSearchUrl = "";
    loadPaginatedUrl = "";
    callbackFunction = (value) => {};

    options = {};
    itemsPerPage = 15;

    // Custom
    pageParam = new URLSearchParams(window.location.search).get('page');

    /**
     * Creates Pagination object to be used on the website.
     * @param options
     * @param callback
     */
    constructor(options,
                callback = (value) => {}
    ) {
        this.currentPage = options.currentPage;
        this.maxPage = options.maxPage;
        this.ajaxSearchUrl = options.ajaxSearchUrl;
        this.loadPaginatedUrl = options.loadPaginatedUrl;
        this.options = options;
        this.callbackFunction = callback;

        // Check options, allows some to be left empty
        this.checkOptions();

        this.initialize();
    }

    checkOptions() {
        let opt = this.options;
        if (!opt.content_container) opt.content_container = $("#content-container");

        if (!opt.error) opt.error = {};
        if (!opt.error.error_container) opt.error.error_container = $("#error-container");
        if (!opt.error.error_countdown) opt.error.error_countdown = $("#error-countdown");

        if (!opt.pagination) opt.pagination = {};
        opt.pagination = {
            page_container: opt.pagination.page_container ? opt.pagination.page_container : $(".pagination"),
            page_item: opt.pagination.page_item ? opt.pagination.page_item : $(".page-item"),
            page_item_first: opt.pagination.page_item_first ? opt.pagination.page_item_first : $("#page-item-first"),
            page_item_last: opt.pagination.page_item_last ? opt.pagination.page_item_last : $("#page-item-last"),
            page_item_prev: opt.pagination.page_item_prev ? opt.pagination.page_item_prev : $("#page-item-prev"),
            page_item_next: opt.pagination.page_item_next ? opt.pagination.page_item_next : $("#page-item-next"),
            current_page_indicator: opt.pagination.current_page_indicator ? opt.pagination.current_page_indicator : $("#current-page-indicator"),
        }
    }

    update(type) {
        const _this = this;

        if (type === 'add' && this.storedPageData[_this.maxPage].length === _this.itemsPerPage)
        {
            _this.maxPage++;
        }

        this.storedPages = [];
        this.storedPageData = [];

        _this.loadData(_this.currentPage);
    }

    /**
     * Initializes the class, including initial load
     */
    initialize() {
        const _this = this;
        // Checks if URL contains ?page=x and loads according page-data.
        // If null, loads first page
        if (_this.pageParam) {_this.loadData(_this.currentPage); $(".pagination").css('display', 'flex');} else {_this.loadData(1); $(".pagination").css('display', 'flex');}

        handleUserInput();
        
        /**
         * Handles the user's input and registers according event listeners
         */
        function handleUserInput() {
            let timer;
            const input = _this.options.search_input;
            const pagination = _this.options.pagination.page_container;
            const page_item = _this.options.pagination.page_item;
            const page_indicator = _this.options.pagination.current_page_indicator;

            /**
             * Register event listener for click-event on .page-item
             */
            page_item.on('click', function() {
                if (_this.buttonsDisabled) return;
                let action = $(this).data('action');

                // Check if "Next" / "Prev" button clicked
                if (action) {
                    switch (action) {
                        case "prev":
                            if (_this.currentPage === 1) return;
                            _this.currentPage--;
                            _this.loadData(_this.currentPage);
                            return;

                        case "next":
                            if (_this.currentPage === _this.maxPage) return;
                            _this.currentPage++;
                            _this.loadData(_this.currentPage);
                            return;

                        case "last":
                            if (_this.currentPage === _this.maxPage) return;
                            _this.currentPage = _this.maxPage;
                            _this.loadData(_this.currentPage);
                            return;

                        case "first":
                            if (_this.currentPage === 1) return;
                            _this.currentPage = 1;
                            _this.loadData(_this.currentPage);
                            return;
                    }
                }
            });

            /**
             * Register event listener for keyup-event on input
             */
            input.on('keyup', function (e) {
                if ($(this).val().length === 0) {
                    search();
                    return;
                }
                if (timer) {
                    clearTimeout(timer);
                }
                timer = setTimeout(search, 400);
            });
            
            /**
             * Calls the corresponding API call and includes the relevant data such as query_string
             */
            function search() {
                let text = input.val();

                // Reset page to last downloaded state if no search_query present
                if (text.length === 0) {
                    _this.loadData(_this.currentPage);
                    page_indicator.text(_this.currentPage);
                    pagination.css('display', 'flex');
                    return;
                }

                searchData(text);
                pagination.css('display', 'none');
            }

            /**
             * Searches the user in the database
             * @param search_string
             */
            function searchData(search_string) {
                $.ajax({
                    url: _this.ajaxSearchUrl,
                    type: 'GET',
                    data: {
                        search_param: search_string
                    },
                    success: (data) => {
                        _this.showResults(data, -1);
                    },
                    error: () => {
                        showNoty("Fehler beim Laden der Daten!", 'danger', 1000);
                        _this.showResults([], -1);
                    }
                });
            }
        }
    }

    /**
     * Loads the data for the specific page using an AJAX request
     * @param pageId
     */
    loadData(pageId) {
        const _this = this;

        _this.buttonsDisabled = true;
        window.history.pushState('', '', `?page=${pageId}`);

        // Check if we have already downloaded this dataset previously
        if (_this.storedPages.includes(pageId)) {
            _this.showResults(_this.storedPageData[pageId], pageId);
            return;
        }

        // If we haven't downloaded it previously, download the dataset now
        $.ajax({
            url: _this.loadPaginatedUrl,
            type: 'GET',
            data: {
                page: pageId
            },
            success: (data) => {
                _this.storedPageData[data['current_page']] = data;
                _this.storedPages.push(pageId);
                _this.showResults(data, pageId);
            },
            error: () => {
                _this.options.content_container.empty();
                _this.options.error.error_container.css('display', 'block');

                let i = 60;

                setInterval(() => {
                    if (i === 0) {
                        window.location.reload();
                    }

                    _this.options.error.error_countdown.text(i);
                    i--;
                }, 1000);

            }
        });
    }

    /**
     * Shows the result of either loadData() or search() (from inside initialize())
     * @param data
     * @param pageId
     */
    showResults(data, pageId) {
        const _this = this;

        const list_content = _this.options.list_content;
        const page_item_first = _this.options.pagination.page_item_first;
        const page_item_last = _this.options.pagination.page_item_last;
        const page_item_prev = _this.options.pagination.page_item_prev;
        const page_item_next = _this.options.pagination.page_item_next;
        const page_current_indicator = _this.options.pagination.current_page_indicator;

        list_content.empty();

        let iterator = data['data'];
        // Check if page still exists (i.e. after deleting)
        if (pageId > 1 && _this.storedPageData[pageId] && _this.storedPageData[pageId]['data'].length === 0)
        {
            // Reduce some variables
            pageId--;
            _this.currentPage--;
            _this.maxPage--;

            // If we only have one page left, then we also hide the pagination controls
            if (pageId === 1 && this.maxPage === 1)
            {
                page_item_last.css('display', 'none');
                page_item_next.addClass('text-muted');
                $("#page-indicator-dots").css('display', 'none');
            }

            // Load data from this one page
            this.loadData(pageId);
            return;
        }

        if (pageId !== -1)
        {
            // If more than one page
            if ((pageId === '1' || pageId === 1) && 1 !== _this.maxPage)
            {
                page_item_first.css('display', 'none');
                page_item_prev.addClass('text-muted');

                // We know here, that we have more than one page and that we're currently on page 1 (for example when adding elements to the set)
                page_item_next.css('display', 'block');
                page_item_last.css('display', 'block');
                page_item_last.empty()
                    .append(`<a class="page-link" href="javascript:void(0)">${_this.maxPage}</a>`);
                $("#page-indicator-dots").css('display', 'block');
            } else
            {
                page_item_first.css('display', 'block');
                if (1 !== _this.maxPage) {
                    page_item_prev.removeClass('text-muted');
                    page_item_last.empty()
                        .append(`<a class="page-link" href="javascript:void(0)">${_this.maxPage}</a>`);
                }
                else {
                    $("#page-item-last").css('display', 'none');
                    $("#page-item-first").css('display', 'none');
                    $("#page-indicator-dots").css('display', 'none');
                    $("#page-item-next").addClass('text-muted');
                    $("#page-item-prev").addClass('text-muted');
                }
            }

            if ((pageId === _this.maxPage || pageId === _this.maxPage.toString()) && 1 !== _this.maxPage)
            {
                page_current_indicator.css('display', 'none');
                page_item_last.addClass('active');
                page_item_next.addClass('text-muted');
            } else
            {
                page_current_indicator.css('display', 'block');
                page_item_last.removeClass('active');
                if (1 !== _this.maxPage) page_item_next.removeClass('text-muted');
            }

            page_current_indicator.text(pageId);
            $("#element-count").text(`${data['total']}`);
            if (!data['from'] && !data['to'])
            {
                $("#dataset-length").text(`Zeige Elemente ${"0" + " - " +"0"} aus ${data['total']} Ergebnissen`);
                list_content.append(`<tr class="text-center">
                            <td colspan="${_this.options.cols ? _this.options.cols : 4}" class="text-muted">Keine Treffer</td>
                         </tr>`);
                return;
            }
            $("#dataset-length").text(`Zeige Elemente ${data['from'] + " - " + data['to']} aus ${data['total']} Ergebnissen`);
        } else
        {
            iterator = data;
            if (iterator.length === 0)
            {
                list_content
                    .append(`<tr class="text-center" id="noresult-found">
                            <td colspan="${_this.options.cols ? _this.options.cols : 4}" class="text-muted">Keine Treffer</td>
                         </tr>`);
            }

            $("#dataset-length").text(`Die Suche ergab ${iterator.length} Treffer`);
        }

        $.each(iterator, (key, value) => {
            _this.callbackFunction(value, list_content);
        });

        _this.buttonsDisabled = false;
    }
}
