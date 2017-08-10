<ul class="pagination no-margin pull-right">
    @if($dataProvider->total() > 0)
        <li>
            <a href="javascript:void(0)" class="no-border"><b>{{ $dataProvider->firstItem() . '-' . $dataProvider->lastItem() . '/' . $dataProvider->total() }}</b></a>
        </li>
    @endif
    @if($dataProvider->lastPage() > 1)
        @if($dataProvider->currentPage() > 1)
            <li>
                <a href="{{ $dataProvider->previousPageUrl() }}"><i class="fa fa-chevron-left fa-fw"></i></a>
            </li>
            <li><a href="{{ $dataProvider->url(1) }}">1</a></li>
        @endif

        @for($i = 2;$i >= 1;$i --)
            @if($dataProvider->currentPage() - $i > 1)
                @if($dataProvider->currentPage() - $i > 2 && $i == 2)
                    <li>...</li>
                    <li><a href="{{ $dataProvider->url($dataProvider->currentPage() - $i) }}">{{ $dataProvider->currentPage() - $i }}</a></li>
                @else
                    <li><a href="{{ $dataProvider->url($dataProvider->currentPage() - $i) }}">{{ $dataProvider->currentPage() - $i }}</a></li>
                @endif
            @endif
        @endfor

        <li class="active"><a href="javascript:void(0)">{{ $dataProvider->currentPage() }}</a></li>

        @for($i = 1;$i <= 2;$i ++)
            @if($dataProvider->currentPage() + $i < $dataProvider->lastPage())
                @if($dataProvider->currentPage() + $i < $dataProvider->lastPage() - 1 && $i == 2)
                    <li><a href="{{ $dataProvider->url($dataProvider->currentPage() + $i) }}">{{ $dataProvider->currentPage() + $i }}</a></li>
                    <li>...</li>
                @else
                    <li><a href="{{ $dataProvider->url($dataProvider->currentPage() + $i) }}">{{ $dataProvider->currentPage() + $i }}</a></li>
                @endif
            @endif
        @endfor

        @if($dataProvider->currentPage() < $dataProvider->lastPage())
            <li><a href="{{ $dataProvider->url($dataProvider->lastPage()) }}">{{ $dataProvider->lastPage() }}</a></li>
            <li><a href="{{ $dataProvider->nextPageUrl() }}"><i class="fa fa-chevron-right fa-fw"></i></a></li>
        @endif
    @endif
</ul>