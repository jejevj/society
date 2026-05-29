<!--end::Modal - Invite Friend-->
<script>
tinymce.init({
  selector: '.tiny',
  height: 300,
  menubar: false,
  plugins: 'lists link code',
  toolbar: 'undo redo | bold italic | bullist numlist | link | code',
  branding: false
});

$(document).on("input", ".only-number", function() {
    this.value = this.value.replace(/[^0-9]/g, "");
});

$(document).ready(function() {
   $('[data-control="select2"]').select2({
        placeholder: "-Pilih-",
        allowClear: true,
        width: '100%'
   });
});

</script>

		<script>var hostUrl = "assets/";</script>
		<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="{{ asset('assets/js/custom/apps/ecommerce/catalog/products.js') }}"></script>
		<script src="{{ asset('assets/js/widgets.bundle.js') }}"></script>
		<script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
		<script src="{{ asset('assets/js/custom/apps/chat/chat.js') }}"></script>
		<script src="{{ asset('assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
		<script src="{{ asset('assets/js/custom/utilities/modals/users-search.js') }}"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>