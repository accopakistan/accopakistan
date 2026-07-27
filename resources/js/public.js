// ACCO Pakistan public site — GSAP-driven premium interactions.
// Fully independent of Bootstrap's JS bundle (admin keeps that separately).

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

function onReady(fn) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fn);
    } else {
        fn();
    }
}

// --- Header scroll state + mega menu ---------------------------------------

function initHeader() {
    const header = document.querySelector('[data-header]:not([data-header-init])');
    if (!header) return;
    header.setAttribute('data-header-init', 'true');

    const isTransparent = header.dataset.transparent === '1';

    const updateScroll = () => {
        if (!isTransparent) {
            header.classList.add('is-solid');
            return;
        }
        header.classList.toggle('is-solid', window.scrollY > 40);
    };
    updateScroll();
    window.addEventListener('scroll', updateScroll, { passive: true });

    const triggers = header.querySelectorAll('[data-mega-trigger]');
    const megas = document.querySelectorAll('[data-mega]');
    const nav = header.querySelector('[data-header-nav]');

    let closeTimer = null;

    const cancelClose = () => {
        if (closeTimer) {
            clearTimeout(closeTimer);
            closeTimer = null;
        }
    };

    const closeAll = () => {
        triggers.forEach((t) => t.classList.remove('is-open'));
        megas.forEach((m) => m.classList.remove('is-open'));
    };

    const scheduleClose = () => {
        cancelClose();
        closeTimer = setTimeout(closeAll, 250);
    };

    triggers.forEach((trigger) => {
        const key = trigger.dataset.megaTrigger;
        const panel = document.querySelector(`[data-mega="${key}"]`);
        if (!panel) return;

        trigger.addEventListener('mouseenter', () => {
            cancelClose();
            closeAll();
            trigger.classList.add('is-open');
            panel.classList.add('is-open');
        });

        panel.addEventListener('mouseenter', cancelClose);
        panel.addEventListener('mouseleave', scheduleClose);
    });

    nav?.addEventListener('mouseleave', scheduleClose);
    nav?.addEventListener('mouseenter', cancelClose);
}

// --- Mobile menu -------------------------------------------------------

function initMobileMenu() {
    const toggle = document.querySelector('[data-menu-toggle]:not([data-menu-init])');
    const menu = document.querySelector('[data-mobile-menu]');
    const closeBtn = document.querySelector('[data-menu-close]');
    if (!toggle || !menu) return;
    toggle.setAttribute('data-menu-init', 'true');

    const open = () => {
        menu.classList.add('is-open');
        toggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    };
    const close = () => {
        menu.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    };

    toggle.addEventListener('click', () => {
        menu.classList.contains('is-open') ? close() : open();
    });
    closeBtn?.addEventListener('click', close);

    menu.querySelectorAll('[data-submenu-toggle]').forEach((sub) => {
        sub.addEventListener('click', () => {
            const panel = document.getElementById(sub.dataset.submenuToggle);
            panel?.classList.toggle('is-open');
            sub.classList.toggle('is-open');
        });
    });

    menu.querySelectorAll('a:not([data-submenu-toggle])').forEach((link) => link.addEventListener('click', close));
}

// --- Hero + heading reveal ------------------------------------------------

function initTextReveal() {
    const targets = document.querySelectorAll('[data-reveal-text]:not([data-reveal-init])');
    targets.forEach((el) => {
        el.setAttribute('data-reveal-init', 'true');
        const span = el.querySelector(':scope > .line > span') || el;

        gsap.fromTo(
            span,
            { yPercent: 110 },
            {
                yPercent: 0,
                duration: 1.1,
                ease: 'power4.out',
                delay: 0.15,
            }
        );
    });
}

// --- Scroll reveals ------------------------------------------------------

function initScrollReveals() {
    const ups = document.querySelectorAll('.reveal-up:not([data-reveal-init])');
    ups.forEach((el) => {
        el.setAttribute('data-reveal-init', 'true');
        gsap.to(el, {
            opacity: 1,
            y: 0,
            duration: 0.9,
            ease: 'power3.out',
            scrollTrigger: { trigger: el, start: 'top 88%' },
        });
    });

    const fades = document.querySelectorAll('.reveal-fade:not([data-reveal-init])');
    fades.forEach((el) => {
        el.setAttribute('data-reveal-init', 'true');
        gsap.to(el, {
            opacity: 1,
            duration: 1.1,
            ease: 'power2.out',
            scrollTrigger: { trigger: el, start: 'top 90%' },
        });
    });

    const masks = document.querySelectorAll('.reveal-mask:not([data-reveal-init])');
    masks.forEach((el) => {
        el.setAttribute('data-reveal-init', 'true');
        const img = el.querySelector('img');
        if (!img) return;
        gsap.to(img, {
            scale: 1,
            duration: 1.4,
            ease: 'power3.out',
            scrollTrigger: { trigger: el, start: 'top 85%' },
        });
    });
}

// --- Stat counters ---------------------------------------------------------

