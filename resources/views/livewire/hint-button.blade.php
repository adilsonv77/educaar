<div>
    

    <div wire:ignore.self class="modal fade" id="hintModalHintButton" tabindex="-1" role="dialog" data-backdrop="static"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h5>Pista para a atividade seguinte:</h5>
                </div>
                <div class="container my-2 max" style="max-height: 60vh; overflow-y: auto;">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <p class="card-text">
                                {{ $hint }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('show-hint-modal', function () {
            if (typeof $ !== 'undefined' && $.fn && $.fn.modal) {
                $('#hintModalHintButton').modal('show');
            }
        });
    </script>
</div>
