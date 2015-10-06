var MediaSlider = function () {
    this.init = function () {
        $(".mediaSlider").owlCarousel({
            //navigation: true,
            singleItem: true,
            autoplay: 5000
        });
    }
};

var ms = new MediaSlider();
ms.init();