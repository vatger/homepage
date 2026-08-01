# Public styling

The public website uses Tailwind CSS. `resources/css/app-public.css` is the thin
Tailwind compiler entry; authored styles stay in these SCSS partials:

- `_tokens.scss` contains the branding palette and dark-mode variant.
- `_base.scss` contains document defaults.
- `_layout.scss` contains shared page structure and the temporary grid bridge.
- `_components.scss`, `_forms.scss`, and `_interactive.scss` contain reusable UI.
- `_utilities.scss` maps remaining legacy utility class names to Tailwind while
  their Blade templates are converted.
- `pages/` contains page-specific styles only.

The public and administration areas use separate Tailwind entry points. Shared
tokens and compatibility components live here; admin-only shell and component
styles live in `resources/scss/admin/`. Bootstrap and Landrick are no longer
part of either build.
