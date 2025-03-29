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
  let style = {
    background: "linear-gradient(to right, #00b09b, #96c93d)",
  };
  switch (type) {
    case "error":
      style = {
        background: "linear-gradient(to right, #C93D3D, #Bb1f1f)",
      };
      break;
    case "warning":
      style = {
        background: "linear-gradient(to right, #bb9e1f, #b8a658)",
      };
      break;
    case "":
      style = {
        background: "linear-gradient(to right, #C93D3D, #Bb1f1f)",
      };
      break;
  }
  Toastify({
    text: message,
    duration: timeout,
    destination: destination,
    newWindow: true,
    close: true,
    gravity: "top", // `top` or `bottom`
    position: "right", // `left`, `center` or `right`
    stopOnFocus: true, // Prevents dismissing of toast on hover
    style: style,
    onClick: onclick, // Callback after click
  }).showToast();
};

export function laravelFireNoty() {
  window.dispatchEvent(new Event("laravel_showNoty"));
}
