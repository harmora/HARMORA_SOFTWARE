
window.icons = {
    refresh: 'bx-refresh'
}

function loadingTemplate(message) {
    return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical" ></i>'
}

function actionFormatterUsers(value, row, index) {
    return [
        '<a href="/users/edit/' + row.id + '" title=' + label_update + '>' +
        '<i class="bx bx-edit mx-1">' +
        '</i>' +
        '</a>' +
        '<button title=' + label_delete + ' type="button" class="btn delete" data-id=' + row.id + ' data-type="users">' +
        '<i class="bx bx-trash text-danger mx-1"></i>' +
        '</button>'
    ]
}

function actionFormatterClients(value, row, index) {
    return [
        '<a href="/clients/edit/' + row.id + '" title=' + label_update + '>' +
        '<i class="bx bx-edit mx-1">' +
        '</i>' +
        '</a>' +
        '<button title=' + label_delete + ' type="button" class="btn delete" data-id=' + row.id + ' data-type="clients">' +
        '<i class="bx bx-trash text-danger mx-1"></i>' +
        '</button>'
    ]
}

function queryParamsCommandes(p) {
    return {
        "status_ids": $('#commande_status_filter').val(),
        "priority_ids": $('#commande_priority_filter').val(),
        "user_ids": $('#commandes_user_filter').val(),
        "client_ids": $('#commandes_client_filter').val(),
        "product_ids": $('#commandes_product_filter').val(),
        "commande_start_date_from": $('#commande_start_date_from').val(),
        "commande_start_date_to": $('#commande_start_date_to').val(),
        "commande_end_date_from": $('#commande_end_date_from').val(),
        "commande_end_date_to": $('#commande_end_date_to').val(),
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
$('#commande_status_filter, #commande_priority_filter, #commandes_user_filter, #commandes_client_filter, #commandes_product_filter').on('change', function (e, refreshTable) {
    e.preventDefault();
    if (typeof refreshTable === 'undefined' || refreshTable) {
        $('#commande_table').bootstrapTable('refresh');
    }
});

function userFormatter(value, row, index) {
    return '<div class="d-flex">' + row.photo + '<div class="mx-2 mt-2"><h6 class="mb-1">' + row.first_name + ' ' + row.last_name +
        (row.status === 1 ? ' <span class="badge bg-success">Active</span>' : ' <span class="badge bg-danger">Deactive</span>') +
        '</h6><p class="text-muted">' + row.email + '</p></div>' +
        '</div>';

}

function clientFormatter(value, row, index) {
    return '<div class="d-flex">' + row.profile + '<div class="mx-2 mt-2"><h6 class="mb-1">' + row.first_name + ' ' + row.last_name +
        (row.status === 1 ? ' <span class="badge bg-success">Active</span>' : ' <span class="badge bg-danger">Deactive</span>') +
        '</h6><p class="text-muted">' + row.email + '</p></div>' +
        '</div>';

}

function assignedFormatter(value, row, index) {
    return '<div class="d-flex justify-content-start align-items-center"><div class="text-center mx-4"><span class="badge rounded-pill bg-primary" >' + row.products + '</span><div>' + label_products + '</div></div>' +
        '<div class="text-center"><span class="badge rounded-pill bg-primary" >' + row.commandes + '</span><div>' + label_commandes + '</div></div></div>'
}

function queryParamsUsersClients(p) {
    return {
        type: $('#type').val(),
        typeId: $('#typeId').val(),
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

$(document).on('click', '.clear-filters', function (e) {
    e.preventDefault();
    $('#commande_start_date_between').val('');
    $('#commande_end_date_between').val('');
    $('#commande_start_date_from').val('');
    $('#commande_start_date_to').val('');
    $('#commande_end_date_from').val('');
    $('#commande_end_date_to').val('');
    $('#commandes_product_filter').val('').trigger('change', [0]);
    $('#commandes_user_filter').val('').trigger('change', [0]);
    $('#commandes_client_filter').val('').trigger('change', [0]);
    $('#commande_status_filter').val('').trigger('change', [0]);
    $('#commande_priority_filter').val('').trigger('change', [0]);
    $('#commande_table').bootstrapTable('refresh');
})

$(document).on('submit', '#commandeForm', function (e) {
    e.preventDefault();

    var formData = new FormData(this);
    var currentForm = $(this);
    var submit_btn = $(this).find('#submit_btn');
    var btn_html = submit_btn.html();
    var btn_val = submit_btn.val();
    var button_text = (btn_html != '' || btn_html != 'undefined') ? btn_html : btn_val;

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
        },
        beforeSend: function () {
            submit_btn.html('Please wait...');
            submit_btn.attr('disabled', true);
        },
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (result) {
            submit_btn.html(button_text);
            submit_btn.attr('disabled', false);
            if (result['error'] == true) {
                toastr.error(result['message']);
            } else {
                var modalWithClass = $('#create_commande_modal');
                if (modalWithClass.length > 0) {
                    modalWithClass.modal('hide');
                    $('#commandeTable').bootstrapTable('refresh'); // Refresh the specific table
                    currentForm[0].reset();
                }
                toastr.success(result['message']);
            }
        },
        error: function (xhr, status, error) {
            submit_btn.html(button_text);
            submit_btn.attr('disabled', false);
            if (xhr.status === 422) {
                var response = xhr.responseJSON;
                var errors = response.errors;
                
                for (var field in errors) {
                    var inputField = currentForm.find('[name="' + field + '"]');
                    var errorMessage = errors[field][0];
                    
                    inputField.after('<span class="text-danger">' + errorMessage + '</span>');
                }
                toastr.error('Please correct the errors and try again.');
            } else {
                toastr.error(error);
            }
        }
    });
});
