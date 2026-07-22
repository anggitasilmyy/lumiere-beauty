document.addEventListener("DOMContentLoaded", () => {
    const BASE_URL = window.LUMIERE_BASE_URL || "";

    function assetPath(path) {
        return `${BASE_URL}${path}`.replace(/([^:]\/)\/+/g, "$1");
    }

    const slideData = [
        {
            image: assetPath("assets/images/beauty1.jpeg"),
            badge: "Beauty Clinic Premium",
            title: "Reveal Your Natural Radiance",
            description: "Nikmati pengalaman treatment kecantikan yang elegan, nyaman, dan dirancang untuk menonjolkan kecantikan alammu."
        },
        {
            image: assetPath("assets/images/beauty2.jpeg"),
            badge: "Healthy Skin Journey",
            title: "Treatments Tailored for Every Skin Need",
            description: "Setiap sesi perawatan disusun agar terasa personal, tenang, dan sesuai kebutuhan kulit yang berbeda-beda."
        },
        {
            image: assetPath("assets/images/beauty3.jpeg"),
            badge: "Confidence Starts Here",
            title: "Modern Care in a Luxurious Atmosphere",
            description: "Perpaduan suasana klinik yang hangat dan penampilan visual yang bersih membuat home page terasa lebih premium."
        }
    ];

    const sliderElements = {
        slidesContainer: document.getElementById("heroSlides"),
        dotsContainer: document.getElementById("sliderDots"),
        title: document.getElementById("heroTitle"),
        description: document.getElementById("heroDescription"),
        badge: document.getElementById("heroBadge"),
        prevButton: document.getElementById("prevSlide"),
        nextButton: document.getElementById("nextSlide"),
        heroSection: document.querySelector(".hero-slider")
    };

    const nav = {
        header: document.querySelector(".site-header"),
        menuToggle: document.getElementById("menuToggle"),
        siteNav: document.getElementById("siteNav"),
        navLinks: document.querySelectorAll(".nav-link")
    };

    const counters = document.querySelectorAll(".counter");
    const revealElements = document.querySelectorAll(".reveal");
    const treatmentButtons = document.querySelectorAll(".select-treatment");
    const promoButton = document.querySelector(".promo-cta");

    const countdownElements = {
        days: document.getElementById("countDays"),
        hours: document.getElementById("countHours"),
        minutes: document.getElementById("countMinutes")
    };

    let currentSlide = 0;
    let sliderInterval = null;

    function hasSlider() {
        return (
            sliderElements.slidesContainer &&
            sliderElements.dotsContainer &&
            sliderElements.title &&
            sliderElements.description &&
            sliderElements.badge &&
            sliderElements.prevButton &&
            sliderElements.nextButton &&
            sliderElements.heroSection
        );
    }

    function renderSlides() {
        if (!hasSlider()) return;

        sliderElements.slidesContainer.innerHTML = slideData
            .map((slide, index) => `
                <div
                    class="hero-slide ${index === 0 ? "active" : ""}"
                    data-index="${index}"
                    aria-hidden="${index === 0 ? "false" : "true"}"
                    style="background-image: url('${slide.image}')"
                ></div>
            `)
            .join("");

        sliderElements.dotsContainer.innerHTML = slideData
            .map((_, index) => `
                <button
                    class="dot ${index === 0 ? "active" : ""}"
                    type="button"
                    aria-label="Pindah ke slide ${index + 1}"
                    data-index="${index}"
                ></button>
            `)
            .join("");

        updateSlideContent(0);
    }

    function updateSlideContent(index) {
        if (!hasSlider()) return;

        const slides = document.querySelectorAll(".hero-slide");
        const dots = document.querySelectorAll(".dot");
        const selectedSlide = slideData[index];

        slides.forEach((slide, slideIndex) => {
            const isActive = slideIndex === index;
            slide.classList.toggle("active", isActive);
            slide.setAttribute("aria-hidden", String(!isActive));
        });

        dots.forEach((dot, dotIndex) => {
            dot.classList.toggle("active", dotIndex === index);
        });

        sliderElements.badge.textContent = selectedSlide.badge;
        sliderElements.title.textContent = selectedSlide.title;
        sliderElements.description.textContent = selectedSlide.description;
        currentSlide = index;
    }

    function showNextSlide() {
        updateSlideContent((currentSlide + 1) % slideData.length);
    }

    function showPrevSlide() {
        updateSlideContent((currentSlide - 1 + slideData.length) % slideData.length);
    }

    function startAutoSlide() {
        if (!hasSlider()) return;

        stopAutoSlide();
        sliderInterval = window.setInterval(showNextSlide, 4500);
    }

    function stopAutoSlide() {
        if (sliderInterval) {
            window.clearInterval(sliderInterval);
        }
    }

    function resetAutoSlide() {
        startAutoSlide();
    }

    function bindSliderEvents() {
        if (!hasSlider()) return;

        sliderElements.prevButton.addEventListener("click", () => {
            showPrevSlide();
            resetAutoSlide();
        });

        sliderElements.nextButton.addEventListener("click", () => {
            showNextSlide();
            resetAutoSlide();
        });

        sliderElements.dotsContainer.addEventListener("click", (event) => {
            const target = event.target;

            if (!(target instanceof HTMLElement) || !target.classList.contains("dot")) {
                return;
            }

            const index = Number(target.dataset.index);
            updateSlideContent(index);
            resetAutoSlide();
        });

        sliderElements.heroSection.addEventListener("mouseenter", stopAutoSlide);
        sliderElements.heroSection.addEventListener("mouseleave", startAutoSlide);

        document.addEventListener("keydown", (event) => {
            if (event.key === "ArrowRight") {
                showNextSlide();
                resetAutoSlide();
            }

            if (event.key === "ArrowLeft") {
                showPrevSlide();
                resetAutoSlide();
            }
        });
    }

    function toggleMenu(forceClose = false) {
        if (!nav.siteNav || !nav.menuToggle) return;

        const shouldOpen = forceClose ? false : !nav.siteNav.classList.contains("open");

        nav.siteNav.classList.toggle("open", shouldOpen);
        nav.menuToggle.classList.toggle("active", shouldOpen);
        nav.menuToggle.setAttribute("aria-expanded", String(shouldOpen));
    }

    function handleHeaderState() {
        if (!nav.header) return;

        nav.header.classList.toggle("scrolled", window.scrollY > 10);
    }

    function getHashFromHref(href) {
        try {
            const parsedUrl = new URL(href, window.location.href);
            return parsedUrl.hash;
        } catch {
            return href.startsWith("#") ? href : "";
        }
    }

    function normalizePath(pathname) {
        return pathname.replace(/\/+/g, "/").replace(/\/index\.php$/, "/");
    }

    function isHomepagePath(pathname) {
        const fileName = pathname.split("/").pop();
        return fileName === "" || fileName === "index.php";
    }

    function handleActiveSection() {
        const currentPath = normalizePath(window.location.pathname);
        const currentPage = (document.body.dataset.currentPage || "").toLowerCase();
        const isTreatmentPage = currentPage === "treatments.php" || currentPath.endsWith("/treatments.php");

        if (isTreatmentPage) {
            nav.navLinks.forEach((link) => {
                const href = link.getAttribute("href") || "";
                let linkFile = "";

                try {
                    linkFile = new URL(href, window.location.href).pathname.split("/").pop().toLowerCase();
                } catch {
                    linkFile = "";
                }

                link.classList.toggle("active", linkFile === "treatments.php");
            });

            return;
        }

        if (!isHomepagePath(window.location.pathname)) {
            nav.navLinks.forEach((link) => {
                const href = link.getAttribute("href") || "";
                let linkPath = "";

                try {
                    linkPath = normalizePath(new URL(href, window.location.href).pathname);
                } catch {
                    linkPath = "";
                }

                link.classList.toggle("active", linkPath === currentPath);
            });

            return;
        }

        const sections = document.querySelectorAll("main section[id], footer[id]");
        let currentSectionId = "home";

        sections.forEach((section) => {
            const rect = section.getBoundingClientRect();

            if (rect.top <= 140 && rect.bottom >= 140) {
                currentSectionId = section.id;
            }
        });

        nav.navLinks.forEach((link) => {
            const hash = getHashFromHref(link.getAttribute("href") || "");
            const isActive = hash === `#${currentSectionId}`;

            link.classList.toggle("active", isActive);
        });
    }

    function bindNavigationEvents() {
        if (nav.menuToggle) {
            nav.menuToggle.addEventListener("click", () => toggleMenu());
        }

        nav.navLinks.forEach((link) => {
            link.addEventListener("click", () => toggleMenu(true));
        });

        window.addEventListener("scroll", () => {
            handleHeaderState();
            handleActiveSection();
        });

        handleHeaderState();
        handleActiveSection();
    }

    function animateCounter(counterElement) {
        const target = Number(counterElement.dataset.target || 0);
        let current = 0;
        const increment = Math.max(1, Math.ceil(target / 50));

        const counterTimer = window.setInterval(() => {
            current += increment;

            if (current >= target) {
                current = target;
                window.clearInterval(counterTimer);
            }

            counterElement.textContent = current.toLocaleString("id-ID");
        }, 30);
    }

    function initializeObservers() {
        if (!("IntersectionObserver" in window)) {
            revealElements.forEach((element) => element.classList.add("visible"));
            counters.forEach((counter) => animateCounter(counter));
            return;
        }

        const revealObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("visible");
                        revealObserver.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.15 }
        );

        revealElements.forEach((element) => revealObserver.observe(element));

        const counterObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        counterObserver.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.7 }
        );

        counters.forEach((counter) => counterObserver.observe(counter));
    }

    function hasCountdown() {
        return countdownElements.days && countdownElements.hours && countdownElements.minutes;
    }

    function updatePromoCountdown() {
        if (!hasCountdown()) return;

        const now = new Date();
        const promoEndDate = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59);
        const difference = promoEndDate.getTime() - now.getTime();

        if (difference <= 0) {
            countdownElements.days.textContent = "00";
            countdownElements.hours.textContent = "00";
            countdownElements.minutes.textContent = "00";
            return;
        }

        const totalMinutes = Math.floor(difference / (1000 * 60));
        const days = Math.floor(totalMinutes / (60 * 24));
        const hours = Math.floor((totalMinutes % (60 * 24)) / 60);
        const minutes = totalMinutes % 60;

        countdownElements.days.textContent = String(days).padStart(2, "0");
        countdownElements.hours.textContent = String(hours).padStart(2, "0");
        countdownElements.minutes.textContent = String(minutes).padStart(2, "0");
    }

    function goToTreatmentBooking(treatmentName = "") {
        const target = treatmentName
            ? assetPath(`treatments.php?selected=${encodeURIComponent(treatmentName)}`)
            : assetPath("treatments.php");

        window.location.href = target;
    }

    function bindTreatmentButtons() {
        treatmentButtons.forEach((button) => {
            button.addEventListener("click", () => {
                const card = button.closest(".treatment-card");
                const treatmentName = card ? card.dataset.treatment || "" : "";

                goToTreatmentBooking(treatmentName);
            });
        });

        if (promoButton) {
            promoButton.addEventListener("click", () => {
                const treatmentName = promoButton.dataset.treatment || "";
                goToTreatmentBooking(treatmentName);
            });
        }
    }

    renderSlides();
    bindSliderEvents();
    startAutoSlide();

    bindNavigationEvents();
    initializeObservers();

    updatePromoCountdown();
    window.setInterval(updatePromoCountdown, 60000);

    bindTreatmentButtons();
});