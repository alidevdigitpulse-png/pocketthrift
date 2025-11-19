<style>
    .custompagination {
        list-style: none;
        display: inline-block;
        padding: 0;
        margin-top: 10px;
    }

    .custompagination li {
        display: inline;
        text-align: center;
    }

    .custompagination a {
        float: left;
        display: block;
        font-size: 14px;
        text-decoration: none;
        padding: 5px 12px;
        color: #fff;
        margin-left: -1px;
        border: 1px solid transparent;
        line-height: 1.5;
    }

    .custompagination a.active {
        cursor: default;
    }

    .custompagination a:active {
        outline: none;
    }

    .modal-5 {
        position: relative;
    }

    .modal-5:after {
        content: '';
        position: absolute;
        width: 100%;
        height: 35px;
        left: 0;
        bottom: 0;
        z-index: -1;
        background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4gPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PGxpbmVhckdyYWRpZW50IGlkPSJncmFkIiBncmFkaWVudFVuaXRzPSJvYmplY3RCb3VuZGluZ0JveCIgeDE9IjAuMCIgeTE9IjAuNSIgeDI9IjEuMCIgeTI9IjAuNSI+PHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzAwMDAwMCIgc3RvcC1vcGFjaXR5PSIwLjAiLz48c3RvcCBvZmZzZXQ9IjQwJSIgc3RvcC1jb2xvcj0iIzAwMDAwMCIgc3RvcC1vcGFjaXR5PSIwLjY1Ii8+PHN0b3Agb2Zmc2V0PSI1MCUiIHN0b3AtY29sb3I9IiMwMDAwMDAiIHN0b3Atb3BhY2l0eT0iMC42NSIvPjxzdG9wIG9mZnNldD0iNjAlIiBzdG9wLWNvbG9yPSIjMDAwMDAwIiBzdG9wLW9wYWNpdHk9IjAuNjUiLz48c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiMwMDAwMDAiIHN0b3Atb3BhY2l0eT0iMC4wIi8+PC9saW5lYXJHcmFkaWVudD48L2RlZnM+PHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNncmFkKSIgLz48L3N2Zz4g');
        background-size: 100%;
        background-image: -webkit-gradient(linear, 0% 50%, 100% 50%, color-stop(0%, rgba(0, 0, 0, 0)), color-stop(40%, rgba(0, 0, 0, 0.65)), color-stop(50%, rgba(0, 0, 0, 0.65)), color-stop(60%, rgba(0, 0, 0, 0.65)), color-stop(100%, rgba(0, 0, 0, 0)));
        background-image: -moz-linear-gradient(left, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.65) 40%, rgba(0, 0, 0, 0.65) 50%, rgba(0, 0, 0, 0.65) 60%, rgba(0, 0, 0, 0) 100%);
        background-image: -webkit-linear-gradient(left, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.65) 40%, rgba(0, 0, 0, 0.65) 50%, rgba(0, 0, 0, 0.65) 60%, rgba(0, 0, 0, 0) 100%);
        background-image: linear-gradient(to right, rgb(255 0 0 / 0%) 0%, rgb(253 104 62) 40%, rgb(253 104 62) 50%, rgb(253 104 62) 40%, rgb(255 0 0 / 0%) 100%);
    }

    .modal-5 a {
        color: #fff;
        padding: 13px 5px 5px;
        margin: 0 10px;
        position: relative;
    }

    .modal-5 a:hover {
        color: #fff;
    }

    .modal-5 a:hover:after {
        content: '';
        position: absolute;
        width: 24px;
        height: 24px;
        background: #1E7EE2;
        -moz-border-radius: 100%;
        -webkit-border-radius: 100%;
        border-radius: 100%;
        z-index: -1;
        left: -3px;
        bottom: 4px;
        margin: auto;
    }

    .modal-5 a.next,
    .modal-5 a.prev {
        color: #1E7EE2;
    }

    .modal-5 a.next:hover,
    .modal-5 a.prev:hover {
        color: #fff;
    }

    .modal-5 a.next:hover:after,
    .modal-5 a.prev:hover:after {
        display: none;
    }

    .modal-5 a.active {
        background: #ffffff;
        color: #fd683e;
    }

    .modal-5 a.active:before {
        content: '';
        position: absolute;
        top: -11px;
        left: -10px;
        width: 18px;
        border: 10px solid transparent;
        border-bottom: 7px solid #104477;
        z-index: -1;
    }

    .modal-5 a.active:hover:after {
        display: none;
    }

    nav.paginate {
        display: flex;
        justify-content: center;
        width: 100%;
    }
</style>
@if ($paginator->hasPages())
    @php
        $search = false;
        if (Request::get('search')) {
            $search = '&search=' . Request::get('search');
        }
    @endphp
    <nav class="paginate">
        <ul class="custompagination modal-5">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <a aria-hidden="true">&lsaquo;</a>
                </li>
            @else
                <li class="">
                    <a href="{{ $search == null ? $paginator->previousPageUrl() : $paginator->previousPageUrl() . $search }}"
                        rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class=" disabled" aria-disabled="true"><a>{{ $element }}</a></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li aria-current="page"><a class="active">{{ $page }}</a></li>
                        @else
                            <li class=""><a class=""
                                    href="{{ $search == null ? $url : $url . $search }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="">
                    <a href="{{ $search == null ? $paginator->nextPageUrl() : $paginator->nextPageUrl() . $search }}"
                        rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class=" disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>



@endif
