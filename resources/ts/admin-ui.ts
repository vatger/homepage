export function initializeAdminUi() {
  const sidebar = document.getElementById("admin-sidebar");
  const toggle = document.getElementById("admin-sidebar-toggle");
  const backdrop = document.getElementById("admin-sidebar-backdrop");

  const setOpen = (open: boolean) => {
    sidebar?.classList.toggle("is-open", open);
    backdrop?.classList.toggle("is-open", open);
    toggle?.setAttribute("aria-expanded", String(open));
    document.body.classList.toggle("overflow-hidden", open);
  };

  toggle?.addEventListener("click", () =>
    setOpen(!sidebar?.classList.contains("is-open")),
  );
  backdrop?.addEventListener("click", () => setOpen(false));
  sidebar
    ?.querySelectorAll("a")
    .forEach((link) => link.addEventListener("click", () => setOpen(false)));

  window.addEventListener("resize", () => {
    if (window.innerWidth >= 1024) setOpen(false);
  });
}
