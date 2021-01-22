import Swal from "sweetalert2";

if (window.location.pathname.split('/')[2] === "payments") {
        function deleteBooking(e) {
            if (e.target.id === 'deletePayment') {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure you want to delete this payment?',
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
        document.addEventListener('click', deleteBooking);

    }
