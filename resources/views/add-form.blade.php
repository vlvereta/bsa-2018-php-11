<!doctype html>
<html>
<head>
    <title>Add</title>
</head>
<body>
<div style="text-align: center">

    <form method="POST" action="{{ route('store') }}">
        {{ csrf_field() }}

        @if (isset($error))
            @if ($error === false)
                <h4>Lot has been added successfully!</h4>
            @else
                <h4>Sorry, error has been occurred: {{ $message }}</h4>
            @endif
        @endif

        <label for="currency_id">currencyId</label><br>
        <input id="currency_id" type="currency_id" name="currency_id" value="{{ old('currency_id') }}"><br>

        <label for="seller_id">sellerId</label><br>
        <input id="seller_id" type="seller_id" name="seller_id" value="{{ old('seller_id') }}"><br>

        <label for="date_time_open">dateTimeOpen</label><br>
        <input id="date_time_open" type="date_time_open" name="date_time_open" value="{{ old('date_time_open') }}"><br>

        <label for="date_time_close">dateTimeClose</label><br>
        <input id="date_time_close" type="date_time_close" name="date_time_close" value="{{ old('date_time_close') }}"><br>

        <label for="price">price</label><br>
        <input id="price" type="price" name="price" value="{{ old('price') }}"><br>

        <button type="submit" class="btn btn-primary">Add</button>

    </form>

</div>

</body>
</html>