@if ($products->hasPages())
    <div class="pagination-wrapper">
        {{ $products->links() }}
    </div>
@endif
