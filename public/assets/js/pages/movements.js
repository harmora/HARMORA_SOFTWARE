'use strict';

function queryParams(p) {
    return {
        "type": $('#type_filter').val(), // Filter for type of stock movement
        "batch_number": $('#batch_number_filter').val(), // Filter for batch number
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
$('#type_filter, #batch_number_filter').on('change', function (e) {
    e.preventDefault();
    $('#table').bootstrapTable('refresh');
});
