import 'datatables.net-dt/css/dataTables.dataTables.css';
import 'datatables.net-buttons-dt/css/buttons.dataTables.css';
import $ from 'jquery';

window.$ = window.jQuery = $;

import JSZip from 'jszip';
window.JSZip = JSZip;

import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
pdfMake.vfs = pdfFonts;
window.pdfMake = pdfMake;

import 'datatables.net-dt';
import 'datatables.net-buttons-dt';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-datatable').forEach(function (el) {
        const exportOptions = { columns: ':not(.no-export)' };

        $(el).DataTable({
            dom: 'Bfrtip',
            order: [],
            pageLength: 25,
            scrollX: true,
            autoWidth: false,
            buttons: [
                { extend: 'copy', exportOptions },
                { extend: 'csv', exportOptions },
                { extend: 'excel', exportOptions },
                { extend: 'pdf', exportOptions, orientation: 'landscape' },
                { extend: 'print', exportOptions },
            ],
        });
    });
});
