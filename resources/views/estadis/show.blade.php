@extends('layouts.equip')
@section('title', __("Detall d'Estadi"))

@section('content')
  <x-estadi
    :nom="$estadi->nom"
    :capacitat="$estadi->capacitat"
    :equips="$estadi->equips"
  />
@endsection
