/**
 * This script is responsible for taking an uploaded .pdf file by a customer
 * and displaying the 1st page of the file as a 'preview'.
 * 
 * Additionally, some file validation is handled by this script, but is repeated
 * server-side to avoid bugs or tinkering.
 * 
 * The script relies on Mozilla's FireFox pdf.js to read and render the pdf page.
 * 
 */
(function ($) {
    ('use strict');
    $(function () {
        var _upload = $('#pwp-file-upload');
        var _preview = $('#pwp-pdf-canvas');
        var _name = $('#pwp-upload-filename')
        var _canvas = _preview[0];

        var _pdf_doc;

        _upload.on('change', e => {
            var file = e.target.files[0];

            if (!validateFile) return;

            object_url = URL.createObjectURL(file);
            showPDF(object_url);
            _name.text(file.name);
            URL.revokeObjectURL(object_url);
        });

        function showPDF(pdf_url) {
            pdfjsLib.getDocument(pdf_url)
                .then(function (pdf_doc) {
                    _pdf_doc = pdf_doc;
                    showPage(1);
                })
                .catch(function (error) {
                    alert(error.message);
                });
        }

        function showPage(page_no) {
            _pdf_doc.getPage(page_no)
                .then(function (page) {
                    var viewport = page.getViewport(_canvas.width / page.getViewport(1).width);
                    _canvas.height = viewport.height;
                    var context = _canvas.getContext('2d');

                    var rendercontext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    page.render(rendercontext).then(function () {
                        _preview.css("display", "");
                    });
                });
        }

        function validateFile(file) {
            var mime_types = ['application/pdf'];
            if (mime_types.indexOf(file.type) == -1) {
                alert('Error: Incorrect file type, PDF required');
                return false;
            }

            if (file.size > 20000000) {
                alert('Error: File size exceeds 20MB');
                return false;
            }

            return true;
        }

    });
})(jQuery);