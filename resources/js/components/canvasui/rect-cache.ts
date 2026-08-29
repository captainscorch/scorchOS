/**
 * Caches an element's bounding rect and only re-measures when something could
 * have moved it. Pointer handlers read `current` per event, so calling
 * getBoundingClientRect() directly there would force a layout on every move.
 */
export interface RectCache {
  readonly current: DOMRect;
  destroy: () => void;
}

const EMPTY = new DOMRect(0, 0, 0, 0);

export function createRectCache(element: Element): RectCache {
  let rect: DOMRect | null = null;
  let destroyed = false;

  const invalidate = () => {
    rect = null;
  };

  const observer =
    typeof ResizeObserver === "undefined" ? null : new ResizeObserver(invalidate);
  observer?.observe(element);

  window.addEventListener("scroll", invalidate, { passive: true, capture: true });
  window.addEventListener("resize", invalidate, { passive: true });

  return {
    get current() {
      if (destroyed) return EMPTY;
      if (!rect) rect = element.getBoundingClientRect();
      return rect;
    },
    destroy() {
      destroyed = true;
      rect = null;
      observer?.disconnect();
      window.removeEventListener("scroll", invalidate, { capture: true });
      window.removeEventListener("resize", invalidate);
    },
  };
}
