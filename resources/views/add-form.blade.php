<!doctype html>
<html>
<head>
    <title>Add</title>
</head>
<body>
<div style="text-align: center">

    <form method="POST" action="{{ route('store') }}">
        {{ csrf_field() }}

        @if ($success === true)
            <h4>Lot has been added successfully!</h4>
        @elseif ($success !== null && $success === false)
            <h4>Sorry, error has been occurred {{ $error }}</h4>
        @endif

        <label for="currencyId">currencyId</label><br>
        <input id="currencyId" type="currencyId" name="currencyId" value="{{ old('currencyId') }}"><br>

        <label for="sellerId">sellerId</label><br>
        <input id="sellerId" type="sellerId" name="sellerId" value="{{ old('sellerId') }}"><br>

        <label for="dateTimeOpen">dateTimeOpen</label><br>
        <input id="dateTimeOpen" type="dateTimeOpen" name="dateTimeOpen" value="{{ old('dateTimeOpen') }}"><br>

        <label for="dateTimeClose">dateTimeClose</label><br>
        <input id="dateTimeClose" type="dateTimeClose" name="dateTimeClose" value="{{ old('dateTimeClose') }}"><br>

        <label for="price">sellerId</label><br>
        <input id="price" type="price" name="price" value="{{ old('price') }}"><br>

        <button type="submit" class="btn btn-primary">Add</button>

    </form>

</div>

</body>
</html>