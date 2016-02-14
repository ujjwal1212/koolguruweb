/**
 * Function to bind delete function for a table row
 * @returns {undefined}
 */
function bindDelete() {
    $(".btnDelete").bind("click", Delete);
}


/**
 * Function to bind update function for a table row
 * @returns {undefined}
 */
function bindUpdate(id) {
    $(".btnUpdate").bind("click", updateEvent);
}

/**
 * Function to bind edit function for a table row
 * @returns {undefined}
 */
function bindEdit(id) {
    $(".btnEdit").bind("click", editEventRow);
}

function bindSelect() {
    $(".checkbox").bind("click", enableDelete);
}

/**
 * Function to delete a table row
 * @returns {undefined}
 */
function Delete() {
    var par = $(this).parent().parent();
    var msg = 'Really want to delete the record?';
    if (confirmDelete(msg)) {
        par.remove();
    }
}

function DeleteRow(id) {
    var msg = 'Really want to delete the record?';
    if (confirmDelete(msg)) {
        $('#'+id).remove();
    }
}

/**
 * Function to confirm the delete event
 */
function confirmDelete(msg) {
    return confirm(msg);
}
