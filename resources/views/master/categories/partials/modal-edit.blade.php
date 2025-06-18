<!-- Modal -->
@foreach ($categories as $category)
    
@endforeach
<div class="modal fade modal-animate" id="editModal_{{ $category->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('app.add') }} Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">{{ __('app.close') }}</button>
                <button type="button" class="btn btn-primary shadow-2">{{ __('app.save') }}</button>
            </div>
        </div>
    </div>
</div>
