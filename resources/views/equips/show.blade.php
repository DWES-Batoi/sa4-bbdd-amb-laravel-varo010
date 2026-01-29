@extends('layouts.equip')
@section('title', __("Detall d'Equip"))

@section('content')
    <x-equip :equip="$equip" />
@endsection
