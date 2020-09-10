@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
    <!-- 
        Aqui vamos a llamar el componente 
        creamos una etiqueta div con el id="app"    
    -->
    <div id="app">
       <!-- Dentro de esta etiquta llamamos el componente -->
       <example-component></example-component>
    </div>

@endsection

@push('js')
    <!-- Importamos el archivo para ejecutar Vue.js -->
    <script src="{{ asset('js/app.js') }}"></script>
@endpush