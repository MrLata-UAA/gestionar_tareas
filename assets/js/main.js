$(document).ready(function() {
    $(".btn-delete").click(function(e) {
        if (!confirm("¿Seguro que deseas eliminar esta tarea?")) {
            e.preventDefault();
        }
    });
});
