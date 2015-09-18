function imageChooser(entityImages, browseUrl) {


    var imageObj = function (id, src) {
        this.id = id;
        this.src = src;
        this.getImgElem = function () {
            return $('<img>', {
                "src": this.src,
                "data-id": this.id
            });
        }
    };

    function renderImages() {
        $("#imageChooserSelectedImages").html('');
        for (var id in entityImages) {
            if (entityImages.hasOwnProperty(id)) {
                var div = $('<div>', {class: "selectedImageItem"}).append($('<a>', {
                    class: "removeSelectedImageItem",
                    "data-image-id": id,
                    href: "javascript:void(0)",
                    html: "<i class=\"fa fa-trash-o\"></i>"
                }));

                var img = $('<img>', {
                    "src": entityImages[id],
                    "data-id": id
                });

                div.append(img);
                $("#imageChooserSelectedImages").append(div);
            }
        }
    }

    // Returns fresh copy of object
    function copyObject(src) {
        dst = {};
        for (var id in src) {
            if (src.hasOwnProperty(id)) {
                dst[id] = src[id]
            }
        }
        return dst;
    }

    renderImages();

    var imagesChooser = {
        iframe: null,
        selectedImages: {},
        selectedClass: 'selected',

        init: function () {
            this.iframe = $('#imageGalleryIframe');
        },

        getSelectedImagesIds: function () {
            var arr = [];
            for (var id in this.selectedImages) {
                if (this.selectedImages.hasOwnProperty(id)) {
                    arr.push(id);
                }
            }
            return arr;
        },

        getOriginalImgElement: function (img) {
            return this.iframe.contents().find('[data-image-id="' + img.id + '"]');
        },

        getOriginalLiElement: function (img) {
            return this.getOriginalImgElement(img).closest('li');
        },

        isElementSelected: function (img) {
            return this.getOriginalLiElement(img).hasClass(this.selectedClass)
        },

        selectElement: function (img) {
            if (!this.isElementSelected(img)) {
                this.getOriginalLiElement(img).addClass(this.selectedClass)
            }
        },

        unselectElement: function (img) {
            if (this.isElementSelected(img)) {
                this.getOriginalLiElement(img).removeClass(this.selectedClass)
            }
        },

        isImageSelected: function (img) {
            return this.selectedImages.hasOwnProperty(img.id)
        },

        selectImage: function (img) {
            if (!this.isImageSelected(img)) {
                this.selectedImages[img.id] = img.src;
            }
        },

        unselectImage: function (img) {
            if (this.isImageSelected(img)) {
                delete this.selectedImages[img.id];
            }
        },

        handleImgClick: function (img) {
            if (this.isImageSelected(img)) {
                this.unselectImage(img);
            } else {
                this.selectImage(img);
            }

            if (this.isElementSelected(img)) {
                this.unselectElement(img);
            } else {
                this.selectElement(img);
            }
        }
    };

    imagesChooser.init();

    imageClickEvent = function (elem) {
        var img = new imageObj(parseInt($(elem).attr('data-image-id')), $(elem).attr('data-image-src'));
        imagesChooser.handleImgClick(img);
    };

    $("body").on("click", ".removeSelectedImageItem", function () {
        var id = parseInt($(this).attr("data-image-id"));
        if (!confirm('Confirm delete action')) {
            return;
        }
        if (entityImages.hasOwnProperty(id)) {
            delete entityImages[id];
            renderImages();
        }

    });

    var modal = $('#imageGalleryModal');

    modal.on('show.bs.modal', function () {
        imagesChooser.selectedImages = copyObject(entityImages);
        var frameSrc = browseUrl + "?selected=" + JSON.stringify(imagesChooser.getSelectedImagesIds());
        $('#imageGalleryIframe').attr("src", frameSrc).fadeIn();
    });

    modal.on('hidden.bs.modal', function (e) {
        $('#imageGalleryIframe').attr("src", "").hide();
    });

    $("#saveSelection").on("click", function () {
        entityImages = copyObject(imagesChooser.selectedImages);
        modal.modal('hide');
        renderImages();
    });

    // flush image array to select on form submit
    $('form').submit(function () {
        var select = $('select[id$=_images]');
        select.html('');

        for (var id in entityImages) {
            if (entityImages.hasOwnProperty(id)) {
                select.append($('<option>', {
                    value: id,
                    "selected": "selected"
                }));
            }
        }
    })
}
