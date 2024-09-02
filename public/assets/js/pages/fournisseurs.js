'use strict';
function queryParams(p) {
    return {
        "fournisseur_status_filter": $('#fournisseur_status_filter').val(),
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
}

function loadingTemplate(message) {
    return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical" ></i>'
}

$('#fournisseur_status_filter').on('change', function (e) {
    e.preventDefault();
    $('#table').bootstrapTable('refresh');
});
