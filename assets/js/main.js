/* 
Confirmación antes de eliminar una tarea básica con jQuery
$(document).ready(function() {
    $(".btn-delete").click(function(e) {
        if (!confirm("¿Seguro que deseas eliminar esta tarea?")) {
            e.preventDefault();
        }
    });
});
 */
//Confirmación antes de eliminar una tarea básica con jQuery y SweetAlert2
$(document).ready(function() {
    $(".btn-delete").click(function(e) {
        e.preventDefault(); // prevenimos el comportamiento por defecto
        
        const url = $(this).attr("href"); // capturamos el enlace de la acción de eliminar

        Swal.fire({
            title: '¿Seguro que deseas eliminar esta tarea?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, redirigimos o ejecutamos la acción
                window.location.href = url;
            }
        });
    });
});