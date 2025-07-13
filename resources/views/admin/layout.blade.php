<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <div class="w-64 bg-white shadow-lg fixed top-0 bottom-0 z-30">
            @include('admin.partials.sidebar')
        </div>
          <div id="realtime-notifikasi" class="fixed top-6 right-6 z-[9999] flex flex-col gap-3">
          
        </div>
        {{-- Konten --}}
        <div class="flex-1 ml-64 overflow-y-auto p-6">
            @yield('content')
        </div>
    </div>
    @vite(['resources/js/app.js', 'resources/js/echo.js'])
    
    <script>
        // Aggressively prevent admin from going back
        (function() {
            @if(session('justLoggedIn'))
                // Clear all browser history and start fresh
                if (window.history.replaceState) {
                    window.history.replaceState(null, null, window.location.href);
                }
                // Clear the session flag
                @php session()->forget('justLoggedIn'); @endphp
            @endif
            
            // Create a barrier in history
            function createHistoryBarrier() {
                window.history.pushState(null, null, window.location.href);
            }
            
            // Initial barrier
            createHistoryBarrier();
            
            // Counter untuk tracking berapa kali user mencoba back (global variable)
            window.backAttempts = window.backAttempts || 0;
            
            // Handle popstate (back button)
            window.addEventListener('popstate', function(event) {
                event.preventDefault();
                event.stopPropagation();
                
                window.backAttempts++;
                console.log('Back attempt:', window.backAttempts);
                
                // Jika lebih dari 3 kali mencoba back, buka new tab dan tutup current tab
                if (window.backAttempts > 3) {
                    try {
                        // Buka dashboard admin di new tab
                        const newTab = window.open('{{ route("admin.dashboard") }}', '_blank', 'noopener,noreferrer');
                        
                        if (newTab) {
                            // Fokus ke tab baru
                            newTab.focus();
                            
                            // Tutup tab saat ini setelah delay singkat
                            setTimeout(() => {
                                window.close();
                                // Jika window.close() tidak bekerja (modern browsers), redirect ke about:blank
                                if (!window.closed) {
                                    window.location.replace('about:blank');
                                }
                            }, 100);
                        } else {
                            // Fallback jika popup blocked
                            alert('Tab baru diblokir. Anda akan diarahkan ke dashboard.');
                            window.location.replace('{{ route("admin.dashboard") }}');
                        }
                    } catch (error) {
                        console.log('Error opening new tab:', error);
                        // Fallback ultimate
                        window.location.replace('{{ route("admin.dashboard") }}');
                    }
                    return false;
                }
                
                // Recreate barrier untuk attempt 1-3
                createHistoryBarrier();
                
                // Reset counter after some time
                setTimeout(() => {
                    window.backAttempts = Math.max(0, window.backAttempts - 1);
                }, 3000);
                
                return false;
            });
            
            // Additional protection
            window.addEventListener('beforeunload', function(e) {
                createHistoryBarrier();
            });
            
            // Handle browser navigation buttons
            window.addEventListener('hashchange', function(e) {
                e.preventDefault();
                createHistoryBarrier();
                return false;
            });
            
            // Disable right-click context menu on admin pages
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                return false;
            });
            
            // Disable keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // F5 or Ctrl+R or Ctrl+Shift+R (refresh)
                if (e.key === 'F5' || (e.ctrlKey && e.key === 'r') || (e.ctrlKey && e.shiftKey && e.key === 'R')) {
                    e.preventDefault();
                    window.location.replace(window.location.href);
                    return false;
                }
                
                // Disable Alt+Left (back) and Alt+Right (forward)
                if (e.altKey && (e.key === 'ArrowLeft' || e.key === 'ArrowRight')) {
                    e.preventDefault();
                    window.backAttempts++;
                    if (window.backAttempts > 3) {
                        const newTab = window.open('{{ route("admin.dashboard") }}', '_blank');
                        if (newTab) {
                            newTab.focus();
                            setTimeout(() => window.close(), 100);
                        }
                    }
                    return false;
                }
                
                // Disable Backspace navigation (when not in input)
                if (e.key === 'Backspace' && !['INPUT', 'TEXTAREA'].includes(e.target.tagName)) {
                    e.preventDefault();
                    return false;
                }
                
                // Disable Ctrl+H (history)
                if (e.ctrlKey && e.key === 'h') {
                    e.preventDefault();
                    return false;
                }
            });
            
            // Override window.history methods
            const originalPushState = history.pushState;
            const originalReplaceState = history.replaceState;
            
            history.pushState = function() {
                originalPushState.apply(history, arguments);
                createHistoryBarrier();
            };
            
            history.replaceState = function() {
                originalReplaceState.apply(history, arguments);
                createHistoryBarrier();
            };
            
        })();
    </script>
    
    @stack('scripts')
</body>
</html>
