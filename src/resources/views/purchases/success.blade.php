@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchases/stripe.css') }}">
@endsection

@section('content')
<div class="container">
    <h1 class="stripe-title">購入が完了しました！</h1>
</div>
<a class="page-link" href="{{ route('items.index') }}">商品一覧に戻る</a>
@endsection
