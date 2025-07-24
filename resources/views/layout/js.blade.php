<!-- latest jquery-->
<script src="{{ asset('assets/js/jquery-3.6.3.min.js') }}"></script>

<!-- Simple bar js-->
<script src="{{ asset('assets/vendor/simplebar/simplebar.js') }}"></script>

<!-- phosphor js -->
<script src="{{ asset('assets/vendor/phosphor/phosphor.js') }}"></script>

<!-- Bootstrap js-->
<script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

<!-- select2 -->
<script src="{{ asset('assets/vendor/select/select2.min.js') }}"></script>

<!--js-->
<script src="{{ asset('assets/js/select.js') }}"></script>

<!-- App js-->
<script src="{{ asset('assets/js/script.js') }}"></script>

<!-- Customizer js-->
<script src="{{ asset('assets/js/customizer.js') }}"></script>

<!-- Custom Navigation JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple manual toggle without Bootstrap Collapse interference
    const toggles = document.querySelectorAll('.main-nav a[data-bs-toggle="collapse"]');
    
    // Remove Bootstrap data attributes to prevent conflicts
    toggles.forEach(function(toggle) {
        // Store the target for our custom handler
        const targetId = toggle.getAttribute('href');
        const target = document.querySelector(targetId);
        
        // Remove Bootstrap data attributes to prevent auto-initialization
        toggle.removeAttribute('data-bs-toggle');
        toggle.removeAttribute('aria-controls');
        toggle.removeAttribute('role');
        
        // Add our custom click handler
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (target) {
                const isOpen = target.classList.contains('show');
                
                // Close all other menus first
                document.querySelectorAll('.main-nav .collapse').forEach(function(otherElement) {
                    if (otherElement !== target) {
                        otherElement.classList.remove('show');
                        const otherToggle = document.querySelector('a[href="#' + otherElement.id + '"]');
                        if (otherToggle) {
                            otherToggle.setAttribute('aria-expanded', 'false');
                        }
                    }
                });
                
                // Toggle this menu
                if (isOpen) {
                    target.classList.remove('show');
                    this.setAttribute('aria-expanded', 'false');
                } else {
                    target.classList.add('show');
                    this.setAttribute('aria-expanded', 'true');
                }
            }
        });
        
        // Initialize as closed
        toggle.setAttribute('aria-expanded', 'false');
        if (target) {
            target.classList.remove('show');
        }
    });
    
    console.log('Custom navigation initialized without Bootstrap conflicts');
});
</script>
