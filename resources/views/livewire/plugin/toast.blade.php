<?php

use Livewire\Volt\Component;

new class extends Component {
     protected $listeners = ['showToast'];

     public function showToast($status, $message)
     {
        $this->dispatch('show-toast', [
        'type' => $status,
        'message' => $message
        ]);
     }
}; ?>

<div>
    <!-- Toastr Notification -->
    @script
    <script>
        $wire.on('show-toast', (event) => {
            console.log(event);
            toastr[event[0].type](event[0].message, '', {
                "closeButton": true
                , "debug": false
                , "newestOnTop": false
                , "progressBar": false
                , "positionClass": "toast-top-right"
                , "preventDuplicates": false
                , "onclick": null
                , "showDuration": "300"
                , "hideDuration": "1000"
                , "timeOut": "5000"
                , "extendedTimeOut": "1000"
                , "showEasing": "swing"
                , "hideEasing": "linear"
                , "showMethod": "fadeIn"
                , "hideMethod": "fadeOut"
            , });
        });
    </script>
    @endscript
</div>