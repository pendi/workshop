@extends('shared/search')

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