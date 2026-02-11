<style>
    .sortable-drag {
        opacity: 0;
        
    }
</style>

<div>
    <table class="table">
        <thead>
            <tr>
                <th style="width: 50%">Atividade</th>
                <th style="width: 50%">Marcador</th>
            </tr>
        </thead>
        <tbody id="activities">
            @foreach ($activities as $activity)
                <tr data-id="{{ $activity->id }}">
                    <td>
                        <h5>{{ $activity->name }}</h5>
                    </td>
                    <td>
                        <img src="/marcadores/{{ $activity->marcador }}" alt="" width="200" height="200">
                        <li style="display: none" class="imagens_compilar">/marcadores/{{$activity->marcador}}</li>
                    </td>
                    <td wire:sortable.handle style="cursor: grab;">
                        <i class="bi bi-arrows-move h2"></i>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        document.addEventListener('livewire:load', () => {
            const el = document.getElementById('activities');

            function saveActivityOrder() {
                let orderedIds = [...el.children].map(li => li.dataset.id);
                Livewire.emit('updateOrder', orderedIds);
            }

            saveActivityOrder()

            new Sortable(el, {
                animation: 150,
                dragClass: "sortable-drag",
                onEnd: (evt) => {
                    saveActivityOrder()
                }
            });
        });
    </script>

</div>