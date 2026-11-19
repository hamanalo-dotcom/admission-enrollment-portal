<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <title>AEP - SRHS</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css" type="text/css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-ui.css') }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .header-container {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }

        .main {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            padding-top: 80px;
            position: relative;
            z-index: 10;
        }

        @media (max-width: 768px) {
            .header-container {
                margin-left: 0 !important;
            }
            
            .main {
                margin-left: 0;
                padding-top: 70px;
            }
        }

        .main.shift {
            margin-left: 10px;
        }

        .main.public-layout-main {
            margin-left: 0;
            padding: 0;
            min-height: auto;
        }

        @media (max-width: 768px) {
            .main.public-layout-main {
                padding: 0;
            }
        }

        .box {
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            margin-bottom: 20px;
        }

        .table-responsive {
            margin-top: 20px;
        }

        hr {
            margin: 20px 0;
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 30px 0 0 0;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .pagination li {
            display: inline-block;
            margin: 0;
        }

        .pagination li a,
        .pagination li span {
            display: inline-block;
            padding: 10px 14px;
            border: 1px solid #d4e5f3;
            border-radius: 6px;
            text-decoration: none;
            color: #2c5aa0;
            font-weight: 600;
            transition: all 0.3s ease;
            min-width: 40px;
            text-align: center;
            background: #fff;
        }
        
        .pagination li.disabled span {
            color: #999;
            border-color: #e0e0e0;
            background: #f5f5f5;
            cursor: not-allowed;
        }

        .pagination li a:hover {
            background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
            color: white;
            border-color: #2c5aa0;
        }

        .pagination li.active span {
            background: linear-gradient(135deg, #2c5aa0 0%, #526c86 100%);
            color: white;
            border-color: #2c5aa0;
        }

    </style>
</head>

<body>

    @php
        $isPublicAdmissionPage = request()->routeIs('admission.create.public')
            || request()->routeIs('admission.success');
    @endphp

    @if(!$isPublicAdmissionPage)
        @include('layout.sidebar')

        @include('layout.header')
    @endif

    <div class="main {{ $isPublicAdmissionPage ? 'public-layout-main' : '' }}" id="mainContent">
        @yield('content')
    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @include('flash_message')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Fallback loader if SweetAlert2 CDN fails
        (function ensureSwal() {
            if (typeof Swal !== 'undefined') return;
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/sweetalert2@11/dist/sweetalert2.min.js';
            script.onload = () => console.log('SweetAlert2 fallback loaded');
            script.onerror = () => console.error('SweetAlert2 failed to load from both CDNs');
            document.head.appendChild(script);
        })();
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script>
        // Global helper function to replace confirm() with SweetAlert2 for form submissions
        function confirmSubmit(event, message) {
            event.preventDefault();
            const form = event.target;
            Swal.fire({
                title: 'Are you sure?',
                html: message || 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, proceed',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
        
        // Ensure sidebar overlay is hidden on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const sidenav = document.getElementById('sidenav');
            
            // Make sure overlay is completely disabled on desktop
            if (sidebarOverlay) {
                sidebarOverlay.classList.remove('active');
                if (window.innerWidth > 768) {
                    // Force remove overlay on desktop
                    sidebarOverlay.style.display = 'none';
                    sidebarOverlay.style.pointerEvents = 'none';
                    sidebarOverlay.style.visibility = 'hidden';
                    sidebarOverlay.style.opacity = '0';
                    sidebarOverlay.style.width = '0';
                    sidebarOverlay.style.height = '0';
                }
            }
            if (sidenav && window.innerWidth > 768) {
                sidenav.classList.remove('active');
            }
            document.body.style.overflow = '';
        });

        // Burger menu toggle functionality for mobile
        const burgerBtn = document.getElementById('burgerBtn');
        const sidenav = document.getElementById('sidenav');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const closeSidebarBtn = document.getElementById('closeSidebar');
        const mainContent = document.getElementById('mainContent');

        // Open sidebar
        function openSidebar() {
            if (sidenav) {
                sidenav.classList.add('active');
            }
            if (sidebarOverlay) {
                sidebarOverlay.classList.add('active');
            }
            // Prevent body scroll when sidebar is open
            document.body.style.overflow = 'hidden';
        }

        // Close sidebar
        function closeSidebar() {
            if (sidenav) {
                sidenav.classList.remove('active');
            }
            if (sidebarOverlay) {
                sidebarOverlay.classList.remove('active');
            }
            // Restore body scroll
            document.body.style.overflow = '';
        }

        // Burger button click
        if (burgerBtn) {
            burgerBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                openSidebar();
            });
        }

        // Close button click
        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                closeSidebar();
            });
        }

        // Overlay click to close sidebar
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                closeSidebar();
            });
        }

        // Ensure correct state on resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                if (sidenav) sidenav.classList.remove('active');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('active');
                    // Force remove overlay on desktop
                    sidebarOverlay.style.display = 'none';
                    sidebarOverlay.style.pointerEvents = 'none';
                    sidebarOverlay.style.visibility = 'hidden';
                    sidebarOverlay.style.opacity = '0';
                    sidebarOverlay.style.width = '0';
                    sidebarOverlay.style.height = '0';
                }
                document.body.style.overflow = '';
            }
        });
    </script>
</body>

</html>