(function($) {
    tableDisplay($); 
})(jQuery);

function tableDisplay($) {
    var users_list = users;
    $(document).ready(function () {
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
