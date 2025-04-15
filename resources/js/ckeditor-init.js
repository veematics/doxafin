import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.ckeditor').forEach(element => {
        ClassicEditor
            .create(element, {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote'],
                removePlugins: ['CKFinderUploadAdapter', 'CKFinder', 'EasyImage', 'Image', 'ImageCaption', 'ImageStyle', 'ImageToolbar', 'ImageUpload', 'MediaEmbed'],
            })
            .then(editor => {
                // Hide "powered by CKEditor" label
                const poweredBy = editor.ui.view.element.querySelector('.ck-powered-by');
                if (poweredBy) {
                    poweredBy.remove();
                }
            })
            .catch(error => {
                console.error(error);
            });
    });
});