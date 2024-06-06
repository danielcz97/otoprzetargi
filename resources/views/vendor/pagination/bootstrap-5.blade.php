@if ($paginator->hasPages())
    <nav class="d-flex justify-items-center justify-content-between">
        <div class="d-flex justify-content-between flex-fill d-sm-none">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">Poprzednia</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link"
                            href="{{ $paginator->previousPageUrl() }}{{ request()->getQueryString() ? '&' . request()->getQueryString() : '' }}"
                            rel="prev">Poprzednia</a>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link"
                            href="{{ $paginator->nextPageUrl() }}{{ request()->getQueryString() ? '&' . request()->getQueryString() : '' }}"
                            rel="next">Następna</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">Następna</span>
                    </li>
                @endif
            </ul>
        </div>

        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
            <div>
                <form method="GET" class="d-flex justify-content-center mt-3">
                    <div class="input-group mb-3" style="width: 150px;">
                        <input type="number" name="page" class="form-control" min="1"
                            max="{{ $paginator->lastPage() }}" value="{{ $paginator->currentPage() }}">
                        <button class="btn btn-primary" type="submit">Idź</button>
                    </div>
                </form>
            </div>

            <div>
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="Poprzednia">
                            <span class="page-link" aria-hidden="true">&lsaquo;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link"
                                href="{{ $paginator->previousPageUrl() }}{{ request()->getQueryString() ? '&' . request()->getQueryString() : '' }}"
                                rel="prev" aria-label="Poprzednia">&lsaquo;</a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="page-item disabled" aria-disabled="true"><span
                                    class="page-link">{{ $element }}</span></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active" aria-current="page"><span
                                            class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link"
                                            href="{{ $url }}{{ request()->getQueryString() ? '&' . request()->getQueryString() : '' }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link"
                                href="{{ $paginator->nextPageUrl() }}{{ request()->getQueryString() ? '&' . request()->getQueryString() : '' }}"
                                rel="next" aria-label="Następna">&rsaquo;</a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true" aria-label="Następna">
                            <span class="page-link" aria-hidden="true">&rsaquo;</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
