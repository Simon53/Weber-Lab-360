<!-- container-scroller -->
    <!-- jQuery must be loaded first -->
    <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
    <!-- plugins:js -->
    <script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{asset('vendors/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('vendors/progressbar.js/progressbar.min.js')}}"></script>
    <script src="{{asset('vendors/jvectormap/jquery-jvectormap.min.js')}}"></script>
    <script src="{{asset('vendors/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
    <script src="{{asset('vendors/owl-carousel-2/owl.carousel.min.js')}}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{asset('js/off-canvas.js')}}"></script>
    <script src="{{asset('js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('js/misc.js')}}"></script>
    <script src="{{asset('js/settings.js')}}"></script>
    <script src="{{asset('js/todolist.js')}}"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{asset('js/dashboard.js')}}"></script>
    <!-- End custom js for this page -->
    <script src="{{asset('js/custom.js')}}"></script>
    <!-- Laravel Echo & Reverb client for WebSocket -->
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@laravel/reverb-client@1.0.6/dist/reverb.iife.js"></script>
    <script>
        // CSRF setup
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });
        
        // Wrap entire Echo setup in global error handler
        window.addEventListener('error', function(e) {
            if (e.message && e.message.includes('socketId')) {
                e.preventDefault();
                e.stopPropagation();
                console.warn('WebSocket error suppressed:', e.message);
                return false;
            }
        }, true);
        
        window.addEventListener('unhandledrejection', function(e) {
            if (e.reason && e.reason.message && e.reason.message.includes('socketId')) {
                e.preventDefault();
                e.stopPropagation();
                console.warn('WebSocket promise rejection suppressed');
                return false;
            }
        });
        
        // Echo initialization
        setTimeout(function() {
            try {
                window.Echo = new Echo({
                    broadcaster: 'reverb',
                    key: '{{ env('REVERB_APP_KEY', 'localkey') }}',
                    wsHost: '{{ env('REVERB_HOST', '127.0.0.1') }}',
                    wsPort: {{ env('REVERB_PORT', 9000) }},
                    wssPort: {{ env('REVERB_PORT', 9000) }},
                    forceTLS: false,
                    enabledTransports: ['ws'],
                });
                console.log('Echo initialized');
            } catch (error) {
                console.warn('Echo init failed:', error.message);
                window.Echo = {
                    channel: function() { return this; },
                    listen: function() { return this; },
                    error: function() { return this; }
                };
            }
        }, 100);
    </script>