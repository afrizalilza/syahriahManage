<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Syahriah PP Nurul Asna')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="wrapper">
        <!-- Header -->
        @include('partials.header')

        <!-- Menu -->
        @include('partials.menu')

        <!-- Main Content -->
        <main class="py-4">
            <div class="container">
                @yield('container')
            </div>
        </main>

        <!-- Footer -->
        @include('partials.footer')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Script untuk konfirmasi SweetAlert --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Menangani form dengan class 'form-confirm'
            document.querySelectorAll('.form-confirm').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const message = this.dataset.message || 'Yakin ingin menghapus data ini?';

                    Swal.fire({
                        title: 'Konfirmasi',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Lanjutkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            // Menangani link/button dengan class 'btn-confirm'
            document.querySelectorAll('.btn-confirm').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const message = this.dataset.message || 'Apakah Anda yakin?';
                    const url = this.href;

                    Swal.fire({
                        title: 'Konfirmasi',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Lanjutkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });

            // SweetAlert2 Toast Notifications
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif
        });
    </script>

    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const headerMain = document.querySelector('.header-main');
            const headerTop = document.querySelector('.header-top');

            if (headerMain && headerTop) {
                const headerOffset = headerTop.offsetHeight;

                window.addEventListener('scroll', () => {
                    if (window.scrollY > headerOffset) {
                        headerMain.classList.add('sticky');
                        document.body.style.paddingTop = headerMain.offsetHeight + 'px';
                    } else {
                        headerMain.classList.remove('sticky');
                        document.body.style.paddingTop = '0';
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
