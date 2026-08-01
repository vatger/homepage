import { icons } from "feather-icons";

function renderFeatherIcons() {
  document
    .querySelectorAll<HTMLElement>("[data-feather]")
    .forEach((element) => {
      const name = element.dataset.feather;
      if (!name || !icons[name]) return;

      const className = element.getAttribute("class") ?? "";
      element.insertAdjacentHTML(
        "beforebegin",
        icons[name].toSvg({ class: className }),
      );
      element.remove();
    });
}

let featherRenderQueued = false;

function scheduleFeatherIcons() {
  if (featherRenderQueued) return;
  featherRenderQueued = true;
  queueMicrotask(() => {
    featherRenderQueued = false;
    renderFeatherIcons();
  });
}

function initializeFeatherObserver() {
  const observer = new MutationObserver((mutations) => {
    const hasNewIcons = mutations.some((mutation) =>
      [...mutation.addedNodes].some(
        (node) =>
          node instanceof Element &&
          (node.matches("[data-feather]") ||
            node.querySelector("[data-feather]")),
      ),
    );

    if (hasNewIcons) scheduleFeatherIcons();
  });

  observer.observe(document.body, { childList: true, subtree: true });
}

function selectorFromControl(control: HTMLElement): string | null {
  return control.getAttribute("data-bs-target") ?? control.getAttribute("href");
}

export function showPublicModal(id: string) {
  const modal = document.getElementById(id);
  if (!modal) return;

  modal.style.removeProperty("display");
  modal.classList.add("show");
  modal.removeAttribute("aria-hidden");
  modal.setAttribute("aria-modal", "true");
  document.body.classList.add("modal-open");
  modal
    .querySelector<HTMLElement>("button, [href], input, select, textarea")
    ?.focus();
}

export function hidePublicModal(id: string) {
  const modal = document.getElementById(id);
  if (!modal) return;

  modal.classList.remove("show");
  modal.setAttribute("aria-hidden", "true");
  modal.removeAttribute("aria-modal");

  if (!document.querySelector(".modal.show")) {
    document.body.classList.remove("modal-open");
  }
}

function initializeTheme() {
  const sync = () => {
    const activeTheme =
      document.documentElement.dataset.theme === "dark" ? "dark" : "light";
    document
      .querySelectorAll<HTMLButtonElement>(".theme-toggle-control")
      .forEach((button) => {
        const buttonTheme = button.dataset.themeValue;
        const isActive = buttonTheme
          ? buttonTheme === activeTheme
          : activeTheme === "dark";
        const label = button.dataset.themeLabel;
        button.classList.toggle("is-active", isActive);
        button.setAttribute("aria-pressed", String(isActive));
        if (label) {
          button.setAttribute("aria-label", label);
          button.setAttribute("title", label);
        }
      });
  };

  document
    .querySelectorAll<HTMLButtonElement>(".theme-toggle-control")
    .forEach((button) => {
      button.addEventListener("click", () => {
        const requestedTheme = button.dataset.themeValue;
        const next =
          requestedTheme === "light" || requestedTheme === "dark"
            ? requestedTheme
            : document.documentElement.dataset.theme === "dark"
              ? "light"
              : "dark";
        document.documentElement.dataset.theme = next;
        try {
          window.localStorage?.setItem("vatger-theme", next);
        } catch {
          // Storage can be unavailable in privacy modes; the active page still switches.
        }
        document
          .querySelector('meta[name="color-scheme"]')
          ?.setAttribute("content", next);
        sync();
      });
    });

  sync();
}

const detailsStoragePrefix = "vatger-details:";

function restorePersistedDetails() {
  document
    .querySelectorAll<HTMLDetailsElement>("details[data-persist-details]")
    .forEach((details) => {
      const id = details.dataset.persistDetails;
      if (!id) return;

      try {
        const storedState = window.sessionStorage?.getItem(
          `${detailsStoragePrefix}${id}`,
        );
        if (storedState !== null) details.open = storedState === "open";
      } catch {
        // Session storage can be unavailable; wire:ignore.self still preserves morph state.
      }
    });
}

function initializePersistentDetails() {
  restorePersistedDetails();

  document.addEventListener(
    "toggle",
    (event) => {
      const details = event.target as HTMLDetailsElement;
      if (!details.matches?.("details[data-persist-details]")) return;

      const id = details.dataset.persistDetails;
      if (!id) return;

      try {
        window.sessionStorage?.setItem(
          `${detailsStoragePrefix}${id}`,
          details.open ? "open" : "closed",
        );
      } catch {
        // The disclosure remains functional when storage is unavailable.
      }
    },
    true,
  );

  window.addEventListener("featherReplace", () =>
    queueMicrotask(restorePersistedDetails),
  );
}

function initializeLegacyControls() {
  document.addEventListener("click", (event) => {
    const target = event.target as HTMLElement;
    const control = target.closest<HTMLElement>(
      "[data-bs-toggle], [data-bs-dismiss]",
    );
    if (!control) return;

    const toggle = control.dataset.bsToggle;
    const dismiss = control.dataset.bsDismiss;

    if (toggle === "modal") {
      const selector = selectorFromControl(control);
      if (selector?.startsWith("#")) showPublicModal(selector.slice(1));
    }

    if (dismiss === "modal") {
      const modal = control.closest<HTMLElement>(".modal");
      if (modal?.id) hidePublicModal(modal.id);
    }

    if (dismiss === "alert") control.closest(".alert")?.remove();

    if (toggle === "collapse") {
      const selector = selectorFromControl(control);
      const panel = selector
        ? document.querySelector<HTMLElement>(selector)
        : null;
      panel?.classList.toggle("show");
      control.setAttribute(
        "aria-expanded",
        String(panel?.classList.contains("show")),
      );
    }

    if (toggle === "dropdown") {
      control.nextElementSibling?.classList.toggle("show");
    }

    if (toggle === "pill" || toggle === "tab") {
      const selector = selectorFromControl(control);
      const tab = selector
        ? document.querySelector<HTMLElement>(selector)
        : null;
      const tabList = control.closest("[role='tablist'], .nav");
      tabList
        ?.querySelectorAll(".active")
        .forEach((item) => item.classList.remove("active"));
      control.classList.add("active");
      tab?.parentElement
        ?.querySelectorAll(":scope > .tab-pane")
        .forEach((item) => item.classList.remove("show", "active"));
      tab?.classList.add("show", "active");
    }
  });

  document.addEventListener("keydown", (event) => {
    if (event.key !== "Escape") return;
    document
      .querySelectorAll<HTMLElement>(".modal.show")
      .forEach((modal) => hidePublicModal(modal.id));
    document
      .querySelectorAll(".dropdown-menu.show")
      .forEach((menu) => menu.classList.remove("show"));
  });
}

export function initializePublicUi() {
  initializeTheme();
  initializePersistentDetails();
  initializeLegacyControls();
  renderFeatherIcons();
  initializeFeatherObserver();
  window.addEventListener("featherReplace", scheduleFeatherIcons);
}
