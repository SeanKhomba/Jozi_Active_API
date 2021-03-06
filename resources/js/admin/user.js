import Swal from "sweetalert2";

if (window.location.pathname.split('/')[2] === "users") {
        function deleteUser(e) {
            if (e.target.id === 'deleteUser') {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure you want to delete this user?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        window.onbeforeunload = false;
                        window.location.replace(e.target.href);
                    }
                })
            }
        }
        document.addEventListener('click', deleteUser);

    }
