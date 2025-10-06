<style>
    .rounded-0 {
        border-radius: 0 !important;
    }
</style>

@if (session('success'))
    <script>
        iziToast.success({
            title: 'Success',
            message: "{{ session('success') }}",
            position: 'topRight',
            class: 'rounded-0',
        });
    </script>
@endif

@if (session('error'))
    <script>
        iziToast.error({
            title: 'Error',
            message: "{{ session('error') }}",
            position: 'topRight',
            class: 'rounded-0',
        });
    </script>
@endif

@if (session('info'))
    <script>
        iziToast.info({
            title: 'Info',
            message: "{{ session('info') }}",
            position: 'topRight',
            class: 'rounded-0',
        });
    </script>
@endif

@if (session('warning'))
    <script>
        iziToast.warning({
            title: 'Warning',
            message: "{{ session('warning') }}",
            position: 'topRight',
            class: 'rounded-0',
        });
    </script>
@endif
