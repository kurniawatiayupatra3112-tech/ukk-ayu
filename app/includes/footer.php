        </main>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Flash Message (notifikasi sukses/error) -->
    <?php if (isset($_SESSION['flash_message'])): ?>
    <script>
        Swal.fire({
            icon: '<?= $_SESSION['flash_type'] ?? 'success' ?>',
            title: 'Berhasil!',
            text: '<?= $_SESSION['flash_message'] ?>',
            timer: 2000
        });
    </script>
    <?php
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
    endif;
    ?>
</body>
</html>