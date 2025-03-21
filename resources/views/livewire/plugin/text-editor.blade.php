<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Modelable;

new class extends Component {
    #[Modelable]
    public $content;
    public $name;
    public $disabled = false;
    public $placeholder = '';

    protected $listeners = ['resetEditor', 'toggleDisableEditor'];

    public function mount($name, $content = '', $placeholder = '', $disabled = false)
    {
        $this->name = $name;
        $this->content = $content;
        $this->placeholder = $placeholder;
        $this->disabled = $disabled;
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
            
            if (content) {
                quillEditor.root.innerHTML = content;
            }
            
            quillEditor.on('text-change', function () {
                $wire.set('content', quillEditor.root.innerHTML);
            });
            
            window.addEventListener('quill-reset', function(e) {
                quillEditor.root.innerHTML = e.detail[0].content;
                $wire.set('content', e.detail[0].content);
            });
            
            window.addEventListener('quill-toggle-disable', function(e) {
                quillEditor.enable(e.detail[0].disabled);
            });
        ">
        <div x-ref="quillContainer" style="min-height: 150px;"></div>
    </div>
    <input type="hidden" wire:model="content" name="{{ $name }}" id="{{ $name }}">
</div>