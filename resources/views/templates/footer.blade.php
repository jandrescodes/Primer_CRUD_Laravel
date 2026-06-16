        </main>
        <footer class="mt-auto bg-dark text-light">
            <div class="container py-3">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-2 text-center">
                    <span>&copy; <span id="añoActual"></span> <b>UPDS</b>, Inc.</span>
                    <a href="#" class="text-decoration-none text-light">Privacidad</a>
                </div>
            </div>
        </footer>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
            integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
            integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
        </script>

        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

        <!-- DataTables Buttons -->
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"
            integrity="sha384-POmQLDS+gc6Qd3ZalIVuutsaZQkTDU8foo3J6egQy4YmrNhxcGuIlKXsas5XaZNG" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"
            integrity="sha384-R/1aieuV27Trs+cczprs/2SFjCwdqKQo/4qYM0SBHv/wla+LSTHWds8FCw6s51Bb" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"
            integrity="sha384-+mbV2IY1Zk/X1p/nWllGySJSUN8uMs+gUAN10Or95UBH0fpj6GfKgPmgC5EXieXG" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"
            integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"
            integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"
            integrity="sha384-HTrhO6m5QbXzKQm/28HT6tYIdf/GeCK8xucgDpUbyrlqDwVh5AN5il4gcRxyIF51" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"
            integrity="sha384-m1bkDRTx51kIl1OC2S24Tnj0vEwit7u2Be60VaV392LbUhm9qrm5GBlraC3S2U/l" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"
            integrity="sha384-h/SRPFzc2+BE+XfOqlAqiHb43fnY8jzXhQ0fI1JBfgrjbxUokMr9To2eLbSWEt1g" crossorigin="anonymous"></script>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Custom JS -->
        <script src="{{ asset('js/main.js') }}"></script>

        @yield('scripts')

        </body>

        </html>
