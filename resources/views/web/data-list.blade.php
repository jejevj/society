@include('layouts.header-v2')

				<div class="app-wrapper flex-column flex-row-fluid mt-150" id="kt_app_wrapper">
					<div class="container-xxl">
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid">
								<div id="kt_app_content" class="app-content">
									<div class="card card-flush">
                                        
                                        <div class="mt-6">
                                            <div class="m-6">                                                
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h2 class="list-title mb-2">
                                                            {{ $nama_menu_list }}
                                                        </h2>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <p class="list-desc mb-4" style="max-width: 600px;">
                                                            {{ $set->deskripsi_topik }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <div class="row">
                                                            <div class="col-md-8 mb-2">
                                                                <input type="text" id="searchInput" class="form-control input-modern" value="{{ request('search') }}" placeholder="Cari dataset, infografis...">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button id="btnSearch" class="btn btn-search-list"><i class="fa fa-search text-white"></i> Cari</button>
                                                                <a href="{{ route('list') }}" class="btn btn-secondary"><i class="fa fa-rotate m-auto p-auto"></i>Hapus Filter</a>
                                                            </div>                
                                                        </div>
                                                    </div>
                                                        
                                                    <div class="col-md-3 mt-8">
                                                        @include('web.data-list-filter')
                                                    </div>
                                                    <div class="col-md-9 mt-8">
                                                        <div id="data_container" class="data-wrapper">
                                                            <div class="text-center py-5">Memuat...</div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
								</div>
							</div>
							
<script>
    $(document).ready(function () {
    loadData();

    $('#btnSearch').click(function(e){
        e.preventDefault();
        loadData();
    });

    $('#searchInput').on('keypress', function(e){
        if(e.which == 13){
            loadData();
        }
    });
});

function getFilterParams() {
    let params = {};

    params.search = $('#searchInput').val();

    params.tipe = getChecked('filterTipe');
    params.org = getChecked('filterOrg');
    params.topik = getChecked('filterTopik');
    params.status = getChecked('filterSifat');

    return params;
}

function getChecked(prefix){
    let arr = [];
    $(`input[id^=${prefix}]:checked`).each(function(){
        if($(this).val() !== 'semua'){
            arr.push($(this).val());
        }
    });
    return arr;
}

function loadData(url = "{{ route('listData') }}") {
    $('#data_container').html('<div class="text-center py-5">Memuat...</div>');

    $.get(url, getFilterParams(), function(res){
        $('#data_container').html(res.html);
    });
}

$(document).on('click', '#data_container .pagination a', function(e){
    e.preventDefault();
    let url = $(this).attr('href');
    loadData(url);
});
</script>
@include('layouts.footer-v2')
