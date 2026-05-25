import './bootstrap';

import Alpine from 'alpinejs';
import './admin';
import Swal from "./sweet-alert";

window.Swal = Swal;
window.Alpine = Alpine;
Alpine.start();

window.testSuccess = () => Swal.success("Great job!", "You clicked the success button!");
window.testError = () => Swal.error("Oops...", "Something went wrong!");
window.testWarning = () => Swal.warning("Warning!", "This is a warning message.");
window.testInfo = () => Swal.info("Information", "This is an info message.");
window.testConfirm = async () => {
    const result = await Swal.confirm("Are you sure?", "You won't be able to revert this!", "Yes, do it!");
    if (result.isConfirmed) Swal.success("Confirmed!", "Your action has been confirmed.");
    else Swal.info("Cancelled", "Your action was cancelled.");
};
window.testToast = () => Swal.toast({ title: "Hello!", text: "This is a toast.", type: "success", backdrop: false, timer: 4000 });
