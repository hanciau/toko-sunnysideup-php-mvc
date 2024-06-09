function toggleAllCheckboxes() {
    var checkboxes = document.getElementsByName('selected_items[]');
    var selectAllCheckbox = document.getElementById('selectAll');

    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = selectAllCheckbox.checked;
    }
}
function confirmDelete() {
return confirm('Are you sure you want to delete this item?');
}