import './bootstrap'; // axios setup

// Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;

// SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// Trix Editor
import 'trix'
import 'trix/dist/trix.css'

// Optional: kalau mau langsung start Alpine di semua halaman
Alpine.start();
