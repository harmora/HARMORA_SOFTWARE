'use strict';

function queryParams(p) {
    return {
        "document_number": $('#document_number_filter').val(), // Filter for document number
        "order": $('#order_filter').val(), // Filter for order
        "client": $('#client_filter').val(), // Filter for client
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

// Define icons for different actions
window.icons = {
    refresh: 'bx-refresh',
    toggleOff: 'bx-toggle-left',
    toggleOn: 'bx-toggle-right'
}

// Template for loading spinner
function loadingTemplate(message) {
    return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical"></i>';
}

// Event listener for filter changes to refresh the table
$('#document_number_filter, #order_filter, #client_filter').on('change', function (e) {
    e.preventDefault();
    $('#table').bootstrapTable('refresh');
});
