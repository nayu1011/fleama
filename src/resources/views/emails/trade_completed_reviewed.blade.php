<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>取引完了通知</title>
</head>
<body>
    <p>{{ $trade->seller->name }}様</p>

    <p>
        商品：「{{ $trade->item->name }}」の取引が完了し、<br>
        購入者の「{{ $trade->buyer->name }}」さんから評価されました。
    </p>

    <p>評価：{{ str_repeat('★', $rating) }}</p>

    <p>
        購入者を評価するにはマイページの取引中の商品をご確認ください。<br>
        <a href="{{ $tradeUrl }}">{{ $tradeUrl }}</a>
    </p>

    <p>coachtechフリマ</p>
</body>
</html>
