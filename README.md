# Header Footer — Simple Plugin for WordPress + Elementor

A lightweight WordPress plugin to build a **global header and footer with Elementor**
and choose which one is live from a single Settings screen.

Developed by Din Mohammed.

## Features

- **Headers & Footers** custom post type — each entry is one Elementor-editable design.
- Tag each design as a **Header** or **Footer** from a side meta box.
- **Settings → Header Footer Builder** — pick the active header and active footer from
  dropdowns of your published designs, with an "Edit with Elementor" shortcut.
- **Sticky Header option** — a checkbox that pins the active header to the top of the
  screen while scrolling (`position: sticky; top: 0; z-index: 9999`).
- Active header is printed on `wp_body_open`; active footer on `wp_footer`.
- Rendered through Elementor when the design was built with it, with a graceful
  `the_content` fallback when Elementor is not active.
- Single template URLs are redirected to the home page so designs aren't reachable
  on their own.

## Requirements

- WordPress
- [Elementor](https://wordpress.org/plugins/elementor/) installed and active

## Installation

1. Copy this folder into `wp-content/plugins/`.
2. Activate **Header Footer** from **Plugins**.
3. Go to **Headers & Footers → Add New**, build your design, set it to Header or
   Footer in the side meta box, and publish.
4. Go to **Settings → Header Footer Builder**, select the active header and footer,
   optionally enable **Sticky Header**, and save.

## Usage notes

- The sticky option applies to the header only. It relies on CSS `position: sticky`,
  so the header wrapper must not sit inside an ancestor with `overflow: hidden`.

## File structure

```
elementor-header-footer.php     Bootstrap: constants, includes, hooks, activation
includes/class-cpt.php          Headers & Footers CPT, type meta box, single redirect
includes/class-settings.php     Settings screen, active header/footer + sticky toggle
includes/class-frontend.php     Renders the active header and footer on the front end
```

## License

GPL-2.0-or-later
