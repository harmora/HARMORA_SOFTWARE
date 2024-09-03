'use strict';

function queryParams(p) {
    return {
        "document_type_filter": $('#document_type_filter').val(), // Filter for document number
        // "order": $('#order_filter').val(), // Filter for order
        // "client": $('#client_filter').val(), // Filter for client
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
}

// Template for loading spinner
function loadingTemplate(message) {
    return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical"></i>';
}

// Event listener for filter changes to refresh the table
$('#document_type_filter').on('change', function (e) {
    e.preventDefault();
    $('#table').bootstrapTable('refresh');
});
