@extends('layouts.master')

@section('title', 'University Portal')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>React + Laravel</title>
    @vite(['resources/js/app.jsx', 'resources/css/app.css'])
</head>
<body>
    <div id="app"></div>
</body>
</html>

@endsection
