export function getDarkmode(): boolean {
  return document.documentElement.dataset.theme === "dark";
}

export function getLanguage(): "de" | "en" {
  return document
    .querySelector('meta[name="lang"]')
    ?.getAttribute("content") === "de"
    ? "de"
    : "en";
}