function initCounters() {
    const counters = document.querySelectorAll('[data-count-to]:not([data-count-init])');
    counters.forEach((el) => {
        el.setAttribute('data-count-init', 'true');
        const target = parseFloat(el.dataset.countTo);
        const suffix = el.dataset.countSuffix ?? '';
        const obj = { val: 0 };

        gsap.to(obj, {
            val: target,
            duration: 1.6,
            ease: 'power2.out',
            scrollTrigger: { trigger: el, start: 'top 90%', once: true },
            onUpdate: () => {
                el.textContent = Math.round(obj.val) + suffix;
            },
        });
    });
}

// --- Testimonial slider ------------------------------------------------

function initTestimonialSlider() {
    const sliders = document.querySelectorAll('[data-testi-slider]:not([data-testi-init])');
    sliders.forEach((slider) => {
        slider.setAttribute('data-testi-init', 'true');
        const track = slider.querySelector('[data-testi-track]');
        const slides = slider.querySelectorAll('[data-testi-slide]');
        const dots = slider.querySelectorAll('[data-testi-dot]');
        if (!track || !slides.length) return;

        let index = 0;
        const go = (i) => {
            index = (i + slides.length) % slides.length;
            track.style.transform = `translateX(-${index * 100}%)`;
            dots.forEach((d, di) => d.classList.toggle('is-active', di === index));
        };

        dots.forEach((dot, di) => dot.addEventListener('click', () => go(di)));

        let autoplay = setInterval(() => go(index + 1), 6000);
        slider.addEventListener('mouseenter', () => clearInterval(autoplay));
        slider.addEventListener('mouseleave', () => {
            autoplay = setInterval(() => go(index + 1), 6000);
        });
    });
}

// --- Accordion (FAQ) ------------------------------------------------------

function initAccordions() {
    const triggers = document.querySelectorAll('[data-accordion-trigger]:not([data-accordion-init])');
    triggers.forEach((trigger) => {
        trigger.setAttribute('data-accordion-init', 'true');
        trigger.addEventListener('click', () => {
            const panel = trigger.nextElementSibling;
            const isOpen = trigger.getAttribute('aria-expanded') === 'true';

            trigger.closest('[data-accordion-group]')
                ?.querySelectorAll('[data-accordion-trigger]')
                .forEach((t) => {
                    if (t !== trigger) {
                        t.setAttribute('aria-expanded', 'false');
                        t.nextElementSibling.style.maxHeight = null;
                    }
                });

            trigger.setAttribute('aria-expanded', String(!isOpen));
            panel.style.maxHeight = isOpen ? null : `${panel.scrollHeight}px`;
        });
    });
}

// --- Tabs -----------------------------------------------------------------

function initTabs() {
    const groups = document.querySelectorAll('[data-tabs]:not([data-tabs-init])');
    groups.forEach((group) => {
        group.setAttribute('data-tabs-init', 'true');
        const tabs = group.querySelectorAll('[data-tab]');
        const panels = document.querySelectorAll(`[data-tab-panel][data-tab-group="${group.dataset.tabs}"]`);

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                tabs.forEach((t) => t.classList.remove('is-active'));
                panels.forEach((p) => p.classList.add('is-hidden'));
                tab.classList.add('is-active');
                document.querySelector(`[data-tab-panel="${tab.dataset.tab}"]`)?.classList.remove('is-hidden');
            });
        });
    });
}

// --- Blog: reading progress + table of contents ---------------------------

function initReadingProgress() {
    const bar = document.querySelector('[data-reading-progress]:not([data-progress-init])');
    const article = document.querySelector('[data-article-body]');
    if (!bar || !article) return;
    bar.setAttribute('data-progress-init', 'true');

    const update = () => {
        const rect = article.getBoundingClientRect();
        const total = rect.height - window.innerHeight;
        const scrolled = Math.min(Math.max(-rect.top, 0), total);
        bar.style.width = `${total > 0 ? (scrolled / total) * 100 : 0}%`;
    };
    update();
    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
}

function initTocHighlight() {
    const links = document.querySelectorAll('[data-toc-link]:not([data-toc-init])');
    if (!links.length) return;

    links.forEach((l) => l.setAttribute('data-toc-init', 'true'));

    const headings = Array.from(links)
        .map((l) => document.getElementById(l.getAttribute('href').slice(1)))
        .filter(Boolean);

    if (!headings.length) return;

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                links.forEach((l) => l.classList.remove('is-active'));
                document.querySelector(`[data-toc-link][href="#${entry.target.id}"]`)?.classList.add('is-active');
            });
        },
        { rootMargin: '-20% 0px -70% 0px' }
    );

    headings.forEach((h) => observer.observe(h));
}

// --- Parallax on hero media --------------------------------------------

function initParallax() {
    const targets = document.querySelectorAll('[data-parallax]:not([data-parallax-init])');
    targets.forEach((el) => {
        el.setAttribute('data-parallax-init', 'true');
        gsap.to(el, {
            yPercent: 12,
            ease: 'none',
            scrollTrigger: {
                trigger: el.closest('section') || el,
                start: 'top top',
                end: 'bottom top',
                scrub: true,
            },
        });
    });
}

function initAll() {
    initHeader();
    initMobileMenu();
    initTextReveal();
    initScrollReveals();
    initCounters();
    initTestimonialSlider();
    initAccordions();
    initTabs();
    initReadingProgress();
    initTocHighlight();
    initParallax();
    ScrollTrigger.refresh();
}

onReady(initAll);
document.addEventListener('livewire:navigated', initAll);
