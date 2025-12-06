@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchases/address.css') }}">
@endsection

@section('content')
    <div class="shipping-edit-page">
        <h2 class="page-heading">住所の変更</h2>
        <form class="shipping-edit-form" action="{{ route('purchases.updateAddress', $item->id) }}" method="POST">
            @csrf
            <div class="form__group">
                <label class="form__label" for="postal_code">郵便番号</label>
                <input class="form__input" type="text" name="postal_code" id="postal_code"
                    value="{{ old('postal_code', session('address.postal_code')) }}">
                @error('postal_code')
                    <p class="form__error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form__group">
                <label class="form__label" for="address">住所</label>
                <input class="form__input" type="text" name="address" id="address"
                    value="{{ old('address', session('address.address')) }}">
                @error('address')
                    <p class="form__error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form__group">
                <label class="form__label" for="building">建物名</label>
                <input class="form__input" type="text" name="building" id="building"
                    value="{{ old('building', session('address.building')) }}">
                @error('building')
                    <p class="form__error">{{ $message }}</p>
                @enderror
            </div>
            <input class="form__btn" type="submit" value="更新する">
        </form>
    </div>
@endsection
