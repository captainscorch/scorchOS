/**
 * Chrome's html-in-canvas trial stopped hit testing canvas descendants
 * (seen in Chrome 155): a click on a drawn link lands on the canvas or on
 * whatever lies behind it, never on the link. The descendants still keep
 * their layout boxes where they are drawn, so this forwards clicks and the
 * cursor to the interactive element under the pointer by its box.
 *
 * Once a click reaches a descendant natively, nothing is forwarded, so the
 * shim steps aside by itself when Chrome hit tests the children again.
 */
const INTERACTIVE =
  'a[href], button, input, select, textarea, label, summary, [role="button"]';

export function forwardCanvasPointer(content: HTMLElement): () => void {
  let cursorOwner: HTMLElement | null = null;

  const targetAt = (event: MouseEvent): HTMLElement | null => {
    if (content.contains(event.target as Node)) return null;
    // Peel sets this while the curled sheet covers the content
    if (content.style.pointerEvents === "none") return null;
    const { clientX: x, clientY: y } = event;
    const box = content.getBoundingClientRect();
    if (x < box.left || x > box.right || y < box.top || y > box.bottom) {
      return null;
    }
    let hit: HTMLElement | null = null;
    // Later matches sit deeper or later in the tree, so the last one wins
    for (const el of content.querySelectorAll<HTMLElement>(INTERACTIVE)) {
      const r = el.getBoundingClientRect();
      if (x >= r.left && x <= r.right && y >= r.top && y <= r.bottom) hit = el;
    }
    return hit;
  };

  const onMove = (event: PointerEvent) => {
    const el = targetAt(event);
    const owner = el ? (event.target as HTMLElement) : null;
    if (cursorOwner && cursorOwner !== owner) cursorOwner.style.cursor = "";
    if (owner && el) owner.style.cursor = getComputedStyle(el).cursor;
    cursorOwner = owner;
  };

  const onClick = (event: MouseEvent) => {
    const el = targetAt(event);
    if (!el) return;
    event.preventDefault();
    event.stopPropagation();
    // Still inside the trusted click, so target="_blank" opens a tab
    el.click();
  };

  document.addEventListener("pointermove", onMove, { passive: true });
  document.addEventListener("click", onClick, true);

  return () => {
    document.removeEventListener("pointermove", onMove);
    document.removeEventListener("click", onClick, true);
    if (cursorOwner) cursorOwner.style.cursor = "";
  };
}
