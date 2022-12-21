@extends('layouts.template')

@section('content')
    
@endsection

@section('custom_script')
  <script>
   $(document).ready(function() {
        var table = $('#table').DataTable({
            'autoWidth': false,
            'dom': 'Rlfrtip',
            'colReorder': {
                'allowReorder': false
            }
        });
    });
  </script>
@endsection 