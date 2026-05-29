@include('admin-panel.layouts.header')
<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
  <div id="kt_app_toolbar" class="app-toolbar py-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex align-items-start">
      <div class="d-flex flex-column flex-row-fluid">
       
      </div>
    </div>
  </div>
  <div class="app-container container-xxl">
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
      <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content">
          
          <div class="card card-flush">
            <div class="card-body">
              <div class="alert alert-danger"><h4 class="text-center">Anda tidak memiliki akses.</h4></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


@include('admin-panel.layouts.footer')