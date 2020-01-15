(function($) {
    tableDisplay($); 

    function tableDisplay($) {
    // Use the params variable of wp_localize_script()
    // and parse it with JSON.parse()
    var users_list = JSON.parse(params);

    $(document).ready(function () {
        // Create and render the table with a set of options
        $('#users-table').DataTable({
            data: users_list,
            aaSorting: [],
            pageLength: 10,
            lengthChange: false,
            deferRender: true,
            dom: '<"top"i>rt<"bottom"flp><"clear">',
            columnDefs: [
                {
                    searchable: false,
                    targets: [0, 1]
                },
                {
                    orderable: false,
                    targets: 2
                }
            ],
            language: {
                search: "Filter by role",
                info: "Showing page _PAGE_ of _PAGES_"
            }
        });
    });
}

})(jQuery);

