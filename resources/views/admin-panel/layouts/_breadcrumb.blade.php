{{-- 
    Usage: @include('admin-panel.layouts._breadcrumb', ['items' => [
        ['label' => 'Home', 'url' => route('dashboard')],
        ['label' => 'Events', 'url' => route('event')],
        ['label' => 'Add Event', 'url' => null],  // null = tidak bisa diklik (halaman aktif)
    ]])
--}}
<ul class="breadcrumb breadcrumb-separatorless fw-semibold">
    {{-- Home icon selalu pertama --}}
    <li class="breadcrumb-item fw-bold lh-1">
        <a href="{{ route('dashboard') }}" class="text-hover-primary">
            <i class="ki-outline ki-home fs-3"></i>
        </a>
    </li>

    @foreach($items as $item)
        <li class="breadcrumb-item">
            <i class="ki-outline ki-right fs-4 mx-n1"></i>
        </li>
        <li class="breadcrumb-item fw-bold lh-1">
            @if(!empty($item['url']))
                <a href="{{ $item['url'] }}" class="text-hover-primary">{{ $item['label'] }}</a>
            @else
                <span class="text-muted">{{ $item['label'] }}</span>
            @endif
        </li>
    @endforeach
</ul>
