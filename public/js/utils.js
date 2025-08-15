// public/js/utils.js
export function el(tag, className, html) {
  const e = document.createElement(tag);
  if (className) e.className = className;
  if (html !== undefined) e.innerHTML = html;
  return e;
}

export function safeText(t) {
  return (t ?? '').toString();
}

export function formatWhen(ts) {
  try {
    return new Date(ts).toLocaleString();
  } catch {
    return '';
  }
}
