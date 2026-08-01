import Toastify from "toastify-js";

/**
 * Show new noty message with custom (or default) parameters
 */
export const showNoty = function (
  message: string,
  type = "success",
  timeout = 2500,
  destination: string | undefined = undefined,
  onclick: (() => void) | undefined = undefined,
) {
  const normalizedType = (() => {
    switch (type.toLowerCase()) {
      case "error":
      case "alert":
        return "error";
      case "warning":
        return "warning";
      case "info":
      case "information":
        return "info";
      default:
        return "success";
    }
  })();

  const iconByType = {
    success: "✓",
    warning: "!",
    error: "!",
    info: "i",
  };

  const content = document.createElement("div");
  content.className = "vatger-toast__content";

  const icon = document.createElement("span");
  icon.className = "vatger-toast__icon";
  icon.setAttribute("aria-hidden", "true");
  icon.textContent = iconByType[normalizedType];

  const messageElement = document.createElement("span");
  messageElement.className = "vatger-toast__message";
  messageElement.textContent = message;

  content.append(icon, messageElement);

  Toastify({
    text: message,
    node: content,
    duration: timeout,
    destination: destination,
    newWindow: true,
    close: true,
    gravity: "top",
    position: "right",
    stopOnFocus: true,
    className: `vatger-toast vatger-toast--${normalizedType}`,
    ariaLive:
      normalizedType === "error" || normalizedType === "warning"
        ? "assertive"
        : "polite",
    onClick: onclick,
  }).showToast();
};

export function laravelFireNoty() {
  window.dispatchEvent(new Event("laravel_showNoty"));
}
