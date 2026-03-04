<div class="trade-item">
    <div class="trade-item-image">
        @if(!empty($item->image_path))
            <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}">
        @else
            <div class="trade-item-image-placeholder">商品画像</div>
        @endif
    </div>

    <div class="trade-item-info">
        <h1 class="trade-item-name">{{ $item->name }}</h1>
        <div class="trade-item-price">{{ number_format($item->price) }}円</div>
    </div>
</div>
