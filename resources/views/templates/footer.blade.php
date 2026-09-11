        </main>
        <footer class="mt-auto bg-dark text-light">
            <div class="container py-3">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-2 text-center">
                    <span>&copy; <span id="year">{{ date('Y') }}</span> <b>UPDS</b>, Inc.</span>
                </div>
            </div>
        </footer>

        <!-- Bootstrap 5.3.8 JS: Popper (para dropdowns/popovers) + Bootstrap core.
             jQuery y main.js solo se cargan donde hace falta DataTables (index/papelera). -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
            integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
            integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous">
        </script>

        <!-- Librerías específicas de la vista (DataTables, SweetAlert2, main.js) -->
        @yield('scripts_extra')

        <!-- Tema claro/oscuro: vanilla JS, sin jQuery, necesario en todas las páginas -->
        <script src="{{ asset('js/tema.js') }}?v={{ filemtime(public_path('js/tema.js')) }}"></script>

        @yield('scripts')

        </body>

        </html>
