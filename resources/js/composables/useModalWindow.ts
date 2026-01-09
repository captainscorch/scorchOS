import { useWindowSize } from '@vueuse/core';
import { computed, onUnmounted, ref } from 'vue';

export function useModalWindow(initialWidth = 800, initialHeight = 612, minWidthMobile = 290, minWidthDesktop = 400) {
    const { width: windowWidth, height: windowHeight } = useWindowSize();

    // State
    const isDragging = ref(false);
    const dragOffset = ref({ x: 0, y: 0 });
    const modalPosition = ref<{ x: number | null; y: number | null }>({ x: null, y: null });
    const modalRef = ref<HTMLElement | null>(null);
    const handleRef = ref<HTMLElement | null>(null);

    const isResizing = ref(false);
    const resizeDirection = ref('');
    const resizeStart = ref({ x: 0, y: 0, width: 0, height: 0 });
    const modalSize = ref({ width: initialWidth, height: initialHeight });
    const isFullscreen = ref(false);

    // Computed
    const modalWidth = computed(() => {
        if (isFullscreen.value) {
            return windowWidth.value < 768 ? '100dvw' : '100vw';
        }
        const isMobile = windowWidth.value < 768;
        return isMobile ? 'calc(100vw - 2rem)' : `${modalSize.value.width}px`;
    });

    const modalHeight = computed(() => {
        if (isFullscreen.value) {
            return windowWidth.value < 768 ? '100dvh' : '100vh';
        }
        return `${modalSize.value.height}px`;
    });

    const modalMinWidth = computed(() => {
        const isMobile = windowWidth.value < 768;
        return isMobile ? `${minWidthMobile}px` : `${minWidthDesktop}px`;
    });

    // Methods
    const setInitialSize = () => {
        const isMobile = windowWidth.value < 768;
        if (isMobile) {
            modalSize.value = {
                width: Math.min(initialWidth, windowWidth.value - 32),
                height: Math.min(initialHeight, windowHeight.value - 32),
            };
        } else {
            modalSize.value = {
                width: initialWidth,
                height: initialHeight,
            };
        }
    };

    const startDrag = (event: MouseEvent) => {
        if (!modalRef.value || isFullscreen.value) return;

        // Check if the target is the handle or inside the handle
        if (handleRef.value && !handleRef.value.contains(event.target as Node) && event.target !== handleRef.value) {
            return;
        }

        // Ignore buttons within the handle
        if ((event.target as HTMLElement).tagName === 'BUTTON' || (event.target as HTMLElement).parentElement?.tagName === 'BUTTON') return;

        isDragging.value = true;

        const rect = modalRef.value.getBoundingClientRect();

        if (modalPosition.value.x === null || modalPosition.value.y === null) {
            const centerX = window.innerWidth / 2;
            const centerY = window.innerHeight / 2;
            const width = rect.width;
            const height = rect.height;

            modalPosition.value = {
                x: centerX - width / 2,
                y: centerY - height / 2,
            };
        }

        dragOffset.value = {
            x: event.clientX - rect.left,
            y: event.clientY - rect.top,
        };

        document.addEventListener('mousemove', onDrag, { passive: false });
        document.addEventListener('mouseup', stopDrag, { passive: false });
        // event.preventDefault(); // Removed to allow text selection if needed, but usually good for drag
    };

    const onDrag = (event: MouseEvent) => {
        if (!isDragging.value) return;

        let newX = event.clientX - dragOffset.value.x;
        let newY = event.clientY - dragOffset.value.y;

        // Keep modal within viewport bounds
        const width = modalRef.value?.offsetWidth || initialWidth;
        const height = modalRef.value?.offsetHeight || initialHeight;

        newX = Math.max(0, Math.min(newX, window.innerWidth - width));
        newY = Math.max(0, Math.min(newY, window.innerHeight - height));

        modalPosition.value = {
            x: newX,
            y: newY,
        };
    };

    const stopDrag = () => {
        isDragging.value = false;
        document.removeEventListener('mousemove', onDrag);
        document.removeEventListener('mouseup', stopDrag);
    };

    const startResize = (event: MouseEvent, direction: string) => {
        if (!modalRef.value) return;

        // Disable on mobile devices
        if (window.innerWidth < 768) return;

        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        isResizing.value = true;
        resizeDirection.value = direction;

        const rect = modalRef.value.getBoundingClientRect();
        resizeStart.value = {
            x: event.clientX,
            y: event.clientY,
            width: rect.width,
            height: rect.height,
        };

        document.addEventListener('mousemove', onResize);
        document.addEventListener('mouseup', stopResize);
    };

    const onResize = (event: MouseEvent) => {
        if (!isResizing.value) return;

        const deltaX = event.clientX - resizeStart.value.x;
        const deltaY = event.clientY - resizeStart.value.y;

        let newWidth = resizeStart.value.width;
        let newHeight = resizeStart.value.height;

        if (resizeDirection.value.includes('right')) {
            newWidth = Math.max(minWidthDesktop, resizeStart.value.width + deltaX);
        }
        if (resizeDirection.value.includes('left')) {
            newWidth = Math.max(minWidthDesktop, resizeStart.value.width - deltaX);
        }
        if (resizeDirection.value.includes('bottom')) {
            newHeight = Math.max(300, resizeStart.value.height + deltaY);
        }
        if (resizeDirection.value.includes('top')) {
            newHeight = Math.max(300, resizeStart.value.height - deltaY);
        }

        // Constrain to full viewport dimensions
        newWidth = Math.min(newWidth, window.innerWidth);
        newHeight = Math.min(newHeight, window.innerHeight);

        modalSize.value = {
            width: newWidth,
            height: newHeight,
        };
    };

    const stopResize = (event?: MouseEvent) => {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();
        }

        setTimeout(() => {
            isResizing.value = false;
            resizeDirection.value = '';
        }, 50);

        document.removeEventListener('mousemove', onResize);
        document.removeEventListener('mouseup', stopResize);
    };

    const toggleFullscreen = () => {
        isFullscreen.value = !isFullscreen.value;
        if (isFullscreen.value) {
            modalPosition.value = { x: null, y: null };
        }
    };

    const resetPosition = () => {
        modalPosition.value = { x: null, y: null };
        modalSize.value = { width: initialWidth, height: initialHeight };
        isFullscreen.value = false;
    };

    // Lifecycle hooks for cleanup
    onUnmounted(() => {
        document.removeEventListener('mousemove', onDrag);
        document.removeEventListener('mouseup', stopDrag);
        document.removeEventListener('mousemove', onResize);
        document.removeEventListener('mouseup', stopResize);
    });

    return {
        // Refs
        modalRef,
        handleRef,

        // State
        isDragging,
        isResizing,
        isFullscreen,
        modalPosition,
        modalSize,

        // Computed
        modalWidth,
        modalHeight,
        modalMinWidth,
        windowWidth,

        // Methods
        startDrag,
        startResize,
        toggleFullscreen,
        resetPosition,
        setInitialSize,
    };
}
