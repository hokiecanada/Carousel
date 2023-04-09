jQuery(document).ready(function($) {
    $(".carousel-wrapper").each(function() {
        let $carousel = $(this).find(".carousel");
        let $slides = $carousel.find(".slide");
        let $prevBtn = $(this).find(".prev-btn");
        let $nextBtn = $(this).find(".next-btn");

        $slides.addClass("hidden");
        $slides.first().addClass("prev3").removeClass("hidden")
                .next(".slide").addClass("prev2").removeClass("hidden")
                .next(".slide").addClass("prev").removeClass("hidden")
                .next(".slide").addClass("active").removeClass("hidden")
                .next(".slide").addClass("next").removeClass("hidden")
                .next(".slide").addClass("next2").removeClass("hidden")
                .next(".slide").addClass("next3").removeClass("hidden");

        $carousel.on("swiperight", function() {
            reverse();
        });

        $carousel.on("swipeleft", function() {
            advance();
        });

        $nextBtn.click(function() {
            advance();
        });

        $prevBtn.click(function() {
            reverse();
        });

        function advance() {
            $carousel.find(".prev3").addClass("hidden").removeClass("prev3");
            $carousel.find(".prev2").addClass("prev3").removeClass("prev2");
            $carousel.find(".prev").addClass("prev2").removeClass("prev");
            $carousel.find(".active").addClass("prev").removeClass("active");
            $carousel.find(".next").addClass("active").removeClass("next");
            $carousel.find(".next2").addClass("next").removeClass("next2");
            let $slide = $carousel.find(".next3").addClass("next2").removeClass("next3");

            if ($slide.next(".slide").length) {
                $slide.next(".slide").removeClass("hidden").addClass("next3");
            } else {
                $slides.first().addClass("next3").removeClass("hidden");
            }
        }

        function reverse() {
            $carousel.find(".next3").addClass("hidden").removeClass("next3");
            $carousel.find(".next2").addClass("next3").removeClass("next2");
            $carousel.find(".next").addClass("next2").removeClass("next");
            $carousel.find(".active").addClass("next").removeClass("active");
            $carousel.find(".prev").addClass("active").removeClass("prev");
            $carousel.find(".prev2").addClass("prev").removeClass("prev2");
            let $slide = $carousel.find(".prev3").addClass("prev2").removeClass("prev3");

            if ($slide.prev(".slide").length) {
                $slide.prev(".slide").addClass("prev3").removeClass("hidden");
            } else {
                $slides.last(".slide").addClass("prev3").removeClass("hidden");
            }
        }
    });
});