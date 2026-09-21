export {};

if (typeof window !== 'undefined' && typeof document !== 'undefined') {
    const reducedMotionQuery = window.matchMedia(
        '(prefers-reduced-motion: reduce)',
    );
    const finePointerQuery = window.matchMedia(
        '(hover: hover) and (pointer: fine)',
    );

    const revealSelector = [
        '.vvs-storefront main > section',
        '.vvs-storefront main article > section',
        '.watch-card',
        '.guide-info-card',
        '.guide-choice-card',
        '.guide-report',
    ].join(',');

    const titleSelector = [
        '.vvs-display-title',
        '.guide-hero h1',
        '.guide-hero h2',
    ].join(',');

    const editorialImageSelector = 'img[src*="/images/editorial/"]';
    const spectrumImageSelector =
        'img[src*="/images/editorial/diamond-vs-moissanite"]';

    let revealObserver: IntersectionObserver | null = null;
    let mutationObserver: MutationObserver | null = null;

    const isInInitialViewport = (element: HTMLElement) => {
        const bounds = element.getBoundingClientRect();

        return bounds.top < window.innerHeight && bounds.bottom > 0;
    };

    const prepareEditorialImage = (image: HTMLImageElement) => {
        const wrapper = image.parentElement;

        if (!(wrapper instanceof HTMLElement)) {
            return;
        }

        wrapper.classList.add('vvs-editorial-shimmer', 'vvs-soft-zoom');

        if (image.matches(spectrumImageSelector)) {
            wrapper.classList.add('vvs-spectrum-visual');
        }
    };

    const preparePointerCard = (card: HTMLElement) => {
        if (card.dataset.vvsPointerReady === 'true') {
            return;
        }

        card.dataset.vvsPointerReady = 'true';

        card.addEventListener(
            'pointermove',
            (event) => {
                if (
                    !finePointerQuery.matches ||
                    reducedMotionQuery.matches
                ) {
                    return;
                }

                const bounds = card.getBoundingClientRect();

                if (!bounds.width || !bounds.height) {
                    return;
                }

                const x =
                    ((event.clientX - bounds.left) / bounds.width) * 100;
                const y =
                    ((event.clientY - bounds.top) / bounds.height) * 100;

                card.style.setProperty(
                    '--vvs-pointer-x',
                    `${Math.max(0, Math.min(100, x)).toFixed(2)}%`,
                );
                card.style.setProperty(
                    '--vvs-pointer-y',
                    `${Math.max(0, Math.min(100, y)).toFixed(2)}%`,
                );
            },
            { passive: true },
        );

        card.addEventListener(
            'pointerleave',
            () => {
                card.style.removeProperty('--vvs-pointer-x');
                card.style.removeProperty('--vvs-pointer-y');
            },
            { passive: true },
        );
    };

    const prepareRevealElement = (element: Element) => {
        if (!(element instanceof HTMLElement)) {
            return;
        }

        if (element.dataset.vvsRevealReady === 'true') {
            return;
        }

        element.dataset.vvsRevealReady = 'true';
        element.classList.add('vvs-reveal-ready');

        if (
            reducedMotionQuery.matches ||
            !revealObserver ||
            isInInitialViewport(element)
        ) {
            element.classList.add('vvs-in-view');
            return;
        }

        revealObserver.observe(element);
    };

    const prepareTitle = (element: Element) => {
        if (!(element instanceof HTMLElement)) {
            return;
        }

        element.classList.add('vvs-title-accent');

        if (
            reducedMotionQuery.matches ||
            !revealObserver ||
            isInInitialViewport(element)
        ) {
            element.classList.add('vvs-in-view');
            return;
        }

        revealObserver.observe(element);
    };

    const prepareFilters = () => {
        const searchInput = document.querySelector<HTMLInputElement>(
            '#collection-search',
        );
        const sortSelect = document.querySelector<HTMLSelectElement>(
            '#collection-sort',
        );

        if (searchInput) {
            const wrapper = searchInput.parentElement;
            const active = searchInput.value.trim() !== '';

            if (wrapper instanceof HTMLElement) {
                wrapper.classList.toggle('vvs-filter-active', active);
            }

            if (searchInput.dataset.vvsFilterReady !== 'true') {
                searchInput.dataset.vvsFilterReady = 'true';

                searchInput.addEventListener('input', () => {
                    const parent = searchInput.parentElement;

                    if (parent instanceof HTMLElement) {
                        parent.classList.toggle(
                            'vvs-filter-active',
                            searchInput.value.trim() !== '',
                        );
                    }
                });
            }
        }

        if (sortSelect) {
            const wrapper = sortSelect.parentElement;
            const active = sortSelect.value !== 'newest';

            if (wrapper instanceof HTMLElement) {
                wrapper.classList.toggle('vvs-filter-active', active);
            }

            if (sortSelect.dataset.vvsFilterReady !== 'true') {
                sortSelect.dataset.vvsFilterReady = 'true';

                sortSelect.addEventListener('change', () => {
                    const parent = sortSelect.parentElement;

                    if (parent instanceof HTMLElement) {
                        parent.classList.toggle(
                            'vvs-filter-active',
                            sortSelect.value !== 'newest',
                        );
                    }
                });
            }
        }
    };

    const prepareConfirmation = () => {
        const candidates =
            document.querySelectorAll<HTMLElement>('main.vvs-storefront div');

        for (const element of candidates) {
            if (element.textContent?.trim() !== '\u2713') {
                continue;
            }

            element.classList.add('vvs-confirm-icon-safe');
            break;
        }
    };

    const scan = (root: ParentNode = document) => {
        root.querySelectorAll<HTMLElement>(revealSelector).forEach(
            prepareRevealElement,
        );

        root.querySelectorAll<HTMLElement>(titleSelector).forEach(
            prepareTitle,
        );

        root.querySelectorAll<HTMLImageElement>(editorialImageSelector).forEach(
            prepareEditorialImage,
        );

        root.querySelectorAll<HTMLElement>('.watch-card').forEach(
            preparePointerCard,
        );

        prepareFilters();
        prepareConfirmation();
    };

    const syncReducedMotionClass = () => {
        document.documentElement.classList.toggle(
            'vvs-reduced-motion',
            reducedMotionQuery.matches,
        );

        if (reducedMotionQuery.matches) {
            document
                .querySelectorAll<HTMLElement>('.vvs-reveal-ready')
                .forEach((element) => element.classList.add('vvs-in-view'));
        }
    };

    const initialize = () => {
        document.documentElement.classList.add('vvs-motion-ready');
        syncReducedMotionClass();

        revealObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add('vvs-in-view');
                    revealObserver?.unobserve(entry.target);
                });
            },
            {
                threshold: 0.08,
                rootMargin: '0px 0px -7% 0px',
            },
        );

        scan();

        mutationObserver = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (!(node instanceof HTMLElement)) {
                        return;
                    }

                    scan(node);
                });
            });
        });

        mutationObserver.observe(document.body, {
            childList: true,
            subtree: true,
        });

        reducedMotionQuery.addEventListener(
            'change',
            syncReducedMotionClass,
        );
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize, {
            once: true,
        });
    } else {
        initialize();
    }
}
