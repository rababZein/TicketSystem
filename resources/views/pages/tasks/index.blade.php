@extends('layouts.master')

@section('title', 'Tasks Management')

@section('content')
<tasks-component></tasks-component>
<!-- set progressbar -->
<vue-progress-bar></vue-progress-bar>
@endsection