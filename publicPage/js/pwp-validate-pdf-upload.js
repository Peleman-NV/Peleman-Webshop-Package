(function ($) {
    ('use strict');
    $(function () {
        console.log('foo!');

        const _clear = $('#pwp-file-clear');
        const _upload = $('#pwp-file-upload');
        const _preview = $('#pwp-pdf-canvas');
        const _name = $('#pwp-upload-filename');

        const _pages = $('#pwp-pdf-pages');
        const _pagePricing = $('#pwp-pdf-price');
        const _canvas = _preview[0];

        var _pdf_doc;
        var _object_url;

        _upload.on('change', e => {
            var file = e.target.files[0];
            var mime_types = ['application/pdf'];
            if (mime_types.indexOf(file.type) == -1) {
                alert('Error: Incorrect file type');
                return;
            }

            if (file.size > 20000000) {
                alert('Error: Exceeds size 20MB');
                return;
            }

            _object_url = URL.createObjectURL(file);
            showPDF(_object_url);
            _name.text(file.name);
        });

        function showPDF(pdf_url) {
            pdfjsLib.getDocument(pdf_url)
                .then(function (pdf_doc) {
                    _pdf_doc = pdf_doc;
                    showPage(1);
                    showPageCount();
                    URL.revokeObjectURL(_object_url);
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
                        _clear.css("display", "");
                    });
                });
        }

        function showPageCount() {
            var pageCount = _pdf_doc.numPages;
            var pageCost = $('#content-price-per-page').text();
            var symbol = pageCost.substr(0, 1);
            pageCost = pageCost.substr(1);
            var price = parseFloat(pageCost) * pageCount;
            console.log(pageCount + " for the price of " + price);

            _pages.text(pageCount);
            _pagePricing.text(symbol + " " + price.toFixed(2));
        }

        _clear.click(function () {
            _upload.val('');
            _name.text('');
            _preview.css("display", "none");
            _clear.css("display", "none");
        })
    });
})(jQuery);