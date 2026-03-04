@error('message')
<p class="form__error">{{ $message }}</p>
@enderror
@error('image')
<p class="form__error">{{ $message }}</p>
@enderror

<form class="trade-form"
        id="trade-message-form"
        method="POST"
        action="{{ route('trades.storeMessage', $trade) }}"
        enctype="multipart/form-data">
    @csrf
    <input
        class="trade-form-input"
        type="text"
        name="message"
        value="{{ old('message') }}"
        placeholder="取引メッセージを記入してください"
    >

    <label class="trade-form-image-btn">
        画像を追加
        <input type="file" name="image" accept="image/*" hidden>
    </label>

    <button type="submit" class="trade-form-send" aria-label="送信">
        <div class="message-send-img">
            <img class="send-icon" src="{{ asset('images/send-icon.png') }}" alt="送信アイコン">
        </div>
    </button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('trade-message-form');
    if (!form) return;
    const input = form.querySelector('input[name="message"]');
    if (!input) return;

    const draftKey = 'trade_message_draft_{{ $trade->id }}';
    const ttlMs = 6 * 60 * 60 * 1000; // 6時間

    form.addEventListener('submit', function () {
        sessionStorage.setItem('trade_scroll_to_bottom', '1');
        localStorage.removeItem(draftKey);
    });

    try {
        const raw = localStorage.getItem(draftKey);
        if (raw) {
            const parsed = JSON.parse(raw);
            const isExpired = !parsed.saved_at || (Date.now() - parsed.saved_at > ttlMs);
            if (isExpired) {
                localStorage.removeItem(draftKey);
            } else if (!input.value && typeof parsed.message === 'string') {
                input.value = parsed.message;
            }
        }
    } catch (e) {
        localStorage.removeItem(draftKey);
    }

    input.addEventListener('input', function () {
        try {
            localStorage.setItem(draftKey, JSON.stringify({
                message: input.value,
                saved_at: Date.now(),
            }));
        } catch (e) {
            // localStorage容量超過などは無視
        }
    });
});
</script>
