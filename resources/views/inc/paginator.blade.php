

<!-- pagination -->
@if ($paginator->hasPages()	)


 <div class="col-md-12">
    <div class="post-pagination">
        @if($paginator->onFirstPage())
        <a href="#" class="btn disabled pagination-back pull-left">back</a>

        @else
        <a href="{{$paginator->previousPageUrl()}}" class="pagination-back pull-left">back</a>

        @endif

        <ul class="pages">
            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li @if ($paginator->currentPage() == $page) class="active" @endif>
                            <a href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                @endif
            @endforeach
        </ul>



            @if($paginator->hasMorePages())
            <a href="{{$paginator->nextPageUrl()}}	" class="pagination-next pull-right">next</a>

            @else
            <a href="#" class="btn disabled pagination-next pull-right">next</a>

            @endif
    </div>
</div>
@endif

