// Auth page specific JavaScript
// Minimal JavaScript for authentication pages only

$(document).ready(function() {
    // Remove any conflicting event listeners
    
    // Basic form validation
    $('form').on('submit', function() {
        // Add loading state if needed
        $(this).find('button[type="submit"]').prop('disabled', true);
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
});
