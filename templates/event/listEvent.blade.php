@extends('shared/search')

<?php
$schema = array();
foreach (f('controller')->schema() as $key => $field) {
    if ($field['list-column']) {
        $schema[$key] = $field;
    }
}
?>

@section('back')
    <ul class="flat left">
        <li><a href="{{ f('controller.url') }}"><i class="xn xn-left-open"></i>{{ l('Back') }}</a></li>
        
        @if(f('auth.allowed', f('controller.uri', '/null/create')))
            <li><a href="{{ f('controller.url', '/null/create?cat='.$idCat) }}"><i class="xn xn-plus"></i>{{ l('New') }}</a></li>
        @endif
        <li class="search">
            <nav id="search">
                <div class="search-area">
                    <span class="icn xn xn-search"></span>
                    <form action="#" class="input-search">
                        <input type="text" placeholder="Search Here..." id="searchbar">
                    </form>
                </div>
            </nav>
        </li>
    </ul>
@stop

@section('content')
    @if(!$entries->count(true))
        <div class="contentcenter">
            <div class="middle">
                <p class="empty"><i class="xn xn-docs"></i><br />Data still empty.<br />Click <a href="{{ f('controller.url', '/null/create') }}"><i class="xn xn-plus"></i> New</a> to add new data.</p>
            </div>
        </div>
    @else
        <form method="get" id="search-form" class="wrapper full">
            <div class="table-container">
                <table class="table nowrap hover">
                    <thead>
                        @section('table.head')
                        <tr>
                            @if (count($schema))
                                @foreach ($schema as $key => $field)
                                    <th><a href="#">{{ $field['label'] }} </a></th>
                                @endforeach
                                <th>&nbsp;</th>
                            @else
                                <th>Data</th>
                            @endif
                        </tr>
                        @show
                        @section('table.filter')
                        @show
                    </thead>
                    <tbody>
                        @section('table.body')
                            @foreach ($entries as $entry)
                                <?php $i = 0 ?>
                                <tr>
                                    @if (count($schema))
                                        @foreach ($schema as $name => $field)
                                            <td>
                                                @if($i++ === 0)
                                                    <a href="{{ f('controller.url', '/'.$entry['$id'].'/update') }}">{{ substr($field->format('plain', $entry[$name], $entry), 0, 48) }}</a>
                                                @else
                                                    <?php try { $string = strip_tags($entry->format($name)); echo substr($string, 0, 48); } catch(\Exception $e) { echo 'xxx'; var_dump($e); } ?>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td>
                                            <a href="{{ f('controller.url', '/'.$entry['$id'].'/attendance') }}" class="xn-check" style="color: green;"></a>
                                            <a href="{{ f('controller.url', '/'.$entry['$id'].'/update') }}" class="xn-pencil"></a>
                                            <a href="{{ f('controller.url', '/'.$entry['$id'].'/delete') }}" class="xn-trash" style="color: red;"></a>
                                        </td>
                                    @else
                                        <td><a href="{{ f('controller.url', '/'.$entry['$id']) }}">{{ $entry->format() }}</a></td>
                                    @endif
                                </tr>
                            @endforeach
                        @show
                    </tbody>
                </table>
            </div>
            <input type="submit" style="display:none" />
        </form>
        <script type="text/javascript">
            $('#search-form').on('submit', function(evt) {
                evt.preventDefault();
                var qs = [];
                $(this).serializeArray().forEach(function(a) {
                    if (a.value) {
                        qs.push(a.name + '!like=' + a.value);
                    }
                });
                location.href = (qs.length) ? location.pathname + '?' + qs.join('&') : location.pathname;
            });
        </script>
    @endif
@stop