@foreach($topik as $t)
<div class="col-4 hover-shadow-ds">
    <a href="{{ route('list', ['topik' => $t->id_topik]) }}">
        <div class="rounded-2 px-6 py-5" style="background: linear-gradient(180deg, rgb(240,240,240), rgb(255,255,255));">
            <div class="d-flex flex-column align-items-center justify-content-center text-center">

                <div class="symbol mb-4">
                    <img src="{{ url('storage/'.$t->gambar_topik) }}" width="70" loading="lazy">
                </div>

                <div>
                    <span class="text-gray-700 fw-bolder d-block fs-2 lh-1 ls-n1 mb-1">
                        {{ $t->nama_topik }}
                    </span>
                </div>

            </div>
        </div>
    </a>
</div>
@endforeach