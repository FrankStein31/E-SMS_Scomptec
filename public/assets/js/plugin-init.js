// Plugin Initialization Utilities
// Safe initialization for common plugins used in the application

$(document).ready(function() {
    // Initialize all plugins safely
    initializePlugins();
});

function initializePlugins() {
    // Summernote initialization
    SafeDOM.conditionalInit('#summernote', function(element) {
        $(element).summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        console.log('Summernote initialized successfully');
    });

    // CodeMirror initialization
    SafeDOM.conditionalInit('codeMirrorDemo', function(element) {
        const cm = SafeDOM.codeMirror(element.id, {
            mode: "htmlmixed",
            theme: "monokai",
            lineNumbers: true,
            autoCloseTags: true,
            lineWrapping: true,
            indentUnit: 4,
            smartIndent: true,
            matchBrackets: true
        });
        console.log('CodeMirror initialized successfully');
        return cm;
    });

    // DataTables initialization for common tables
    SafeDOM.conditionalInit('#example1', function(element) {
        $(element).DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
        console.log('DataTable #example1 initialized successfully');
    });

    SafeDOM.conditionalInit('#example2', function(element) {
        $(element).DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
        console.log('DataTable #example2 initialized successfully');
    });

    // Select2 initialization for elements with .select2 class
    $('.select2').each(function() {
        if ($(this).length && !$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        }
    });

    // Select2 initialization for user selection
    $('.select2-user').each(function() {
        if ($(this).length && !$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: 'Pilih User...'
            });
        }
    });

    console.log('All plugins initialization completed');
}

// Export for global use
window.PluginInit = {
    initialize: initializePlugins
};
