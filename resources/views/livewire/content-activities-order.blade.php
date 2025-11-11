<div>
    <h3>Ordenar Atividades</h3>
   <ul id="activities">
        @foreach ($activities as $activity)
            <li data-id="{{ $activity->id }}">
                <h5 wire:sortable.handle>{{ $activity->name }}</h5>
            </li>
        @endforeach
    </ul>


    <script>
    document.addEventListener('livewire:load', () => {
        const el = document.getElementById('activities');
        new Sortable(el, {
            animation: 150,
            onEnd: (evt) => {
                let orderedIds = [...el.children].map(li => li.dataset.id);
                Livewire.emit('updateOrder', orderedIds);
            }
        });
    });
</script>
    

    
</div>