<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Modelable;

new class extends Component {
    #[Modelable]
    public $content;

    public $label = '';
    public $disabled = false;
    public $placeholder = '';
    // height editor xs = 50px, sm = 100px, md = 150px, lg = 200px, xl = 250px
    public $size = 'md';
    public $sizes = ['xs' => '50px', 'sm' => '100px', 'md' => '150px', 'lg' => '200px', 'xl' => '250px'];

    protected $listeners = ['resetEditor', 'toggleDisableEditor'];

    public function mount( $label = '', $placeholder = '', $disabled = false, $size = 'md')
    {
        $this->placeholder = $placeholder;
        $this->disabled = $disabled;
        $this->label = $label;
        $this->size = $size;
    }

    public function resetEditor($content)
    {
        $this->content = $content;
        $this->dispatch('quill-reset', ['content' => $content]);
    }

    public function toggleDisableEditor($isEnabled)
    {
        $this->disabled = $isEnabled;
        $this->dispatch('quill-toggle-disable', ['disabled' => $isEnabled]);
    }
}; ?>

<div wire:ignore>
    <div x-data="{ 
                quillEditor: null,
                content: '{{ $content }}',
                disabled: {{ $disabled ? 'true' : 'false' }}
            }" x-init="
                quillEditor = new Quill($refs.quillContainer, {
                    theme: 'snow',
                    placeholder: '{{ $placeholder }}',
                    readOnly: disabled,
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],
                            ['blockquote', 'code-block'],
                            [{ 'header': 1 }, { 'header': 2 }],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                            [{ 'indent': '-1'}, { 'indent': '+1' }],
                            [{ 'size': ['small', false, 'large', 'huge'] }],
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'align': [] }],
                            ['link', 'image'],
                            ['clean']
                        ]
                    }
                });

                quillEditor.on('text-change', function () {
                    $wire.set('content', quillEditor.root.innerHTML);
                });
                
                $wire.on('quill-reset', (e) => {
                    quillEditor.root.innerHTML = e[0].content;
                    $wire.set('content', e[0].content);
                });
                
                $wire.on('quill-toggle-disable', (e) => {
                    quillEditor.enable(e[0].disabled);
                });
            ">
        <div class="text-sm font-medium select-none text-zinc-800 dark:text-white mb-1">{{ $label }}</div>
        <div x-ref="quillContainer" style="min-height: {{ $sizes[$size] }}">{!! $content !!}</div>
    </div>
    <input type="hidden" wire:model.live="content">
</div>