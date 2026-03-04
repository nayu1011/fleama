<div class="messages">
    @foreach($messages as $message)
        @php $isMine = $message->sender_id === $userId; @endphp

        <div class="message-row {{ $isMine ? 'message-row-mine' : 'message-row-theirs' }}">
            <div class="message">
                <div class="message-meta">
                    <div class="message-sender">
                        @if($isMine)
                            <span class="message-name">{{ $message->sender->name ?? 'ユーザー' }}</span>
                            <div class="avatar avatar-chat">
                                @if(!empty($message->sender->image_path))
                                    <img src="{{ Storage::url($message->sender->image_path) }}" alt="{{ $message->sender->name }}">
                                @endif
                            </div>
                        @else
                            <div class="avatar avatar-chat">
                                @if(!empty($message->sender->image_path))
                                    <img src="{{ Storage::url($message->sender->image_path) }}" alt="{{ $message->sender->name }}">
                                @endif
                            </div>
                            <span class="message-name">{{ $message->sender->name ?? 'ユーザー' }}</span>
                        @endif
                    </div>
                </div>

                @if($isMine)
                    @php $editFormId = 'edit-message-' . $message->id; @endphp
                    <form id="{{ $editFormId }}" method="POST" action="{{ route('trades.updateMessage', $message) }}" class="message-edit-form" data-edit-form>
                        @csrf
                        @method('PATCH')

                        <div class="message-bubble">
                            @if(!empty($message->message))
                                <div class="message-text">{{ $message->message }}</div>
                            @endif
                            <input class="message-edit-input" type="text" name="message" value="{{ old('message', $message->message) }}" required data-message-input>

                            @if(!empty($message->image_path))
                                <div class="message-image">
                                    <img src="{{ Storage::url($message->image_path) }}" alt="添付画像">
                                </div>
                            @endif
                        </div>
                    </form>

                    <div class="message-actions">
                        <button type="submit" form="{{ $editFormId }}" class="message-action-link" data-edit-toggle data-edit-form-id="{{ $editFormId }}">編集</button>

                    <form method="POST" action="{{ route('trades.destroyMessage', $message) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">削除</button>
                    </form>
                    </div>
                @endif

                @unless($isMine)
                    <div class="message-bubble">
                        @if(!empty($message->message))
                            <div class="message-text">{{ $message->message }}</div>
                        @endif

                        @if(!empty($message->image_path))
                            <div class="message-image">
                                <img src="{{ Storage::url($message->image_path) }}" alt="添付画像">
                            </div>
                        @endif
                    </div>
                @endunless
            </div>
        </div>
    @endforeach
</div>

<script>
function scrollTradeMessagesToBottom() {
    const container = document.querySelector('.trade-messages');
    if (!container) return;
    container.scrollTop = container.scrollHeight;
}

document.addEventListener('DOMContentLoaded', function () {
    scrollTradeMessagesToBottom();

    if (sessionStorage.getItem('trade_scroll_to_bottom') === '1') {
        scrollTradeMessagesToBottom();
        sessionStorage.removeItem('trade_scroll_to_bottom');
    }
});

document.addEventListener('click', function (e) {
    const toggle = e.target.closest('[data-edit-toggle]');
    if (!toggle) return;

    const formId = toggle.dataset.editFormId;
    const form = formId ? document.getElementById(formId) : null;
    if (!form || form.classList.contains('is-editing')) return;

    e.preventDefault();
    form.classList.add('is-editing');
    toggle.textContent = '完了';

    const input = form.querySelector('[data-message-input]');
    if (input) {
        input.focus();
        input.setSelectionRange(input.value.length, input.value.length);
    }
});
</script>
