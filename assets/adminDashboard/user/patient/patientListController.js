$(document).ready(function () {
    $('#table_patient_list').DataTable({
        paging: true,
        ordering: false,
        info: true,
        searching: true,
        lengthChange: true,
        bInfo: true,
    });

});