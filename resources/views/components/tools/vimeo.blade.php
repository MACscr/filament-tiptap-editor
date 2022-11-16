@props([
    'statePath' => null,
])

<x-filament-tiptap-editor::button
        action="openModal()"
        active="'vimeo'"
        label="{{ __('filament-tiptap-editor::editor.video.vimeo') }}"
        icon="vimeo"
        x-on:insert-video.window="insertVideo($event.detail.video)"
        x-data="{
            openModal() {
                $wire.dispatchFormEvent('tiptap::setVimeoContent', '{{ $statePath }}');
            },
            insertVideo(video) {
                if (video.url === null) {
                    return;
                }

                console.log(video);

                this.editor()
                    .chain()
                    .focus()
                    .setVimeoVideo({
                        src: video.url,
                        width: video.width ?? 640,
                        height: video.height ?? 480,
                        autoplay: video.autoplay ? 1 : 0,
                        loop: video.loop ? 1 : 0,
                        title: video.show_title ? 1 : 0,
                        byline: video.byline ? 1 : 0,
                        portrait: video.portrait ? 1 : 0,
                        responsive: video.responsive ?? true,
                    })
                    .run();
            }
        }"
/>