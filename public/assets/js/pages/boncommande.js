'use strict';

function queryParams(p) {
    return {
        "fournisseur": $('#fournisseur_filter').val(),  // Add the filter for fournisseur
        "type_achat": $('#type_achat_filter').val(),    // Add the filter for type_achat
        "status": $('#status_filter').val(),            // Add the filter for status
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

window.icons = {
    refresh: 'bx-refresh',
    toggleOff: 'bx-toggle-left',
    toggleOn: 'bx-toggle-right'
}

function loadingTemplate(message) {
    return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical"></i>';
}

$('#fournisseur_filter, #type_achat_filter, #status_filter').on('change', function (e) {
    e.preventDefault();
    $('#table').bootstrapTable('refresh');  // Refresh the table when filters change
});
