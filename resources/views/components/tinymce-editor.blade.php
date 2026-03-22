{{-- Self-hosted TinyMCE (from eventverse) - no API key.
    Usage: <x-tinymce-editor selector="#content" :height="420" form-id="page-form" />
    For multiple: selector="#header_content, #footer_content"
--}}
<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof tinymce !== 'undefined') {
        var selectors = '{{ $selector ?? "#content" }}';
        tinymce.init({
            selector: selectors,
            height: {{ $height ?? 300 }},
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor',
                'searchreplace', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media', 'table', 'help'
            ],
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help | image table code',
            statusbar: true,
            resize: 'both',
            promotion: false,
            branding: false,
            base_url: '{{ asset("tinymce") }}',
            suffix: '.min',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
            file_picker_callback: function(cb, value, meta) {
                if (meta.filetype === 'image') {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.onchange = function() {
                        var file = this.files[0];
                        if (!file) return;
                        var formData = new FormData();
                        formData.append('file', file);
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', '{{ route("admin.cms.upload-image") }}');
                        xhr.onload = function() {
                            if (xhr.status >= 200 && xhr.status < 300) {
                                var data = JSON.parse(xhr.responseText);
                                cb(data.url, { title: file.name });
                            } else {
                                console.error('Upload failed', xhr.responseText);
                                var reader = new FileReader();
                                reader.onload = function() { cb(reader.result, { title: file.name }); };
                                reader.readAsDataURL(file);
                            }
                        };
                        xhr.onerror = function() {
                            var reader = new FileReader();
                            reader.onload = function() { cb(reader.result, { title: file.name }); };
                            reader.readAsDataURL(file);
                        };
                        xhr.send(formData);
                    };
                    input.click();
                }
            }
        });
        @if(!empty($formId))
        var form = document.getElementById('{{ $formId }}');
        if (form) {
            form.addEventListener('submit', function() {
                selectors.split(',').forEach(function(sel) {
                    var id = sel.trim().replace('#', '');
                    var ed = tinymce.get(id);
                    if (ed) ed.save();
                });
            });
        }
        @endif
    }
});
</script>
